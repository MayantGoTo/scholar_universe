<?php
include ("includes/header.php");
include ("config/Mysql.php");
include ("modelos/Validaciones.php");
include ("modelos/Usuario.php");
include ("modelos/Estudiante.php");
include ("modelos/Universidad.php");
$base = new Mysql();
$cx = $base->connect();
$val = new Validaciones();
$usuario = new Usuario($cx);
$estudiante = new Estudiante($cx);
$universidades = new Universidad($cx);
$bndSaveUni = false;
if (isset($_POST["registrarse"])) {
    $nombre = $_POST["nombre"];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $universidad_id = $_POST['universidad_id'];
    $estudiante_id = "";
    $matricula = $_POST['matricula'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmar_password = $_POST['confirmar_password'];
    $id = "";
    if ($val->isEmpty($nombre) || $val->isEmpty($apellido_paterno) || $val->isEmpty($universidad_id) || $val->isEmpty($matricula) || $val->isEmpty($email) || $val->isEmpty($password) || $val->isEmpty($confirmar_password)) {
        $error = "Todos los campos son obligatorios";
    } else {
        if ($usuario->validaEmail($email)) {
            $error = "el correo electrónico ya está registrado";
        } else {
            $usuario_id = false;
            $usuario_id = $usuario->guardaActualiza("", $nombre, $apellido_paterno, $apellido_materno, $email, $password, "1");
            if ($usuario_id) {
                $usuario_id = $estudiante->guardaActualiza("", $matricula, $universidad_id, $usuario_id);
            }
            if ($usuario_id) {
                $mensaje = "Se ha actualizado el registro";
            } else {
                $error = "Existe un problema al actualizar";
            }
        }
    }
}
if (isset($_POST["addUniversidad"])) {
    $nombre = $_POST["nombre"];
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

<?php if ($val->validaLogeado() != ""): ?>
    <script>
        window.location = '<?= $val->validaLogeado() ?>'; 
    </script>
<?php endif; ?>
<!--Imprimir el error o el mensaje -->
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
            window.location = "<?= !$bndSaveUni ? "acceder.php" : "registrarse.php" ?>";
        });
    </script>
<?php endif; ?>

<div class="container-fluid mt-5">
    <h1 class="text-center">Registro de Usuarios</h1>
    <div class="row">
        <div class="col-sm-6 offset-3">
            <div class="card">
                <div class="card-header">
                    Crear Cuenta
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="matricula" class="form-label">Matricula*</label>
                            <input type="text" class="form-control" id="matricula" name="matricula"
                                placeholder="Matricula" required maxlength="50" minlength="1">
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre(s)*</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre(s)"
                                required maxlength="50" minlength="1">
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
                            <label for="universidad_id" class="form-label">Universidad*</label>
                            <select class="selectpicker show-tick" data-style="btn-custom" title="Seleccione la carrera"
                                data-width="90%" data-live-search="true" id="universidad_id" name="universidad_id"
                                required>
                                <?php foreach ($universidades->listar() as $key => $item): ?>
                                    <option value="<?= $item->id ?>"><?= $item->nombre ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUniversidad"><i
                                    class="bi bi-plus-circle-fill"></i></button>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" name="email" placeholder="Ingresa el email"
                                required maxlength="50" minlength="1">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" class="form-control" name="password"
                                placeholder="Ingresa el password" required maxlength="50" minlength="1">
                        </div>

                        <div class="mb-3">
                            <label for="confirmarPassword" class="form-label">Confirmar password:</label>
                            <input type="password" class="form-control" name="confirmar_password"
                                placeholder="Ingresa la confirmación del password" required maxlength="50"
                                minlength="1">
                        </div>

                        <br />
                        <button type="submit" name="registrarse" class="btn btn-primary w-100"><i
                                class="bi bi-person-bounding-box"></i> Registrarse</button>
                    </form>
                </div>
            </div>
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
                        <label for="nombre" class="form-label">Nombre(s)*</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre(s)"
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
<?php include ("includes/footer.php") ?>

<script>
    $(document).ready(function () {
        $('#universidad_id').selectpicker();
    })
</script>