<?php

class Usuario
{
    private $conn; //conexión con la BD
    private $table = "usuarios";

    public function __construct($cx)
    {
        $this->conn = $cx;
    }

    public function listar()
    {
        try {
            $id = $_SESSION['id'];
            $qry = "select * from $this->table where id != 1 and id != :id";
            $st = $this->conn->prepare($qry);
            $st->bindParam(":id", $id, PDO::PARAM_INT);
            $st->execute();
            return $st->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
            return false;
        }
    }

    public function guardaActualiza($id, $nombre, $apellido_paterno, $apellido_materno, $email, $password, $rol)
    {
        try {
            //Instrucción que dice que hacer
            $idAux = $id == "" ? "null," : ":id,";
            $passAux = $password == "" ? "" : ",password=md5(:password)";
            $qry = "insert into $this->table (id,nombre,apellido_paterno,apellido_materno,email,password,rol) 
                values ($idAux:nombre,:apellido_paterno,:apellido_materno,:email,md5(:password),:rol) on duplicate key update 
                nombre=:nombre,apellido_paterno=:apellido_paterno,apellido_materno=:apellido_materno,email=:email$passAux";
            //Preparo la operación
            $st = $this->conn->prepare($qry);
            //Asignar los valores
            if ($id != "") {
                $st->bindParam(':id', $id, PDO::PARAM_INT);
            }
            $st->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $st->bindParam(':apellido_paterno', $apellido_paterno, PDO::PARAM_STR);
            $st->bindParam(':apellido_materno', $apellido_materno, PDO::PARAM_STR);
            $st->bindParam(":email", $email, PDO::PARAM_STR);
            $st->bindParam(':password', $password, PDO::PARAM_STR);
            $st->bindParam(':rol', $rol, PDO::PARAM_STR);
            $st->execute();
            return $id == "" ? $this->conn->lastInsertId() : $id;
        } catch (PDOException $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
            return false;
        }
    }

    public function validaEmail($email)
    {
        try {
            $qry = 'select * from ' . $this->table . " where email = :email";
            $st = $this->conn->prepare($qry);
            $st->bindParam(":email", $email, PDO::PARAM_STR);
            $st->execute();
            $resultado = $st->fetch(PDO::FETCH_ASSOC);
            if ($resultado) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
            return false;
        }
    }

    public function getUser($id)
    {
        try {
            $qry = "select u.id,u.nombre, u.apellido_paterno,u.apellido_materno,u.email,u.password,u.rol,u.fecha_creacion, e.id estudiante_id,e.matricula, e.universidad_id
            from $this->table u left join estudiantes e on e.usuario_id = u.id where u.id = :id";
            $st = $this->conn->prepare($qry);
            $st->bindParam(':id', $id, PDO::PARAM_INT);
            $st->execute();
            return $st->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
            return false;
        }
    }

    public function login($email, $password)
    {
        try {
            $qry = "select u.id, u.nombre, u.apellido_paterno, u.apellido_materno, u.email, u.rol, e.id estudiante_id,e.matricula,e.universidad_id 
            from $this->table u left join estudiantes e on e.usuario_id = u.id where u.email = :email and u.password = md5(:password)";
            $st = $this->conn->prepare($qry);
            $st->bindParam(':email', $email, PDO::PARAM_STR);
            $st->bindParam(':password', $password, PDO::PARAM_STR);
            $st->execute();
            $resultado = $st->fetch(PDO::FETCH_ASSOC);
            if ($resultado) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
            return false;
        }
    }

    public function consultaEmail($email)
    {
        try {
            $qry = "select u.id, u.nombre, u.apellido_paterno, u.apellido_materno, u.email, u.rol, e.id estudiante_id,e.matricula,e.universidad_id 
            from $this->table u left join estudiantes e on e.usuario_id = u.id where u.email = :email";
            $st = $this->conn->prepare($qry);
            $st->bindParam(':email', $email, PDO::PARAM_STR);
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

    public function borrarUser($id)
    {
        try {
            $qry = "delete from $this->table where id=:id";
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