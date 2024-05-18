<?php
class Categoria
{
    private $conn;
    private $tabla = 'categorias';

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

    public function guardaActualiza($id, $nombre)
    {
        try {
            //Instrucción que dice que hacer
            $idAux = $id == "" ? "null," : ":id,";
            $qry = "insert into $this->tabla (id,nombre) values ($idAux:nombre) on duplicate key update nombre=:nombre";
            //Preparo la operación
            $st = $this->conn->prepare($qry);
            //Asignar los valores
            if ($id != "") {
                $st->bindParam(':id', $id, PDO::PARAM_INT);
            }
            $st->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $st->execute();
            return $id == "" ? $this->conn->lastInsertId() : $id;
        } catch (PDOException $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
            return 0;
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

    public function borrar()
    {
        try {
            $qry = "delete from $this->tabla where id=:id";
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