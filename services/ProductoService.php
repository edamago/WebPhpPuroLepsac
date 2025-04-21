<?php
require_once 'controllers/api/ProductoApiController.php';

class ProductoService {
    private $apiController;

    public function __construct() {
        $this->apiController = new ProductoApiController();
    }

    public function listarProductos() {
        // Obtener lista de productos desde el API
        ob_start(); // Captura la salida del controlador
        $this->apiController->handleRequest('listar');
        $response = ob_get_clean(); // Capturamos la salida generada
        $productos = json_decode($response, true);

        return $productos;
    }
    public function obtenerProducto($id) {
        // Obtener el producto desde el API
        $_GET['id'] = $id;
        ob_start(); // Captura la salida
        $this->apiController->handleRequest('obtener');
        $response = ob_get_clean(); // Capturamos la salida generada
        $producto = json_decode($response, true);

        return $producto;
    }

    public function actualizarProducto($data) {
        // Actualizar el producto a través del API
        $_GET['id'] = $data['id'];
        $_POST = $data; // Datos a actualizar

        ob_start(); // Captura la salida
        $this->apiController->handleRequest('actualizar');
        $response = ob_get_clean();
        $resultado = json_decode($response, true);

        return $resultado;
    }
}
?>
