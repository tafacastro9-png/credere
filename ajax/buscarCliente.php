<?php
include("../includes/db.php");

$q = $_GET['q'] ?? '';

$sql = "
SELECT DISTINCT 
    c.id,
    c.nombreClient
FROM clientes c
INNER JOIN prestamos p ON p.id_cliente = c.id
INNER JOIN estado_prestamo ep ON ep.id = p.id_estp
WHERE ep.id = 6
AND c.nombreClient LIKE '%$q%'
ORDER BY c.nombreClient ASC
LIMIT 10
";

$resultado = mysqli_query($conexion, $sql);

$data = [];

while($row = mysqli_fetch_assoc($resultado)){
    $data[] = [
        "id" => $row['id'],
        "text" => $row['nombreClient']
    ];
}

echo json_encode($data);