<?php include "../includes/header.php";
date_default_timezone_set('America/Mexico_City');
include "../includes/db.php";
$hoy = date('Y-m-d');
// Datos del préstamo
$query = mysqli_query($conexion, "SELECT p.id AS id_prestamo, p.folioPrest, c.nombreClient, c.apellidoClient, cp.numero_cuota, cp.fecha_pago, 
cp.monto, cp.estado, cp.fecha_pagado, dp.* FROM prestamos p INNER JOIN clientes c ON p.id_cliente = c.id INNER JOIN cuotas_prestamo cp ON cp.id_prestamo = p.id
INNER JOIN detalle_prestamo dp ON dp.id_prestamo = p.id WHERE cp.fecha_pagado = '$hoy' AND cp.estado = 'Pagado' 
ORDER BY cp.fecha_pago ");
?>
<!-- ========== table components start ========== -->
<section class="table-components">
    <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <br>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <div class="card-style mb-30">
                    <h2 class="mb-10 text-center">ABONOS DEL DIA</h2>

                    <br>
                    <br>
                    <div class="table-wrapper table-responsive">
                        <table class="table" id="datatable">
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Cliente</th>
                                    <th>Fecha Pago</th>
                                    <th>#Cuotas</th>
                                    <th>Monto</th>
                                    <th>FechaRegistro</th>
                                    <th>Status</th>
                                    <th>Cronograma</th>
                                </tr>
                                <!-- end table row-->
                            </thead>
                            <tbody>
                                <?php
                                if (mysqli_num_rows($query) > 0) {
                                    while ($cuota = mysqli_fetch_assoc($query)) {
                                        $id = $cuota['id'];
                                        $folioPrest = $cuota['folioPrest'];
                                        $cliente = $cuota['nombreClient'] . ' ' . $cuota['apellidoClient'];
                                        $fecha_pago = $cuota['fecha_pago'];
                                        $fecha_pagado = $cuota['fecha_pagado'];
                                        $cuota_num = $cuota['numero_cuota'];
                                        $num_cuotas = $cuota['num_cuotas'];
                                        $monto = $cuota['monto'];
                                        $encrypted_id = base64_encode($cuota['id']);
                                        // Estado: si es hoy -> Pendiente, si es anterior -> Atrasado
                                        if (!empty($fecha_pagado)) {
                                            $status = 'Pagado';
                                            $bgStatus = 'bg-success text-white';
                                        } elseif ($fecha_pago >= $hoy) {
                                            $status = 'Pendiente';
                                            $bgStatus = 'bg-warning text-dark';
                                        } else {
                                            $status = 'Atrasado';
                                            $bgStatus = 'bg-danger text-white';
                                        }

                                        /*
if (!empty($fecha_pagado)) {
    if ($fecha_pagado > $fecha_pago) {
        $status = 'Pagado con atraso';
        $bgStatus = 'bg-warning text-dark';
    } else {
        $status = 'Pagado';
        $bgStatus = 'bg-success text-white';
    }
} elseif ($fecha_pago >= $hoy) {
    $status = 'Pendiente';
    $bgStatus = 'bg-warning text-dark';
} else {
    $status = 'Atrasado';
    $bgStatus = 'bg-danger text-white';
}

                                        */

                                ?>
                                        <tr>
                                            <td>
                                                <p class="text-sm"><?= htmlspecialchars($folioPrest) ?></p>
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
                                            <td>
                                                <p class="text-sm"><?= htmlspecialchars($fecha_pagado) ?></p>
                                            </td>
                                            <td>
                                                <span class="badge <?= $bgStatus ?> px-3 py-1 rounded-pill"><?= $status ?></span>
                                            </td>
                                            <td>
                                                <a href="ver_cronograma.php?id=<?php echo $encrypted_id; ?>" class="btn btn-warning"><i class="fa fa-calendar-check"></i></a>

                                            </td>
                                        </tr>

                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <p class="text-sm text-muted">No hay cuotas vencidas ni programadas para hoy.</p>
                                        </td>
                                    </tr>

                                <?php } ?>
                            </tbody>
                        </table>
                        <!-- end table -->
                    </div>
                    <br>
                    <br>
                    <h5>GRAFICA DE ABONOS DE LA SEMANA</h5>
                    <!--Ventas de la semana Grafica-->
                    <div class="chart-container" style="height: 400px;">
                        <canvas id="graficaBarras"></canvas>
                    </div>
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- ========== tables-wrapper end ========== -->
    </div>
    <!-- end container -->
</section>
<!-- ========== table components end ========== -->
<script src="../js/Chart/graficaDia.js"></script>
<?php include "../includes/footer.php"; ?>