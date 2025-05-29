<?php
header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT'] . '/pro/controllers/api/AuthApiController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pro/helpers/AuthHelper.php';

// Validar el token
try {
    $usuarioAutenticado = AuthHelper::validarToken(); // Retorna datos del usuario si es válido
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

// Obtener los datos del cuerpo de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

$controller = new AuthApiController();
$response = $controller->crearUsuario($data, $usuarioAutenticado);

// Verificar si la creación fue exitosa
if ($response && isset($response['success']) && $response['success'] === true) {
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => $response['message'] ?? 'Usuario creado exitosamente'
    ]);
} else {
    http_response_code(400);
    echo json_encode([
        'error' => $response['error'] ?? 'Error al crear usuario'
    ]);
}


