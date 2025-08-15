<?php
require_once __DIR__.'/conexion.php';

class MdlMateriasGrupos {
    private $conn;
    private $tabla = 'materias_grupo';

    public function __construct() {
        $this->conn = (new Conexion())->conectar();
    }
    
    public function obtenerTodos() {
        try {
            $query = "SELECT mg.id_materia_grupo as id, 
                            m.nombre as materia, 
                            g.grupo as grupo,
                            ma.nombre as maestro
                    FROM $this->tabla mg
                    JOIN materias m ON mg.id_materia = m.id_materia AND m.activo = 0
                    JOIN grupos g ON mg.id_grupo = g.id_grupo AND g.activo = 0
                    JOIN maestros ma ON mg.id_maestro = ma.id_maestros AND ma.activo = 0
                    WHERE mg.activo = 0";
            return $this->conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerTodos: " . $e->getMessage());
            return [];
        }
    }

    public function guardar($id_materia, $id_grupo, $id_maestro) {
        $stmt = $this->conn->prepare("INSERT INTO $this->tabla (id_materia, id_grupo, id_maestro) VALUES (:id_materia, :id_grupo, :id_maestro)");
        $stmt->bindParam(':id_materia', $id_materia, PDO::PARAM_INT);
        $stmt->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
        $stmt->bindParam(':id_maestro', $id_maestro, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function actualizar($id, $id_materia, $id_grupo, $id_maestro) {
        $stmt = $this->conn->prepare("UPDATE $this->tabla SET id_materia = :id_materia, id_grupo = :id_grupo, id_maestro = :id_maestro WHERE id_materia_grupo = :id");
        $stmt->bindParam(':id_materia', $id_materia, PDO::PARAM_INT);
        $stmt->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
        $stmt->bindParam(':id_maestro', $id_maestro, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $stmt = $this->conn->prepare("UPDATE $this->tabla SET activo = 1 WHERE id_materia_grupo = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function obtenerPaginado($inicio, $limite) {
        $sql = "SELECT mg.id_materia_grupo as id, 
                    m.nombre as materia, 
                    g.grupo as grupo,
                    ma.nombre as maestro,
                    mg.id_materia as materia_id,
                    mg.id_grupo as grupo_id,
                    mg.id_maestro as maestro_id
                FROM $this->tabla mg
                JOIN materias m ON mg.id_materia = m.id_materia
                JOIN grupos g ON mg.id_grupo = g.id_grupo
                JOIN maestros ma ON mg.id_maestro = ma.id_maestros
                WHERE mg.activo = 0
                LIMIT :inicio, :limite";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarMaterias_grupos() {
        $sql = "SELECT COUNT(*) AS total FROM $this->tabla WHERE activo = 0";
        return $this->conn->query($sql)->fetch(PDO::FETCH_ASSOC)['total'];
    }

}
?>