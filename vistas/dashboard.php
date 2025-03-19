<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/database.php';
require_once '../modelos/Persona.php';
require_once '../modelos/Proveedor.php';
require_once '../modelos/Categoria.php';

$database = new Database();
$db = $database->getConnection();

$persona = new Persona($db);
$proveedor = new Proveedor($db);
$categoria = new Categoria($db);

// Obtener estadísticas
$total_personas = $persona->contar();
$total_proveedores = $proveedor->contar();
$total_categorias = $categoria->contar();

// Obtener datos para gráficos
$proveedores_por_categoria = $proveedor->contarProveedoresPorCategoria();
$personas_por_mes = $persona->contarPersonasPorMes();
$proveedores_por_mes = $proveedor->contarProveedoresPorMes();

// Preparar datos para el gráfico de actividad mensual
$actividad_mensual = [
    'labels' => ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    'personas' => array_fill(0, 12, 0), // Inicializar con 0
    'proveedores' => array_fill(0, 12, 0) // Inicializar con 0
];

foreach ($personas_por_mes as $item) {
    $actividad_mensual['personas'][$item['mes'] - 1] = $item['total']; // Ajustar índice (meses de 1 a 12)
}

foreach ($proveedores_por_mes as $item) {
    $actividad_mensual['proveedores'][$item['mes'] - 1] = $item['total']; // Ajustar índice (meses de 1 a 12)
}

// Preparar datos para el gráfico de proveedores por categoría
$proveedores_por_categoria_data = [
    'labels' => array_column($proveedores_por_categoria, 'categoria'),
    'data' => array_column($proveedores_por_categoria, 'total')
];

// Calcular algunos KPIs
$promedio_personas_proveedor = $total_proveedores > 0 ? round($total_personas / $total_proveedores, 1) : 0;
$nuevos_este_mes = $actividad_mensual['personas'][date('n') - 1] + $actividad_mensual['proveedores'][date('n') - 1];
$tasa_crecimiento = rand(5, 15); // Simulado como porcentaje
?>

<?php include 'header.php'; ?>

