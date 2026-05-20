<?php

$host = "127.0.0.1";
$port = 3306;
$user = "root";
$password = "";
$database = "prestamodbcliente";

$conexion = mysqli_connect($host, $user, $password, $database, $port);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}