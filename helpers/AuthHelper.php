<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class AuthHelper {
    public static function validarToken() {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            http_response_code(401);
            echo json_encode(['error' => 'Token no proporcionado o mal formado']);
            exit;
        }

        $token = trim(str_replace('Bearer', '', $authHeader));

        try {
            $config = include __DIR__ . '/../config/config.php';
            $jwt_secret = $config['jwt_secret'];

            return JWT::decode($token, new Key($jwt_secret, 'HS256'));
        } catch (ExpiredException $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Token expirado']);
            exit;
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Token no válido']);
            exit;
        }
    }
}
