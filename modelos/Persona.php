<?php
class Persona {
    private $conn;
    private $table = 'personas';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear una persona
    public function crear($nombre, $apellido, $telefono, $email) {
        // Verificar si el correo electrónico ya existe
        $query = "SELECT id FROM {$this->table} WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            // El correo electrónico ya existe, actualizar el registro
            $query = "UPDATE {$this->table} SET nombre = :nombre, apellido = :apellido, telefono = :telefono WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':email', $email);
            return $stmt->execute();
        } else {
            // Insertar el nuevo registro
            $query = "INSERT INTO {$this->table} (nombre, apellido, telefono, email) VALUES (:nombre, :apellido, :telefono, :email)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':email', $email);
            return $stmt->execute();
        }
    }

    // Leer todas las personas
    public function leer() {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obtener una persona por ID
    public function obtenerPorId($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar una persona
    public function actualizar($id, $nombre, $apellido, $telefono, $email) {
        $query = "UPDATE {$this->table} SET nombre = :nombre, apellido = :apellido, telefono = :telefono, email = :email WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Eliminar una persona
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

    public function contarPersonasPorMes() {
        // Si no hay columna fecha_registro, devolver un array vacío o con valores predeterminados
        return [
            ['mes' => 1, 'total' => 0],
            ['mes' => 2, 'total' => 0],
            ['mes' => 3, 'total' => 0],
            ['mes' => 4, 'total' => 0],
            ['mes' => 5, 'total' => 0],
            ['mes' => 6, 'total' => 0],
            ['mes' => 7, 'total' => 0],
            ['mes' => 8, 'total' => 0],
            ['mes' => 9, 'total' => 0],
            ['mes' => 10, 'total' => 0],
            ['mes' => 11, 'total' => 0],
            ['mes' => 12, 'total' => 0]
        ];
    }
    
}
?>