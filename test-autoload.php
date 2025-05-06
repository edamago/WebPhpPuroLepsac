<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

//require_once __DIR__ . '/../vendor/autoload.php'; // ajusta si es necesario
require_once __DIR__ . '/vendor/autoload.php';


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = 'ejemplo_clave_secreta';
$payload = [
    'iss' => 'test',
    'iat' => time(),
    'exp' => time() + 3600,
    'data' => [
        'id' => 1,
        'usuario' => 'admin'
    ]
];

$jwt = JWT::encode($payload, $key, 'HS256');
echo "Token generado correctamente: <br>$jwt";
