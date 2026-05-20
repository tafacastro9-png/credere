<?php
error_reporting(0);
session_start();
$usuario = $_SESSION['usuario'];
$correo = $_SESSION['correo'];
$permiso = $_SESSION['type'];
if ($usuario == null || $usuario == ''  && $permiso == null || $permiso == '') {

?>

    <script src="../js/validacionSesionActiva.js"></script>
    <script src="../js/SweetAlert2/sweetalert2.all.js"></script>
    <script src="../js/SweetAlert2/sweetalert2.all.min.js"></script>
<?php die();
} ?>