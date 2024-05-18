<?php
include ("../../../includes/header.php");
include ("../../../config/Mysql.php");
include ("../../../modelos/Validaciones.php");
include ("../../../modelos/Estudiante.php");
include ("../../../modelos/Materia.php");
// include ("../modelos/Alumno.php");
// include ("../modelos/Carrera.php");
$base = new Mysql();
$cx = $base->connect();
$val = new Validaciones();
$estudiante = new Estudiante($cx);
$materia = new Materia($cx);
// $alumnos = new Alumno($cx);
// $carreras = new Carrera($cx);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $materia->borrarMateriaArchvioEstudiante();
}
?>


<div class="row g-3 align-items-center mb-5">
    <div class="col-auto">
        <h3>Mis materias</h3>
    </div>
    <div class="col-auto">
        <a href="crear.php" class="btn btn-success"><i class="bi bi-plus-circle-fill"></i> Agregar</a>
    </div>
</div>

<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php foreach ($estudiante->getMisMaterias($_SESSION['estudiante_id']) as $key => $item): ?>
        <div class="col">
            <div class="card h-100 shadow bg-card-secondary card-hover">
                <div class="card-body">
                    <h5 class="card-title"><?= $item->nombre ?></h5>
                    <p class="card-text"><?= $item->descripcion ?></p>
                    <div class="text-center mb-3">
                        <p class="h6">Categorias</p>
                        <?php foreach ($estudiante->getCategoriasMateria($item->id) as $key => $items): ?>
                            <span class="badge bg-secondary"><?= $items->nombre ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-danger btn-borrar" id="<?= $item->id ?>" title="Eliminar"><i
                                class="bi bi-trash"></i></button>
                        <a href="editar.php?id=<?= $item->id ?>" class="btn btn-primary" title="Editar"><i
                                class="bi bi-pencil-square"></i></a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (empty($estudiante->getMisMaterias($_SESSION['estudiante_id']))): ?>
        <p class="h3">Sin Materias Registradas</p>
    <?php endif; ?>
</div>
<br />
<?php include ("../../../includes/footer.php") ?>

<script>
    $(document).ready(function () {
        $('.btn-borrar').off();
        $('.btn-borrar').click(function (e) {
            e.preventDefault();
            Swal.fire({
                title: "¿Estas seguro de borrar esta materia?",
                text: "esto solo borrar tus archivos y tu vinculacion con la materia, una vez hecho esto no se podra recuperar los archvios",
                showCancelButton: true,
                cancelButtonText: "Eliminar",
                confirmButtonText: "Cerrar",
                confirmButtonColor: "#6c757d",
                cancelButtonColor: "#dc3545",
            }).then((result) => {
                if (!result.isConfirmed) {
                    $.ajax({
                        url: "index.php",
                        method: "POST",
                        data: {
                            borrar: "borrar",
                            estudiante_id: "<?= $_SESSION['estudiante_id'] ?>", 
                            materia_id: $(this).attr('id')
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