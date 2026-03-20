<?php
/**
 * Clase Database - Manejo de conexion a base de datos
 * Inventio Max v9.0
 * Actualizado para compatibilidad con PHP 8.x
 * 
 * Configuracion:
 * - Modifica las credenciales directamente en esta clase, o
 * - Crea un archivo config/database.php con las credenciales
 */
class Database {
	public static $db;
	public static $con;
	
	// Propiedades de conexion
	private $user;
	private $pass;
	private $host;
	private $ddbb;
	private $port;
	
	function __construct(){
		// Intentar cargar configuracion desde archivo externo
		$config_file = ROOT . '/config/database.php';
		
		if(file_exists($config_file)){
			$config = include($config_file);
			$this->host = $config['host'] ?? 'localhost';
			$this->user = $config['user'] ?? 'root';
			$this->pass = $config['pass'] ?? '';
			$this->ddbb = $config['database'] ?? 'inventiomax';
			$this->port = $config['port'] ?? 3306;
		} else {
			// Configuracion por defecto - MODIFICA ESTOS VALORES
			$this->host = "localhost";
			$this->user = "root";
			$this->pass = "";
			$this->ddbb = "inventiomax";
			$this->port = 3306;
		}
	}

	/**
	 * Establecer conexion a la base de datos
	 * @return mysqli|false Objeto de conexion o false en caso de error
	 */
	function connect(){
		try {
			$con = new mysqli($this->host, $this->user, $this->pass, $this->ddbb, $this->port);
			
			// Verificar errores de conexion
			if ($con->connect_error) {
				error_log("Error de conexion MySQL: " . $con->connect_error);
				return false;
			}
			
			// Configurar modo SQL y charset
			$con->query("SET sql_mode='';");
			$con->set_charset("utf8mb4");
			
			return $con;
		} catch (Exception $e) {
			error_log("Excepcion al conectar a MySQL: " . $e->getMessage());
			return false;
		}
	}

	/**
	 * Obtener la conexion singleton
	 * @return mysqli|null Objeto de conexion o null si falla
	 */
	public static function getCon(){
		if(self::$con == null || self::$db == null){
			self::$db = new Database();
			self::$con = self::$db->connect();
		}
		
		// Verificar que la conexion siga activa
		if(self::$con && !self::$con->ping()){
			self::$con = self::$db->connect();
		}
		
		return self::$con;
	}
	
	/**
	 * Cerrar la conexion a la base de datos
	 */
	public static function close(){
		if(self::$con != null){
			self::$con->close();
			self::$con = null;
			self::$db = null;
		}
	}
	
	/**
	 * Escapar cadenas para prevenir SQL Injection
	 * @param string $str Cadena a escapar
	 * @return string Cadena escapada
	 */
	public static function escape($str){
		$con = self::getCon();
		if($con){
			return $con->real_escape_string($str);
		}
		return addslashes($str);
	}
}
?>
