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
$materias = new Materia($cx);
$universidades = new Universidad($cx);
$categorias = new Categoria($cx);
$error = null;
$mensaje = null;
$id = 0;
$crud = "Materia";
$cruds = "Materias";
$categorias_ids = "";
$universidad_ids = "";
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $uni = $materias->get($id);
    foreach ($materias->getCategoriasMateria($id) as $key => $categoria) {
        $categorias_ids = $categorias_ids . ($key == 0 ? "" : ",") . $categoria->categoria_id;
    }
    foreach ($materias->getUniversidadesMateria($id) as $key => $item) {
        $universidad_ids = $universidad_ids . ($key == 0 ? "" : ",") . $item->univerdad_id;
    }
} else {
    $id = null;
}
if (isset($_POST['update'])) {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST['descripcion'];
    $universidad_id = $_POST['universidad_id'];
    $materia_id = $_POST['materia_id'];
    $id = $uni->id;
    if ($val->isEmpty($nombre) || $val->isEmpty($descripcion) || $val->isEmpty($universidad_id) || $val->isEmpty($materia_id)) {
        $error = "Todos los campos son obligatorios";
    } else {
        if ($materias->guardaActualiza($id, $nombre, $descripcion, $universidad_id, $materia_id)) {
            $mensaje = "Se ha actualizado el registro";
        } else {
            $error = "Existe un problema al actualizar";
        }
    }
}

?>
<!--Imprimir el error o el mensaje -->
<?php if ($val->validaSesionRoot() != ""): ?>
    <script>
        window.location = '<?= $val->validaSesionRoot() ?>'; 
    </script>
<?php endif; ?>

<?php if (!isset($id)): ?>
    <script>
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Ocurrio un error inesperado",
        }).then((result) => {
            window.location = 'index.php';
        });
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
            window.location = 'index.php';
        });
    </script>
<?php endif; ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= RUTA_ADMIN ?>inicio.php">Inicio</a></li>
        <li class="breadcrumb-item active"><a href="<?= RUTA_ADMIN ?>materia/index.php">Administración -
                <?= $cruds ?></a>
        </li>
        <li class="breadcrumb-item" aria-current="page">Editar <?= $crud ?></li>
    </ol>
</nav>

<div class="row mt-3">
    <p class="h3 text-center">Editar</p>
</div>

<div class="row d-flex justify-content-center">
    <div class="card text-dark mb-3 shadow w-50 p-3 bg-body rounded">
        <div class="card-body">
            <form method="POST" action="" enctype="application/json">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre*</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required
                        maxlength="50" minlength="1" autofocus value="<?= $uni->nombre ?>">
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripcion*</label>
                    <textarea class="form-control" placeholder="Descripcion" id="descripcion" name="descripcion"
                        maxlength="200" rows="3"><?= $uni->descripcion ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="universidad_id" class="form-label">Elije las Universidades*</label>
                    <select class="selectpicker show-tick" data-style="btn-custom" title="Selecciona las Universidades"
                        data-width="100%" data-live-search="true" id="universidad_id" name="universidad_id[]" multiple
                        required>
                        <?php foreach ($universidades->listar() as $key => $universidad): ?>
                            <option value="<?= $universidad->id ?>"><?= $universidad->nombre ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="materia_id" class="form-label">Elije las Categorias*</label>
                    <select class="selectpicker show-tick" data-style="btn-custom" title="Selecciona las Categorias"
                        data-width="100%" data-live-search="true" id="materia_id" name="materia_id[]" multiple required>
                        <?php foreach ($categorias->listar() as $key => $categoria): ?>
                            <option value="<?= $categoria->id ?>"><?= $categoria->nombre ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="<?= RUTA_ADMIN ?>materia/index.php" class="btn btn-danger" tabindex="-1" role="button"
                        aria-disabled="true">Cancelar</a>
                    <button type="submit" name="update" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include ("../../../includes/footer.php") ?>

<script>
    $(document).ready(function () {
        let categorias_ids = "<?= $categorias_ids ?>";
        $('#materia_id').selectpicker('val', categorias_ids.split(","));
        let universidad_ids = "<?= $universidad_ids ?>";
        $('#universidad_id').selectpicker('val', universidad_ids.split(","));
    });
</script>