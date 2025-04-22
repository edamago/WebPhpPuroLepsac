<?php
require_once '../../../models/ProductoModel.php';

header('Content-Type: application/json');

// Obtener el ID desde la URL (query string)
$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['error' => 'ID no proporcionado']);
    exit;
}

// Crear una instancia del modelo y realizar la eliminación
$producto = new Producto();
$exito = $producto->eliminar($id);

// Responder con el resultado de la eliminación
echo json_encode(['success' => $exito]);
