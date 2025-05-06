<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/pro/controllers/api/ProductoApiController.php';

header('Content-Type: application/json');

// Crear instancia del controlador
$controller = new ProductoApiController();
$controller->handleRequest('obtener');
