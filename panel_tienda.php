<?php
// 1. Siempre iniciar la sesión antes de cualquier código HTML
session_start();

// 2. El "Guardia de Seguridad"
// Si la variable 'logeado' no existe o no es true, lo expulsamos al login
if (!isset($_SESSION['logeado']) || $_SESSION['logeado'] !== true) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Panel - Tienda Virtual</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; }
        .navbar { background-color: #0056b3; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: white; text-decoration: none; font-weight: bold; background-color: #dc3545; padding: 8px 15px; border-radius: 4px; }
        .navbar a:hover { background-color: #c82333; }
        .contenedor { max-width: 800px; margin: 40px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .tabla-compras { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .tabla-compras th, .tabla-compras td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .tabla-compras th { background-color: #f8f9fa; color: #333; }
    </style>
</head>
<body>

    <div class="navbar">
        <div>Tienda Virtual DSS404</div>
        <a href="logout.php">Cerrar Sesión</a>
    </div>

    <div class="contenedor">
        <h1>¡Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_completo']); ?>!</h1>
        <p>Este es el resumen de tu cuenta y tus últimos movimientos.</p>

        <h2>Mis Compras Recientes</h2>
        <table class="tabla-compras">
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Producto</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>#10045</td>
                    <td>Teclado Mecánico RGB</td>
                    <td>12/04/2026</td>
                    <td><span style="color: green; font-weight: bold;">Entregado</span></td>
                </tr>
                <tr>
                    <td>#10089</td>
                    <td>Monitor Curvo 27"</td>
                    <td>14/04/2026</td>
                    <td><span style="color: orange; font-weight: bold;">En camino</span></td>
                </tr>
            </tbody>
        </table>
    </div>

</body>
</html>