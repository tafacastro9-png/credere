<?php
include "../includes/header.php";
include "../includes/db.php";
date_default_timezone_set('America/Bogota');
$fechaHoy = date("Y-m-d");
?>

<?php
function obtenerParametro($conexion, $nombre){
    $q = mysqli_query($conexion, "
        SELECT valor 
        FROM parametros 
        WHERE nombre = '$nombre'
        LIMIT 1
    ");
    
    $row = mysqli_fetch_assoc($q);
    return floatval($row['valor'] ?? 0);
}

$seguro_porcentaje = obtenerParametro($conexion, 'SEGURO_CREDITO');
?>

<!-- 1. jQuery primero -->


<!-- 2. Select2 después -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

<!-- 3. Tu JS personalizado -->
<script src="../js/generetePrest.js"></script>


<!-- ========== table components start ========== -->
<section class="table-components">
<div class="container-fluid px-4">

<div class="row justify-content-center">
<div class="col-xl-11">

<div class="card shadow-lg border-0 rounded-4">
<div class="card-body p-4">

<!-- HEADER -->
<div class="titulo-modulo">
    <i class="fa fa-file-invoice-dollar me-2"></i>
    <span>Gestión de Créditos</span>
</div>
         
	
	
	




<form id="formPrestamo" method="POST">

<!-- ================= DATOS DEL CLIENTE ================= -->
<div class="card border-0">
<div class="card-body">

<h6 class="fw-bold titulo-seccion mb-3">
📌 Datos del Cliente
</h6>

<div class="row g-4">

<div class="col-md-6">
<label class="form-label fw-semibold">Cliente</label>
<select id="cliente_busqueda" name="id_cliente"
class="form-control select2" style="width:100%;"></select>
</div>

<div class="col-md-6">
<label class="form-label fw-semibold">Tipo de Crédito</label>
<select id="id_tipo_credito"
name="id_tipo_credito"
class="form-select"
>
<option value="">Seleccione tipo</option>

<?php
$tipos = mysqli_query($conexion, "SELECT * FROM tipo_credito ORDER BY nombre ASC");
while($tp = mysqli_fetch_assoc($tipos)):
?>

<option 
value="<?= $tp['id']; ?>"
data-plazo="<?= $tp['plazo_dias']; ?>"
data-tasa="<?= $tp['tasa_interes']; ?>"
data-factor="<?= $tp['factor']; ?>"
data-frec="<?= $tp['frecuencia_pago']; ?>"
data-multa="<?= $tp['multa_mora']; ?>"
data-tipo-proyeccion="<?= $tp['tipo_proyeccion']; ?>"
>
<?= $tp['nombre']; ?>
</option>

<?php endwhile; ?>

</select>
</div>

</div>

</div>
</div>


<!-- ================= REFERENCIAS ================= -->
<div class="card border-0 shadow-sm mb-4">
<div class="card-body">

<h6 class="fw-bold titulo-seccion mb-3">
👥 Referencias
</h6>

<div class="row g-4">

<div class="col-md-6">
<label class="form-label fw-semibold">Referencia Personal</label>
<select id="aval_busqueda"
name="id_aval"
class="form-control select2"
style="width:100%;"></select>
</div>

<div class="col-md-6">
<label class="form-label fw-semibold">Referencia Familiar</label>
<select id="aval_busquedafamiliar"
name="id_avalFamiliar"
class="form-control select2"
style="width:100%;"></select>
</div>


</div>
</div>
</div>

<!-- ================= CONFIGURACIÓN DEL CRÉDITO ================= -->
<div class="card border-0 shadow-sm mb-4">
<div class="card-body">

<h6 class="fw-bold titulo-seccion mb-3">
💰 Configuración del Crédito
</h6>

<div class="row g-4">

<div class="col-md-6">
    <label class="form-label">Tipo de proyección</label>
                            <select id="tipo_proyeccion" name="tipo_proyeccion" class="form-control" required>
                                <option value="">Seleccione</option>
                                   <?php
                                //Consulta para llamar a las categorias 
                                include "../includes/db.php";
                                $sql = "SELECT * FROM tipo_proyeccion";
                                $resultado = mysqli_query($conexion, $sql);
                                while ($consulta = mysqli_fetch_array($resultado)) {
                                    echo '<option value="' . $consulta['id'] . '">' . $consulta['nombre'] . '</option>';
                                }
                                ?>
                            </select>
</div>

  <!-- BLOQUE SIMPLE -->
                    <div id="bloque_simple" style="display:none;">
                        <div class="row g-3">

                             <div class="col-md-6">
                            <label for="nombre_tipo" class="form-label">Nombre del Tipo</label>
                            <input type="text" class="form-control" id="nombre_tipo" name="nombre_tipo" placeholder="Ej. Personal, Emprendedor" required>
                        </div>


<div class="col-md-6">
    <label class="form-label">Tasa Interés Mensual (%)</label>
    <input type="number" class="form-control" id="tasa_mensual_simple" step="0.01" placeholder="Ej. 2.5">
</div>

<div class="col-md-6">
    <label class="form-label">Tasa de Interés Anual (%)</label>
    <input type="number" class="form-control" id="tasa_interes" name="tasa_interes" step="0.01" >
</div>

                            <div class="col-md-6">
                                <label class="form-label">Periodo de gracia</label>
                                <input type="number" class="form-control" name="periodo_gracia">
                            </div>

                            <div class="col-md-6">
                           <label for="plazo_meses" class="form-label">Plazo (meses)</label>
                            <input type="number" class="form-control" id="plazo_meses" name="plazo_meses" placeholder="Ej. 30, 60, 90" required>
                        </div>

                      
                        <div class="col-md-6">
                            <label for="frecuencia_pago" class="form-label">Frecuencia de Pago</label>
                            <select name="id_frp" id="id_frp" class="form-control" required>
                                <option value="">Selecciona una opcion</option>
                                <?php
                                //Consulta para llamar a las categorias 
                                include "../includes/db.php";
                                $sql = "SELECT * FROM frecuencia_pago";
                                $resultado = mysqli_query($conexion, $sql);
                                while ($consulta = mysqli_fetch_array($resultado)) {
                                    echo '<option value="' . $consulta['id'] . '">' . $consulta['frecuencia'] . '</option>';
                                }
                                ?>

                            </select>
                        </div>

                             <div class="col-md-6">
                            <label for="multa_mora" class="form-label">Multa por Mora (%)</label>
                            <input type="number" class="form-control" id="multa_mora" name="multa_mora" step="0.01" placeholder="Ej. 3.0" required>
                        </div>

                              <div class="col-md-6">
                            <label for="monto_maximo" class="form-label">Monto Máximo Permitido</label>
                            <input type="number" class="form-control" id="monto_maximo" name="monto_maximo" step="0.01" placeholder="Ej. 10000.00" required>
                        </div>

                        </div>
						  <div class="col-md-12 mt-3">
                            <label for="descripcion" class="form-label">Obervaciones</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Observaciones del credito"></textarea>
                        </div>
                    </div>
					
					 <!-- BLOQUE AMORTIZADO -->
                    <div id="bloque_amortizado" style="display:none;">
                        <div class="row g-3 mt-3">

                            <div class="col-md-6">
                                <label class="form-label">Nombre del tipo</label>
                                <input type="text" class="form-control" name="nombre_tipo_amort">
                            </div>

                         
                     
							   <div class="col-md-6">
                            <label for="tasa_anual_amor" class="form-label">Tasa de Interés Anual (%)</label>
                            <input type="number" class="form-control" id="tasa_anual_amor" name="tasa_anual_amor" step="0.01" placeholder="Ej. 5.5" readonly>
                        </div>

            
									   <div class="col-md-6">
                            <label for="tasa_mensual_amort" class="form-label">Tasa interés mensual (%)</label>
                            <input type="number" class="form-control" id="tasa_mensual_amort" name="tasa_mensual_amort" step="0.01" placeholder="Ej. 5.5" required>
                        </div>
							

                            <div class="col-md-6">
                                <label class="form-label">Plazo (meses)</label>
                                <input type="number" class="form-control" name="plazo_amort">
                            </div>

                                    <div class="col-md-6">
                            <label for="frecuencia_pago_amort" class="form-label">Frecuencia de Pago</label>
                            <select name="frecuencia_pago_amort" id="frecuencia_pago_amort" class="form-control" required>
                                <option value="">Selecciona una opcion</option>
                                <?php
                                //Consulta para llamar a las categorias 
                                include "../includes/db.php";
                                $sql = "SELECT * FROM frecuencia_pago";
                                $resultado = mysqli_query($conexion, $sql);
                                while ($consulta = mysqli_fetch_array($resultado)) {
                                    echo '<option value="' . $consulta['id'] . '">' . $consulta['frecuencia'] . '</option>';
                                }
                                ?>

                            </select>
                        </div>

                            <div class="col-md-6">
                                <label class="form-label">Multa por mora (%)</label>
                                <input type="number" class="form-control" name="multa_mora_amort">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Monto máximo</label>
                                <input type="number" class="form-control" name="monto_maximo_amort">
                            </div>
							
								  <div class="col-md-12">
                            <label for="descripcion_amort" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion_amort" name="descripcion_amort" rows="3" placeholder="Breve descripción del tipo de préstamo..."></textarea>
                        </div>

                        </div>
                    </div>

             
				
				

                <input type="hidden" name="accion" value="SaveTypePrest">




<div class="col-md-6">
<label class="form-label fw-semibold">Monto Prestado</label>
<input type="text"
class="form-control"
id="monto_prestado"
name="monto_prestado">
</div>

</div>

                            <!-- Info préstamo -->
                       <div id="info_prestamo" class="bg-light p-3 rounded border d-none">
    <h6>Detalles del tipo de préstamo:</h6>
    <ul class="mb-0">
        <li><strong>Descripción:</strong> <span id="info_desc"></span></li>
        <li><strong>Tasa Efectiva Anual:</strong> <span id="info_tasa"></span>%</li>
		<li id="tasa_mensual_container" class="d-none">
    <strong>Tasa Interés Mensual:</strong> <span id="tasa_Mensual"></span>
</li>
        <li><strong>Periodo de gracia:</strong> <span id="periodo_gracia_valor"></span></li>
        <li><strong>Plazo (meses):</strong> <span id="plazo_meses_text"></span></li>
        <li><strong>Frecuencia:</strong> <span id="info_frec"></span></li>
        <li><strong>Multa por mora:</strong> <span id="info_multa"></span>%</li>
        <li><strong>Monto máximo:</strong> $<span id="info_maximo"></span></li>
		
        <!-- Tasa de interés mensual para PROYECCIÓN AMORTIZANDO -->
       
</div>









                            <!-- Monto -->

                        <br>
						
						</div>
</div>


                        <!-- Fechas -->
                        <div class="card border-0 shadow-sm mb-2">
<div class="card-body">

<h6 class="fw-bold titulo-seccion mb-3">
📅 Fechas del Crédito
</h6>

<div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Fecha de inicio</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Fecha de Vencimiento</label>
                                <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" class="form-control" readonly >
                            </div>
                        </div>
						
						</div>
</div>

						
						<br>
						<br>



                        <br>
						
						<div class="card border-0 shadow-sm mb-0">
<div class="card-body">

<h6 class="fw-bold titulo-seccion mb-3">
🧮 Acciones
</h6>

                        <div class="d-flex gap-3 mt-2">
                           <button type="button"
            class="btn btn-primary btn-lg px-4"
            id="calcular_prestamo">
        🧮 Calcular Crédito
    </button>
                        </div>

                        <div class="mt-3 d-none" id="resultado_prestamo">
                            <ul class="list-group">
							     <li class="list-group-item">📌 Frecuencia de pago: <span id="frecuencia_pago"></span></li>
								 
								 
						
								 
								 
								 <li class="list-group-item" id="factor_container">
💰 Factor: <span id="factor_valor"></span>
</li>
								
								 
								 
								 
								 
								 
<li class="list-group-item">
    📆 Número de cuotas: <span id="num_cuotas"></span>
</li>



<li class="list-group-item">
💳 Valor cuota: $<span id="valor_cuota_resumen"></span>
</li>


<li class="list-group-item">
🛡️ Seguro total: $<span id="seguro_total"></span>
</li>

<li class="list-group-item">
🛡️ Seguro por cuota: $<span id="seguro_cuota"></span>
</li>

<li class="list-group-item">
💳 Cuota con seguro: $<span id="cuota_seguro"></span>
</li>





<li id="li_valor_credito" class="list-group-item">💳 Valor Crédito: $<span id="valor_credito"></span></li>





<li id="li_pagare_simple" class="list-group-item">📄 Valor del pagaré: $<span id="valor_pagare"></span></li>

<li id="li_pagare_amort" class="list-group-item d-none">
    📄 Valor del pagaré (Amortizado): $<span id="valor_pagareamort"></span>
</li>
								 <li class="list-group-item">🧾 Gasto de trámite (20%): $<span id="gasto_tramite"></span></li>
								 <li class="list-group-item"> 💵 Valor desembolsado: $<span id="valor_desembolsado"></span></li>

                             <!--   <li class="list-group-item">💰 Total a pagar: $<span id="total_pagar"></span></li>-->

                                <li class="list-group-item">⚠️ Multa por mora: $<span id="multa_mora"></span></li>
                            </ul>
                        </div>
						
						
						

                        <!-- Hidden -->
						
                        <input type="hidden" name="total_pagar" id="input_total_pagar">
                        <input type="hidden" name="cuota_pago" id="input_cuota_pago">
						<input type="hidden" name="factor" id="input_factor">
                        <input type="hidden" name="multa_mora" id="input_multa_mora">
                        <input type="hidden" name="num_cuotas" id="input_num_cuotas">
                        <input type="hidden" name="frecuencia_pago" id="input_frecuencia_pago">
						<input type="hidden" id="input_valor_pagare" name="valor_pagare">
						<input type="hidden" id="input_plazo_meses" name="plazo_meses">
						<input type="hidden" id="input_gasto_tramite" name="gasto_tramite">
						<input type="hidden" id="input_valor_desembolsado" name="valor_desembolsado">

                        <br>

                          <button type="submit"
            class="btn btn-success btn-lg px-4"
            id="btnRegistrar"
            disabled>
        💾 Registrar Crédito
    </button>
						
						</div>
</div>
						
<div id="proyeccion_table_container" class="d-none">

    <br><br><br>
<h5 id="titulo_proyeccion" class="mt-4 mb-3 text-center fw-bold">
    PROYECCIÓN DE AMORTIZACIÓN
</h5>

<div class="d-flex justify-content-end gap-2 mt-3">

    <button type="button" class="btn btn-descargar" onclick="descargarExcel()">
        📥 Descargar Excel
    </button>

    <button type="button" class="btn btn-pdf" onclick="descargarPDF()">
        📄 Descargar PDF
    </button>

</div>
<br>

    <div class="table-responsive">
        <table id="tablaAmortizacion"
               class="table table-striped table-hover table-bordered align-middle shadow-sm">
            <thead class="table-dark text-center">
                <tr>
                    <th>#</th>
                    <th class="text-end">Interés</th>
                    <th id="th_amortizacion" class="text-end">Amortización</th>
                    <th class="text-end">Valor Cuota</th>
                    <th class="text-end">Saldo Pendiente</th>
                    <th>Fecha de Pago</th>
                </tr>
            </thead>
            <tbody id="proyeccion_table_body"></tbody>
        </table>
    </div>



</div>

					</div>	
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>








<style>

.titulo-modulo {
    font-size: 1.6rem;
    font-weight: 700;
    color: #000a38;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    position: relative;
    padding-bottom: 6px;
    margin-bottom: 30px; 
}

.titulo-modulo::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    height: 3px;
    background-color: #000a38;
}

