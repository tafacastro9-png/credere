<?php
include "../includes/configSession.php";
require_once "../includes/db.php";
include "../includes/header.php";
?>

<div class="container-fluid mt-4">

    <h4 class="mb-4">📊 Dashboard de Cartera</h4>
	
	<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

    <!-- 🔍 BUSCAR CLIENTE -->
    <select id="filtroCliente" class="form-control" style="max-width:300px;">
        <option value="">Buscar cliente...</option>
    </select>

    <!-- 🌍 VER TODOS -->
    <button class="btn btn-dark" onclick="verTodosCartera()">
        🌍 Ver todos
    </button>

</div>

    <!-- 🔥 KPIs -->
    <div class="row text-center mb-4">

        <div class="col-md-3">
            <div class="card p-3 shadow">
                <h6>Total Cartera</h6>
                <h4 id="totalCartera">$0</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow">
                <h6>Cartera en Mora</h6>
                <h4 id="totalMora">$0</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow">
                <h6>% Mora</h6>
                <h4 id="porcentajeMora">0%</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow">
                <h6>👤 Cliente más moroso</h6>
                <h5 id="clienteMora">-</h5>
                <small id="valorMora">$0</small>
            </div>
        </div>

    </div>

    <!-- 🔥 GRÁFICA -->
    <div class="card p-4 mb-4">
        <h5>📈 Mora por mes</h5>
        <canvas id="graficaMora"></canvas>
    </div>

    <!-- 🔥 RANKING -->
    <div class="card p-4">
        <h5>🔥 Top clientes morosos</h5>
        <div id="rankingMora"></div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script>
let chartMora = null;

function cargarDashboardCartera(){

   $.ajax({
    url: "../ajax/dashboardCarteraAjax.php",
    type: "POST",
    dataType: "json",
    data: {
        id_cliente: $("#filtroCliente").val() || 0
    },
        success: function(res){

            // 🔥 KPIs
            $("#totalCartera").text("$ " + res.totalCartera);
            $("#totalMora").text("$ " + res.totalMora);
            $("#porcentajeMora").text(res.porcentajeMora + "%");
            $("#clienteMora").text(res.clienteMora);
            $("#valorMora").text("$ " + res.valorMora);

            // 🔥 Ranking
            let html = `
                <table class="table table-bordered">
                <tr>
                    <th>Cliente</th>
                    <th>Deuda</th>
                </tr>
            `;

            res.ranking.forEach(r => {
                html += `
                    <tr>
                        <td>${r.nombre}</td>
                        <td>$ ${r.deuda}</td>
                    </tr>
                `;
            });

            html += "</table>";
            $("#rankingMora").html(html);

            // 🔥 Gráfica
            let labels = res.grafica.map(x => x.mes);
            let data = res.grafica.map(x => x.total);

            let meses = ["","Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"];
            labels = labels.map(m => meses[m]);

            let ctx = document.getElementById("graficaMora").getContext("2d");

            if(chartMora === null){
                chartMora = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Mora',
                            data: data
                        }]
                    }
                });
            } else {
                chartMora.data.labels = labels;
                chartMora.data.datasets[0].data = data;
                chartMora.update();
            }

        }
    });
}

// 🔥 cargar al iniciar
cargarDashboardCartera();


$("#filtroCliente").select2({
    placeholder: "Buscar cliente...",
    allowClear: true,
    ajax: {
        url: "../ajax/buscarCliente.php",
        dataType: "json",
        delay: 250,
        data: function (params) {
            return {
                q: params.term
            };
        },
        processResults: function (data) {
            return {
                results: data
            };
        }
    }
});

$("#filtroCliente").on("change", function(){
    cargarDashboardCartera();
});

function verTodosCartera(){
    $("#filtroCliente").val(null).trigger("change");
    cargarDashboardCartera(); // 🔥 ESTA LÍNEA ES LA CLAVE
}


</script>