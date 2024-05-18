<?php
class Universidad
{
    private $conn; //conexión con la BD
    private $table = "universidades";

    public function __construct($cx)
    {
        $this->conn = $cx;
    }

    public function listar()
    {
        try {
            $qry = "select * from view_$this->table";
            $st = $this->conn->prepare($qry);
            $st->execute();
            return $st->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Errores : " . $e->getMessage();
            return false;
        }
    }

    public function guardaActualiza($id, $nombre, $direccion, $pais, $estado)
    {
        try {
            //Instrucción que dice que hacer
            $idAux = $id == "" ? "null," : ":id,";
            $qry = "insert into $this->table (id,nombre,direccion,pais,estado) 
            values ($idAux:nombre,:direccion,:pais,:estado) 
            on duplicate key update nombre=:nombre,direccion=:direccion,pais=:pais,estado=:estado";
            //Preparo la operación
            $st = $this->conn->prepare($qry);
            //Asignar los valores
            if ($id != "") {
                $st->bindParam(':id', $id, PDO::PARAM_INT);
            }
            $st->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $st->bindParam(':direccion', $direccion, PDO::PARAM_STR);
            $st->bindParam(':pais', $pais, PDO::PARAM_STR);
            $st->bindParam(':estado', $estado, PDO::PARAM_STR);
            $st->execute();
            return $id == "" ? $this->conn->lastInsertId() : $id;
        } catch (PDOException $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
            return 0;
        }
    }

    public function get($id)
    {
        try {
            $qry = "select * from $this->table where id = :id";
            $st = $this->conn->prepare($qry);
            $st->bindParam(':id', $id, PDO::PARAM_INT);
            $st->execute();
            return $st->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
            return false;
        }
    }

    public function borrar()
    {
        try {
            $qry = "delete from $this->table where id=:id";
            $st = $this->conn->prepare($qry);
            $st->bindParam(":id", $_REQUEST['id'], PDO::PARAM_INT);
            $st->execute();
            return true;
        } catch (PDOException $e) {
            echo "Errores : " . $e->getMessage();
            return false;
        }
    }

}