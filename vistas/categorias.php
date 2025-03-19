<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/database.php';
require_once '../modelos/Categoria.php';

$database = new Database();
$db = $database->getConnection();

$categoria = new Categoria($db);

// Crear una categoría
if ($_POST && isset($_POST['crear'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    if ($categoria->crear($nombre, $descripcion)) {
        $mensaje = "Categoría creada correctamente";
        $clase = "success";
    } else {
        $mensaje = "Error al crear la categoría";
        $clase = "danger";
    }
}

// Leer todas las categorías
$stmt = $categoria->leer();
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
            <h4 class="mb-0">Gestión de Categorías</h4>
        </div>
        <div class="card-body">
            <!-- Búsqueda y botón para nueva categoría -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" id="busqueda" class="form-control" placeholder="Buscar categorías...">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCategoria">
                        <i class="fas fa-plus"></i> Nueva Categoría
                    </button>
                </div>
            </div>

            <!-- Tabla de categorías -->
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="resultados">
                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nombre']; ?></td>
                            <td><?php echo $row['descripcion']; ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-warning" onclick="editarCategoria(<?php echo $row['id']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="eliminarCategoria(<?php echo $row['id']; ?>)">
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

<!-- Modal para crear/editar categoría -->
<div class="modal fade" id="modalCategoria" tabindex="-1" role="dialog" aria-labelledby="modalCategoriaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalCategoriaLabel">Nueva Categoría</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre de la categoría" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Descripción de la categoría"></textarea>
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
            url: '../ajax/buscar_categorias.php',
            type: 'POST',
            data: { termino: $(this).val() },
            success: function(response) {
                let categorias = JSON.parse(response);
                let html = '';
                categorias.forEach(categoria => {
                    html += `<tr>
                        <td>${categoria.id}</td>
                        <td>${categoria.nombre}</td>
                        <td>${categoria.descripcion}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-warning" onclick="editarCategoria(${categoria.id})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger" onclick="eliminarCategoria(${categoria.id})">
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

// Funciones para editar y eliminar categorías
function editarCategoria(id) {
    $.ajax({
        url: '../ajax/obtener_categoria.php',
        type: 'POST',
        data: { id: id },
        success: function(response) {
            let categoria = JSON.parse(response);
            $('#modalCategoriaLabel').text('Editar Categoría');
            $('#nombre').val(categoria.nombre);
            $('#descripcion').val(categoria.descripcion);
            // Agregar campo oculto para identificar la acción de edición
            $('form').append('<input type="hidden" name="editar" value="1">');
            $('form').append('<input type="hidden" name="id" value="' + id + '">');
            $('#modalCategoria').modal('show');
        }
    });
}

function eliminarCategoria(id) {
    if (confirm('¿Está seguro que desea eliminar esta categoría?')) {
        $.ajax({
            url: '../ajax/eliminar_categoria.php',
            type: 'POST',
            data: { id: id },
            success: function(response) {
                alert('Categoría eliminada correctamente');
                location.reload();
            }
        });
    }
}
</script>