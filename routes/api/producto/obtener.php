<?php
require_once '../../../models/ProductoModel.php';

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID no proporcionado']);
    exit;
}

$producto = new Producto();
$resultado = $producto->obtener($_GET['id']);
echo json_encode($resultado);
