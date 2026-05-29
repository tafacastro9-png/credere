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
                     <h5 class="modal-title">Reportes Comisiones</h5>
                    <br>
                    <form id="reportescarteraForm">
<div class="row g-3 mb-3">

    <div class="col-md-4">
        <label class="form-label">Tipo de Reporte</label>
        <select id="tipo_reporte" name="tipo_reporte" class="form-control" required>
            <option value="">Seleccione</option>
            <?php
            $sql = "SELECT * FROM tipo_reporte WHERE id in (3)";
            $resultado = mysqli_query($conexion, $sql);
            while ($consulta = mysqli_fetch_array($resultado)) {
                echo '<option value="' . $consulta['id'] . '">' . $consulta['nombre'] . '</option>';
            }
            ?>
        </select>
    </div>

    <div class="col-md-4">
        <label><b>Fecha inicio</b></label>
        <input type="date" name="fecha_inicio" class="form-control" required>
    </div>

    <div class="col-md-4">
        <label><b>Fecha fin</b></label>
        <input type="date" name="fecha_fin" class="form-control" required>
    </div>
	
	<div class="col-md-4 d-none" id="filtro_anio">
    <label><b>Año</b></label>
<select name="anio" id="anio" class="form-control">
    <option value="">Seleccione año</option>
    <option value="TODOS">Todos</option>
    <?php
    $query = mysqli_query($conexion, "
        SELECT DISTINCT YEAR(fecha_inicio) as anio 
        FROM prestamos 
        ORDER BY anio DESC
    ");
    while($row = mysqli_fetch_assoc($query)){
        echo "<option value='{$row['anio']}'>{$row['anio']}</option>";
    }
    ?>
</select>

<div class="col-md-4" id="filtro_cedula">
    <label><b>N° documento</b></label>
    <input type="text" id="cedula" name="cedula" class="form-control" placeholder="Opcional">
</div>

<br>
<div class="col-md-4 d-none" id="filtro_asesor">
    <label>Asesor</label>
    <select id="asesor" class="form-control">
        <option value="">Todos</option>
        <?php
        $q = mysqli_query($conexion, "SELECT * FROM users");
        while($u = mysqli_fetch_assoc($q)){
            echo "<option value='{$u['id']}'>{$u['usuario']}</option>";
        }
        ?>
    </select>
</div>



</div>


<div class="col-md-4 d-none" id="filtro_mes">
    <label><b>Mes</b></label>
    <select name="mes" id="mes" class="form-control">
        <option value="">Seleccione mes</option>
        <option value="1">Enero</option>
        <option value="2">Febrero</option>
        <option value="3">Marzo</option>
        <option value="4">Abril</option>
        <option value="5">Mayo</option>
        <option value="6">Junio</option>
        <option value="7">Julio</option>
        <option value="8">Agosto</option>
        <option value="9">Septiembre</option>
        <option value="10">Octubre</option>
        <option value="11">Noviembre</option>
        <option value="12">Diciembre</option>
    </select>
</div>
	
	

    <div class="col-md-12 mt-3">
        <button type="button" class="btn btn-danger" onclick="generarReporte()">
            📊 Generar Reporte
        </button>
    </div>

</div>
</form>

<div class="table-responsive mt-4">
    <table class="table table-bordered table-striped" id="datatable">
        <thead>
            <tr>
                <th colspan="8" class="text-center">Reporte de cartera</th>
            </tr>
        </thead>
        <tbody id="bodyReporte">
            <tr>
                <td colspan="8" class="text-center text-muted">
                    Genera un reporte para visualizar datos
                </td>
            </tr>
        </tbody>
    </table>
</div>





<button class="btn btn-success" onclick="exportarExcel()">
    📥 Exportar a Excel
</button>
                    

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/prestPagado.js"></script>


<script>
function generarReporte() {

    let tipo = $("#tipo_reporte").val();
    let fecha_inicio = $("input[name='fecha_inicio']").val();
    let fecha_fin = $("input[name='fecha_fin']").val();
    let mes = $("#mes").val();
    let anio = $("#anio").val();
    let cedula = $("#cedula").val();
    let asesor = $("#asesor").val(); // 🔥 NUEVO

    if (!tipo) {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Seleccione tipo de reporte'
        });
        return;
    }

    // 🔴 REPORTE 1 → SOLO AÑO
    if(tipo == 1){
        if(anio === ""){
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: "Seleccione un año o 'Todos'"
            });
            return;
        }
    }

    // 🔵 REPORTE 2 → MES + AÑO (solo si NO hay cédula)
    if(tipo == 2){

        if(cedula.trim() === ""){
            if(!mes || !anio){
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'Seleccione mes y año o ingrese una cédula'
                });
                return;
            }

            // 🚫 Evitar meses futuros
            let hoy = new Date();
            let mesActual = hoy.getMonth() + 1;
            let anioActual = hoy.getFullYear();

            if(anio > anioActual || (anio == anioActual && mes > mesActual)){
                Swal.fire({
                    icon: 'error',
                    title: 'Fecha inválida',
                    text: 'No puedes consultar cartera vencida en meses futuros'
                });
                return;
            }
        }
    }


