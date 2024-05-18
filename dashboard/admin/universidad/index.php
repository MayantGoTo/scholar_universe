<?php
include ("../../../includes/header.php");
include ("../../../config/Mysql.php");
include ("../../../modelos/Validaciones.php");
include ("../../../modelos/Universidad.php");
$base = new Mysql();
$cx = $base->connect();
$val = new Validaciones();
$universidades = new Universidad($cx);
$crud = "Universidades";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $universidades->borrar();
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
    <div class="col-sm-4 offset-2">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="crear.php" class="btn btn-success"><i class="bi bi-plus-circle-fill"></i> Agregar</a>
        </div>
    </div>
</div>
<div class="row mt-2 caja">
    <div class="col-sm-12">
        <table id="tblData" class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th style="width:15%;">Nombre</th>
                    <th>Direccion</th>
                    <th style="width:15%;">Pais</th>
                    <th style="width:15%;">Estado</th>
                    <th style="width:15%;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($universidades->listar() as $item): ?>
                    <tr>
                        <td><?= $item->id ?></td>
                        <td><?= $item->nombre ?></td>
                        <td><?= $item->direccion ?></td>
                        <td><?= $item->pais ?></td>
                        <td><?= $item->estado ?></td>
                        <td>
                            <a href="show.php?id=<?= $item->id ?>" title="Ver" class="btn btn-secondary"><i
                                    class="bi bi-eye-fill"></i></a>
                            <a href="editar.php?id=<?= $item->id ?>" title="Editar" class="btn btn-primary"><i
                                    class="bi bi-pencil-fill"></i></a>
                            <button class="btn btn-danger btn-borrar" title="Eliminar" id="<?= $item->id ?>"><i
                                    class="bi bi-trash"></i></button>
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
                        url: "index.php",
                        method: "POST",
                        data: { funcion: "borrar", id: $(this).attr('id') },
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