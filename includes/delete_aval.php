<?php

$id = $_GET['id'];

require_once("db.php");

$query = mysqli_query($conexion, "DELETE FROM avales WHERE id = '$id'");
header('Location: ../views/avales.php');
