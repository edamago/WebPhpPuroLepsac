<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/pro/controllers/api/ProductoApiController.php';

header('Content-Type: application/json');

// Obtener el ID desde la URL (query string)
$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['error' => 'ID no proporcionado']);
    exit;
}

// Obtener los datos del cuerpo de la solicitud (JSON)
$data = json_decode(file_get_contents("php://input"), true);

// Verificar si todos los campos requeridos están presentes
$camposRequeridos = ['codigo', 'descripcion', 'unidad_medida', 'stock_minimo', 'stock_maximo', 'peso_bruto', 'peso_neto', 'alto', 'ancho', 'profundo', 'clasif_demanda', 'clasif_comercial', 'comentarios', 'estado', 'activo'];

$faltantes = [];
foreach ($camposRequeridos as $campo) {
    if (!isset($data[$campo])) {
        $faltantes[] = $campo;
    }
}

if (!empty($faltantes)) {
    echo json_encode(['error' => 'Campos faltantes: ' . implode(', ', $faltantes)]);
    exit;
}

// Crear una instancia del controlador y llamar al método para actualizar el producto
$productoApiController = new ProductoApiController();
$productoApiController->handleRequest('actualizar');

