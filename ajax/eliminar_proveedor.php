<?php
require_once '../config/database.php';
require_once '../modelos/Proveedor.php';

$database = new Database();
$db = $database->getConnection();

$proveedor = new Proveedor($db);

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    if ($proveedor->eliminar($id)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>