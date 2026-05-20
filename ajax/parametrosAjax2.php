<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "INICIO<br>";

$conexion = mysqli_connect("localhost","root","","prestamodb");

if(!$conexion){
    die("ERROR CONEXION: " . mysqli_connect_error());
}

echo "CONEXION OK<br>";

$sql = "SELECT id, nombre, descripcion, valor FROM parametros";

$q = mysqli_query($conexion, $sql);

if(!$q){
    die("ERROR QUERY: " . mysqli_error($conexion));
}

echo "QUERY OK<br>";

$data = [];

while($row = mysqli_fetch_assoc($q)){
    $data[] = $row;
}

echo "ANTES JSON<br>";

header('Content-Type: application/json');
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
echo "<br>FIN";
exit;