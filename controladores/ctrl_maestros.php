<?php
require_once __DIR__ . '/../modelos/mdl_maestros.php';

class CtrlMaestros {
    private $modelo;

    public function __construct() {
        $this->modelo = new MdlMaestros();
    }

    public function procesarRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            switch ($action) {
                case 'guardar':
                    $this->guardarMaestro();
                    break;
                case 'actualizar':
                    $this->actualizarMaestro();
                    break;
                case 'eliminar':
                    $this->eliminarMaestro();
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

    private function guardarMaestro() {
        $nombre = $_POST['nombre_maestro'] ?? '';
        
        if (empty($nombre)) {
            $this->redirigir('El nombre es requerido', 'error');
        }

        $this->validarNombre($nombre);
        $this->modelo->guardar($nombre) ? $this->redirigir('Maestro creado', 'exito') : 
        $this->redirigir('Error al guardar', 'error');
    }

    private function actualizarMaestro() {
        $id = $_POST['id'] ?? '';
        $nombre = $_POST['nombre_maestro'] ?? '';
        
        if (empty($id) || empty($nombre)) {
            $this->redirigir('Datos incompletos', 'error');
        }
        
        $this->validarNombre($nombre);
        $this->modelo->actualizar($id, $nombre) ? $this->redirigir('Maestro actualizado', 'exito') : 
        $this->redirigir('Error al actualizar', 'error');
    }

    private function eliminarMaestro() {
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            $this->redirigir('ID requerido', 'error');
        }
        
        $this->modelo->eliminar($id) 
            ? $this->redirigir('Maestro eliminado exitosamente', 'exito') 
            : $this->redirigir('Error al eliminar el maestro', 'error');
    }

    private function redirigir($mensaje, $tipo) {
        header("Location: ../maestros.php?$tipo=" . urlencode($mensaje));
        exit;
    }
}

$controlador = new CtrlMaestros();
$controlador->procesarRequest();
