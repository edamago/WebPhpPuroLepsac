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

        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name};charset=utf8", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            die("Error de conexión a la base de datos.");
        }
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
        // Este método está definido para evitar la deserialización de la instancia de la clase.
        throw new Exception("Deserialización no permitida.");
    }
}
