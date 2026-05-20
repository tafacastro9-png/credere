<?php

$id = $_GET['id'];

require_once("db.php");

$query = mysqli_query($conexion, "DELETE FROM tipo_prestamo WHERE id = '$id'");
header('Location: ../views/tiposPrestamos.php');
