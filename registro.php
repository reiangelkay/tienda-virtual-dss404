<?php
require_once 'conexion.php';

$mensaje = "";
$tipo_mensaje = ""; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
   
    $nombre_completo = trim($_POST['nombre_completo'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');

   
    if (empty($nombre_completo) || empty($correo) || empty($contrasena)) {
        $mensaje = "Todos los campos son obligatorios.";
        $tipo_mensaje = "red";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "El formato del correo no es válido.";
        $tipo_mensaje = "red";
    } else {
        try {
           
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);

           
            $pdo = Database::connect();

           
            $sql = "INSERT INTO clientes (nombre_completo, correo, contrasena) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            
           
            $stmt->execute([$nombre_completo, $correo, $hash]);

            $mensaje = "¡Registro exitoso! Ya puedes iniciar sesión en la tienda.";
            $tipo_mensaje = "green";

        } catch (PDOException $e) {
            
            if ($e->getCode() == 23000) {
                $mensaje = "Este correo ya está registrado. Intenta iniciar sesión.";
            } else {
                $mensaje = "Error en el servidor: " . $e->getMessage();
            }
            $tipo_mensaje = "red";
        } finally {
            Database::disconnect();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Tienda Virtual</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; padding-top: 50px; }
        .contenedor { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #666; }
        input[type="text"], input[type="email"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #0056b3; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        button:hover { background-color: #004494; }
        .mensaje { padding: 10px; margin-bottom: 15px; border-radius: 4px; text-align: center; font-weight: bold; }
        .link-login { display: block; text-align: center; margin-top: 15px; text-decoration: none; color: #0056b3; }
    </style>
</head>
<body>

<div class="contenedor">
    <h2>Crear Cuenta</h2>

    <?php if ($mensaje): ?>
        <div class="mensaje" style="color: <?php echo $tipo_mensaje; ?>; border: 1px solid <?php echo $tipo_mensaje; ?>;">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <form action="registro.php" method="POST">
        <div class="form-group">
            <label for="nombre_completo">Nombre Completo</label>
            <input type="text" id="nombre_completo" name="nombre_completo" required>
        </div>

        <div class="form-group">
            <label for="correo">Correo Electrónico</label>
            <input type="email" id="correo" name="correo" required>
        </div>

        <div class="form-group">
            <label for="contrasena">Contraseña</label>
            <input type="password" id="contrasena" name="contrasena" required minlength="6">
        </div>

        <button type="submit">Registrarme</button>
    </form>

    <a href="login.php" class="link-login">¿Ya tienes cuenta? Inicia sesión aquí</a>
</div>

</body>
</html>