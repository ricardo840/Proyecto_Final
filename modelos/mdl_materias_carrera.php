<?php
require_once __DIR__.'/conexion.php';

class MdlMateriasCarrera {
    private $conn;
    private $tabla = 'materias_carrera';

    public function __construct() {
        $this->conn = (new Conexion())->conectar();
    }

    public function obtenerTodos() {
        try {
            $query = "SELECT mc.id_materia_carrera as id, 
                             m.nombre as materia, 
                             c.nombre as carrera
                      FROM $this->tabla mc
                      JOIN materias m ON mc.id_materia = m.id_materia
                      JOIN carreras c ON mc.id_carrera = c.id_carrera";
            return $this->conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerTodos: " . $e->getMessage());
            return [];
        }
    }

    public function guardar($id_materia, $id_carrera) {
        $stmt = $this->conn->prepare("INSERT INTO $this->tabla (id_materia, id_carrera) VALUES (:id_materia, :id_carrera)");
        $stmt->bindParam(':id_materia', $id_materia, PDO::PARAM_INT);
        $stmt->bindParam(':id_carrera', $id_carrera, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function actualizar($id, $id_materia, $id_carrera) {
        $stmt = $this->conn->prepare("UPDATE $this->tabla SET id_materia = :id_materia, id_carrera = :id_carrera WHERE id_materia_carrera = :id");
        $stmt->bindParam(':id_materia', $id_materia, PDO::PARAM_INT);
        $stmt->bindParam(':id_carrera', $id_carrera, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $stmt = $this->conn->prepare("UPDATE $this->tabla SET activo = 1 WHERE id_materia_carrera = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerPorCarrera($id_carrera) {
        try {
            $stmt = $this->conn->prepare("SELECT m.id_materia as id, m.nombre 
                                         FROM $this->tabla mc
                                         JOIN materias m ON mc.id_materia = m.id_materia
                                         WHERE mc.id_carrera = :id_carrera");
            $stmt->bindParam(':id_carrera', $id_carrera, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerPorCarrera: " . $e->getMessage());
            return [];
        }
    }

    public function existeRelacion($id_materia, $id_carrera) {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as existe 
                                        FROM $this->tabla 
                                        WHERE id_materia = :id_materia 
                                        AND id_carrera = :id_carrera");
            $stmt->bindParam(':id_materia', $id_materia, PDO::PARAM_INT);
            $stmt->bindParam(':id_carrera', $id_carrera, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['existe'] > 0;
        } catch (PDOException $e) {
            error_log("Error en existeRelacion: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerPaginado($inicio, $limite) {
        $sql = "SELECT mc.id_materia_carrera as id, 
                    m.nombre as materia, 
                    c.nombre as carrera,
                    m.id_materia as materia_id,
                    c.id_carrera as carrera_id
                FROM $this->tabla mc
                JOIN materias m ON mc.id_materia = m.id_materia AND m.activo = 0
                JOIN carreras c ON mc.id_carrera = c.id_carrera AND c.activo = 0
                WHERE mc.activo = 0
                LIMIT :inicio, :limite";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarMaterias_carrera() {
        $sql = "SELECT COUNT(*) AS total FROM $this->tabla WHERE activo = 0";
        return $this->conn->query($sql)->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>