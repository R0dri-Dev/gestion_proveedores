<?php
class Categoria {
    private $conn;
    private $table = 'categorias';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear una categoría
    public function crear($nombre, $descripcion) {
        $query = "INSERT INTO {$this->table} (nombre, descripcion) VALUES (:nombre, :descripcion)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        return $stmt->execute();
    }

    // Leer todas las categorías
    public function leer() {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obtener una categoría por ID
    public function obtenerPorId($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar una categoría
    public function actualizar($id, $nombre, $descripcion) {
        $query = "UPDATE {$this->table} SET nombre = :nombre, descripcion = :descripcion WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Eliminar una categoría
    public function eliminar($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function contar() {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'];
    }
}
?>