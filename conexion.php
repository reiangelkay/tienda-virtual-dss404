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
                die("Error crítico de conexión a la tienda: " . $e->getMessage());
            }
        }
        return self::$cont;
    }

    public static function disconnect() {
        self::$cont = null;
    }
}
?>