.titulo-seccion {
    color: #1e3a8a; /* azul más elegante */
}
.table thead th {
    background-color: #1f2937; /* negro elegante */
    color: #ffffff !important; /* texto blanco */
}

.btn-descargar {
    background-color: #000a38; /* Tu color */
    color: white;
}


.btn-pdf {
    background-color: #2e7d32; /* Tu color */
    color: white;
}


</style>


<script>
let SEGURO_PORCENTAJE = <?= $seguro_porcentaje ?>;
</script>

<script>

let cuotasArray = [];

document.addEventListener("DOMContentLoaded", function () {

const calcularPrestamoButton = document.getElementById("calcular_prestamo");
const proyeccionTableBody = document.getElementById("proyeccion_table_body");
const proyeccionTableContainer = document.getElementById("proyeccion_table_container");
const fechaInicioField = document.getElementById("fecha_inicio");
const btnRegistrar = document.getElementById("btnRegistrar");

document.getElementById("btnRegistrar").disabled = true;

calcularPrestamoButton.addEventListener("click", function () {

cuotasArray = [];

let cliente = $('#cliente_busqueda').val();
if (!cliente) {
Swal.fire("Cliente requerido","Seleccione un cliente","warning");
return;
}

let aval = $('#aval_busqueda').val();
if (!aval) {
Swal.fire("Referencia requerida","Seleccione referencia personal","warning");
return;
}

let avalFamiliar = $('#aval_busquedafamiliar').val();
if (!avalFamiliar) {
Swal.fire("Referencia requerida","Seleccione referencia familiar","warning");
return;
}



let montoInput = document.getElementById("monto_prestado").value;
let monto = parseFloat(montoInput.replace(/\./g,'').replace(/,/g,''));

if(isNaN(monto) || monto <= 0){
Swal.fire("Monto inválido","Ingrese un monto válido","warning");
return;
}

let plazoMeses =
parseInt(document.getElementById("plazo_meses").value) ||
parseInt(document.querySelector("input[name='plazo_amort']").value);

if(isNaN(plazoMeses) || plazoMeses <= 0){
Swal.fire("Plazo inválido","Ingrese plazo en meses","warning");
return;
}

let tasaInteres;

let factor_valor = document.getElementById("factor_valor").value;

let tipoProyeccion = document.getElementById("tipo_proyeccion").value;

if(Number(tipoProyeccion) === 1){

    document.getElementById("factor_container").style.display = "none";
// ocultar factor en amortizado
document.getElementById("factor_container").style.display = "none";

    let tasaInput = document.getElementById("tasa_mensual_amort").value;

    tasaInteres = parseFloat(tasaInput) / 100;

    if(isNaN(tasaInteres)){
        Swal.fire("Tasa inválida","Ingrese tasa de interés mensual","warning");
        return;
    }


}else{ // SIMPLE

// mostrar factor en simple
document.getElementById("factor_container").style.display = "block";

    let tasaInput = document.getElementById("tasa_interes").value;

    tasaInteres = parseFloat(tasaInput);

    if(isNaN(tasaInteres)){
        Swal.fire("Tasa inválida","Ingrese tasa de interés","warning");
        return;
    }

    tasaInteres = tasaInteres / 100;
}

let frecuencia = "";

let frSimple = document.getElementById("id_frp");
let frAmort = document.getElementById("frecuencia_pago_amort");

if(frSimple && frSimple.value !== ""){
    frecuencia = frSimple.options[frSimple.selectedIndex].text;
}

if(frAmort && frAmort.value !== ""){
    frecuencia = frAmort.options[frAmort.selectedIndex].text;
}

let multa =
document.getElementById("multa_mora").value ||
document.querySelector("input[name='multa_mora_amort']").value;

document.getElementById("frecuencia_pago").textContent = frecuencia;
document.getElementById("input_frecuencia_pago").value = frecuencia;

document.getElementById("multa_mora").textContent = multa + "%";
document.getElementById("input_multa_mora").value = multa;

let numeroCuotas = plazoMeses;

document.getElementById("num_cuotas").textContent = numeroCuotas;
document.getElementById("input_num_cuotas").value = numeroCuotas;

if (!fechaInicioField.value) {
Swal.fire("Fecha requerida","Seleccione fecha de inicio","warning");
return;
}

let fechaInicio = new Date(fechaInicioField.value);

proyeccionTableBody.innerHTML = "";


// ============================
// AMORTIZADO
// ============================

// ============================
// AMORTIZADO (IGUAL A EXCEL)
// ============================

if(Number(tipoProyeccion) === 1){

    proyeccionTableContainer.classList.remove("d-none");

    // ✅ TASA (sin inventos raros)
// 🔥 VALIDAR
if(isNaN(tasaInteres)){
    Swal.fire("Error","La tasa no es válida","error");
    return;
}

// 🔥 CONVERTIR BIEN
let tasaAnual = parseFloat(document.getElementById("tasa_anual_amor").value) / 100;

let tasaMensual = Math.pow(1 + tasaAnual, 1/12) - 1;

console.log("tasaMensual REAL:", tasaMensual);
    console.log("tasaAnual REAL:", tasaAnual);

    // 🔥 CUOTA (igual a Excel)
    let cuota = monto * (tasaMensual * Math.pow(1 + tasaMensual, numeroCuotas)) /
    (Math.pow(1 + tasaMensual, numeroCuotas) - 1);

    cuota = Math.round(cuota);

    document.getElementById("valor_cuota_resumen").textContent =
    cuota.toLocaleString('es-CO');

    // ============================
    // 🔐 SEGURO
    // ============================
    let seguro_valor = monto * (SEGURO_PORCENTAJE / 100);
    let seguro_por_cuota = seguro_valor / numeroCuotas;
    let cuota_con_seguro = cuota + seguro_por_cuota;

    document.getElementById("seguro_total").textContent =
        Math.round(seguro_valor).toLocaleString('es-CO');

    document.getElementById("seguro_cuota").textContent =
        Math.round(seguro_por_cuota).toLocaleString('es-CO');

    document.getElementById("cuota_seguro").textContent =
        Math.round(cuota_con_seguro).toLocaleString('es-CO');

    let saldo = monto;

    let totalInteres = 0;
    let totalCapital = 0;

    for (let i = 1; i <= numeroCuotas; i++) {

        // 🔥 CLAVE: SIN REDONDEAR
        let interes = saldo * tasaMensual;
        let capital = cuota - interes;

        // 🔥 AJUSTE ÚLTIMA CUOTA
        if (i === numeroCuotas) {
            capital = saldo;
            saldo = 0;
        } else {
            saldo = saldo - capital;
        }

        // ✅ SOLO VISUAL
        let interesMostrar = Math.round(interes);
        let capitalMostrar = Math.round(capital);
        let saldoMostrar = Math.round(saldo);
        let cuotaMostrarFila = Math.round(interes + capital);

        let fechaPago = new Date(fechaInicio);
        fechaPago.setMonth(fechaPago.getMonth() + i);

        cuotasArray.push({
            numero_cuota: i,
            interes: interesMostrar,
            capital: capitalMostrar,
            valor_cuota: cuotaMostrarFila,
            fecha_pago: fechaPago.toISOString().split('T')[0]
        });

        let fila = `
        <tr>
            <td class="text-center">${i}</td>
            <td class="text-end">$${interesMostrar.toLocaleString('es-CO')}</td>
            <td class="text-end">$${capitalMostrar.toLocaleString('es-CO')}</td>
            <td class="text-end">$${cuotaMostrarFila.toLocaleString('es-CO')}</td>
            <td class="text-end">$${saldoMostrar.toLocaleString('es-CO')}</td>
            <td class="text-center">${fechaPago.toLocaleDateString('es-CO')}</td>
        </tr>
        `;

        proyeccionTableBody.innerHTML += fila;
    }


// 🔥 CALCULAR TOTAL PAGAR (PAGARÉ)
let totalPagar = cuota * numeroCuotas;

// 🔥 mostrar pagaré amortizado
document.getElementById("valor_pagareamort").textContent =
totalPagar.toLocaleString('es-CO');

// 🔥 guardar hidden
document.getElementById("input_valor_pagare").value = totalPagar;

// 🔥 también como valor crédito
document.getElementById("valor_credito").textContent =
totalPagar.toLocaleString('es-CO');

document.getElementById("input_total_pagar").value = totalPagar;
// 🔥 mostrar/ocultar correcto
document.getElementById("li_pagare_simple").classList.remove("d-none");
document.getElementById("li_pagare_amort").classList.add("d-none");

// 🔥 mostrar/ocultar correcto
document.getElementById("li_pagare_simple").classList.add("d-none");
document.getElementById("li_pagare_amort").classList.remove("d-none");

}
// ============================
// SIMPLE
// ============================

else{
	
	proyeccionTableContainer.classList.remove("d-none");

let tasa = tasaInteres / 100;

// 🔵 FACTOR EXACTO COMO EXCEL


let periodoGracia = parseInt(document.querySelector("input[name='periodo_gracia']")?.value) || 0;

let factor = ((plazoMeses + periodoGracia) * tasaInteres) + 1;

// mostrar factor
let factorElemento = document.getElementById("factor_valor");

if (factorElemento) {
    factorElemento.textContent = factor.toFixed(4);
}

document.getElementById("input_factor").value = factor;


// 🔵 CALCULAR CUOTA
let cuota = (monto * factor) / plazoMeses;

cuota = Math.round(cuota);

document.getElementById("valor_cuota_resumen").textContent =
cuota.toLocaleString('es-CO');

// ============================
// 🔐 SEGURO
// ============================
let seguro_valor = monto * (SEGURO_PORCENTAJE / 100);
let seguro_por_cuota = seguro_valor / numeroCuotas;
let cuota_con_seguro = cuota + seguro_por_cuota;

document.getElementById("seguro_total").textContent =
    Math.round(seguro_valor).toLocaleString('es-CO');

document.getElementById("seguro_cuota").textContent =
    Math.round(seguro_por_cuota).toLocaleString('es-CO');

document.getElementById("cuota_seguro").textContent =
    Math.round(cuota_con_seguro).toLocaleString('es-CO');


// 🔵 CALCULAR TOTAL PAGAR
let totalPagar = cuota * plazoMeses;

let saldo = monto;

let capitalFijo = Math.round(monto / plazoMeses);

// interés fijo
// calcular interés igual que Excel
let totalInteres = totalPagar - monto;
let interesFijo = Math.round(totalInteres / plazoMeses);


let totalInteresTabla = 0;
let totalCapitalTabla = 0;
let totalCuotasTabla = 0;

for (let i = 1; i <= plazoMeses; i++) {

let interes = interesFijo;
let capital = capitalFijo;

saldo = saldo - capital;

totalInteresTabla += interes;
totalCapitalTabla += capital;
totalCuotasTabla += cuota;

let fechaPago = new Date(fechaInicio);
fechaPago.setMonth(fechaPago.getMonth() + i);

let fila = `
<tr>
<td class="text-center">${i}</td>
<td class="text-end">$${interes.toLocaleString('es-CO')}</td>
<td class="text-end">$${capital.toLocaleString('es-CO')}</td>
<td class="text-end">$${cuota.toLocaleString('es-CO')}</td>
<td class="text-end"></td>
<td class="text-center">${fechaPago.toLocaleDateString('es-CO')}</td>
</tr>
`;
proyeccionTableBody.innerHTML += fila;

cuotasArray.push({
numero_cuota: i,
interes: interes,
capital: capital,
valor_cuota: cuota,
fecha_pago: fechaPago.toISOString().split('T')[0]
});

}

let filaTotales = `
<tr class="table-dark fw-bold">
<td class="text-center">TOTAL</td>
<td class="text-end">$${totalInteresTabla.toLocaleString('es-CO')}</td>
<td class="text-end">$${totalCapitalTabla.toLocaleString('es-CO')}</td>
<td class="text-end">$${totalCuotasTabla.toLocaleString('es-CO')}</td>
<td></td>
<td></td>
</tr>
`;

proyeccionTableBody.innerHTML += filaTotales;

document.getElementById("valor_pagare").textContent =
totalPagar.toLocaleString('es-CO');

document.getElementById("input_valor_pagare").value = totalPagar;

document.getElementById("valor_credito").textContent =
totalPagar.toLocaleString('es-CO');

document.getElementById("input_total_pagar").value = totalPagar;

}



document.getElementById("frecuencia_pago").textContent = frecuencia;
document.getElementById("input_frecuencia_pago").value = frecuencia;


// ============================
// GASTO TRÁMITE
// ============================

let gastoTramite = monto * 0.20;

document.getElementById("gasto_tramite").textContent =
gastoTramite.toLocaleString('es-CO');

document.getElementById("input_gasto_tramite").value = gastoTramite;




// ============================
// DESEMBOLSO
// ============================

let valorDesembolsado = monto - gastoTramite;

document.getElementById("valor_desembolsado").textContent =
valorDesembolsado.toLocaleString('es-CO');

document.getElementById("input_valor_desembolsado").value =
valorDesembolsado;


document.getElementById("resultado_prestamo").classList.remove("d-none");

btnRegistrar.disabled = false;

});


});


