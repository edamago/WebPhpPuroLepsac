<?php
require_once '../../../models/ProductoModel.php';

header('Content-Type: application/json');

//$data = json_decode(file_get_contents("php://input"), true);
$data = $_POST;


if (!isset($data['id'])) {
    echo json_encode(['error' => 'ID no proporcionado']);
    exit;
}

$producto = new Producto();
// Depuración
$camposRequeridos = ['id','codigo','descripcion','unidad_medida','stock_minimo','stock_maximo','peso_bruto','peso_neto','alto','ancho','profundo','clasif_demanda','clasif_comercial','comentarios','estado','activo'];

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
$exito = $producto->actualizar($data['id'], $data);

/*$exito = $producto->actualizar(
    $data['id'],$data['codigo'], $data['descripcion'], $data['unidad_medida'], $data['stock_minimo'],
    $data['stock_maximo'], $data['peso_bruto'], $data['peso_neto'],
    $data['alto'], $data['ancho'], $data['profundo'], $data['clasif_demanda'],
    $data['clasif_comercial'], $data['comentarios'], $data['estado'],
    $data['activo']
);*/

echo json_encode(['success' => $exito]);
