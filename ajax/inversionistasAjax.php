<?php  
include("../includes/db.php");

function registrarCaja($conexion, $tipo, $concepto, $origen, $ref, $valor){

    mysqli_query($conexion,"
        INSERT INTO movimientos_caja 
        (tipo, concepto, origen, referencia_id, valor, fecha)
        VALUES (
            '$tipo',
            '$concepto',
            '$origen',
            '$ref',
            '$valor',
            NOW()
        )
    ");
}

$accion = $_POST['accion'] ?? '';



function f($n){
    return number_format($n ?? 0,0,',','.');
}

// 🔹 FUNCION GLOBAL (evita redefinir)
function limpiarTexto($texto){
    $texto = strtolower($texto);
    $texto = preg_replace('/[^a-z0-9]/', '_', $texto);
    return trim($texto, '_');
}

// ============================
// 🔹 LISTAR TABLA
// ============================
if($accion == "listar_tabla"){

    $sql = "
    SELECT 
        i.*,
        IFNULL(SUM(
            CASE 
                WHEN UPPER(m.tipo)='APORTE' THEN m.valor
                ELSE -m.valor
            END
        ),0) as saldo
    FROM inversionistas i
    LEFT JOIN movimientos_inversionista m 
        ON m.id_inversionista = i.id
    GROUP BY i.id
    ";

    $res = mysqli_query($conexion,$sql);

    if(!$res){
        die("Error SQL: " . mysqli_error($conexion));
    }

    while($row = mysqli_fetch_assoc($res)){

        echo "<tr>
            <td>{$row['nombre']}</td>
            <td>{$row['documento']}</td>
            <td>$ " . f($row['saldo']) . "</td>
<td class='text-center'>

    <div class='d-flex justify-content-center gap-1'>

        <a href='ver_inversionista.php?id={$row['id']}' 
           class='btn btn-primary'
           style='padding:4px 6px; font-size:12px; width:28px; height:28px; display:flex; align-items:center; justify-content:center;'
           title='Ver hoja de vida'>
           <i class='lni lni-eye' style='font-size:14px;'></i>
        </a>

        <a href='editar_inversionista.php?id={$row['id']}' 
           class='btn btn-warning'
           style='padding:4px 6px; font-size:12px; width:28px; height:28px; display:flex; align-items:center; justify-content:center;'
           title='Editar'>
           <i class='lni lni-pencil' style='font-size:14px;'></i>
        </a>

        <a href='inversionista_detalle.php?id={$row['id']}' 
           class='btn btn-dark'
           style='padding:4px 6px; font-size:12px; width:28px; height:28px; display:flex; align-items:center; justify-content:center;'
           title='Gestionar'>
           <i class='lni lni-cog' style='font-size:14px;'></i>
        </a>

    </div>

</td>
        </tr>";
    }
}

// ============================
// 🔹 MOVIMIENTOS (🔥 FALTABA)
// ============================
if($accion == "movimientos"){

    $id = $_POST['id'] ?? 0;

$q = mysqli_query($conexion,"
  SELECT
    mi.id,
    mi.fecha,
    mi.tipo,
    mi.valor,
    mi.interes,
    mi.aporte_id, 
    mi.medio_pago,
    mi.banco,
    mi.numero_cuenta,
    mi.tasa,
    mi.meses,
    mi.estado,
    fp.frecuencia
  FROM movimientos_inversionista mi
  LEFT JOIN frecuencia_pago fp
  ON mi.frecuencia_pago_id = fp.id
WHERE mi.id_inversionista = '$id'

ORDER BY mi.fecha ASC, mi.id ASC

");

$saldo = 0;

while($m = mysqli_fetch_assoc($q)){

    // 🔹 Ocultar movimientos hijos (ya lo tenías)
    if($m['tipo'] != 'APORTE' && !empty($m['aporte_id'])){
        continue;
    }



    // 🔥 OCULTAR RETIROS DE INTERÉS (ANTES DE TODO)


if($m['tipo'] == 'APORTE'){

    $saldo += $m['valor'];
    $tipo = "<span class='text-success'>⬆️ APORTE</span>";

}
elseif($m['tipo'] == 'RETIRO'){

    // 🔥 SOLO RESTAR CAPITAL REAL
    if($m['medio_pago'] == 'LIQUIDACION' || empty($m['aporte_id'])){

        $saldo -= $m['valor'];

// 🔥 CLAVE: nunca permitir negativo
if($saldo < 0){
    $saldo = 0;
}

        if($m['medio_pago'] == 'LIQUIDACION'){
            $tipo = "<span class='text-danger'>💼 LIQUIDACIÓN</span>";
        } else {
            $tipo = "<span class='text-danger'>⬇️ RETIRO CAPITAL</span>";
        }

    } else {

        // 🔹 INTERÉS → NO afecta saldo
        $tipo = "<span class='text-warning'>💸 RETIRO INTERÉS</span>";
    }
}


// 🔹 CALCULAR PRÓXIMA FECHA
$fechaProxima = '-';
$colorFecha = '';

if($m['tipo'] == 'APORTE'){

    $fechaAporte = new DateTime($m['fecha']);
    $fechaPago = clone $fechaAporte;

    $meses = intval($m['meses']);

    switch(strtolower($m['frecuencia'])){
        case 'mensual':
            $fechaPago->modify("+$meses month");
        break;
        case 'quincenal':
            $fechaPago->modify("+" . (15 * $meses) . " days");
        break;
        case 'semanal':
            $fechaPago->modify("+" . (7 * $meses) . " days");
        break;
        default:
            $fechaPago->modify("+$meses month");
    }

    $fechaProxima = $fechaPago->format('d-m-Y');

    // 🔥 COLOR
    $hoy = new DateTime();

    if($hoy >= $fechaPago){
        $colorFecha = "style='color:green; font-weight:bold;'";
    } else {
        $colorFecha = "style='color:orange;'";
    }
}


$btnInteres = '';
$btnLiquidar = '';

if($m['tipo'] == 'APORTE'){

    $fechaAporte = new DateTime($m['fecha']);
    $hoy = new DateTime();

    $fechaPago = clone $fechaAporte;

    $meses = intval($m['meses']);

    switch(strtolower($m['frecuencia'])){
        case 'mensual':
            $fechaPago->modify("+$meses month");
        break;
        case 'quincenal':
            $fechaPago->modify("+" . (15 * $meses) . " days");
        break;
        case 'semanal':
            $fechaPago->modify("+" . (7 * $meses) . " days");
        break;
        default:
            $fechaPago->modify("+$meses month");
    }

    // 🔹 validar si ya pagó interés
    $qPagado = mysqli_query($conexion,"
        SELECT COUNT(*) as total
        FROM movimientos_inversionista
        WHERE aporte_id = '{$m['id']}'
        AND tipo='RETIRO'
        AND interes > 0
    ");
    $yaPago = mysqli_fetch_assoc($qPagado)['total'];

    $puedeLiquidar = ($hoy >= $fechaPago);

    if($m['estado'] == 'LIQUIDADO'){

        $btnLiquidar = "<span class='badge bg-success'>✔ Pagado</span>";

    } else {

        if(!$yaPago && $puedeLiquidar){
            $btnInteres = "
                <button class='btn btn-info'
                    style='width:32px;height:32px;border-radius:50%;padding:0;'
                    onclick='liquidarInteres({$m['id']})'>
                    💸
                </button>
            ";
        }

        if($puedeLiquidar){
            $btnLiquidar = "
                <button class='btn btn-warning'
                    style='width:32px;height:32px;border-radius:50%;padding:0;'
                    onclick='liquidar({$m['id']})'>
                    <i class='lni lni-wallet'></i>
                </button>
            ";
        } else {
            $btnLiquidar = "
                <button class='btn btn-secondary'
                    style='width:32px;height:32px;border-radius:50%;padding:0;opacity:0.5;'
                    disabled>
                    ⏳
                </button>
            ";
        }
    }
}




$btnExpandir = '';

if($m['tipo'] == 'APORTE'){
    $btnExpandir = "
        <button 
            class='btn btn-outline-primary btn-sm'
            style='width:28px; height:28px; padding:0; display:flex; align-items:center; justify-content:center; border-radius:6px;'
            onclick='verMovimientosAporte(this, {$m['id']})'
            title='Ver detalle'
        >
            +
        </button>
    ";
}
echo "<tr>

    <!-- 🔥 BOTÓN AQUÍ -->
    <td class='text-center'>
        $btnExpandir
    </td>

    <td>{$m['fecha']}</td>
    <td>$tipo</td>
    <td>$ ".f($m['valor'])."</td>
    <td><b>$ ".f($saldo)."</b></td>

    <td>{$m['medio_pago']}</td>
    <td>".($m['banco'] ? $m['banco'] : '-')."</td>
    <td>".($m['numero_cuenta'] ? $m['numero_cuenta'] : '-')."</td>
    <td>".($m['tasa'] > 0 ? number_format($m['tasa'],2) : '-')."</td>
    <td>".($m['meses'] > 0 ? $m['meses'] : '-')."</td>
    <td>{$m['frecuencia']}</td>
	<td $colorFecha>$fechaProxima</td>

    <!-- 🔥 SOLO ESTE AQUÍ -->
    <td class='text-center'>
    <div class='d-flex justify-content-center gap-1'>
        $btnInteres
        $btnLiquidar
    </div>
</td>

</tr>";



    }

    exit;
}

// ============================
// 🔹 SALDO
// ============================
if($accion == "saldo"){

    $id = $_POST['id'] ?? 0;

    $q = mysqli_query($conexion,"
        SELECT IFNULL(SUM(
            CASE 
                WHEN UPPER(tipo)='APORTE' THEN valor

                -- 🔥 RESTAR RETIROS (manuales y liquidaciones)
                WHEN UPPER(tipo)='RETIRO' 
                AND (
                    aporte_id IS NULL -- 🔹 retiro manual
                    OR medio_pago = 'LIQUIDACION' -- 🔹 liquidación
                )
                THEN -valor

                ELSE 0
            END
        ),0) as total
        FROM movimientos_inversionista
        WHERE id_inversionista='$id'
    ");

    if(!$q){
        echo "0";
        exit;
    }

    $saldo = floatval(mysqli_fetch_assoc($q)['total'] ?? 0);

    // 🔥 CLAVE: evitar negativos SIEMPRE
    $saldo = max(0, $saldo);

    echo number_format($saldo,0,',','.');
    exit;
}
// ============================
// 🔹 GUARDAR MOVIMIENTO
// ============================
if($accion == "movimiento"){

    $id = $_POST['id_inversionista'] ?? 0;
    $valor = floatval($_POST['valor'] ?? 0);
    $tipo = $_POST['tipo'] ?? 'APORTE';

    $medio_pago = $_POST['medio_pago'] ?? '';
    $banco = $_POST['banco'] ?? '';
    $numero_cuenta = $_POST['numero_cuenta'] ?? '';
    $tasa = $_POST['tasa'] ?? 0;
    $meses = $_POST['meses'] ?? 0;
    $frecuencia_pago_id = $_POST['frecuencia_pago_id'] ?: "NULL";

    // 🔹 CALCULAR SALDO
    $qSaldo = mysqli_query($conexion,"
        SELECT IFNULL(SUM(
            CASE 
                WHEN UPPER(tipo)='APORTE' THEN valor

                WHEN UPPER(tipo)='RETIRO' 
                AND (
                    aporte_id IS NULL 
                    OR medio_pago = 'LIQUIDACION'
                )
                THEN -valor

                ELSE 0
            END
        ),0) as total
        FROM movimientos_inversionista
        WHERE id_inversionista='$id'
    ");

    if(!$qSaldo){
        echo "error_sql";
        exit;
    }

    $saldo = floatval(mysqli_fetch_assoc($qSaldo)['total'] ?? 0);

    // 🔹 VALIDAR RETIRO
  if(strtoupper($tipo) == "RETIRO" && $valor > $saldo){

    echo json_encode([
        "status" => "error",
        "msg" => "No tienes capital suficiente para retirar. Debes realizar un retiro por el valor disponible."
    ]);

    exit;
}

    // 🔹 INSERT MOVIMIENTO INVERSIONISTA
    $insert = mysqli_query($conexion,"
        INSERT INTO movimientos_inversionista
        (id_inversionista,tipo,valor,fecha,medio_pago,banco,numero_cuenta,tasa,meses,frecuencia_pago_id)
        VALUES('$id','$tipo','$valor',NOW(),'$medio_pago','$banco','$numero_cuenta','$tasa','$meses',$frecuencia_pago_id)
    ");

    if(!$insert){
        echo "error_insert";
        exit;
    }

    // 🔹 OBTENER ID INSERTADO
    $idInsertado = mysqli_insert_id($conexion);

    // 🔹 SI ES APORTE → ACTUALIZA aporte_id
    if(strtoupper($tipo) == "APORTE"){

        mysqli_query($conexion,"
            UPDATE movimientos_inversionista 
            SET aporte_id = '$idInsertado'
            WHERE id = '$idInsertado'
        ");

        // 🟢 REGISTRAR EN CAJA (INGRESO)
        mysqli_query($conexion,"
            INSERT INTO movimientos_caja
            (tipo, concepto, origen, referencia_id, valor, fecha)
            VALUES (
                'INGRESO',
                'APORTE INVERSIONISTA',
                'inversionista',
                '$id',
                '$valor',
                NOW()
            )
        ");
    }

    // 🔴 SI ES RETIRO → REGISTRAR EGRESO
    if(strtoupper($tipo) == "RETIRO"){

        mysqli_query($conexion,"
            INSERT INTO movimientos_caja
            (tipo, concepto, origen, referencia_id, valor, fecha)
            VALUES (
                'EGRESO',
                'RETIRO MANUAL INVERSIONISTA',
                'inversionista',
                '$id',
                '$valor',
                NOW()
            )
        ");
    }

    echo "ok";
    exit;
}
// ============================
// 🔹 GUARDAR INVERS
// ============================
if($accion == "guardar"){

    $nombre = $_POST['nombre'] ?? '';
    $doc = $_POST['documento'] ?? '';
    $tel = $_POST['telefono'] ?? '';
    $email = $_POST['email'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $barrio = $_POST['barrio'] ?? '';
    $tipo_id = $_POST['tipo_identificacion_id'] ?? 0;
    $ciudad_id = $_POST['ciudad_id'] ?? 0;

    if(!$nombre || !$doc || !$tel || !$email || !$direccion || !$barrio || !$tipo_id || !$ciudad_id){
        echo "error_campos";
        exit;
    }

    mysqli_query($conexion,"
        INSERT INTO inversionistas
        (nombre,documento,telefono,email,direccion,barrio,tipo_identificacion_id,ciudad_id)
        VALUES
        ('$nombre','$doc','$tel','$email','$direccion','$barrio','$tipo_id','$ciudad_id')
    ");

    $id_inversionista = mysqli_insert_id($conexion);

    // 🔹 RUTA
    $base = "../documentos_inversionistas/";
    $ruta_final = $base.date("Y/m/d/");

    if(!is_dir($ruta_final)){
        mkdir($ruta_final, 0777, true);
    }

    // 🔹 FUNCION GUARDAR
    function guardarArchivo($file, $tipo, $id_inversionista, $ruta_final, $conexion, $doc, $nombre){

        if(isset($_FILES[$file]) && $_FILES[$file]['error'] == 0){

            $ruta_tipo = $ruta_final . $tipo . "/";
            if(!is_dir($ruta_tipo)){
                mkdir($ruta_tipo, 0777, true);
            }

            $ext = pathinfo($_FILES[$file]['name'], PATHINFO_EXTENSION);

            $nuevo_nombre = limpiarTexto($doc) . "_" . limpiarTexto($nombre) . "_" . time() . "." . $ext;
            $ruta = $ruta_tipo . $nuevo_nombre;

            if(move_uploaded_file($_FILES[$file]['tmp_name'], $ruta)){
                mysqli_query($conexion,"
                    INSERT INTO documentos_inversionista
(inversionista_id,tipo_documento,ruta,fecha)
VALUES('$id_inversionista','$tipo','$ruta',NOW())
                ");
            }
        }
    }

    guardarArchivo("doc_identidad", "IDENTIDAD", $id_inversionista, $ruta_final, $conexion, $doc, $nombre);
    guardarArchivo("doc_direccion", "DIRECCION", $id_inversionista, $ruta_final, $conexion, $doc, $nombre);

    echo "ok";
}

// ============================
// 🔹 EDITAR INVERS + DOCUMENTOS (CON HISTORIAL)
// ============================
if($accion == "editar"){

    $id = $_POST['id'] ?? 0;
    $nombre = $_POST['nombre'] ?? '';
    $doc = $_POST['documento'] ?? '';
    $tel = $_POST['telefono'] ?? '';
    $email = $_POST['email'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $barrio = $_POST['barrio'] ?? '';
    $tipo_id = $_POST['tipo_identificacion_id'] ?? 0;
    $ciudad_id = $_POST['ciudad_id'] ?? 0;

    if(!$id){
        echo "error";
        exit;
    }

    mysqli_query($conexion,"
        UPDATE inversionistas SET
        nombre='$nombre',
        documento='$doc',
        telefono='$tel',
        email='$email',
        direccion='$direccion',
        barrio='$barrio',
        tipo_identificacion_id='$tipo_id',
        ciudad_id='$ciudad_id'
        WHERE id='$id'
    ");

    // 🔹 RUTA
    $base = "../documentos_inversionistas/";
    $ruta_final = $base.date("Y/m/d/");

    if(!is_dir($ruta_final)){
        mkdir($ruta_final, 0777, true);
    }

    // 🔹 FUNCION ACTUALIZAR (CON HISTORIAL)
    function actualizarArchivo($file, $tipo, $id, $ruta_final, $conexion, $doc, $nombre){

        if(isset($_FILES[$file]) && $_FILES[$file]['error'] == 0){

            $ruta_tipo = $ruta_final . $tipo . "/";
            if(!is_dir($ruta_tipo)){
                mkdir($ruta_tipo, 0777, true);
            }

            $ext = pathinfo($_FILES[$file]['name'], PATHINFO_EXTENSION);

            $nuevo_nombre = limpiarTexto($doc) . "_" . limpiarTexto($nombre) . "_" . time() . "." . $ext;
            $ruta = $ruta_tipo . $nuevo_nombre;

            if(move_uploaded_file($_FILES[$file]['tmp_name'], $ruta)){
                mysqli_query($conexion,"
                    INSERT INTO documentos_inversionista
                    (inversionista_id,tipo_documento,ruta,fecha)
                    VALUES('$id','$tipo','$ruta',NOW())
                ");
            }
        }
    }

    // 🔹 GUARDAR NUEVOS DOCUMENTOS (SIN BORRAR LOS ANTERIORES)
    actualizarArchivo("doc_identidad", "IDENTIDAD", $id, $ruta_final, $conexion, $doc, $nombre);
    actualizarArchivo("doc_direccion", "DIRECCION", $id, $ruta_final, $conexion, $doc, $nombre);

    echo "ok";
}
if($accion == "calcular_liquidacion"){

    $id = $_POST['id'];

    $q = mysqli_query($conexion,"
        SELECT * FROM movimientos_inversionista
        WHERE id='$id'
    ");

    $m = mysqli_fetch_assoc($q);

    // 🔍 VALIDAR SI YA SE PAGÓ INTERÉS
    $validar = mysqli_query($conexion,"
        SELECT id FROM movimientos_inversionista
        WHERE aporte_id='$id'
        AND tipo='RETIRO'
        AND interes > 0
    ");

    $yaPagoInteres = mysqli_num_rows($validar) > 0;

    // 🔹 CALCULAR INTERÉS SOLO SI NO SE HA PAGADO
    $interes = 0;

    if(!$yaPagoInteres){
        $interes = ($m['valor'] * $m['tasa'] / 100) * $m['meses'];
    }

    $total = $m['valor'] + $interes;

    echo json_encode([
        "valor" => number_format($m['valor'],0,',','.'),
        "tasa" => $m['tasa'],
        "meses" => $m['meses'],
        "interes" => number_format($interes,0,',','.'),
        "total" => number_format($total,0,',','.'),
        "ya_pagado" => $yaPagoInteres
    ]);

    exit;
}

if($accion == "liquidar"){ 

    $id = $_POST['id'] ?? 0;

    // 🔹 TRAER EL APORTE
    $q = mysqli_query($conexion,"
        SELECT * FROM movimientos_inversionista
        WHERE id='$id'
    ");

    if(!$q){
        echo "error";
        exit;
    }

    $m = mysqli_fetch_assoc($q);

    if(!$m){
        echo "error";
        exit;
    }

    // 🔥 VALIDAR SI YA PAGÓ INTERÉS
    $validar = mysqli_query($conexion,"
        SELECT id FROM movimientos_inversionista
        WHERE aporte_id='$id'
        AND tipo='RETIRO'
        AND interes > 0
    ");

    if(!$validar){
        echo "error";
        exit;
    }

    $yaPagoInteres = mysqli_num_rows($validar) > 0;

    // 🔹 CALCULAR INTERÉS
    $interes = 0;
    if(!$yaPagoInteres){
        $interes = ($m['valor'] * $m['tasa'] / 100) * $m['meses'];
    }

    // ============================
    // 🔥 🔥 NUEVO BLOQUE (SALDO CORRECTO)
    // ============================

    $qSaldo = mysqli_query($conexion,"
        SELECT IFNULL(SUM(
            CASE 
                WHEN UPPER(tipo)='APORTE' THEN valor

                WHEN UPPER(tipo)='RETIRO' 
                AND (
                    aporte_id IS NULL 
                    OR medio_pago = 'LIQUIDACION'
                )
                THEN -valor

                ELSE 0
            END
        ),0) as total
        FROM movimientos_inversionista
        WHERE id_inversionista='{$m['id_inversionista']}'
    ");

    if(!$qSaldo){
        echo "error";
        exit;
    }

    $saldo = floatval(mysqli_fetch_assoc($qSaldo)['total'] ?? 0);
	
	// 🔥 VALIDAR SALDO SUFICIENTE
if($m['valor'] > $saldo){
    echo "error_saldo_liquidacion";
    exit;
}

    // 🔴 🔴 🔴 VALIDACIÓN NUEVA (CLAVE)
    if($m['valor'] > $saldo){

        echo json_encode([
            "status" => "error",
            "msg" => "No tienes capital suficiente para liquidar este aporte. Debes retirar solo el saldo disponible."
        ]);

        exit;
    }

    // 🔥 VALIDAR QUE NO RETIRE MÁS DE LO QUE HAY
    // 🔴 VALIDAR SI YA FUE LIQUIDADO
    if($m['estado'] == 'LIQUIDADO'){
        echo "ya_liquidado";
        exit;
    }

    // ============================

    $total = $m['valor'] + $interes;

    // 🔹 MARCAR APORTE COMO LIQUIDADO
    $update = mysqli_query($conexion,"
        UPDATE movimientos_inversionista
        SET 
            estado='LIQUIDADO',
            fecha_liquidacion=NOW(),
            interes='$interes',
            total='$total'
        WHERE id='$id'
    ");

    if(!$update){
        echo "error";
        exit;
    }

    // ============================
    // 🔥 INSERTS
    // ============================

    $ok1 = true;
    $ok2 = true;

    // 🔹 INSERT INTERÉS (si no se ha pagado)
    if(!$yaPagoInteres && $interes > 0){
        $ok1 = mysqli_query($conexion,"
            INSERT INTO movimientos_inversionista
            (id_inversionista, tipo, valor, fecha, medio_pago, interes, aporte_id)
            VALUES(
                '{$m['id_inversionista']}',
                'RETIRO',
                '$interes',
                NOW(),
                'INTERES',
                '$interes',
                '$id'
            )
        ");

        // 🔴 REGISTRAR EN CAJA (INTERÉS)
        registrarCaja(
            $conexion,
            'EGRESO',
            'PAGO INTERES',
            'inversionista',
            $m['id_inversionista'],
            $interes
        );
    }

    // 🔹 INSERT CAPITAL
    $ok2 = mysqli_query($conexion,"
        INSERT INTO movimientos_inversionista
        (id_inversionista, tipo, valor, fecha, medio_pago, interes, aporte_id)
        VALUES(
            '{$m['id_inversionista']}',
            'RETIRO',
            '{$m['valor']}',
            NOW(),
            'LIQUIDACION',
            0,
            '$id'
        )
    ");

    // 🔴 REGISTRAR EN CAJA (CAPITAL)
    registrarCaja(
        $conexion,
        'EGRESO',
        'LIQUIDACION CAPITAL INVERSION',
        'inversionista',
        $m['id_inversionista'],
        $m['valor']
    );

    // 🔥 VALIDACIÓN FINAL
    if(!$ok1 || !$ok2){
        echo "error";
        exit;
    }

    // ✅ RESPUESTA LIMPIA PARA AJAX
    echo "ok";
    exit;
}
if(($accion ?? '') == "liquidar_todo"){  

    $id = $_POST['id'] ?? 0;

    if(!$id){
        echo "error";
        exit;
    }

    // ============================
    // 🔹 SALDO REAL (CORREGIDO)
    // ============================
    $saldo = mysqli_query($conexion,"
        SELECT IFNULL(SUM(
            CASE 
                WHEN UPPER(tipo)='APORTE' THEN valor

                WHEN UPPER(tipo)='RETIRO'
                AND (
                    aporte_id IS NULL 
                    OR medio_pago = 'LIQUIDACION'
                )
                THEN -valor

                ELSE 0
            END
        ),0) as total
        FROM movimientos_inversionista
        WHERE id_inversionista='$id'
    ");

    if(!$saldo){
        die("SQL SALDO: " . mysqli_error($conexion));
    }

    $saldoReal = floatval(mysqli_fetch_assoc($saldo)['total'] ?? 0);

    if($saldoReal <= 0){
        echo "sin_saldo";
        exit;
    }

    // ============================
    // 🔹 INTERESES GENERADOS
    // ============================
    $intereses = mysqli_query($conexion,"
        SELECT IFNULL(SUM((valor * tasa / 100) * meses),0) as total
        FROM movimientos_inversionista
        WHERE id_inversionista='$id'
        AND UPPER(tipo)='APORTE'
    ");

    if(!$intereses){
        die("SQL INTERESES: " . mysqli_error($conexion));
    }

    $totalInteres = floatval(mysqli_fetch_assoc($intereses)['total'] ?? 0);

    // ============================
    // 🔹 INTERESES RETIRADOS
    // ============================
    $retirosInteres = mysqli_query($conexion,"
        SELECT IFNULL(SUM(interes),0) as total 
        FROM movimientos_inversionista 
        WHERE id_inversionista='$id' 
        AND UPPER(tipo)='RETIRO'
        AND interes > 0
    ");

    if(!$retirosInteres){
        die("SQL RETIROS INTERES: " . mysqli_error($conexion));
    }

    $totalInteresRetirado = floatval(mysqli_fetch_assoc($retirosInteres)['total'] ?? 0);

    // ============================
    // 🔹 INTERÉS DISPONIBLE
    // ============================
    $interesDisponible = max(0, $totalInteres - $totalInteresRetirado);

    // ============================
    // 🔥 🔥 NUEVO: LIQUIDAR POR APORTE (NO GLOBAL)
    // ============================

    $aportes = mysqli_query($conexion,"
        SELECT * FROM movimientos_inversionista
        WHERE id_inversionista='$id'
        AND tipo='APORTE'
        AND (estado IS NULL OR estado != 'LIQUIDADO')
    ");

    if(!$aportes){
        die("SQL APORTES: " . mysqli_error($conexion));
    }

    if(mysqli_num_rows($aportes) == 0){
        echo "sin_saldo";
        exit;
    }

    while($m = mysqli_fetch_assoc($aportes)){

        $aporte_id = $m['id'];

        // 🔹 VALIDAR SI YA PAGÓ INTERÉS
        $validar = mysqli_query($conexion,"
            SELECT id FROM movimientos_inversionista
            WHERE aporte_id='$aporte_id'
            AND tipo='RETIRO'
            AND interes > 0
        ");

        $yaPagoInteres = mysqli_num_rows($validar) > 0;

        // 🔹 CALCULAR INTERÉS
        $interes = 0;
        if(!$yaPagoInteres){
            $interes = ($m['valor'] * $m['tasa'] / 100) * $m['meses'];
        }

        $total = $m['valor'] + $interes;

        // 🔹 MARCAR APORTE
        mysqli_query($conexion,"
            UPDATE movimientos_inversionista
            SET 
                estado='LIQUIDADO',
                fecha_liquidacion=NOW(),
                interes='$interes',
                total='$total'
            WHERE id='$aporte_id'
        ");

        // 🔹 INSERT INTERÉS
        if(!$yaPagoInteres && $interes > 0){
            mysqli_query($conexion,"
                INSERT INTO movimientos_inversionista
                (id_inversionista, tipo, valor, fecha, medio_pago, interes, aporte_id)
                VALUES(
                    '{$m['id_inversionista']}',
                    'RETIRO',
                    '$interes',
                    NOW(),
                    'INTERES',
                    '$interes',
                    '$aporte_id'
                )
            ");

            // 🔴 REGISTRAR EN CAJA (INTERÉS)
            registrarCaja(
                $conexion,
                'EGRESO',
                'PAGO INTERES',
                'inversionista',
                $m['id_inversionista'],
                $interes
            );
        }

        // 🔹 INSERT CAPITAL
        mysqli_query($conexion,"
            INSERT INTO movimientos_inversionista
            (id_inversionista, tipo, valor, fecha, medio_pago, interes, aporte_id)
            VALUES(
                '".$m['id_inversionista']."',
                'RETIRO',
                '".$m['valor']."',
                NOW(),
                'LIQUIDACION',
                0,
                '".$aporte_id."'
            )
        ");

        // 🔴 REGISTRAR EN CAJA (CAPITAL)
        registrarCaja(
            $conexion,
            'EGRESO',
            'LIQUIDACION CAPITAL INVERSION',
            'inversionista',
            $m['id_inversionista'],
            $m['valor']
        );

        echo "ok";
        exit;
    }

    // ============================
    // ✅ RESPUESTA FINAL
    // ============================

    ob_clean();
    header('Content-Type: application/json');

    echo json_encode([
        "status" => "ok"
    ]);

    exit;
}

// ============================
// 🔹 MOVIMIENTOS POR APORTE (DETALLE)
// ============================
if($accion == "movimientos_aporte"){

    $aporte_id = $_POST['aporte_id'] ?? 0;

$q = mysqli_query($conexion,"
 SELECT fecha, valor, interes, tipo, medio_pago
FROM movimientos_inversionista
WHERE aporte_id = '$aporte_id'
AND id != '$aporte_id'
ORDER BY fecha ASC

");

    if(mysqli_num_rows($q) == 0){
        echo "<i>No hay movimientos</i>";
        exit;
    }

    echo "<table class='table table-sm table-bordered mb-0'>
            <tr>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Valor</th>
                <th>Interés</th>
            </tr>";

    while($r = mysqli_fetch_assoc($q)){

$medio = $r['medio_pago'] ?? '';

if($r['tipo'] == 'RETIRO' && $r['interes'] > 0){

    $tipo = "<span class='text-warning'>💸 RETIRO INTERÉS</span>";

}elseif($r['tipo'] == 'RETIRO'){

    // 🔥 TODO RETIRO DE CAPITAL EN ROJO CON FLECHA
    $tipo = "<span class='text-danger'>⬇️ RETIRO CAPITAL</span>";

}
        echo "<tr>
            <td>{$r['fecha']}</td>
            <td>$tipo</td>
            <td>$ ".number_format($r['valor'],0,',','.')."</td>
            <td>
".($r['interes'] > 0 
    ? "$ ".number_format($r['interes'],0,',','.') 
    : "-")."
</td>
        </tr>";
    }

    echo "</table>";
    exit;
}

// ============================
// 🔹 CALCULAR SOLO INTERÉS
// ============================
if($accion == "calcular_interes"){

    $id = $_POST['id'];

    $q = mysqli_query($conexion,"
        SELECT * FROM movimientos_inversionista
        WHERE id='$id'
    ");

    $m = mysqli_fetch_assoc($q);

    $interes = ($m['valor'] * $m['tasa'] / 100) * $m['meses'];

    echo json_encode([
        "valor" => number_format($m['valor'],0,',','.'),
        "tasa" => $m['tasa'],
        "meses" => $m['meses'],
        "interes" => number_format($interes,0,',','.')
    ]);

    exit;
}

// ============================
// 🔹 LIQUIDAR SOLO INTERÉS
// ============================
if($accion == "liquidar_interes"){

    $id = $_POST['id'];

    // 🔹 traer aporte
    $q = mysqli_query($conexion,"
        SELECT * FROM movimientos_inversionista
        WHERE id='$id'
    ");

    $m = mysqli_fetch_assoc($q);

    if(!$m){
        echo "error";
        exit;
    }

    // 🔹 calcular interés
    $interes = ($m['valor'] * $m['tasa'] / 100) * $m['meses'];

    // 🔴 validar si ya se pagó
    $validar = mysqli_query($conexion,"
        SELECT id FROM movimientos_inversionista
        WHERE aporte_id='$id'
        AND tipo='RETIRO'
        AND interes > 0
    ");

    if(mysqli_num_rows($validar) > 0){
        echo "ya_pagado";
        exit;
    }

    // 🔥 registrar retiro SOLO interés
    mysqli_query($conexion,"
        INSERT INTO movimientos_inversionista
        (id_inversionista, tipo, valor, fecha, medio_pago, interes, aporte_id)
        VALUES(
            '{$m['id_inversionista']}',
            'RETIRO',
            '$interes',
            NOW(),
            'INTERES',
            '$interes',
            '$id'
        )
    ");

    // 🔴 🔴 🔴 AQUÍ ESTÁ EL AJUSTE (CAJA)
    registrarCaja(
        $conexion,
        'EGRESO',
        'PAGO INTERES',
        'inversionista',
        $m['id_inversionista'],
        $interes
    );

    echo "ok";
    exit;
}

if($accion == "calcular_liquidacion_total"){

    $id = $_POST['id'];

    // 🔹 CAPITAL
$capitalQ = mysqli_query($conexion,"
    SELECT IFNULL(SUM(
        CASE 
            WHEN UPPER(tipo)='APORTE' THEN valor

            -- 🔥 AHORA RESTA RETIROS DE CAPITAL (NO INTERESES)
            WHEN UPPER(tipo)='RETIRO' 
            AND (interes IS NULL OR interes = 0)
            THEN -valor

            ELSE 0
        END
    ),0) as total
    FROM movimientos_inversionista
    WHERE id_inversionista='$id'
");

    $capital = mysqli_fetch_assoc($capitalQ)['total'] ?? 0;

    // 🔹 INTERÉS GENERADO
    $interesQ = mysqli_query($conexion,"
        SELECT IFNULL(SUM((valor * tasa / 100) * meses),0) as total
        FROM movimientos_inversionista
        WHERE id_inversionista='$id'
        AND tipo='APORTE'
    ");

    $interes = mysqli_fetch_assoc($interesQ)['total'] ?? 0;

    // 🔹 INTERÉS RETIRADO
    $retiradoQ = mysqli_query($conexion,"
        SELECT IFNULL(SUM(interes),0) as total
        FROM movimientos_inversionista
        WHERE id_inversionista='$id'
        AND tipo='RETIRO'
        AND interes > 0
    ");

    $retirado = mysqli_fetch_assoc($retiradoQ)['total'] ?? 0;

    $disponible = max(0, $interes - $retirado);

    echo json_encode([
        "capital" => number_format($capital,0,',','.'),
        "interes" => number_format($interes,0,',','.'),
        "interes_retirado" => number_format($retirado,0,',','.'),
        "interes_disponible" => number_format($disponible,0,',','.')
    ]);

    exit;
}

if(($accion ?? '') == "liquidar_intereses_todo"){  

    $id = $_POST['id'] ?? 0;

    if(!$id){
        echo "error";
        exit;
    }

    $hoy = new DateTime();

    // 🔥 TRAER SOLO APORTES ACTIVOS
    $aportes = mysqli_query($conexion,"
        SELECT 
            mi.*,
            fp.frecuencia
        FROM movimientos_inversionista mi
        LEFT JOIN frecuencia_pago fp
            ON mi.frecuencia_pago_id = fp.id
        WHERE mi.id_inversionista='$id'
        AND mi.tipo='APORTE'
        AND (mi.estado IS NULL OR mi.estado != 'LIQUIDADO')
    ");

    if(!$aportes){
        echo "error";
        exit;
    }

    $pagosRealizados = 0; // 🔥 CONTADOR

    while($m = mysqli_fetch_assoc($aportes)){

        $aporte_id = $m['id'];

        // ============================
        // 🔹 CALCULAR FECHA DE PAGO
        // ============================
        $fechaAporte = new DateTime($m['fecha']);
        $fechaPago = clone $fechaAporte;

        $meses = intval($m['meses']);

        switch(strtolower($m['frecuencia'])){
            case 'mensual':
                $fechaPago->modify("+$meses month");
            break;
            case 'quincenal':
                $fechaPago->modify("+" . (15 * $meses) . " days");
            break;
            case 'semanal':
                $fechaPago->modify("+" . (7 * $meses) . " days");
            break;
            default:
                $fechaPago->modify("+$meses month");
        }

        // 🔥 SOLO SI YA VENCIÓ
        if($hoy < $fechaPago){
            continue;
        }

        // ============================
        // 🔹 VALIDAR SI YA PAGÓ INTERÉS
        // ============================
        $validar = mysqli_query($conexion,"
            SELECT id FROM movimientos_inversionista
            WHERE aporte_id='$aporte_id'
            AND tipo='RETIRO'
            AND interes > 0
        ");

        if(mysqli_num_rows($validar) > 0){
            continue;
        }

        // ============================
        // 🔹 CALCULAR INTERÉS
        // ============================
        $interes = ($m['valor'] * $m['tasa'] / 100) * $m['meses'];

        if($interes <= 0){
            continue;
        }

        // ============================
        // 🔹 INSERT INTERÉS
        // ============================
        $ok = mysqli_query($conexion,"
            INSERT INTO movimientos_inversionista
            (id_inversionista, tipo, valor, fecha, medio_pago, interes, aporte_id)
            VALUES(
                '{$m['id_inversionista']}',
                'RETIRO',
                '$interes',
                NOW(),
                'INTERES',
                '$interes',
                '$aporte_id'
            )
        ");

        if($ok){

            // 🔥 NUEVO: REGISTRAR EN CAJA
            mysqli_query($conexion,"
                INSERT INTO movimientos_caja
                (tipo, concepto, origen, referencia_id, valor, fecha)
                VALUES(
                    'EGRESO',
                    'PAGO INTERES INVERSIONISTA',
                    'inversionista',
                    '$aporte_id',
                    '$interes',
                    NOW()
                )
            ");

            $pagosRealizados++; // 🔥 CONTAR SOLO SI INSERTÓ
        }
    }

    // ============================
    // 🔥 RESPUESTA FINAL
    // ============================
    if($pagosRealizados > 0){
        echo "ok";
    } else {
        echo "sin_vencidos";
    }

    exit;
}


if(($accion ?? '') == "liquidar_capital_todo"){  

    $id = $_POST['id'] ?? 0;

    if(!$id){
        echo "error";
        exit;
    }

    $hoy = new DateTime();

    $aportes = mysqli_query($conexion,"
        SELECT 
            mi.*,
            fp.frecuencia
        FROM movimientos_inversionista mi
        LEFT JOIN frecuencia_pago fp
            ON mi.frecuencia_pago_id = fp.id
        WHERE mi.id_inversionista='$id'
        AND mi.tipo='APORTE'
        AND (mi.estado IS NULL OR mi.estado != 'LIQUIDADO')
    ");

    if(!$aportes){
        echo "error";
        exit;
    }

    $retirosRealizados = 0;

    while($m = mysqli_fetch_assoc($aportes)){

        $aporte_id = $m['id'];

        // 🔹 calcular fecha de vencimiento
        $fechaAporte = new DateTime($m['fecha']);
        $fechaPago = clone $fechaAporte;

        $meses = intval($m['meses']);

        switch(strtolower($m['frecuencia'])){
            case 'mensual':
                $fechaPago->modify("+$meses month");
            break;
            case 'quincenal':
                $fechaPago->modify("+" . (15 * $meses) . " days");
            break;
            case 'semanal':
                $fechaPago->modify("+" . (7 * $meses) . " days");
            break;
            default:
                $fechaPago->modify("+$meses month");
        }

        // 🔥 solo vencidos
        if($hoy < $fechaPago){
            continue;
        }

        // 🔹 evitar doble liquidación
        if($m['estado'] == 'LIQUIDADO'){
            continue;
        }

        // 🔹 marcar como liquidado
        mysqli_query($conexion,"
            UPDATE movimientos_inversionista
            SET 
                estado='LIQUIDADO',
                fecha_liquidacion=NOW()
            WHERE id='$aporte_id'
        ");

        // 🔹 retirar capital
        $ok = mysqli_query($conexion,"
            INSERT INTO movimientos_inversionista
            (id_inversionista, tipo, valor, fecha, medio_pago, interes, aporte_id)
            VALUES(
                '{$m['id_inversionista']}',
                'RETIRO',
                '{$m['valor']}',
                NOW(),
                'LIQUIDACION',
                0,
                '$aporte_id'
            )
        ");

        if($ok){

            // 🔥 NUEVO: REGISTRAR EN CAJA
            mysqli_query($conexion,"
                INSERT INTO movimientos_caja
                (tipo, concepto, origen, referencia_id, valor, fecha)
                VALUES(
                    'EGRESO',
                    'LIQUIDACION CAPITAL INVERSION',
                    'inversionista',
                    '$aporte_id',
                    '{$m['valor']}',
                    NOW()
                )
            ");

            $retirosRealizados++;
        }
    }

    // 🔥 respuesta
    if($retirosRealizados > 0){
        echo "ok";
    } else {
        echo "sin_vencidos";
    }

    exit;
}

?>