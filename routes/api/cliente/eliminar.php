<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/pro/controllers/api/ClienteApiController.php';

header('Content-Type: application/json');

$controller = new ClienteApiController();
$controller->handleRequest('eliminar');