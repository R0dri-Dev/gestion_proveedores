<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header("Location: vistas/dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Gestión de Proveedores</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .hero-section {
            background-color: #343a40;
            color: white;
            padding: 80px 0;
            margin-bottom: 40px;
        }
        .feature-card {
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .btn-login {
            background-color: #343a40;
            color: white;
            padding: 10px 30px;
            font-size: 18px;
            border-radius: 30px;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background-color: #212529;
            color: white;
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .icon-feature {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #343a40;
        }
        .footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            margin-top: 50px;
        }
    </style>
</head>
<body>

    <div class="hero-section">
        <div class="container text-center">
            <h1 class="display-4">Bienvenido al Sistema de Gestión de Proveedores</h1>
            <p class="lead mb-4">La solución integral para administrar eficientemente tus proveedores y productos</p>
        </div>
    </div>

    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-md-12">
                <h2>Nuestro sistema le permite:</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card feature-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-users icon-feature"></i>
                        <h4 class="card-title">Gestión de Proveedores</h4>
                        <p class="card-text">Administre toda la información de sus proveedores en un solo lugar.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-boxes icon-feature"></i>
                        <h4 class="card-title">Control de Inventario</h4>
                        <p class="card-text">Seguimiento detallado de productos, existencias y movimientos.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-line icon-feature"></i>
                        <h4 class="card-title">Reportes Avanzados</h4>
                        <p class="card-text">Genere informes detallados para una mejor toma de decisiones.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-12 text-center">
                <p class="lead">Por favor, inicie sesión para acceder al panel de administración.</p>
                <a href="vistas/login.php" class="btn btn-login mt-3">
                    <i class="fas fa-lock mr-2"></i>Acceder al Sistema
                </a>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container text-center">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Sistema de Gestión de Proveedores. Todos los derechos reservados.</p>
            <p class="mb-0">Desarrollado por <a href="https://github.com/R0dri-Dev" target="_blank">Rodrigo Tejeda Riojas</a>.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>