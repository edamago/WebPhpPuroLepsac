<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'services/ProductoService.php';
require_once 'services/View.php';

class ProductoController {
    private $productoService;

    public function __construct() {
        $this->productoService = new ProductoService();
    }

    public function listarProductos() {
        //$productos = $this->productoService->listarProductos();

        //if (isset($productos['error'])) {
        //    die($productos['error']);
        //}

        include $_SERVER['DOCUMENT_ROOT'] . '/jabones/views/productos/listar.php';
        
    }
    
    public function editarProducto($id) {
        $producto = $this->productoService->obtenerProducto($id);
    
        if (isset($producto['error'])) {
            die($producto['error']);
        }
        //extract($producto);
        // Lo pasas tal cual como variable
        include $_SERVER['DOCUMENT_ROOT'] . '/jabones/views/productos/editar.php';
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
            include $_SERVER['DOCUMENT_ROOT'] . '/jabones/views/productos/editar.php';
        } else {
            $mensaje = "Producto actualizado correctamente.";
            $producto = $this->productoService->obtenerProducto($data['id']);
            include $_SERVER['DOCUMENT_ROOT'] . '/jabones/views/productos/editar.php';
        }
    }
}
?>
