<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Tienda Virtual</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; padding-top: 50px; }
        .contenedor { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #666; }
        input[type="email"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        button:hover { background-color: #218838; }
        .mensaje-error { background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 4px; text-align: center; border: 1px solid #f5c6cb; }
        .link-registro { display: block; text-align: center; margin-top: 15px; text-decoration: none; color: #0056b3; }
    </style>
</head>
<body>

<div class="contenedor">
    <h2>Bienvenido de nuevo</h2>

    <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
        <div class="mensaje-error">
            Correo o contraseña incorrectos.
        </div>
    <?php endif; ?>

    <form action="autenticacion.php" method="POST">
        <div class="form-group">
            <label for="correo">Correo Electrónico</label>
            <input type="email" id="correo" name="correo" required>
        </div>

        <div class="form-group">
            <label for="contrasena">Contraseña</label>
            <input type="password" id="contrasena" name="contrasena" required>
        </div>

        <button type="submit">Entrar a la tienda</button>
    </form>

    <a href="registro.php" class="link-registro">¿No tienes cuenta? Regístrate aquí</a>
</div>

</body>
</html>