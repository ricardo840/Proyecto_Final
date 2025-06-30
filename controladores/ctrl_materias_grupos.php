<?php
require_once __DIR__ . '/../Modelos/mdl_materias_grupos.php';

class CtrlMateriasGrupos {
    private $modelo;

    public function __construct() {
        $this->modelo = new MdlMateriasGrupos();
    }

    public function procesarRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            switch ($action) {
                case 'guardar':
                    $this->guardarRelacion();
                    break;
                case 'actualizar':
                    $this->actualizarRelacion();
                    break;
                case 'eliminar':
                    $this->eliminarRelacion();
                    break;
                default:
                    $this->redirigir('Acción inválida', 'error');
                    break;
            }
        }
    }

    private function guardarRelacion() {
        $id_materia = $_POST['id_materia'] ?? '';
        $id_grupo = $_POST['id_grupo'] ?? '';
        $id_maestro = $_POST['id_maestro'] ?? '';
        
        if (empty($id_materia) || empty($id_grupo) || empty($id_maestro)) {
            $this->redirigir('Todos los campos son requeridos', 'error');
        }
        
        $this->modelo->guardar($id_materia, $id_grupo, $id_maestro) 
            ? $this->redirigir('Asignación creada exitosamente', 'exito') 
            : $this->redirigir('Error al guardar la asignación', 'error');
    }

    private function actualizarRelacion() {
        $id = $_POST['id'] ?? '';
        $id_materia = $_POST['id_materia'] ?? '';
        $id_grupo = $_POST['id_grupo'] ?? '';
        $id_maestro = $_POST['id_maestro'] ?? '';
        
        if (empty($id) || empty($id_materia) || empty($id_grupo) || empty($id_maestro)) {
            $this->redirigir('Datos incompletos para la actualización', 'error');
        }
        
        $this->modelo->actualizar($id, $id_materia, $id_grupo, $id_maestro) 
            ? $this->redirigir('Asignación actualizada exitosamente', 'exito') 
            : $this->redirigir('Error al actualizar la asignación', 'error');
    }

    private function eliminarRelacion() {
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            $this->redirigir('ID de asignación requerido', 'error');
        }
        
        $this->modelo->eliminar($id) 
            ? $this->redirigir('Asignación eliminada exitosamente', 'exito') 
            : $this->redirigir('Error al eliminar la asignación', 'error');
    }

    private function redirigir($mensaje, $tipo) {
        header("Location: ../materias_grupos.php?$tipo=" . urlencode($mensaje));
        exit;
    }
}

$controlador = new CtrlMateriasGrupos();
$controlador->procesarRequest();