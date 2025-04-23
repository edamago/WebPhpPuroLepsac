<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'services/ProductoService.php';
require_once 'services/View.php';
require_once 'config/config.php';  // Asegúrate de que la ruta sea correcta

class ProductoController {
    private $productoService;

    public function __construct() {
        $this->productoService = new ProductoService();
    }

    public function crear() {
        $productos = $this->productoService->listarProductos();

        if (isset($productos['error'])) {
            die($productos['error']);
        }

        //include $_SERVER['DOCUMENT_ROOT'] . '/jabones/views/productos/agregar.php';
        include VISTA_BASE . 'productos/agregar.php';
        
    }
    public function listarProductos() {
        $productos = $this->productoService->listarProductos();

        if (isset($productos['error'])) {
            die($productos['error']);
        }

        include VISTA_BASE . 'productos/listar.php';
    }
    
    public function editarProducto($id) {
        $producto = $this->productoService->obtenerProducto($id);
    
        if (isset($producto['error'])) {
            die($producto['error']);
        }
        //extract($producto);
        // Lo pasas tal cual como variable
        //include $_SERVER['DOCUMENT_ROOT'] . '/jabones/views/productos/editar.php';
        include VISTA_BASE . 'productos/editar.php';
        //include 'views/productos/editar.php';
        //View::render('productos/editar', [
        //    'producto' => $producto
        //]);
    }
    

    public function actualizarProducto() {
        $data = $_POST;
        $resultado = $this->productoService->actualizarProducto($data);
    
        if (isset($resultado['error'])) {
            $error = $resultado['error'];
            $producto = $this->productoService->obtenerProducto($data['id']);
            //include $_SERVER['DOCUMENT_ROOT'] . '/jabones/views/productos/editar.php';
            include VISTA_BASE . 'productos/editar.php';
        } else {
            $mensaje = "Producto actualizado correctamente.";
            $producto = $this->productoService->obtenerProducto($data['id']);
            //include $_SERVER['DOCUMENT_ROOT'] . '/jabones/views/productos/editar.php';
            include VISTA_BASE . 'productos/editar.php';
        }
    }

    public function eliminarProducto($id) {
        // Pasar el $id directamente a la función del servicio
        $resultado = $this->productoService->eliminarProducto(['id' => $id]);

        if (isset($resultado['error'])) {
            die($resultado['error']);
        }

        // Llamar al método listarProductos para mostrar la lista actualizada
        $this->listarProductos();
    }
    
}
?>
