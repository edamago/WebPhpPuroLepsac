<?php
header('Content-Type: application/json');
//require_once '../../../controllers/api/AuthApiController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pro/controllers/api/AuthApiController.php';

$headers = getallheaders();
$controller = new AuthApiController();

// Validar el token antes de continuar
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Token no proporcionado']);
    exit;
}

$token = str_replace('Bearer ', '', $headers['Authorization']);
$usuarioAutenticado = $controller->verificar_token($token);

if (!$usuarioAutenticado) {
    http_response_code(401);
    echo json_encode(['error' => 'Token no válido']);
    exit;
}

// Obtener el ID del usuario desde la URL
$requestUri = $_SERVER['REQUEST_URI']; // Obtener la URI completa de la solicitud
$uriParts = explode('/', $requestUri); // Dividir la URI en partes
$id = end($uriParts); // Obtener el último segmento, que es el ID

// Verificar que se haya proporcionado un ID
if (empty($id)) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de usuario no proporcionado']);
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

