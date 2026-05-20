<?php 
include "../includes/configSession.php";
require_once "../includes/permisos.php";
require_once "../includes/db.php";
include "../includes/header.php"; 

if (!isset($_SESSION['permisos']) || 
    !in_array('prestamosfinalizados.ver', $_SESSION['permisos'])) {

    echo '
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="card shadow-lg border-0 text-center p-5" style="max-width: 500px; border-radius: 15px;">
            
            <div class="mb-4">
                <i class="bi bi-shield-lock-fill" style="font-size: 60px; color: #dc3545;"></i>
            </div>

            <h3 class="mb-3 fw-bold text-danger">Acceso Restringido</h3>
            
            <p class="text-muted mb-4">
                No tienes permisos para acceder a este módulo.
                <br>
                Si crees que esto es un error, contacta al administrador.
            </p>

            <a href="index.php" class="btn btn-primary px-4">
                Volver al inicio
            </a>

        </div>
    </div>
    ';
    exit;
}


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
                    <h2 class="mb-10 text-center">PRESTAMOS PAGADOS</h2>
                    <br>
                    <form id="reporteForm">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><b>Del Día</b></label>
                                    <input type="date" name="star" id="star" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><b>Hasta el Día</b></label>
                                    <input type="date" name="fin" id="fin" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><b></b></label> <br>
                                    <button type="button" class="btn btn-outline-primary" id="filtro">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="generarReporte()">
                                        Generar Reporte
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <br>
                   <?php if(in_array('prestamosfinalizados.agregar', $_SESSION['permisos'])): ?>
<a href="form_prestamo.php" class="btn btn-success">Agregar <i class="fa fa-plus"></i></a>
<?php endif; ?>

                    <?php include("./forms/form_prestamo.php"); ?>
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
                                    <th>Ver Cronograma</th>
                                </tr>
                                <!-- end table row-->
                            </thead>
                            <tbody>

                                <?php

                                require_once("../includes/db.php");
                                $result = mysqli_query($conexion, "SELECT p.*, c.folioClient, c.nombreClient, c.apellidoClient,a.nombreAval,a.apellidoAval,tp.nombre_tipo,
                                ep.statusPrest, dp.total_pagar,dp.num_cuotas,dp.monto_cuota,dp.frecuencia_pago,dp.multa_mora FROM prestamos p INNER JOIN clientes c ON p.id_cliente = c.id INNER JOIN avales a ON p.id_aval = a.id 
                                INNER JOIN tipo_prestamo tp ON p.id_tp = tp.id INNER JOIN estado_prestamo ep ON p.id_estp = ep.id INNER JOIN detalle_prestamo dp
                                ON dp.id_prestamo = p.id WHERE p.id_estp = 5");
                                while ($fila = mysqli_fetch_assoc($result)) :
                                    $encrypted_id = base64_encode($fila['id']);

                                ?>
                                    <tr>
                                        <td><?php echo $fila['folioPrest'] ?></td>
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
                                            <td>
<?php if(in_array('prestamosfinalizados.cronograma', $_SESSION['permisos'])): ?>
    <a href="ver_cronograma.php?id=<?php echo $encrypted_id; ?>" class="btn btn-warning">
        <i class="fa fa-calendar-check"></i>
    </a>
<?php endif; ?>
</td>

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

<script src="../js/prestPagado.js"></script>


<?php include "../includes/footer.php"; ?>