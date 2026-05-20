<?php
include("../includes/db.php");

$q = $_GET['q'] ?? '';

$res = mysqli_query($conexion,"
SELECT id, nombre 
FROM inversionistas
WHERE nombre LIKE '%$q%'
LIMIT 10
");

$data=[];

while($r=mysqli_fetch_assoc($res)){
    $data[]=[
        "id"=>$r['id'],
        "text"=>$r['nombre']
    ];
}

echo json_encode($data);