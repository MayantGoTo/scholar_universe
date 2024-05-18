<?php

class Estudiante
{
    private $conn;
    private $tabla = 'estudiantes';

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

    public function guardaActualiza($id, $matricula, $universidad_id, $usuario_id)
    {
        try {
            //Instrucción que dice que hacer
            $idAux = $id == "" ? "null," : ":id,";
            $qry = "insert into $this->tabla (id,matricula,universidad_id,usuario_id) 
                values ($idAux:matricula,:universidad_id,:usuario_id) 
                on duplicate key update matricula=:matricula,universidad_id=:universidad_id";
            //Preparo la operación
            $st = $this->conn->prepare($qry);
            //Asignar los valores
            if ($id != "") {
                $st->bindParam(':id', $id, PDO::PARAM_INT);
            }
            $st->bindParam(':matricula', $matricula, PDO::PARAM_STR);
            $st->bindParam(':universidad_id', $universidad_id, PDO::PARAM_INT);
            $st->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
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
            $qry = "select u.id, u.nombre, u.apellido_paterno, u.apellido_materno, u.email, u.rol, e.id estudiante_id,e.matricula,e.universidad_id 
            from $this->tabla e inner join usuarios u on u.id = e.usuario_id where e.id = :id";
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
            $usuario = new Usuario($this->conn);
            if ($usuario->borrarUser($_REQUEST['usuario_id'])) {
                $qry = "delete from $this->tabla where id=:id";
                $st = $this->conn->prepare($qry);
                $st->bindParam(":id", $_REQUEST['id'], PDO::PARAM_INT);
                $st->execute();
                return true;
            }
            return false;
        } catch (PDOException $e) {
            echo "Errores : " . $e->getMessage();
            return false;
        }
    }

    //estudiante relaciones 
    function getMisMaterias($id)
    {
        try {
            $qry = "select m.id,m.nombre,m.descripcion,m.fecha_creacion,em.seguir from estudiante_materia em inner join materias m on m.id= em.materia_id where em.seguir='2' and em.estudiante_id =:id";
            $st = $this->conn->prepare($qry);
            $st->bindParam(':id', $id, PDO::PARAM_INT);
            $st->execute();
            return $st->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Mensajes encontrados : " . $e->getMessage();
            return [];
        }
    }

    function getCategoriasMateria($id)
    {
        try {
            $qry = "select c.* from categoria_materia cm inner join categorias c on c.id = cm.categoria_id where cm.materia_id=:id";
            $st = $this->conn->prepare($qry);
            $st->bindParam(':id', $id, PDO::PARAM_INT);
            $st->execute();
            return $st->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Mensajes encontrados : " . $e->getMessage();
            return [];
        }
    }

    //colaboraciones estudiante
    function getMisColaboraciones($id)
    {
        try {
            $qry = "select m.id,m.nombre,m.descripcion,m.fecha_creacion, 
            ifnull((select em2.seguir from estudiante_materia em2 where em2.seguir='1' and em2.materia_id=m.id and em2.estudiante_id=:id),'0') seguir
            from estudiante_materia em inner join materias m on m.id= em.materia_id where em.seguir ='2' and em.estudiante_id !=:id";
            $st = $this->conn->prepare($qry);
            $st->bindParam(':id', $id, PDO::PARAM_INT);
            $st->execute();
            return $st->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Mensajes encontrados : " . $e->getMessage();
            return [];
        }
    }
}