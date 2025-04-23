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
        $sql = "UPDATE $this->table SET 
            codigo = ?, 
            descripcion = ?, unidad_medida = ?, stock_minimo = ?, stock_maximo = ?, 
            peso_bruto = ?, peso_neto = ?, alto = ?, ancho = ?, profundo = ?, 
            clasif_demanda = ?, clasif_comercial = ?, comentarios = ?, estado = ?, activo = ?
            WHERE id = ?";
    
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
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
    }

    public function eliminar($id) {
        // Baja lógica: desactiva el producto
        $stmt = $this->conn->prepare("UPDATE $this->table SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

