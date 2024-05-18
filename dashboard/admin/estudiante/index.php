<?php
include ("../../../includes/header.php");
include ("../../../config/Mysql.php");
include ("../../../modelos/Validaciones.php");
include ("../../../modelos/Estudiante.php");
$base = new Mysql();
$cx = $base->connect();
$val = new Validaciones();
$estudiantes = new Estudiante($cx);
$crud = "Estudiantes";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $estudiantes->borrar();
}
?>

<?php if ($val->validaSesionRoot() != ""): ?>
    <script>
        window.location = '<?= $val->validaSesionRoot() ?>'; 
    </script>
<?php endif; ?>
<!--Imprimir el error o el mensaje -->

<div class="row">
    <div class="col-sm-6">
        <h3>Lista de <?= $crud ?></h3>
    </div>
</div>
<div class="row mt-2 caja">
    <div class="col-sm-12">
        <table id="tblData" class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Matricula</th>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Universidad</th>
                    <th style="width:15%;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($estudiantes->listar() as $item): ?>
                    <tr>
                        <td><?= $item->id ?></td>
                        <td><?= $item->matricula ?></td>
                        <td><?= $item->nombre ?></td>
                        <td><?= $item->apellido_paterno ?></td>
                        <td><?= $item->apellido_materno ?></td>
                        <td><?= $item->universidad ?></td>
                        <td>
                            <a href="show.php?id=<?= $item->id ?>" title="Ver" class="btn btn-secondary"><i
                                    class="bi bi-eye-fill"></i></a>
                            <a href="editar.php?id=<?= $item->id ?>" title="Editar" class="btn btn-primary"><i
                                    class="bi bi-pencil-fill"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include ("../../../includes/footer.php") ?>

<script>
    $(document).ready(function () {
        $('#tblData').DataTable({
            language: {
                url: '<?= RUTA_FRONT ?>js/es-ES.json',
            },
        });
    });
</script>