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
$mensaje = null;
if (isset($_POST['update'])) {
    $nombre = $_POST["nombre"];
    $email = $_POST['email'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $rol = "2";
    $password = $_POST["password"];
    $confirmar_password = $_POST['confirmar_password'];
    if ($val->isEmpty($nombre) || $val->isEmpty($email) || $val->isEmpty($apellido_paterno) || $val->isEmpty($password) || $val->isEmpty($confirmar_password)) {
        $error = "Todos los campos son obligatorios";
    } else {
        if ($usuario->validaEmail($email)) {
            $error = "el correo electrónico ya está registrado";
        } else {
            if ($password == $confirmar_password) {
                if ($usuario->guardaActualiza("", $nombre, $apellido_paterno, $apellido_materno, $email, $password, $rol)) {
                    $mensaje = "Se ha creado el registro";
                } else {
                    $error = "Existe un problema al guardado";
                }
            } else {
                $error = "Las contraseñas no coinciden";
            }
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
        <li class="breadcrumb-item active"><a href="<?= RUTA_ADMIN ?>usuario/index.php">Administración - Usuarios</a>
        </li>
        <li class="breadcrumb-item" aria-current="page">Crear Usuario</li>
    </ol>
</nav>

<div class="row mt-3">
    <p class="h3 text-center">Crear</p>
</div>

<div class="row d-flex justify-content-center">
    <div class="card text-dark mb-3 shadow w-50 p-3 bg-body rounded">
        <div class="card-body">
            <form method="POST" action="" enctype="application/json">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre(s)*</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre(s)" required
                        maxlength="50" minlength="1" autofocus>
                </div>
                <div class="mb-3">
                    <label for="apellido_paterno" class="form-label">Apellido Paterno*</label>
                    <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno"
                        placeholder="Apellido Paterno" required maxlength="50" minlength="1">
                </div>
                <div class="mb-3">
                    <label for="apellido_materno" class="form-label">Apellido Materno</label>
                    <input type="text" class="form-control" id="apellido_materno" name="apellido_materno"
                        placeholder="Apellido Materno" maxlength="50" minlength="1">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email*</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required
                        maxlength="50" minlength="1">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password*</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                        maxlength="50" minlength="1" required>
                </div>
                <div class="mb-3">
                    <label for="confirmar_password" class="form-label">Confimrmar Password*</label>
                    <input type="password" class="form-control" id="confirmar_password" name="confirmar_password"
                        placeholder="Confimrmar Password" maxlength="50" minlength="1" required>
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="<?= RUTA_ADMIN ?>usuario/index.php" class="btn btn-danger" tabindex="-1" role="button"
                        aria-disabled="true">Cancelar</a>
                    <button type="submit" name="update" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include ("../../../includes/footer.php") ?>