<?php
//require_once '../../../controllers/api/PedidoApiController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/pro/controllers/api/PedidoApiController.php';

$data = json_decode(file_get_contents("php://input"), true);

$controller = new PedidoApiController();
$response = $controller->crear($data);

http_response_code($response['status']);
echo json_encode($response);
