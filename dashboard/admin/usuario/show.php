<?php
include ("../../../includes/header.php");
include ("../../../config/Mysql.php");
include ("../../../modelos/Validaciones.php");
include ("../../../modelos/Usuario.php");
$base = new Mysql();
$cx = $base->connect();
$val = new Validaciones();
$usuario = new Usuario($cx);
$error = null;
$id = 0;
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $user = $usuario->getUser($id);
} else {
    $id = null;
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

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= RUTA_ADMIN ?>inicio.php">Inicio</a></li>
        <li class="breadcrumb-item active"><a href="<?= RUTA_ADMIN ?>usuario/index.php">Administración - Usuarios</a>
        </li>
        <li class="breadcrumb-item" aria-current="page">Usuario</li>
    </ol>
</nav>

<div class="row mt-3">
    <p class="h3 text-center">Usuario</p>
</div>

<div class="row d-flex justify-content-center">
    <div class="card text-white mb-3 shadow w-50 p-3 bg-body rounded">
        <div class="card-body">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre(s)*</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre(s)" disabled
                    value="<?= $user->nombre ?>">
            </div>
            <div class="mb-3">
                <label for="apellido_paterno" class="form-label">Apellido Paterno*</label>
                <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno"
                    placeholder="Apellido Paterno" disabled value="<?= $user->apellido_paterno ?>">
            </div>
            <div class="mb-3">
                <label for="apellido_materno" class="form-label">Apellido Materno</label>
                <input type="text" class="form-control" id="apellido_materno" name="apellido_materno"
                    placeholder="Apellido Materno" disabled value="<?= $user->apellido_materno ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email*</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" disabled
                    value="<?= $user->email ?>">
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="<?= RUTA_ADMIN ?>usuario/index.php" class="btn btn-danger" tabindex="-1" role="button"
                    aria-disabled="true">Cancelar</a>
            </div>
        </div>
    </div>
</div>
<?php include ("../../../includes/footer.php") ?>