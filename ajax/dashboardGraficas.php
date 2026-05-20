<?php
include("../includes/db.php");

$id = $_POST['id'] ?? 0;

$where = "WHERE 1=1";
if($id){
    $where .= " AND id_inversionista='$id'";
}

// CRECIMIENTO
$q = mysqli_query($conexion,"
SELECT DATE(fecha) fecha,
SUM(CASE 
WHEN tipo='APORTE' THEN valor
WHEN tipo='RETIRO' AND medio_pago='LIQUIDACION' THEN -valor
ELSE 0 END) total
FROM movimientos_inversionista
$where
GROUP BY DATE(fecha)
");

$crecimiento=[];
while($r=mysqli_fetch_assoc($q)) $crecimiento[]=$r;

// FLUJO
$q2 = mysqli_query($conexion,"
SELECT DATE(fecha) fecha,
SUM(CASE WHEN tipo='APORTE' THEN valor ELSE 0 END) ingresos,
SUM(CASE WHEN tipo='RETIRO' THEN valor ELSE 0 END) egresos
FROM movimientos_inversionista
$where
GROUP BY DATE(fecha)
");

$flujo=[];
while($r=mysqli_fetch_assoc($q2)) $flujo[]=$r;

// INTERESES
$q3 = mysqli_query($conexion,"
SELECT DATE_FORMAT(fecha,'%Y-%m') mes,
SUM(interes) total
FROM movimientos_inversionista
$where AND interes>0
GROUP BY mes
");

$intereses=[];
while($r=mysqli_fetch_assoc($q3)) $intereses[]=$r;

// TOP
$q4 = mysqli_query($conexion,"
SELECT i.nombre, SUM(m.valor) total
FROM inversionistas i
JOIN movimientos_inversionista m ON m.id_inversionista=i.id
WHERE m.tipo='APORTE'
GROUP BY i.id
ORDER BY total DESC
LIMIT 5
");

$top=[];
while($r=mysqli_fetch_assoc($q4)) $top[]=$r;

echo json_encode([
"crecimiento"=>$crecimiento,
"flujo"=>$flujo,
"intereses"=>$intereses,
"top"=>$top
]);