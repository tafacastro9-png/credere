<?php
require_once("db.php");

header('Content-Type: application/json');

// ============================
// 🔒 SOLO POST
// ============================
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

// 👉 IMPORTANTE: valor por defecto para botón rápido
$medio  = trim($_POST['medio'] ?? 'EFECTIVO');
$banco  = trim($_POST['banco'] ?? '');
$cuenta = trim($_POST['cuenta'] ?? '');

// ============================
// 🔴 VALIDACIONES
// ============================
if ($id <= 0 || $valor <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Datos inválidos'
    ]);
    exit;
}

// ============================
// 🔹 TRAER CUOTA Y RECALCULAR SALDO REAL
// ============================
$qCuota = mysqli_query($conexion, "SELECT * FROM cuotas_prestamo WHERE id = $id");

if (!$qCuota || mysqli_num_rows($qCuota) == 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Cuota no encontrada'
    ]);
    exit;
}

$cuota = mysqli_fetch_assoc($qCuota);

// --- AJUSTE: Cálculo de saldo basado en abonos reales ---
$qAbonos = mysqli_query($conexion, "SELECT SUM(valor) as total FROM pagos_cuotas WHERE id_cuota = $id");
$total_abonado = mysqli_fetch_assoc($qAbonos)['total'] ?? 0;

$id_prestamo  = $cuota['id_prestamo'];
$monto_cuota  = floatval($cuota['monto']);
$saldo_actual = $monto_cuota - $total_abonado; // Saldo real según historial

// ============================
// 🚫 VALIDAR SOBRE PAGO (con margen de 1 peso por decimales)
// ============================
if ($valor > ($saldo_actual + 1)) {
    echo json_encode([
        'success' => false,
        'message' => 'El pago ($'.number_format($valor).') no puede ser mayor al saldo pendiente ($'.number_format($saldo_actual).')'
    ]);
    exit;
}

// ============================
// 🔥 CALCULAR NUEVO SALDO
// ============================
$nuevo_saldo = $saldo_actual - $valor;
if ($nuevo_saldo < 0) $nuevo_saldo = 0;

// ============================
// 🔥 ESTADO
// ============================
$hoy = date("Y-m-d");

if ($nuevo_saldo <= 0) {
    $estado = 'pagado';
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
$insertPago = mysqli_query($conexion, "
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

if (!$insertPago) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al guardar historial de pago'
    ]);
    exit;
}

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

    // ============================
    // FINALIZAR PRÉSTAMO
    // ============================
    mysqli_query($conexion, "
        UPDATE prestamos 
        SET id_estp = 5 
        WHERE id = $id_prestamo
    ");

    // ============================
    // CERRAR SEGUIMIENTO CRM
    // ============================
    mysqli_query($conexion, "
        UPDATE gestion_cartera
        SET
            seguimiento_activo = 0,
            estado_seguimiento = 'CERRADO'
        WHERE id_prestamo = $id_prestamo
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
// ✅ RESPUESTA FINAL
// ============================
echo json_encode([
    'success' => true,
    'estado'  => $estado,
    'saldo'   => $nuevo_saldo,
    'message' => $mensaje
]);