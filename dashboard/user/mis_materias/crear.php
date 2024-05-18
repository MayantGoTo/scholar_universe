<?php
include ("../../../includes/header.php");
include ("../../../config/Mysql.php");
include ("../../../modelos/Validaciones.php");
include ("../../../modelos/Materia.php");
include ("../../../modelos/Universidad.php");
include ("../../../modelos/Categoria.php");
$base = new Mysql();
$cx = $base->connect();
$val = new Validaciones();
$materia = new Materia($cx);
$universidades = new Universidad($cx);
$categorias = new Categoria($cx);
$error = null;
$mensaje = null;
$crud = "Materia";
$cruds = "Materias";
$bndSaveUni = false;
if (isset($_POST['update'])) {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST['descripcion'];
    $estudiante_id = $_SESSION['estudiante_id'];
    $universidad_id = $_POST['universidad_id'];
    $materia_id = $_POST['materia_id'];
    $archivos = $_FILES["archivo"];
    $visible = isset($_POST['visible']) ? '1' : '0';
    if ($val->isEmpty($nombre) || $val->isEmpty($descripcion) || $val->isEmpty($estudiante_id) || $val->isEmpty($materia_id) || $val->isEmpty($universidad_id)) {
        $error = "Todos los campos son obligatorios";
    } else {
        if ($materia->guardaActualizaMateriaAlumno("", $nombre, $descripcion, $estudiante_id, $universidad_id, $materia_id, "2", $archivos, $visible)) {
            $mensaje = "Se ha creado el registro";
        } else {
            $error = "Existe un problema al guardado";
        }
    }
}
if (isset($_POST["addUniversidad"])) {
    $nombre = $_POST["nombres"];
    $direccion = $_POST['direccion'];
    $pais = $_POST['pais'];
    $estado = $_POST['estado'];
    if ($val->isEmpty($nombre) || $val->isEmpty($pais) || $val->isEmpty($estado)) {
        $error = "Todos los campos son obligatorios";
    } else {
        if ($universidades->guardaActualiza("", $nombre, $direccion, $pais, $estado)) {
            $bndSaveUni = true;
            $mensaje = "Se ha creado el registro";
        } else {
            $error = "Existe un problema al guardado";
        }
    }
}
if (isset($_POST["addCat"])) {
    $nombre = $_POST["nombreCat"];
    if ($val->isEmpty($nombre)) {
        $error = "Todos los campos son obligatorios";
    } else {
        if ($categorias->guardaActualiza("", $nombre)) {
            $bndSaveUni = true;
            $mensaje = "Se ha creado el registro";
        } else {
            $error = "Existe un problema al guardado";
        }
    }
}
?>
<!--Imprimir el error o el mensaje -->
<?php if ($val->validaSesion() != ""): ?>
    <script>
        window.location = '<?= $val->validaSesion() ?>'; 
    </script>
<?php endif; ?>

<?php if (isset($error)): ?>
    <script>
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "<?= $error ?>",
        });
    </script>
<?php endif; ?>

<?php if (isset($mensaje)): ?>
    <script>
        Swal.fire({
            icon: "success",
            title: "Exito",
            text: "<?= $mensaje ?>",
        }).then((result) => {
            // window.location = 'index.php';
            window.location = "<?= !$bndSaveUni ? "index.php" : "crear.php" ?>";
        });
    </script>
<?php endif; ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= RUTA_DASHBOARD ?>inicio.php">Inicio</a></li>
        <li class="breadcrumb-item active"><a href="<?= RUTA_USER ?>mis_materias/index.php">Mis Materias</a>
        </li>
        <li class="breadcrumb-item" aria-current="page">Crear <?= $crud ?></li>
    </ol>
</nav>

<div class="row mt-3">
    <p class="h3 text-center">Agregar</p>
</div>

<div class="row d-flex justify-content-center">
    <div class="card text-dark mb-3 shadow w-50 p-3 bg-body rounded">
        <div class="card-body">
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre*</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required
                        maxlength="50" minlength="1" autofocus>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripcion*</label>
                    <textarea class="form-control" placeholder="Descripcion" id="descripcion" name="descripcion"
                        maxlength="200" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="universidad_id" class="form-label">Elije las Universidades*</label>
                    <select class="selectpicker show-tick" data-style="btn-custom" title="Selecciona las Universidades"
                        data-width="90%" data-live-search="true" id="universidad_id" name="universidad_id[]" multiple
                        required>
                        <?php foreach ($universidades->listar() as $key => $universidad): ?>
                            <option value="<?= $universidad->id ?>"><?= $universidad->nombre ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addUniversidad"><i class="bi bi-plus-circle-fill"></i></button>
                </div>
                <div class="mb-3">
                    <label for="materia_id" class="form-label">Elije las Categorias*</label>
                    <select class="selectpicker show-tick" data-style="btn-custom" title="Selecciona las Categorias"
                        data-width="90%" data-live-search="true" id="materia_id" name="materia_id[]" multiple required>
                        <?php foreach ($categorias->listar() as $key => $categoria): ?>
                            <option value="<?= $categoria->id ?>"><?= $categoria->nombre ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCat"><i
                            class="bi bi-plus-circle-fill"></i></button>
                </div>
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <label class="form-check-label" for="visible">archivos visibles
                            para cualquier usuario que vea esta materia</label>
                        <input class="form-check-input" type="checkbox" role="switch" id="visible" name="visible"
                            checked>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="archivo" class="form-label">Selecciona Tus Archivos</label>
                    <input type="file" class="form-control" name="archivo[]" id="archivo"
                        placeholder="Selecciona tu archivo" multiple>
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="<?= RUTA_USER ?>mis_materias/index.php" class="btn btn-danger" tabindex="-1" role="button"
                        aria-disabled="true">Cancelar</a>
                    <button type="submit" name="update" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addUniversidad" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Agregar Universidad</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombres" class="form-label">Nombre(s)*</label>
                        <input type="text" class="form-control" id="nombres" name="nombres" placeholder="Nombre(s)"
                            required maxlength="50" minlength="1" autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Direccion*</label>
                        <textarea class="form-control" placeholder="Direccion" id="direccion" name="direccion"
                            maxlength="200" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="pais" class="form-label">Pais*</label>
                        <input type="text" class="form-control" id="pais" name="pais" placeholder="Pais" maxlength="100"
                            minlength="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado*</label>
                        <input type="text" class="form-control" id="estado" name="estado" placeholder="Estado"
                            maxlength="100" minlength="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="addUniversidad">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addCat" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Agregar Categoria</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombreCat" class="form-label">Nombre*</label>
                        <input type="text" class="form-control" id="nombreCat" name="nombreCat" placeholder="Nombre"
                            required maxlength="50" minlength="1" autofocus>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="addCat">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php include ("../../../includes/footer.php") ?>
