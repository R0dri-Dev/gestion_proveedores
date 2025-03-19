<?php
require_once '../config/database.php';
require_once '../modelos/Categoria.php';
require_once '../modelos/Persona.php';

$database = new Database();
$db = $database->getConnection();

$tipo = $_POST['tipo'];

if ($tipo == 'categorias') {
    $categoria = new Categoria($db);
    $stmt = $categoria->leer();
    $resultados = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $resultados[] = $row;
    }
    echo json_encode($resultados);
} elseif ($tipo == 'personas') {
    $persona = new Persona($db);
    $stmt = $persona->leer();
    $resultados = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $resultados[] = $row;
    }
    echo json_encode($resultados);
}
?>