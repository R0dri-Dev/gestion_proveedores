<?php
class Usuario {
    private $conn;
    private $table = 'usuarios';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Registrar un usuario
    public function registrar($nombre, $email, $contrasena) {
        // Verificar si el email ya existe
        $query = "SELECT id FROM {$this->table} WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return false; // El email ya está registrado
        }

        // Hash de la contraseña
        $hashed_password = password_hash($contrasena, PASSWORD_BCRYPT);

        // Insertar el nuevo usuario
        $query = "INSERT INTO {$this->table} (nombre, email, contrasena, rol) VALUES (:nombre, :email, :contrasena, 'usuario')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':contrasena', $hashed_password);
        return $stmt->execute();
    }

    // Iniciar sesión
    public function login($email, $contrasena) {
        $query = "SELECT * FROM {$this->table} WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            return $usuario;
        }
        return false;
    }
}
?>