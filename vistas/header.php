<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Proveedores</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --sidebar-width: 250px;
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --danger-color: #e74a3b;
            --warning-color: #f6c23e;
            --sidebar-bg: #4e73df;
            --sidebar-color: #fff;
        }
        
        body {
            font-family: 'Nunito', 'Segoe UI', sans-serif;
            background-color: #f8f9fc;
            overflow-x: hidden;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--sidebar-bg) 10%, #224abe 100%);
            color: var(--sidebar-color);
            transition: all 0.3s;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .sidebar .logo {
            padding: 1.5rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }
        
        .sidebar .logo i {
            font-size: 1.75rem;
        }
        
        .sidebar .logo span {
            font-weight: 700;
            font-size: 1.1rem;
        }
        
        .sidebar .nav-link {
            padding: 0.8rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-radius: 0.35rem;
            margin: 0.25rem 0.7rem;
            transition: all 0.2s;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        
        .sidebar .nav-link i {
            width: 1.25rem;
            text-align: center;
        }
        
        .sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            margin: 1rem 1rem 0.5rem;
        }
        
        .sidebar-heading {
            padding: 0 1rem;
            margin-top: 0.5rem;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            color: rgba(255, 255, 255, 0.6);
        }
        
        /* Content area */
        .content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s;
        }
        
        /* Topbar */
        .topbar {
            background-color: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            padding: 0.5rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 990;
        }
        
        .toggle-sidebar {
            background: none;
            border: none;
            color: var(--secondary-color);
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        .search-bar {
            position: relative;
            width: 25rem;
            max-width: 100%;
        }
        
        .search-bar input {
            border-radius: 2rem;
            padding-left: 2.5rem;
            background-color: #f8f9fc;
            border-color: #f8f9fc;
        }
        
        .search-bar i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary-color);
        }
        
        .user-dropdown img {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e3e6f0;
        }
        
        /* Main content */
        main {
            padding: 1.5rem;
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            padding: 0.75rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .card-header h6 {
            font-weight: 700;
            font-size: 1rem;
            color: var(--primary-color);
            margin-bottom: 0;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .content {
                margin-left: 0;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .search-bar {
                display: none;
            }
        }
        
        /* Toggle animation */
        .sidebar-toggled .sidebar {
            width: 6.5rem;
        }
        
        .sidebar-toggled .content {
            margin-left: 6.5rem;
        }
        
        .sidebar-toggled .sidebar .logo span,
        .sidebar-toggled .sidebar .nav-link span {
            display: none;
        }
        
        .sidebar-toggled .sidebar .nav-link {
            padding: 1rem;
            display: flex;
            justify-content: center;
        }
        
        .sidebar-toggled .sidebar .nav-link i {
            font-size: 1.25rem;
            width: auto;
        }
        
        .sidebar-toggled .sidebar-heading {
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <i class="fas fa-truck-loading"></i>
            <span>Gestión Proveedores</span>
        </div>
        
        <div class="sidebar-divider"></div>
        
        <div class="sidebar-heading">Principal</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="dashboard.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>
        
        <div class="sidebar-divider"></div>
        
        <div class="sidebar-heading">Gestión</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="personas.php">
                    <i class="fas fa-users"></i>
                    <span>Personas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="proveedores.php">
                    <i class="fas fa-building"></i>
                    <span>Proveedores</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="categorias.php">
                    <i class="fas fa-tags"></i>
                    <span>Categorías</span>
                </a>
            </li>
        </ul>
        
        <div class="sidebar-divider"></div>
        
        <div class="sidebar-heading">Configuración</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="perfil.php">
                    <i class="fas fa-user-cog"></i>
                    <span>Mi Perfil</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar Sesión</span>
                </a>
            </li>
        </ul>
    </aside>
    
    <!-- Content wrapper -->
    <div class="content">
        <!-- Top navigation -->
        <nav class="topbar">
            <div class="d-flex align-items-center">
                <button class="toggle-sidebar me-3">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="search-bar d-none d-md-block">
                    <i class="fas fa-search"></i>
                    <input type="text" class="form-control" placeholder="Buscar...">
                </div>
            </div>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" id="notificationsDropdown" data-bs-toggle="dropdown">
                        <i class="fas fa-bell fa-fw"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3+
                        </span>
                    </a>
                </div>
                <div class="dropdown ms-3">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown">
                        <span class="d-none d-lg-inline text-gray-600 small me-2">
                            <?php echo isset($_SESSION['usuario']['nombre']) ? $_SESSION['usuario']['nombre'] : 'Usuario'; ?>
                        </span>
                        <img src="/api/placeholder/32/32" alt="User" class="user-image">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="perfil.php"><i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i> Perfil</a></li>
                        <li><a class="dropdown-item" href="configuracion.php"><i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i> Configuración</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i> Cerrar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <!-- Main content container -->
        <main>
            <!-- Aquí va el contenido de la página -->
            
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar
        document.querySelector('.toggle-sidebar').addEventListener('click', function() {
            document.body.classList.toggle('sidebar-toggled');
            
            // For mobile
            if (window.innerWidth < 768) {
                document.querySelector('.sidebar').classList.toggle('show');
            }
        });
        
        // Close sidebar on mobile if clicked outside
        document.addEventListener('click', function(event) {
            if (window.innerWidth < 768) {
                if (!event.target.closest('.sidebar') && 
                    !event.target.closest('.toggle-sidebar') &&
                    document.querySelector('.sidebar').classList.contains('show')) {
                    document.querySelector('.sidebar').classList.remove('show');
                }
            }
        });
        
        // Set active menu item based on current page
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname.split('/').pop();
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>