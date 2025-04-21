<?php
require_once 'models/ProductoModel.php';

class ProductoApiController {
    private $model;

    public function __construct() {
        $this->model = new Producto();
    }

    // Métodos reutilizables para el servicio
    public function listarProductos() {
        return $this->model->listar();
    }

    public function obtenerProductoPorId($id) {
        return $this->model->obtener($id);
    }

    public function insertarProducto($data) {
        return $this->model->insertar($data);
    }

    public function actualizarProductoPorId($id, $data) {
        return $this->model->actualizar($id, $data);
    }

    public function eliminarProducto($id) {
        return $this->model->eliminar($id);
    }

    // Manejo de API (respuestas JSON para solicitudes HTTP)
    public function handleRequest($action) {
        header('Content-Type: application/json');

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

            case 'crear':
                $data = json_decode(file_get_contents("php://input"), true);
                if (empty($data['codigo']) || empty($data['descripcion'])) {
                    $this->error("Datos incompletos", 400);
                } else {
                    if ($this->insertarProducto($data)) {
                        echo json_encode(["mensaje" => "Producto creado"]);
                    } else {
                        $this->error("Error al crear el producto", 500);
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
                        if ($this->actualizarProductoPorId($id, $data)) {
                            echo json_encode(["mensaje" => "Producto actualizado"]);
                        } else {
                            $this->error("Error al actualizar el producto", 500);
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
