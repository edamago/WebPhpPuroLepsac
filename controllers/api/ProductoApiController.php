<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../models/ProductoModel.php';
require_once __DIR__ . '/../../helpers/AuthHelper.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class ProductoApiController {
    private $model;

    public function __construct() {
        $this->model = new Producto();

        // ✅✅✅ Agrega CORS en todas las respuestas
        //header("Access-Control-Allow-Origin: http://localhost:81");
        //header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        //header("Access-Control-Allow-Headers: Content-Type, Authorization");
        
        // ✅✅✅ Responde a preflight OPTIONS y termina
        //if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        //    http_response_code(200);
        //    exit();
        //}
    }

    // Métodos reutilizables para el servicio
    public function listarProductos() {
        return $this->model->listar();
    }

    public function obtenerProductoPorId($id) {
        return $this->model->obtener($id);
    }

    public function obtenerProductoPorCodigo($codigo) {
        return $this->model->obtenerPorCodigo($codigo);
    }

    public function insertarProducto($data) {
        return $this->model->insertar($data);
    }

    public function actualizarProductoPorId($id, $data) {
        $productoExistente = $this->model->obtenerPorCodigo($data['codigo']);
        if ($productoExistente && $productoExistente['id'] != $id) {
            return ["error" => "El código del producto ya está en uso por otro producto."];
        }
        return $this->model->actualizar($id, $data);
    }

    public function eliminarProducto($id) {
        return $this->model->eliminar($id);
    }

    public function handleRequest($action) {
        header('Content-Type: application/json');

        // ✅ Validación de token antes de todo
        AuthHelper::validarToken();

        switch ($action) {
            case 'listar':
                echo json_encode($this->listarProductos());
                break;

            case 'obtener':
                $id = $_GET['id'] ?? null;
                if (!$id) {
                    $this->error("ID es requerido", 400);
                } else {
                    $producto = $this->obtenerProductoPorId($id);
                    if ($producto) {
                        echo json_encode($producto);
                    } else {
                        $this->error("Producto no encontrado", 404);
                    }
                }
                break;

            case 'obtenerPorCodigo':
                $codigo = $_GET['codigo'] ?? null;
                if (!$codigo) {
                    $this->error("Código es requerido", 400);
                } else {
                    $producto = $this->obtenerProductoPorCodigo($codigo);
                    if ($producto) {
                        echo json_encode($producto);
                    } else {
                        $this->error("Producto no encontrado con este código", 404);
                    }
                }
                break;

            case 'crear':
                $data = json_decode(file_get_contents("php://input"), true);
                if (empty($data['codigo']) || empty($data['descripcion'])) {
                    $this->error("Datos incompletos", 400);
                } else {
                    $productoExistente = $this->model->obtenerPorCodigo($data['codigo']);
                    if ($productoExistente) {
                        $this->error("El producto con este código ya existe", 400);
                    } else {
                        if ($this->insertarProducto($data)) {
                            echo json_encode(["mensaje" => "Producto creado"]);
                        } else {
                            $this->error("Error al crear el producto", 500);
                        }
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
                        $resultado = $this->actualizarProductoPorId($id, $data);
                        if (isset($resultado['error'])) {
                            $this->error($resultado['error'], 400);
                        } else {
                            echo json_encode(["mensaje" => "Producto actualizado"]);
                        }
                    }
                }
                break;

            case 'eliminar':
                $id = $_GET['id'] ?? null;
                if (!$id) {
                    $this->error("ID es requerido", 400);
                } else {
                    if ($this->eliminarProducto($id)) {
                        echo json_encode(["mensaje" => "Producto eliminado"]);
                    } else {
                        $this->error("Error al eliminar el producto", 500);
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
