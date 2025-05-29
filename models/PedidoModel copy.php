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

    public function crear($data, $pdo = null)
    {
        $db = $pdo ?: $this->conn;

        $sql = "INSERT INTO t_detalle_pedido (
                    cantidad, precio, comentario, estado, t_pedido_id, t_producto_id
                ) VALUES (
                    :cantidad, :precio, :comentario, :estado, :t_pedido_id, :t_producto_id
                )";

        $stmt = $db->prepare($sql);

        $stmt->execute([
            ':cantidad' => $data['cantidad'],
            ':precio' => $data['precio'],
            ':comentario' => $data['comentario'],
            ':estado' => $data['estado'],
            ':t_pedido_id' => $data['t_pedido_id'],
            ':t_producto_id' => $data['t_producto_id'],
        ]);
    }

    public function obtenerPorPedidoId($pedido_id)
    {
        $sql = "SELECT * FROM t_detalle_pedido WHERE t_pedido_id = :pedido_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':pedido_id', $pedido_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarPorPedidoId($pedido_id, $pdo = null)
    {
        $db = $pdo ?: $this->conn;

        $sql = "DELETE FROM t_detalle_pedido WHERE t_pedido_id = :pedido_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':pedido_id', $pedido_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function actualizarDetalles($t_pedido_id, $detalles, $pdo = null)
    {
        $db = $pdo ?: $this->conn;

        // Obtener los IDs actuales en la BD
        $stmt = $db->prepare("SELECT id FROM t_detalle_pedido WHERE t_pedido_id = ?");
        $stmt->execute([$t_pedido_id]);
        $idsActuales = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $idsRecibidos = [];

        foreach ($detalles as $detalle) {
            $detalle['t_pedido_id'] = $t_pedido_id;

            if (isset($detalle['id']) && in_array($detalle['id'], $idsActuales)) {
                $this->actualizar($detalle, $db);
                $idsRecibidos[] = $detalle['id'];
            } else {
                $this->crear($detalle, $db);
            }
        }

        // Eliminar los detalles que ya no están en la nueva lista
        $idsEliminar = array_diff($idsActuales, $idsRecibidos);
        if (!empty($idsEliminar)) {
            $in = str_repeat('?,', count($idsEliminar) - 1) . '?';
            $stmt = $db->prepare("DELETE FROM t_detalle_pedido WHERE id IN ($in)");
            $stmt->execute($idsEliminar);
        }
    }

    public function actualizar($detalle, $pdo = null)
    {
        $db = $pdo ?: $this->conn;

        $sql = "UPDATE t_detalle_pedido SET
                    cantidad = :cantidad,
                    precio = :precio,
                    comentario = :comentario,
                    estado = :estado,
                    t_pedido_id = :t_pedido_id,
                    t_producto_id = :t_producto_id
                WHERE id = :id";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':cantidad' => $detalle['cantidad'],
            ':precio' => $detalle['precio'],
            ':comentario' => $detalle['comentario'],
            ':estado' => $detalle['estado'],
            ':t_pedido_id' => $detalle['t_pedido_id'],
            ':t_producto_id' => $detalle['t_producto_id'],
            ':id' => $detalle['id'],
        ]);
    }
}
