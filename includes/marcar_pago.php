<?php
require_once("db.php");

header('Content-Type: application/json');

file_put_contents(
    'debug_marcar_pago.txt',
    date('Y-m-d H:i:s') . " - SE EJECUTO marcar_pago.php\n",
    FILE_APPEND
);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
    exit;
}

// ============================
// 🔹 DATOS
// ============================
$id     = intval($_POST['id'] ?? 0);
$valor  = floatval($_POST['valor'] ?? 0);
$medio  = trim($_POST['medio'] ?? '');
$banco  = trim($_POST['banco'] ?? '');
$cuenta = trim($_POST['cuenta'] ?? '');

// ============================
// 🔴 VALIDACIONES
// ============================
if ($id <= 0 || $valor <= 0 || empty($medio)) {
    echo json_encode([
        'success' => false,
        'message' => 'Datos inválidos'
    ]);
    exit;
}

// ============================
// 🔹 TRAER CUOTA
// ============================
$qCuota = mysqli_query($conexion, "
    SELECT * 
    FROM cuotas_prestamo 
    WHERE id = $id
");

if (!$qCuota || mysqli_num_rows($qCuota) == 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Cuota no encontrada'
    ]);
    exit;
}

$cuota = mysqli_fetch_assoc($qCuota);

$id_prestamo = $cuota['id_prestamo'];
$monto       = floatval($cuota['monto']);
$saldo_actual = isset($cuota['saldo']) ? floatval($cuota['saldo']) : $monto;

// ============================
// 🚫 VALIDAR SOBRE PAGO
// ============================
if ($valor > $saldo_actual) {
    echo json_encode([
        'success' => false,
        'message' => 'El pago no puede ser mayor al saldo pendiente'
    ]);
    exit;
}

// ============================
// 🔥 CALCULAR NUEVO SALDO
// ============================
$nuevo_saldo = $saldo_actual - $valor;

// ============================
// 🔥 ESTADO
// ============================
$hoy = date("Y-m-d");

if ($nuevo_saldo <= 0) {
    $estado = 'pagado';
    $nuevo_saldo = 0;
} else {
    $estado = ($cuota['fecha_pago'] < $hoy) ? 'mora' : 'pendiente';
}

// ============================
// 🔹 ACTUALIZAR CUOTA
// ============================
$update = mysqli_query($conexion, "
    UPDATE cuotas_prestamo 
    SET 
        saldo = $nuevo_saldo,
        estado = '$estado',
        fecha_pagado = NOW()
    WHERE id = $id
");

if (!$update) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al actualizar cuota'
    ]);
    exit;
}

// ============================
// 💾 HISTORIAL PAGOS
// ============================
mysqli_query($conexion, "
    INSERT INTO pagos_cuotas
    (id_cuota, valor, medio, banco, cuenta, fecha)
    VALUES(
        $id,
        $valor,
        '$medio',
        " . ($banco ? "'$banco'" : "NULL") . ",
        " . ($cuenta ? "'$cuenta'" : "NULL") . ",
        NOW()
    )
");

// ============================
// 💵 CAJA
// ============================
mysqli_query($conexion, "
    INSERT INTO movimientos_caja
    (tipo, concepto, valor, origen, referencia_id, fecha)
    VALUES(
        'INGRESO',
        'PAGO CUOTA #$id',
        $valor,
        'cuota',
        $id,
        NOW()
    )
");

// ============================
// 🔹 VALIDAR PRÉSTAMO FINALIZADO
// ============================
$consultaPendientes = mysqli_query($conexion, "
    SELECT COUNT(*) AS total 
    FROM cuotas_prestamo 
    WHERE id_prestamo = $id_prestamo 
    AND estado != 'pagado'
");

$rowPendientes = mysqli_fetch_assoc($consultaPendientes);
$restantes = intval($rowPendientes['total']);

if ($restantes == 0) {
    mysqli_query($conexion, "
        UPDATE prestamos 
        SET id_estp = 5 
        WHERE id = $id_prestamo
    ");
}

// ============================
// ✅ MENSAJE
// ============================
if ($estado == 'pagado') {
    $mensaje = 'Cuota pagada completamente ✅';
} elseif ($estado == 'mora') {
    $mensaje = 'Pago parcial registrado ⚠️ (sigue en mora)';
} else {
    $mensaje = 'Pago parcial registrado';
}

// ============================
// ✅ RESPUESTA
// ============================
echo json_encode([
    'success' => true,
    'estado'  => $estado,
    'saldo'   => $nuevo_saldo,
    'message' => $mensaje
]);