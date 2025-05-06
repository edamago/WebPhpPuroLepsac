<?php
require_once 'controllers/api/ProductoApiController.php';

class ProductoService {
    private $apiController;

    public function __construct() {
        $this->apiController = new ProductoApiController();
    }

    public function listarProductos() {
        return $this->apiController->listarProductos();
    }

    public function obtenerProducto($id) {
        return $this->apiController->obtenerProductoPorId($id);
    }

    public function actualizarProducto($data) {
        $id = $data['id'];
        return $this->apiController->actualizarProductoPorId($id, $data);
    }

    public function eliminarProducto($data) {
        $id = $data['id'];
        return $this->apiController->eliminarProducto($id);
    }
}

