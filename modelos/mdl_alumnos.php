<?php
require_once __DIR__.'/conexion.php';

class MdlAlumnos {
    private $conn;
    private $tabla = 'alumnos';

    public function __construct() {
        $this->conn = (new Conexion())->conectar();
    }

    public function obtenerTodos() {
        try {
            return $this->conn->query("SELECT id_alumno, nombre, ape_pa, ape_ma, genero, activo FROM $this->tabla where activo = 0")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerTodos: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerPaginado($inicio, $limite) {
        try {
            $sql = "SELECT id_alumno, nombre, ape_pa, ape_ma, genero, activo 
                    FROM $this->tabla 
                    Where activo = 0
                    LIMIT :inicio, :limite";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
            $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerPaginado: " . $e->getMessage());
            return [];
        }
    }

    public function contarAlumnos() {
        try {
            $sql = "SELECT COUNT(*) AS total FROM $this->tabla";
            return $this->conn->query($sql)->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            error_log("Error en contarAlumnos: " . $e->getMessage());
            return 0;
        }
    }

    public function guardar($datos) {
        $stmt = $this->conn->prepare("INSERT INTO $this->tabla (nombre, ape_pa, ape_ma, genero, activo) 
                                     VALUES (:nombre, :ape_pa, :ape_ma, :genero, :activo)");
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':ape_pa', $datos['ape_pa']);
        $stmt->bindParam(':ape_ma', $datos['ape_ma']);
        $stmt->bindParam(':genero', $datos['genero']);
        $stmt->bindParam(':activo', $datos['activo'], PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function actualizar($id, $datos) {
        $stmt = $this->conn->prepare("UPDATE $this->tabla 
                                    SET nombre = :nombre, 
                                        ape_pa = :ape_pa, 
                                        ape_ma = :ape_ma, 
                                        genero = :genero, 
                                        activo = :activo 
                                    WHERE id_alumno = :id");
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':ape_pa', $datos['ape_pa']);
        $stmt->bindParam(':ape_ma', $datos['ape_ma']);
        $stmt->bindParam(':genero', $datos['genero']);
        $stmt->bindParam(':activo', $datos['activo'], PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $stmt = $this->conn->prepare("UPDATE $this->tabla SET activo = 1 WHERE id_alumno = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function buscarPorNombre($nombre) {
        try {
            $stmt = $this->conn->prepare("SELECT id_alumno, nombre, ape_pa, ape_ma, genero, activo 
                                        FROM $this->tabla 
                                        WHERE CONCAT(nombre, ' ', ape_pa, ' ', ape_ma) LIKE :nombre");
            $param = "%$nombre%";
            $stmt->bindParam(':nombre', $param);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en buscarPorNombre: " . $e->getMessage());
            return [];
        }
    }
}
?>