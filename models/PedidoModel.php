<?php

require_once __DIR__ . '/../config/Database.php';

class Pedido {
    private $conn;
    private $table = "t_pedido";

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    public function crear($data) {
        $sql = "INSERT INTO $this->table (fecha, atencion, moneda, subtotal, igv, total, comentario, enviado, estado, t_cliente_id)
                VALUES (:fecha, :atencion, :moneda, :subtotal, :igv, :total, :comentario, :enviado, :estado, :t_cliente_id)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':fecha' => $data['fecha'],
            ':atencion' => $data['atencion'],
            ':moneda' => $data['moneda'],
            ':subtotal' => $data['subtotal'],
            ':igv' => $data['igv'],
            ':total' => $data['total'],
            ':comentario' => $data['comentario'],
            ':enviado' => $data['enviado'],
            ':estado' => $data['estado'],
            ':t_cliente_id' => $data['t_cliente_id']
        ]);

        return $this->conn->lastInsertId();
    }

    public function getConnection() {
        return $this->conn;
    }
}
