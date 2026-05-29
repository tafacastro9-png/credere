<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');

$queryMantenimiento = mysqli_query(
    $conexion,
    "SELECT valor
     FROM parametrosinternos
     WHERE nombre = 'mantenimiento'
     LIMIT 1"
);

$row = mysqli_fetch_assoc($queryMantenimiento);

if($row['valor'] == 1){

    die('MANTENIMIENTO ACTIVADO');
}