<?php
require_once __DIR__ . '/../modelos/mdl_asignacion.php';

class CtrlAsignacion {
    private $modelo;

    public function __construct() {
        $this->modelo = new MdlAsignacion();
    }

    public function procesarRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
            $action = $_GET['action'];
            
            if ($action === 'obtener') {
                $this->obtenerAsignacion();
            }
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            switch ($action) {
                case 'guardar':
                    $this->guardarAsignacion();
                    break;
                case 'actualizar':
                    $this->actualizarAsignacion();
                    break;
                case 'eliminar':
                    $this->eliminarAsignacion();
                    break;
                default:
                    $this->redirigir('Acción inválida', 'error');
                    break;
            }
        }
    }

    private function obtenerAsignacion() {
        $id = $_GET['id'] ?? '';
        
        if (empty($id)) {
            header('Content-Type: application/json');
            echo json_encode([]);
            exit;
        }
        
        $asignacion = $this->modelo->obtenerPorId($id);
        header('Content-Type: application/json');
        echo json_encode($asignacion);
        exit;
    }

    private function guardarAsignacion() {
        $id_grupo = $_POST['id_grupo'] ?? '';
        $id_maestro = $_POST['id_maestro'] ?? '';
        $id_materia = $_POST['id_materia'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $hora = $_POST['hora'] ?? '';
        $alumnos = $_POST['alumnos'] ?? [];
        
        // Validación básica
        if (empty($id_grupo) || empty($id_maestro) || empty($id_materia) || 
        empty($fecha) || empty($hora) || empty($alumnos)) {
            $this->redirigir('Todos los campos son requeridos y debe seleccionar al menos un alumno', 'error');
        }
        
        // Asegurarse que alumnos sea un array
        if (!is_array($alumnos)) {
            $alumnos = [$alumnos];
        }
        
        // Filtrar valores vacíos
        $alumnos = array_filter($alumnos, function($alumno) {
            return !empty($alumno);
        });
        
        if (empty($alumnos)) {
            $this->redirigir('Debe seleccionar al menos un alumno', 'error');
        }
        
        if ($this->modelo->guardar($id_grupo, $id_maestro, $id_materia, $fecha, $hora, $alumnos)) {
            $this->redirigir('Asignación creada exitosamente', 'exito');
        } else {
            $this->redirigir('Error al guardar la asignación', 'error');
        }
    }

    private function actualizarAsignacion() {
        $id_asignacion = $_POST['id_asignacion'] ?? '';
        $id_grupo = $_POST['id_grupo'] ?? '';
        $id_maestro = $_POST['id_maestro'] ?? '';
        $id_materia = $_POST['id_materia'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $hora = $_POST['hora'] ?? '';
        $alumnos = $_POST['alumnos'] ?? [];
        
        // Convertir alumnos a array si es un string
        if (!is_array($alumnos)) {
            $alumnos = [$alumnos];
        }
        
        // Validación básica
        if (empty($id_asignacion) || empty($id_grupo) || empty($id_maestro) || 
            empty($id_materia) || empty($fecha) || empty($hora) || empty($alumnos)) {
            $this->redirigir('Todos los campos son requeridos', 'error');
        }
        
        // Filtrar alumnos vacíos
        $alumnos = array_filter($alumnos, function($alumno) {
            return !empty($alumno);
        });
        
        if (empty($alumnos)) {
            $this->redirigir('Debe seleccionar al menos un alumno', 'error');
        }
        
        if ($this->modelo->actualizar($id_asignacion, $id_grupo, $id_maestro, $id_materia, $fecha, $hora, $alumnos)) {
            $this->redirigir('Asignación actualizada exitosamente', 'exito');
        } else {
            $this->redirigir('Error al actualizar la asignación', 'error');
        }
    }

    private function eliminarAsignacion() {
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            $this->redirigir('ID requerido', 'error');
        }
        
        $this->modelo->eliminar($id) 
            ? $this->redirigir('Asignación eliminada exitosamente', 'exito') 
            : $this->redirigir('Error al eliminar la asignación', 'error');
    }

    private function redirigir($mensaje, $tipo) {
        header("Location: ../asignacion.php?$tipo=" . urlencode($mensaje));
        exit;
    }
}

$controlador = new CtrlAsignacion();
$controlador->procesarRequest();