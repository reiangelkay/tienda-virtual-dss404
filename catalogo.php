<?php
session_start();
require_once 'conexion.php';

// Seguridad: Solo pilotos logeados
if (!isset($_SESSION['logeado']) || $_SESSION['logeado'] !== true) {
    header("Location: login.php");
    exit();
}

// Lógica del Carrito
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_id'])) {
    $id_producto = $_POST['agregar_id'];
    
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = array();
    }
    
    if (isset($_SESSION['carrito'][$id_producto])) {
        $_SESSION['carrito'][$id_producto] += 1;
    } else {
        $_SESSION['carrito'][$id_producto] = 1;
    }
    
    $mensaje = "¡Mobile Suit agregado al carrito de despliegue!";
}

// Cargar inventario
try {
    $pdo = Database::connect();
    $stmt = $pdo->query("SELECT * FROM productos WHERE stock > 0");
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexión con el Hangar: " . $e->getMessage());
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
        body { font-family: 'Segoe UI', sans-serif; background: #e9ecef; margin: 0; }
        .nav { background: #1a1a1a; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2);}
        .nav a { color: #f1c40f; text-decoration: none; margin-left: 20px; font-weight: bold; transition: 0.2s;}
        .nav a:hover { color: #fff; }
        
        /* Ajuste fino para la cuadrícula */
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; padding: 30px; max-width: 1200px; margin: 0 auto;}
        
        .tarjeta { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center; transition: transform 0.2s; border-bottom: 4px solid transparent;}
        .tarjeta:hover { transform: translateY(-5px); border-bottom: 4px solid #3498db; }
        
        /* Contenedor de la imagen para que todas midan lo mismo */
        .img-container { width: 100%; height: 250px; overflow: hidden; border-radius: 6px; margin-bottom: 15px; background: #f8f9fa; border: 1px solid #ddd;}
        .img-container img { width: 100%; height: 100%; object-fit: cover; }
        
        .btn-agregar { background: #e74c3c; color: white; border: none; padding: 12px 15px; border-radius: 6px; cursor: pointer; width: 100%; margin-top: 15px; font-weight: bold; font-size: 16px; transition: background 0.2s;}
        .btn-agregar:hover { background: #c0392b; }
        
        .alerta { background: #d4edda; color: #155724; padding: 15px; text-align: center; margin: 20px auto; max-width: 1160px; border-radius: 6px; border-left: 5px solid #28a745; font-weight: bold;}
        .badge { background: #e74c3c; color: white; border-radius: 50%; padding: 3px 8px; font-size: 12px; vertical-align: top; margin-left: 5px;}
    </style>
</head>
<body>

<div class="nav">
    <div>
        <strong>GUNPLA STORE DSS404</strong> | Piloto Autorizado: <?php echo htmlspecialchars($_SESSION['nombre_completo']); ?>
    </div>
    <div>
        <a href="carrito.php">
            🛒 Carrito 
            <?php if(isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0): ?>
                <span class="badge"><?php echo count($_SESSION['carrito']); ?></span>
            <?php endif; ?>
        </a>
        <a href="panel_tienda.php">Mi Base de Datos</a>
        <a href="logout.php" style="color: #e74c3c;">Desconectar</a>
    </div>
</div>

<?php if(isset($mensaje)): ?>
    <div class="alerta"><?php echo $mensaje; ?></div>
<?php endif; ?>

<div class="grid">
    <?php foreach ($productos as $p): ?>
        <div class="tarjeta">
            <div class="img-container">
                <img src="img/<?php echo htmlspecialchars($p['imagen']); ?>" alt="<?php echo htmlspecialchars($p['nombre']); ?>">
            </div>
            
            <h3 style="margin: 0 0 5px 0; color: #333; font-size: 20px;"><?php echo htmlspecialchars($p['nombre']); ?></h3>
            <p style="color: #7f8c8d; font-size: 14px; margin: 0 0 15px 0; font-weight: bold;">[<?php echo htmlspecialchars($p['grado']); ?>]</p>
            
            <p style="font-size: 26px; font-weight: 900; color: #2c3e50; margin: 10px 0;">$<?php echo number_format($p['precio'], 2); ?></p>
            
            <?php if($p['stock'] <= 5): ?>
                <p style="font-size: 13px; color: #e74c3c; font-weight: bold; margin: 5px 0;">¡Solo quedan <?php echo $p['stock']; ?> unidades!</p>
            <?php else: ?>
                <p style="font-size: 13px; color: #27ae60; font-weight: bold; margin: 5px 0;">Stock disponible: <?php echo $p['stock']; ?></p>
            <?php endif; ?>
            
            <form action="catalogo.php" method="POST">
                <input type="hidden" name="agregar_id" value="<?php echo $p['id_producto']; ?>">
                <button type="submit" class="btn-agregar">➕ Agregar al Carrito</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>