<?php
include (dirname(__FILE__) . "/../config/config.php");
session_start();
?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="<?= RUTA_FRONT ?>css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= RUTA_FRONT ?>css/bootstrap-icons-1.2.1/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= RUTA_FRONT ?>css/dataTables.min.css">
  <link rel="stylesheet" href="<?= RUTA_FRONT ?>css/sweetalert2.min.css">
  <link rel="stylesheet" href="<?= RUTA_FRONT ?>css/bootstrap-select.min.css">
  <link rel="stylesheet" href="<?= RUTA_FRONT ?>css/style.css">
  <script src="<?= RUTA_FRONT ?>js/jquery-3.7.1.min.js"></script>
  <!-- <script src="js/bootstrap.min.js"></script> -->
  <script src="<?= RUTA_FRONT ?>js/bootstrap.bundle.min.js"></script>
  <script src="<?= RUTA_FRONT ?>js/popper.min.js"></script>
  <script src="<?= RUTA_FRONT ?>js/dataTables.min.js"></script>
  <!-- <script src="js/sweetalert2.all.min.js"></script> -->
  <script src="<?= RUTA_FRONT ?>js/sweetalert2@10.js"></script>
  <script src="<?= RUTA_FRONT ?>js/bootstrap-select.min.js"></script>
  <script src="<?= RUTA_FRONT ?>js/defaults-es_ES.js"></script>

  <title>ITSAL</title>
</head>

<body style= background-color: #F5F5F5;>

  <?php
  include ("menu.php");
  ?>
  <div class="container mt-5 caja">