const camposRecalculo = [
    "id_tipo_credito",
    "monto_prestado",
    "fecha_inicio"
];

camposRecalculo.forEach(id => {
    const campo = document.getElementById(id);
    if (campo) {
        campo.addEventListener("change", function () {
            btnRegistrar.disabled = true;
        });
    }
});



function calcularCuota(monto, tasa, cuotas){

    let r = tasa; // tasa ya en decimal
    let n = cuotas;

    let cuota = monto * (r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1);

    return cuota;
}

function calcularInteresExcel(monto, tasa, n, periodo) {

    let cuota = monto * (tasa * Math.pow(1 + tasa, n)) / 
                (Math.pow(1 + tasa, n) - 1);

    cuota = Math.round(cuota); // 🔥 Excel trabaja con cuota redondeada

    let saldo = monto;

    for (let i = 1; i < periodo; i++) {

        let interes = Math.round(saldo * tasa); // 🔥 REDONDEA AQUÍ
        let capital = Math.round(cuota - interes); // 🔥 REDONDEA AQUÍ

        saldo = Math.round(saldo - capital); // 🔥 REDONDEA AQUÍ
    }

    return Math.round(saldo * tasa); // 🔥 REDONDEA FINAL
}


// =====================
// DESCARGAR EXCEL
// =====================
function descargarExcel() {

    let tabla = document.getElementById("tablaAmortizacion");

    if (!tabla) {
        alert("No hay datos para exportar");
        return;
    }

    let workbook = XLSX.utils.table_to_book(tabla, { sheet: "Amortizacion" });
    XLSX.writeFile(workbook, "Proyeccion_Amortizacion.xlsx");
}


