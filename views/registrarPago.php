<?php
include "../includes/configSession.php";
require_once "../includes/permisos.php";
include "../includes/header.php";

if (!isset($_SESSION['permisos']) || 
    !in_array('prestamos.historialpagos', $_SESSION['permisos'])) {

    echo '
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="card shadow-lg border-0 text-center p-5" style="max-width: 500px; border-radius: 15px;">
            <div class="mb-4">
                <i class="bi bi-shield-lock-fill" style="font-size: 60px; color: #dc3545;"></i>
            </div>
            <h3 class="mb-3 fw-bold text-danger">Acceso Restringido</h3>
            <p class="text-muted mb-4">
                No tienes permisos para acceder al historial de pagos.
                <br>
                Si crees que es un error, contacta al administrador.
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

<section class="table-components">
    <div class="container-fluid">

        <div class="card-style mb-30">

            <!-- TITULO ESTILO CLIENTES -->
            <div class="d-flex align-items-center mb-3">
                <i class="lni lni-credit-cards me-2" style="font-size: 28px; color:#1e3a8a;"></i>
                <h2 class="mb-0" style="color:#1e3a8a; font-weight:600;">
                    Préstamos Desembolsados
                </h2>
            </div>

            <hr style="height:3px; background:#1e3a8a; width:220px; margin-top:-10px; margin-bottom:25px;">

            <!-- DESCRIPCION -->
            <p class="mb-4" style="color:#4b5563; font-weight:500;">
                Busca por folio, cliente o aval para registrar pagos o consultar cronogramas.
            </p>

            <!-- BUSCADOR -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <input type="text" 
                           id="buscadorPrestamos" 
                           autofocus 
                           class="form-control"
                           placeholder="Escribe el número de Folio o nombre del Cliente o Aval">
                </div>
            </div>

            <!-- TABLA -->
            <div class="table-wrapper table-responsive">
                <table class="table align-middle">

                    <thead style="background:#eef2f7;">
                        <tr>
                            <th>Folio</th>
                            <th>Cliente</th>
                            <th>Aval</th>
                            <th>Tipo Préstamo</th>
                            <th>Monto Prestado</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Vencimiento</th>
                            <th>Status</th>
                            <th>Fecha Registro</th>
                            <th>Cronograma</th>
                        </tr>
                    </thead>

                    <tbody id="tablaResultados">
                        <!-- Resultados dinámicos -->
                    </tbody>

                </table>
            </div>

        </div>
    </div>
</section>

<script>
document.getElementById("buscadorPrestamos").addEventListener("keyup", function() {

    const consulta = this.value.trim();

    if (consulta.length === 0) {
        document.getElementById("tablaResultados").innerHTML = "";
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../includes/searchPrestmoAutorizado.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById("tablaResultados").innerHTML = xhr.responseText;
        }
    };

    xhr.send("consulta=" + encodeURIComponent(consulta));
});
</script>

<?php include "../includes/footer.php"; ?>