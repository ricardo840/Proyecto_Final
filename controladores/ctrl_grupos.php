<?php
require_once __DIR__ . '/../Modelos/mdl_grupos.php';

class CtrlGrupos {
    private $modelo;

    public function __construct() {
        $this->modelo = new MdlGrupos();
    }

    public function procesarRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            switch ($action) {
                case 'guardar':
                    $this->guardarGrupo();
                    break;
                case 'actualizar':
                    $this->actualizarGrupo();
                    break;
                case 'eliminar':
                    $this->eliminarGrupo();
                    break;
                default:
                    $this->redirigir('Acción inválida', 'error');
                    break;
            }
        }
    }

    private function guardarGrupo() {
        $grupo = $_POST['nombre_grupo'] ?? '';
        $carrera = $_POST['carrera'] ?? '';
        
        if (empty($grupo) || empty($carrera)) {
            $this->redirigir('Todos los campos son requeridos', 'error');
        }
        
        $this->modelo->guardar($grupo, $carrera) 
            ? $this->redirigir('Grupo creado exitosamente', 'exito') 
            : $this->redirigir('Error al guardar el grupo', 'error');
    }

    private function actualizarGrupo() {
        $id = $_POST['id'] ?? '';
        $grupo = $_POST['nombre_grupo'] ?? '';
        $carrera = $_POST['carrera'] ?? '';
        
        if (empty($id) || empty($grupo) || empty($carrera)) {
            $this->redirigir('Todos los campos son requeridos', 'error');
        }
        
        $this->modelo->actualizar($id, $grupo, $carrera) 
            ? $this->redirigir('Grupo actualizado exitosamente', 'exito') 
            : $this->redirigir('Error al actualizar el grupo', 'error');
    }

    private function eliminarGrupo() {
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            $this->redirigir('ID requerido', 'error');
        }
        
        $this->modelo->eliminar($id) 
            ? $this->redirigir('Grupo eliminado exitosamente', 'exito') 
            : $this->redirigir('Error al eliminar el grupo', 'error');
    }

    private function redirigir($mensaje, $tipo) {
        header("Location: ../grupos.php?$tipo=" . urlencode($mensaje));
        exit;
    }
}

$controlador = new CtrlGrupos();
$controlador->procesarRequest();