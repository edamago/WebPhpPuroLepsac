<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/pro/controllers/api/AuthApiController.php';


header('Content-Type: application/json');

// Obtener el ID desde la URL
$id = $_GET['id'] ?? null;

// Verificar que el ID esté presente
if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'El ID del usuario es requerido']);
    exit;
}

// Obtener los datos del cuerpo de la solicitud (JSON)
$input = json_decode(file_get_contents("php://input"), true);

// Verificar que los datos requeridos estén presentes
$nombre = $input['nombre'] ?? '';
$correo = $input['correo'] ?? '';
$usuario = $input['usuario'] ?? '';
$estado = $input['estado'] ?? 'A';
$activo = isset($input['activo']) ? (int)$input['activo'] : 1;

if (empty($nombre) || empty($correo) || empty($usuario)) {
    http_response_code(400);
    echo json_encode(['error' => 'Todos los campos requeridos deben estar presentes']);
    exit;
}

// Crear una instancia del controlador
$authController = new AuthApiController();

// Llamar al método para modificar el usuario
$response = $authController->modificarUsuario($id, $nombre, $correo, $usuario, $estado, $activo);

// Devolver la respuesta en formato JSON
echo json_encode($response);
?>

