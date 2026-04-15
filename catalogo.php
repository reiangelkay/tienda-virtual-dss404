<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['logeado']) || $_SESSION['logeado'] !== true) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_id'])) {
    $id_producto = $_POST['agregar_id'];
    $cantidad = 1; 
    
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = array();
    }
    
    if (isset($_SESSION['carrito'][$id_producto])) {
        $_SESSION['carrito'][$id_producto] += $cantidad;
    } else {
       
        $_SESSION['carrito'][$id_producto] = $cantidad;
    }
    
    $mensaje = "¡Gundam agregado al carrito!";
}

try {
    $pdo = Database::connect();
    $stmt = $pdo->query("SELECT * FROM productos WHERE stock > 0");
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al cargar los Gunplas: " . $e->getMessage());
} finally {
    Database::disconnect();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo Gunpla - Tienda Virtual</title>
    <style>
        body { font-family: sans-serif; background: #f0f0f0; margin: 0; }
        .nav { background: #1a1a1a; color: white; padding: 15px; display: flex; justify-content: space-between; align-items: center;}
        .nav a { color: #f1c40f; text-decoration: none; margin-left: 20px; font-weight: bold;}
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; padding: 20px; }
        .tarjeta { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center; }
        .btn-agregar { background: #e74c3c; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; width: 100%; margin-top: 10px; font-weight: bold;}
        .btn-agregar:hover { background: #c0392b; }
        .alerta { background: #d4edda; color: #155724; padding: 10px; text-align: center; margin: 10px 20px; border-radius: 4px;}
        .badge { background: red; color: white; border-radius: 50%; padding: 2px 8px; font-size: 12px; vertical-align: top; }
    </style>
</head>
<body>

<div class="nav">
    <div>
        <strong>GUNPLA STORE DSS404</strong> | Piloto: <?php echo htmlspecialchars($_SESSION['nombre_completo']); ?>
    </div>
    <div>
        <a href="carrito.php">
            🛒 Ver Carrito 
            <?php if(isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0): ?>
                <span class="badge"><?php echo count($_SESSION['carrito']); ?></span>
            <?php endif; ?>
        </a>
        <a href="panel_tienda.php">Mi Panel</a>
        <a href="logout.php">Desconectar</a>
    </div>
</div>

<?php if(isset($mensaje)): ?>
    <div class="alerta"><?php echo $mensaje; ?></div>
<?php endif; ?>

<div class="grid">
    <?php foreach ($productos as $p): ?>
        <div class="tarjeta">
            <h3 style="margin-top:0;"><?php echo htmlspecialchars($p['nombre']); ?></h3>
            <p style="color: #666; font-size: 14px;">[<?php echo htmlspecialchars($p['grado']); ?>]</p>
            <p style="font-size: 22px; font-weight: bold; color: #2c3e50;">$<?php echo number_format($p['precio'], 2); ?></p>
            <p style="font-size: 12px; color: green;">En stock: <?php echo $p['stock']; ?> unidades</p>
            
            <form action="catalogo.php" method="POST">
                <input type="hidden" name="agregar_id" value="<?php echo $p['id_producto']; ?>">
                <button type="submit" class="btn-agregar">➕ Añadir al Carrito</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>