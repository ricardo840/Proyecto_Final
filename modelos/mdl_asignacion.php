<?php
require_once __DIR__.'/conexion.php';

class MdlAsignacion {
    private $conn;
    private $tabla = 'asignacion';

    public function __construct() {
        $this->conn = (new Conexion())->conectar();
    }

    public function obtenerTodas() {
        try {
            $sql = "SELECT a.id_asignacion, g.grupo, m.nombre as profesor, mat.nombre as materia, 
                    a.fecha, 
                    GROUP_CONCAT(DISTINCT CONCAT(al.nombre, ' ', al.ape_pa) SEPARATOR ', ') as nombres_alumnos,
                    COUNT(DISTINCT al.id_alumno) as alumnos, 
                    SUM(CASE WHEN al.genero = 'M' THEN 1 ELSE 0 END) as hombres,
                    SUM(CASE WHEN al.genero = 'F' THEN 1 ELSE 0 END) as mujeres
                    FROM {$this->tabla} a
                    JOIN grupos g ON a.id_grupo = g.id_grupo
                    JOIN maestros m ON a.id_maestros = m.id_maestros
                    JOIN materias mat ON a.id_materia = mat.id_materia
                    JOIN alumnos al ON a.id_alumno = al.id_alumno
                    WHERE a.activo = 0 AND g.activo = 0 AND m.activo = 0 AND mat.activo = 0 AND al.activo = 0
                    GROUP BY a.id_asignacion, g.grupo, m.nombre, mat.nombre, a.fecha";
            
            return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerTodas: " . $e->getMessage());
            return [];
        }
    }

