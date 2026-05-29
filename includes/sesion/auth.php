<?php

if (session_status() == PHP_SESSION_NONE) {

    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');

die('ENTRO AL AUTH');

// =========================================
// MODO MANTENIMIENTO
// =========================================

$queryMantenimiento = mysqli_query(
    $conexion,
    "SELECT valor
     FROM parametrosinternos
     WHERE nombre = 'mantenimiento'
     LIMIT 1"
);

if($queryMantenimiento && mysqli_num_rows($queryMantenimiento) > 0){

    $rowMantenimiento = mysqli_fetch_assoc($queryMantenimiento);

    if($rowMantenimiento['valor'] == 1){

        include $_SERVER['DOCUMENT_ROOT'] .
        '/includes/mantenimiento.php';

        exit();
    }
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