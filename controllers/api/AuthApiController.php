<?php
require_once __DIR__ . '/../../config/Database.php';

class AuthApiController {
    private $conn;

    public function __construct() {
        // Usar el método getInstance() para obtener la instancia y luego llamar a getConnection()
        $this->conn = Database::getInstance()->getConnection();
    }

    public function login() {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents("php://input"), true);
        $usuario = $input['usuario'] ?? '';
        $clave = $input['clave'] ?? '';

        if (empty($usuario) || empty($clave)) {
            http_response_code(400);
            echo json_encode(['error' => 'Usuario y contraseña son requeridos']);
            return;
        }

        $stmt = $this->conn->prepare("SELECT * FROM t_usuario WHERE usuario = ?");
        $stmt->execute([$usuario]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($clave, $user['password'])) {
            echo json_encode([
                'success' => true,
                'usuario' => $user['usuario'],
                'nombre' => $user['nombre'],
                'correo' => $user['correo'],
                'estado' => $user['estado']
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Usuario o contraseña incorrectos']);
        }
    }

    public function crearUsuario() {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents("php://input"), true);
        $nombre = $input['nombre'] ?? '';
        $correo = $input['correo'] ?? '';
        $estado = $input['estado'] ?? 'A';
        $usuario = $input['usuario'] ?? '';
        $clave = $input['clave'] ?? '';
        $activo = isset($input['activo']) ? (int)$input['activo'] : 1;

        if (empty($nombre) || empty($correo) || empty($usuario) || empty($clave)) {
            http_response_code(400);
            echo json_encode(['error' => 'Todos los campos son requeridos']);
            return;
        }

        $claveHash = password_hash($clave, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("INSERT INTO t_usuario (nombre, correo, estado, usuario, password, activo) VALUES (?, ?, ?, ?, ?, ?)");

        if ($stmt->execute([$nombre, $correo, $estado, $usuario, $claveHash, $activo])) {
            echo json_encode(['success' => true, 'message' => 'Usuario creado correctamente']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al crear el usuario']);
        }
    }
}
