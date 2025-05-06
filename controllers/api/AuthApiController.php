<?php
require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__ . '/../../models/UsuarioModel.php';
use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;

class AuthApiController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }

    public function login($usuario, $clave) {
        $user = $this->usuarioModel->obtenerUsuarioPorNombre($usuario);

        if ($user && password_verify($clave, $user['password'])) {
            if ($user['estado'] === 'I') {
                http_response_code(403);
                return ['error' => 'El usuario tiene el estado inactivo'];
            }

            if ((int)$user['activo'] === 0) {
                http_response_code(403);
                return ['error' => 'El usuario está inactivo'];
            }

            $payload = [
                'iss' => 'tu-aplicacion',
                'aud' => 'tu-aplicacion',
                'iat' => time(),
                'exp' => time() + 3600,
                'data' => [
                    'id' => $user['id'],
                    'usuario' => $user['usuario'],
                    'nombre' => $user['nombre'],
                    'correo' => $user['correo'],
                    'estado' => $user['estado'],
                    'activo' => $user['activo']
                ]
            ];

            $config = include __DIR__ . '/../../config/config.php';
            $jwt_secret = $config['jwt_secret'];
            $jwt = FirebaseJWT::encode($payload, $jwt_secret, 'HS256');

            return [
                'success' => true,
                'token' => $jwt,
                'usuario' => $user['usuario'],
                'nombre' => $user['nombre'],
                'correo' => $user['correo'],
                'estado' => $user['estado'],
                'activo' => $user['activo']
            ];
        } else {
            http_response_code(401);
            return ['error' => 'Usuario o contraseña incorrectos'];
        }
    }

    public function crearUsuario($input, $token) {
        header('Content-Type: application/json');

        $usuarioAutenticado = $this->verificar_token($token);
        if (!$usuarioAutenticado) {
            http_response_code(401);
            echo json_encode(['error' => 'Acceso no autorizado']);
            exit;
        }

        if (empty($input['nombre']) || empty($input['correo']) || empty($input['usuario']) || empty($input['clave'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Todos los campos son requeridos']);
            exit;
        }

        try {
            $resultado = $this->usuarioModel->crearUsuario($input);

            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Usuario creado correctamente']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'No se pudo crear el usuario']);
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                http_response_code(409);
                if (strpos($e->getMessage(), 'correo') !== false) {
                    echo json_encode(['error' => 'El correo ya está registrado']);
                } elseif (strpos($e->getMessage(), 'usuario') !== false) {
                    echo json_encode(['error' => 'El nombre de usuario ya está registrado']);
                } else {
                    echo json_encode(['error' => 'Datos duplicados']);
                }
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Error al crear el usuario']);
            }
        }
        exit;
    }

    public function modificarUsuario($input, $token) {
        header('Content-Type: application/json');
    
        // Verificar el token antes de continuar
        $usuarioAutenticado = $this->verificar_token($token);
        if (!$usuarioAutenticado) {
            http_response_code(401);
            echo json_encode(['error' => 'Acceso no autorizado']);
            exit;
        }
    
        // Obtener los datos del input
        $id = $input['id'] ?? null;
        $nombre = $input['nombre'] ?? '';
        $correo = $input['correo'] ?? '';
        $estado = $input['estado'] ?? 'A';
        $usuario = $input['usuario'] ?? '';
        $activo = isset($input['activo']) ? (int)$input['activo'] : 1;
    
        if (!$id || empty($nombre) || empty($correo) || empty($usuario)) {
            http_response_code(400);
            echo json_encode(['error' => 'Todos los campos requeridos deben estar presentes']);
            exit;
        }
    
        // Preparar los datos como un array
        $data = [
            'nombre' => $nombre,
            'correo' => $correo,
            'estado' => $estado,
            'usuario' => $usuario,
            'activo' => $activo
        ];
    
        try {
            // Llamar al modelo para modificar el usuario
            $resultado = $this->usuarioModel->modificarUsuario($id, $data);
    
            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Usuario modificado correctamente']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'No se encontró el usuario para modificar']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al modificar el usuario']);
        }
    
        exit;
    }
    
    
    // Eliminar usuario por ID
    public function eliminarUsuario($id) {
        // Crear una instancia del modelo UsuarioModel
        $usuarioModel = new UsuarioModel();

        // Llamar al método eliminarUsuario del modelo
        $resultado = $usuarioModel->eliminarUsuario($id);

        // Verificar si la eliminación fue exitosa
        if ($resultado) {
            return ['success' => true];
        } else {
            return ['success' => false, 'error' => 'No se pudo eliminar el usuario'];
        }
    }


    public function verificar_token($token) {
        if (!$token) {
            http_response_code(401);
            echo json_encode(['error' => 'Token no proporcionado']);
            exit;
        }

        try {
            $config = include __DIR__ . '/../../config/config.php';
            $jwt_secret = $config['jwt_secret'];
            return FirebaseJWT::decode($token, new Key($jwt_secret, 'HS256'));
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Token no válido']);
            exit;
        }
    }
}
