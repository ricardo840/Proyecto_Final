<?php
class ConexionPublic {
    private $usuario = "root";
    private $contraseña = "";
    private $db = "proyecto3";
    private $servidor = "localhost"; 
    public $conn;

    public function conectar() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=".$this->servidor.";dbname=".$this->db.";charset=utf8", 
                $this->usuario, 
                $this->contraseña
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            error_log("Error al conectar: " . $e->getMessage());
            die("Error al conectar con la base de datos");
        }
        return $this->conn;
    }
}
?>
