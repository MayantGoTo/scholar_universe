<?php
include ("../../../includes/header.php");
include ("../../../config/Mysql.php");
include ("../../../modelos/Validaciones.php");
include ("../../../modelos/Estudiante.php");
include ("../../../modelos/Materia.php");
$base = new Mysql();
$cx = $base->connect();
$val = new Validaciones();
$estudiante = new Estudiante($cx);
$materia = new Materia($cx);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['seguir'])) {
    $materia->seguir();
}
?>

<div class="row g-3 align-items-center mb-5">
    <div class="col-auto">
        <h3>Mis Colaboraciones</h3>
    </div>
</div>

<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php foreach ($estudiante->getMisColaboraciones($_SESSION['estudiante_id']) as $key => $item): ?>
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
                        <button type="button"
                            class="btn btn<?= $item->seguir == "0" ? "-outline" : "" ?>-primary btn-seguir btn-sm"
                            id="<?= $item->id ?>" data="<?= $item->seguir == "0" ? "1" : "0" ?>"
                            title="Seguir"><?= $item->seguir == "0" ? "seguir" : "siguiendo" ?></button>
                        <?php if ($item->seguir == "1"): ?>
                            <a href="show.php?id=<?= $item->id ?>" class="btn btn-secondary btn-sm" title="Ver"><i
                                    class="bi bi-eye-fill"></i></a>
                        <?php endif; ?>
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
        $('.btn-seguir').off();
        $('.btn-seguir').click(function (e) {
            e.preventDefault();
            $.ajax({
                url: "index.php",
                method: "POST",
                data: {
                    seguir: $(this).attr('data'),
                    func: "seguir",
                    estudiante_id: "<?= $_SESSION['estudiante_id'] ?>", 
                    materia_id: $(this).attr('id')
                },
                dataType: "json",
                complete: function (data) {
                    if (data.status == 200) {
                        location.reload();
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Ocurrio un error al borrar el registro",
                        });
                    }
                },
            });
        });
    });
</script>