<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/pro/controllers/api/ProductoApiController.php';

header('Content-Type: application/json');

$controller = new ProductoApiController();
$controller->handleRequest('eliminar');