function descargarPDF() {

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // ==============================
    // 🔷 VALIDAR TABLA
    // ==============================
    let tabla = document.getElementById("tablaAmortizacion");

    if (!tabla) {
        alert("No hay datos para exportar");
        return;
    }

    // ==============================
    // 🔷 DATOS LIMPIOS (SIN DUPLICADOS)
    // ==============================
    let nombre = $("#cliente_busqueda option:selected").text();
    let monto = document.getElementById("monto_prestado").value;

    let tasaAnual = document.getElementById("tasa_interes")?.value 
        || document.getElementById("tasa_anual_amor")?.value || "";

    let tasaMensual = document.getElementById("tasa_mensual_amort")?.value 
        || document.getElementById("tasa_mensual_simple")?.value || "";

    let numPeriodos = document.getElementById("input_num_cuotas").value;
	
	let frecuencia = document.getElementById("input_frecuencia_pago").value;

// 🔥 detectar unidad
let unidad = "";

if (frecuencia.toLowerCase().includes("mensual")) {
    unidad = "Meses";
} else if (frecuencia.toLowerCase().includes("diario")) {
    unidad = "Días";
} else if (frecuencia.toLowerCase().includes("anual")) {
    unidad = "Años";
} else if (frecuencia.toLowerCase().includes("quincenal")) {
    unidad = "Quincenas";
} else {
    unidad = "Periodos";
}

// 🔥 corregir singular
if (numPeriodos == 1) {
    unidad = unidad.replace("s", "");
}

// 🔥 texto final
let periodosTexto = numPeriodos + " " + unidad;

    let valorPagare = document.getElementById("input_valor_pagare").value;
    let gastoTramite = document.getElementById("input_gasto_tramite").value;
    let desembolso = document.getElementById("input_valor_desembolsado").value;

    // ==============================
    // 🔷 TÍTULO
    // ==============================
    doc.setFontSize(16);
    doc.text("PROYECCIÓN DE AMORTIZACIÓN", 14, 15);

    doc.setFontSize(10);
    doc.text("Fecha: " + new Date().toLocaleDateString('es-CO'), 14, 22);

    // ==============================
    // 🔷 ENCABEZADO
    // ==============================
    let y = 30;

    // IZQUIERDA
    doc.text("INFORMACIÓN DEUDOR", 14, y);
    y += 6;
    doc.text("Nombre: " + nombre, 14, y);
    y += 6;
    doc.text("Valor Crédito: $" + formatearPesos(monto), 14, y);
    y += 6;
    doc.text("Gasto Trámite: $" + formatearPesos(gastoTramite), 14, y);
    y += 6;
    doc.text("Valor Desembolso: $" + formatearPesos(desembolso), 14, y);

    // DERECHA
    let y2 = 30;
    doc.text("INFORMACIÓN ADICIONAL", 120, y2);
    y2 += 6;
    doc.text("Tasa EA: " + tasaAnual + "%", 120, y2);
    y2 += 6;
    doc.text("Tasa Mensual: " + tasaMensual + "%", 120, y2);
    y2 += 6;
    doc.text("N° Periodos: " + numPeriodos + " " + unidad, 120, y2);
    y2 += 6;
    doc.text("Valor Pagaré: $" + formatearPesos(valorPagare), 120, y2);

    // ==============================
    // 🔷 TABLA
    // ==============================
    doc.autoTable({
        html: '#tablaAmortizacion',
        startY: 75,
        theme: 'grid',
        styles: {
            fontSize: 8,
            cellPadding: 3,
            halign: 'right'
        },
        headStyles: {
            fillColor: [41, 128, 185],
            halign: 'center'
        },
        columnStyles: {
            0: { halign: 'center' },
            5: { halign: 'center' }
        }
    });

    // ==============================
    // 🔷 GUARDAR PDF
    // ==============================
    doc.save("Proyeccion_Amortizacion.pdf");
}

