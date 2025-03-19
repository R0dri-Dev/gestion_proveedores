# Sistema de Gestión de Proveedores

Este sistema permite administrar proveedores, personas de contacto y categorías utilizando PHP puro y MariaDB. Proporciona operaciones completas de CRUD (Crear, Leer, Actualizar, Eliminar) para cada entidad, así como reportes y estadísticas básicas.

## Requisitos del Sistema

- PHP 7.4 o superior
- MariaDB 10.5 o superior
- Servidor web (Apache, Nginx, etc.)
- PDO PHP Extension
- Navegador web moderno

## Instalación

### 1. Clonar el Repositorio

```bash
git clone https://github.com/tu-usuario/sistema-proveedores.git
cd sistema-proveedores
```

### 2. Configurar la Base de Datos

1. Crea una base de datos en MariaDB:

```sql
CREATE DATABASE gestion_proveedores;
USE gestion_proveedores;
```

2. Crea las tablas necesarias:

```sql
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

CREATE TABLE `personas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `nombre_empresa` varchar(100) NOT NULL,
  `contacto_id` int(11) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `direccion` text DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `rol` enum('admin','editor','usuario') DEFAULT 'usuario',
  `email` varchar(100) NOT NULL,
  `contrasena` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;


```

### 3. Configurar la Conexión a la Base de Datos

Crea un archivo `config/database.php` con los siguientes contenidos:

```php
<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'gestion_proveedores';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
        return $this->conn;
    }
}
?>
```

### 4. Configurar el Servidor Web

Si utilizas Apache, asegúrate de que el archivo `.htaccess` esté configurado correctamente para manejar las rutas:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

## Estructura del Proyecto

```
PROJECT/
├── ajax/
|   └── buscar_proveedores.php
|   └── cargar_datos.php
|   └── eliminar_categoria.php
|   └── eliminar_persona.php
|   └── eliminar_proveedor.php
|   └── obtener_categoria.php
|   └── obtener_persona.php
|   └── obtener_proveedor.php
├── config/
│   └── database.php
├── files/
│   └── generar_pdf.php
├── models/
│   ├── Categoria.php
│   ├── Persona.php
│   └── Proveedor.php
|   └── Usuario.php
├── public/
│   ├── css/
│   ├── fuentes/
│   └── imagenes/
|   └── librerias/
├── vistas/
│   ├── Categorias.php
│   ├── Dashbaord.php
│   ├── footer.php
│   └── header.php
│   └── login.php
│   └── logout.php
│   └── personas.php
│   └── proveedores.php
│   └── registro.php
├── index.php
└── README.md
```

## Uso del Sistema

### Iniciar el Sistema

1. Navega a la URL del proyecto en tu navegador web (por ejemplo, `http://localhost/project/index.php`).
2. Utiliza el menú de navegación para acceder a las diferentes secciones del sistema.

### Gestión de Categorías

- **Listar Categorías**: Muestra todas las categorías registradas en el sistema.
- **Crear Categoría**: Registra una nueva categoría con nombre y descripción.
- **Editar Categoría**: Modifica los datos de una categoría existente.
- **Eliminar Categoría**: Elimina una categoría del sistema.

### Gestión de Personas

- **Listar Personas**: Muestra todas las personas registradas en el sistema.
- **Crear Persona**: Registra una nueva persona con sus datos de contacto.
- **Editar Persona**: Modifica los datos de una persona existente.
- **Eliminar Persona**: Elimina una persona del sistema.

### Gestión de Proveedores

- **Listar Proveedores**: Muestra todos los proveedores registrados en el sistema.
- **Crear Proveedor**: Registra un nuevo proveedor asociado a una categoría y persona de contacto.
- **Editar Proveedor**: Modifica los datos de un proveedor existente.
- **Eliminar Proveedor**: Elimina un proveedor del sistema.
- **Buscar Proveedor**: Encuentra proveedores por nombre o email.

### Reportes y Estadísticas

El sistema ofrece reportes básicos, como:
- Total de proveedores.
- Distribución de proveedores por categoría.
- Totales mensuales (función preparada para futura implementación).

## Características Principales

1. **Validación de datos**:
   - Verificación de correos electrónicos duplicados.
   - Validación de datos obligatorios.

2. **Relaciones entre entidades**:
   - Proveedores asociados a categorías.
   - Proveedores asociados a personas de contacto.

3. **Consultas avanzadas**:
   - Búsqueda de proveedores por término.
   - Conteo y estadísticas de proveedores por categoría.


## Solución de Problemas

### Errores de Conexión a la Base de Datos
- Verifica que los datos de conexión en `database.php` sean correctos.
- Asegúrate de que el servicio de MariaDB esté funcionando.
