<?php 
include "../includes/configSession.php";
require_once "../includes/db.php";
include "../includes/header.php"; 
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>

.card-kpi {
    border-radius: 14px;
    padding: 18px;
    background: #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    transition: 0.2s;
}

.card-kpi:hover {
    transform: translateY(-3px);
}

.kpi-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
}

.bg-capital { background: #2563eb; }
.bg-interes { background: #7c3aed; }
.bg-retiro { background: #dc2626; }
.bg-disponible { background: #16a34a; }

.card-chart {
    background: #fff;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.05);
}


#card-total{
    background: #c7d2fe;
    border-radius: 14px;
    padding: 20px;
}

/* 🔥 ICONO CONTROLADO */
.icon-total{
    position:absolute;
    right:870px; /* 👈 ajusta aquí */
    top:50%;
    transform:translateY(-50%);

    width:50px;
    height:50px;
    border-radius:12px;
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    font-size:22px;
}

</style>

<section class="table-components">
<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

    <div>
        <h4 class="fw-bold mb-1">📊 Dashboard Inversionistas</h4>
        <small class="text-muted">Control financiero en tiempo real</small>
    </div>

    <div class="d-flex gap-2 align-items-center">

        <select id="filtro" style="width:220px;"></select>

        <button type="button" class="btn btn-dark" onclick="verTodos()">
    🌍 Ver todos
</button>

    </div>

</div>
<br><br>

<!-- 🔥 RESUMEN -->
<div class="row g-3 mb-3">

<div class="col-md-3">
<div class="card-kpi d-flex justify-content-between align-items-center">
    <div>
        <small>Capital</small>
        <h5 id="capital">$0</h5>
    </div>
    <div class="kpi-icon bg-capital">💰</div>
</div>
</div>

<div class="col-md-3">
<div class="card-kpi d-flex justify-content-between align-items-center">
    <div>
        <small>Intereses</small>
        <h5 id="interes">$0</h5>
    </div>
    <div class="kpi-icon bg-interes">📈</div>
</div>
</div>

<div class="col-md-3">
<div class="card-kpi d-flex justify-content-between align-items-center">
    <div>
        <small>Retirados</small>
        <h5 id="retirado">$0</h5>
    </div>
    <div class="kpi-icon bg-retiro">📉</div>
</div>
</div>

<div class="col-md-3">
<div class="card-kpi d-flex justify-content-between align-items-center">
    <div>
        <small>Disponibles</small>
        <h5 id="disponible">$0</h5>
    </div>
    <div class="kpi-icon bg-disponible">🟢</div>
</div>
</div>

</div>

<div id="card-total" class="mb-4 position-relative">

    <div class="mx-auto text-center" style="max-width:500px;">

        <small style="color:#475569;">Total (Capital + Intereses)</small>

        <h3 id="total" style="margin:0; font-weight:700;">
            $0
        </h3>

    </div>

    <!-- ICONO -->
    <div class="icon-total">
        💼
    </div>

</div>

<!-- 🔥 GRÁFICAS -->
<div class="row g-3">

<div class="col-md-8">
<div class="card-chart">
    <h6 class="mb-3">📈 Crecimiento</h6>
    <canvas id="graficaCrecimiento"></canvas>
</div>
</div>

<div class="col-md-4">
<div class="card-chart">
    <h6 class="mb-3">🏆 Top inversionistas</h6>
    <div id="topInversionistas"></div>
</div>
</div>

<div class="col-md-6">
<div class="card-chart">
    <h6 class="mb-3">💸 Flujo</h6>
    <canvas id="graficaFlujo"></canvas>
</div>
</div>

<div class="col-md-6">
<div class="card-chart">
    <h6 class="mb-3">📊 Intereses</h6>
    <canvas id="graficaIntereses"></canvas>
</div>
</div>

</div>

</div>
</section>

<script>

function formatear(n){
    return "$" + parseInt(n).toLocaleString('es-CO');
}

// 🔥 RESUMEN
function cargarResumen(){

    $.post("../ajax/dashboardInversionistas.php", {
        id: $("#filtro").val()
    }, function(res){

        let data = (typeof res === "object") ? res : JSON.parse(res);

        $("#capital").text(formatear(data.capital));
        $("#interes").text(formatear(data.interes));
        $("#retirado").text(formatear(data.retirado));
        $("#disponible").text(formatear(data.disponible));

        $("#total").text(
            formatear(parseInt(data.capital) + parseInt(data.disponible))
        );

    });

}

// 🔥 GRÁFICAS
function cargarGraficas(){

    $.post("../ajax/dashboardGraficas.php", {
        id: $("#filtro").val()
    }, function(res){

        let data = (typeof res === "object") ? res : JSON.parse(res);

        // 🔥 DESTRUIR GRÁFICAS ANTES DE CREARLAS

    if(window.graficaCrecimiento instanceof Chart){
    window.graficaCrecimiento.destroy();
}

if(window.graficaFlujo instanceof Chart){
    window.graficaFlujo.destroy();
}

if(window.graficaIntereses instanceof Chart){
    window.graficaIntereses.destroy();
}

        // CRECIMIENTO
        window.graficaCrecimiento = new Chart(document.getElementById('graficaCrecimiento'), {
            type: 'line',
            data: {
                labels: data.crecimiento.map(x => x.fecha),
                datasets: [{
                    label: 'Capital',
                    data: data.crecimiento.map(x => x.total)
                }]
            }
        });

        // FLUJO
        window.graficaFlujo = new Chart(document.getElementById('graficaFlujo'), {
            type: 'bar',
            data: {
                labels: data.flujo.map(x => x.fecha),
                datasets: [
                    { label: 'Ingresos', data: data.flujo.map(x => x.ingresos) },
                    { label: 'Egresos', data: data.flujo.map(x => x.egresos) }
                ]
            }
        });

        // INTERESES
        window.graficaIntereses = new Chart(document.getElementById('graficaIntereses'), {
            type: 'bar',
            data: {
                labels: data.intereses.map(x => x.mes),
                datasets: [{
                    label: 'Intereses',
                    data: data.intereses.map(x => x.total)
                }]
            }
        });

        // TOP
        let html = "";
        data.top.forEach(t=>{
            html += `<div class="d-flex justify-content-between mb-2">
                <span>${t.nombre}</span>
                <b>${formatear(t.total)}</b>
            </div>`;
        });

        $("#topInversionistas").html(html);

    });

}

// 🔥 BUSCADOR
$("#filtro").select2({
    placeholder:"Buscar inversionista...",
    ajax:{
        url:"../ajax/buscarInversionista.php",
        dataType:"json",
        delay:250,
        data:params=>({q:params.term}),
        processResults:data=>({results:data})
    }
});

// 🔥 EVENTOS
$('#filtro').on('select2:select', function (e) {
    cargarResumen();
    cargarGraficas();
});

function verTodos(){
    $("#filtro").val(null).trigger("change");
}

$(document).ready(function(){
    cargarResumen();
    cargarGraficas();
});

function verTodos(){

    // 🔥 limpiar filtro
    $("#filtro").val("");

    // 🔥 recargar todo el dashboard
    cargarResumen();
    cargarGraficas();

}

</script>