// 🟣 REPORTE 3 → FLEXIBLE (asesor o fechas)
if(tipo == 3){

    // ✅ Si NO hay asesor → exigir filtro
    if(!asesor){

        // validar mes/año o fechas
        if((!mes || !anio) && (!fecha_inicio || !fecha_fin)){
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Seleccione asesor o un filtro (mes/año o fechas)'
            });
            return;
        }

    }

    // ✅ Si hay asesor → pasa sin validar fechas
}

    // 🟡 OTROS → FECHAS
    if(tipo != 1 && tipo != 2 && tipo != 3){
        if(!fecha_inicio || !fecha_fin){
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Seleccione fechas'
            });
            return;
        }
    }

    // ⏳ Loading
    $("#bodyReporte").html(`
        <tr>
            <td colspan="9" class="text-center">
                ⏳ Generando reporte...
            </td>
        </tr>
    `);

    $.ajax({
        url: "../ajax/reportesComisionesAjax.php",
        type: "POST",
        data: {
            tipo_reporte: tipo,
            fecha_inicio: fecha_inicio,
            fecha_fin: fecha_fin,
            mes: mes,
            anio: anio,
            cedula: cedula,
            asesor: asesor // 🔥 IMPORTANTE
        },
        success: function(response) {
            $("#bodyReporte").html(response);
        },
       error: function(xhr) {
    console.log(xhr.responseText); // 🔥 ESTO ES CLAVE
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Error en servidor, revisa consola'
    });
}
    });
}
</script>

<script>
$("#tipo_reporte").change(function(){

    let tipo = $(this).val();

    // 🔴 OCULTAR TODO PRIMERO
    $("input[name='fecha_inicio']").closest('.col-md-4').addClass("d-none");
    $("input[name='fecha_fin']").closest('.col-md-4').addClass("d-none");
    $("#filtro_anio").addClass("d-none");
    $("#filtro_mes").addClass("d-none");
    $("#filtro_cedula").addClass("d-none");
    $("#filtro_asesor").addClass("d-none"); // 🔥 ocultar asesor por defecto

    // 🟢 REPORTE 1 → SOLO AÑO
    if(tipo == 1){
        $("#filtro_anio").removeClass("d-none");
    }

    // 🔵 REPORTE 2 → MES + AÑO + CÉDULA
    if(tipo == 2){
        $("#filtro_anio").removeClass("d-none");
        $("#filtro_mes").removeClass("d-none");
        $("#filtro_cedula").removeClass("d-none");
    }

    // 🟣 REPORTE 3 → ASESOR (+ opcional mes/año si quieres)
    if(tipo == 3){
        $("#filtro_anio").removeClass("d-none");   // opcional
        $("#filtro_mes").removeClass("d-none");    // opcional
        $("#filtro_asesor").removeClass("d-none"); // 🔥 SOLO aquí aparece asesor
    }

    // 🟡 OTROS → FECHAS
    if(tipo != 1 && tipo != 2 && tipo != 3){
        $("input[name='fecha_inicio']").closest('.col-md-4').removeClass("d-none");
        $("input[name='fecha_fin']").closest('.col-md-4').removeClass("d-none");
    }

});


function exportarExcel(){
    let tipo = $("#tipo_reporte").val();
    let mes = $("#mes").val();
    let anio = $("#anio").val();
    let cedula = $("#cedula").val();
    let asesor = $("#asesor").val();

    window.location.href = `../ajax/exportarExcel.php?tipo=${tipo}&mes=${mes}&anio=${anio}&cedula=${cedula}&asesor=${asesor}`;
}


</script>

<?php include "../includes/footer.php"; ?>