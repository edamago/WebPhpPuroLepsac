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
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE activo = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener($id) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorCodigo($codigo) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE codigo = ?");
        $stmt->execute([$codigo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Validar si el código ya existe (excluyendo el producto actual si es una actualización)
    public function verificarCodigoExistente($codigo, $idExcluido = null) {
        // Consulta para verificar si el código ya está en uso por otro producto
        $query = "SELECT COUNT(*) FROM $this->table WHERE codigo = ? AND id != ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$codigo, $idExcluido]);
        $resultado = $stmt->fetchColumn();
        return $resultado > 0; // Si hay productos con el mismo código, retorna true
    }

    public function insertar($data) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO $this->table 
                (codigo, descripcion, unidad_medida, stock_minimo, stock_maximo, peso_bruto, peso_neto, alto, ancho, profundo, clasif_demanda, clasif_comercial, comentarios, estado, activo)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $resultado = $stmt->execute([
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
            ]);

            return $resultado;
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') { // Código de error SQLSTATE para violación de clave única
                return ['error' => 'El código del producto ya existe.'];
            }
            error_log($e->getMessage());
            return ['error' => 'Error al insertar el producto.'];
        }
    }

    public function actualizar($id, $data) {
        // Validamos si el código ya está en uso por otro producto
        if ($this->verificarCodigoExistente($data['codigo'], $id)) {
            return ['error' => 'El código del producto ya está en uso.'];
        }

        // Si el código es único, se realiza la actualización
        $sql = "UPDATE $this->table SET 
            codigo = ?, 
            descripcion = ?, unidad_medida = ?, stock_minimo = ?, stock_maximo = ?, 
            peso_bruto = ?, peso_neto = ?, alto = ?, ancho = ?, profundo = ?, 
            clasif_demanda = ?, clasif_comercial = ?, comentarios = ?, estado = ?, activo = ?
            WHERE id = ?";
    
        $stmt = $this->conn->prepare($sql);
        $resultado = $stmt->execute([
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
        ]);

        return $resultado;
    }

    public function eliminar($id) {
        // Baja lógica: desactiva el producto
        $stmt = $this->conn->prepare("UPDATE $this->table SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>