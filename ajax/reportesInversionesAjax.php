<?php
include("../includes/db.php");

$tipo = $_POST['tipo_reporte'] ?? '';
$anio = $_POST['anio'] ?? '';
$fecha_inicio = $_POST['fecha_inicio'] ?? '';
$fecha_fin = $_POST['fecha_fin'] ?? '';
$cedula = $_POST['cedula'] ?? '';

$meses = [
1=>"ENERO",2=>"FEBRERO",3=>"MARZO",4=>"ABRIL",
5=>"MAYO",6=>"JUNIO",7=>"JULIO",8=>"AGOSTO",
9=>"SEPTIEMBRE",10=>"OCTUBRE",11=>"NOVIEMBRE",12=>"DICIEMBRE"
];

if($tipo == 1){

    if($anio === ""){
        echo "<tr><td colspan='8'>Seleccione un año o 'Todos'</td></tr>";
        exit;
    }

    // 🔥 FILTRO DINÁMICO
    if($anio == "TODOS"){
        $filtro_prestamos = "1=1";
        $filtro_cuotas = "1=1";
    }else{
        $filtro_prestamos = "YEAR(p.fecha_inicio) = '$anio'";
        $filtro_cuotas = "YEAR(cp.fecha_pago) = '$anio'";
    }

    // 🔥 SQL SOLO POR AÑO (SIN FECHAS)
    $sql = "
SELECT 
    mes,
    SUM(inversion) as inversion,
    SUM(desembolso) as desembolso,
    SUM(capital) as capital,
    SUM(intereses) as intereses,
    SUM(mora) as mora
FROM (

    -- 🔥 SOLO préstamos desembolsados
    SELECT 
        MONTH(p.fecha_inicio) as mes,

        SUM(p.monto_prestado) as inversion,
        SUM(p.monto_prestado) as desembolso,

        0 as capital,
        0 as intereses,
        0 as mora

    FROM prestamos p
    INNER JOIN estado_prestamo ep 
        ON ep.id = p.id_estp

    WHERE $filtro_prestamos
      AND ep.statusPrest = 'DESEMBOLSADO'

    GROUP BY MONTH(p.fecha_inicio)

    UNION ALL

    -- 🔵 SOLO pagos de préstamos desembolsados
    SELECT 
        MONTH(cp.fecha_pago) as mes,

        0 as inversion,
        0 as desembolso,

        SUM(cp.capital) as capital,
        SUM(cp.interes) as intereses,
        0 as mora

    FROM cuotas_prestamo cp

    INNER JOIN prestamos p 
        ON p.id = cp.id_prestamo

    INNER JOIN estado_prestamo ep 
        ON ep.id = p.id_estp

    WHERE $filtro_cuotas
      AND cp.estado = 'PAGADO'
      AND ep.statusPrest = 'DESEMBOLSADO'

    GROUP BY MONTH(cp.fecha_pago)

) t

GROUP BY mes
ORDER BY mes ASC
    ";

    $result = mysqli_query($conexion, $sql);

    if (!$result) {
        die("Error en la consulta: " . mysqli_error($conexion));
    }

    $data = [];

    while($row = mysqli_fetch_assoc($result)){
        $data[$row['mes']] = $row;
    }

    $saldo = 0;

    echo "
    <tr class='table-dark text-center'>
    <th>PERIODO</th>
    <th>MONTO INVERSIÓN</th>
    <th>DESEMBOLSO</th>
    <th>CAPITAL</th>
    <th>INTERESES</th>
    <th>SALDO CARTERA</th>
    <th>MORA</th>
    <th>SEGUROS</th>
    </tr>
    ";

    $total_inv = 0;
    $total_des = 0;
    $total_cap = 0;
    $total_int = 0;
    $total_mora = 0;
    $total_seg = 0;

    for($i=1; $i<=12; $i++){

        $row = $data[$i] ?? null;

        $inv = $row['inversion'] ?? 0;
        $des = $row['desembolso'] ?? 0;
        $cap = $row['capital'] ?? 0;
        $int = $row['intereses'] ?? 0;
        $mora = $row['mora'] ?? 0;
        $seg = 0; // aún no manejas seguros

        $saldo = $saldo + $des - $cap;

        $total_inv += $inv;
        $total_des += $des;
        $total_cap += $cap;
        $total_int += $int;
        $total_mora += $mora;
        $total_seg += $seg;

        echo "<tr>
        <td><b>{$meses[$i]}</b></td>
        <td>$ ".number_format($inv,0,',','.')."</td>
        <td>$ ".number_format($des,0,',','.')."</td>
        <td>$ ".number_format($cap,0,',','.')."</td>
        <td>$ ".number_format($int,0,',','.')."</td>
        <td><b>$ ".number_format($saldo,0,',','.')."</b></td>
        <td>$ ".number_format($mora,0,',','.')."</td>
        <td>$ ".number_format($seg,0,',','.')."</td>
        </tr>";
    }

    echo "
    <tr class='table-primary fw-bold'>
    <td>TOTAL</td>
    <td>$ ".number_format($total_inv,0,',','.')."</td>
    <td>$ ".number_format($total_des,0,',','.')."</td>
    <td>$ ".number_format($total_cap,0,',','.')."</td>
    <td>$ ".number_format($total_int,0,',','.')."</td>
    <td>$ ".number_format($saldo,0,',','.')."</td>
    <td>$ ".number_format($total_mora,0,',','.')."</td>
    <td>$ ".number_format($total_seg,0,',','.')."</td>
    </tr>
    ";

}


