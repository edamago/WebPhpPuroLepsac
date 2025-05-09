<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/pro/controllers/api/ClienteApiController.php';

header('Content-Type: application/json');

// Crear una instancia del controlador
$clienteApiController = new ClienteApiController();

// Llamar al método de manejar la solicitud de listado
$clienteApiController->handleRequest('listar');