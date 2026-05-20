<?php

header('Content-Type: application/json; charset=utf-8');

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("db.php");

mysqli_set_charset($conexion, "utf8");

// ==========================
// CLIENTES EN CARTERA
// ==========================
$query = mysqli_query($conexion, "

SELECT
    p.id as id_prestamo,

    p.folioPrest as prestamo,

    c.id as id_cliente,

    CONCAT(
        c.nombreClient,
        ' ',
        c.apellidoClient
    ) as cliente,

    c.celClient as telefono,

    SUM(
        IFNULL(cp.saldo, cp.monto)
    ) as saldo,

    MAX(
        CASE
            WHEN cp.fecha_pago < CURDATE()
            THEN DATEDIFF(CURDATE(), cp.fecha_pago)
            ELSE 0
        END
    ) as dias_mora,

    ep.statusPrest as estado,

    gc.seguimiento_activo,
    gc.estado_seguimiento,
    gc.proxima_gestion,
    gc.fecha_promesa,
    gc.valor_promesa

FROM prestamos p

INNER JOIN clientes c
ON p.id_cliente = c.id

INNER JOIN cuotas_prestamo cp
ON cp.id_prestamo = p.id

INNER JOIN estado_prestamo ep
ON ep.id = p.id_estp

LEFT JOIN (

    SELECT g1.*

    FROM gestion_cartera g1

    INNER JOIN (

        SELECT
            id_prestamo,
            MAX(id) as ultimo

        FROM gestion_cartera

        GROUP BY id_prestamo

    ) g2

    ON g1.id = g2.ultimo

) gc

ON gc.id_prestamo = p.id

WHERE IFNULL(cp.saldo, cp.monto) > 0
AND cp.estado != 'pagado'
AND p.id_estp = 6

GROUP BY p.id

ORDER BY dias_mora DESC

");

// ==========================
// VALIDAR ERROR SQL
// ==========================
if(!$query){

    echo json_encode([
        'success' => false,
        'error' => mysqli_error($conexion)
    ]);

    exit;
}

// ==========================
// RESULTADOS CLIENTES
// ==========================
$clientes = [];

while($row = mysqli_fetch_assoc($query)){

    $clientes[] = $row;
}

// ==========================
// PROMESAS DE PAGO ACTIVAS
// ==========================
$qPromesas = mysqli_query($conexion, "

SELECT COUNT(*) as total

FROM gestion_cartera

WHERE resultado = 'Promesa de pago'
AND estado_seguimiento = 'ACTIVO'

");

$promesas = 0;

if($qPromesas){

    $promesas = mysqli_fetch_assoc($qPromesas)['total'];
}

// ==========================
// GESTIONES DEL DIA
// ==========================
$qHoy = mysqli_query($conexion, "

SELECT COUNT(*) as total

FROM gestion_cartera

WHERE DATE(fecha) = CURDATE()

");

$gestionesHoy = 0;

if($qHoy){

    $gestionesHoy = mysqli_fetch_assoc($qHoy)['total'];
}

// ==========================
// PROXIMAS GESTIONES
// ==========================
$qProximas = mysqli_query($conexion, "

SELECT COUNT(*) as total

FROM gestion_cartera

WHERE proxima_gestion = CURDATE()

");

$proximas = 0;

if($qProximas){

    $proximas = mysqli_fetch_assoc($qProximas)['total'];
}

// ==========================
// CLIENTES CRITICOS
// ==========================
$qCriticos = mysqli_query($conexion, "

SELECT COUNT(DISTINCT p.id) as total

FROM prestamos p

INNER JOIN cuotas_prestamo cp
ON cp.id_prestamo = p.id

WHERE cp.fecha_pago < CURDATE()
AND IFNULL(cp.saldo, cp.monto) > 0
AND DATEDIFF(CURDATE(), cp.fecha_pago) >= 30
AND p.id_estp = 6

");

$criticos = 0;

if($qCriticos){

    $criticos = mysqli_fetch_assoc($qCriticos)['total'];
}

// ==========================
// RESPUESTA JSON
// ==========================
echo json_encode([

    'success' => true,

    'clientes' => $clientes,

    'indicadores' => [

        'promesas' => $promesas,

        'gestiones_hoy' => $gestionesHoy,

        'proximas_gestiones' => $proximas,

        'criticos' => $criticos

    ]

]);