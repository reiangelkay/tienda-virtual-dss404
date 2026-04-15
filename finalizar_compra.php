<?php
session_start();
require_once 'conexion.php';

// 1. Guardia de Seguridad
if (!isset($_SESSION['logeado']) || $_SESSION['logeado'] !== true) {
    header("Location: login.php");
    exit();
}

// 2. Si intentan entrar aquí con el carrito vacío, los regresamos
if (!isset($_SESSION['carrito']) || count($_SESSION['carrito']) == 0) {
    header("Location: catalogo.php");
    exit();
}

$id_cliente = $_SESSION['id_cliente'];
$mensaje_exito = "";
$mensaje_error = "";

try {
    $pdo = Database::connect();
    
    // 3. INICIAR TRANSACCIÓN: "O se guarda todo perfecto, o no se guarda nada"
    $pdo->beginTransaction();

    // Preparamos las sentencias SQL una sola vez por eficiencia
    $stmtProducto = $pdo->prepare("SELECT nombre, precio, stock FROM productos WHERE id_producto = ?");
    $stmtCompra = $pdo->prepare("INSERT INTO compras (id_cliente, producto, precio) VALUES (?, ?, ?)");
    $stmtStock = $pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id_producto = ?");

    // 4. Recorrer el carrito y procesar
    foreach ($_SESSION['carrito'] as $id_producto => $cantidad) {
        
        $stmtProducto->execute([$id_producto]);
        $producto_db = $stmtProducto->fetch(PDO::FETCH_ASSOC);

        // Verificamos que exista y tenga stock suficiente
        if ($producto_db && $producto_db['stock'] >= $cantidad) {
            
            // Calculamos el total de esa línea
            $subtotal = $producto_db['precio'] * $cantidad;
            // Guardamos el nombre y la cantidad para el historial (ej. "Gundam Aerial (x2)")
            $nombre_historial = $producto_db['nombre'] . " (x" . $cantidad . ")";

            // Registrar en la tabla de compras del cliente
            $stmtCompra->execute([$id_cliente, $nombre_historial, $subtotal]);

            // Descontar las unidades del inventario principal
            $stmtStock->execute([$cantidad, $id_producto]);

        } else {
            // Si no hay stock, disparamos un error intencional para frenar el proceso
            throw new Exception("Lo sentimos, no hay stock suficiente para " . $producto_db['nombre']);
        }
    }

    // 5. CONFIRMAR TRANSACCIÓN: Todo salió bien, guardamos definitivamente
    $pdo->commit();

    // 6. Destruir el carrito de la sesión
    unset($_SESSION['carrito']);
    
    $mensaje_exito = "¡Operación exitosa! Tus Gunplas han sido asignados y están en preparación.";

} catch (Exception $e) {
    // 7. ROLLBACK: Algo falló. Revertimos cualquier cambio a medias en la base de datos
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $mensaje_error = $e->getMessage();
} finally {
    Database::disconnect();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra Finalizada - Gundam Store</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; padding-top: 100px; }
        .tarjeta { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 500px; text-align: center; }
        .exito { color: #27ae60; font-size: 60px; margin: 0; }
        .error { color: #e74c3c; font-size: 60px; margin: 0; }
        h2 { color: #333; margin-top: 10px; }
        p { color: #666; font-size: 16px; line-height: 1.5; }
        .btn { display: inline-block; background-color: #0056b3; color: white; padding: 12px 25px; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 20px; }
        .btn:hover { background-color: #004494; }
    </style>
</head>
<body>

<div class="tarjeta">
    <?php if ($mensaje_exito): ?>
        <div class="exito">✅</div>
        <h2>¡Pago Procesado!</h2>
        <p><?php echo $mensaje_exito; ?></p>
        <a href="panel_tienda.php" class="btn">Ver mi historial de compras</a>
    <?php else: ?>
        <div class="error">⚠️</div>
        <h2>Error en la transacción</h2>
        <p><?php echo $mensaje_error; ?></p>
        <a href="carrito.php" class="btn">Volver al Carrito</a>
    <?php endif; ?>
</div>

</body>
</html>