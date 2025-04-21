<?php
require_once '../../../models/ProductoModel.php';

header('Content-Type: application/json');

$producto = new Producto();
$resultado = $producto->listar();
echo json_encode($resultado);
