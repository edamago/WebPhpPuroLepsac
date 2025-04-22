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

    public function crear() {
        //$productos = $this->productoService->listarProductos();

        //if (isset($productos['error'])) {
        //    die($productos['error']);
        //}

        include $_SERVER['DOCUMENT_ROOT'] . '/jabones/views/productos/agregar.php';
        
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

    public function eliminarProducto($id) {
        // Pasar el $id directamente a la función del servicio
        $resultado = $this->productoService->eliminarProducto(['id' => $id]);
        
        if (isset($resultado['error'])) {
            die($resultado['error']);
        }
        
        // Redirigir a la lista de productos (o incluir la vista si es necesario)
        include $_SERVER['DOCUMENT_ROOT'] . '/jabones/views/productos/listar.php';
    }
    
}
?>
