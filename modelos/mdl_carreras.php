<?php
require_once __DIR__.'/conexion.php';

class MdlCarrera {
    private $conn;
    private $tabla = 'carreras';

    public function __construct() {
        $this->conn = (new Conexion())->conectar();
    }

    public function obtenerTodos() {
        try {
            return $this->conn->query("SELECT id_carrera as id, nombre FROM $this->tabla where activo=0" )->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerTodos: " . $e->getMessage());
            return [];
        }
    }

public function guardar($nombre) {
    // Validar números (retorna false si encuentra números)
    if (preg_match('/[0-9]/', $nombre)) {
        return "El nombre de la carrera no puede contener números";
    }
    
    try {
        $stmt = $this->conn->prepare("INSERT INTO $this->tabla (nombre) VALUES (:nombre)");
        $stmt->bindParam(':nombre', $nombre);
        return $stmt->execute() ? true : "Error al guardar la carrera";
    } catch (PDOException $e) {
        error_log("Error en guardar: " . $e->getMessage());
        return "Error en la base de datos";
    }
}

public function actualizar($id, $nombre) {
    // Validar números
    if (preg_match('/[0-9]/', $nombre)) {
        return "El nombre de la carrera no puede contener números";
    }
    
    try {
        $stmt = $this->conn->prepare("UPDATE $this->tabla SET nombre = :nombre WHERE id_carrera = :id");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute() ? true : "Error al actualizar la carrera";
    } catch (PDOException $e) {
        error_log("Error en actualizar: " . $e->getMessage());
        return "Error en la base de datos";
    }
}
    public function eliminar($id) {
        $stmt = $this->conn->prepare("UPDATE $this->tabla SET activo = 1 WHERE id_carrera = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerPaginado($inicio, $limite) {
        $sql = "SELECT id_carrera as id, nombre FROM carreras WHERE activo = 0 LIMIT :inicio, :limite";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
            $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    public function contarCarreras() {
        $sql = "SELECT COUNT(*) AS total FROM $this->tabla WHERE activo = 0";
        return $this->conn->query($sql)->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>
