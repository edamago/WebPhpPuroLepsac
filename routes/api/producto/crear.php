<?php
require_once '../../../models/ProductoModel.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

$producto = new Producto();
$exito = $producto->insertar($data); // <-- aquí se pasa como un array

if ($exito) {
    echo json_encode(['success' => true, 'mensaje' => 'Producto creado correctamente']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'mensaje' => 'Error al crear el producto']);
}
