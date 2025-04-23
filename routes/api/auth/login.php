<?php
require_once '../../../controllers/api/AuthApiController.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);
$usuario = $input['usuario'] ?? '';
$clave = $input['clave'] ?? '';

if (empty($usuario) || empty($clave)) {
    http_response_code(400);
    echo json_encode(['error' => 'Usuario y contraseña son requeridos']);
    exit;
}

$authController = new AuthApiController();
$response = $authController->login($usuario, $clave);

echo json_encode($response);