<?php
include ("../../../includes/header.php");
include ("../../../config/Mysql.php");
include ("../../../modelos/Validaciones.php");
include ("../../../modelos/Categoria.php");
$base = new Mysql();
$cx = $base->connect();
$val = new Validaciones();
$categoria = new Categoria($cx);
$error = null;
$mensaje = null;
$crud = "Categoria";
$cruds = "Categorias";
if (isset($_POST['update'])) {
    $nombre = $_POST["nombre"];
    if ($val->isEmpty($nombre)) {
        $error = "Todos los campos son obligatorios";
    } else {
        if ($categoria->guardaActualiza("", $nombre)) {
            $mensaje = "Se ha creado el registro";
        } else {
            $error = "Existe un problema al guardado";
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
        <li class="breadcrumb-item active"><a href="<?= RUTA_ADMIN ?>categoria/index.php">Administración -
                <?= $cruds ?></a>
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
            <form method="POST" action="" enctype="application/json">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre*</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required
                        maxlength="50" minlength="1" autofocus>
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="<?= RUTA_ADMIN ?>categoria/index.php" class="btn btn-danger" tabindex="-1" role="button"
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

    });
</script>