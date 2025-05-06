<?php
// Usar $_SERVER['DOCUMENT_ROOT'] para obtener la ruta completa del archivo
require_once $_SERVER['DOCUMENT_ROOT'] . '/pro/controllers/api/ProductoApiController.php';

// Obtener datos JSON enviados
$data = json_decode(file_get_contents("php://input"), true);

// Validar si los datos son correctos
if (!$data) {
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

// Crear una instancia del controlador
$productoApiController = new ProductoApiController();

// Llamar al método de manejar la solicitud de creación de producto
$productoApiController->handleRequest('crear');
?>
