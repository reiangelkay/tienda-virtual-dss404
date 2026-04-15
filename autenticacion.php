<?php

session_start();

require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $correo = trim($_POST['correo'] ?? '');
    $contrasena_ingresada = trim($_POST['contrasena'] ?? '');

   
    if (empty($correo) || empty($contrasena_ingresada)) {
        header("Location: login.php?error=1");
        exit(); 
    }

    try {
        $pdo = Database::connect();

        
        $sql = "SELECT id_cliente, nombre_completo, contrasena FROM clientes WHERE correo = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$correo]);
        
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($usuario && password_verify($contrasena_ingresada, $usuario['contrasena'])) {
            
            $_SESSION['id_cliente'] = $usuario['id_cliente'];
            $_SESSION['nombre_completo'] = $usuario['nombre_completo'];
            $_SESSION['logeado'] = true;

            header("Location: panel_tienda.php");
            exit();

        } else {
            header("Location: login.php?error=1");
            exit();
        }

    } catch (PDOException $e) {
        header("Location: login.php?error=1");
        exit();
    } finally {
        Database::disconnect();
    }
} else {
    header("Location: login.php");
    exit();
}
?>