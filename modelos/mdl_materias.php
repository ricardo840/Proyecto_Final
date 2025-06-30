<?php
require_once __DIR__.'/conexion.php';

class MdlMaterias {
    private $conn;
    private $tabla = 'materias';

    public function __construct() {
        $this->conn = (new Conexion())->conectar();
    }

    public function obtenerTodos() {
        try {
            return $this->conn->query("SELECT id_materia as id, nombre FROM $this->tabla")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerTodos: " . $e->getMessage());
            return [];
        }
    }

    public function guardar($nombre) {
        $stmt = $this->conn->prepare("INSERT INTO $this->tabla (nombre) VALUES (:nombre)");
        $stmt->bindParam(':nombre', $nombre);
        return $stmt->execute();
    }

    public function actualizar($id, $nombre) {
        $stmt = $this->conn->prepare("UPDATE $this->tabla SET nombre = :nombre WHERE id_materia = :id");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $stmt = $this->conn->prepare("UPDATE $this->tabla SET activo = 1 where id_materia = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function buscarPorNombre($nombre) {
        try {
            $stmt = $this->conn->prepare("SELECT id_materia as id, nombre FROM $this->tabla WHERE nombre LIKE :nombre");
            $param = "%$nombre%";
            $stmt->bindParam(':nombre', $param);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en buscarPorNombre: " . $e->getMessage());
            return [];
        }
    }

     public function obtenerPaginado($inicio, $limite) {
        $sql = "SELECT id_materia as id, nombre FROM materias where activo = 0 LIMIT :inicio, :limite";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
            $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    public function contarMaterias() {
        $sql = "SELECT COUNT(*) AS total FROM $this->tabla WHERE activo = 0";
        return $this->conn->query($sql)->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>