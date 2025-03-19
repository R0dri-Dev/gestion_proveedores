<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/database.php';
require_once '../modelos/Proveedor.php';
require_once '../modelos/Persona.php';
require_once '../modelos/Categoria.php';

$database = new Database();
$db = $database->getConnection();

$proveedor = new Proveedor($db);
$persona = new Persona($db);
$categoria = new Categoria($db);

// Mensajes de éxito o error
$mensaje = '';
$clase = '';

// Crear un proveedor
if ($_POST && isset($_POST['crear'])) {
    $nombre_empresa = $_POST['nombre_empresa'];
    $contacto_id = $_POST['contacto_id'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];
    $categoria_id = $_POST['categoria_id'];

    if ($proveedor->crear($nombre_empresa, $contacto_id, $telefono, $email, $direccion, $categoria_id)) {
        $mensaje = "Proveedor creado correctamente";
        $clase = "success";
    } else {
        $mensaje = "Error: El email ya está registrado";
        $clase = "danger";
    }
}

// Actualizar un proveedor
if ($_POST && isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nombre_empresa = $_POST['nombre_empresa'];
    $contacto_id = $_POST['contacto_id'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];
    $categoria_id = $_POST['categoria_id'];

    if ($proveedor->actualizar($id, $nombre_empresa, $contacto_id, $telefono, $email, $direccion, $categoria_id)) {
        $mensaje = "Proveedor actualizado correctamente";
        $clase = "success";
    } else {
        $mensaje = "Error: El email ya está registrado";
        $clase = "danger";
    }
}

// Leer todos los proveedores
$stmt = $proveedor->leer();
?>

<?php include 'header.php'; ?>

<div class="container mt-4">
    <?php if ($mensaje): ?>
    <div class="alert alert-<?php echo $clase; ?> alert-dismissible fade show" role="alert">
        <?php echo $mensaje; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Gestión de Proveedores</h4>
        </div>
        <div class="card-body">
            <!-- Búsqueda de proveedores -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" id="busqueda" class="form-control" placeholder="Buscar proveedores...">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalProveedor">
                        <i class="fas fa-plus"></i> Nuevo Proveedor
                    </button>
                </div>
            </div>

            <!-- Tabla de proveedores -->
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Empresa</th>
                            <th>Contacto</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Dirección</th>
                            <th>Categoría</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="resultados">
                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nombre_empresa']; ?></td>
                            <td><?php echo $row['contacto_id']; ?></td>
                            <td><?php echo $row['telefono']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['direccion']; ?></td>
                            <td><span class="badge badge-info"><?php echo $row['categoria_nombre']; ?></span></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-warning" onclick="editarProveedor(<?php echo $row['id']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="eliminarProveedor(<?php echo $row['id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear/editar proveedor -->
<div class="modal fade" id="modalProveedor" tabindex="-1" role="dialog" aria-labelledby="modalProveedorLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalProveedorLabel">Nuevo Proveedor</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre_empresa">Nombre de la empresa</label>
                                <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa" placeholder="Nombre de la empresa" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contacto_id">Contacto</label>
                                <select class="form-control" id="contacto_id" name="contacto_id" required>
                                    <option value="">Seleccionar contacto</option>
                                    <?php
                                    $personas = $persona->leer();
                                    while ($row = $personas->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='{$row['id']}'>{$row['nombre']} {$row['apellido']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="direccion">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="categoria_id">Categoría</label>
                                <select class="form-control" id="categoria_id" name="categoria_id" required>
                                    <option value="">Seleccionar categoría</option>
                                    <?php
                                    $categorias = $categoria->leer();
                                    while ($row = $categorias->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" name="crear">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<!-- Scripts -->
<script src="../public/librerias/jquery/jquery.min.js"></script>
<script src="../public/librerias/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    $('#busqueda').on('input', function() {
        $.ajax({
            url: '../ajax/buscar_proveedores.php',
            type: 'POST',
            data: { termino: $(this).val() },
            success: function(response) {
                let proveedores = JSON.parse(response);
                let html = '';
                proveedores.forEach(proveedor => {
                    html += `<tr>
                        <td>${proveedor.id}</td>
                        <td>${proveedor.nombre_empresa}</td>
                        <td>${proveedor.contacto_id}</td>
                        <td>${proveedor.telefono}</td>
                        <td>${proveedor.email}</td>
                        <td>${proveedor.direccion}</td>
                        <td><span class="badge badge-info">${proveedor.categoria_nombre}</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-warning" onclick="editarProveedor(${proveedor.id})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger" onclick="eliminarProveedor(${proveedor.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>`;
                });
                $('#resultados').html(html);
            }
        });
    });
});

// Funciones para editar y eliminar proveedores
function editarProveedor(id) {
    // Cargar datos del proveedor y abrir modal
    $.ajax({
        url: '../ajax/obtener_proveedor.php',
        type: 'POST',
        data: { id: id },
        success: function(response) {
            let proveedor = JSON.parse(response);
            $('#modalProveedorLabel').text('Editar Proveedor');
            $('#nombre_empresa').val(proveedor.nombre_empresa);
            $('#contacto_id').val(proveedor.contacto_id);
            $('#telefono').val(proveedor.telefono);
            $('#email').val(proveedor.email);
            $('#direccion').val(proveedor.direccion);
            $('#categoria_id').val(proveedor.categoria_id);
            // Agregar campo oculto para identificar la acción de edición
            $('form').append('<input type="hidden" name="editar" value="1">');
            $('form').append('<input type="hidden" name="id" value="' + id + '">');
            $('#modalProveedor').modal('show');
        }
    });
}

function eliminarProveedor(id) {
    if (confirm('¿Está seguro que desea eliminar este proveedor?')) {
        $.ajax({
            url: '../ajax/eliminar_proveedor.php',
            type: 'POST',
            data: { id: id },
            success: function(response) {
                alert('Proveedor eliminado correctamente');
                location.reload();
            }
        });
    }
}
</script>