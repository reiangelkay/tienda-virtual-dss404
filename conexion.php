<?php
// conexion.php
class Database {
    private static $dbHost = 'localhost';
    private static $dbName = 'tienda_virtual'; // Apunta exactamente a la BD de su desafío
    private static $dbUsername = 'root';
    private static $dbUserPassword = ''; // En XAMPP suele ir vacío
    private static $cont = null;

    public static function connect() {
        if (null == self::$cont) {
            try {
                // Se establece la conexión con PDO, requisito clave de DSS404
                self::$cont = new PDO(
                    "mysql:host=".self::$dbHost.";dbname=".self::$dbName.";charset=utf8", 
                    self::$dbUsername, 
                    self::$dbUserPassword
                );
                // Activar el modo de excepciones para capturar errores fácilmente
                self::$cont->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           } catch (PDOException $e) {
                // En lugar de die("Error: " . $e->getMessage()); usamos un mensaje limpio
                die("
                    <div style='text-align:center; padding: 50px; font-family: sans-serif;'>
                        <h2 style='color:#e74c3c;'>Problema de Conexión</h2>
                        <p>No podemos acceder al Hangar de Gunplas en este momento.</p>
                        <p>Por favor, avisa al equipo de soporte o intenta de nuevo más tarde.</p>
                    </div>
                ");
            }
        }
        return self::$cont;
    }

    public static function disconnect() {
        self::$cont = null;
    }
}
?>