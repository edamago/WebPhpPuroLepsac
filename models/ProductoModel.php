<?php
require_once __DIR__ . '/../config/Database.php';

class Producto {
    private $conn;
    private $table = "t_producto";

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    public function listar() {
        $sql = "SELECT * FROM $this->table";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return ["error" => "Error en la preparación de la consulta"];
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    public function obtener($id) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function obtenerPorCodigo($codigo) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE codigo = ?");
        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function verificarCodigoExistente($codigo, $idExcluido = null) {
        if ($idExcluido !== null) {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM $this->table WHERE codigo = ? AND id != ?");
            $stmt->bind_param("si", $codigo, $idExcluido);
        } else {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM $this->table WHERE codigo = ?");
            $stmt->bind_param("s", $codigo);
        }

        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        return $fila['total'] > 0;
    }

    public function insertar($data) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO $this->table 
                (codigo, descripcion, unidad_medida, stock_minimo, stock_maximo, peso_bruto, peso_neto, alto, ancho, profundo, clasif_demanda, clasif_comercial, comentarios, estado, activo)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param(
                "sssiiidddssssss",
                $data['codigo'],
                $data['descripcion'],
                $data['unidad_medida'],
                $data['stock_minimo'],
                $data['stock_maximo'],
                $data['peso_bruto'],
                $data['peso_neto'],
                $data['alto'],
                $data['ancho'],
                $data['profundo'],
                $data['clasif_demanda'],
                $data['clasif_comercial'],
                $data['comentarios'],
                $data['estado'],
                $data['activo']
            );

            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                return ['error' => 'El código del producto ya existe.'];
            }
            error_log($e->getMessage());
            return ['error' => 'Error al insertar el producto.'];
        }
    }

    public function actualizar($id, $data) {
    if ($this->verificarCodigoExistente($data['codigo'], $id)) {
        return ['error' => 'El código del producto ya está en uso.'];
    }

    $sql = "UPDATE $this->table SET 
        codigo = ?, descripcion = ?, unidad_medida = ?, stock_minimo = ?, stock_maximo = ?, 
        peso_bruto = ?, peso_neto = ?, alto = ?, ancho = ?, profundo = ?, 
        clasif_demanda = ?, clasif_comercial = ?, comentarios = ?, estado = ?, activo = ?
        WHERE id = ?";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param(
        "sssiiidddssssssi",
        $data['codigo'],
        $data['descripcion'],
        $data['unidad_medida'],
        $data['stock_minimo'],
        $data['stock_maximo'],
        $data['peso_bruto'],
        $data['peso_neto'],
        $data['alto'],
        $data['ancho'],
        $data['profundo'],
        $data['clasif_demanda'],
        $data['clasif_comercial'],
        $data['comentarios'],
        $data['estado'],
        $data['activo'],
        $id
    );

    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        // Verificamos si el producto con ese ID existe
        $verificarStmt = $this->conn->prepare("SELECT COUNT(*) as total FROM $this->table WHERE id = ?");
        $verificarStmt->bind_param("i", $id);
        $verificarStmt->execute();
        $resultado = $verificarStmt->get_result();
        $fila = $resultado->fetch_assoc();

        if ($fila['total'] === 0) {
            return ['error' => 'No se encontró el producto con el ID especificado.'];
        } else {
            // Los datos existen, pero no hubo cambios. Lo consideramos éxito, no error.
            return true;
        }
    }

    return true;
}


    public function eliminar($id) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET activo = 0 WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
