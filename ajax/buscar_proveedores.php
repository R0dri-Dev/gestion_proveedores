<?php
require_once '../config/database.php';
require_once '../modelos/Proveedor.php';

$database = new Database();
$db = $database->getConnection();

$proveedor = new Proveedor($db);

$termino = $_POST['termino'];
$stmt = $proveedor->buscar($termino);

$resultados = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $resultados[] = $row;
}

echo json_encode($resultados);
?>