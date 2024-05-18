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
$materia = new Materia($cx);
$universidades = new Universidad($cx);
$categorias = new Categoria($cx);
$error = null;
$mensaje = null;
$id = 0;
$categorias_ids = "";
$universidad_ids = "";
if (isset($_GET['id']) && !empty($_GET['id']) || isset($_POST['id']) && !empty($_POST['id'])) {
    $id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
    $uni = $materia->getMateriaEstudiante($_SESSION['estudiante_id'], $id);
    foreach ($materia->getCategoriasMateria($id) as $key => $categoria) {
        $categorias_ids = $categorias_ids . ($key == 0 ? "" : ",") . $categoria->categoria_id;
    }
    foreach ($materia->getUniversidadesMateria($id) as $key => $item) {
        $universidad_ids = $universidad_ids . ($key == 0 ? "" : ",") . $item->univerdad_id;
    }
} else {
    $id = null;
}
if (isset($_POST['update'])) {
    $estudiante_id = $_SESSION['estudiante_id'];
    $archivos = $_FILES["archivo"];
    if ($val->isEmpty($archivos)) {
        $error = "Todos los campos son obligatorios";
    } else {
        if ($materia->colaborar($id, $estudiante_id, $archivos)) {
            $mensaje = "Se Actualizo el registro";
        } else {
            $error = "Existe un problema al actualizar";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["borrar"])) {
    $materia->borrarArchvioEstudiante();
}
?>
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
<!--Imprimir el error o el mensaje -->
<?php if ($val->validaSesion() != ""): ?>
    <script>
        window.location = '<?= $val->validaSesion() ?>'; 
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
            window.location = "show.php?id=<?= $id ?>";
        });
    </script>
<?php endif; ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= RUTA_DASHBOARD ?>inicio.php">Inicio</a></li>
        <li class="breadcrumb-item active"><a href="<?= RUTA_USER ?>mis_colaboraciones/index.php">Mis Colaboraciones</a>
        </li>
        <li class="breadcrumb-item" aria-current="page">Colaborar</li>
    </ol>
</nav>

<div class="row d-flex justify-content-center">
    <div class="card text-dark mb-3 shadow w-50 p-3 bg-body rounded">
        <div class="card-body">
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre*</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" disabled
                        value="<?= $uni->nombre ?>">
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripcion*</label>
                    <textarea class="form-control" placeholder="Descripcion" id="descripcion" name="descripcion"
                        disabled rows="5"><?= $uni->descripcion ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="universidad_id" class="form-label">Elije las Universidades*</label>
                    <select class="selectpicker show-tick" data-style="btn-custom" title="Selecciona las Universidades"
                        data-width="100%" data-live-search="true" id="universidad_id" name="universidad_id[]" multiple
                        disabled>
                        <?php foreach ($universidades->listar() as $key => $universidad): ?>
                            <option value="<?= $universidad->id ?>"><?= $universidad->nombre ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="materia_id" class="form-label">Elije las Categorias*</label>
                    <select class="selectpicker show-tick" data-style="btn-custom" title="Selecciona las Categorias"
                        data-width="100%" data-live-search="true" id="materia_id" name="materia_id[]" multiple disabled>
                        <?php foreach ($categorias->listar() as $key => $categoria): ?>
                            <option value="<?= $categoria->id ?>"><?= $categoria->nombre ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <p>Recursos</p>
                    <?php if (empty($materia->getArchivosMateriaColaboraciones($_SESSION['estudiante_id'], $id))): ?>
                        <p>Sin recursos</p>
                    <?php endif; ?>
                    <ol>
                        <?php foreach ($materia->getArchivosMateriaColaboraciones($_SESSION['estudiante_id'], $id) as $key => $item): ?>
                            <li><a href="<?= RUTA_FRONT . $item->ruta . "\\" . $item->nombre ?>" target="_blank"
                                    class="small" download><?= $item->nombre ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </div>
                <div class="mb-3">
                    <label for="archivo" class="form-label">Selecciona Tus Archivos</label>
                    <input type="file" class="form-control" name="archivo[]" id="archivo"
                        placeholder="Selecciona tu archivo" multiple required>
                </div>
                <div class="mb-3">
                    <p>Mis Colaboraciones</p>
                    <?php if (empty($materia->getArchivosMateriaEstudiante($_SESSION['estudiante_id'], $id))): ?>
                        <p>Aun no has colaborado con algun recurso</p>
                    <?php endif; ?>
                    <ol>
                        <?php foreach ($materia->getArchivosMateriaEstudiante($_SESSION['estudiante_id'], $id) as $key => $item): ?>
                            <li><a href="<?= RUTA_FRONT . $item->ruta . "\\" . $item->nombre ?>" target="_blank"
                                    class="small" download><?= $item->nombre ?></a>
                                <button class="btn btn-sm btn-danger btn-borrar" title="Eliminar" id="<?= $item->id ?>"><i
                                        class="bi bi-trash"></i></button>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="<?= RUTA_USER ?>mis_colaboraciones/index.php" class="btn btn-danger" tabindex="-1"
                        role="button" aria-disabled="true">Cancelar</a>
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

        $('.btn-borrar').off();
        $('.btn-borrar').click(function (e) {
            e.preventDefault();
            Swal.fire({
                title: "¿Estas seguro de borrar el registro?",
                showCancelButton: true,
                cancelButtonText: "Eliminar",
                confirmButtonText: "Cerrar",
                confirmButtonColor: "#6c757d",
                cancelButtonColor: "#dc3545",
            }).then((result) => {
                if (!result.isConfirmed) {
                    $.ajax({
                        url: "show.php",
                        method: "POST",
                        data: {
                            borrar: "borrar",
                            estudiante_id: "<?= $_SESSION['estudiante_id'] ?>", 
                            materia_id: "<?= $id ?>", 
                            archivo_id: $(this).attr('id'),
                            id: "<?= $id ?>"
                        },
                        dataType: "json",
                        complete: function (data) {
                            if (data.status == 200) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Exito",
                                    text: "Registro borrado exitosamente",
                                }).then((result) => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: "Ocurrio un error al borrar el registro",
                                });
                            }
                        },
                    });
                }
            });
        });
    });
</script>