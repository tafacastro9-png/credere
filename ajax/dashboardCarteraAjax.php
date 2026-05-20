<?php
include("../includes/db.php");

$id_cliente = $_POST['id_cliente'] ?? 0;

// 🔥 FILTRO ÚNICO
$filtro = $id_cliente ? " AND p.id_cliente = '$id_cliente' " : "";

// 🔥 ESTADOS VÁLIDOS (AJUSTE AQUÍ)
$estado = "ep.statusPrest IN ('DESEMBOLSADO','VIGENTE')";

// ============================
// 🔹 TOTAL CARTERA
// ============================
$sql1 = "
SELECT IFNULL(SUM(p.monto_prestado),0) as total
FROM prestamos p
INNER JOIN estado_prestamo ep ON ep.id = p.id_estp
WHERE $estado
$filtro
";
$r1 = mysqli_fetch_assoc(mysqli_query($conexion, $sql1));

// ============================
// 🔹 TOTAL MORA
// ============================
$sql2 = "
SELECT IFNULL(SUM(cp.monto),0) as total
FROM cuotas_prestamo cp
INNER JOIN prestamos p ON p.id = cp.id_prestamo
INNER JOIN estado_prestamo ep ON ep.id = p.id_estp
WHERE cp.estado != 'PAGADO'
AND cp.fecha_pago < CURDATE()
AND $estado
$filtro
";
$r2 = mysqli_fetch_assoc(mysqli_query($conexion, $sql2));

$totalCartera = $r1['total'];
$totalMora = $r2['total'];

// ============================
// 🔹 PORCENTAJE
// ============================
$porcentaje = $totalCartera > 0 ? ($totalMora / $totalCartera) * 100 : 0;

// ============================
// 🔹 CLIENTE MÁS MOROSO
// ============================
$sql3 = "
SELECT 
    c.nombreClient,
    SUM(cp.monto) as deuda
FROM cuotas_prestamo cp
INNER JOIN prestamos p ON p.id = cp.id_prestamo
INNER JOIN clientes c ON c.id = p.id_cliente
INNER JOIN estado_prestamo ep ON ep.id = p.id_estp
WHERE cp.estado != 'PAGADO'
AND cp.fecha_pago < CURDATE()
AND $estado
" . ($id_cliente ? " AND p.id_cliente = '$id_cliente' " : "") . "
GROUP BY c.id
ORDER BY deuda DESC
LIMIT 1
";
$r3 = mysqli_fetch_assoc(mysqli_query($conexion, $sql3));

// ============================
// 🔹 RANKING
// ============================
$sql4 = "
SELECT 
    c.nombreClient,
    SUM(cp.monto) as deuda
FROM cuotas_prestamo cp
INNER JOIN prestamos p ON p.id = cp.id_prestamo
INNER JOIN clientes c ON c.id = p.id_cliente
INNER JOIN estado_prestamo ep ON ep.id = p.id_estp
WHERE cp.estado != 'PAGADO'
AND cp.fecha_pago < CURDATE()
AND $estado
" . ($id_cliente ? " AND p.id_cliente = '$id_cliente' " : "") . "
GROUP BY c.id
ORDER BY deuda DESC
LIMIT 5
";
$q4 = mysqli_query($conexion, $sql4);

$ranking = [];
while($row = mysqli_fetch_assoc($q4)){
    $ranking[] = [
        "nombre" => $row['nombreClient'],
        "deuda" => number_format($row['deuda'],0,',','.')
    ];
}

// ============================
// 🔹 GRÁFICA
// ============================
$sql5 = "
SELECT 
    MONTH(cp.fecha_pago) as mes,
    SUM(cp.monto) as total
FROM cuotas_prestamo cp
INNER JOIN prestamos p ON p.id = cp.id_prestamo
INNER JOIN estado_prestamo ep ON ep.id = p.id_estp
WHERE cp.estado != 'PAGADO'
AND cp.fecha_pago < CURDATE()
AND $estado
$filtro
GROUP BY MONTH(cp.fecha_pago)
ORDER BY mes
";
$q5 = mysqli_query($conexion, $sql5);

$grafica = [];
while($row = mysqli_fetch_assoc($q5)){
    $grafica[] = [
        "mes" => $row['mes'],
        "total" => $row['total']
    ];
}

// ============================
// 🔹 RESPONSE
// ============================
echo json_encode([
    "totalCartera" => number_format($totalCartera,0,',','.'),
    "totalMora" => number_format($totalMora,0,',','.'),
    "porcentajeMora" => round($porcentaje,2),
    "clienteMora" => $r3['nombreClient'] ?? '-',
    "valorMora" => number_format($r3['deuda'] ?? 0,0,',','.'),
    "ranking" => $ranking,
    "grafica" => $grafica
]);