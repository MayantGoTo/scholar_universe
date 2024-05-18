<?php
include ("../../../includes/header.php");
include ("../../../config/Mysql.php");
include ("../../../modelos/Validaciones.php");
include ("../../../modelos/Categoria.php");
$base = new Mysql();
$cx = $base->connect();
$val = new Validaciones();
$categorias = new Categoria($cx);
$error = null;
$mensaje = null;
$id = 0;
$crud = "Categoria";
$cruds = "Categorias";
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $uni = $categorias->get($id);
} else {
    $id = null;
}
if (isset($_POST['update'])) {
    $nombre = $_POST["nombre"];
    $id = $uni->id;
    if ($val->isEmpty($nombre)) {
        $error = "Todos los campos son obligatorios";
    } else {
        if ($categorias->guardaActualiza($id, $nombre)) {
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
        <li class="breadcrumb-item active"><a href="<?= RUTA_ADMIN ?>categorias/index.php">Administración -
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
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="<?= RUTA_ADMIN ?>categorias/index.php" class="btn btn-danger" tabindex="-1" role="button"
                        aria-disabled="true">Cancelar</a>
                    <button type="submit" name="update" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include ("../../../includes/footer.php") ?>