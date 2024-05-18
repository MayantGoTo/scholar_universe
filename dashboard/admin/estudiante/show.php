<?php
include ("../../../includes/header.php");
include ("../../../config/Mysql.php");
include ("../../../modelos/Validaciones.php");
include ("../../../modelos/Estudiante.php");
include ("../../../modelos/Universidad.php");
$base = new Mysql();
$cx = $base->connect();
$val = new Validaciones();
$estudiante = new Estudiante($cx);
$universidades = new Universidad($cx);
$error = null;
$id = 0;
$crud = "Estudiante";
$cruds = "Estudiantes";
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $uni = $estudiante->get($id);
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
        <li class="breadcrumb-item active"><a href="<?= RUTA_ADMIN ?>estudiante/index.php">Administración -
                <?= $cruds ?></a>
        </li>
        <li class="breadcrumb-item" aria-current="page"><?= $crud ?></li>
    </ol>
</nav>

<div class="row mt-3">
    <p class="h3 text-center"><?= $crud ?></p>
</div>

<div class="row d-flex justify-content-center">
    <div class="card text-dark mb-3 shadow w-50 p-3 bg-body rounded">
        <div class="card-body">
            <div class="mb-3">
                <label for="matricula" class="form-label">Matricula*</label>
                <input type="text" class="form-control" id="matricula" name="matricula" placeholder="Matricula" disabled
                    value="<?= $uni->matricula ?>">
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre(s)*</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre(s)" disabled
                    value="<?= $uni->nombre ?>">
            </div>
            <div class="mb-3">
                <label for="apellido_paterno" class="form-label">Apellido Paterno*</label>
                <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno"
                    placeholder="Apellido Paterno" disabled value="<?= $uni->apellido_paterno ?>">
            </div>
            <div class="mb-3">
                <label for="apellido_materno" class="form-label">Apellido Materno</label>
                <input type="text" class="form-control" id="apellido_materno" name="apellido_materno"
                    placeholder="Apellido Materno" disabled value="<?= $uni->apellido_materno ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email*</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" disabled
                    value="<?= $uni->email ?>">
            </div>
            <div class="mb-3">
                <label for="universidad_id" class="form-label">Universidad*</label>
                <select class="selectpicker show-tick" data-style="btn-custom" title="Seleccione la carrera"
                    data-width="100%" data-live-search="true" id="universidad_id" name="universidad_id" disabled>
                    <?php foreach ($universidades->listar() as $key => $item): ?>
                        <option value="<?= $item->id ?>"><?= $item->nombre ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="<?= RUTA_ADMIN ?>estudiante/index.php" class="btn btn-danger" tabindex="-1" role="button"
                    aria-disabled="true">Cancelar</a>
            </div>
        </div>
    </div>
</div>
<?php include ("../../../includes/footer.php") ?>

<script>
    $(document).ready(function () {
        $('#universidad_id').selectpicker('val', "<?= $uni->universidad_id ?>");
    })
</script>