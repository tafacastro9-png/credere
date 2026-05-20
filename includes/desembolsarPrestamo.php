<?php
require_once("../includes/db.php");
session_start();

header('Content-Type: application/json');

// 🔴 VALIDAR DATOS (AHORA VIENEN POR POST)
if(isset($_POST['id']) && isset($_POST['fecha']) && isset($_POST['medio'])){

    $id = intval($_POST['id']);
    $fecha = $_POST['fecha'];
    $usuario = $_SESSION['id'] ?? 0;

    $medio = strtoupper(trim($_POST['medio']));
    $banco = $_POST['banco'] ?? null;
    $cuenta = $_POST['cuenta'] ?? null;

    // ============================
    // 🔴 VALIDACIONES
    // ============================

    if(empty($medio)){
        echo json_encode([
            "status" => "error",
            "msg" => "Debe seleccionar el medio de desembolso"
        ]);
        exit;
    }

    if($medio === 'TRANSFERENCIA'){
        if(empty($banco) || empty($cuenta)){
            echo json_encode([
                "status" => "error",
                "msg" => "Debe ingresar banco y número de cuenta"
            ]);
            exit;
        }

        // Validar que la cuenta sea numérica
        if(!preg_match('/^[0-9]+$/', $cuenta)){
            echo json_encode([
                "status" => "error",
                "msg" => "El número de cuenta debe ser numérico"
            ]);
            exit;
        }
    } else {
        // Si es efectivo, limpiamos estos campos
        $banco = null;
        $cuenta = null;
    }

    // 🔹 Convertir fecha
    $fechaFormateada = date("Y-m-d H:i:s", strtotime($fecha));

    // ============================
    // 🔹 TRAER MONTO DEL PRÉSTAMO
    // ============================
    $stmtMonto = $conexion->prepare("
        SELECT monto_prestado 
        FROM prestamos 
        WHERE id = ?
    ");
    $stmtMonto->bind_param("i", $id);
    $stmtMonto->execute();
    $resultMonto = $stmtMonto->get_result();

    if($resultMonto->num_rows === 0){
        echo json_encode([
            "status" => "error",
            "msg" => "Préstamo no encontrado"
        ]);
        exit;
    }

    $rowMonto = $resultMonto->fetch_assoc();
    $monto = $rowMonto['monto_prestado'];

    // ============================
    // 🔥 VALIDAR CAJA SOLO EFECTIVO
    // ============================
    if($medio === 'EFECTIVO'){

        $qCaja = mysqli_query($conexion,"
            SELECT IFNULL(SUM(
                CASE 
                    WHEN tipo='INGRESO' THEN valor
                    ELSE -valor
                END
            ),0) as total
            FROM movimientos_caja
        ");

        if(!$qCaja){
            echo json_encode([
                "status" => "error",
                "msg" => mysqli_error($conexion)
            ]);
            exit;
        }

        $rowCaja = mysqli_fetch_assoc($qCaja);
        $caja = $rowCaja['total'];

        if($monto > $caja){
            echo json_encode([
                "status" => "error",
                "msg" => "No hay dinero en caja para desembolsar"
            ]);
            exit;
        }
    }

    // ============================
    // 🔹 ACTUALIZAR PRÉSTAMO
    // ============================
    $stmt = $conexion->prepare("
        UPDATE prestamos 
        SET 
            id_estp = 6,
            fecha_desembolso = ?,
            usuario_desembolso = ?,
            medio_desembolso = ?,
            banco_desembolso = ?,
            cuenta_desembolso = ?
        WHERE id = ?
        AND id_estp = 3
    ");

    if(!$stmt){
        echo json_encode([
            "status" => "error",
            "msg" => $conexion->error
        ]);
        exit;
    }

    $stmt->bind_param(
        "sisssi",
        $fechaFormateada,
        $usuario,
        $medio,
        $banco,
        $cuenta,
        $id
    );

    if($stmt->execute()){

        // ============================
        // 💸 REGISTRO EN CAJA
        // ============================
        $concepto = ($medio === 'EFECTIVO') 
            ? 'DESEMBOLSO PRESTAMO - EFECTIVO'
            : 'DESEMBOLSO PRESTAMO - TRANSFERENCIA';

        mysqli_query($conexion,"
            INSERT INTO movimientos_caja
            (tipo,concepto,valor,origen,referencia_id,fecha)
            VALUES(
                'EGRESO',
                '$concepto',
                '$monto',
                'prestamo',
                '$id',
                NOW()
            )
        ");

        // ============================
        // 💼 COMISIÓN
        // ============================
        $comision = $monto * 0.20 * 0.20;

        mysqli_query($conexion,"
            INSERT INTO movimientos_caja
            (tipo,concepto,valor,origen,referencia_id,fecha)
            VALUES(
                'EGRESO',
                'PAGO COMISION',
                '$comision',
                'prestamo',
                '$id',
                NOW()
            )
        ");

        // ============================
        // 🛡 SEGURO
        // ============================
        $seguro = $monto * 0.01;

        mysqli_query($conexion,"
            INSERT INTO movimientos_caja
            (tipo,concepto,valor,origen,referencia_id,fecha)
            VALUES(
                'EGRESO',
                'PAGO SEGURO',
                '$seguro',
                'prestamo',
                '$id',
                NOW()
            )
        ");

        echo json_encode([
            "status" => "ok"
        ]);
        exit;

    } else {
        echo json_encode([
            "status" => "error",
            "msg" => "Error al desembolsar"
        ]);
        exit;
    }

}else{
    echo json_encode([
        "status" => "error",
        "msg" => "Datos incompletos"
    ]);
}
?>