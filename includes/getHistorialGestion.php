<?php
require_once("db.php");

header('Content-Type: application/json');

$id_cliente = intval($_GET['id_cliente'] ?? 0);
$id_prestamo = intval($_GET['id_prestamo'] ?? 0);

$query = mysqli_query($conexion, "
SELECT * FROM gestion_cartera
WHERE id_cliente = $id_cliente
AND id_prestamo = $id_prestamo
ORDER BY fecha DESC
");

$historial = [];

while($row = mysqli_fetch_assoc($query)){
    $historial[] = $row;
}

echo json_encode([
    'historial' => $historial
]);