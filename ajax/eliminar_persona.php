<?php
require_once '../config/database.php';
require_once '../modelos/Persona.php';

$database = new Database();
$db = $database->getConnection();

$persona = new Persona($db);

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    if ($persona->eliminar($id)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>