function formatearPesos(valor) {
    if (!valor) return "0";
    return parseFloat(valor)
        .toLocaleString('es-CO');
}



</script>




<script>

document.getElementById("formPrestamo")
.addEventListener("submit", function (e) {

    e.preventDefault();
	
	// 🔥 VALIDAR CLIENTE
    let cliente = $('#cliente_busqueda').val();

    if (!cliente || cliente.trim() === "") {
        Swal.fire({
            icon: 'warning',
            title: 'Cliente requerido',
            text: 'Debe seleccionar un cliente antes de registrar el préstamo.',
            confirmButtonColor: '#3085d6'
        });
        return;
    }
	
	// 🔥 VALIDAR REFERENCIA PERSONAL
let aval = $('#aval_busqueda').val();

if (!aval || aval.trim() === "") {
    Swal.fire({
        icon: 'warning',
        title: 'Referencia requerida',
        text: 'Debe seleccionar una referencia personal.',
        confirmButtonColor: '#3085d6'
    });
    return;
}

// 🔥 VALIDAR REFERENCIA FAMILIAR
let avalFamiliar = $('#aval_busquedafamiliar').val();

if (!avalFamiliar || avalFamiliar.trim() === "") {
    Swal.fire({
        icon: 'warning',
        title: 'Referencia familiar requerida',
        text: 'Debe seleccionar una referencia familiar.',
        confirmButtonColor: '#3085d6'
    });
    return;
}



    // ===============================
    // 🔥 VALIDACIÓN AQUÍ (JUSTO AQUÍ)
    // ===============================

    let pagareSimple = document.getElementById("valor_pagare").textContent;
    let pagareAmort = document.getElementById("valor_pagareamort").textContent;

    let valorFinal = pagareSimple || pagareAmort;

  if (!valorFinal || valorFinal.trim() === "") {
    Swal.fire({
        icon: 'warning',
        title: 'Cálculo requerido',
        text: 'Debe calcular el préstamo antes de registrarlo.',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Entendido'
    });
    return;
}


    let valorNumerico = valorFinal
        .replace(/\./g, '')
        .replace(/,/g, '')
        .replace('$', '')
        .trim();

    document.getElementById("input_valor_pagare").value = valorNumerico;

    // ===============================
    // 🔥 AHORA SÍ CREAS EL FORMDATA
    // ===============================
	



    const formData = new FormData(this);
	
	formData.append("cuotas_json", JSON.stringify(cuotasArray));

    fetch("../includes/guardarPrestamo.php", {
        method: "POST",
        body: formData
    })

    .then(response => response.json()) // 🔥 CAMBIO IMPORTANTE
    .then(data => {

        console.log("Respuesta servidor:", data);

        if (data.success) {

            Swal.fire({
                icon: 'success',
                title: 'Préstamo Solicitado',
                text: data.message,
                confirmButtonColor: '#3085d6'
            }).then(() => {
                window.location.reload();
            });

        } else {

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message
            });

        }

    })
    .catch(error => {
        console.error("Error:", error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error en la petición'
        });
    });

});



