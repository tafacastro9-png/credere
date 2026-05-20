<?php
require_once("db.php");

header('Content-Type: application/json');

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'ID inválido'
    ]);
    exit;
}

$query = mysqli_query($conexion, "
SELECT id, id_cuota, valor, medio, banco, cuenta, fecha
FROM pagos_cuotas
WHERE id_cuota = $id
ORDER BY fecha DESC
");

$pagos = [];

while ($row = mysqli_fetch_assoc($query)) {
    $pagos[] = $row;
}

echo json_encode([
    'success' => true,
    'pagos' => $pagos
]);