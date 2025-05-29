<?php
require_once __DIR__ . '/../config/Database.php';

class UsuarioModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function obtenerUsuarios() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM t_usuario");
            $stmt->execute();
            $result = $stmt->get_result();

            return [
                'success' => true,
                'data' => $result->fetch_all(MYSQLI_ASSOC)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Error al obtener usuarios: ' . $e->getMessage()
            ];
        }
    }
    public function obtenerUsuarioPorId($id) {
    try {
        $stmt = $this->conn->prepare("SELECT * FROM t_usuario WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        return $usuario; // devuelve null si no lo encuentra
    } catch (Exception $e) {
        return null;
    }
}

    public function obtenerUsuarioPorNombre($usuario) {
    try {
        $stmt = $this->conn->prepare("SELECT * FROM t_usuario WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        return $usuario;  // directamente el usuario o null si no existe
    } catch (Exception $e) {
        return null;
    }
}


public function crearUsuario($data) {
    try {
        // Verificar si el correo ya existe
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM t_usuario WHERE correo = ?");
        $stmt->bind_param("s", $data['correo']);
        $stmt->execute();
        $stmt->bind_result($correoExiste);
        $stmt->fetch();
        $stmt->close();

        if ((int)$correoExiste > 0) {
            return [
                'success' => false,
                'error' => 'El correo ya está registrado.'
            ];
        }

        // Verificar si el nombre de usuario ya existe
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM t_usuario WHERE usuario = ?");
        $stmt->bind_param("s", $data['usuario']);
        $stmt->execute();
        $stmt->bind_result($usuarioExiste);
        $stmt->fetch();
        $stmt->close();

        if ((int)$usuarioExiste > 0) {
            return [
                'success' => false,
                'error' => 'El nombre de usuario ya está registrado.'
            ];
        }

        // Insertar nuevo usuario
        $claveHash = password_hash($data['clave'], PASSWORD_DEFAULT);
        $estado = $data['estado'] ?? 'A';
        $activo = $data['activo'] ?? 1;

        $stmt = $this->conn->prepare("
            INSERT INTO t_usuario (nombre, correo, estado, usuario, password, activo) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "sssssi",
            $data['nombre'],
            $data['correo'],
            $estado,
            $data['usuario'],
            $claveHash,
            $activo
        );

        $stmt->execute();
        $stmt->close();

        return [
            'success' => true,
            'message' => 'Usuario creado correctamente'
        ];

    } catch (mysqli_sql_exception $e) {
        return [
            'success' => false,
            'error' => 'Error en la base de datos: ' . $e->getMessage()
        ];
    }
}



    public function modificarUsuario($id, $data) {
        try {
            $sql = "UPDATE t_usuario SET nombre = ?, correo = ?, estado = ?, usuario = ?, activo = ?";
            $params = [
                $data['nombre'],
                $data['correo'],
                $data['estado'],
                $data['usuario'],
                $data['activo'],
            ];
            $types = "ssssi";

            if (!empty($data['clave'])) {
                $sql .= ", password = ?";
                $params[] = password_hash($data['clave'], PASSWORD_DEFAULT);
                $types .= "s";
            }

            $sql .= " WHERE id = ?";
            $params[] = $id;
            $types .= "i";

            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param($types, ...$params);

            if (!$stmt->execute()) {
                throw new Exception("Error al actualizar usuario: " . $stmt->error);
            }

            return [
                'success' => true,
                'message' => 'Usuario actualizado correctamente'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Error al modificar usuario: ' . $e->getMessage()
            ];
        }
    }

    public function eliminarUsuario($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM t_usuario WHERE id = ?");
            $stmt->bind_param("i", $id);

            if (!$stmt->execute()) {
                throw new Exception("Error al eliminar usuario: " . $stmt->error);
            }

            return [
                'success' => true,
                'message' => 'Usuario eliminado correctamente'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Error al eliminar usuario: ' . $e->getMessage()
            ];
        }
    }
}

