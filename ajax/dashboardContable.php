<?php 
include "../includes/configSession.php";
require_once "../includes/db.php";
include "../includes/header.php";

// ============================
// 🔧 FUNCIONES
// ============================

function f($n){
    return number_format($n ?? 0,0,',','.');
}

function getTotal($conexion, $sql){
    $r = mysqli_query($conexion, $sql);
    if(!$r){ die(mysqli_error($conexion)); }
    $row = mysqli_fetch_assoc($r);
    return $row['total'] ?? 0;
}

// ============================
// 💰 CAJA
// ============================

$caja = getTotal($conexion,"
    SELECT IFNULL(SUM(
        CASE 
            WHEN tipo='INGRESO' THEN valor
            ELSE -valor
        END
    ),0) total
    FROM movimientos_caja
");

// ============================
// 📊 CARTERA
// ============================

$cartera = getTotal($conexion,"
    SELECT IFNULL(SUM(capital),0) total
    FROM cuotas_prestamo
    WHERE estado IN ('PENDIENTE','VENCIDO')
");

// ============================
// ⚠️ MORA
// ============================

$total_cartera = getTotal($conexion,"
    SELECT IFNULL(SUM(capital),0) total
    FROM cuotas_prestamo
");

$cartera_mora = getTotal($conexion,"
    SELECT IFNULL(SUM(capital),0) total
    FROM cuotas_prestamo
    WHERE estado='VENCIDO'
");

$porcentaje_mora = ($total_cartera > 0) 
    ? ($cartera_mora / $total_cartera) * 100 
    : 0;

// ============================
// 📈 RENTABILIDAD
// ============================

$ingresos = getTotal($conexion,"
    SELECT IFNULL(SUM(valor),0) total
    FROM movimientos_caja
    WHERE tipo='INGRESO'
");

$egresos = getTotal($conexion,"
    SELECT IFNULL(SUM(valor),0) total
    FROM movimientos_caja
    WHERE tipo='EGRESO'
");

$utilidad = $ingresos - $egresos;

$rentabilidad = ($ingresos > 0) 
    ? ($utilidad / $ingresos) * 100 
    : 0;

// ============================
// 💼 INVERSIONISTAS
// ============================

$invertido = getTotal($conexion,"
    SELECT IFNULL(SUM(
        CASE 
            WHEN tipo='APORTE' THEN valor
            WHEN tipo='RETIRO' THEN -valor
            ELSE 0
        END
    ),0) total
    FROM movimientos_inversionista
");

$ganado = getTotal($conexion,"
    SELECT IFNULL(SUM(interes),0) total
    FROM cuotas_prestamo
    WHERE estado='PAGADO'
");

$roi = ($invertido > 0) 
    ? ($ganado / $invertido) * 100 
    : 0;

// ============================
// 📊 GRÁFICAS
// ============================

// Caja por mes
$datosCaja = mysqli_query($conexion,"
    SELECT 
        DATE_FORMAT(fecha,'%Y-%m') as mes,
        SUM(CASE WHEN tipo='INGRESO' THEN valor ELSE -valor END) as total
    FROM movimientos_caja
    GROUP BY mes
    ORDER BY mes ASC
");

$meses = [];
$valoresCaja = [];

while($row = mysqli_fetch_assoc($datosCaja)){
    $meses[] = $row['mes'];
    $valoresCaja[] = $row['total'];
}

// Ingresos vs egresos
$datosFlujo = mysqli_query($conexion,"
    SELECT 
        DATE_FORMAT(fecha,'%Y-%m') as mes,
        SUM(CASE WHEN tipo='INGRESO' THEN valor ELSE 0 END) as ingresos,
        SUM(CASE WHEN tipo='EGRESO' THEN valor ELSE 0 END) as egresos
    FROM movimientos_caja
    GROUP BY mes
    ORDER BY mes ASC
");

$mesesFlujo = [];
$ingresosData = [];
$egresosData = [];

while($row = mysqli_fetch_assoc($datosFlujo)){
    $mesesFlujo[] = $row['mes'];
    $ingresosData[] = $row['ingresos'];
    $egresosData[] = $row['egresos'];
}

// Cartera
$carteraNormal = getTotal($conexion,"
    SELECT IFNULL(SUM(capital),0) total
    FROM cuotas_prestamo
    WHERE estado='PENDIENTE'
");

$carteraMora = getTotal($conexion,"
    SELECT IFNULL(SUM(capital),0) total
    FROM cuotas_prestamo
    WHERE estado='VENCIDO'
");

?>

<style>
.card-box {
    border-radius: 15px;
    padding: 20px;
    color: white;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
.bg-caja { background: linear-gradient(135deg,#28a745,#218838); }
.bg-cartera { background: linear-gradient(135deg,#007bff,#0056b3); }
.bg-mora { background: linear-gradient(135deg,#dc3545,#b02a37); }
.bg-rent { background: linear-gradient(135deg,#6f42c1,#5936a2); }
</style>

<div class="container-fluid mt-4">

<h3 class="mb-4 fw-bold">📊 Dashboard Financiero</h3>

<div class="row">

<div class="col-md-3">
<div class="card-box bg-caja">
<h6>Caja actual</h6>
<h2>$<?= f($caja); ?></h2>
</div>
</div>

<div class="col-md-3">
<div class="card-box bg-cartera">
<h6>Cartera activa</h6>
<h2>$<?= f($cartera); ?></h2>
</div>
</div>

<div class="col-md-3">
<div class="card-box bg-mora">
<h6>% Mora</h6>
<h2><?= number_format($porcentaje_mora,2); ?>%</h2>
</div>
</div>

<div class="col-md-3">
<div class="card-box bg-rent">
<h6>Rentabilidad</h6>
<h2><?= number_format($rentabilidad,2); ?>%</h2>
</div>
</div>

</div>

<hr>

<h4 class="mt-4">💼 Inversionistas</h4>

<div class="row">

<div class="col-md-4">
<div class="card-box bg-cartera">
<h6>Total invertido</h6>
<h2>$<?= f($invertido); ?></h2>
</div>
</div>

<div class="col-md-4">
<div class="card-box bg-caja">
<h6>Total ganado</h6>
<h2>$<?= f($ganado); ?></h2>
</div>
</div>

<div class="col-md-4">
<div class="card-box bg-rent">
<h6>ROI</h6>
<h2><?= number_format($roi,2); ?>%</h2>
</div>
</div>

</div>

<hr>

<!-- 📊 GRÁFICAS -->
<div class="row mt-4">

<div class="col-md-6">
<div class="card p-3">
<h5>📈 Evolución de Caja</h5>
<canvas id="graficaCaja"></canvas>
</div>
</div>

<div class="col-md-6">
<div class="card p-3">
<h5>💰 Ingresos vs Egresos</h5>
<canvas id="graficaFlujo"></canvas>
</div>
</div>

</div>

<div class="row mt-4">

<div class="col-md-6">
<div class="card p-3">
<h5>📊 Cartera</h5>
<canvas id="graficaCartera"></canvas>
</div>
</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

// Caja
new Chart(document.getElementById('graficaCaja'), {
    type: 'line',
    data: {
        labels: <?= json_encode($meses); ?>,
        datasets: [{
            label: 'Caja',
            data: <?= json_encode($valoresCaja); ?>
        }]
    }
});

// Flujo
new Chart(document.getElementById('graficaFlujo'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($mesesFlujo); ?>,
        datasets: [
            {
                label: 'Ingresos',
                data: <?= json_encode($ingresosData); ?>
            },
            {
                label: 'Egresos',
                data: <?= json_encode($egresosData); ?>
            }
        ]
    }
});

// Cartera
new Chart(document.getElementById('graficaCartera'), {
    type: 'doughnut',
    data: {
        labels: ['Al día', 'En mora'],
        datasets: [{
            data: [<?= $carteraNormal ?>, <?= $carteraMora ?>]
        }]
    }
});

</script>

<?php include "../includes/footer.php"; ?>