<?php
require_once __DIR__ . '/../Modelos/mdl_carreras.php';

class CtrlCarrera {
    private $modelo;

    public function __construct() {
        $this->modelo = new MdlCarrera();
    }

    public function procesarRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            switch ($action) {
                case 'guardar':
                    $this->guardarCarrera();
                    break;
                case 'actualizar':
                    $this->actualizarCarrera();
                    break;
                case 'eliminar':
                    $this->eliminarCarrera();
                    break;
                default:
                    $this->redirigir('Acción inválida', 'error');
                    break;
            }
        }
    }

    private function validarNombre($nombre) {
        if (preg_match('/[0-9]/', $nombre)) {
            $this->redirigir('El nombre no puede contener números', 'error');
        }
    }

    private function guardarCarrera() {
        $nombre = $_POST['nombre_carrera'] ?? '';
        if (empty($nombre)) $this->redirigir('Nombre requerido', 'error');
        
        $this->validarNombre($nombre);
        $this->modelo->guardar($nombre) ? $this->redirigir('Carrera creada', 'exito') : 
        $this->redirigir('Error al guardar', 'error');
    }

    private function actualizarCarrera() {
        $id = $_POST['id'] ?? '';
        $nombre = $_POST['nombre_carrera'] ?? '';
        if (empty($id) || empty($nombre)) $this->redirigir('Datos incompletos', 'error');
        
        $this->validarNombre($nombre);
        $this->modelo->actualizar($id, $nombre) ? $this->redirigir('Carrera actualizada', 'exito') : 
        $this->redirigir('Error al actualizar', 'error');
    }

    private function eliminarCarrera() {
        $id = $_POST['id'] ?? '';
        if (empty($id)) $this->redirigir('ID requerido', 'error');
        $this->modelo->eliminar($id) ? $this->redirigir('Carrera eliminada', 'exito') : $this->redirigir('Error al eliminar', 'error');
    }

    private function redirigir($mensaje, $tipo) {
        header("Location: ../carreras.php?$tipo=" . urlencode($mensaje));
        exit;
    }
}

$controlador = new CtrlCarrera();
$controlador->procesarRequest();
?>
