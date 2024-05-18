<?php
class Materia
{
    private $conn;
    private $tabla = 'materias';

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

    public function guardaActualiza($id, $nombre, $descripcion, $universidad_id, $materia_id)
    {
        try {
            //Instrucción que dice que hacer
            $idAux = $id == "" ? "null," : ":id,";
            $qry = "insert into $this->tabla (id,nombre,descripcion) values ($idAux:nombre,:descripcion) 
            on duplicate key update nombre=:nombre,descripcion=:descripcion";
            //Preparo la operación
            $st = $this->conn->prepare($qry);
            //Asignar los valores
            if ($id != "") {
                $st->bindParam(':id', $id, PDO::PARAM_INT);
            }
            $st->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $st->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $st->execute();

            //recuperamos la id insertada para nuevo registro
            if ($id == "") {
                $id = $this->conn->lastInsertId();
            }
            //borramos si ya tiene universidades
            $qryDelUni = "delete from univerdad_materia where materia_id=:id";
            $stDelUni = $this->conn->prepare($qryDelUni);
            $stDelUni->bindParam(":id", $id, PDO::PARAM_INT);
            $stDelUni->execute();
            //guardamos las universidades
            foreach ($universidad_id as $item) {
                $qryUni = "insert into univerdad_materia (univerdad_id,materia_id) values (:univerdad_id,:materia_id)";
                $stUni = $this->conn->prepare($qryUni);
                $stUni->bindParam(':univerdad_id', $item, PDO::PARAM_INT);
                $stUni->bindParam(':materia_id', $id, PDO::PARAM_INT);
                $stUni->execute();
            }
            //borramos si ya tiene categorias
            $qryDelCat = "delete from categoria_materia where materia_id=:id";
            $stDelCat = $this->conn->prepare($qryDelCat);
            $stDelCat->bindParam(":id", $id, PDO::PARAM_INT);
            $stDelCat->execute();
            //guardamos las categorias
            foreach ($materia_id as $item) {
                $qryCat = "insert into categoria_materia (categoria_id,materia_id) values (:categoria_id,:materia_id)";
                $stCat = $this->conn->prepare($qryCat);
                $stCat->bindParam(':categoria_id', $item, PDO::PARAM_INT);
                $stCat->bindParam(':materia_id', $id, PDO::PARAM_INT);
                $stCat->execute();
            }

            return true;
        } catch (PDOException $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
            return 0;
        }
    }

