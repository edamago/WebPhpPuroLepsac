<?php
// Habilitar CORS
//header("Access-Control-Allow-Origin: http://localhost:81");
//header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
//header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../../controllers/api/ClienteApiController.php';

// ✅ Instancia el controlador
$controller = new ClienteApiController();

// ✅ Llama al método que maneja la petición, usando la acción correcta
$controller->handleRequest('obtenerPorDocumento');
