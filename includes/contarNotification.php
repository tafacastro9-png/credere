<?php
include_once("db.php");
date_default_timezone_set('America/Bogota');

$lunes = date('Y-m-d', strtotime('monday this week'));
$domingo = date('Y-m-d', strtotime('sunday this week'));

$sql = "SELECT COUNT(*) as count
        FROM prestamos p
        INNER JOIN clientes c ON p.id_cliente = c.id
        INNER JOIN avales a ON p.id_aval = a.id
        INNER JOIN tipo_prestamo tp ON p.id_tp = tp.id
        INNER JOIN estado_prestamo ep ON p.id_estp = ep.id
        INNER JOIN detalle_prestamo dp ON dp.id_prestamo = p.id
        WHERE p.id_estp = '1'
        AND DATE(p.fechaRegistro) BETWEEN '$lunes' AND '$domingo'";

$result = mysqli_query($conexion, $sql);
$row = mysqli_fetch_assoc($result);
echo $row['count'];
?>