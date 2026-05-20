<?php
include "db.php";

$search = $_GET['term'];

$sql = "SELECT id, nombre 
        FROM municipios 
        WHERE nombre LIKE '%$search%' 
        ORDER BY nombre ASC 
        LIMIT 20";

$resultado = mysqli_query($conexion, $sql);

$data = [];

while ($row = mysqli_fetch_assoc($resultado)) {
    $data[] = [
        "id" => $row['id'],
        "text" => $row['nombre']
    ];
}

echo json_encode($data);
?>