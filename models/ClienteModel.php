<?php
require_once __DIR__ . '/../config/Database.php';

class Cliente {
    private $conn;
    private $table = "t_cliente";

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    public function listar() {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE activo = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener($id) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertar($data) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO $this->table 
                (tipo_documento, documento, nombre, direccion, direccion_entrega, distrito, ciudad, tipo, clasif_comercial, comentarios, estado, activo)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            return $stmt->execute([
                $data['tipo_documento'],
                $data['documento'],
                $data['nombre'],
                $data['direccion'],
                $data['direccion_entrega'],
                $data['distrito'],
                $data['ciudad'],
                $data['tipo'],
                $data['clasif_comercial'],
                $data['comentarios'],
                $data['estado'],
                $data['activo']
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                return ['error' => 'El documento del cliente ya existe.'];
            }
            error_log($e->getMessage());
            return ['error' => 'Error al insertar el cliente.'];
        }
    }

    public function actualizar($id, $data) {
        $sql = "UPDATE $this->table SET 
            tipo_documento = ?, 
            documento = ?, 
            nombre = ?, 
            direccion = ?, 
            direccion_entrega = ?, 
            distrito = ?, 
            ciudad = ?, 
            tipo = ?, 
            clasif_comercial = ?, 
            comentarios = ?, 
            estado = ?, 
            activo = ?
            WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['tipo_documento'],
            $data['documento'],
            $data['nombre'],
            $data['direccion'],
            $data['direccion_entrega'],
            $data['distrito'],
            $data['ciudad'],
            $data['tipo'],
            $data['clasif_comercial'],
            $data['comentarios'],
            $data['estado'],
            $data['activo'],
            $id
        ]);
    }

    public function eliminar($id) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}