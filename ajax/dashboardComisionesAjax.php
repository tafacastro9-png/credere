<?php
include("../includes/db.php");

$anio = $_POST['anio'] ?? date('Y');
$mes = $_POST['mes'] ?? '';

$filtro = "WHERE ep.statusPrest = 'DESEMBOLSADO'";

if(!empty($anio)){
    $filtro .= " AND YEAR(p.fecha_inicio) = '$anio'";
}

if(!empty($mes)){
    $filtro .= " AND MONTH(p.fecha_inicio) = '$mes'";
}

// 🔥 KPIs
$sql = "
SELECT 
    SUM(p.monto_prestado) as totalVentas,
    SUM(p.monto_prestado * 0.20 * 0.20) as totalComisiones,
    COUNT(DISTINCT p.id_usuario_radica) as totalAsesores
FROM prestamos p
INNER JOIN estado_prestamo ep ON ep.id = p.id_estp
$filtro
";

$r = mysqli_fetch_assoc(mysqli_query($conexion, $sql));

// 🔥 Mejor asesor
$sql2 = "
SELECT u.usuario
FROM prestamos p
INNER JOIN estado_prestamo ep ON ep.id = p.id_estp
LEFT JOIN users u ON u.id = p.id_usuario_radica
$filtro
GROUP BY u.usuario
ORDER BY SUM(p.monto_prestado) DESC
LIMIT 1
";

$r2 = mysqli_fetch_assoc(mysqli_query($conexion, $sql2));

// 🔥 Ranking
$sql3 = "
SELECT 
    u.usuario,
    SUM(p.monto_prestado) as ventas,
    SUM(p.monto_prestado * 0.20 * 0.20) as comision
FROM prestamos p
INNER JOIN estado_prestamo ep ON ep.id = p.id_estp
LEFT JOIN users u ON u.id = p.id_usuario_radica
$filtro
GROUP BY u.usuario
ORDER BY ventas DESC
";

$q3 = mysqli_query($conexion, $sql3);

$ranking = [];
while($row = mysqli_fetch_assoc($q3)){
    $ranking[] = [
        "usuario" => $row['usuario'],
        "ventas" => number_format($row['ventas'],0,',','.'),
        "comision" => number_format($row['comision'],0,',','.')
    ];
}

// 🔥 Gráfica mensual
if(!empty($mes)){

    // 🔥 GRÁFICA POR ASESOR (cuando filtran mes)
    $sql4 = "
    SELECT 
        u.usuario as mes,
        SUM(p.monto_prestado * 0.20 * 0.20) as total
    FROM prestamos p
    INNER JOIN estado_prestamo ep ON ep.id = p.id_estp
    LEFT JOIN users u ON u.id = p.id_usuario_radica
    $filtro
    GROUP BY u.usuario
    ORDER BY total DESC
    ";

} else {

    // 🔥 GRÁFICA POR MES (cuando NO hay mes)
    $sql4 = "
    SELECT 
        MONTH(p.fecha_inicio) as mes,
        SUM(p.monto_prestado * 0.20 * 0.20) as total
    FROM prestamos p
    INNER JOIN estado_prestamo ep ON ep.id = p.id_estp
    $filtro
    GROUP BY MONTH(p.fecha_inicio)
    ORDER BY mes
    ";

}

$q4 = mysqli_query($conexion, $sql4);

$grafica = [];
while($row = mysqli_fetch_assoc($q4)){
    $grafica[] = [
        "mes" => $row['mes'],
        "total" => $row['total']
    ];
}

echo json_encode([
    "totalVentas" => number_format($r['totalVentas'],0,',','.'),
    "totalComisiones" => number_format($r['totalComisiones'],0,',','.'),
    "totalAsesores" => $r['totalAsesores'],
    "mejorAsesor" => $r2['usuario'] ?? '-',
    "ranking" => $ranking,
    "grafica" => $grafica
]);