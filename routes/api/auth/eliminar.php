<?php
header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT'] . '/pro/controllers/api/AuthApiController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pro/helpers/AuthHelper.php';

try {
    // Validar el token
    AuthHelper::validarToken(); // Esto lanza un error si el token no es válido
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

$controller = new AuthApiController();

// Obtener el ID del usuario desde la URL
$requestUri = $_SERVER['REQUEST_URI'];
$uriParts = explode('/', $requestUri);
$id = end($uriParts);

// Verificar que se haya proporcionado un ID
if (empty($id) || !is_numeric($id)) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de usuario no proporcionado o inválido']);
    exit;
}

// Llamar al método de eliminación en el controlador
$response = $controller->eliminarUsuario($id);

// Verificar si la eliminación fue exitosa
if ($response['success']) {
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Usuario eliminado correctamente']);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'No se encontró el usuario para eliminar']);
}

