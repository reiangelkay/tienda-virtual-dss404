<?php
// 1. Incluir el motor de la base de datos
require_once 'conexion.php';

$mensaje = "";
$tipo_mensaje = ""; // Para darle color al mensaje (verde o rojo)

// 2. Verificar si el usuario envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Limpiar los datos entrantes (No le hacemos trim a la contraseña por seguridad)
    $nombre_completo = trim($_POST['nombre_completo'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    // Validación estricta del lado del servidor
    if (empty($nombre_completo) || empty($correo) || empty($contrasena)) {
        $mensaje = "Todos los campos son obligatorios.";
        $tipo_mensaje = "red";
        
    // Validar que el nombre SOLO contenga letras y espacios (incluye acentos y la ñ)
    } elseif (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre_completo)) {
        $mensaje = "Error: El nombre solo debe contener letras y espacios.";
        $tipo_mensaje = "red";
        
    // Validar el formato oficial de un correo electrónico
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "El formato del correo no es válido.";
        $tipo_mensaje = "red";
        
    // Validar que la contraseña sea robusta (mínimo 6 caracteres)
    } elseif (strlen($contrasena) < 6) {
        $mensaje = "Error: La contraseña debe tener al menos 6 caracteres.";
        $tipo_mensaje = "red";
        
    } else {
        // Si pasa TODAS las validaciones, procedemos a guardar en la base de datos
        try {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $pdo = Database::connect();
            $sql = "INSERT INTO clientes (nombre_completo, correo, contrasena) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre_completo, $correo, $hash]);

            $mensaje = "¡Registro exitoso! Ya puedes iniciar sesión en la tienda.";
            $tipo_mensaje = "green";

        } catch (PDOException $e) {
            // Evaluamos el código exacto del error, pero mostramos texto amigable
            if ($e->getCode() == 23000) {
                $mensaje = "Ese correo electrónico ya está registrado. Por favor, intenta iniciar sesión.";
            } elseif ($e->getCode() == 1045 || $e->getCode() == 2002) {
                $mensaje = "Nuestros servidores están en mantenimiento. Intenta de nuevo en unos minutos.";
            } else {
                // Un error desconocido genérico que no expone código SQL
                $mensaje = "Ocurrió un problema inesperado al crear tu cuenta. Intenta más tarde.";
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
        <div class="mensaje" style="color: <?php echo $tipo_mensaje; ?>; border: 1px solid <?php echo $tipo_mensaje; ?>; background-color: <?php echo $tipo_mensaje === 'red' ? '#ffe6e6' : '#e6ffe6'; ?>;">
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