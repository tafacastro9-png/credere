<?php
include("../includes/db.php");

$id = $_POST['id'] ?? 0;

// 🔥 FIX CLAVE (NO ROMPE EL AND)
$where = "WHERE 1=1";
if($id){
    $where .= " AND id_inversionista='$id'";
}

// 🔹 CAPITAL
$q = mysqli_query($conexion,"
    SELECT IFNULL(SUM(
        CASE 
            WHEN tipo='APORTE' THEN valor
            WHEN tipo='RETIRO' AND interes = 0 THEN -valor
            ELSE 0
        END
    ),0) as capital
    FROM movimientos_inversionista
    $where
");

if(!$q){
    die("ERROR SQL CAPITAL: " . mysqli_error($conexion));
}

$row = mysqli_fetch_assoc($q);
$capital = $row['capital'] ?? 0;


// 🔹 INTERESES
$q2 = mysqli_query($conexion,"
    SELECT IFNULL(SUM((valor * tasa / 100) * meses),0) as interes
    FROM movimientos_inversionista
    $where
    AND tipo='APORTE'
");

if(!$q2){
    die("ERROR SQL INTERES: " . mysqli_error($conexion));
}

$row2 = mysqli_fetch_assoc($q2);
$interes = $row2['interes'] ?? 0;


// 🔹 RETIRADOS
$q3 = mysqli_query($conexion,"
    SELECT IFNULL(SUM(interes),0) as retirado
    FROM movimientos_inversionista
    $where
    AND tipo='RETIRO'
    AND interes > 0
");

if(!$q3){
    die("ERROR SQL RETIRADOS: " . mysqli_error($conexion));
}

$row3 = mysqli_fetch_assoc($q3);
$retirado = $row3['retirado'] ?? 0;


// 🔥 RESPUESTA
header('Content-Type: application/json');

echo json_encode([
    "capital" => $capital,
    "interes" => $interes,
    "retirado" => $retirado,
    "disponible" => max(0, $interes - $retirado)
]);

exit;