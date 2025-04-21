<?php
require_once 'models/ProductoModel.php';

class ProductoApiController {
    private $model;

    public function __construct() {
        $this->model = new Producto();
    }

    public function handleRequest($action) {
        header('Content-Type: application/json');
        switch ($action) {
            case 'listar':
                $this->listar();
                break;
            case 'obtener':
                $this->obtener();
                break;
            case 'crear':
                $this->crear();
                break;
            case 'actualizar':
                $this->actualizar();
                break;
            case 'eliminar':
                $this->eliminar();
                break;
            default:
                $this->error("Acción no válida", 404);
                break;
        }
    }

    private function listar() {
        $productos = $this->model->listar();
        echo json_encode($productos);
    }

    private function obtener() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->error("ID es requerido", 400);
            return;
        }

        $producto = $this->model->obtener($id);
        if ($producto) {
            echo json_encode($producto);
        } else {
            $this->error("Producto no encontrado", 404);
        }
    }

    private function crear() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data['codigo']) || empty($data['descripcion'])) {
            $this->error("Datos incompletos", 400);
            return;
        }

        if ($this->model->insertar($data)) {
            echo json_encode(["mensaje" => "Producto creado"]);
        } else {
            $this->error("Error al crear el producto", 500);
        }
    }

    private function actualizar() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->error("ID es requerido", 400);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data)) {
            $this->error("Datos incompletos para actualizar", 400);
            return;
        }

        if ($this->model->actualizar($id, $data)) {
            echo json_encode(["mensaje" => "Producto actualizado"]);
        } else {
            $this->error("Error al actualizar el producto", 500);
        }
    }

    private function eliminar() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->error("ID es requerido", 400);
            return;
        }

        if ($this->model->eliminar($id)) {
            echo json_encode(["mensaje" => "Producto eliminado"]);
        } else {
            $this->error("Error al eliminar el producto", 500);
        }
    }

    private function error($mensaje, $codigo) {
        http_response_code($codigo);
        echo json_encode(["error" => $mensaje]);
    }
}
