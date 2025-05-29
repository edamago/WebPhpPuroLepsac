<?php
require_once __DIR__ . '/../../models/PedidoModel.php';
require_once __DIR__ . '/../../models/DetallePedidoModel.php';
require_once __DIR__ . '/../../helpers/AuthHelper.php';

class PedidoApiController
{
    private $pedidoModel;
    private $detalleModel;

    public function __construct()
    {
        $this->pedidoModel = new Pedido();
        $this->detalleModel = new DetallePedido();
    }

    public function handleRequest($action)
    {
        header('Content-Type: application/json');
        AuthHelper::validarToken();

        switch ($action) {
            case 'crear':
                $data = json_decode(file_get_contents("php://input"), true);
                if (!is_array($data)) {
                    return $this->error("El cuerpo del JSON es inválido o está vacío.", 400);
                }
                $response = $this->crear($data);
                http_response_code($response["status"]);
                echo json_encode($response);
                break;

            case 'listar':
                $response = $this->listar();
                http_response_code($response["status"]);
                echo json_encode($response);
                break;

            case 'obtener':
                $id = $_GET['id'] ?? null;
                if (!$id) {
                    return $this->error("ID es requerido", 400);
                }
                $response = $this->obtener($id);
                http_response_code($response["status"]);
                echo json_encode($response);
                break;

            case 'eliminar':
                $id = $_GET['id'] ?? null;
                if (!$id) {
                    return $this->error("ID es requerido para eliminar", 400);
                }
                $response = $this->eliminar($id);
                http_response_code($response["status"]);
                echo json_encode($response);
                break;

            case 'actualizar':
                $data = json_decode(file_get_contents("php://input"), true);
                if (!is_array($data)) {
                    return $this->error("El cuerpo del JSON es inválido o está vacío.", 400);
                }
                $response = $this->actualizar($data);
                http_response_code($response["status"]);
                echo json_encode($response);
                break;

            default:
                return $this->error("Acción no válida", 404);
        }
    }

    public function crear($data)
{
    $pedidoData = $data["pedido"] ?? null;
    $detallesData = $data["detalles"] ?? [];

    if (!$pedidoData || empty($detallesData)) {
        return [
            "status" => 400,
            "error" => "Debe incluir datos del pedido y al menos un detalle."
        ];
    }

    // Validar que todos los campos requeridos estén presentes en cada detalle
    $camposRequeridos = ['cantidad', 'precio', 'comentario', 'estado', 't_producto_id'];
    foreach ($detallesData as $i => $detalle) {
        foreach ($camposRequeridos as $campo) {
            if (!isset($detalle[$campo])) {
                return [
                    "status" => 400,
                    "error" => "Falta el campo '$campo' en el detalle #" . ($i + 1)
                ];
            }
        }
    }

    $mysqli = $this->pedidoModel->getConnection();

    try {
        $mysqli->autocommit(false); // Iniciar transacción

        $pedido_id = $this->pedidoModel->crear($pedidoData, $mysqli);

        foreach ($detallesData as $detalle) {
            $detalle['t_pedido_id'] = $pedido_id;
            $this->detalleModel->crear($detalle, $mysqli);
        }

        $mysqli->commit();
        $mysqli->autocommit(true);

        return [
            "status" => 200,
            "success" => true,
            "pedido_id" => $pedido_id
        ];
    } catch (Exception $e) {
        $mysqli->rollback();
        $mysqli->autocommit(true);

        return [
            "status" => 500,
            "error" => "Error al guardar el pedido: " . $e->getMessage()
        ];
    }
}


    public function listar()
    {
        try {
            $pedidos = $this->pedidoModel->listar();
            return [
                "status" => 200,
                "data" => $pedidos
            ];
        } catch (Exception $e) {
            return [
                "status" => 500,
                "error" => "Error al listar pedidos: " . $e->getMessage()
            ];
        }
    }

    public function obtener($id)
    {
        try {
            $pedido = $this->pedidoModel->obtener($id);
            if (!$pedido) {
                return [
                    "status" => 404,
                    "error" => "Pedido no encontrado"
                ];
            }

            $detalles = $this->detalleModel->obtenerPorPedidoId($id);
            return [
                "status" => 200,
                "data" => [
                    "pedido" => $pedido,
                    "detalles" => $detalles
                ]
            ];
        } catch (Exception $e) {
            return [
                "status" => 500,
                "error" => "Error al obtener pedido: " . $e->getMessage()
            ];
        }
    }

    public function eliminar($id)
    {
        $mysqli = $this->pedidoModel->getConnection();

        try {
            $pedido = $this->pedidoModel->obtener($id);
            if (!$pedido) {
                return [
                    "status" => 404,
                    "error" => "Pedido no encontrado"
                ];
            }

            $mysqli->autocommit(false);

            $this->detalleModel->eliminarPorPedidoId($id, $mysqli);
            $this->pedidoModel->eliminar($id, $mysqli);

            $mysqli->commit();
            $mysqli->autocommit(true);

            return [
                "status" => 200,
                "message" => "Pedido eliminado correctamente"
            ];
        } catch (Exception $e) {
            $mysqli->rollback();
            $mysqli->autocommit(true);

            return [
                "status" => 500,
                "error" => "Error al eliminar el pedido: " . $e->getMessage()
            ];
        }
    }

    public function actualizar($data)
    {
        $pedido_id = $data['pedido']['id'] ?? null;
        if (!$pedido_id) {
            return [
                "status" => 400,
                "error" => "ID del pedido es requerido"
            ];
        }

        $pedidoExistente = $this->pedidoModel->obtener($pedido_id);
        if (!$pedidoExistente) {
            return [
                "status" => 404,
                "error" => "Pedido no encontrado"
            ];
        }

        $pedidoData = $data['pedido'];
        $detallesData = $data['detalles'] ?? [];

        if (empty($detallesData)) {
            return [
                "status" => 400,
                "error" => "Debe incluir al menos un detalle del pedido."
            ];
        }

        $mysqli = $this->pedidoModel->getConnection();

        try {
            $mysqli->autocommit(false);

            $this->pedidoModel->actualizar($pedidoData, $mysqli);
            $this->detalleModel->actualizarDetalles($pedido_id, $detallesData, $mysqli);

            $mysqli->commit();
            $mysqli->autocommit(true);

            return [
                "status" => 200,
                "success" => true,
                "pedido_id" => $pedido_id
            ];
        } catch (Exception $e) {
            $mysqli->rollback();
            $mysqli->autocommit(true);

            return [
                "status" => 500,
                "error" => "Error al actualizar pedido: " . $e->getMessage()
            ];
        }
    }

    private function error($mensaje, $codigo)
    {
        http_response_code($codigo);
        echo json_encode(["error" => $mensaje]);
        return;
    }
}
