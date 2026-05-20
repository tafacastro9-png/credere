<?php 
include "../includes/configSession.php";
require_once "../includes/db.php";
include "../includes/header.php"; 



ini_set('display_errors', 1);
error_reporting(E_ALL);

$id = $_GET['id'] ?? 0;

// 🔹 TRAER DATOS
$sql = mysqli_query($conexion,"
    SELECT * FROM inversionistas WHERE id='$id'
");
$inv = mysqli_fetch_assoc($sql);

// 🔹 CAPITAL
$queryCapital = mysqli_query($conexion,"
    SELECT IFNULL(SUM(
        CASE 
            WHEN UPPER(tipo)='APORTE' THEN valor

            WHEN UPPER(tipo)='RETIRO' 
            AND medio_pago = 'LIQUIDACION'
            THEN -valor

            ELSE 0
        END
    ),0) as total
    FROM movimientos_inversionista
    WHERE id_inversionista='$id'
");




$capital = mysqli_fetch_assoc($queryCapital)['total'] ?? 0;
$capital = max(0, $capital);

// 🔹 INTERESES GENERADOS
$queryInteres = mysqli_query($conexion,"
    SELECT IFNULL(SUM((valor * tasa / 100) * meses),0) as total
    FROM movimientos_inversionista
    WHERE id_inversionista='$id'
    AND UPPER(tipo)='APORTE'
");

$interesGenerado = mysqli_fetch_assoc($queryInteres)['total'] ?? 0;


// 🔹 INTERESES YA PAGADOS
$queryInteresPagado = mysqli_query($conexion,"
    SELECT IFNULL(SUM(interes),0) as total
    FROM movimientos_inversionista
    WHERE id_inversionista='$id'
    AND UPPER(tipo)='RETIRO'
    AND interes > 0
");

$interesPagado = mysqli_fetch_assoc($queryInteresPagado)['total'] ?? 0;


// 🔹 INTERÉS DISPONIBLE
$interesDisponible = max(0, $interesGenerado - $interesPagado);

// 🔥 FRECUENCIAS (NUEVO)
$frecuencias = mysqli_query($conexion,"SELECT * FROM frecuencia_pago");
?>

<style>

	
.table-dark th {
    color: #fff !important;
    background: #1f2937 !important; /* más elegante */
    border: none;
}

/* 🔹 CONTENEDOR GENERAL */
.card-style {
    background: #ffffff;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
}

/* 🔹 RESUMEN (CARDS) */
.resumen-card {
    border-radius: 12px;
    padding: 14px;
    background: #f9fafb;
    border: 1px solid #eef1f4;
    text-align: center;
    transition: all 0.2s ease;
}

.resumen-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(0,0,0,0.08);
}

.resumen-card small {
    display: block;
    font-size: 11px;
    color: #6b7280;
    letter-spacing: .4px;
}

.resumen-card h6 {
    margin: 4px 0 0;
    font-size: 16px;
    font-weight: 600;
}

.resumen-card h5 {
    margin: 4px 0 0;
    font-size: 20px;
    font-weight: 700;
}

/* 🔹 COLORES TIPO FINANCIERO */
.text-primary { color: #2563eb !important; }   /* azul elegante */
.text-success { color: #16a34a !important; }   /* verde dinero */
.text-danger  { color: #dc2626 !important; }   /* rojo alerta */
.text-dark    { color: #111827 !important; }

/* 🔹 BOTONES */
.btn {
    border-radius: 8px !important;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-danger {
    background: #dc2626;
    border: none;
}

.btn-danger:hover {
    background: #b91c1c;
}

.btn-outline-success {
    border-color: #16a34a;
    color: #16a34a;
}

.btn-outline-success:hover {
    background: #16a34a;
    color: #fff;
}

.btn-outline-primary {
    border-color: #2563eb;
    color: #2563eb;
}

.btn-outline-primary:hover {
    background: #2563eb;
    color: #fff;
}

/* 🔹 INPUTS */
.form-control {
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    box-shadow: none;
    font-size: 14px;
}

.form-control:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37,99,235,0.1);
}

/* 🔹 TABLA */
.table {
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
}

.table td, .table th {
    vertical-align: middle;
    font-size: 13px;
}

.table tbody tr:hover {
    background: #f9fafb;
}

/* 🔹 BADGE PAGADO */
.badge, .label, .estado-pagado {
    background: #16a34a !important;
    color: #fff !important;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
}

/* 🔹 ESPACIADOS GENERALES */
hr {
    border-top: 1px solid #e5e7eb;
}

label {
    font-size: 13px;
    color: #374151;
    margin-bottom: 4px;
}

/* 🔹 BOTÓN PRINCIPAL */
.btn-primary {
    background: #111827;
    border: none;
}

.btn-primary:hover {
    background: #000;
}
	/* 🔹 MEJORAR CONTENEDOR GENERAL */
body {
    background: #f4f6f9;
}

/* 🔹 HEADER (Nombre + documento) */
h5 {
    font-weight: 600;
    color: #111827;
}

p {
    margin-bottom: 4px;
    color: #4b5563;
}

/* 🔹 CARDS MÁS "FINTECH" */
.resumen-card {
    background: #ffffff;
    border: 1px solid #eef2f7;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
}

/* 🔹 NUMEROS MÁS LIMPIOS */
.resumen-card h6 {
    font-size: 15px;
    letter-spacing: 0.3px;
}

.resumen-card h5 {
    font-size: 22px;
    letter-spacing: 0.5px;
}

/* 🔹 EFECTO SUAVE */
.resumen-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.06);
}

/* 🔹 BOTONES MÁS PROFESIONALES */
.btn {
    font-size: 12.5px;
    padding: 6px 14px;
}

.btn-danger {
    background: #dc2626;
}

.btn-outline-success {
    border-width: 1.5px;
}

.btn-outline-primary {
    border-width: 1.5px;
}

/* 🔹 INPUTS MÁS FINOS */
.form-control {
    height: 38px;
}

/* 🔹 TABLA MÁS PREMIUM */
.table {
    border-radius: 14px;
    overflow: hidden;
}

.table thead {
    background: #1f2937;
}

.table th {
    font-weight: 500;
    letter-spacing: 0.3px;
}

.table td {
    padding: 10px 8px;
}

/* 🔹 FILA HOVER MÁS SUAVE */
.table tbody tr:hover {
    background: #f3f4f6;
}

/* 🔹 FECHA MÁS DISCRETA */
.table td:first-child {
    font-size: 12px;
    color: #6b7280;
}

/* 🔹 ESTADO PAGADO MÁS FINO */
.estado-pagado,
.badge-success,
.badge {
    background: #16a34a !important;
    font-size: 11px;
    padding: 3px 10px;
}

/* 🔹 BOTÓN REGISTRAR MÁS ELEGANTE */
.btn-primary {
    background: #0f172a;
}

.btn-primary:hover {
    background: #020617;
}

/* 🔹 ESPACIADO GENERAL */
.container-fluid {
    padding-top: 10px;
}

/* 🔥 MÁS AIRE ENTRE BLOQUES */
.card-style {
    padding: 25px;
}

/* 🔹 HEADER MÁS MARCADO */
h5 {
    font-size: 18px;
    margin-bottom: 10px;
}

/* 🔹 SEPARACIÓN DEL RESUMEN */
.resumen-card {
    transition: all 0.2s ease;
    border: 1px solid #f1f5f9;
}

/* 🔥 DAR IMPORTANCIA AL TOTAL */
#total {
    font-size: 20px !important;
    font-weight: 700;
}

/* 🔹 CAPITAL MÁS SUAVE */
#capital {
    opacity: 0.85;
}

/* 🔹 COLORES MÁS FINOS (menos chillones) */
.text-primary {
    color: #2563eb !important;
}

.text-danger {
    color: #dc2626 !important;
}

.text-success {
    color: #16a34a !important;
}

/* 🔥 BOTONES MEJOR DISTRIBUIDOS */
.d-flex.gap-2 {
    margin-top: 5px;
}

/* 🔹 BOTÓN PRINCIPAL DESTACADO */
.btn-danger {
    box-shadow: 0 4px 10px rgba(220,38,38,0.2);
}

/* 🔹 BOTONES SECUNDARIOS MÁS SUAVES */
.btn-outline-success,
.btn-outline-primary {
    background: #fff;
}

/* 🔥 INPUTS MÁS LIMPIOS */
.form-control {
    border: 1px solid #e5e7eb;
}

/* 🔹 TABLA MÁS PRO */
.table {
    margin-top: 10px;
    background: #fff;
}

/* 🔥 HEADER TABLA MÁS FINO */
.table-dark {
    background: #111827 !important;
}

/* 🔹 CELDAS MÁS RESPIRABLES */
.table td, .table th {
    vertical-align: middle;
}

/* 🔥 BOTÓN "+" MÁS MODERNO */
button:has(+ td) {
    border-radius: 8px !important;
}
/* 🔥 CONTENEDOR PRINCIPAL */
.card-style {
    background: #f8fafc;
    border-radius: 16px;
    padding: 25px;
}

/* 🔹 TITULO */
h5 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 5px;
}

/* 🔹 TEXTO GENERAL */
p {
    margin-bottom: 5px;
    color: #6b7280;
}

/* 🔥 CARDS (RESUMEN) */
.resumen-card {
    border-radius: 14px;
    padding: 16px;
    background: #ffffff;
    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
    text-align: center;
    transition: all 0.2s ease;
}

.resumen-card:hover {
    transform: translateY(-2px);
}

/* 🔹 TITULO CARD */
.resumen-card small {
    display: block;
    font-size: 12px;
    color: #9ca3af;
    margin-bottom: 4px;
}

/* 🔹 VALORES */
.resumen-card h6 {
    font-size: 16px;
    font-weight: 600;
}

.resumen-card h5 {
    font-size: 20px;
    font-weight: 700;
}

/* 🔥 COLORES FINOS */
.text-primary { color: #2563eb !important; }
.text-danger { color: #dc2626 !important; }
.text-success { color: #16a34a !important; }

/* 🔥 BOTONES */
.btn-danger {
    background: #dc2626;
    border: none;
    box-shadow: 0 4px 10px rgba(220,38,38,0.2);
}

.btn-danger:hover {
    background: #b91c1c;
}

.btn-outline-success,
.btn-outline-primary {
    border-radius: 10px;
    font-weight: 500;
}

/* 🔥 FORMULARIO */
.form-control {
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    padding: 10px;
}

/* 🔹 LABELS */
label {
    font-size: 13px;
    font-weight: 500;
    color: #374151;
}

/* 🔥 BOTÓN REGISTRAR */
.btn-primary {
    background: #0f172a;
    border: none;
    border-radius: 10px;
}

.btn-primary:hover {
    background: #020617;
}

/* 🔥 TABLA */
.table {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
}

/* 🔹 HEADER TABLA */
.table-dark {
    background: #1e293b !important;
}

/* 🔹 FILAS */
.table td {
    vertical-align: middle;
    font-size: 14px;
}

/* 🔹 EFECTO HOVER */
.table tbody tr:hover {
    background: #f1f5f9;
}

/* 🔥 BADGE PAGADO */
.badge, .btn-success {
    border-radius: 20px !important;
}

/* 🔹 SEPARADORES */
hr {
    opacity: 0.1;
}
#total {
    font-size: 22px !important;
    font-weight: 700;
    color: #0f172a;
}
.btn-danger {
    font-weight: 600;
    letter-spacing: 0.3px;
}
.btn-success {
    font-size: 12px;
    padding: 4px 10px;
}
	
</style>

<section class="table-components">
<div class="container-fluid">

<br><br>

<div class="card-style">

<h5>Detalle Inversionista</h5>

<p><b>Nombre:</b> <?= $inv['nombre'] ?></p>
<p><b>Documento:</b> <?= $inv['documento'] ?></p>
<div class="row g-3">



  <div class="row mt-4 mb-4 g-4">

    <div class="col-md-2">
          <div class="resumen-card">
            <small>Capital</small>
            <h6 class="text-dark mb-0" id="capital">
                <?= number_format($capital,0,',','.') ?>
            </h6>
        </div>
    </div>

    <div class="col-md-3">
       <div class="resumen-card">
            <small>Intereses generados</small>
            <h6 class="text-primary mb-0" id="interes_generado">
                <?= number_format($interesGenerado,0,',','.') ?>
            </h6>
        </div>
    </div>

    <div class="col-md-3">
       <div class="resumen-card">
            <small>Intereses retirados</small>
            <h6 class="text-danger mb-0" id="interes_retirado">
                <?= number_format($interesPagado,0,',','.') ?>
            </h6>
        </div>
    </div>

    <div class="col-md-2">
     <div class="resumen-card">
            <small>Disponibles</small>
            <h6 class="text-success mb-0" id="intereses">
                <?= number_format($interesDisponible,0,',','.') ?>
            </h6>
        </div>
    </div>

    <div class="col-md-2">
       <div class="resumen-card">
            <small>Total</small>
            <h5 class="text-dark mb-0" id="total">
                <?= number_format($capital + $interesDisponible,0,',','.') ?>
            </h5>
        </div>
    </div>

</div>

<div class="d-flex gap-2 mt-3 mb-2 flex-wrap">

    <!-- 🔴 ACCIÓN PRINCIPAL -->
    <button 
        class="btn btn-danger btn-sm px-3"
        onclick="liquidarTodo()"
    >
        ⚡ Liquidar todo
    </button>

    <!-- 🟢 SECUNDARIOS -->
    <button 
        class="btn btn-outline-success btn-sm px-3"
        onclick="liquidarTodoIntereses()"
    >
        💸 Solo intereses
    </button>

    <button 
        class="btn btn-outline-primary btn-sm px-3"
        onclick="liquidarCapitalTodo()"
    >
        💰 Solo capital
    </button>

</div>

<hr>

<form id="formMovimiento">

<input type="hidden" name="id_inversionista" value="<?= $id ?>">

<div class="row g-3">

<div class="col-md-3">
<label>Valor</label>
<input type="number" name="valor" class="form-control" required>
</div>

<div class="col-md-3">
<label>Tipo</label>
<select name="tipo" id="tipo" class="form-control">
<option value="APORTE">APORTE</option>
<option value="RETIRO">RETIRO</option>
</select>
</div>

<div class="col-md-3">
<label>Medio de pago</label>
<select name="medio_pago" id="medio_pago" class="form-control">
<option value="">Seleccione</option>
<option value="EFECTIVO">EFECTIVO</option>
<option value="TRANSFERENCIA">TRANSFERENCIA</option>
</select>
</div>

<div class="col-md-3 d-none" id="divBanco">
<label>Banco</label>
<select name="banco" class="form-control">
<option value="">Seleccione</option>
<option>Bancolombia</option>
<option>Davivienda</option>
<option>BBVA</option>
<option>Banco de Bogotá</option>
<option>Banco de Occidente</option>
<option>Nequi</option>
<option>Daviplata</option>
</select>
</div>

<div class="col-md-3 d-none" id="divCuenta">
<label>Número de cuenta</label>
<input type="text" name="numero_cuenta" class="form-control">
</div>

<div class="col-md-2 d-none" id="divTasa">
<label>Tasa (%)</label>
<input type="number" step="0.01" name="tasa" class="form-control">
</div>

<div class="col-md-2 d-none" id="divMeses">
<label>Meses</label>
<input type="number" name="meses" class="form-control">
</div>

<!-- 🔥 PERIODICIDAD DESDE BD -->
<div class="col-md-3 d-none" id="divFrecuencia">
<label>Periodicidad</label>
<select name="frecuencia_pago_id" class="form-control">
<option value="">Seleccione</option>

<?php while($fp = mysqli_fetch_assoc($frecuencias)){ ?>
    <option value="<?= $fp['id'] ?>">
        <?= $fp['frecuencia'] ?>
    </option>
<?php } ?>

</select>
</div>

<div class="col-md-12">
<button type="button" class="btn btn-primary" onclick="guardarMovimiento()">
➕ Registrar Movimiento
</button>
</div>

</div>

</form>

<hr>

<div class="table-responsive">
<table class="table table-bordered">

<thead class="table-dark">
<tr>
<th></th>
<th>Fecha</th>
<th>Tipo</th>
<th>Valor</th>
<th>Saldo</th>
<th>Medio</th>
<th>Banco</th>
<th>Cuenta</th>
<th>Tasa</th>
<th>Meses</th>
<th>Frecuencia</th>
<th>Próx. Pago</th>
<th>Acción</th>
</tr>
</thead>

<tbody id="tablaMovimientos"></tbody>

</table>
</div>

</div>
</div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

function cargarMovimientos(){

    $("#tablaMovimientos").html(`
        <tr>
            <td colspan="12" class="text-center">⏳ Cargando...</td>
        </tr>
    `);

    $.post("../ajax/inversionistasAjax.php", {
        accion:"movimientos",
        id: <?= $id ?>
    }, function(data){

        $("#tablaMovimientos").html(data);

    });

}

function cargarSaldo(){

    $.post("../ajax/inversionistasAjax.php", {
        accion: "saldo",
        id: <?= $id ?>
    }, function(data){

        let valor = parseFloat(data.replace(/\./g,'')) || 0;
        let color = valor >= 0 ? "green" : "red";

        $("#saldo").html(`<span style="color:${color}; font-weight:bold;">${data}</span>`);

    });

}

function guardarMovimiento(){

    let tipo = $("select[name='tipo']").val();
    let metodo = $("select[name='medio_pago']").val();
    let banco = $("select[name='banco']").val();
    let cuenta = $("input[name='numero_cuenta']").val();
    let meses = $("input[name='meses']").val();
    let tasa = $("input[name='tasa']").val();
    let frecuencia = $("select[name='frecuencia_pago_id']").val();

    if(tipo === "APORTE"){

        if(!metodo){
            Swal.fire("Error", "Selecciona el método de pago", "error");
            return;
        }

        if(metodo === "TRANSFERENCIA"){
            if(!banco || !cuenta){
                Swal.fire("Error", "Debes ingresar banco y número de cuenta", "error");
                return;
            }
        }

        if(!meses || !tasa || !frecuencia){
            Swal.fire("Error", "Debes completar tasa, meses y periodicidad", "error");
            return;
        }
    }

    let form = $("#formMovimiento").serialize();

    $.post("../ajax/inversionistasAjax.php", form + "&accion=movimiento", function(res){

        if(res === "error_saldo"){
            Swal.fire("Error", "No puedes retirar más del saldo disponible", "error");
            return;
        }

        Swal.fire("OK", "Movimiento registrado", "success");
$("#formMovimiento")[0].reset();

$("#tipo").trigger("change"); // 👈 ESTA LÍNEA ES LA CLAVE
$("#medio_pago").trigger("change");

cargarMovimientos();
cargarSaldo();
actualizarTotales(); // 🔥 ESTA ES LA LÍNEA QUE FALTABA

    });

}

$(document).ready(function(){

    cargarMovimientos();
    cargarSaldo();
    actualizarTotales();
    $("#tipo").trigger("change");
    $("#medio_pago").trigger("change");

});

$("#medio_pago").change(function(){

    let metodo = $(this).val();

    if(metodo === "TRANSFERENCIA"){
        $("#divBanco").removeClass("d-none");
        $("#divCuenta").removeClass("d-none");
    }else{
        $("#divBanco").addClass("d-none");
        $("#divCuenta").addClass("d-none");
    }

});

$("#tipo").change(function(){

    let tipo = $(this).val();

    if(tipo === "APORTE"){
        $("#divTasa").removeClass("d-none");
        $("#divMeses").removeClass("d-none");
        $("#divFrecuencia").removeClass("d-none");
    }else{
        $("#divTasa").addClass("d-none");
        $("#divMeses").addClass("d-none");
        $("#divFrecuencia").addClass("d-none");
    }

});


function liquidar(id){ 

    $.post("../ajax/inversionistasAjax.php", {
        accion: "calcular_liquidacion",
        id: id
    }, function(res){

        let data = JSON.parse(res);

        // 🔥 construir interés correctamente
        let interesHTML = "";

        if(data.ya_pagado){
            interesHTML = "<b class='text-success'>Interés: $0 (YA PAGADO)</b>";
        }else{
            interesHTML = "<b class='text-success'>Interés: $" + data.interes + "</b>";
        }

        Swal.fire({
            title: "Liquidar inversión",
            html: `
                <b>Capital:</b> $${data.valor}<br>
                <b>Tasa:</b> ${data.tasa}%<br>
                <b>Meses:</b> ${data.meses}<br><br>

                ${interesHTML}

                <b style="color:blue;">Total:</b> $${data.total}
            `,
            showCancelButton: true,
            confirmButtonText: "💰 Liquidar"
        }).then((result) => {

            if(result.isConfirmed){

            $.post("../ajax/inversionistasAjax.php", {
    accion: "liquidar",
    id: id
}, function(res){

    console.log("RESPUESTA:", res); // 👈 para depurar

    res = (res || "").trim();

    if(res.includes("error_saldo_liquidacion")){
        Swal.fire(
            "Error",
            "No tienes capital suficiente para retirar este aporte. Debes hacer un retiro por el valor disponible.",
            "error"
        );
        return;
    }

    if(res.includes("ya_liquidado")){
        Swal.fire("Aviso", "Esta inversión ya fue liquidada", "warning");
        return;
    }

    if(res.includes("ok")){
        Swal.fire("OK", "Inversión liquidada correctamente", "success");
        cargarMovimientos();
        cargarSaldo();
        actualizarTotales();
        return;
    }

    Swal.fire("Error", "Ocurrió un problema inesperado", "error");

});

            }

        });

    });

}
function liquidarTodo(){ 

    // 🔹 PRIMERO CALCULA
    $.post("../ajax/inversionistasAjax.php", {
        accion: "calcular_liquidacion_total",
        id: <?= $id ?>
    }, function(res){

        if(!res){
            Swal.fire("Error", "No se pudo calcular la liquidación", "error");
            return;
        }

        let data;

        try{
            data = (typeof res === "object") ? res : JSON.parse(res);
        }catch(e){
            console.error("Respuesta inválida:", res);
            Swal.fire("Error", "Error procesando datos", "error");
            return;
        }

        // 🔥 TRAER SALDO REAL
        $.post("../ajax/inversionistasAjax.php", {
            accion: "saldo",
            id: <?= $id ?>
        }, function(saldoRes){

            let saldoReal = (typeof saldoRes === "string") ? saldoRes.trim() : saldoRes;
			// 🔥 CALCULAR TOTAL REAL (AQUÍ VA)
let interesDisponible = parseInt((data.interes_disponible || "0").replace(/\./g,'')) || 0;
let saldoNumero = parseInt((saldoReal || "0").replace(/\./g,'')) || 0;

let totalRetirar = saldoNumero + interesDisponible;

            if(saldoReal === "0" || saldoReal === "" || saldoReal == 0){
                Swal.fire("Aviso", "No hay saldo disponible", "info");
                return;
            }

            // 🔥 RESUMEN
            Swal.fire({
                title: "💣 Liquidación Total",
                html: `
                    <div style="text-align:left;">
                        <b>Capital invertido:</b> $${data.capital}<br><br>

                        <b>Intereses generados:</b> $${data.interes}<br>
                        <b>Intereses retirados:</b> $${data.interes_retirado || '0'}<br>
                        <b>Intereses disponibles:</b> $${data.interes_disponible || '0'}<br>

                        <hr>

                        <b style="color:green;">
                            Saldo disponible: $${saldoReal}
                        </b><br>

                        <b style="color:red; font-size:16px;">
                            TOTAL A RETIRAR: ${totalRetirar.toLocaleString('es-CO')}
                        </b>
                    </div>
                `,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "💣 Sí, liquidar todo"
            }).then((result) => {

                if(result.isConfirmed){

                    // 🔹 EJECUTAR LIQUIDACIÓN
                    $.post("../ajax/inversionistasAjax.php", {
                        accion: "liquidar_todo",
                        id: <?= $id ?>
                    }, function(res){

                        // 🔥 MANEJO CORRECTO (SIN ROMPER)
                        if(typeof res === "object"){

                            if(res.estado === "sin_aportes"){
                                Swal.fire("Aviso", "No hay inversiones activas", "info");
                                return;
                            }

                            if(res.estado === "sin_saldo"){
                                Swal.fire("Error", "No hay saldo suficiente", "error");
                                return;
                            }

                            if(res.estado === "ok"){
                                Swal.fire("OK", "Todo fue liquidado correctamente", "success");
                                cargarMovimientos();
                                cargarSaldo();
                                return;
                            }

                        }else{

                            res = res.trim();

                            if(res === "sin_aportes"){
                                Swal.fire("Aviso", "No hay inversiones activas", "info");
                                return;
                            }

                            if(res === "sin_saldo"){
                                Swal.fire("Error", "No hay saldo suficiente", "error");
                                return;
                            }

                            if(res === "ok"){
                                Swal.fire("OK", "Todo fue liquidado correctamente", "success");
                                cargarMovimientos();
                                cargarSaldo();
								actualizarTotales();
                                return;
                            }

                        }

                        Swal.fire("Error", "Ocurrió un problema", "error");

                    });

                }

            });

        });

    });

}

function toggleMovimientos(aporte_id){

    let fila = $("#detalle_"+aporte_id);

    if(fila.length){
        fila.remove(); // cerrar
        return;
    }

    $.post("../ajax/inversionistasAjax.php", {
        accion: "movimientos_aporte",
        aporte_id: aporte_id
    }, function(data){

        let html = `
        <tr id="detalle_${aporte_id}">
            <td colspan="12">
                ${data}
            </td>
        </tr>
        `;

        $("#fila_"+aporte_id).after(html);

    });

}

function verMovimientosAporte(btn, id){

    let fila = $("#detalle_" + id);

    // 🔹 Si ya existe → solo mostrar/ocultar
    if(fila.length){
        fila.toggle();

        // 🔹 Cambiar icono + / -
        if(fila.is(":visible")){
            $(btn).html("−");
        }else{
            $(btn).html("+");
        }

        return;
    }

    // 🔹 Crear fila temporal (loading)
    let html = `
        <tr id="detalle_${id}">
            <td colspan="12" class="text-center">
                ⏳ Cargando movimientos...
            </td>
        </tr>
    `;

    // 🔹 Insertar justo debajo del registro correcto
    $(btn).closest("tr").after(html);

    // 🔹 Bloquear botón mientras carga (evita doble click)
    $(btn).prop("disabled", true);

    // 🔹 Llamada AJAX
    $.post("../ajax/inversionistasAjax.php", {
        accion: "movimientos_aporte",
        aporte_id: id
    }, function(data){

        $("#detalle_" + id).html(`
            <td colspan="12">
                ${data}
            </td>
        `);

        // 🔹 Cambiar a "-"
        $(btn).html("−");

    }).fail(function(){

        $("#detalle_" + id).html(`
            <td colspan="12" class="text-danger text-center">
                ❌ Error cargando movimientos
            </td>
        `);

    }).always(function(){
        // 🔹 Rehabilitar botón
        $(btn).prop("disabled", false);
    });

}

function liquidarInteres(id){

    $.post("../ajax/inversionistasAjax.php", {
        accion: "calcular_interes",
        id: id
    }, function(res){

        let data = JSON.parse(res);

        Swal.fire({
            title: "💸 Liquidar interés",
            html: `
                <b>Capital:</b> $${data.valor}<br>
                <b>Tasa:</b> ${data.tasa}%<br>
                <b>Meses:</b> ${data.meses}<br><br>

                <b style="color:green;">Interés:</b> $${data.interes}
            `,
            showCancelButton: true,
            confirmButtonText: "💸 Pagar interés"
        }).then((result) => {

            if(result.isConfirmed){

                $.post("../ajax/inversionistasAjax.php", {
                    accion: "liquidar_interes",
                    id: id
                }, function(res){

                    res = res.trim();

                    if(res === "ok"){
                        Swal.fire("OK", "Interés pagado correctamente", "success");
                        cargarMovimientos();
                        cargarSaldo();
						actualizarTotales();
                        return;
                    }

                    if(res === "ya_pagado"){
                        Swal.fire("Aviso", "Este interés ya fue pagado", "warning");
                        return;
                    }

                    Swal.fire("Error", "Ocurrió un problema", "error");

                });

            }

        });

    });

}

function actualizarTotales(){

    $.post("../ajax/inversionistasAjax.php", {
        accion:"calcular_liquidacion_total",
        id: <?= $id ?>
    }, function(r){

        let data = JSON.parse(r);

        let capital = Math.max(0, parseInt(data.capital.replace(/\./g,'')) || 0);
$("#capital").text(capital.toLocaleString('es-CO'));
        $("#intereses").text(data.interes_disponible);
        $("#interes_generado").text(data.interes);
        $("#interes_retirado").text(data.interes_retirado); // 🔥 ESTE

        let interes = parseInt(data.interes_disponible.replace(/\./g,'')) || 0;

$("#total").text(
    (capital + interes).toLocaleString('es-CO')
);

    });

}

function liquidarTodoIntereses(){

    $.post("../ajax/inversionistasAjax.php", {
        accion: "calcular_liquidacion_total",
        id: <?= $id ?>
    }, function(res){

        let data;

        try{
            data = (typeof res === "object") ? res : JSON.parse(res);
        }catch(e){
            console.error("Error parseando:", res);
            Swal.fire("Error", "No se pudo procesar la información", "error");
            return;
        }

        let interesDisponible = parseInt((data.interes_disponible || "0").replace(/\./g,'')) || 0;

        if(interesDisponible <= 0){
            Swal.fire("Aviso", "No hay intereses disponibles", "info");
            return;
        }

        Swal.fire({
            title: "💸 Liquidar intereses",
            html: `
                <div style="text-align:left;">
                    <b>Intereses generados:</b> $${data.interes}<br>
                    <b>Intereses retirados:</b> $${data.interes_retirado || '0'}<br>
                    <b style="color:green;">
                        Intereses disponibles: $${data.interes_disponible}
                    </b>
                </div>
            `,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "💸 Sí, retirar intereses"
        }).then((result) => {

            if(result.isConfirmed){

                $.post("../ajax/inversionistasAjax.php", {
                    accion: "liquidar_intereses_todo",
                    id: <?= $id ?>
                }, function(res){

                    res = (typeof res === "string") ? res.trim() : res;

                    // ✅ NUEVO: SIN VENCIDOS
                    if(res === "sin_vencidos"){
                        Swal.fire("Aviso", "No hay intereses vencidos para pagar", "info");
                        return;
                    }

                    // ✅ OK
                    if(res === "ok"){
                        Swal.fire("OK", "Intereses retirados correctamente", "success");
                        cargarMovimientos();
                        cargarSaldo();
                        actualizarTotales();
                        return;
                    }

                    // ❌ ERROR REAL
                    Swal.fire("Error", "Ocurrió un problema", "error");

                });

            }

        });

    });

}

function liquidarCapitalTodo(){

    $.post("../ajax/inversionistasAjax.php", {
        accion: "calcular_liquidacion_total",
        id: <?= $id ?>
    }, function(res){

        let data;

        try{
            data = (typeof res === "object") ? res : JSON.parse(res);
        }catch(e){
            Swal.fire("Error", "No se pudo procesar la información", "error");
            return;
        }

        let capital = parseInt((data.capital || "0").replace(/\./g,'')) || 0;

        if(capital <= 0){
            Swal.fire("Aviso", "No hay capital disponible", "info");
            return;
        }

        Swal.fire({
            title: "💰 Retirar capital",
            html: `
                <div style="text-align:left;">
                    <b>Capital disponible:</b><br>
                    <b style="color:green;">$${data.capital}</b>
                </div>
            `,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "💰 Sí, retirar capital"
        }).then((result) => {

            if(result.isConfirmed){

                $.post("../ajax/inversionistasAjax.php", {
                    accion: "liquidar_capital_todo",
                    id: <?= $id ?>
                }, function(res){

                    res = (typeof res === "string") ? res.trim() : res;

                    if(res === "sin_vencidos"){
                        Swal.fire("Aviso", "No hay capital vencido para retirar", "info");
                        return;
                    }

                    if(res === "ok"){
                        Swal.fire("OK", "Capital retirado correctamente", "success");
                        cargarMovimientos();
                        cargarSaldo();
                        actualizarTotales();
                        return;
                    }

                    Swal.fire("Error", "Ocurrió un problema", "error");
                });

            }

        });

    });

}

</script>