</script>

<script>
    $(document).ready(function() {
        $('#formTipoPrestamo').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: '../includes/functions.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Datos Guardados',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(function() {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Alerta',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error inesperado'
                    });
                }
            });
        });
    });


document.getElementById('tipo_proyeccion').addEventListener('change', function () {

    const tipoId = this.value; // 0 = simple | 1 = amortizado

    const bloqueSimple = document.getElementById('bloque_simple');
    const bloqueAmort = document.getElementById('bloque_amortizado');

    const simpleInputs = bloqueSimple.querySelectorAll('input, select, textarea');
    const amortInputs  = bloqueAmort.querySelectorAll('input, select, textarea');

    // ================= RESET TOTAL =================
    bloqueSimple.style.display = 'none';
    bloqueAmort.style.display = 'none';

    simpleInputs.forEach(el => {
        el.required = false;
        el.disabled = true;
    });

    amortInputs.forEach(el => {
        el.required = false;
        el.disabled = true;
    });

    // ================= SIMPLE =================
    if (tipoId === '0') {
        bloqueSimple.style.display = 'block';

        simpleInputs.forEach(el => {
            el.disabled = false;
            if (el.name !== 'periodo_gracia') { // opcional
                el.required = true;
            }
        });
    }

    // ================= AMORTIZADO =================
    if (tipoId === '1') {
        bloqueAmort.style.display = 'block';

        amortInputs.forEach(el => {
            el.disabled = false;
            el.required = true;
        });
    }
});


