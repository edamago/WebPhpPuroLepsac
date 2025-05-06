<?php
header('Content-Type: application/json');
//require_once '../../../controllers/api/AuthApiController.php';
require_once __DIR__ . '/../../../controllers/api/AuthApiController.php';

$controller = new AuthApiController();

// Validar el token antes de continuar
$headers = getallheaders();
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

// Llamar al método para listar usuarios
$controller->listarUsuarios();
