<?php
class Archivo
{
    private $conn;
    private $tabla = 'archivos';

    public function __construct($cx)
    {
        $this->conn = $cx;
    }

    public function listar()
    {
        try {
            $qry = "select * from view_$this->tabla";
            $st = $this->conn->prepare($qry);
            $st->execute();
            return $st->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Errores : " . $e->getMessage();
            return false;
        }
    }

    public function guardaActualiza($id, $nombre, $peso, $ruta, $tipo)
    {
        try {
            //Instrucción que dice que hacer
            $idAux = $id == "" ? "null," : ":id,";
            $qry = "insert into $this->tabla (id,nombre,peso,ruta,tipo) values ($idAux:nombre,:peso,:ruta,:tipo) 
            on duplicate key update nombre=:nombre,peso=:peso,ruta=:ruta,tipo=:tipo";
            //Preparo la operación
            $st = $this->conn->prepare($qry);
            //Asignar los valores
            if ($id != "") {
                $st->bindParam(':id', $id, PDO::PARAM_INT);
            }
            $st->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $st->bindParam(':peso', $peso, PDO::PARAM_STR);
            $st->bindParam(':ruta', $ruta, PDO::PARAM_STR);
            $st->bindParam(":tipo", $tipo, PDO::PARAM_STR);
            $st->execute();
            return true;
        } catch (PDOException $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
            return false;
        }
    }

    function get($id)
    {
        try {
            $qry = "select * from $this->tabla where id=:id";
            $st = $this->conn->prepare($qry);
            $st->bindParam(':id', $id, PDO::PARAM_INT);
            $st->execute();
            return $st->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Mensajes encontrados : " . $e->getMessage();
        }
    }



    public function borrar($id)
    {
        try {
            $qry = "delete from $this->tabla where id=:id";
            $st = $this->conn->prepare($qry);
            $st->bindParam(":id", $id, PDO::PARAM_INT);
            $st->execute();
            return true;
        } catch (PDOException $e) {
            echo "Errores : " . $e->getMessage();
            return false;
        }
    }
}