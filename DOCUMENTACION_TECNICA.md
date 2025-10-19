# Documentación Técnica - Sistema de Tutorías Universidad Politécnica de la Región Ribereña

## Tabla de Contenidos

1. [Información General](#información-general)
2. [Arquitectura del Sistema](#arquitectura-del-sistema)
3. [Estructura de Base de Datos](#estructura-de-base-de-datos)
4. [Componentes del Sistema](#componentes-del-sistema)
5. [Funcionalidades](#funcionalidades)
6. [Seguridad](#seguridad)
7. [Tecnologías Utilizadas](#tecnologías-utilizadas)
8. [Instalación y Configuración](#instalación-y-configuración)
9. [API y Endpoints](#api-y-endpoints)
10. [Mantenimiento](#mantenimiento)

---

## Información General

### Descripción del Proyecto
Sistema web integral para la gestión de tutorías de la Universidad Politécnica de la Región Ribereña. Permite administrar alumnos, maestros, carreras, grupos, materias y asignaciones de tutorías de manera eficiente.

### Características Principales
- ✅ Gestión completa de entidades académicas
- ✅ Sistema de autenticación y autorización
- ✅ Interfaz responsiva con modo oscuro/claro
- ✅ Paginación para grandes volúmenes de datos
- ✅ Validación de datos robusta
- ✅ Arquitectura MVC
- ✅ Seguridad implementada

---

## Arquitectura del Sistema

### Patrón MVC (Model-View-Controller)

```
📁 Proyecto_Final/
├── 📁 config/           # Configuraciones
├── 📁 controladores/    # Lógica de negocio (Controller)
├── 📁 modelos/          # Acceso a datos (Model)
├── 📁 vistas/           # Interfaz de usuario (View)
├── 📁 css/             # Estilos CSS
├── 📁 js/              # JavaScript
├── 📁 img/             # Imágenes y recursos
├── 📁 bd/              # Scripts de base de datos
└── 📁 vendor/          # Dependencias externas
```

### Flujo de Datos
```
Usuario → Vista → Controlador → Modelo → Base de Datos
                ↓
        Respuesta ← Vista ← Controlador ← Modelo
```

---

## Estructura de Base de Datos

### Esquema de Base de Datos: `proyecto3`

#### Tablas Principales

**1. `usuarios`**
```sql
- id (PK, AUTO_INCREMENT)
- nombre (VARCHAR)
- email (VARCHAR, UNIQUE)
- password (VARCHAR - Hash)
- tipo_usuario (ENUM: 'usuario', 'admin')
- fecha_registro (DATETIME)
```

**2. `alumnos`**
```sql
- id_alumno (PK, AUTO_INCREMENT)
- nombre (VARCHAR)
- ape_pa (VARCHAR)
- ape_ma (VARCHAR)
- genero (VARCHAR(1))
- activo (INT - Soft Delete)
```

**3. `maestros`**
```sql
- id_maestros (PK, AUTO_INCREMENT)
- nombre (VARCHAR)
- activo (INT - Soft Delete)
```

**4. `carreras`**
```sql
- id_carrera (PK, AUTO_INCREMENT)
- nombre (VARCHAR)
- activo (INT - Soft Delete)
```

**5. `grupos`**
```sql
- id_grupo (PK, AUTO_INCREMENT)
- grupo (VARCHAR)
- carrera (FK → carreras.id_carrera)
- activo (INT - Soft Delete)
```

**6. `materias`**
```sql
- id_materia (PK, AUTO_INCREMENT)
- nombre (VARCHAR)
- activo (INT - Soft Delete)
```

**7. `asignacion`**
```sql
- id_asignacion (PK, AUTO_INCREMENT)
- id_grupo (FK → grupos.id_grupo)
- id_maestros (FK → maestros.id_maestros)
- id_materia (FK → materias.id_materia)
- fecha (DATE)
- hora (TIME)
- id_alumno (FK → alumnos.id_alumno)
- M (INT - Masculino)
- F (INT - Femenino)
- activo (INT - Soft Delete)
```

**8. `materias_carrera`**
```sql
- id_materia_carrera (PK, AUTO_INCREMENT)
- id_materia (FK → materias.id_materia)
- id_carrera (FK → carreras.id_carrera)
- activo (INT - Soft Delete)
```

**9. `materias_grupo`**
```sql
- id_materia_grupo (PK, AUTO_INCREMENT)
- id_materia (FK → materias.id_materia)
- id_grupo (FK → grupos.id_grupo)
- id_maestro (FK → maestros.id_maestros)
- activo (INT - Soft Delete)
```

### Relaciones
- **Uno a Muchos**: Carreras → Grupos
- **Muchos a Muchos**: Materias ↔ Carreras (tabla intermedia: materias_carrera)
- **Muchos a Muchos**: Materias ↔ Grupos (tabla intermedia: materias_grupo)
- **Muchos a Muchos**: Asignaciones complejas con múltiples relaciones

---

## Componentes del Sistema

### 1. Modelos (📁 modelos/)

#### `conexion.php`
- **Propósito**: Clase singleton para manejo de conexión a base de datos
- **Características**:
  - Configuración PDO segura
  - Manejo de errores
  - Configuración UTF-8
  - Protección contra acceso directo

```php
class Conexion {
    private $usuario = "root";
    private $contraseña = "";
    private $db = "proyecto3";
    private $servidor = "localhost";
    
    public function conectar() {
        // Implementación PDO con manejo de errores
    }
}
```

#### Modelos de Entidades
Cada entidad tiene su modelo correspondiente:

- **`mdl_alumnos.php`**: CRUD para gestión de alumnos
- **`mdl_maestros.php`**: CRUD para gestión de maestros
- **`mdl_carreras.php`**: CRUD para gestión de carreras
- **`mdl_grupos.php`**: CRUD para gestión de grupos
- **`mdl_materias.php`**: CRUD para gestión de materias
- **`mdl_asignacion.php`**: CRUD para gestión de asignaciones
- **`mdl_materias_carrera.php`**: Gestión de relaciones materia-carrera
- **`mdl_materias_grupos.php`**: Gestión de relaciones materia-grupo

**Métodos Comunes en Modelos**:
- `obtenerTodos()`: Obtener todos los registros activos
- `obtenerPaginado($inicio, $limite)`: Obtener registros paginados
- `guardar($datos)`: Insertar nuevo registro
- `actualizar($id, $datos)`: Modificar registro existente
- `eliminar($id)`: Soft delete (marcar como inactivo)
- `buscarPorNombre($nombre)`: Búsqueda por nombre
- `contar[Entidad]()`: Contar total de registros

### 2. Controladores (📁 controladores/)

#### Estructura de Controladores
Cada controlador maneja la lógica de negocio para su entidad correspondiente:

```php
class CtrlAlumnos {
    private $modelo;
    
    public function __construct() {
        $this->modelo = new MdlAlumnos();
    }
    
    public function procesarRequest() {
        // Manejo de diferentes acciones POST
    }
    
    private function guardarAlumno() {
        // Validaciones y guardado
    }
    
    private function actualizarAlumno() {
        // Validaciones y actualización
    }
    
    private function eliminarAlumno() {
        // Eliminación lógica
    }
}
```

**Validaciones Implementadas**:
- Campos requeridos
- Validación de caracteres (no números en nombres)
- Sanitización de datos
- Verificación de existencia

### 3. Vistas (📁 vistas/)

#### `parte_superior.php`
- **Header HTML** con meta tags
- **Sidebar** con navegación
- **Topbar** con información de usuario
- **Tema dinámico** (claro/oscuro)
- **Responsive design**

#### `parte_inferior.php`
- **Footer** con información institucional
- **Scripts JavaScript** necesarios
- **Modales** y componentes interactivos

### 4. Archivos Principales

#### `index.php`
- **Página principal** del sistema
- **Dashboard** con información general
- **Acceso restringido** (requiere autenticación)

#### `init.php`
- **Inicialización segura** de sesiones
- **Protección CSRF**
- **Headers de seguridad**
- **Verificación de autenticación**

#### `login.php` / `registro.php`
- **Sistema de autenticación**
- **Registro de usuarios**
- **Hash de contraseñas**
- **Validación de datos**

---

## Funcionalidades

### 1. Gestión de Alumnos
- ✅ **CRUD completo** (Crear, Leer, Actualizar, Eliminar)
- ✅ **Paginación** para grandes volúmenes
- ✅ **Búsqueda** por nombre completo
- ✅ **Validaciones** de campos
- ✅ **Soft delete** (eliminación lógica)

### 2. Gestión de Maestros
- ✅ **CRUD completo**
- ✅ **Validación** de nombres (sin números)
- ✅ **Paginación**
- ✅ **Soft delete**

### 3. Gestión de Carreras
- ✅ **CRUD completo**
- ✅ **Validación** de nombres
- ✅ **Paginación**
- ✅ **Soft delete**

### 4. Gestión de Grupos
- ✅ **CRUD completo**
- ✅ **Relación** con carreras
- ✅ **Consultas** por carrera
- ✅ **Paginación**

### 5. Gestión de Materias
- ✅ **CRUD completo**
- ✅ **Paginación**
- ✅ **Soft delete**

### 6. Asignación de Tutorías
- ✅ **Gestión compleja** de asignaciones
- ✅ **Relaciones múltiples** (grupo, maestro, materia, alumno)
- ✅ **Control de fechas** y horarios
- ✅ **Distribución** por género

### 7. Sistema de Autenticación
- ✅ **Login/Logout**
- ✅ **Registro de usuarios**
- ✅ **Tipos de usuario** (usuario/admin)
- ✅ **Sesiones seguras**
- ✅ **Protección de rutas**

### 8. Interfaz de Usuario
- ✅ **Diseño responsivo**
- ✅ **Modo oscuro/claro**
- ✅ **Sidebar** con navegación
- ✅ **Tablas paginadas**
- ✅ **Modales** para formularios
- ✅ **Alertas** de éxito/error

---

## Seguridad

### 1. Protección de Acceso
```php
// Protección contra acceso directo
if (!defined('SECURE_ACCESS')) {
    http_response_code(403);
    die('Acceso denegado');
}
```

### 2. Autenticación y Autorización
- **Verificación de sesiones** en todas las páginas
- **Hash de contraseñas** con `password_hash()`
- **Verificación de IP** para prevenir hijacking
- **Tokens CSRF** para formularios

### 3. Validación de Datos
- **Sanitización** de inputs
- **Validación** de tipos de datos
- **Prevención** de inyección SQL con PDO
- **Escape** de salida HTML

### 4. Headers de Seguridad
```php
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
```

### 5. Configuración de Sesiones
```php
session_set_cookie_params([
    'lifetime' => 86400,
    'path' => '/',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Strict'
]);
```

---

## Tecnologías Utilizadas

### Backend
- **PHP 8.2+**: Lenguaje de programación principal
- **PDO**: Abstracción de base de datos
- **MySQL/MariaDB**: Sistema de gestión de base de datos

### Frontend
- **HTML5**: Estructura semántica
- **CSS3**: Estilos y diseño responsivo
- **JavaScript**: Interactividad del cliente
- **Bootstrap 4**: Framework CSS
- **FontAwesome**: Iconografía

### Herramientas de Desarrollo
- **DataTables**: Tablas interactivas
- **Chart.js**: Gráficos y visualizaciones
- **jQuery**: Manipulación DOM y AJAX

### Estructura de Archivos
- **Vendor**: Dependencias externas (Bootstrap, jQuery, etc.)
- **CSS**: Hojas de estilo personalizadas
- **JS**: Scripts JavaScript personalizados
- **IMG**: Recursos gráficos

---

## Instalación y Configuración

### Requisitos del Sistema
- **PHP**: 7.4 o superior
- **MySQL/MariaDB**: 5.7 o superior
- **Apache/Nginx**: Servidor web
- **Extensiones PHP**: PDO, PDO_MySQL, mbstring

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone [url-del-repositorio]
cd Proyecto_Final
```

2. **Configurar base de datos**
```bash
# Importar esquema
mysql -u root -p < bd/proyecto3.sql
```

3. **Configurar conexión**
```php
// config/database.php
return [
    'host' => 'localhost',
    'database' => 'proyecto3',
    'username' => 'tu_usuario',
    'password' => 'tu_contraseña'
];
```

4. **Configurar permisos**
```bash
chmod 755 -R .
chmod 644 config/database.php
```

5. **Acceder al sistema**
```
http://localhost/Proyecto_Final/
```

### Configuración de Producción

1. **Cambiar credenciales** de base de datos
2. **Configurar SSL** (HTTPS)
3. **Optimizar PHP** para producción
4. **Configurar backup** automático
5. **Monitoreo** de logs

---

## API y Endpoints

### Estructura de URLs

#### Páginas Principales
- `/` - Dashboard principal
- `/login.php` - Página de login
- `/registro.php` - Registro de usuarios
- `/logout.php` - Cerrar sesión

#### Gestión de Entidades
- `/alumnos.php` - Gestión de alumnos
- `/maestros.php` - Gestión de maestros
- `/carreras.php` - Gestión de carreras
- `/grupos.php` - Gestión de grupos
- `/materias.php` - Gestión de materias
- `/asignacion.php` - Gestión de tutorías

#### Controladores
- `/controladores/ctrl_alumnos.php` - Lógica de alumnos
- `/controladores/ctrl_maestros.php` - Lógica de maestros
- `/controladores/ctrl_carreras.php` - Lógica de carreras
- `/controladores/ctrl_grupos.php` - Lógica de grupos
- `/controladores/ctrl_materias.php` - Lógica de materias
- `/controladores/ctrl_asignacion.php` - Lógica de asignaciones

### Métodos HTTP
- **GET**: Consulta de datos
- **POST**: Creación y modificación de datos

### Parámetros de Query
- `pagina`: Número de página para paginación
- `exito`: Mensaje de éxito
- `error`: Mensaje de error

---

## Mantenimiento

### Logs y Monitoreo
- **Error logs**: PHP error_log()
- **Base de datos**: Logs de MySQL
- **Acceso**: Logs de Apache/Nginx

### Backup
```bash
# Backup de base de datos
mysqldump -u root -p proyecto3 > backup_$(date +%Y%m%d).sql

# Backup de archivos
tar -czf backup_files_$(date +%Y%m%d).tar.gz /ruta/del/proyecto
```

### Actualizaciones
1. **Backup** completo del sistema
2. **Pruebas** en ambiente de desarrollo
3. **Despliegue** gradual
4. **Verificación** de funcionalidades
5. **Rollback** si es necesario

### Optimización
- **Índices** en base de datos
- **Cache** de consultas frecuentes
- **Compresión** de assets
- **CDN** para recursos estáticos

---

## Consideraciones Adicionales

### Escalabilidad
- **Paginación** implementada para grandes volúmenes
- **Índices** en campos de búsqueda frecuente
- **Preparación** para balanceadores de carga

### Usabilidad
- **Interfaz intuitiva** con navegación clara
- **Feedback visual** para acciones del usuario
- **Modo oscuro/claro** para preferencias
- **Responsive design** para dispositivos móviles

### Extensibilidad
- **Arquitectura MVC** permite fácil extensión
- **Separación** de responsabilidades
- **Interfaces** claras entre componentes

---

**Desarrollado por**: Equipo "Los 5 Furiosos"  
**Institución**: Universidad Politécnica de la Región Ribereña  
**Año**: 2025  
**Versión**: 1.0

---

*Esta documentación técnica proporciona una visión completa del sistema de tutorías, incluyendo su arquitectura, funcionalidades, seguridad y mantenimiento.*
