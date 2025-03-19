<?php
require_once '../config/database.php';
require_once '../modelos/Categoria.php';

$database = new Database();
$db = $database->getConnection();

$categoria = new Categoria($db);

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    if ($categoria->eliminar($id)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>