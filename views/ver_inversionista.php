<?php 
include "../includes/configSession.php";
require_once "../includes/permisos.php";
require_once "../includes/db.php";
include "../includes/header.php";

$id = $_GET['id'] ?? 0;

if(!$id){
    echo "<div class='alert alert-danger'>ID inválido</div>";
    exit;
}

// ============================
// 🔹 DATOS DEL INVERSIONISTA
// ============================
$q = mysqli_query($conexion,"
    SELECT i.*, t.nombre as tipo_doc, c.nombre as ciudad
    FROM inversionistas i
    LEFT JOIN tipo_identificacion t ON t.id = i.tipo_identificacion_id
    LEFT JOIN ciudades c ON c.id = i.ciudad_id
    WHERE i.id = '$id'
");

$inv = mysqli_fetch_assoc($q);

if(!$inv){
    echo "<div class='alert alert-danger'>Inversionista no encontrado</div>";
    exit;
}

// ============================
// 🔹 SALDO
// ============================
$qSaldo = mysqli_query($conexion,"
    SELECT IFNULL(SUM(
        CASE 
            WHEN UPPER(tipo)='APORTE' THEN valor
            WHEN UPPER(tipo)='RETIRO' THEN -valor
            ELSE 0
        END
    ),0) as total
    FROM movimientos_inversionista
    WHERE id_inversionista='$id'
");

$saldo = mysqli_fetch_assoc($qSaldo)['total'] ?? 0;

function f($n){
    return number_format($n ?? 0,0,',','.');
}
?>

<div class="container mt-4">

<h4 class="mb-3">👁️ Hoja de Vida del Inversionista</h4>

<div class="card mb-4">
<div class="card-body">

<div class="row">

    <div class="col-md-6"><b>Nombre:</b> <?= $inv['nombre'] ?></div>
    <div class="col-md-6"><b>Tipo Documento:</b> <?= $inv['tipo_doc'] ?></div>

    <div class="col-md-6"><b>Documento:</b> <?= $inv['documento'] ?></div>
    <div class="col-md-6"><b>Teléfono:</b> <?= $inv['telefono'] ?></div>

    <div class="col-md-6"><b>Email:</b> <?= $inv['email'] ?></div>
    <div class="col-md-6"><b>Ciudad:</b> <?= $inv['ciudad'] ?></div>

    <div class="col-md-6"><b>Dirección:</b> <?= $inv['direccion'] ?></div>
    <div class="col-md-6"><b>Barrio:</b> <?= $inv['barrio'] ?></div>

    <div class="col-md-12 mt-3">
        <h5>💰 Saldo Actual: $ <?= f($saldo) ?></h5>
    </div>

</div>

</div>
</div>

<!-- ============================
🔹 DOCUMENTOS
============================ -->
<div class="card mb-4">
<div class="card-header">
    <b>📂 Documentos</b>
</div>
<div class="card-body">

<table class="table table-bordered">
<thead>
<tr>
    <th>Tipo</th>
    <th>Archivo</th>
    <th>Estado</th>
    <th>Fecha</th>
</tr>
</thead>
<tbody>

<?php
$qDocs = mysqli_query($conexion,"
    SELECT d1.*,
    CASE 
        WHEN d1.id = (
            SELECT MAX(d2.id) 
            FROM documentos_inversionista d2 
            WHERE d2.inversionista_id = d1.inversionista_id 
            AND d2.tipo_documento = d1.tipo_documento
        ) THEN 1
        ELSE 0
    END as es_actual
    FROM documentos_inversionista d1
    WHERE d1.inversionista_id='$id'
    ORDER BY es_actual DESC, d1.tipo_documento, d1.id DESC
");

if(mysqli_num_rows($qDocs) == 0){
    echo "<tr><td colspan='3' class='text-center text-muted'>Sin documentos</td></tr>";
}else{
    while($doc = mysqli_fetch_assoc($qDocs)){
        echo "<tr>
            <td>{$doc['tipo_documento']}</td>
            <td>
                <a href='{$doc['ruta']}' target='_blank' class='btn btn-primary btn-sm'>
                    Ver documento
                </a>
            </td>
            <td>";
            
        if($doc['es_actual']){
            echo "<span class='badge bg-success'>ACTUAL</span>";
        }else{
            echo "<span class='badge bg-secondary'>HISTÓRICO</span>";
        }

        echo "</td>
<td>".date("d M Y - h:i A", strtotime($doc['fecha']))."</td>
</tr>";
    }
}
?>

</tbody>
</table>

</div>
</div>

<!-- ============================
🔹 MOVIMIENTOS
============================ -->
<div class="card mb-4">
<div class="card-header">
    <b>📊 Movimientos</b>
</div>
<div class="card-body">

<table class="table table-striped">
<thead>
<tr>
    <th>Fecha</th>
    <th>Tipo</th>
    <th>Valor</th>
    <th>Saldo</th>
	<th>Medio</th>
<th>Banco</th>
<th>Cuenta</th>
<th>Tasa</th>
<th>Meses</th>
</tr>
</thead>
<tbody>

<?php
$qMov = mysqli_query($conexion,"
    SELECT * FROM movimientos_inversionista
    WHERE id_inversionista='$id'
    ORDER BY fecha ASC
");

$saldoTemp = 0;

while($m = mysqli_fetch_assoc($qMov)){

    if(strtoupper($m['tipo']) == 'APORTE'){
        $saldoTemp += $m['valor'];
        $tipo = "<span class='text-success'>⬆️ APORTE</span>";
    }else{
        $saldoTemp -= $m['valor'];
        $tipo = "<span class='text-danger'>⬇️ RETIRO</span>";
    }

    echo "<tr>
        <td>{$m['fecha']}</td>
        <td>$tipo</td>
        <td>$ ".f($m['valor'])."</td>
        <td><b>$ ".f($saldoTemp)."</b></td>
    </tr>";
}
?>

</tbody>
</table>

</div>
</div>

<a href="inversionistas.php" class="btn btn-secondary">← Volver</a>

</div>

<?php include "../includes/footer.php"; ?>