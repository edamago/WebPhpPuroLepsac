<?php
header('Content-Type: application/json');
require_once '../../../controllers/api/AuthApiController.php';

$data = json_decode(file_get_contents('php://input'), true);

$controller = new AuthApiController();
$response = $controller->login($data);

echo json_encode($response);