let factor_valor = document.getElementById("factor_valor");

let tipoProyeccion = document.getElementById("tipo_proyeccion").value;

if(Number(tipoProyeccion) === 1){
    document.getElementById("factor_container").style.display = "none";
}else{
    document.getElementById("factor_container").style.display = "block";
}




</script>




<script>

// 🔒 Control para evitar bucle infinito
let bloqueando = false;

// =========================
// 🔵 FUNCIONES BASE
// =========================

// Mensual → Anual (TEA)
function calcularAnualDesdeMensual(mensual) {
    let m = mensual / 100;
    let anual = (Math.pow(1 + m, 12) - 1) * 100;

    // 🔥 redondeo a 0.50
    return Math.round(anual * 2) / 2;
}

// Anual → Mensual
function calcularMensualDesdeAnual(anual) {
    let a = anual / 100;
    let mensual = (Math.pow(1 + a, 1/12) - 1) * 100;

    return mensual;
}


// =========================
// 🔵 SIMPLE (BIDIRECCIONAL)
// =========================

// 👉 Mensual → Anual
document.getElementById('tasa_mensual_simple')
.addEventListener('input', function() {

    if (bloqueando) return;
    bloqueando = true;

    let mensual = parseFloat(this.value);

    if (!isNaN(mensual)) {
        let anual = calcularAnualDesdeMensual(mensual);
        document.getElementById('tasa_interes').value = anual.toFixed(2);
    } else {
        document.getElementById('tasa_interes').value = '';
    }

    bloqueando = false;
});


