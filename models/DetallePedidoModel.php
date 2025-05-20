<?php
require_once __DIR__ . '/../config/Database.php';

class DetallePedido
{
    private $conn;

    public function __construct()
    {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    public function crear($data)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO t_detalle_pedido (
                cantidad, precio, comentario, estado, t_pedido_id, t_producto_id
            ) VALUES (
                :cantidad, :precio, :comentario, :estado, :t_pedido_id, :t_producto_id
            )
        ");
        $stmt->execute($data);
    }
}
