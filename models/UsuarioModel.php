<?php
require_once __DIR__ . '/../config/Database.php';

class UsuarioModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function obtenerUsuarios() {
        $stmt = $this->conn->prepare("SELECT * FROM t_usuario");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener usuario por nombre de usuario
    public function obtenerUsuarioPorNombre($usuario) {
        $stmt = $this->conn->prepare("SELECT * FROM t_usuario WHERE usuario = ?");
        $stmt->execute([$usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear nuevo usuario
    public function crearUsuario($data) {
        $claveHash = password_hash($data['clave'], PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("
            INSERT INTO t_usuario (nombre, correo, estado, usuario, password, activo) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([ 
            $data['nombre'],
            $data['correo'],
            $data['estado'] ?? 'A',
            $data['usuario'],
            $claveHash,
            $data['activo'] ?? 1
        ]);
    }

    // Modificar un usuario existente
    public function modificarUsuario($id, $data) {
        $sql = "UPDATE t_usuario SET nombre = ?, correo = ?, estado = ?, usuario = ?, activo = ?";
    
        $params = [
            $data['nombre'],
            $data['correo'],
            $data['estado'],
            $data['usuario'],
            $data['activo'],
        ];
    
        if (!empty($data['clave'])) {
            $sql .= ", password = ?";
            $params[] = password_hash($data['clave'], PASSWORD_DEFAULT);
        }
    
        $sql .= " WHERE id = ?";
        $params[] = $id;
    
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    // Eliminar un usuario por ID
    public function eliminarUsuario($id) {
        // Preparar la consulta SQL para eliminar el usuario
        $stmt = $this->conn->prepare("DELETE FROM t_usuario WHERE id = ?");
        
        // Ejecutar la consulta con el ID proporcionado
        return $stmt->execute([$id]);
    }
    
}