// 👉 Anual → Mensual
document.getElementById('tasa_interes')
.addEventListener('input', function() {

    if (bloqueando) return;
    bloqueando = true;

    let anual = parseFloat(this.value);

    if (!isNaN(anual)) {
        let mensual = calcularMensualDesdeAnual(anual);
        document.getElementById('tasa_mensual_simple').value = mensual.toFixed(4);
    } else {
        document.getElementById('tasa_mensual_simple').value = '';
    }

    bloqueando = false;
});


// =========================
// 🔵 AMORTIZADO (BIDIRECCIONAL)
// =========================

// 👉 Mensual → Anual
document.getElementById('tasa_mensual_amort')
?.addEventListener('input', function() {

    if (bloqueando) return;
    bloqueando = true;

    let mensual = parseFloat(this.value);

    if (!isNaN(mensual)) {
        let anual = calcularAnualDesdeMensual(mensual);
        document.getElementById('tasa_anual_amor').value = anual.toFixed(2);
    } else {
        document.getElementById('tasa_anual_amor').value = '';
    }

    bloqueando = false;
});


// 👉 Anual → Mensual
document.getElementById('tasa_anual_amor')
?.addEventListener('input', function() {

    if (bloqueando) return;
    bloqueando = true;

    let anual = parseFloat(this.value);

    if (!isNaN(anual)) {
        let mensual = calcularMensualDesdeAnual(anual);
        document.getElementById('tasa_mensual_amort').value = mensual.toFixed(4);
    } else {
        document.getElementById('tasa_mensual_amort').value = '';
    }

    bloqueando = false;
});


function controlarBloqueo(inputA, inputB) {

    inputA.addEventListener("input", function () {

        if (this.value !== "") {
            inputB.readOnly = true;
            inputB.classList.add("bg-light");
        } else {
            inputB.readOnly = false;
            inputB.classList.remove("bg-light");
        }

    });

}


// =========================
// 🔒 ACTIVAR BLOQUEO SIMPLE
// =========================
controlarBloqueo(
    document.getElementById('tasa_mensual_simple'),
    document.getElementById('tasa_interes')
);

controlarBloqueo(
    document.getElementById('tasa_interes'),
    document.getElementById('tasa_mensual_simple')
);

// =========================
// 🔒 ACTIVAR BLOQUEO AMORTIZADO
// =========================
controlarBloqueo(
    document.getElementById('tasa_mensual_amort'),
    document.getElementById('tasa_anual_amor')
);

controlarBloqueo(
    document.getElementById('tasa_anual_amor'),
    document.getElementById('tasa_mensual_amort')
);


</script>





<?php include "../includes/footer.php"; ?>
