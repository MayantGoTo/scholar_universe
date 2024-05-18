<?php
class Mysql
{
    private $host = "localhost";
    private $db_name = "scholar_universe";
    private $user = "root";
    private $password = "";
    private $conn;

    //Regresa la conexión de la base de datos
    public function connect()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo ('Error en la clase MySQL: ' . $e->getMessage());
        }
        return $this->conn;
    }
}