if($tipo == 2){

    $mes = $_POST['mes'] ?? '';
    $anio = $_POST['anio'] ?? '';

    $filtroCedula = "";
    $filtroFecha = "";

    // ✅ FILTRO POR CÉDULA
    if(!empty($cedula)){
        $filtroCedula = "AND c.docIdentClient = '$cedula'";
    }else{
        // ✅ SI NO HAY CÉDULA → EXIGIR MES Y AÑO
        if(empty($mes) || empty($anio)){
            echo "<tr><td colspan='9'>Seleccione mes y año o ingrese una cédula</td></tr>";
            exit;
        }

        $filtroFecha = "AND MONTH(cp.fecha_pago) = '$mes'
                        AND YEAR(cp.fecha_pago) = '$anio'";
    }

    $sql = "
    SELECT 
        c.docIdentClient as cedula,
        c.nombreClient as nombre,

        cp.numero_cuota,
        cp.interes,
        cp.capital as amortizacion,
        cp.monto as valor_cuota,
        
        (
            SELECT SUM(c2.capital)
            FROM cuotas_prestamo c2
            INNER JOIN prestamos p2 ON p2.id = c2.id_prestamo
            INNER JOIN estado_prestamo ep2 ON ep2.id = p2.id_estp
            WHERE c2.id_prestamo = cp.id_prestamo
              AND c2.estado != 'PAGADO'
              AND ep2.statusPrest = 'DESEMBOLSADO'
        ) as saldo_cartera,

        cp.fecha_pago as fecha_vencimiento,

        DATEDIFF(CURDATE(), cp.fecha_pago) as dias_vencido,

        'CUOTA VENCIDA' as estado

    FROM cuotas_prestamo cp

    INNER JOIN prestamos p 
        ON p.id = cp.id_prestamo

    INNER JOIN clientes c 
        ON c.id = p.id_cliente

    INNER JOIN estado_prestamo ep 
        ON ep.id = p.id_estp

    WHERE cp.estado != 'PAGADO'
      AND ep.statusPrest = 'DESEMBOLSADO'
      AND cp.fecha_pago < CURDATE()
      $filtroFecha
      $filtroCedula

    ORDER BY dias_vencido DESC
    ";

    $result = mysqli_query($conexion, $sql);

    if (!$result) {
        die("Error en la consulta: " . mysqli_error($conexion));
    }

    // 🔥 TOTALES
    $total_interes = 0;
    $total_capital = 0;
    $total_cuota = 0;
    $total_saldo = 0;

    echo "
    <tr class='table-dark text-center'>
    <th>CEDULA</th>
    <th>CUOTA VENCIDA</th>
    <th>INTERES</th>
    <th>AMORTIZACION CUOTA</th>
    <th>VALOR CUOTA</th>
    <th>SALDO DE CARTERA</th>
    <th>FECHA VCTO</th>
    <th>DIAS VCTO</th>
    <th>ESTADO</th>
    </tr>
    ";

    while($row = mysqli_fetch_assoc($result)){

        $total_interes += $row['interes'];
        $total_capital += $row['amortizacion'];
        $total_cuota += $row['valor_cuota'];
        $total_saldo += $row['saldo_cartera'];

        echo "<tr>
        <td>{$row['cedula']}<br><small>{$row['nombre']}</small></td>
        <td>{$row['numero_cuota']}</td>
        <td>$ ".number_format($row['interes'],0,',','.')."</td>
        <td>$ ".number_format($row['amortizacion'],0,',','.')."</td>
        <td>$ ".number_format($row['valor_cuota'],0,',','.')."</td>
        <td>$ ".number_format($row['saldo_cartera'],0,',','.')."</td>
        <td>{$row['fecha_vencimiento']}</td>
        <td>{$row['dias_vencido']}</td>
        <td class='text-danger fw-bold'>{$row['estado']}</td>
        </tr>";
    }

    echo "
    <tr class='table-primary fw-bold'>
        <td colspan='2'>TOTAL</td>
        <td>$ ".number_format($total_interes,0,',','.')."</td>
        <td>$ ".number_format($total_capital,0,',','.')."</td>
        <td>$ ".number_format($total_cuota,0,',','.')."</td>
        <td>$ ".number_format($total_saldo,0,',','.')."</td>
        <td colspan='3'></td>
    </tr>
    ";
}

