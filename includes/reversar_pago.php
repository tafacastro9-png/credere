<?php
require_once("db.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_pago = intval($_POST['id_pago'] ?? 0);

    if ($id_pago <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID de pago inválido']);
        exit;
    }

    // 1. TRAER EL PAGO
    $qPago = mysqli_query($conexion, "SELECT * FROM pagos_cuotas WHERE id = $id_pago");
    if (!$qPago || mysqli_num_rows($qPago) == 0) {
        echo json_encode(['success' => false, 'message' => 'Pago no encontrado']);
        exit;
    }

    $pago = mysqli_fetch_assoc($qPago);
    $id_cuota = $pago['id_cuota'];
    $valor_pago = floatval($pago['valor']);

    // 2. TRAER CUOTA
    $qCuota = mysqli_query($conexion, "SELECT * FROM cuotas_prestamo WHERE id = $id_cuota");
    if (!$qCuota || mysqli_num_rows($qCuota) == 0) {
        echo json_encode(['success' => false, 'message' => 'Cuota no encontrada']);
        exit;
    }

    $cuota = mysqli_fetch_assoc($qCuota);
    $id_prestamo = $cuota['id_prestamo'];
    $monto_cuota = floatval($cuota['monto']);

    // 3. ELIMINAR EL PAGO
    mysqli_query($conexion, "DELETE FROM pagos_cuotas WHERE id = $id_pago");

    // 4. RECALCULAR TOTAL PAGADO REAL (Después de borrar)
    $qSum = mysqli_query($conexion, "SELECT SUM(valor) as total FROM pagos_cuotas WHERE id_cuota = $id_cuota");
    $totalPagadoActual = floatval(mysqli_fetch_assoc($qSum)['total'] ?? 0);
    
    // El nuevo saldo es el monto original menos lo que queda pagado
    $nuevo_saldo = $monto_cuota - $totalPagadoActual;

    // 5. DETERMINAR EL ESTADO CORRECTO
    $hoy = date("Y-m-d");
    $fecha_vencimiento = $cuota['fecha_pago'];

    if ($nuevo_saldo <= 0) {
        $estado = 'pagado';
        $fecha_pagado_sql = "NOW()";
    } else {
        // SI EL SALDO ES MAYOR A 0, REVISAMOS SI YA SE VENCIÓ
        if ($fecha_vencimiento < $hoy) {
            $estado = 'mora';
        } else {
            $estado = 'pendiente';
        }
        $fecha_pagado_sql = "NULL";
    }

    // 6. ACTUALIZAR CUOTA CON EL NUEVO SALDO Y ESTADO
    $updateCuota = "UPDATE cuotas_prestamo SET 
                    pagado = '$totalPagadoActual',
                    saldo = '$nuevo_saldo',
                    estado = '$estado',
                    fecha_pagado = $fecha_pagado_sql 
                    WHERE id = $id_cuota";
    
    mysqli_query($conexion, $updateCuota);

    // 7. REGISTRAR EL MOVIMIENTO EN CAJA (COMO EGRESO)
    mysqli_query($conexion, "INSERT INTO movimientos_caja (tipo, concepto, valor, origen, referencia_id, fecha) 
                             VALUES ('EGRESO', 'REVERSO PAGO CUOTA #$id_cuota', '$valor_pago', 'cuota', '$id_cuota', NOW())");

    // 8. RECALCULAR ESTADO DEL PRÉSTAMO
    // 8. VALIDAR ESTADO REAL DEL PRÉSTAMO
$qPendientes = mysqli_query($conexion, "
    SELECT COUNT(*) as total
    FROM cuotas_prestamo
    WHERE id_prestamo = $id_prestamo
    AND estado != 'pagado'
");

$pendientes = intval(mysqli_fetch_assoc($qPendientes)['total']);

if($pendientes > 0){

    // SI AÚN HAY CUOTAS PENDIENTES
    mysqli_query($conexion, "
        UPDATE prestamos 
        SET id_estp = 6 
        WHERE id = $id_prestamo
    ");

}else{

    // SI TODO QUEDÓ PAGADO
    mysqli_query($conexion, "
        UPDATE prestamos 
        SET id_estp = 5 
        WHERE id = $id_prestamo
    ");

}

    echo json_encode([
        'success' => true, 
        'message' => 'Pago reversado. La cuota ahora está en estado: ' . strtoupper($estado)
    ]);
}
?>