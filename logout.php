<?php
// 1. Retomar la sesión actual
session_start();

// 2. Vaciar todas las variables de sesión
$_SESSION = array();

// 3. Destruir la sesión por completo en el servidor
session_destroy();

// 4. Redirigir de vuelta a la página de login
header("Location: login.php");
exit();
?>