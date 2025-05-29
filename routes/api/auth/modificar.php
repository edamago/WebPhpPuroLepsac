<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/pro/helpers/AuthHelper.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pro/controllers/api/AuthApiController.php';

header('Content-Type: application/json');

try {
    // Validar el token
    AuthHelper::validarToken();
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

// Obtener el ID desde la URL
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    http_response_code(400);
    echo json_encode(['error' => 'El ID del usuario es requerido y debe ser numérico']);
    exit;
}

// Obtener los datos del cuerpo JSON
$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos JSON inválidos o vacíos']);
    exit;
}

$nombre = $input['nombre'] ?? '';
$correo = $input['correo'] ?? '';
$usuario = $input['usuario'] ?? '';
$estado = $input['estado'] ?? 'A';
$activo = isset($input['activo']) ? (int)$input['activo'] : 1;

if (empty($nombre) || empty($correo) || empty($usuario)) {
    http_response_code(400);
    echo json_encode(['error' => 'Los campos nombre, correo y usuario son requeridos']);
    exit;
}

$controller = new AuthApiController();

// Llamar a modificarUsuario y capturar resultado
$response = $controller->modificarUsuario($id, $input);

// Suponiendo que modificarUsuario retorna un array con 'success' y 'message' o 'error'
if ($response['success'] === true) {
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => $response['message']]);
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $response['error'] ?? 'Error al modificar usuario']);
}
