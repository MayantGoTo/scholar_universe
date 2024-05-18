<?php
include ("includes/header.php");
include ("config/Mysql.php");
include ("modelos/Validaciones.php");
include ("modelos/Usuario.php");
$base = new Mysql();
$cx = $base->connect();
$val = new Validaciones();
$user = new Usuario($cx);
if (isset($_POST['acceder'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    if ($email == '' || empty($email) || $password == '' || empty($password)) {
        $error = "Todos los campos son obligatorios";
    } else {
        if ($user->login($email, $password)) {
            $mensaje = "Usuario identificado";
            $u = $user->consultaEmail($email);
            $_SESSION['auth'] = true;
            $_SESSION['id'] = $u->id;
            $_SESSION['nombre'] = $u->nombre;
            $_SESSION['apellido_paterno'] = $u->apellido_paterno;
            $_SESSION['apellido_materno'] = $u->apellido_materno;
            $_SESSION['email'] = $u->email;
            $_SESSION['rol'] = $u->rol;
            $_SESSION['estudiante_id'] = $u->estudiante_id;
            $_SESSION['matricula'] = $u->matricula;
            $_SESSION['universidad_id'] = $u->universidad_id;
            header('Location:dashboard/inicio.php');
        } else {
            $error = "Credenciales inválidas";
        }
    }
}
?>

<?php if ($val->validaLogeado() != ""): ?>
    <script>
       // window.location = '<?= $val->validaLogeado() ?>'; 
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

<div class="container-fluid mt-5">
    <h1 class="text-center">Acceso de Usuarios</h1>
    <div class="row">
        <div class="col-sm-6 offset-3">
            <div class="card">
                <div class="card-header">
                    Ingresa tus datos para acceder
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" name="email" placeholder="Ingresa el email">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" class="form-control" name="password"
                                placeholder="Ingresa el password">
                        </div>
                        <br />
                        <button type="submit" name="acceder" class="btn btn-primary w-100"><i
                                class="bi bi-person-bounding-box"></i> Acceder</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<?php include ("includes/footer.php") ?>