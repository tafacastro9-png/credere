<?php

if (session_status() == PHP_SESSION_NONE) {

    session_start();
}

// =========================================
// EVITAR CACHE
// =========================================

header("Cache-Control: no-cache, no-store, must-revalidate");

header("Pragma: no-cache");

header("Expires: 0");

// =========================================
// VALIDAR SESIÓN
// =========================================

if(
    !isset($_SESSION['id_usuario']) ||
    empty($_SESSION['id_usuario'])
){

    header(
        "Location: ../includes/sesion/cerrarSesion.php"
    );

    exit();
}

?>