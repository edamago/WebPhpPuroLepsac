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

    // Obtener el resultado
    $result = $stmt->get_result();

    // Retornar todos los registros como array asociativo
    return $result->fetch_all(MYSQLI_ASSOC);
}




    public function obtener($id) {
    $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = ?");
    if (!$stmt) {
        // Manejar error en la preparación de la consulta
        return null;
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}


    public function insertar($data) {
    $sql = "INSERT INTO $this->table 
        (tipo_documento, documento, nombre, direccion, direccion_entrega, distrito, ciudad, tipo, clasif_comercial, comentarios, estado, activo)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $this->conn->prepare($sql);

    try {
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
    } catch (mysqli_sql_exception $e) {
        // Captura error de clave duplicada
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            return ['error' => 'El documento del cliente ya existe.'];
        }
        // Otros errores
        return ['error' => 'Error al insertar el cliente: ' . $e->getMessage()];
    }
}


    public function actualizar($id, $data) {
    // Verificar si el cliente existe
    $sqlCheck = "SELECT COUNT(*) FROM $this->table WHERE id = ?";
    $stmtCheck = $this->conn->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $id);
    $stmtCheck->execute();
    $stmtCheck->bind_result($existe);
    $stmtCheck->fetch();
    $stmtCheck->close();

    if (!$existe) {
        return ['error' => 'Cliente no existe'];
    }

    // Actualizar cliente
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
    if (!$stmt) {
        return ['error' => 'Error al preparar la consulta'];
    }

    // Asumiendo que 'activo' es integer, 'id' también
    $stmt->bind_param(
        "sssssssssssii",
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
    );

    $success = $stmt->execute();

    if (!$success) {
        return ['error' => 'Error en la ejecución: ' . $stmt->error];
    }

    $affectedRows = $stmt->affected_rows;
    $stmt->close();

    // true si se modificó alguna fila, false si no hubo cambios
    return $affectedRows > 0;
}





    public function eliminar($id) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}