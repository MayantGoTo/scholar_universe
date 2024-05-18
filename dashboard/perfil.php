<?php
include ("../includes/header.php");
include ("../config/Mysql.php");
include ("../modelos/Validaciones.php");
include ("../modelos/Usuario.php");
include ("../modelos/Estudiante.php");
include ("../modelos/Universidad.php");
$base = new Mysql();
$cx = $base->connect();
$val = new Validaciones();
$usuario = new Usuario($cx);
$estudiante = new Estudiante($cx);
$universidades = new Universidad($cx);
$error = null;
$mensaje = null;
$id = 0;
$bndSaveUni = false;
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $user = $usuario->getUser($id);
} else {
    $id = null;
}
if (isset($_POST['update'])) {
    $nombre = $_POST["nombre"];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $password = $_POST["password"];
    $confirmar_password = $_POST['confirmar_password'];
    $id = $user->id;
    $email = $user->email;
    $matricula = !isset($_POST['matricula']) ? "" : $_POST['matricula'];
    $universidad_id = !isset($_POST['universidad_id']) ? "" : $_POST['universidad_id'];
    $estudiante_id = $_SESSION['estudiante_id'];
    if ($val->isEmpty($nombre) || $val->isEmpty($apellido_paterno)) {
        $error = "Todos los campos son obligatorios";
    } else {
        if ($password == $confirmar_password) {
            $bnd = false;
            $bnd = $usuario->guardaActualiza($id, $nombre, $apellido_paterno, $apellido_materno, $email, $password, $user->rol);
            if ($bnd && $_SESSION['rol'] == "Estudiante") {
                $bnd = $estudiante->guardaActualiza($estudiante_id, $matricula, $universidad_id, $id);
            }
            if ($bnd) {
                $val->actualizaSesion($nombre, $apellido_paterno, $apellido_materno, $matricula, $universidad_id);
                $mensaje = "Se ha actualizado el registro";
            } else {
                $error = "Existe un problema al actualizar";
            }
        } else {
            $error = "Las contraseñas no coinciden";
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

?>
<!--Imprimir el error o el mensaje -->
<?php if ($val->validaSesion() != ""): ?>
    <script>
        window.location = '<?= $val->validaSesion() ?>'; 
    </script>
<?php endif; ?>

<?php if (!isset($id)): ?>
    <script>
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Ocurrio un error inesperado",
        }).then((result) => {
            window.location = '../inicio.php';
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
            window.location = 'perfil.php';
        });
    </script>
<?php endif; ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="inicio.php">Inicio</a>
        </li>
        <li class="breadcrumb-item" aria-current="page">Perfil</li>
    </ol>
</nav>

<div class="row mt-3">
    <p class="h3 text-center">Editar</p>
</div>

<div class="row d-flex justify-content-center">
    <div class="card text-dark mb-3 shadow w-50 p-3 bg-body rounded">
        <div class="card-body">
            <form method="POST" action="" enctype="application/json" autocomplete="off">
                <?php if ($_SESSION['rol'] == "Estudiante"): ?>
                    <div class="mb-3">
                        <label for="matricula" class="form-label">Matricula Escolar*</label>
                        <input type="text" class="form-control" id="matricula" name="matricula"
                            placeholder="Matricula Escolar" required maxlength="50" minlength="1" autofocus
                            value="<?= $user->matricula ?>">
                    </div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre(s)*</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre(s)" required
                        maxlength="50" minlength="1" autofocus value="<?= $user->nombre ?>">
                </div>
                <div class="mb-3">
                    <label for="apellido_paterno" class="form-label">Apellido Paterno*</label>
                    <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno"
                        placeholder="Apellido Paterno" required maxlength="50" minlength="1"
                        value="<?= $user->apellido_paterno ?>">
                </div>
                <div class="mb-3">
                    <label for="apellido_materno" class="form-label">Apellido Materno</label>
                    <input type="text" class="form-control" id="apellido_materno" name="apellido_materno"
                        placeholder="Apellido Materno" maxlength="50" minlength="1"
                        value="<?= $user->apellido_materno ?>">
                </div>
                <?php if ($_SESSION['rol'] == "Estudiante"): ?>
                    <div class="mb-3">
                        <label for="universidad_id" class="form-label">Elije tu Universidad*</label>
                        <select class="selectpicker show-tick" data-style="btn-custom" title="Selecciona tu Universidad"
                            data-width="90%" data-live-search="true" id="universidad_id" name="universidad_id" required>
                            <?php foreach ($universidades->listar() as $key => $universidad): ?>
                                <option value="<?= $universidad->id ?>"><?= $universidad->nombre ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUniversidad"><i
                                class="bi bi-plus-circle-fill"></i></button>
                    </div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="email" class="form-label">Email*</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required
                        maxlength="50" minlength="1" value="<?= $user->email ?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                        maxlength="50" minlength="1">
                </div>
                <div class="mb-3">
                    <label for="confirmar_password" class="form-label">Confimrmar Password</label>
                    <input type="password" class="form-control" id="confirmar_password" name="confirmar_password"
                        placeholder="Confimrmar Password" maxlength="50" minlength="1">
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
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

<?php include ("../includes/footer.php") ?>

<script>
    $(document).ready(function () {
        $('#universidad_id').selectpicker('val', "<?= $user->universidad_id ?>");
    })
</script>