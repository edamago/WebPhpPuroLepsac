<?php

use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

// Define the base path for views
if (!defined('VISTA_BASE')) {
    define('VISTA_BASE', __DIR__ . '/../views/');
}

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

return [
    'app_name' => $_ENV['APP_NAME'] ?? 'Mi Empresa',
    'server_name' => $_ENV['SERVER_NAME'] ?? 'localhost',
    'db' => [
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'name' => $_ENV['DB_NAME'] ?? 'bdempresa',
        'user' => $_ENV['DB_USER'] ?? 'root',
        'password' => $_ENV['DB_PASSWORD'] ?? ''
    ],
    'jwt_secret' => $_ENV['JWT_SECRET'] ?? 'tu_clave_secreta_predeterminada' // Clave secreta para JWT
];

