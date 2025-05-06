<?php
// Usar $_SERVER['DOCUMENT_ROOT'] para obtener la ruta completa del archivo
require_once $_SERVER['DOCUMENT_ROOT'] . '/pro/controllers/api/ProductoApiController.php';

// Crear una instancia del controlador
$productoApiController = new ProductoApiController();

// Llamar al método de manejar la solicitud de listado
$productoApiController->handleRequest('listar');
?>