    public function guardaActualizaMateriaAlumno($id, $nombre, $descripcion, $estudiante_id, $universidad_id, $materia_id, $seguir, $archivos, $visible)
    {
        try {
            //Instrucción que dice que hacer
            $idAux = $id == "" ? "null," : ":id,";
            $qry = "insert into $this->tabla (id,nombre,descripcion) values ($idAux:nombre,:descripcion) 
            on duplicate key update nombre=:nombre,descripcion=:descripcion";
            //Preparo la operación
            $st = $this->conn->prepare($qry);
            //Asignar los valores
            if ($id != "") {
                $st->bindParam(':id', $id, PDO::PARAM_INT);
            }
            $st->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $st->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $st->execute();

            //recuperamos la id insertada para nuevo registro
            if ($id == "") {
                $id = $this->conn->lastInsertId();
            }

            //borramos si ya tiene universidades
            $qryDelUni = "delete from univerdad_materia where materia_id=:id";
            $stDelUni = $this->conn->prepare($qryDelUni);
            $stDelUni->bindParam(":id", $id, PDO::PARAM_INT);
            $stDelUni->execute();
            //guardamos las universidades
            foreach ($universidad_id as $item) {
                $qryUni = "insert into univerdad_materia (univerdad_id,materia_id) values (:univerdad_id,:materia_id)";
                $stUni = $this->conn->prepare($qryUni);
                $stUni->bindParam(':univerdad_id', $item, PDO::PARAM_INT);
                $stUni->bindParam(':materia_id', $id, PDO::PARAM_INT);
                $stUni->execute();
            }
            //borramos si ya tiene materia
            $qryDelUni = "delete from estudiante_materia where materia_id=:id and estudiante_id=:id2";
            $stDelUni = $this->conn->prepare($qryDelUni);
            $stDelUni->bindParam(":id", $id, PDO::PARAM_INT);
            $stDelUni->bindParam(":id2", $estudiante_id, PDO::PARAM_INT);
            $stDelUni->execute();
            //guardamos las materia

            $qryUni = "insert into estudiante_materia (estudiante_id,materia_id,seguir) values (:estudiante_id,:materia_id,:seguir)";
            $stUni = $this->conn->prepare($qryUni);
            $stUni->bindParam(':estudiante_id', $estudiante_id, PDO::PARAM_INT);
            $stUni->bindParam(':materia_id', $id, PDO::PARAM_INT);
            $stUni->bindParam(':seguir', $seguir, PDO::PARAM_INT);
            $stUni->execute();

            //borramos si ya tiene categorias
            $qryDelCat = "delete from categoria_materia where materia_id=:id";
            $stDelCat = $this->conn->prepare($qryDelCat);
            $stDelCat->bindParam(":id", $id, PDO::PARAM_INT);
            $stDelCat->execute();
            //guardamos las categorias
            foreach ($materia_id as $item) {
                $qryCat = "insert into categoria_materia (categoria_id,materia_id) values (:categoria_id,:materia_id)";
                $stCat = $this->conn->prepare($qryCat);
                $stCat->bindParam(':categoria_id', $item, PDO::PARAM_INT);
                $stCat->bindParam(':materia_id', $id, PDO::PARAM_INT);
                $stCat->execute();
            }

            //guarda archivo
            $directorio = __DIR__ . "\..\img\\estudientes_archivos\\$id";
            if (!file_exists($directorio)) {
                mkdir($directorio, 0777, true);
            }

            $length = count($archivos['name']);
            for ($i = 0; $i < $length; $i++) {
                $tmpFilePath = $archivos['tmp_name'][$i];
                $archivoName = $archivos['name'][$i];
                $peso = $archivos['size'][$i];
                if ($tmpFilePath != "") {
                    $qryArc = "insert into archivos (nombre,peso,ruta,tipo) values (:nombre,:peso,:ruta,1)";
                    $stArc = $this->conn->prepare($qryArc);
                    $stArc->bindParam(':nombre', $archivoName, PDO::PARAM_STR);
                    $stArc->bindParam(':peso', $peso, PDO::PARAM_STR);
                    $rutaD = "img\\estudientes_archivos\\$id";
                    $stArc->bindParam(':ruta', $rutaD, PDO::PARAM_STR);
                    $stArc->execute();
                    $archivo_id = $this->conn->lastInsertId();

                    $qryRel = "insert into colaboracion_estudiante_materia (estudiante_id,materia_id,archivo_id,visible) values (:estudiante_id,:materia_id,:archivo_id,:visible)";
                    $stRel = $this->conn->prepare($qryRel);
                    $stRel->bindParam(':estudiante_id', $estudiante_id, PDO::PARAM_INT);
                    $stRel->bindParam(':materia_id', $id, PDO::PARAM_INT);
                    $stRel->bindParam(':archivo_id', $archivo_id, PDO::PARAM_INT);
                    $stRel->bindParam(':visible', $visible, PDO::PARAM_INT);
                    $stRel->execute();

                    $newFilePath = "$directorio/" . $archivos['name'][$i];
                    move_uploaded_file($tmpFilePath, $newFilePath);
                }
            }

            return true;
        } catch (PDOException $e) {
            echo 'Excepción capturada: ', $e, "\n";
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
            //borramos las universidades
            $qryDelUni = "delete from univerdad_materia where materia_id=:id";
            $stDelUni = $this->conn->prepare($qryDelUni);
            $stDelUni->bindParam(":id", $_REQUEST['id'], PDO::PARAM_INT);
            $stDelUni->execute();
            //borramos las categorias
            $qryDelCat = "delete from categoria_materia where materia_id=:id";
            $stDelCat = $this->conn->prepare($qryDelCat);
            $stDelCat->bindParam(":id", $_REQUEST['id'], PDO::PARAM_INT);
            $stDelCat->execute();
            //borramos la materia
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

    // mis materias estudiante
    function getMisMaterias()
    {
        try {
            $qry = "select * from $this->tabla where id=:id";
            $st = $this->conn->prepare($qry);
            $st->bindParam(':id', $_REQUEST['estudiante_id'], PDO::PARAM_INT);
            $st->execute();
            return $st->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Mensajes encontrados : " . $e->getMessage();
            return [];
        }
    }

    function getCategoriasMateria($id)
    {
        try {
            $qry = "select * from categoria_materia where materia_id=:id";
            $st = $this->conn->prepare($qry);
            $st->bindParam(':id', $id, PDO::PARAM_INT);
            $st->execute();
            return $st->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Mensajes encontrados : " . $e->getMessage();
            return [];
        }
    }
    function getUniversidadesMateria($id)
    {
        try {
            $qry = "select * from univerdad_materia where materia_id=:id";
            $st = $this->conn->prepare($qry);
            $st->bindParam(':id', $id, PDO::PARAM_INT);
            $st->execute();
            return $st->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Mensajes encontrados : " . $e->getMessage();
            return [];
        }
    }

    function getMateriaEstudiante($id, $materia_id)
    {
        try {
            $qry = "select m.id,m.nombre,m.descripcion,m.fecha_creacion,em.seguir,
            (select cem.visible from colaboracion_estudiante_materia cem inner join archivos a on a.id= cem.archivo_id 
            where cem.estudiante_id =em.estudiante_id and cem.materia_id =em.materia_id limit 1) visible
            from estudiante_materia em inner join materias m on m.id= em.materia_id 
            where em.estudiante_id =:id and em.materia_id =:materia_id";
            $st = $this->conn->prepare($qry);
            $st->bindParam(':id', $id, PDO::PARAM_INT);
            $st->bindParam(':materia_id', $materia_id, PDO::PARAM_INT);
            $st->execute();
            return $st->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Mensajes encontrados : " . $e->getMessage();
        }
    }

    function getArchivosMateriaEstudiante($id, $materia_id)
    {
        try {
            $qry = "select * from colaboracion_estudiante_materia cem inner join archivos a on a.id= cem.archivo_id 
            where cem.estudiante_id =:id and cem.materia_id =:materia_id";
            $st = $this->conn->prepare($qry);
            $st->bindParam(':id', $id, PDO::PARAM_INT);
            $st->bindParam(':materia_id', $materia_id, PDO::PARAM_INT);
            $st->execute();
            return $st->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Mensajes encontrados : " . $e->getMessage();
            return [];
        }
    }

    public function borrarArchvioEstudiante()
    {
        try {
            //borramos la relacion
            $qryDelUni = "delete from colaboracion_estudiante_materia where estudiante_id =:estudiante_id and materia_id =:materia_id and archivo_id =:archivo_id";
            $stDelUni = $this->conn->prepare($qryDelUni);
            $stDelUni->bindParam(":estudiante_id", $_REQUEST['estudiante_id'], PDO::PARAM_INT);
            $stDelUni->bindParam(":materia_id", $_REQUEST['materia_id'], PDO::PARAM_INT);
            $stDelUni->bindParam(":archivo_id", $_REQUEST['archivo_id'], PDO::PARAM_INT);
            $stDelUni->execute();
            //borramos el archivo
            $qryDelCat = "delete from archivos where id=:archivo_id";
            $stDelCat = $this->conn->prepare($qryDelCat);
            $stDelCat->bindParam(":archivo_id", $_REQUEST['archivo_id'], PDO::PARAM_INT);
            $stDelCat->execute();
            return true;
        } catch (PDOException $e) {
            echo "Errores : " . $e->getMessage();
            return false;
        }
    }

    public function borrarMateriaArchvioEstudiante()
    {
        try {
            //borramos la relacion
            $qryDelCat = "delete from estudiante_materia where estudiante_id=:estudiante_id and materia_id =:materia_id";
            $stDelCat = $this->conn->prepare($qryDelCat);
            $stDelCat->bindParam(":estudiante_id", $_REQUEST['estudiante_id'], PDO::PARAM_INT);
            $stDelCat->bindParam(":materia_id", $_REQUEST['materia_id'], PDO::PARAM_INT);
            $stDelCat->execute();

            $qryDelUni = "delete from colaboracion_estudiante_materia where cem.estudiante_id =:estudiante_id and cem.materia_id =:materia_id";
            $stDelUni = $this->conn->prepare($qryDelUni);
            $stDelUni->bindParam(":estudiante_id", $_REQUEST['estudiante_id'], PDO::PARAM_INT);
            $stDelUni->bindParam(":materia_id", $_REQUEST['materia_id'], PDO::PARAM_INT);
            $stDelUni->execute();
            return true;
        } catch (PDOException $e) {
            echo "Errores : " . $e->getMessage();
            return false;
        }
    }

    public function seguir()
    {
        try {
            //Instrucción que dice que hacer
            $qry = "insert into estudiante_materia (estudiante_id,materia_id,seguir) 
                values (:estudiante_id,:materia_id,:seguir) on duplicate key update seguir=:seguir";
            //Preparo la operación
            $st = $this->conn->prepare($qry);
            //Asignar los valores
            $st->bindParam(':estudiante_id', $_REQUEST['estudiante_id'], PDO::PARAM_STR);
            $st->bindParam(':materia_id', $_REQUEST['materia_id'], PDO::PARAM_INT);
            $st->bindParam(":seguir", $_REQUEST['seguir'], PDO::PARAM_INT);
            $st->execute();
            return true;
        } catch (PDOException $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
            return false;
        }
    }

    function getArchivosMateriaColaboraciones($id, $materia_id)
    {
        try {
            $qry = "select * from colaboracion_estudiante_materia cem inner join archivos a on a.id= cem.archivo_id 
            where cem.estudiante_id !=:id and cem.materia_id =:materia_id";
            $st = $this->conn->prepare($qry);
            $st->bindParam(':id', $id, PDO::PARAM_INT);
            $st->bindParam(':materia_id', $materia_id, PDO::PARAM_INT);
            $st->execute();
            return $st->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo "Mensajes encontrados : " . $e->getMessage();
            return [];
        }
    }

    public function colaborar($materia_id, $estudiante_id, $archivos)
    {
        try {
            //guarda archivo
            $directorio = __DIR__ . "\..\img\\estudientes_archivos\\$materia_id";
            if (!file_exists($directorio)) {
                mkdir($directorio, 0777, true);
            }

            $length = count($archivos['name']);
            for ($i = 0; $i < $length; $i++) {
                $tmpFilePath = $archivos['tmp_name'][$i];
                $archivoName = $archivos['name'][$i];
                $peso = $archivos['size'][$i];
                if ($tmpFilePath != "") {
                    $qryArc = "insert into archivos (nombre,peso,ruta,tipo) values (:nombre,:peso,:ruta,1)";
                    $stArc = $this->conn->prepare($qryArc);
                    $stArc->bindParam(':nombre', $archivoName, PDO::PARAM_STR);
                    $stArc->bindParam(':peso', $peso, PDO::PARAM_STR);
                    $rutaD = "img\\estudientes_archivos\\$materia_id";
                    $stArc->bindParam(':ruta', $rutaD, PDO::PARAM_STR);
                    $stArc->execute();
                    $archivo_id = $this->conn->lastInsertId();

                    $qryRel = "insert into colaboracion_estudiante_materia (estudiante_id,materia_id,archivo_id,visible) values (:estudiante_id,:materia_id,:archivo_id,1)";
                    $stRel = $this->conn->prepare($qryRel);
                    $stRel->bindParam(':estudiante_id', $estudiante_id, PDO::PARAM_INT);
                    $stRel->bindParam(':materia_id', $materia_id, PDO::PARAM_INT);
                    $stRel->bindParam(':archivo_id', $archivo_id, PDO::PARAM_INT);
                    $stRel->execute();

                    $newFilePath = "$directorio/" . $archivos['name'][$i];
                    move_uploaded_file($tmpFilePath, $newFilePath);
                }
            }

            return true;
        } catch (PDOException $e) {
            echo 'Excepción capturada: ', $e, "\n";
            return 0;
        }
    }

}