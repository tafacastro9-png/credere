<?php

$id = $_GET['id'];

require_once("db.php");

$query = mysqli_query($conexion, "DELETE FROM clientes WHERE id = '$id'");
header('Location: ../views/clientes.php');
