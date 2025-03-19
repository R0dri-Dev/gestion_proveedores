<?php
session_start();
require_once '../config/database.php';
require_once '../modelos/Usuario.php';

$database = new Database();
$db = $database->getConnection();

$usuario = new Usuario($db);

// Registrar un usuario
if ($_POST && isset($_POST['registrar'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    // Verificar que las contraseñas coincidan
    if ($contrasena !== $confirmar_contrasena) {
        $error_mensaje = "Las contraseñas no coinciden";
    } else {
        if ($usuario->registrar($nombre, $email, $contrasena)) {
            $_SESSION['mensaje_exito'] = "Registro exitoso. Ahora puedes iniciar sesión.";
            header("Location: login.php");
            exit();
        } else {
            $error_mensaje = "El email ya está registrado";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | Sistema de Gestión</title>
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
        
        .register-container {
            max-width: 500px;
            width: 90%;
            padding: 2rem;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(50, 50, 93, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .register-header h1 {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .register-header p {
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
        
        .password-strength {
            height: 5px;
            margin-top: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
        }
        
        .password-strength div {
            height: 100%;
            border-radius: 5px;
            transition: width 0.3s ease;
        }
        
        .strength-weak {
            background-color: #e74a3b;
        }
        
        .strength-medium {
            background-color: #f6c23e;
        }
        
        .strength-strong {
            background-color: #1cc88a;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>Crear Cuenta</h1>
            <p>Complete el formulario para registrarse</p>
        </div>
        
        <?php if(isset($error_mensaje)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error_mensaje; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        
        <form method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre completo</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Tu nombre" required>
                    <div class="invalid-feedback">
                        Por favor ingresa tu nombre.
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" id="email" class="form-control" placeholder="correo@ejemplo.com" required>
                    <div class="invalid-feedback">
                        Por favor ingresa un correo electrónico válido.
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="contrasena" id="contrasena" class="form-control" placeholder="••••••••" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                    <div class="invalid-feedback">
                        Por favor ingresa una contraseña.
                    </div>
                </div>
                <div class="password-strength mt-2">
                    <div id="passwordStrength"></div>
                </div>
                <small class="text-muted mt-1" id="passwordHint">La contraseña debe tener al menos 8 caracteres</small>
            </div>
            
            <div class="mb-4">
                <label for="confirmar_contrasena" class="form-label">Confirmar contraseña</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" class="form-control" placeholder="••••••••" required>
                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                        <i class="fas fa-eye"></i>
                    </button>
                    <div class="invalid-feedback">
                        Por favor confirma tu contraseña.
                    </div>
                </div>
                <div class="invalid-feedback" id="passwordMismatch" style="display: none;">
                    Las contraseñas no coinciden.
                </div>
            </div>
            
            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input" id="termsCheck" required>
                <label class="form-check-label" for="termsCheck">
                    Acepto los <a href="#" class="text-decoration-none">términos y condiciones</a>
                </label>
                <div class="invalid-feedback">
                    Debes aceptar los términos y condiciones para continuar.
                </div>
            </div>
            
            <button type="submit" name="registrar" class="btn btn-primary w-100">
                Crear Cuenta <i class="fas fa-user-plus ms-2"></i>
            </button>
        </form>
        
        <div class="divider">
            <span>O</span>
        </div>
        
        <p class="text-center mb-0">¿Ya tienes una cuenta? <a href="login.php" class="text-decoration-none fw-bold">Inicia sesión aquí</a></p>
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
                    
                    // Verificar que las contraseñas coincidan
                    const password = document.getElementById('contrasena').value;
                    const confirmPassword = document.getElementById('confirmar_contrasena').value;
                    const mismatchFeedback = document.getElementById('passwordMismatch');
                    
                    if (password !== confirmPassword) {
                        event.preventDefault();
                        event.stopPropagation();
                        mismatchFeedback.style.display = 'block';
                    } else {
                        mismatchFeedback.style.display = 'none';
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
        
        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('confirmar_contrasena');
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
        
        // Password strength indicator
        document.getElementById('contrasena').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrength');
            const passwordHint = document.getElementById('passwordHint');
            
            // Determinar la fuerza de la contraseña
            let strength = 0;
            if (password.length >= 8) strength += 1;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 1;
            if (password.match(/[0-9]/)) strength += 1;
            if (password.match(/[^a-zA-Z0-9]/)) strength += 1;
            
            // Actualizar la barra de fuerza
            switch(strength) {
                case 0:
                    strengthBar.className = '';
                    strengthBar.style.width = '0%';
                    passwordHint.textContent = 'La contraseña debe tener al menos 8 caracteres';
                    break;
                case 1:
                    strengthBar.className = 'strength-weak';
                    strengthBar.style.width = '25%';
                    passwordHint.textContent = 'Contraseña débil - Intenta agregar mayúsculas, números o símbolos';
                    break;
                case 2:
                    strengthBar.className = 'strength-medium';
                    strengthBar.style.width = '50%';
                    passwordHint.textContent = 'Contraseña media - Agrega más combinaciones para mejorar';
                    break;
                case 3:
                    strengthBar.className = 'strength-medium';
                    strengthBar.style.width = '75%';
                    passwordHint.textContent = 'Contraseña buena';
                    break;
                case 4:
                    strengthBar.className = 'strength-strong';
                    strengthBar.style.width = '100%';
                    passwordHint.textContent = 'Contraseña fuerte';
                    break;
            }
        });
    </script>
</body>
</html>