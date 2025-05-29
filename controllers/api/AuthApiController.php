<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../models/UsuarioModel.php';
require_once __DIR__ . '/../../helpers/AuthHelper.php';

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

    public function listarUsuarios() {
    try {
        $usuarios = $this->usuarioModel->obtenerUsuarios();

        if (!empty($usuarios)) {
            return ['success' => true, 'data' => $usuarios];
        } else {
            return ['success' => false, 'error' => 'No se encontraron usuarios'];
        }
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Error al listar usuarios', 'details' => $e->getMessage()];
    }
}


    public function crearUsuario($input) {
        header('Content-Type: application/json');

        // Validar token con AuthHelper
        AuthHelper::validarToken();

        if (empty($input['nombre']) || empty($input['correo']) || empty($input['usuario']) || empty($input['clave'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Todos los campos son requeridos']);
            exit;
        }

        try {
            $resultado = $this->usuarioModel->crearUsuario($input);

            if (isset($resultado['success']) && $resultado['success'] === true) {
                echo json_encode(['success' => true, 'message' => 'Usuario creado correctamente']);
            } else {
                http_response_code(400);
                $errorMsg = $resultado['error'] ?? 'No se pudo crear el usuario';
                echo json_encode(['error' => $errorMsg]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error al crear el usuario', 'details' => $e->getMessage()]);
        }
        exit;
    }

    public function modificarUsuario($id, $data) {
    // No enviar header ni json_encode aquí, solo lógica y retorno
    // Validar token fuera de esta función (en el endpoint)

    // Validar campos requeridos
    if (empty($data['nombre']) || empty($data['correo']) || empty($data['usuario'])) {
        return [
            'success' => false,
            'error' => 'Los campos nombre, correo y usuario son requeridos'
        ];
    }

    try {
        $resultado = $this->usuarioModel->modificarUsuario($id, $data);

        if (isset($resultado['success']) && $resultado['success'] === true) {
            return [
                'success' => true,
                'message' => 'Usuario modificado correctamente'
            ];
        } else {
            return [
                'success' => false,
                'error' => $resultado['error'] ?? 'No se pudo modificar el usuario'
            ];
        }
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => 'Error al modificar el usuario',
            'details' => $e->getMessage()
        ];
    }
}


    //public function eliminarUsuario($id) {
    //    $usuarioModel = new UsuarioModel();
    //    $resultado = $usuarioModel->eliminarUsuario($id);

    //    if ($resultado) {
    //        return ['success' => true];
    //    } else {
    //        return ['success' => false, 'error' => 'No se pudo eliminar el usuario'];
    //    }
    //}

    public function eliminarUsuario($id) {
    $usuario = $this->usuarioModel->obtenerUsuarioPorId($id); // Asegúrate de tener este método en tu modelo

    if (!$usuario) {
        return ['success' => false, 'error' => 'Usuario no encontrado'];
    }

    $resultado = $this->usuarioModel->eliminarUsuario($id);

    if ($resultado) {
        return ['success' => true];
    } else {
        return ['success' => false, 'error' => 'No se pudo eliminar el usuario'];
    }
}

    // Eliminé el método verificar_token porque ya no se usa
}

