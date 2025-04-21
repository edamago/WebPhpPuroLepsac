<?php
require_once '../../../models/ProductoModel.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    echo json_encode(['error' => 'ID no proporcionado']);
    exit;
}

$producto = new Producto();
$exito = $producto->eliminar($data['id']);
echo json_encode(['success' => $exito]);
