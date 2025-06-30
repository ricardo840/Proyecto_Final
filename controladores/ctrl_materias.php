<?php
require_once __DIR__ . '/../modelos/mdl_materias.php';

class CtrlMaterias {
    private $modelo;

    public function __construct() {
        $this->modelo = new MdlMaterias();
    }

    public function procesarRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            switch ($action) {
                case 'guardar':
                    $this->guardarMateria();
                    break;
                case 'actualizar':
                    $this->actualizarMateria();
                    break;
                case 'eliminar':
                    $this->eliminarMateria();
                    break;
                default:
                    $this->redirigir('Acción inválida', 'error');
                    break;
            }
        }
    }

    private function guardarMateria() {
        $nombre = $_POST['nombre_materia'] ?? '';
        
        if (empty($nombre)) {
            $this->redirigir('El nombre de la materia es requerido', 'error');
        }
        
        $this->modelo->guardar($nombre) 
            ? $this->redirigir('Materia creada exitosamente', 'exito') 
            : $this->redirigir('Error al guardar la materia', 'error');
    }

    private function actualizarMateria() {
        $id = $_POST['id'] ?? '';
        $nombre = $_POST['nombre_materia'] ?? '';
        
        if (empty($id) || empty($nombre)) {
            $this->redirigir('Datos incompletos para la actualización', 'error');
        }
        
        $this->modelo->actualizar($id, $nombre) 
            ? $this->redirigir('Materia actualizada exitosamente', 'exito') 
            : $this->redirigir('Error al actualizar la materia', 'error');
    }

    private function eliminarMateria() {
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            $this->redirigir('ID de materia requerido', 'error');
        }
        
        $this->modelo->eliminar($id) 
            ? $this->redirigir('Materia eliminada exitosamente', 'exito') 
            : $this->redirigir('Error al eliminar la materia', 'error');
    }

    private function redirigir($mensaje, $tipo) {
        header("Location: ../materias.php?$tipo=" . urlencode($mensaje));
        exit;
    }
}

$controlador = new CtrlMaterias();
$controlador->procesarRequest();