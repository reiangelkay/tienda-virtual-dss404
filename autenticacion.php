<?php
// 1. Iniciar el motor de sesiones SIEMPRE en la línea 1
session_start();

require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $correo = trim($_POST['correo'] ?? '');
    $contrasena_ingresada = trim($_POST['contrasena'] ?? '');

    // Validación básica preventiva
    if (empty($correo) || empty($contrasena_ingresada)) {
        header("Location: login.php?error=1");
        exit(); // Detenemos el script por seguridad
    }

    try {
        $pdo = Database::connect();

        // 2. Buscar al usuario SOLO por su correo
        $sql = "SELECT id_cliente, nombre_completo, contrasena FROM clientes WHERE correo = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$correo]);
        
        // Extraemos el registro como un array asociativo
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // 3. El Momento de la Verdad: Verificar el Hash
        // Si el usuario existe Y la contraseña coincide con el hash guardado
        if ($usuario && password_verify($contrasena_ingresada, $usuario['contrasena'])) {
            
            // ¡ÉXITO! Creamos las variables de sesión que pide el desafío
            $_SESSION['id_cliente'] = $usuario['id_cliente'];
            $_SESSION['nombre_completo'] = $usuario['nombre_completo'];
            $_SESSION['logeado'] = true;

            // Redirigimos a la página principal de la tienda
            header("Location: panel_tienda.php");
            exit();

        } else {
            // FALLÓ: Redirigimos de vuelta al login con la bandera de error
            header("Location: login.php?error=1");
            exit();
        }

    } catch (PDOException $e) {
        // En un entorno real, no mostramos el error de BD al usuario, solo lo redirigimos
        header("Location: login.php?error=1");
        exit();
    } finally {
        Database::disconnect();
    }
} else {
    // Si alguien intenta entrar a este archivo escribiendo la URL directamente
    header("Location: login.php");
    exit();
}
?>