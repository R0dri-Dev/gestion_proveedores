<?php
session_start();
require_once '../config/database.php';
require_once '../modelos/Usuario.php';

$database = new Database();
$db = $database->getConnection();

$usuario = new Usuario($db);

// Iniciar sesión
if ($_POST && isset($_POST['login'])) {
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];

    $user = $usuario->login($email, $contrasena);
    if ($user) {
        $_SESSION['usuario'] = $user; // Guardar datos del usuario en la sesión
        header("Location: dashboard.php"); // Redirigir al dashboard
        exit();
    } else {
        $error_mensaje = "Credenciales incorrectas";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            max-width: 450px;
            width: 90%;
            padding: 2rem;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(50, 50, 93, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h1 {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .login-header p {
            color: var(--secondary-color);
        }
        
        .form-control {
            border-radius: 5px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            border: 1px solid #d1d3e2;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }
        
        .input-group-text {
            background-color: #f8f9fc;
            border: 1px solid #d1d3e2;
            border-right: none;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        
        .btn-primary:hover {
            background-color: #3a5ecf;
            border-color: #3a5ecf;
            transform: translateY(-1px);
        }
        
        .divider {
            position: relative;
            text-align: center;
            margin: 1.5rem 0;
        }
        
        .divider::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background-color: #e3e6f0;
        }
        
        .divider span {
            position: relative;
            background: #fff;
            padding: 0 1rem;
            color: var(--secondary-color);
        }
        
        .alert {
            border-radius: 5px;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>¡Bienvenido!</h1>
            <p>Ingresa tus credenciales para acceder</p>
        </div>
        
        <?php if(isset($error_mensaje)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error_mensaje; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        
        <form method="POST" class="needs-validation" novalidate>
            <div class="mb-4">
                <label for="email" class="form-label">Correo electrónico</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" id="email" class="form-control" placeholder="correo@ejemplo.com" required>
                    <div class="invalid-feedback">
                        Por favor ingresa un correo electrónico válido.
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <a href="#" class="text-decoration-none small">¿Olvidaste tu contraseña?</a>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="contrasena" id="contrasena" class="form-control" placeholder="••••••••" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                    <div class="invalid-feedback">
                        Por favor ingresa tu contraseña.
                    </div>
                </div>
            </div>
            
            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Recordar sesión</label>
            </div>
            
            <button type="submit" name="login" class="btn btn-primary w-100">
                Iniciar Sesión <i class="fas fa-sign-in-alt ms-2"></i>
            </button>
        </form>
        
        <div class="divider">
            <span>O</span>
        </div>
        
        <p class="text-center mb-0">¿No tienes una cuenta? <a href="registro.php" class="text-decoration-none fw-bold">Regístrate aquí</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación de formulario
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
        
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('contrasena');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>