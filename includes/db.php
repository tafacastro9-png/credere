<?php

$host = "127.0.0.1";
$port = 3306;
$user = "u205183886_Fabian";
$password = "Faca2026*";
$database = "u205183886_prestamodb";

$conexion = mysqli_connect($host, $user, $password, $database, $port);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}