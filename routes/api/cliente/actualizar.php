<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/pro/controllers/api/ClienteApiController.php';

header('Content-Type: application/json');

// Obtener el ID desde la URL (query string)
$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['error' => 'ID no proporcionado']);
    exit;
}

// Obtener los datos del cuerpo de la solicitud (JSON)
$data = json_decode(file_get_contents("php://input"), true);

// Crear una instancia del controlador y llamar al método para actualizar el cliente
$clienteApiController = new ClienteApiController();
$clienteApiController->handleRequest('actualizar');