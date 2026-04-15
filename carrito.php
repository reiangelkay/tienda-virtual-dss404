<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['logeado']) || $_SESSION['logeado'] !== true) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['eliminar'])) {
    $id_eliminar = $_GET['eliminar'];
    unset($_SESSION['carrito'][$id_eliminar]);
    header("Location: carrito.php");
    exit();
}

if (isset($_GET['vaciar'])) {
    unset($_SESSION['carrito']);
    header("Location: carrito.php");
    exit();
}

$productos_carrito = [];
$total_compra = 0;

if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
    try {
        $pdo = Database::connect();
        
        $ids = array_keys($_SESSION['carrito']);
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        
        $sql = "SELECT * FROM productos WHERE id_producto IN ($placeholders)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($ids);
        $productos_carrito = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        die("Error al cargar el carrito: " . $e->getMessage());
    } finally {
        Database::disconnect();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tu Carrito - Gundam Store</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f9f9f9; margin: 0; }
        .nav { background: #1a1a1a; color: white; padding: 15px 30px; display: flex; justify-content: space-between; }
        .nav a { color: #f1c40f; text-decoration: none; margin-left: 15px; }
        .contenedor { max-width: 900px; margin: 40px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { text-align: left; border-bottom: 2px solid #eee; padding: 10px; }
        td { padding: 15px 10px; border-bottom: 1px solid #eee; }
        .total-row { font-size: 20px; font-weight: bold; text-align: right; }
        .btn { padding: 10px 20px; border-radius: 4px; text-decoration: none; display: inline-block; cursor: pointer; border: none; }
        .btn-pagar { background: #27ae60; color: white; font-size: 18px; }
        .btn-vaciar { background: #95a5a6; color: white; font-size: 14px; }
        .btn-eliminar { color: #e74c3c; font-weight: bold; }
        .carrito-vacio { text-align: center; padding: 50px; color: #7f8c8d; }
    </style>
</head>
<body>

<div class="nav">
    <div><strong>GUNPLA STORE DSS404</strong> | Carrito de <?php echo htmlspecialchars($_SESSION['nombre_completo']); ?></div>
    <div>
        <a href="catalogo.php">← Volver al Catálogo</a>
        <a href="logout.php">Salir</a>
    </div>
</div>

<div class="contenedor">
    <h1>Tu Carrito de Compras</h1>

    <?php if (empty($productos_carrito)): ?>
        <div class="carrito-vacio">
            <p style="font-size: 50px;">🛒</p>
            <h3>Tu carrito está vacío.</h3>
            <p>¡Ve al catálogo y elige tu próximo proyecto de ensamblaje!</p>
            <br>
            <a href="catalogo.php" class="btn" style="background: #0056b3; color: white;">Ver Gundams disponibles</a>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Modelo</th>
                    <th>Precio Unit.</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos_carrito as $p): 
                    $cantidad = $_SESSION['carrito'][$p['id_producto']];
                    $subtotal = $p['price'] * $cantidad; 
                    $total_compra += $subtotal;
                ?>
                <tr>
                    <td>
                        <strong><?php echo htmlspecialchars($p['nombre']); ?></strong><br>
                        <small style="color: #666;"><?php echo htmlspecialchars($p['grado']); ?></small>
                    </td>
                    <td>$<?php echo number_format($p['precio'], 2); ?></td>
                    <td><?php echo $cantidad; ?></td>
                    <td>$<?php echo number_format($subtotal, 2); ?></td>
                    <td>
                        <a href="carrito.php?eliminar=<?php echo $p['id_producto']; ?>" class="btn-eliminar">Quitar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="margin-top: 30px; display: flex; justify-content: space-between; align-items: center;">
            <a href="carrito.php?vaciar=1" class="btn btn-vaciar" onclick="return confirm('¿Seguro que quieres vaciar el carrito?')">Vaciar Carrito</a>
            
            <div style="text-align: right;">
                <p class="total-row">Total a Pagar: $<?php echo number_format($total_compra, 2); ?></p>
                <br>
                <a href="finalizar_compra.php" class="btn btn-pagar">Proceder al Pago 💳</a>
            </div>
        </div>
    <?php endif; ?>
</div>

</body>
</html>