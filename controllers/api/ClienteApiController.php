<?php
require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__ . '/../../models/ClienteModel.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class ClienteApiController {
    private $model;

    public function __construct() {
        $this->model = new Cliente();
    }

    public function listarClientes() {
        return $this->model->listar();
    }

    public function obtenerClientePorId($id) {
        return $this->model->obtener($id);
    }

    public function insertarCliente($data) {
        return $this->model->insertar($data);
    }

    public function actualizarClientePorId($id, $data) {
        return $this->model->actualizar($id, $data);
    }

    public function eliminarCliente($id) {
        return $this->model->eliminar($id);
    }

    public function validarToken() {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            http_response_code(401);
            echo json_encode(['error' => 'Token no proporcionado o mal formado']);
            exit;
        }

        $token = trim(str_replace('Bearer', '', $authHeader));

        try {
            $config = include __DIR__ . '/../../config/config.php';
            $jwt_secret = $config['jwt_secret'];

            $decoded = JWT::decode($token, new Key($jwt_secret, 'HS256'));

            return $decoded;
        } catch (ExpiredException $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Token expirado']);
            exit;
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Token no válido']);
            exit;
        }
    }

    public function handleRequest($action) {
        header('Content-Type: application/json');

        $this->validarToken();

        switch ($action) {
            case 'listar':
                echo json_encode($this->listarClientes());
                break;

            case 'obtener':
                $id = $_GET['id'] ?? null;
                if (!$id) {
                    $this->error("ID es requerido", 400);
                } else {
                    $cliente = $this->obtenerClientePorId($id);
                    if ($cliente) {
                        echo json_encode($cliente);
                    } else {
                        $this->error("Cliente no encontrado", 404);
                    }
                }
                break;

            case 'crear':
                $data = json_decode(file_get_contents("php://input"), true);
                if (empty($data)) {
                    $this->error("Datos incompletos", 400);
                } else {
                    if ($this->insertarCliente($data)) {
                        echo json_encode(["mensaje" => "Cliente creado"]);
                    } else {
                        $this->error("Error al crear el cliente", 500);
                    }
                }
                break;

            case 'actualizar':
                $id = $_GET['id'] ?? null;
                if (!$id) {
                    $this->error("ID es requerido", 400);
                } else {
                    $data = json_decode(file_get_contents("php://input"), true);
                    if (empty($data)) {
                        $this->error("Datos incompletos para actualizar", 400);
                    } else {
                        if ($this->actualizarClientePorId($id, $data)) {
                            echo json_encode(["mensaje" => "Cliente actualizado"]);
                        } else {
                            $this->error("Error al actualizar el cliente", 500);
                        }
                    }
                }
                break;

            case 'eliminar':
                $id = $_GET['id'] ?? null;
                if (!$id) {
                    $this->error("ID es requerido", 400);
                } else {
                    if ($this->eliminarCliente($id)) {
                        echo json_encode(["mensaje" => "Cliente eliminado"]);
                    } else {
                        $this->error("Error al eliminar el cliente", 500);
                    }
                }
                break;

            default:
                $this->error("Acción no válida", 404);
                break;
        }
    }

    private function error($mensaje, $codigo) {
        http_response_code($codigo);
        echo json_encode(["error" => $mensaje]);
    }
}