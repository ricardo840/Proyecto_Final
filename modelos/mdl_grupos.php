<?php
require_once __DIR__.'/conexion.php';

class MdlGrupos {
    private $conn;
    private $tabla = 'grupos';

    public function __construct() {
        $this->conn = (new Conexion())->conectar();
    }

    public function obtenerTodos() {
        try {
            return $this->conn->query("SELECT g.id_grupo as id, g.grupo, c.nombre as carrera 
                                     FROM $this->tabla g 
                                     JOIN carreras c ON g.carrera = c.id_carrera
                                     where g.activo = 0")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerTodos: " . $e->getMessage());
            return [];
        }
    }

    public function guardar($grupo, $carrera) {
        $stmt = $this->conn->prepare("INSERT INTO $this->tabla (grupo, carrera) VALUES (:grupo, :carrera)");
        $stmt->bindParam(':grupo', $grupo);
        $stmt->bindParam(':carrera', $carrera, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function actualizar($id, $grupo, $carrera) {
        $stmt = $this->conn->prepare("UPDATE $this->tabla SET grupo = :grupo, carrera = :carrera WHERE id_grupo = :id");
        $stmt->bindParam(':grupo', $grupo);
        $stmt->bindParam(':carrera', $carrera, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $stmt = $this->conn->prepare("UPDATE $this->tabla SET activo = 1 WHERE id_grupo = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerPorCarrera($id_carrera) {
        try {
            $stmt = $this->conn->prepare("SELECT id_grupo as id, grupo 
                                        FROM $this->tabla 
                                        WHERE carrera = :id_carrera");
            $stmt->bindParam(':id_carrera', $id_carrera, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerPorCarrera: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerPaginado($inicio, $limite) {
        $sql = "SELECT g.id_grupo as id, g.grupo, c.nombre as carrera 
                                     FROM $this->tabla g 
                                     JOIN carreras c ON g.carrera = c.id_carrera 
                                     where g.activo = 0
                                     LIMIT :inicio, :limite";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
            $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    public function contarGrupos() {
        $sql = "SELECT COUNT(*) AS total FROM $this->tabla WHERE activo = 0";
        return $this->conn->query($sql)->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>