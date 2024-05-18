<?php
include ("../../../includes/header.php");
include ("../../../config/Mysql.php");
include ("../../../modelos/Validaciones.php");
include ("../../../modelos/Universidad.php");
$base = new Mysql();
$cx = $base->connect();
$val = new Validaciones();
$universidad = new Universidad($cx);
$error = null;
$mensaje = null;
$id = 0;
$crud = "Universidad";
$cruds = "Universidades";
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $uni = $universidad->get($id);
} else {
    $id = null;
}
if (isset($_POST['update'])) {
    $nombre = $_POST["nombre"];
    $direccion = $_POST['direccion'];
    $pais = $_POST['pais'];
    $estado = $_POST['estado'];
    $id = $uni->id;
    if ($val->isEmpty($nombre) || $val->isEmpty($pais) || $val->isEmpty($estado)) {
        $error = "Todos los campos son obligatorios";
    } else {
        if ($universidad->guardaActualiza($id, $nombre, $direccion, $pais, $estado)) {
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
        <li class="breadcrumb-item active"><a href="<?= RUTA_ADMIN ?>universidad/index.php">Administración -
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
                    <label for="direccion" class="form-label">Direccion*</label>
                    <textarea class="form-control" placeholder="Direccion" id="direccion" name="direccion"
                        maxlength="200" rows="3"><?= $uni->direccion ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="pais" class="form-label">Pais*</label>
                    <input type="text" class="form-control" id="pais" name="pais" placeholder="Pais" maxlength="100"
                        minlength="1" required value="<?= $uni->pais ?>">
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado*</label>
                    <input type="text" class="form-control" id="estado" name="estado" placeholder="Estado"
                        maxlength="100" minlength="1" required value="<?= $uni->estado ?>">
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="<?= RUTA_ADMIN ?>universidad/index.php" class="btn btn-danger" tabindex="-1" role="button"
                        aria-disabled="true">Cancelar</a>
                    <button type="submit" name="update" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include ("../../../includes/footer.php") ?>