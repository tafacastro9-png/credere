<?php

session_start();

// =========================================
// MENSAJE SEGÚN MOTIVO
// =========================================

if(isset($_GET['inactividad'])){

    $mensaje =
    "La sesión fue cerrada por inactividad";

}else{

    $mensaje =
    "Debe iniciar sesión nuevamente";
}

// =========================================
// ELIMINAR SESIÓN
// =========================================

$_SESSION = [];

session_unset();

session_destroy();

// =========================================
// EVITAR CACHE
// =========================================

header("Cache-Control: no-cache, no-store, must-revalidate");

header("Pragma: no-cache");

header("Expires: 0");

?>

<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Cerrando sesión...</title>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>

body{

    margin:0;
    padding:0;

    display:flex;

    justify-content:center;
    align-items:center;

    height:100vh;

    background:#f5f7fb;

    font-family:Arial;
}

</style>

</head>

<body>

<script>

Swal.fire({

    icon: 'warning',

    title: 'Sesión finalizada',

    html: `
        <b>
        <?php echo $mensaje; ?>
        </b>
    `,

    confirmButtonText: 'Ir al Login',

    allowOutsideClick:false,

    confirmButtonColor:'#000a38'

}).then(() => {

    window.location.href =
    './login.php';

});

</script>

</body>

</html>