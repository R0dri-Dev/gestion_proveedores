<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/database.php';
require_once '../modelos/Persona.php';

$database = new Database();
$db = $database->getConnection();

$persona = new Persona($db);

// Crear una persona
if ($_POST && isset($_POST['crear'])) {
    if ($persona->crear($_POST['nombre'], $_POST['apellido'], $_POST['telefono'], $_POST['email'])) {
        $mensaje = "Persona creada correctamente";
        $clase = "success";
    } else {
        $mensaje = "Error al crear la persona";
        $clase = "danger";
    }
}

// Leer todas las personas
$stmt = $persona->leer();
?>

<?php include 'header.php'; ?>

<div class="container mt-4">
    <?php if (isset($mensaje)): ?>
    <div class="alert alert-<?php echo $clase; ?> alert-dismissible fade show" role="alert">
        <?php echo $mensaje; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Gestión de Personas</h4>
        </div>
        <div class="card-body">
            <!-- Búsqueda de personas -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" id="busqueda" class="form-control" placeholder="Buscar personas...">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalPersona">
                        <i class="fas fa-plus"></i> Nueva Persona
                    </button>
                </div>
            </div>

            <!-- Tabla de personas -->
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="resultados">
                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nombre']; ?></td>
                            <td><?php echo $row['apellido']; ?></td>
                            <td><?php echo $row['telefono']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-warning" onclick="editarPersona(<?php echo $row['id']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="eliminarPersona(<?php echo $row['id']; ?>)">
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

<!-- Modal para crear/editar persona -->
<div class="modal fade" id="modalPersona" tabindex="-1" role="dialog" aria-labelledby="modalPersonaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalPersonaLabel">Nueva Persona</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
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
            url: '../ajax/buscar_personas.php',
            type: 'POST',
            data: { termino: $(this).val() },
            success: function(response) {
                let personas = JSON.parse(response);
                let html = '';
                personas.forEach(persona => {
                    html += `<tr>
                        <td>${persona.id}</td>
                        <td>${persona.nombre}</td>
                        <td>${persona.apellido}</td>
                        <td>${persona.telefono}</td>
                        <td>${persona.email}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-warning" onclick="editarPersona(${persona.id})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger" onclick="eliminarPersona(${persona.id})">
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

// Funciones para editar y eliminar personas
function editarPersona(id) {
    $.ajax({
        url: '../ajax/obtener_persona.php',
        type: 'POST',
        data: { id: id },
        success: function(response) {
            let persona = JSON.parse(response);
            $('#modalPersonaLabel').text('Editar Persona');
            $('#nombre').val(persona.nombre);
            $('#apellido').val(persona.apellido);
            $('#telefono').val(persona.telefono);
            $('#email').val(persona.email);
            // Agregar campo oculto para identificar la acción de edición
            $('form').append('<input type="hidden" name="editar" value="1">');
            $('form').append('<input type="hidden" name="id" value="' + id + '">');
            $('#modalPersona').modal('show');
        }
    });
}

function eliminarPersona(id) {
    if (confirm('¿Está seguro que desea eliminar esta persona?')) {
        $.ajax({
            url: '../ajax/eliminar_persona.php',
            type: 'POST',
            data: { id: id },
            success: function(response) {
                alert('Persona eliminada correctamente');
                location.reload();
            }
        });
    }
}
</script>