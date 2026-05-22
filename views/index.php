<?php
include "../includes/sesion/auth.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../includes/header.php";

$usuario = $_SESSION['usuario'];

date_default_timezone_set('America/Mexico_City');

include "../includes/db.php";

$hoy = date('Y-m-d');


// Datos del préstamo
$query = mysqli_query($conexion, "
SELECT p.id AS id_prestamo, 
       p.folioPrest, 
       c.nombreClient, 
       c.apellidoClient, 
       cp.numero_cuota, 
       cp.fecha_pago, 
       cp.monto, 
       cp.estado, 
       cp.fecha_pagado, 
       dp.*
FROM prestamos p
INNER JOIN clientes c ON p.id_cliente = c.id
INNER JOIN cuotas_prestamo cp ON cp.id_prestamo = p.id
INNER JOIN detalle_prestamo dp ON dp.id_prestamo = p.id
WHERE p.id_estp = 6  -- 🔥 SOLO DESEMBOLSADOS
AND cp.fecha_pago <= '$hoy'
AND cp.estado = 'Pendiente'
ORDER BY cp.fecha_pago ASC 
LIMIT 5
");
if (!$query) {
    die("Error en consulta principal: " . mysqli_error($conexion));
}
?>
<?php
// SQL para la grafica
$sql = "SELECT tc.nombre, COUNT(*) as cantidad FROM prestamos p INNER JOIN tipo_credito tc ON p.id_tipo_credito = tc.id GROUP BY tc.nombre";
$result = mysqli_query($conexion, $sql);

$tipos = [];
$totales = [];

while ($row = mysqli_fetch_assoc($result)) {
    $tipos[] = $row['nombre'];
    $totales[] = $row['cantidad'];
}
?>


<!-- ========== section start ========== -->
<section class="section">
    <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <div class="title-wrapper pt-30">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="title">
                        <h2>Bienvenido De Nuevo <?php echo $usuario; ?></h2>
                    </div>
                </div>

                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- ========== title-wrapper end ========== -->
        <div class="row">
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="icon-card mb-30">
                    <div class="icon success">
                        <i class="lni lni-dollar"></i>
                    </div>
                    <div class="content">
                        <h6 class="mb-10">Abonos Del Dia</h6>
                        <h3 class="text-bold mb-10" id="contador"> <span>$0</span></h3>
                        <p class="text-sm text-success">
                            <i class="lni lni-arrow-up"></i> <a href="abonosDelDia.php">Ir a los detalles</a>

                        </p>
                    </div>
                </div>
                <!-- End Icon Cart -->
            </div>
            <!-- End Col -->
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="icon-card mb-30">
                    <div class="icon purpluse">
                        <i class="lni lni-credit-cards"></i>
                    </div>
                    <div class="content">
                        <h6 class="mb-10">Prestamos Pagados</h6>
                        <h3 class="text-bold mb-10">
                            <?php
                            $SQL = "SELECT id FROM prestamos WHERE id_estp = 5  ORDER BY id";
                            $dato = mysqli_query($conexion, $SQL);
                            $fila = mysqli_num_rows($dato);
                            echo ('#' . $fila); ?></h3>
                        <p class="text-sm text-success">
                            <i class="lni lni-arrow-up"></i> <a href="prestamosPagados.php">Ir a los detalles</a>
                        </p>
                    </div>
                </div>
                <!-- End Icon Cart -->
            </div>
            <!-- End Col -->
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="icon-card mb-30">
                    <div class="icon primary">

                        <span class="mdi mdi-folder-account"></span>
                    </div>
                    <div class="content">
                        <h6 class="mb-10">Prestamos Desembolsados</h6>
                        <h3 class="text-bold mb-10">
                            <?php
                            $SQL = "SELECT id FROM prestamos WHERE id_estp = 6  ORDER BY id";
                            $dato = mysqli_query($conexion, $SQL);
                            $fila = mysqli_num_rows($dato);
                            echo ('#' . $fila); ?>
                        </h3>
                        <p class="text-sm text-success">
                            <i class="lni lni-arrow-up"></i> <a href="prestamos.php">Ir a los detalles</a>
                        </p>
                    </div>
                </div>
                <!-- End Icon Cart -->
            </div>
            <!-- End Col -->
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="icon-card mb-30">
                    <div class="icon orange">
                        <i class="lni lni-user"></i>
                    </div>
                    <div class="content">
                        <h6 class="mb-10">Clientes</h6>
                        <h3 class="text-bold mb-10">
                            <?php
                            $SQL = "SELECT id FROM clientes ORDER BY id";
                            $dato = mysqli_query($conexion, $SQL);
                            $fila = mysqli_num_rows($dato);
                            echo ('#' . $fila); ?>
                        </h3>
                        <p class="text-sm text-success">
                            <i class="lni lni-arrow-up"></i> <a href="clientes.php">Ir a los detalles</a>
                        </p>
                    </div>
                </div>
                <!-- End Icon Cart -->
            </div>
            <!-- End Col -->
        </div>
        <style>
            .chart-container {
                position: relative;
                margin: auto;
                height: 400px;
                width: 80%;
            }
        </style>
        <div class="row">
            <div class="col-lg-5">
                <div class="card-style mb-30">
                    <h6 class="text-medium mb-3">Tipos de préstamos más contratados</h6>
                    <div class="chart-container">
                        <canvas id="prestamosChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- End Col -->
            <div class="col-lg-7">
                <div class="card-style mb-30">
                    <div class="title d-flex flex-wrap align-items-center justify-content-between">
                        <div class="left">
                            <h6 class="text-medium mb-30">Pagos Pendientes <span class="mdi mdi-clipboard-list"></span></h6>
                        </div>

                    </div>
                    <!-- End Title -->
                    <div class="table-responsive">
                        <table class="table top-selling-table">
                            <thead>
                                <tr>
                                    <th>
                                        <h6 class="text-sm text-medium">FolioPrestamo</h6>
                                    </th>
                                    <th>
                                        <h6 class="text-sm text-medium">Cliente</h6>
                                    </th>
                                    <th class="min-width">
                                        <h6 class="text-sm text-medium">
                                            Fecha Pago <i class="lni lni-arrows-vertical"></i>
                                        </h6>
                                    </th>
                                    <th class="min-width">
                                        <h6 class="text-sm text-medium">
                                            #Cuotas <i class="lni lni-arrows-vertical"></i>
                                        </h6>
                                    </th>
                                    <th class="min-width">
                                        <h6 class="text-sm text-medium">
                                            Monto <i class="lni lni-arrows-vertical"></i>
                                        </h6>
                                    </th>
                                    <th>
                                        <h6 class="text-sm text-medium text-end">
                                            Status <i class="lni lni-arrows-vertical"></i>
                                        </h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (mysqli_num_rows($query) > 0) {
                                    while ($cuota = mysqli_fetch_assoc($query)) {
                                        $folioPrest = $cuota['folioPrest'];
                                        $cliente = $cuota['nombreClient'] . ' ' . $cuota['apellidoClient'];
                                        $fecha_pago = $cuota['fecha_pago'];
                                        $cuota_num = $cuota['numero_cuota'];
                                        $num_cuotas = $cuota['num_cuotas'];
                                        $monto = $cuota['monto'];

                                        // Estado: si es hoy -> Pendiente, si es anterior -> Atrasado
                                        $status = ($fecha_pago == $hoy) ? 'Pendiente' : 'En Mora';
                                        $bgStatus = ($status == 'Pendiente') ? 'bg-warning text-dark' : 'bg-danger text-white';
                                ?>
                                        <tr>
                                            <td>
                                                <p class="text-sm" style="color: green;"><?= htmlspecialchars($folioPrest) ?></p>
                                            </td>
                                            <td>
                                                <p class="text-sm"><?= htmlspecialchars($cliente) ?></p>
                                            </td>
                                            <td>
                                                <p class="text-sm"><?= htmlspecialchars($fecha_pago) ?></p>
                                            </td>
                                            <td>
                                                <p class="text-sm"><?= htmlspecialchars($cuota_num) . '/' . $num_cuotas ?></p>
                                            </td>
                                            <td>
                                                <p class="text-sm">$<?= number_format($monto, 2) ?></p>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge <?= $bgStatus ?> px-3 py-1 rounded-pill"><?= $status ?></span>
                                            </td>
                                        </tr>

                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <p class="text-sm text-muted">No hay cuotas vencidas ni programadas para hoy.</p>
                                        </td>
                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>
                        <!-- End Table -->
                        <a href="dashboardCartera.php">Ver la lista completa...</a>
                    </div>
                </div>
            </div>
            <!-- End Col -->
        </div>



    </div>
    <!-- end container -->


</section>
<!-- ========== section end ========== -->

<?php include "../includes/footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../js/contadorCuotas.js"></script>
<script>
    const ctx = document.getElementById('prestamosChart').getContext('2d');
    const prestamosChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: <?= json_encode($tipos) ?>,
            datasets: [{
                label: 'Cantidad de Préstamos',
                data: <?= json_encode($totales) ?>,
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>