    public function guardar($id_grupo, $id_maestro, $id_materia, $fecha, $hora, $alumnos) {
        try {
            // Iniciar transacción
            $this->conn->beginTransaction();
            
            // Paso 1: Insertar el primer alumno para obtener el ID de asignación
            $stmt = $this->conn->prepare("INSERT INTO {$this->tabla} 
                        (id_grupo, id_maestros, id_materia, fecha, hora, id_alumno, M, F) 
                        VALUES (:id_grupo, :id_maestro, :id_materia, :fecha, :hora, :id_alumno, :m, :f)");
            
            $primerAlumno = $alumnos[0];
            $stmtGenero = $this->conn->prepare("SELECT genero FROM alumnos WHERE id_alumno = ?");
            $stmtGenero->execute([$primerAlumno]);
            $genero = $stmtGenero->fetchColumn();
            
            $esHombre = ($genero === 'M') ? 1 : 0;
            $esMujer = ($genero === 'F') ? 1 : 0;
            
            $stmt->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
            $stmt->bindParam(':id_maestro', $id_maestro, PDO::PARAM_INT);
            $stmt->bindParam(':id_materia', $id_materia, PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':hora', $hora);
            $stmt->bindParam(':id_alumno', $primerAlumno, PDO::PARAM_INT);
            $stmt->bindParam(':m', $esHombre, PDO::PARAM_INT);
            $stmt->bindParam(':f', $esMujer, PDO::PARAM_INT);
            $stmt->execute();
            
            // Obtener el ID de la asignación
            $id_asignacion = $this->conn->lastInsertId();
            
            // Paso 2: Insertar los demás alumnos con el mismo ID de asignación
            for ($i = 1; $i < count($alumnos); $i++) {
                $id_alumno = $alumnos[$i];
                
                $stmtGenero->execute([$id_alumno]);
                $genero = $stmtGenero->fetchColumn();
                
                $esHombre = ($genero === 'M') ? 1 : 0;
                $esMujer = ($genero === 'F') ? 1 : 0;
                
                $stmt = $this->conn->prepare("INSERT INTO {$this->tabla} 
                            (id_asignacion, id_grupo, id_maestros, id_materia, fecha, hora, id_alumno, M, F) 
                            VALUES (:id_asignacion, :id_grupo, :id_maestro, :id_materia, :fecha, :hora, :id_alumno, :m, :f)");
                
                $stmt->bindParam(':id_asignacion', $id_asignacion, PDO::PARAM_INT);
                $stmt->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
                $stmt->bindParam(':id_maestro', $id_maestro, PDO::PARAM_INT);
                $stmt->bindParam(':id_materia', $id_materia, PDO::PARAM_INT);
                $stmt->bindParam(':fecha', $fecha);
                $stmt->bindParam(':hora', $hora);
                $stmt->bindParam(':id_alumno', $id_alumno, PDO::PARAM_INT);
                $stmt->bindParam(':m', $esHombre, PDO::PARAM_INT);
                $stmt->bindParam(':f', $esMujer, PDO::PARAM_INT);
                $stmt->execute();
            }
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error en guardar: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar($id_asignacion, $id_grupo, $id_maestro, $id_materia, $fecha, $hora, $alumnos) {
        try {
            $this->conn->beginTransaction();
            
            // 1. Actualizar los datos comunes en todos los registros de la asignación
            $stmt = $this->conn->prepare("UPDATE {$this->tabla} SET 
                        id_grupo = :id_grupo,
                        id_maestros = :id_maestro,
                        id_materia = :id_materia,
                        fecha = :fecha,
                        hora = :hora
                        WHERE id_asignacion = :id");
            
            $stmt->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
            $stmt->bindParam(':id_maestro', $id_maestro, PDO::PARAM_INT);
            $stmt->bindParam(':id_materia', $id_materia, PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':hora', $hora);
            $stmt->bindParam(':id', $id_asignacion, PDO::PARAM_INT);
            $stmt->execute();
            
            // 2. Obtener alumnos actuales
            $alumnos_actuales = $this->conn->query("SELECT id_alumno FROM {$this->tabla} 
                                WHERE id_asignacion = {$id_asignacion} AND id_alumno IS NOT NULL")
                                ->fetchAll(PDO::FETCH_COLUMN);
            
            // 3. Alumnos a eliminar (están en BD pero no en el nuevo array)
            $alumnos_eliminar = array_diff($alumnos_actuales, $alumnos);
            if (!empty($alumnos_eliminar)) {
                $placeholders = implode(',', array_fill(0, count($alumnos_eliminar), '?'));
                $stmt = $this->conn->prepare("DELETE FROM {$this->tabla} 
                            WHERE id_asignacion = ? AND id_alumno IN ($placeholders)");
                $stmt->execute(array_merge([$id_asignacion], $alumnos_eliminar));
            }
            
            // 4. Alumnos a agregar (están en nuevo array pero no en BD)
            $alumnos_agregar = array_diff($alumnos, $alumnos_actuales);
            foreach ($alumnos_agregar as $id_alumno) {
                // Obtener género del alumno
                $stmtGenero = $this->conn->prepare("SELECT genero FROM alumnos WHERE id_alumno = ?");
                $stmtGenero->execute([$id_alumno]);
                $genero = $stmtGenero->fetchColumn();
                
                $esHombre = ($genero === 'M') ? 1 : 0;
                $esMujer = ($genero === 'F') ? 1 : 0;
                
                $stmt = $this->conn->prepare("INSERT INTO {$this->tabla} 
                            (id_asignacion, id_grupo, id_maestros, id_materia, fecha, hora, id_alumno, M, F) 
                            VALUES (:id, :id_grupo, :id_maestro, :id_materia, :fecha, :hora, :id_alumno, :m, :f)");
                
                $stmt->bindParam(':id', $id_asignacion, PDO::PARAM_INT);
                $stmt->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
                $stmt->bindParam(':id_maestro', $id_maestro, PDO::PARAM_INT);
                $stmt->bindParam(':id_materia', $id_materia, PDO::PARAM_INT);
                $stmt->bindParam(':fecha', $fecha);
                $stmt->bindParam(':hora', $hora);
                $stmt->bindParam(':id_alumno', $id_alumno, PDO::PARAM_INT);
                $stmt->bindParam(':m', $esHombre, PDO::PARAM_INT);
                $stmt->bindParam(':f', $esMujer, PDO::PARAM_INT);
                $stmt->execute();
            }
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error en actualizar: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar($id_asignacion) {
        try {
            $this->conn->beginTransaction();
            
            // Eliminar todos los registros de la asignación (marcar como inactivos)
            $stmt = $this->conn->prepare("UPDATE {$this->tabla} SET activo = 1 WHERE id_asignacion = :id");
            $stmt->bindParam(':id', $id_asignacion, PDO::PARAM_INT);
            $stmt->execute();
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error en eliminar: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerPaginado($inicio, $limite) {
        try {
            $sql = "SELECT 
                    a.id_asignacion,
                    g.grupo,
                    m.nombre as profesor,
                    mat.nombre as materia,
                    CONCAT(a.fecha, ' ', TIME_FORMAT(a.hora, '%h:%i %p')) as fecha_hora,
                    (SELECT GROUP_CONCAT(CONCAT(al.nombre, ' ', al.ape_pa, ' ', al.ape_ma) SEPARATOR ', ') 
                    FROM asignacion a2
                    JOIN alumnos al ON a2.id_alumno = al.id_alumno AND al.activo = 0
                    WHERE a2.id_asignacion = a.id_asignacion) as nombres_alumnos,
                    SUM(CASE WHEN al.genero = 'M' THEN 1 ELSE 0 END) as hombres,
                    SUM(CASE WHEN al.genero = 'F' THEN 1 ELSE 0 END) as mujeres
                    FROM asignacion a
                    JOIN grupos g ON a.id_grupo = g.id_grupo AND g.activo = 0
                    JOIN maestros m ON a.id_maestros = m.id_maestros AND m.activo = 0
                    JOIN materias mat ON a.id_materia = mat.id_materia AND mat.activo = 0
                    JOIN alumnos al ON a.id_alumno = al.id_alumno AND al.activo = 0
                    WHERE a.activo = 0
                    GROUP BY a.id_asignacion, g.grupo, m.nombre, mat.nombre, a.fecha, a.hora
                    ORDER BY a.fecha DESC, a.hora DESC
                    LIMIT :inicio, :limite";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':inicio', (int)$inicio, PDO::PARAM_INT);
            $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerPaginado: " . $e->getMessage());
            return [];
        }
    }

    public function contarAsignaciones() {
        try {
            $sql = "SELECT COUNT(DISTINCT id_asignacion) as total
                    FROM asignacion
                    WHERE activo = 0";
            
            return $this->conn->query($sql)->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            error_log("Error en contarAsignaciones: " . $e->getMessage());
            return 0;
        }
    }

    public function obtenerPorId($id_asignacion) {
        try {
            $sql = "SELECT 
                    a.id_asignacion, 
                    a.id_grupo, 
                    a.id_maestros, 
                    a.id_materia, 
                    a.fecha,
                    a.hora,
                    GROUP_CONCAT(DISTINCT a2.id_alumno) as alumnos_ids
                    FROM asignacion a
                    JOIN asignacion a2 ON a.id_asignacion = a2.id_asignacion
                    WHERE a.id_asignacion = :id AND a.activo = 0
                    AND a2.id_alumno IS NOT NULL
                    GROUP BY a.id_asignacion, a.id_grupo, a.id_maestros, a.id_materia, a.fecha, a.hora";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id_asignacion, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerPorId: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerTodosAlumnos() {
        try {
            $sql = "SELECT id_alumno, CONCAT(nombre, ' ', ape_pa, ' ', ape_ma) as nombre_completo, genero 
                    FROM alumnos 
                    WHERE activo = 0
                    ORDER BY nombre_completo";
            
            return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerTodosAlumnos: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerGrupos() {
        try {
            return $this->conn->query("SELECT id_grupo, grupo FROM grupos WHERE activo = 0")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerGrupos: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerMaestros() {
        try {
            return $this->conn->query("SELECT id_maestros, nombre FROM maestros WHERE activo = 0 ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerMaestros: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerMaterias() {
        try {
            return $this->conn->query("SELECT id_materia, nombre FROM materias WHERE activo = 0 ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerMaterias: " . $e->getMessage());
            return [];
        }
    }
}
?>