<?php include "../includes/header.php"; ?>


<!-- ========== table components start ========== -->
<section class="table-components">
    <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <br>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <div class="card-style mb-30">
                    <h2 class="mb-10 text-center">PRESTAMOS AUTORIZADOS</h2>
                    <br>
                    <a href="form_prestamo.php" class="btn btn-success">Agregar <i class="fa fa-plus"></i></a>
                    <?php include("./forms/form_prestamo.php"); ?>
                    <button onclick="exportarExcel()" class="btn btn-primary blue">Exportar a Excel <i class="fas fa-download fa-sm text-white-50"></i></button>
                    <br>
                    <br>
                    <div class="table-wrapper table-responsive">
                        <table class="table" id="datatable">
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Cliente</th>
                                    <th>Aval</th>
                                    <th>Tipo Prestamo</th>
                                    <th>Monto Prestado</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Vencimiento</th>
                                    <th>Status</th>
                                    <th>FechaRegistro</th>
                                    <th>Cronograma/Contrato</th>
                                </tr>
                                <!-- end table row-->
                            </thead>
                            <tbody>

                                <?php
                                require_once("../includes/db.php");
                                $result = mysqli_query($conexion, "SELECT p.*, c.folioClient, c.nombreClient, c.apellidoClient,a.nombreAval,a.apellidoAval,tp.nombre_tipo,
                                ep.statusPrest, dp.total_pagar,dp.num_cuotas,dp.monto_cuota,dp.frecuencia_pago,dp.multa_mora FROM prestamos p INNER JOIN clientes c ON p.id_cliente = c.id INNER JOIN avales a ON p.id_aval = a.id 
                                INNER JOIN tipo_prestamo tp ON p.id_tp = tp.id INNER JOIN estado_prestamo ep ON p.id_estp = ep.id INNER JOIN detalle_prestamo dp
                                ON dp.id_prestamo = p.id WHERE p.id_estp = 1");
                                while ($fila = mysqli_fetch_assoc($result)) :
                                    $encrypted_id = base64_encode($fila['id']);
                                ?>

                                    <tr>
                                        <td style="color: green;"><?php echo $fila['folioPrest'] ?></td>
                                        <td><?php echo $fila['nombreClient'] . ' ' . $fila['apellidoClient']; ?></td>
                                        <td><?php echo $fila['nombreAval'] . ' ' . $fila['apellidoAval']; ?></td>
                                        <td><?php echo $fila['nombre_tipo'] ?></td>
                                        <td><?php echo '$' . $fila['monto_prestado'] ?></td>
                                        <td><?php echo $fila['fecha_inicio'] ?></td>
                                        <td><?php echo $fila['fecha_vencimiento'] ?></td>
                                        <td> <span class="badge bg-success ?>">
                                                <?php echo $fila['statusPrest']; ?>
                                            </span></td>
                                        <td><?php echo $fila['fechaRegistro'] ?></td>
                                        <td>
                                            <a href="ver_cronograma.php?id=<?php echo $encrypted_id; ?>" class="btn btn-warning"><i class="fa fa-calendar-check"></i></a>
                                            <a href="../includes/reportes/generar_contrato.php?id=<?php echo $encrypted_id; ?>" target="_blank" class="btn btn-primary" target="_blank">
                                                <i class="fa fa-file-pdf"></i>
                                            </a>
                                        </td>
                                    </tr>

                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <!-- end table -->
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
<script>


function exportarExcel() {
    var tabla = document.getElementById("datatable");
    var libro = XLSX.utils.table_to_book(tabla);
    XLSX.writeFile(libro, "REPORTE_DE_PRESTAMOS_AUTORIZADOS.xlsx");
}
</script>
<?php include "../includes/footer.php"; ?>