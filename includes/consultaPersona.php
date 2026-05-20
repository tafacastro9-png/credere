<?php
header('Content-Type: application/json');

require_once "db.php";

$term  = isset($_GET['term']) ? trim($_GET['term']) : '';
$tipo  = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$termLike = "%$term%";

$resultados = [];

if ($tipo === 'cliente') {

    $sql = "
        SELECT 
            id,
            folioClient,
            nombreClient,
            apellidoClient
        FROM clientes
        WHERE nombreClient LIKE ?
           OR apellidoClient LIKE ?
           OR folioClient LIKE ?
        LIMIT 10
    ";

    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        echo json_encode([]);
        exit;
    }

    $stmt->bind_param("sss", $termLike, $termLike, $termLike);
}

/* =======================
   A V A L E S
======================= */
elseif ($tipo === 'aval') {

    $tipoReferencia = isset($_GET['tipoReferencia']) ? intval($_GET['tipoReferencia']) : 0;

    $sql = "
        SELECT 
            id,
            folioAval,
            nombreAval,
            apellidoAval
        FROM avales
        WHERE id_tiporeferencia = ?
          AND (
                nombreAval LIKE ?
             OR apellidoAval LIKE ?
             OR folioAval LIKE ?
          )
        LIMIT 10
    ";

    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        echo json_encode([]);
        exit;
    }

    $stmt->bind_param("isss", $tipoReferencia, $termLike, $termLike, $termLike);
}

else {
    echo json_encode([]);
    exit;
}

/* =======================
   E J E C U C I Ó N
======================= */
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {

    if ($tipo === 'cliente') {
        $resultados[] = [
            "id"   => $row['id'],
            "text" => $row['folioClient'] . " - " . $row['nombreClient'] . " " . $row['apellidoClient']
        ];
    }

    if ($tipo === 'aval') {
        $resultados[] = [
            "id"   => $row['id'],
            "text" => $row['folioAval'] . " - " . $row['nombreAval'] . " " . $row['apellidoAval']
        ];
    }
}

echo json_encode($resultados);
