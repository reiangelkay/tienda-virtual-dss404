<?php
session_start();
require_once 'conexion.php';

// Si no está logeado, lo expulsamos
if (!isset($_SESSION['logeado']) || $_SESSION['logeado'] !== true) {
    header("Location: login.php");
    exit();
}

$compras = [];
try {
    $pdo = Database::connect();
    // Buscamos SOLO las compras del piloto logeado
    $sql = "SELECT producto, precio, fecha_compra FROM compras WHERE id_cliente = ? ORDER BY fecha_compra DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['id_cliente']]);
    $compras = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al cargar el historial: " . $e->getMessage());
} finally {
    Database::disconnect();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Panel - Gunpla Store</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; margin: 0; }
        .navbar { background-color: #1a1a1a; color: white; padding: 15px 30px; display: flex; justify-content: space-between; }
        .navbar a { color: #f1c40f; text-decoration: none; font-weight: bold; margin-left: 15px; }
        .contenedor { max-width: 800px; margin: 40px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .tabla-compras { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .tabla-compras th, .tabla-compras td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .tabla-compras th { background-color: #f8f9fa; }
    </style>
</head>
<body>

    <div class="navbar">
        <div>Base de Operaciones DSS404</div>
        <div>
            <a href="catalogo.php">Ir a la Tienda</a>
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </div>

    <div class="contenedor">
        <h1>¡Bienvenido, Piloto <?php echo htmlspecialchars($_SESSION['nombre_completo']); ?>!</h1>
        
        <h2>Tu Historial de Despliegue (Compras)</h2>
        
        <?php if (empty($compras)): ?>
            <p>Aún no has adquirido ningún Mobile Suit. ¡Visita la tienda!</p>
        <?php else: ?>
            <table class="tabla-compras">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Monto Pagado</th>
                        <th>Fecha de Adquisición</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($compras as $c): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($c['producto']); ?></strong></td>
                        <td>$<?php echo number_format($c['precio'], 2); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($c['fecha_compra'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</body>
</html>