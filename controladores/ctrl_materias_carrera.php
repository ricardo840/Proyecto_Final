<?php
require_once __DIR__ . '/../Modelos/mdl_materias_carrera.php';
require_once __DIR__ . '/../Modelos/mdl_materias.php';
require_once __DIR__ . '/../Modelos/mdl_carreras.php';

class CtrlMateriasCarrera {
    private $modelo;
    private $modeloMaterias;
    private $modeloCarreras;

    public function __construct() {
        $this->modelo = new MdlMateriasCarrera();
        $this->modeloMaterias = new MdlMaterias();
        $this->modeloCarreras = new MdlCarrera();
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
        $id_carrera = $_POST['id_carrera'] ?? '';
        
        if (empty($id_materia) || empty($id_carrera)) {
            $this->redirigir('Debe seleccionar materia y carrera', 'error');
        }
        
        if ($this->modelo->existeRelacion($id_materia, $id_carrera)) {
            $this->redirigir('Esta materia ya está asignada a la carrera', 'error');
        }
        
        $this->modelo->guardar($id_materia, $id_carrera) 
            ? $this->redirigir('Relación creada exitosamente', 'exito') 
            : $this->redirigir('Error al guardar la relación', 'error');
    }

    private function actualizarRelacion() {
        $id = $_POST['id'] ?? '';
        $id_materia = $_POST['id_materia'] ?? '';
        $id_carrera = $_POST['id_carrera'] ?? '';
        
        if (empty($id) || empty($id_materia) || empty($id_carrera)) {
            $this->redirigir('Datos incompletos para la actualización', 'error');
        }
        
        $this->modelo->actualizar($id, $id_materia, $id_carrera) 
            ? $this->redirigir('Relación actualizada exitosamente', 'exito') 
            : $this->redirigir('Error al actualizar la relación', 'error');
    }

    private function eliminarRelacion() {
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            $this->redirigir('ID de relación requerido', 'error');
        }
        
        $this->modelo->eliminar($id) 
            ? $this->redirigir('Relación eliminada exitosamente', 'exito') 
            : $this->redirigir('Error al eliminar la relación', 'error');
    }

    private function redirigir($mensaje, $tipo) {
        header("Location: ../materias_carrera.php?$tipo=" . urlencode($mensaje));
        exit;
    }
}

$controlador = new CtrlMateriasCarrera();
$controlador->procesarRequest();