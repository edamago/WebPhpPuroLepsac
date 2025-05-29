<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/pro/controllers/api/PedidoApiController.php';

$controller = new PedidoApiController();
$controller->handleRequest('listar');
