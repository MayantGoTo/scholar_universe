<?php $sesion = isset($_SESSION['auth']) ? $_SESSION['auth'] : false; ?>
<nav class="navbar navbar-expand-sm navbar-dark " style="background-color: #2d717d;" aria-label="Third navbar example">
    <div class="container-fluid">
       
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample03"
            aria-controls="navbarsExample03" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample03">
            <ul class="navbar-nav me-auto mb-2 mb-sm-0">
                <!-- valida si esta logeado -->
                <?php if ($sesion): ?>

                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?= RUTA_DASHBOARD ?>inicio.php">Inicio</a>
                    </li>
                    <?php if ($_SESSION['rol'] == "Root"): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-bs-toggle="dropdown"
                                aria-expanded="false">Administración</a>
                            <ul class="dropdown-menu" aria-labelledby="dropdown03">
                                <li><a class="dropdown-item" href="<?= RUTA_ADMIN ?>usuario/index.php">Gestionar
                                        Usuarios</a></li>
                                <li><a class="dropdown-item" href="<?= RUTA_ADMIN ?>universidad/index.php">Gestionar
                                        Universidades</a></li>
                                <li><a class="dropdown-item" href="<?= RUTA_ADMIN ?>categoria/index.php">Gestionar
                                        Categorias</a></li>
                                <li><a class="dropdown-item" href="<?= RUTA_ADMIN ?>materia/index.php">Gestionar
                                        Materias</a></li>
                                <li><a class="dropdown-item" href="<?= RUTA_ADMIN ?>estudiante/index.php">Gestionar
                                        Estudiantes</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if ($_SESSION['rol'] == "Estudiante"): ?>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="<?= RUTA_USER ?>mis_materias/index.php">Mis
                                Materias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="<?= RUTA_USER ?>mis_colaboraciones/index.php">Mis
                                Colaboraciones</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link ml-5" aria-current="page" href="<?= RUTA_DASHBOARD ?>perfil.php">Perfil</a>
                    </li>

                <?php endif; ?>

                <!-- menu para usuario no logeado -->
                <?php if (!$sesion): ?>
                    <ul class="nav justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?= RUTA_FRONT ?>inicio.php">Inicio</a>
                    </li>
                <?php endif; ?>
            </ul>
            <?php if (!$sesion): ?>
                <ul class="navbar-nav mb-2 mb-lg-0"> 
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="<?= RUTA_FRONT ?>acceder.php">Acceder</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="<?= RUTA_FRONT ?>registrarse.php">Registrarse</a>
                    </li>
                </ul>
            <?php endif; ?>
            <?php if ($sesion): ?>
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">
                        <p class="text-white mt-2"><i class="bi bi-person-circle"></i> <span
                                class="badge bg-light text-dark"><?= $_SESSION['nombre'] . " " . $_SESSION['apellido_paterno'] ?><span
                                    class="badge bg-light text-dark"></p>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= RUTA_FRONT ?>salir.php">Salir</a>
                    </li>
                </ul>
            <?php endif; ?>

        </div>
    </div>
</nav>
