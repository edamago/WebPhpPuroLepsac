<?php
header('Content-Type: application/json');
//require_once '../../../controllers/api/AuthApiController.php';
require_once __DIR__ . '/../../../controllers/api/AuthApiController.php';

$headers = getallheaders();
$data = json_decode(file_get_contents('php://input'), true);

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

// Si el token es válido, continuar con la creación
$response = $controller->crearUsuario($data, $token);
echo json_encode($response);

