<?php
require_once '../config/database.php';
require_once '../modelos/Categoria.php';

$database = new Database();
$db = $database->getConnection();

$categoria = new Categoria($db);

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $categoria = $categoria->obtenerPorId($id);
    echo json_encode($categoria);
}
?>