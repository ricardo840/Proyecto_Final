<?php
require_once __DIR__ . '/../Modelos/mdl_alumnos.php';

class CtrlAlumnos {
    private $modelo;

    public function __construct() {
        $this->modelo = new MdlAlumnos();
    }

    public function procesarRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            switch ($action) {
                case 'guardar':
                    $this->guardarAlumno();
                    break;
                case 'actualizar':
                    $this->actualizarAlumno();
                    break;
                case 'eliminar':
                    $this->eliminarAlumno();
                    break;
                default:
                    $this->redirigir('Acción inválida', 'error');
                    break;
            }
        }
    }

    private function guardarAlumno() {
        $datos = [
            'nombre' => $_POST['nombre'] ?? '',
            'ape_pa' => $_POST['ape_pa'] ?? '',
            'ape_ma' => $_POST['ape_ma'] ?? '',
            'genero' => $_POST['genero'] ?? '',
            'activo' => $_POST['activo'] ?? 0
        ];
        
        // Validación de campos requeridos
        if(empty($datos['nombre'])) {
            $this->redirigir('El nombre del alumno es requerido', 'error');
        }
        if (empty($datos['ape_pa'])) {
            $this->redirigir('El apellido paterno es requerido', 'error');
        }
        
        // Validación de caracteres no permitidos
        if (preg_match('/\d/', $datos['nombre'])) {
            $this->redirigir('El nombre no puede contener números', 'error');
        }
        if (preg_match('/\d/', $datos['ape_pa'])) {
            $this->redirigir('El apellido paterno no puede contener números', 'error');
        }
        if (!empty($datos['ape_ma']) && preg_match('/\d/', $datos['ape_ma'])) {
            $this->redirigir('El apellido materno no puede contener números', 'error');
        }
        
        $this->modelo->guardar($datos) 
            ? $this->redirigir('Alumno registrado exitosamente', 'exito') 
            : $this->redirigir('Error al registrar al alumno', 'error');
    }

    private function actualizarAlumno() {
        $id = $_POST['id_alumno'] ?? '';
        $datos = [
            'nombre' => $_POST['nombre'] ?? '',
            'ape_pa' => $_POST['ape_pa'] ?? '',
            'ape_ma' => $_POST['ape_ma'] ?? '',
            'genero' => $_POST['genero'] ?? '',
            'activo' => $_POST['activo'] ?? 0
        ];
        
        // Validación de campos requeridos
        if (empty($id)) {
            $this->redirigir('ID de alumno requerido', 'error');
        }
        if (empty($datos['nombre'])) {
            $this->redirigir('El nombre del alumno es requerido', 'error');
        }
        if (empty($datos['ape_pa'])) {
            $this->redirigir('El apellido paterno es requerido', 'error');
        }
        
        // Validación de caracteres no permitidos
        if (preg_match('/\d/', $datos['nombre'])) {
            $this->redirigir('El nombre no puede contener números', 'error');
        }
        if (preg_match('/\d/', $datos['ape_pa'])) {
            $this->redirigir('El apellido paterno no puede contener números', 'error');
        }
        if (!empty($datos['ape_ma']) && preg_match('/\d/', $datos['ape_ma'])) {
            $this->redirigir('El apellido materno no puede contener números', 'error');
        }
        
        $this->modelo->actualizar($id, $datos) 
            ? $this->redirigir('Alumno actualizado exitosamente', 'exito') 
            : $this->redirigir('Error al actualizar al alumno', 'error');
    }

    private function eliminarAlumno() {
        $id = $_POST['id_alumno'] ?? '';
        
        if (empty($id)) {
            $this->redirigir('ID de alumno requerido', 'error');
        }
        
        $this->modelo->eliminar($id) 
            ? $this->redirigir('Alumno dado de baja exitosamente', 'exito') 
            : $this->redirigir('Error al dar de baja al alumno', 'error');
    }

    private function redirigir($mensaje, $tipo) {
        // Conservar el número de página en la redirección
        $pagina = $_GET['pagina'] ?? 1;
        header("Location: ../alumnos.php?$tipo=" . urlencode($mensaje) . "&pagina=$pagina");
        exit;
    }
}

$controlador = new CtrlAlumnos();
$controlador->procesarRequest();