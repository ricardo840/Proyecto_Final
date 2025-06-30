<?php
class Conexion {

    private $usuario = "root";
    private $contraseña = "";
    private $db = "proyecto2"; //actualizo la el nombre se la base
    private $servidor = "localhost"; 
    public $conn;

    public function conectar() {
        $this->conn = null;
        try {
            
            $this->conn = new PDO("mysql:host=".$this->servidor.
            ";dbname=".$this->db, 
            $this->usuario, 
            $this->contraseña);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, 
                                          PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            
            echo "Error al conectar: " . $e->getMessage();
        }
        return $this->conn;
    }
}






