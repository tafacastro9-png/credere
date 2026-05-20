<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include "db.php";

date_default_timezone_set('America/Bogota');
$datetime = date("Y-m-d H:i:s");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
    exit;
}

mysqli_begin_transaction($conexion);

try {

    // ============================
    // 🔹 RECIBIR DATOS
    // ============================

    $id_cliente = $_POST['id_cliente'] ?? null;
	$id_usuario_radica = $_SESSION['id_usuario']; // el logueado
    $id_aval = !empty($_POST['id_aval']) ? (int)$_POST['id_aval'] : "NULL";
    $id_avalFamiliar = !empty($_POST['id_avalFamiliar']) ? (int)$_POST['id_avalFamiliar'] : "NULL";
    $id_tp = null;
$tipo = intval($_POST['tipo_proyeccion'] ?? 0);
if ($tipo !== 0 && $tipo !== 1) {
    throw new Exception("Tipo de proyección inválido");
}

    $monto = floatval($_POST['monto_prestado'] ?? 0);
    $fecha_inicio = $_POST['fecha_inicio'] ?? null;
    $fecha_vencimiento = $_POST['fecha_vencimiento'] ?? null;

    $id_estp = 1;

    $total_pagar = floatval($_POST['total_pagar'] ?? 0);
    $num_cuotas = intval($_POST['num_cuotas'] ?? 0);
    $cuota_pago = floatval($_POST['cuota_pago'] ?? 0);
    $frecuencia_pago = $_POST['frecuencia_pago'] ?? null;
    $multa_mora = floatval($_POST['multa_mora'] ?? 0);

    $valor_pagare = floatval($_POST['valor_pagare'] ?? 0);
    $gasto_tramite = floatval($_POST['gasto_tramite'] ?? 0);
    $valor_desembolsado = floatval($_POST['valor_desembolsado'] ?? 0);
	$id_tipo_credito = $_POST['id_tipo_credito'] ?? null;


    // ============================
    // 🔹 CUOTAS JSON
    // ============================

    $cuotas = [];

    if (isset($_POST['cuotas_json'])) {
        $cuotas = json_decode($_POST['cuotas_json'], true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error al decodificar cuotas JSON");
        }
    }

  if (!$id_cliente || $monto <= 0) {
    throw new Exception("Datos incompletos o inválidos.");
}

    // ============================
    // 🔥 OBTENER FACTOR DESDE BD
    // ============================

$factor_valor = floatval($_POST['factor'] ?? 0);


// ============================
// 🔹 GUARDAR TIPO PRESTAMO
// ============================

if ($tipo === 0) {

    $nombre_tipo = $_POST['nombre_tipo'];
    $descripcion = $_POST['descripcion'];
    $tasa_interes = floatval($_POST['tasa_interes']);
    $periodo_gracia = intval($_POST['periodo_gracia']);
    $plazo_meses = intval($_POST['plazo_meses']);
    $id_frp = $_POST['id_frp'];
    $multa_mora_tp = floatval($_POST['multa_mora']);
    $monto_maximo = floatval($_POST['monto_maximo']);

    $plazo_dias = $plazo_meses * 30;

    $tasa_decimal = $tasa_interes / 100;

    $factor = 1 + ($tasa_decimal * ($plazo_meses + $periodo_gracia));

    $sqlTipo = "INSERT INTO tipo_prestamo (
        nombre_tipo,
        descripcion,
        tasa_interes,
        periodo_gracia,
        plazo_dias,
        id_frp,
        multa_mora,
        monto_maximo,
        tipo_proyeccion,
        factor,
        fechaRegistro
    ) VALUES (
        '$nombre_tipo',
        '$descripcion',
        '$tasa_interes',
        '$periodo_gracia',
        '$plazo_dias',
        '$id_frp',
        '$multa_mora_tp',
        '$monto_maximo',
        '0',
        '$factor',
        '$datetime'
    )";

}

if ($tipo === 1) {

    $nombre_tipo = $_POST['nombre_tipo_amort'];
    $descripcion = $_POST['descripcion_amort'];
    $tasa_anual = floatval($_POST['tasa_anual_amor']);
    $tasa_mensual = floatval($_POST['tasa_mensual_amort']);
    $plazo_amort = intval($_POST['plazo_amort']);
    $frecuencia_pago_amort = $_POST['frecuencia_pago_amort'];
    $multa_mora_tp = floatval($_POST['multa_mora_amort']);
    $monto_maximo = floatval($_POST['monto_maximo_amort']);

    $plazo_dias = $plazo_amort * 30;

    $sqlTipo = "INSERT INTO tipo_prestamo (
        nombre_tipo,
        descripcion,
        tasa_interes,
        tasa_mensual,
        plazo_dias,
        id_frp,
        multa_mora,
        monto_maximo,
        tipo_proyeccion,
        fechaRegistro
    ) VALUES (
        '$nombre_tipo',
        '$descripcion',
        '$tasa_anual',
        '$tasa_mensual',
        '$plazo_dias',
        '$frecuencia_pago_amort',
        '$multa_mora_tp',
        '$monto_maximo',
        '1',
        '$datetime'
    )";
}

