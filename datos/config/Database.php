<?php namespace Iglesia\Datos\Config;

class Database {
    private static $host = 'localhost';
    private static $base_datos = 'iglesia_db';
    private static $usuario = 'root';
    private static $password = 'juniorzamo1999';
    private static $conexion;
    
    /**
     
     * @return PDO 
     */
    public static function obtenerConexion() {
        try {
       
            if (self::$conexion) {
                return self::$conexion;
            }
            
           
            $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$base_datos . ";charset=utf8mb4";
            
       
            $opciones = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ];
            
            self::$conexion = new \PDO($dsn, self::$usuario, self::$password, $opciones);
            
            return self::$conexion;
        } catch (\PDOException $e) {
           
            error_log('Error de conexión a la base de datos: ' . $e->getMessage());
            
         
            throw new \Exception('No se pudo conectar a la base de datos. Por favor, intente más tarde.');
        }
    }
    
 
    public static function cerrarConexion() {
        self::$conexion = null;
    }
}