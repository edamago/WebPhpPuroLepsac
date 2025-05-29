<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../models/ClienteModel.php';
require_once __DIR__ . '/../../helpers/AuthHelper.php';

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
    $resultado = $this->model->insertar($data);

    if (is_array($resultado) && isset($resultado['error'])) {
        // Retornamos error controlado
        return $resultado;
    }
    return $resultado; // true si se insertó correctamente
}


    public function actualizarClientePorId($id, $data) {
    $resultado = $this->model->actualizar($id, $data);

    if (is_array($resultado) && isset($resultado['error'])) {
        // Envía solo el error que viene del modelo y termina ejecución
        $this->error($resultado['error'], 400);
        return false;  // Importante retornar para que no siga el flujo
    } 
    
    if ($resultado === true) {
        echo json_encode(["mensaje" => "Cliente actualizado"]);
        return true;
    } 
    
    // Si no hay error explícito y no se actualizó, envía este mensaje
    //$this->error("No se realizaron cambios o el cliente no existe", 400);
    //return false;
}







    public function eliminarCliente($id) {
    // Validar si el cliente existe primero
    $clienteExistente = $this->model->obtener($id);
    if (!$clienteExistente) {
        // Retornar error o false para indicar que no existe
        $this->error("Cliente no existe", 404);
        return false;
    }

    // Si existe, proceder a eliminar
    $resultado = $this->model->eliminar($id);

    if ($resultado) {
        echo json_encode(["mensaje" => "Cliente eliminado"]);
        return true;
    } else {
        $this->error("Error al eliminar el cliente", 500);
        return false;
    }
}


    //public function validarToken() {
    //    $headers = getallheaders();
    //    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;

    //    if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
    //        http_response_code(401);
    //        echo json_encode(['error' => 'Token no proporcionado o mal formado']);
    //        exit;
    //    }

    //    $token = trim(str_replace('Bearer', '', $authHeader));

    //    try {
    //        $config = include __DIR__ . '/../../config/config.php';
    //        $jwt_secret = $config['jwt_secret'];

    //        $decoded = JWT::decode($token, new Key($jwt_secret, 'HS256'));

    //        return $decoded;
    //    } catch (ExpiredException $e) {
    //        http_response_code(401);
    //        echo json_encode(['error' => 'Token expirado']);
    //        exit;
    //    } catch (Exception $e) {
    //        http_response_code(401);
    //        echo json_encode(['error' => 'Token no válido']);
    //        exit;
    //    }
    //}

    public function handleRequest($action) {
        header('Content-Type: application/json');

        //$this->validarToken();
        AuthHelper::validarToken();

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
                $resultado = $this->insertarCliente($data);
                if (is_array($resultado) && isset($resultado['error'])) {
                    $this->error($resultado['error'], 400);
                } elseif ($resultado) {
                    echo json_encode(["mensaje" => "Cliente creado"]);
                } else {
                    $this->error("Error al crear el cliente", 500);
                }
            }
            break;

            case 'actualizar':
        $id = $_GET['id'] ?? null;
        if (!$id) {
            return $this->error("ID es requerido", 400);
        }

        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data)) {
            return $this->error("Datos incompletos para actualizar", 400);
        }

        $resultado = $this->actualizarClientePorId($id, $data);
        if (is_array($resultado) && isset($resultado['error'])) {
            return $this->error($resultado['error'], 400);
        }

        if ($resultado) {
            return json_encode(["mensaje" => "Cliente actualizado"]);
        } //else {
        //    return $this->error("No se realizaron cambios o el cliente no existe", 400);
        //}

        break;


            case 'eliminar':
    $id = $_GET['id'] ?? null;
    if (!$id) {
        $this->error("ID es requerido", 400);
    } else {
        $this->eliminarCliente($id);
    }
    break;

        }
    }

    private function error($mensaje, $codigo) {
        http_response_code($codigo);
        echo json_encode(["error" => $mensaje]);
    }
}