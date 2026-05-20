<?php
include "../includes/configSession.php";
require_once "../includes/db.php";
include "../includes/header.php";
?>

<div class="container-fluid mt-4">

    <h4 class="mb-4">📊 Dashboard de Comisiones</h4>

    <!-- 🔥 FILTRO -->
    <div class="row mb-4">
        <div class="col-md-3">
            <select id="anio" class="form-control">
                <option value="">Año actual</option>
                <?php
                $q = mysqli_query($conexion, "SELECT DISTINCT YEAR(fecha_inicio) as anio FROM prestamos ORDER BY anio DESC");
                while($r = mysqli_fetch_assoc($q)){
                    echo "<option value='{$r['anio']}'>{$r['anio']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-3">
            <select id="mes" class="form-control">
                <option value="">Mes actual</option>
                <?php
$meses = [
1=>"Enero",2=>"Febrero",3=>"Marzo",4=>"Abril",
5=>"Mayo",6=>"Junio",7=>"Julio",8=>"Agosto",
9=>"Septiembre",10=>"Octubre",11=>"Noviembre",12=>"Diciembre"
];

foreach($meses as $num => $nombre){
    echo "<option value='$num'>$nombre</option>";
}
?>
            </select>
        </div>

        <div class="col-md-3">
            <button class="btn btn-primary" onclick="cargarDashboard()">🔄 Actualizar</button>
        </div>
    </div>

    <!-- 🔥 KPIs -->
    <div class="row text-center mb-4">
        <div class="col-md-3">
            <div class="card p-3 shadow">
                <h6>Total Ventas</h6>
                <h4 id="totalVentas">$0</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow">
                <h6>Total Comisiones</h6>
                <h4 id="totalComisiones">$0</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow">
                <h6>Asesores</h6>
                <h4 id="totalAsesores">0</h4>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow">
                <h6>🏆 Mejor Asesor</h6>
                <h4 id="mejorAsesor">-</h4>
            </div>
        </div>
    </div>

    <!-- 🔥 GRÁFICA -->
    <div class="card p-4 mb-4">
        <h5>📈 Comisiones por mes</h5>
        <canvas id="grafica"></canvas>
    </div>

    <!-- 🔥 RANKING -->
    <div class="card p-4">
        <h5>🔥 Ranking de Asesores</h5>
        <div id="ranking"></div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let chart = null;

function cargarDashboard(){

    let hoy = new Date();

    let anio = $("#anio").val() || hoy.getFullYear();
    let mes = $("#mes").val() || (hoy.getMonth() + 1);

    $.ajax({
        url: "../ajax/dashboardComisionesAjax.php",
        type: "POST",
        data: {anio, mes},
        dataType: "json",
        success: function(res){

            console.log(res);

            // KPIs
            $("#totalVentas").text("$ " + res.totalVentas);
            $("#totalComisiones").text("$ " + res.totalComisiones);
            $("#totalAsesores").text(res.totalAsesores);
            $("#mejorAsesor").text(res.mejorAsesor);

            // Ranking
            let html = `<table class="table table-bordered">
                <tr>
                    <th>Asesor</th>
                    <th>Ventas</th>
                    <th>Comisión</th>
                </tr>`;

            res.ranking.forEach(r => {
                html += `
                    <tr>
                        <td>${r.usuario}</td>
                        <td>$ ${r.ventas}</td>
                        <td>$ ${r.comision}</td>
                    </tr>`;
            });

            html += "</table>";
            $("#ranking").html(html);

            // 🔥 DATOS DE GRÁFICA
            let labels = res.grafica.map(x => x.mes);
            let data = res.grafica.map(x => x.total);

            let ctx = document.getElementById("grafica").getContext("2d");

            // 🔥 SI NO EXISTE → CREAR
            if(chart === null){
                chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Comisiones',
                            data: data
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            } else {
                // 🔥 SI YA EXISTE → ACTUALIZAR
                chart.data.labels = labels;
                chart.data.datasets[0].data = data;
                chart.update(); // 🔥 ESTA ES LA CLAVE
            }

        },
        error: function(e){
            console.log("Error:", e.responseText);
        }
    });
}
</script>