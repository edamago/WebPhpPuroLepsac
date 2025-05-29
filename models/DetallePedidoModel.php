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

    public function crear($data, $db = null)
    {
        $db = $db ?: $this->conn;

        // Validar que todos los campos requeridos están presentes
        $requeridos = ['cantidad', 'precio', 'comentario', 'estado', 't_pedido_id', 't_producto_id'];
        foreach ($requeridos as $campo) {
            if (!isset($data[$campo])) {
                throw new Exception("Falta el campo obligatorio: $campo");
            }
        }

        $sql = "INSERT INTO t_detalle_pedido (cantidad, precio, comentario, estado, t_pedido_id, t_producto_id)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar consulta: " . $db->error);
        }

        $stmt->bind_param(
            "idssii",
            $data['cantidad'],
            $data['precio'],
            $data['comentario'],
            $data['estado'],
            $data['t_pedido_id'],
            $data['t_producto_id']
        );

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar: " . $stmt->error);
        }

        return $db->insert_id;
    }


    public function obtenerPorPedidoId($pedido_id)
    {
        $sql = "SELECT * FROM t_detalle_pedido WHERE t_pedido_id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar consulta: " . $this->conn->error);
        }

        $stmt->bind_param("i", $pedido_id);

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar consulta: " . $stmt->error);
        }

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function eliminarPorPedidoId($pedido_id, $db = null)
    {
        $db = $db ?: $this->conn;

        $sql = "DELETE FROM t_detalle_pedido WHERE t_pedido_id = ?";
        $stmt = $db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar consulta: " . $db->error);
        }

        $stmt->bind_param("i", $pedido_id);

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar consulta: " . $stmt->error);
        }
    }


    public function actualizarDetalles($t_pedido_id, $detalles, $db = null)
    {
        $db = $db ?: $this->conn;

        // Obtener los IDs actuales en la BD
        $stmt = $db->prepare("SELECT id FROM t_detalle_pedido WHERE t_pedido_id = ?");
        if (!$stmt) {
            throw new Exception("Error al preparar consulta: " . $db->error);
        }

        $stmt->bind_param("i", $t_pedido_id);
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar consulta: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $idsActuales = [];
        while ($row = $result->fetch_assoc()) {
            $idsActuales[] = $row['id'];
        }

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
            $in = implode(',', array_fill(0, count($idsEliminar), '?'));
            $sql = "DELETE FROM t_detalle_pedido WHERE id IN ($in)";
            $stmt = $db->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error al preparar consulta de eliminación: " . $db->error);
            }

            // Para bind_param dinámico con tipos todos enteros:
            $types = str_repeat('i', count($idsEliminar));
            $stmt->bind_param($types, ...$idsEliminar);

            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar eliminación: " . $stmt->error);
            }
        }
    }


    public function actualizar($detalle, $db = null)
    {
        $db = $db ?: $this->conn;

        $sql = "UPDATE t_detalle_pedido SET
            cantidad = ?,
            precio = ?,
            comentario = ?,
            estado = ?,
            t_pedido_id = ?,
            t_producto_id = ?
            WHERE id = ?";

        $stmt = $db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar consulta: " . $db->error);
        }

        $stmt->bind_param(
            "idssiii",
            $detalle['cantidad'],
            $detalle['precio'],
            $detalle['comentario'],
            $detalle['estado'],
            $detalle['t_pedido_id'],
            $detalle['t_producto_id'],
            $detalle['id']
        );

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar actualización: " . $stmt->error);
        }
    }
}
