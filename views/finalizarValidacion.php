<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../includes/db.php");

session_start();

header('Content-Type: application/json');

// ======================================
// VALIDAR DATOS
// ======================================

if(!isset($_POST['id_prestamo'])){

    echo json_encode([

        "status" => "error",

        "mensaje" => "Préstamo no válido"

    ]);

    exit;
}

$idPrestamo = intval($_POST['id_prestamo']);

// ======================================
// RECHAZADOS
// ======================================

$queryRechazados = mysqli_query($conexion, "

    SELECT COUNT(*) total

    FROM (

        SELECT
            MAX(id) id

        FROM documentos_prestamo

        WHERE id_prestamo = $idPrestamo

        GROUP BY tipo_documento

    ) ultimos

    INNER JOIN documentos_prestamo d
        ON d.id = ultimos.id

    WHERE d.estado = 'Rechazado'

");

$rechazados = mysqli_fetch_assoc(
    $queryRechazados
)['total'];


// ======================================
// PENDIENTES
// ======================================

$queryPendientes = mysqli_query($conexion, "

    SELECT COUNT(*) total

    FROM (

        SELECT MAX(id) id

        FROM documentos_prestamo

        WHERE id_prestamo = $idPrestamo

        GROUP BY tipo_documento

    ) ultimos

    INNER JOIN documentos_prestamo d
        ON d.id = ultimos.id

    WHERE
        d.estado IS NULL
        OR d.estado = ''
        OR d.estado = 'Pendiente'

");

$pendientes = mysqli_fetch_assoc(
    $queryPendientes
)['total'];


// ======================================
// CASO RECHAZADOS
// ======================================

if($rechazados > 0){

    mysqli_query($conexion, "

        UPDATE prestamos

        SET id_estp = 1

        WHERE id = $idPrestamo

    ");

    echo json_encode([

        "status" => "rechazado",

        "mensaje" =>

        "El crédito se devuelve a estado Radicado para que se vuelvan a cargar los documentos rechazados."

    ]);

    exit;
}


// ======================================
// CASO PENDIENTES
// ======================================

if($pendientes > 0){

    echo json_encode([

        "status" => "pendiente",

        "mensaje" =>

        "El crédito sigue en estado Radicado hasta que se validen todos los documentos."

    ]);

    exit;
}


// ======================================
// TODOS APROBADOS
// ======================================

mysqli_query($conexion, "

    UPDATE prestamos

    SET id_estp = 3

    WHERE id = $idPrestamo

");

echo json_encode([

    "status" => "aprobado",

    "mensaje" =>

    "Los documentos fueron aprobados correctamente y el crédito pasa a estado Pendiente de desembolso."

]);

exit;

?>