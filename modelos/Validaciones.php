<?php
class Validaciones
{

    public function __construct()
    {
    }

    public function validaSesionRoot()
    {
        $sesion = isset($_SESSION['auth']) ? $_SESSION['rol'] == "Root" : false;
        if (!$sesion) {
            $ruta = (isset($_SESSION['auth']) ? '' : '../') . '../../inicio.php';
            return $ruta;
        }
        return "";
    }

    public function validaSesion()
    {
        $sesion = isset($_SESSION['auth']) ? $_SESSION['auth'] : false;
        if (!$sesion) {
            $ruta = '../inicio.php';
            return $ruta;
        }
        return "";
    }

    public function validaLogeado()
    {
        $sesion = isset($_SESSION['auth']) ? $_SESSION['auth'] : false;
        if ($sesion) {
            $ruta = 'dashboard/inicio.php';
            return $ruta;
        }
        return "";
    }

    public function isEmpty($data)
    {
        return $data == '' || empty($data);
    }

    public function actualizaSesion($nombre, $apellido_paterno, $apellido_materno, $matricula, $universidad_id)
    {
        $_SESSION['auth'] = true;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['apellido_paterno'] = $apellido_paterno;
        $_SESSION['apellido_materno'] = $apellido_materno;
        $_SESSION['matricula'] = $matricula;
        $_SESSION['universidad_id'] = $universidad_id;
    }

}