if($tipo == 3){

    $mes = $_POST['mes'] ?? '';
    $anio = $_POST['anio'] ?? '';
    $cedula = $_POST['cedula'] ?? '';
    $asesor = $_POST['asesor'] ?? '';

    $filtro = "WHERE ep.statusPrest = 'DESEMBOLSADO'";

    // 📅 filtro por mes/año
    if(!empty($mes) && !empty($anio)){
        $filtro .= " AND MONTH(p.fecha_inicio) = '$mes' 
                     AND YEAR(p.fecha_inicio) = '$anio'";
    }

    // 👤 filtro por cédula cliente
    if(!empty($cedula)){
        $filtro .= " AND c.docIdentClient LIKE '%$cedula%'";
    }

    // 🧑‍💼 filtro por asesor
    if(!empty($asesor)){
        $filtro .= " AND p.id_usuario_radica = '$asesor'";
    }

    $sql = "
    SELECT 
        'CREDITO' as operacion,
        DAY(p.fecha_inicio) as dia,
        MONTH(p.fecha_inicio) as mes,
        YEAR(p.fecha_inicio) as anio,

        c.docIdentClient as cedula_cliente,
        c.nombreClient as cliente,

        p.folioPrest as credito,
        p.monto_prestado as monto,

        (p.monto_prestado * 0.20) as valor_ingreso,
        (p.monto_prestado * 0.20 * 0.80) as ganancia_tramite,

        u.correo as correo_asesor,
        u.usuario as asesor,

        (p.monto_prestado * 0.20 * 0.20) as valor_comision

    FROM prestamos p

    INNER JOIN clientes c 
        ON c.id = p.id_cliente

    INNER JOIN estado_prestamo ep 
        ON ep.id = p.id_estp

    LEFT JOIN users u 
        ON u.id = p.id_usuario_radica

    $filtro

    ORDER BY p.fecha_inicio DESC
    ";

    $result = mysqli_query($conexion, $sql);

    if (!$result) {
        die("Error en la consulta: " . mysqli_error($conexion));
    }

    // 🔥 TOTALES
    $total_monto = 0;
    $total_ingreso = 0;
    $total_ganancia = 0;
    $total_comision = 0;

    echo "
    <tr class='table-dark text-center'>
        <th>OPERACIÓN</th>
        <th>DIA</th>
        <th>MES</th>
        <th>AÑO</th>
        <th>CC</th>
        <th>NOMBRES Y APELLIDOS</th>
        <th># CREDITO</th>
        <th>MONTO</th>
        <th>%</th>
        <th>VALOR</th>
        <th>GANANCIA TRAMITE</th>
        <th>NOMBRES Y APELLIDOS</th>
        <th>%</th>
        <th>VALOR</th>
    </tr>
    ";

    while($row = mysqli_fetch_assoc($result)){

        $total_monto += $row['monto'];
        $total_ingreso += $row['valor_ingreso'];
        $total_ganancia += $row['ganancia_tramite'];
        $total_comision += $row['valor_comision'];

        echo "<tr>
            <td>{$row['operacion']}</td>
            <td>{$row['dia']}</td>
            <td>{$meses[$row['mes']]}</td>
            <td>{$row['anio']}</td>
            <td>{$row['cedula_cliente']}</td>
            <td>{$row['cliente']}</td>
            <td>{$row['credito']}</td>
            <td>$ ".number_format($row['monto'],0,',','.')."</td>
            <td>20%</td>
            <td>$ ".number_format($row['valor_ingreso'],0,',','.')."</td>
            <td>$ ".number_format($row['ganancia_tramite'],0,',','.')."</td>
            <td>{$row['asesor']}</td>
            <td>20%</td>
            <td>$ ".number_format($row['valor_comision'],0,',','.')."</td>
        </tr>";
    }

    // 🔥 FILA TOTAL
    echo "
    <tr class='table-primary fw-bold'>
        <td colspan='7'>TOTAL</td>
        <td>$ ".number_format($total_monto,0,',','.')."</td>
        <td></td>
        <td>$ ".number_format($total_ingreso,0,',','.')."</td>
        <td>$ ".number_format($total_ganancia,0,',','.')."</td>
        <td colspan='2'></td>
        <td>$ ".number_format($total_comision,0,',','.')."</td>
    </tr>
    ";
}
// ===============================
// 🔥 REPORTE 4 - INVERSIONISTAS
// ===============================
if($tipo == 4){

    if(empty($anio)){
        echo "<tr><td colspan='2'>Seleccione un año</td></tr>";
        exit;
    }

    // 🔹 INVERSIÓN
    $sql1 = "
    SELECT 
        SUM(p.monto_prestado) as inversion,
        COUNT(p.id) as desembolsos,
        SUM(p.monto_prestado) as desembolsado
    FROM prestamos p
    INNER JOIN estado_prestamo ep ON ep.id = p.id_estp
    WHERE YEAR(p.fecha_inicio) = '$anio'
    AND ep.statusPrest = 'DESEMBOLSADO'
    ";
    $r1 = mysqli_fetch_assoc(mysqli_query($conexion, $sql1));

    // 🔹 ESTADO CARTERA
    $sql2 = "
    SELECT 
        SUM(CASE WHEN cp.estado != 'PAGADO' THEN cp.capital ELSE 0 END) as saldo_cartera,
        SUM(CASE 
            WHEN cp.estado != 'PAGADO' AND cp.fecha_pago < CURDATE() 
            THEN cp.capital ELSE 0 END) as mora,
        COUNT(DISTINCT CASE 
            WHEN cp.estado != 'PAGADO' AND cp.fecha_pago < CURDATE() 
            THEN p.id_cliente END) as clientes_mora
    FROM cuotas_prestamo cp
    INNER JOIN prestamos p ON p.id = cp.id_prestamo
    INNER JOIN estado_prestamo ep ON ep.id = p.id_estp
    WHERE ep.statusPrest = 'DESEMBOLSADO'
    ";
    $r2 = mysqli_fetch_assoc(mysqli_query($conexion, $sql2));

    // 🔹 RECAUDO
    $sql3 = "
    SELECT 
        SUM(cp.capital) as recaudo_capital,
        SUM(cp.interes) as recaudo_interes
    FROM cuotas_prestamo cp
    INNER JOIN prestamos p ON p.id = cp.id_prestamo
    INNER JOIN estado_prestamo ep ON ep.id = p.id_estp
    WHERE cp.estado = 'PAGADO'
    AND YEAR(cp.fecha_pago) = '$anio'
    ";
    $r3 = mysqli_fetch_assoc(mysqli_query($conexion, $sql3));

    // 🔹 GANANCIA
    $sql4 = "
    SELECT SUM(p.monto_prestado * 0.20) as ganancia
    FROM prestamos p
    INNER JOIN estado_prestamo ep ON ep.id = p.id_estp
    WHERE YEAR(p.fecha_inicio) = '$anio'
    AND ep.statusPrest = 'DESEMBOLSADO'
    ";
    $r4 = mysqli_fetch_assoc(mysqli_query($conexion, $sql4));

    // 🔹 PAGOS
    $sql5 = "
    SELECT 
        SUM(p.monto_prestado * 0.20 * 0.20) as comisiones,
        SUM(p.monto_prestado * 0.01) as seguros
    FROM prestamos p
    INNER JOIN estado_prestamo ep ON ep.id = p.id_estp
    WHERE YEAR(p.fecha_inicio) = '$anio'
    AND ep.statusPrest = 'DESEMBOLSADO'
    ";
    $r5 = mysqli_fetch_assoc(mysqli_query($conexion, $sql5));

    function f($n){
        return "$ ".number_format($n ?? 0,0,',','.');
    }

    echo "
    <tr>
        <td colspan='2' class='text-center fw-bold bg-dark text-white'>
            INFORME INVERSIONES ACUMULADO - AÑO $anio
        </td>
    </tr>

    <tr class='table-primary'>
        <th>CONCEPTO</th>
        <th>CIFRAS</th>
    </tr>

    <tr><td colspan='2'><b>INVERSION</b></td></tr>
    <tr><td>VALOR DE LA CARTERA</td><td>".f($r1['inversion'])."</td></tr>
    <tr><td>NUMERO DE DESEMBOLSOS</td><td>{$r1['desembolsos']}</td></tr>
    <tr><td>VALOR DESEMBOLSADO</td><td>".f($r1['desembolsado'])."</td></tr>

    <tr><td colspan='2'><hr></td></tr>

    <tr><td colspan='2'><b>ESTADO DE LA CARTERA</b></td></tr>
    <tr><td>SALDO DE LA CARTERA A LA FECHA</td><td>".f($r2['saldo_cartera'])."</td></tr>
    <tr><td>SALDO DE LA CARTERA EN MORA</td><td>".f($r2['mora'])."</td></tr>
    <tr><td>NUMERO DE CLIENTES EN MORA</td><td>{$r2['clientes_mora']}</td></tr>

    <tr><td colspan='2'><hr></td></tr>

    <tr><td colspan='2'><b>REINVERSION POR RECAUDO - DESEMBOLSO</b></td></tr>
    <tr><td>RECAUDO POR CUOTAS</td><td>".f($r3['recaudo_capital'])."</td></tr>
    <tr><td>RECAUDO POR INTERES</td><td>".f($r3['recaudo_interes'])."</td></tr>
    <tr><td>GANANCIA POR TRAMITE</td><td>".f($r4['ganancia'])."</td></tr>

    <tr><td colspan='2'><hr></td></tr>

    <tr><td colspan='2'><b>PAGOS REALIZADOS</b></td></tr>
    <tr><td>POR COMISIONES</td><td>".f($r5['comisiones'])."</td></tr>
    <tr><td>POR SEGUROS</td><td>".f($r5['seguros'])."</td></tr>
    ";

}

