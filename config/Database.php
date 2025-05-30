<?php
require_once __DIR__ . '/config.php';

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private static $instance = null;
    private $conn;

    private function __construct() {
        $config = require __DIR__ . '/config.php';
        $this->host = $config['db']['host'];
        $this->db_name = $config['db']['name'];
        $this->username = $config['db']['user'];
        $this->password = $config['db']['password'];

        // Activar modo estricto de errores para mysqli
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        
        // Conexión usando mysqli
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

        if ($this->conn->connect_error) {
            error_log("Database connection error: " . $this->conn->connect_error);
            die("Error de conexión a la base de datos.");
        }

        // Opcional: establecer charset utf8
        $this->conn->set_charset("utf8");
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    private function __clone() {}

    public function __wakeup() {
        throw new Exception("Deserialización no permitida.");
    }
}