<!-- Contenido del dashboard -->
<div class="content">
    <div class="container-fluid px-4">
        <!-- Encabezado de la página -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <div>
            <a href="../Files/generar_pdf.php" class="btn btn-sm btn-primary">
                <i class="fas fa-download fa-sm"></i> Generar Reporte PDF
            </a>
            </div>
        </div>

        <!-- Tarjetas de información -->
        <div class="row g-4">
            <!-- Tarjeta de Personas -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-left-primary h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Personas</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($total_personas); ?></div>
                                <div class="mt-2 text-success">
                                    <i class="fas fa-arrow-up fa-sm"></i>
                                    <span class="small"><?php echo $tasa_crecimiento; ?>% desde el mes pasado</span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Proveedores -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-left-success h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Proveedores</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($total_proveedores); ?></div>
                                <div class="mt-2 text-success">
                                    <i class="fas fa-check fa-sm"></i>
                                    <span class="small"><?php echo $nuevos_este_mes; ?> nuevos este mes</span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-building fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Categorías -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-left-info h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Categorías
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($total_categorias); ?></div>
                                <div class="mt-2 text-muted">
                                    <i class="fas fa-tags fa-sm"></i>
                                    <span class="small">Categorías activas</span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Promedio -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-left-warning h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Personas por Proveedor</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $promedio_personas_proveedor; ?></div>
                                <div class="mt-2 text-muted">
                                    <i class="fas fa-calculator fa-sm"></i>
                                    <span class="small">Promedio</span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-percentage fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos principales -->
        <div class="row mt-4">
            <!-- Gráfico de actividad mensual -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Actividad Mensual</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item" href="#">Ver detalles</a></li>
                                <li><a class="dropdown-item" href="#">Exportar datos</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Personalizar gráfico</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="actividadMensual"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico de proveedores por categoría -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Proveedores por Categoría</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink2" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownMenuLink2">
                                <li><a class="dropdown-item" href="#">Ver detalles</a></li>
                                <li><a class="dropdown-item" href="#">Exportar datos</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie">
                            <canvas id="proveedoresPorCategoria"></canvas>
                        </div>
                        <div class="mt-4 text-center small">
                            <?php foreach ($proveedores_por_categoria_data['labels'] as $index => $label): ?>
                            <span class="me-2">
                                <i class="fas fa-circle" style="color: <?php echo ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'][$index % 5]; ?>"></i> <?php echo $label; ?>
                            </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Proveedores por Categoría -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Detalle de Proveedores por Categoría</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink3" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownMenuLink3">
                                <li><a class="dropdown-item" href="#">Exportar a Excel</a></li>
                                <li><a class="dropdown-item" href="#">Exportar a PDF</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tablaProveedoresPorCategoria" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Categoría</th>
                                        <th>Cantidad de Proveedores</th>
                                        <th>Porcentaje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total = array_sum($proveedores_por_categoria_data['data']);
                                    foreach ($proveedores_por_categoria_data['labels'] as $index => $label): 
                                        $cantidad = $proveedores_por_categoria_data['data'][$index];
                                        $porcentaje = $total > 0 ? round(($cantidad / $total) * 100, 1) : 0;
                                    ?>
                                    <tr>
                                        <td><?php echo $label; ?></td>
                                        <td><?php echo $cantidad; ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-2"><?php echo $porcentaje; ?>%</div>
                                                <div class="progress flex-grow-1" style="height: 8px;">
                                                    <div class="progress-bar" role="progressbar" style="width: <?php echo $porcentaje; ?>%; background-color: <?php echo ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'][$index % 5]; ?>;" aria-valuenow="<?php echo $porcentaje; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total</th>
                                        <th><?php echo $total; ?></th>
                                        <th>100%</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts para gráficos -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuración de gráficos con estilos uniformes
    const fontFamily = '"Nunito", "Segoe UI", sans-serif';
    
    Chart.defaults.font.family = fontFamily;
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#858796';
    
    // Colores principales
    const primaryColor = '#4e73df';
    const successColor = '#1cc88a';
    const infoColor = '#36b9cc';
    const warningColor = '#f6c23e';
    const dangerColor = '#e74a3b';
    
    // Convertir los colores a versiones con transparencia
    function hexToRgba(hex, opacity) {
        const r = parseInt(hex.slice(1, 3), 16);
        const g = parseInt(hex.slice(3, 5), 16);
        const b = parseInt(hex.slice(5, 7), 16);
        return `rgba(${r}, ${g}, ${b}, ${opacity})`;
    }
    
    // Datos para el gráfico de actividad mensual
    const actividadMensualData = {
        labels: <?php echo json_encode($actividad_mensual['labels']); ?>,
        datasets: [
            {
                label: 'Personas',
                borderColor: primaryColor,
                backgroundColor: hexToRgba(primaryColor, 0.1),
                pointBackgroundColor: primaryColor,
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: primaryColor,
                tension: 0.3,
                data: <?php echo json_encode($actividad_mensual['personas']); ?>
            },
            {
                label: 'Proveedores',
                borderColor: successColor,
                backgroundColor: hexToRgba(successColor, 0.1),
                pointBackgroundColor: successColor,
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: successColor,
                tension: 0.3,
                data: <?php echo json_encode($actividad_mensual['proveedores']); ?>
            }
        ]
    };
    
    // Datos para el gráfico de proveedores por categoría
    const proveedoresPorCategoriaData = {
        labels: <?php echo json_encode($proveedores_por_categoria_data['labels']); ?>,
        datasets: [{
            data: <?php echo json_encode($proveedores_por_categoria_data['data']); ?>,
            backgroundColor: [primaryColor, successColor, infoColor, warningColor, dangerColor],
            hoverBackgroundColor: [
                hexToRgba(primaryColor, 0.8),
                hexToRgba(successColor, 0.8),
                hexToRgba(infoColor, 0.8),
                hexToRgba(warningColor, 0.8),
                hexToRgba(dangerColor, 0.8)
            ],
            hoverBorderColor: 'rgba(234, 236, 244, 1)',
        }]
    };
    
    // Gráfico de actividad mensual
    const actividadCanvas = document.getElementById('actividadMensual');
    new Chart(actividadCanvas, {
        type: 'line',
        data: actividadMensualData,
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                },
                y: {
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10
                    },
                    grid: {
                        color: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyColor: "#858796",
                    titleColor: "#6e707e",
                    titleMarginBottom: 10,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    padding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.raw;
                        }
                    }
                }
            }
        }
    });
    
    // Gráfico de proveedores por categoría
    const categoriasCanvas = document.getElementById('proveedoresPorCategoria');
    new Chart(categoriasCanvas, {
        type: 'doughnut',
        data: proveedoresPorCategoriaData,
        options: {
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyColor: "#858796",
                    titleColor: "#6e707e",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    displayColors: false,
                    caretPadding: 10,
                }
            }
        }
    });

    // Inicializar DataTables para la tabla de proveedores por categoría
    $(document).ready(function() {
        $('#tablaProveedoresPorCategoria').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
            },
            pageLength: 5,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]]
        });
    });
});
</script>

<?php include 'footer.php'; ?>