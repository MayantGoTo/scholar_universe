<?php
include ("includes/header.php");
include ("config/Mysql.php");
$base = new Mysql();
$cx = $base->connect();
$sesion = isset($_SESSION['auth']);
?>

<?php if ($sesion): ?>
    <script>
        window.location = 'admin/inicio.php';
    </script>
<?php endif; ?>

<div class="text-center">
    <img src="<?= RUTA_FRONT ?>/img/Logo2.png" class="img-fluid" alt="..." style="width: 300px; height: auto;">
</div>
<br />
<?php include ("includes/footer.php") ?>

<script>
    $(document).ready(function () {
    });
</script>