if (!mysqli_query($conexion, $sqlTipo)) {
    throw new Exception(mysqli_error($conexion));
}

$id_tp = mysqli_insert_id($conexion);

    // ============================
    // 🔹 INSERT PRESTAMO
    // ============================

    $sql = "INSERT INTO prestamos 
(id_cliente,id_usuario_radica ,id_aval, id_avalFamiliar, id_tp, id_tipo_credito, monto_prestado, fecha_inicio ,fecha_vencimiento, id_estp, fechaRegistro)
VALUES 
('$id_cliente', '$id_usuario_radica',$id_aval, $id_avalFamiliar, '$id_tp', '$id_tipo_credito', '$monto', '$fecha_inicio', '$fecha_vencimiento', '$id_estp', '$datetime')
";

    if (!mysqli_query($conexion, $sql)) {
        throw new Exception(mysqli_error($conexion));
    }

    $id_prestamo = mysqli_insert_id($conexion);
	
	
	

    // ============================
    // 🔹 INSERTAR CUOTAS
    // ============================

    if (!empty($cuotas) && is_array($cuotas)) {

        foreach ($cuotas as $cuota) {

            $numero = intval($cuota['numero_cuota']);
            $interes = floatval($cuota['interes']);
            $capital = floatval($cuota['capital']);
            $valor_cuota = floatval($cuota['valor_cuota']);
            $fecha_pago = $cuota['fecha_pago'];

            $sqlCuota = "INSERT INTO cuotas_prestamo
            (id_prestamo, numero_cuota, fecha_pago, monto, interes, capital, estado)
            VALUES
            ('$id_prestamo', '$numero', '$fecha_pago', '$valor_cuota', '$interes', '$capital', 'Pendiente')";

            if (!mysqli_query($conexion, $sqlCuota)) {
                throw new Exception("Error al guardar cuotas: " . mysqli_error($conexion));
            }
        }
    }

// ============================
// 🔹 GENERAR FOLIO POR TIPO
// ============================

// 1️⃣ Definir prefijo usando la variable correcta
switch ($id_tipo_credito) {
    case 1:
        $prefijo = "VHO";
        break;
    case 2:
        $prefijo = "MTO";
        break;
    case 3:
        $prefijo = "HPT";
        break;
    default:
        $prefijo = "GEN";
}

// 2️⃣ Buscar último folio de ese tipo
$queryFolio = mysqli_query($conexion, "
    SELECT folioPrest 
    FROM prestamos 
    WHERE folioPrest LIKE '$prefijo%' 
    ORDER BY id DESC 
    LIMIT 1
");

if (mysqli_num_rows($queryFolio) > 0) {

    $row = mysqli_fetch_assoc($queryFolio);

    // Extraer parte numérica (después del prefijo)
    $numero = intval(substr($row['folioPrest'], 3));
    $nuevoNumero = $numero + 1;

} else {
    $nuevoNumero = 1;
}

// 3️⃣ Formatear con ceros
$folio = $prefijo . str_pad($nuevoNumero, 3, '0', STR_PAD_LEFT);

// 4️⃣ Actualizar préstamo
if (!mysqli_query($conexion, 
    "UPDATE prestamos SET folioPrest = '$folio' WHERE id = $id_prestamo"
)) {
    throw new Exception(mysqli_error($conexion));
}
    // ============================
    // 🔹 INSERT DETALLE
    // ============================

    $sql_detalle = "INSERT INTO detalle_prestamo 
    (id_prestamo, total_pagar, num_cuotas, monto_cuota, frecuencia_pago, multa_mora, factor_valor, valor_pagare, gasto_tramite, valor_desembolsado)
    VALUES 
    ('$id_prestamo', '$total_pagar', '$num_cuotas', '$cuota_pago', '$frecuencia_pago', '$multa_mora', '$factor_valor', '$valor_pagare', '$gasto_tramite', '$valor_desembolsado')";

    if (!mysqli_query($conexion, $sql_detalle)) {
        throw new Exception(mysqli_error($conexion));
    }

    // ============================
    // 🔥 CONFIRMAR TRANSACCIÓN
    // ============================

    mysqli_commit($conexion);

    echo json_encode([
        "success" => true,
        "message" => "Préstamo registrado correctamente"
    ]);
    exit;

} catch (Exception $e) {

    mysqli_rollback($conexion);

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
    exit;
}
