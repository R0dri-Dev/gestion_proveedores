<?php
class Proveedor {
    private $conn;
    private $table = 'proveedores';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear un proveedor
    public function crear($nombre_empresa, $contacto_id, $telefono, $email, $direccion, $categoria_id) {
        // Verificar si el email ya existe
        $query = "SELECT id FROM {$this->table} WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            // Si el email ya existe, retornar false
            return false;
        }
    
        // Si el email no existe, insertar el nuevo proveedor
        $query = "INSERT INTO {$this->table} (nombre_empresa, contacto_id, telefono, email, direccion, categoria_id) 
                  VALUES (:nombre_empresa, :contacto_id, :telefono, :email, :direccion, :categoria_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre_empresa', $nombre_empresa);
        $stmt->bindParam(':contacto_id', $contacto_id);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':categoria_id', $categoria_id);
        return $stmt->execute();
    }

    // Leer todos los proveedores
    public function leer() {
        $query = "SELECT p.*, c.nombre as categoria_nombre FROM {$this->table} p LEFT JOIN categorias c ON p.categoria_id = c.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obtener un proveedor por ID
    public function obtenerPorId($id) {
        $query = "SELECT p.*, c.nombre as categoria_nombre FROM {$this->table} p LEFT JOIN categorias c ON p.categoria_id = c.id WHERE p.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar un proveedor
    public function actualizar($id, $nombre_empresa, $contacto_id, $telefono, $email, $direccion, $categoria_id) {
        // Verificar si el email ya existe (excepto para el proveedor actual)
        $query = "SELECT id FROM {$this->table} WHERE email = :email AND id != :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            // Si el email ya existe, retornar false
            return false;
        }
    
        // Si el email no existe, actualizar el proveedor
        $query = "UPDATE {$this->table} 
                  SET nombre_empresa = :nombre_empresa, contacto_id = :contacto_id, telefono = :telefono, 
                      email = :email, direccion = :direccion, categoria_id = :categoria_id 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre_empresa', $nombre_empresa);
        $stmt->bindParam(':contacto_id', $contacto_id);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':categoria_id', $categoria_id);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Eliminar un proveedor
    public function eliminar($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function buscar($termino) {
        $query = "SELECT p.*, c.nombre as categoria_nombre FROM {$this->table} p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  WHERE p.nombre_empresa LIKE :termino OR p.email LIKE :termino";
        $stmt = $this->conn->prepare($query);
        $termino = "%$termino%";
        $stmt->bindParam(':termino', $termino);
        $stmt->execute();
        return $stmt;
    }

    public function contar() {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'];
    }

    public function contarProveedoresPorCategoria() {
        $query = "SELECT c.nombre as categoria, COUNT(p.id) as total 
                  FROM proveedores p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  GROUP BY c.nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarProveedoresPorMes() {
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