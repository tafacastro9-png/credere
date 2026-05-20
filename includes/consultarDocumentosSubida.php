<?php

require_once("db.php");

$idPrestamo = intval($_GET['id']);

$response = [

    "credito" => "",
    "identidad" => "",
    "otros" => ""

];

// =====================================
// FUNCIÓN OBTENER ÚLTIMO ESTADO
// =====================================

function obtenerUltimoEstado(
    $conexion,
    $idPrestamo,
    $tipo
){

    $query = mysqli_query($conexion, "

        SELECT estado

        FROM documentos_prestamo

        WHERE
            id_prestamo = $idPrestamo
            AND tipo_documento = '$tipo'

        ORDER BY id DESC

        LIMIT 1

    ");

    if(mysqli_num_rows($query) > 0){

        $data = mysqli_fetch_assoc($query);

        return $data['estado'];
    }

    return "";
}

// =====================================
// ESTADOS
// =====================================

$response['credito'] = obtenerUltimoEstado(
    $conexion,
    $idPrestamo,
    'Documentos del Crédito'
);

$response['identidad'] = obtenerUltimoEstado(
    $conexion,
    $idPrestamo,
    'Documentos de Identidad'
);

$response['otros'] = obtenerUltimoEstado(
    $conexion,
    $idPrestamo,
    'Otros'
);

echo json_encode($response);

?>