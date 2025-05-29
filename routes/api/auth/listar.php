<?php
header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT'] . '/pro/controllers/api/AuthApiController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pro/helpers/AuthHelper.php';

try {
    // Validar el token
    AuthHelper::validarToken();
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

$controller = new AuthApiController();
$response = $controller->listarUsuarios();

// Verificar si el listado fue exitoso
if ($response && isset($response['success']) && $response['success'] === true) {
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => $response['data']
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'error' => $response['error'] ?? 'Error al listar usuarios'
    ]);
}

