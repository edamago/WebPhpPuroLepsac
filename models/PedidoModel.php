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
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "ssssddsssi",
            $data['fecha'],
            $data['atencion'],
            $data['moneda'],
            $data['subtotal'],
            $data['igv'],
            $data['total'],
            $data['comentario'],
            $data['enviado'],
            $data['estado'],
            $data['t_cliente_id']
        );
        $stmt->execute();

        return $this->conn->insert_id;
    }

    public function listar() {
        $sql = "SELECT * FROM $this->table ORDER BY fecha DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtener($id) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function eliminar($id) {
        $stmt = $this->conn->prepare("DELETE FROM t_pedido WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    public function actualizar($pedidoData) {
        $sql = "UPDATE t_pedido SET
            fecha = ?,
            atencion = ?,
            moneda = ?,
            subtotal = ?,
            igv = ?,
            total = ?,
            comentario = ?,
            enviado = ?,
            estado = ?,
            t_cliente_id = ?
            WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "ssssddsssii",
            $pedidoData["fecha"],
            $pedidoData["atencion"],
            $pedidoData["moneda"],
            $pedidoData["subtotal"],
            $pedidoData["igv"],
            $pedidoData["total"],
            $pedidoData["comentario"],
            $pedidoData["enviado"],
            $pedidoData["estado"],
            $pedidoData["t_cliente_id"],
            $pedidoData["id"]
        );
        $stmt->execute();
    }

    public function getConnection() {
        return $this->conn;
    }
}
