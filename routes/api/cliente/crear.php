<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/pro/controllers/api/ClienteApiController.php';

header('Content-Type: application/json');

// Obtener datos JSON enviados
$data = json_decode(file_get_contents("php://input"), true);

// Validar si los datos son correctos
if (!$data) {
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

// Crear una instancia del controlador
$clienteApiController = new ClienteApiController();

// Llamar al método de manejar la solicitud de creación de cliente
$clienteApiController->handleRequest('crear');