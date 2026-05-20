<?php
require_once("db.php");

if(!isset($_GET['id'])){
    echo json_encode([]);
    exit;
}

$id = intval($_GET['id']);

$query = mysqli_query($conexion, "
    SELECT r.motivo, r.fecha, u.usuario
    FROM rechazo_documentos r
    LEFT JOIN users u ON r.id_usuario = u.id
    WHERE r.id_prestamo = $id
    ORDER BY r.fecha DESC
");

$historial = [];

while($row = mysqli_fetch_assoc($query)){
    $historial[] = $row;
}

echo json_encode($historial);
?>