// ===============================
// 🔥 REPORTE 5 - INVERSIONES POR MES
// ===============================
if($tipo == 5){

    $mes = $_POST['mes'] ?? '';
    $anio = $_POST['anio'] ?? '';

    if(empty($mes) || empty($anio)){
        echo "<tr><td colspan='2'>Seleccione mes y año</td></tr>";
        exit;
    }

    // 🔹 INVERSIÓN
    $sql1 = "
    SELECT 
        SUM(p.monto_prestado) as inversion,
        COUNT(p.id) as desembolsos,
        SUM(p.monto_prestado) as desembolsado
    FROM prestamos p
    INNER JOIN estado_prestamo ep ON ep.id = p.id_estp
    WHERE MONTH(p.fecha_inicio) = '$mes'
    AND YEAR(p.fecha_inicio) = '$anio'
    AND ep.statusPrest = 'DESEMBOLSADO'
    ";
    $r1 = mysqli_fetch_assoc(mysqli_query($conexion, $sql1));

    // 🔹 RECAUDO
    $sql2 = "
    SELECT 
        SUM(cp.capital) as capital,
        SUM(cp.interes) as interes
    FROM cuotas_prestamo cp
    WHERE cp.estado = 'PAGADO'
    AND MONTH(cp.fecha_pago) = '$mes'
    AND YEAR(cp.fecha_pago) = '$anio'
    ";
    $r2 = mysqli_fetch_assoc(mysqli_query($conexion, $sql2));

    // 🔹 GANANCIA
    $sql3 = "
    SELECT SUM(p.monto_prestado * 0.20) as ganancia
    FROM prestamos p
    INNER JOIN estado_prestamo ep ON ep.id = p.id_estp
    WHERE MONTH(p.fecha_inicio) = '$mes'
    AND YEAR(p.fecha_inicio) = '$anio'
    AND ep.statusPrest = 'DESEMBOLSADO'
    ";
    $r3 = mysqli_fetch_assoc(mysqli_query($conexion, $sql3));

    // 🔹 PAGOS
    $sql4 = "
    SELECT 
        SUM(p.monto_prestado * 0.20 * 0.20) as comisiones,
        SUM(p.monto_prestado * 0.01) as seguros
    FROM prestamos p
    INNER JOIN estado_prestamo ep ON ep.id = p.id_estp
    WHERE MONTH(p.fecha_inicio) = '$mes'
    AND YEAR(p.fecha_inicio) = '$anio'
    AND ep.statusPrest = 'DESEMBOLSADO'
    ";
    $r4 = mysqli_fetch_assoc(mysqli_query($conexion, $sql4));

    function f($n){
        return "$ ".number_format($n ?? 0,0,',','.');
    }

    echo "
    <tr>
        <td colspan='2' class='text-center fw-bold bg-dark text-white'>
            INFORME INVERSIONES POR MES - $mes / $anio
        </td>
    </tr>

    <tr class='table-primary'>
        <th>CONCEPTO</th>
        <th>CIFRAS</th>
    </tr>

    <tr><td colspan='2'><b>INVERSION</b></td></tr>
    <tr><td>VALOR DE LA CARTERA</td><td>".f($r1['inversion'])."</td></tr>
    <tr><td>NUMERO DE DESEMBOLSOS</td><td>{$r1['desembolsos']}</td></tr>
    <tr><td>VALOR DESEMBOLSADO</td><td>".f($r1['desembolsado'])."</td></tr>

    <tr><td colspan='2'><hr></td></tr>

    <tr><td colspan='2'><b>REINVERSION</b></td></tr>
    <tr><td>RECAUDO POR CAPITAL</td><td>".f($r2['capital'])."</td></tr>
    <tr><td>RECAUDO POR INTERES</td><td>".f($r2['interes'])."</td></tr>
    <tr><td>GANANCIA POR TRAMITE</td><td>".f($r3['ganancia'])."</td></tr>

    <tr><td colspan='2'><hr></td></tr>

    <tr><td colspan='2'><b>PAGOS</b></td></tr>
    <tr><td>COMISIONES</td><td>".f($r4['comisiones'])."</td></tr>
    <tr><td>SEGUROS</td><td>".f($r4['seguros'])."</td></tr>
    ";
}





?>