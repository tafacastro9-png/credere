<?php 
include "../includes/configSession.php";
require_once "../includes/permisos.php";
require_once "../includes/db.php";
include "../includes/header.php";

if (!isset($_SESSION['permisos']) || 
    !in_array('abonosnopagados.ver', $_SESSION['permisos'])) {

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


date_default_timezone_set('America/Bogota');
?>
<style>
    .chart-container {
        position: relative;
        margin: auto;
        height: 400px;
        width: 100%;
    }

    .control {

        /* width: 100%; */
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #6e707e;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }
</style>

<!-- ========== table components start ========== -->
<section class="table-components">
    <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <br>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <div class="card-style mb-30">
                    <h3 class="mb-10 text-center">INGRESOS PENDIENTES DE ABONOS NO PAGADOS POR MES</h3>
                    <div class="graficass">
                        <br>
                        <label for="for-label">Seleccionar Año</label>
                        <select id="selectYear" class="control" onChange="mostrarResultados(this.value);">
                            <?php
                            $yearActual = date('Y');
                            for ($i = 2022; $i < 2050; $i++) {
                                if ($i == $yearActual) {
                                    echo '<option value="' . $i . '" selected>' . $i . '</option>';
                                } else {
                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <br>


                    <br>

                    <!--Ventas de la semana Grafica-->
                    <div class="chart-container">
                        <!-- Aquí es donde se renderizará la gráfica de barras -->
                        <canvas id="graficaBarras"></canvas>
                    </div>
                    <br>

                    <div class="total-ganancias-container">
                        <h3 id="totalGanancias">Total de ingresos del año: $0.00</h3>
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


<?php include "../includes/footer.php"; ?>
<script src="../js/Chart/graficaSinAbonoMes.js"></script>
<script src="../js/totalSinAbonosPagados.js"></script>