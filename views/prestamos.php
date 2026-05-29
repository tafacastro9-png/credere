<?php 
include "../includes/configSession.php";
require_once "../includes/permisos.php";
require_once("../includes/db.php"); 
include "../includes/header.php";

if (!isset($_SESSION['permisos']) || 
    !in_array('prestamos.ver', $_SESSION['permisos'])) {
?>

<script>
// ============================
// 🔹 FUNCION PARAMETROS
// ============================

function obtenerParametro($conexion, $nombre){
    $q = mysqli_query($conexion, "
        SELECT valor 
        FROM parametros 
        WHERE nombre = '$nombre'
        LIMIT 1
    ");
    
    $row = mysqli_fetch_assoc($q);
    return floatval($row['valor'] ?? 0);
}

// 🔹 porcentaje seguro
$seguro_porcentaje = ($tipo == "CREDITO") 
    ? obtenerParametro($conexion, 'SEGURO_CREDITO') 
    : 0;

// 🔹 valor total del seguro
$seguro_valor = $monto * ($seguro_porcentaje / 100);

// 🔹 seguro distribuido por cuota
$seguro_por_cuota = $plazo > 0 ? ($seguro_valor / $plazo) : 0;

// 🔹 cuota final
$cuota_con_seguro = $cuota + $seguro_por_cuota;

</script>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card shadow-lg border-0 text-center p-5" style="max-width: 500px; border-radius: 15px;">
        
        <div class="mb-4">
            <span class="mdi mdi-shield-lock" style="font-size: 60px; color: #dc3545;"></span>
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





<?php
include "../includes/footer.php";
exit;
}
?>
<style>

/* ============================= */
/* VARIABLES DE COLOR */
/* ============================= */

:root {
    --color-primary: #6424ff;
    --color-secondary: #249cff;
    --color-success: #40edbf;
    --color-dark: #000a38;
    --color-white: #ffffff;
}

/* ============================= */
/* TITULOS */
/* ============================= */

.titulo-seccion {
    color: var(--color-dark);
    font-weight: 700;
    font-size: 22px;
}

/* ============================= */
/* BOTON PRINCIPAL (FUERA DE TABLA) */
/* ============================= */

.btn-main {
    background: var(--color-primary) !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 10px;
    padding: 10px 20px;
    font-weight: 600;
    transition: 0.2s ease;
}

.btn-main:hover {
    background: var(--color-secondary) !important;
    color: #ffffff !important;
}

/* ============================= */
/* BADGES */
/* ============================= */

.badge-aprobado {
    background: var(--color-success) !important;
    color: var(--color-dark) !important;
}

.badge-pendiente {
    background: var(--color-secondary) !important;
    color: #ffffff !important;
}

.badge-rechazado {
    background: #ff4d4d !important;
    color: #ffffff !important;
}

/* ============================= */
/* TABLA */
/* ============================= */

table thead {
    background: #f4f7ff;
    color: var(--color-dark);
}

table tbody tr:hover {
    background: #f1f5ff;
}

/* ============================= */
/* BOTONES BASE */
/* ============================= */

.btn-action {
    border-radius: 8px;
    padding: 6px 10px;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    border: none !important;
    transition: 0.2s ease;
}

.btn-action i {
    font-size: 14px;
    color: inherit !important;
}

/* ========================================= */
/* 🔥 PROTECCIÓN CONTRA BOOTSTRAP */
/* ========================================= */

.table td .btn {
    background-image: none !important;
    box-shadow: none !important;
}

/* ============================= */
/* BOTONES POR TIPO DE ACCION */
/* ============================= */

/* 📄 PDF */
.btn-darkblue {
    background: #000a38 !important;
    color: #ffffff !important;
}

.btn-darkblue:hover {
    background: #1a237e !important;
}

/* ✏ EDITAR */
.btn-edit {
    background: #249cff !important;
    color: #ffffff !important;
}

.btn-edit:hover {
    background: #1976d2 !important;
}

/* ℹ INFO */
.btn-info-soft {
    background: #6424ff !important;
    color: #ffffff !important;
}

.btn-info-soft:hover {
    background: #5120cc !important;
}

/* 👁 VER */
.btn-view {
    background: #28a745 !important;
    color: #ffffff !important;
}

.btn-view:hover {
    background: #218838 !important;
}

/* ⬆ SUBIR */
.btn-upload {
    background: #ff9800 !important;
    color: #ffffff !important;
}

.btn-upload:hover {
    background: #e68900 !important;
}

/* 🗑 ELIMINAR */
.btn-danger-soft {
    background: #ff4d4d !important;
    color: #ffffff !important;
}

.btn-danger-soft:hover {
    background: #c82333 !important;
}

/* REVISIÓN - MORADO CORPORATIVO */
.badge-revision {
    background: var(--color-primary) !important; /* #6424ff */
    color: #ffffff !important;
}

.badge-pendientedesembolso {
    background: var(--color-dark) !important; /* #000a38 */
    color: #ffffff !important;
}


.titulo-modulo {
    font-size: 28px;
    font-weight: 700;
    color: #0b1e4f;
    padding-bottom: 8px;
    border-bottom: 4px solid #0b1e4f;
    display: inline-block;
    letter-spacing: 0.5px;
}
.titulo-modulo i {
    color: #1e3a8a;
}
#modalHojaVida .modal-header {
    background-color: #0d6efd; /* opcional si quieres azul */
}

#modalHojaVida .modal-title {
    color: #ffffff;
}

/* 🔥 FIX SWEETALERT SCROLL */
.swal2-overflow-fix {
    overflow-x: hidden !important;
}

.swal2-popup {
    font-size: 14px !important;
}

.swal2-input, .swal2-select {
    width: 100% !important;
    margin: 5px 0 10px 0 !important;
}

</style>


<section class="table-components">
<div class="container-fluid">
<br><br>

  

<div class="row">
<div class="col-lg-12">
<div class="card-style mb-30">

<div class="d-flex justify-content-between align-items-center mb-4">

	


                    <div class="titulo-modulo mb-4">
     <i class="fa fa-hand-holding-usd me-2"></i>
    Gestión de Préstamos
</div>








<?php if (tienePermiso('prestamos.crear', $conexion)) { ?>
<a href="form_prestamo.php" class="btn btn-main btn-sm px-4">
    <i class="fa fa-plus me-1"></i> Nuevo Préstamo
</a>
<?php } ?>

</div>

<br><br>


<div class="row mb-3 align-items-end">

    <div class="col-md-4">
        <label class="fw-bold mb-1">Filtrar por estado:</label>
        <select id="filtroEstado" class="form-select">
            <option value="">Todos</option>
            <option value="Radicado">Radicado</option>
            <option value="Desembolsado">Desembolsado</option>
            <option value="Pendiente desembolso">Pendiente desembolso</option>
            <option value="Revision">Revision</option>
        </select>
    </div>

    <div class="col-md-3">
        <label class="fw-bold mb-1">Desde</label>
        <input type="date" id="fechaDesde" class="form-control">
    </div>

    <div class="col-md-3">
        <label class="fw-bold mb-1">Hasta</label>
        <input type="date" id="fechaHasta" class="form-control">
    </div>

</div>

<div class="table-wrapper table-responsive">
<table class="table" id="datatable">
<thead>
<tr>
<th>Estado</th>
<th>Folio</th>
<th>Cliente</th>
<th>Aval</th>
<th>Tipo Prestamo</th>
<th>Monto</th>
<th>Inicio</th>
<th>Vencimiento</th>
<th>FechaRegistro</th>
<th>Acciones</th>
</tr>
</thead>

<tbody>

<?php
$result = mysqli_query($conexion, "
SELECT p.*, 
c.nombreClient, c.apellidoClient,
a.nombreAval, a.apellidoAval,
tp.nombre_tipo,
ep.statusPrest
FROM prestamos p 
INNER JOIN clientes c ON p.id_cliente = c.id 
INNER JOIN avales a ON p.id_aval = a.id 
INNER JOIN tipo_prestamo tp ON p.id_tp = tp.id 
INNER JOIN estado_prestamo ep ON p.id_estp = ep.id
WHERE p.id_estp IN (1,2,3,4,6)
");

while ($fila = mysqli_fetch_assoc($result)) :

// 🔴 CONTAR RECHAZOS
$consultaRechazos = mysqli_query($conexion, "
SELECT COUNT(*) as total 
FROM rechazo_documentos 
WHERE id_prestamo = {$fila['id']}
");

$totalRechazos = mysqli_fetch_assoc($consultaRechazos)['total'];
?>

<tr>

<td data-estado="<?= $fila['statusPrest']; ?>">
<?php
$badge = 'badge-pendiente';

if($fila['id_estp']==1) $badge='badge-pendiente';
if($fila['id_estp']==2) $badge='badge-revision';
if($fila['id_estp']==3) $badge='badge-pendientedesembolso';
if($fila['id_estp']==4) $badge='badge-rechazado';
if($fila['id_estp']==6) $badge='badge-aprobado';
?>

<span class="badge <?= $badge; ?>">
<?= $fila['statusPrest']; ?>
</span>

<?php if($fila['id_estp'] == 6 && !empty($fila['fecha_desembolso'])): ?>
    <br>
    <small class="text-success fw-bold">
        📅 <?= date("d M Y", strtotime($fila['fecha_desembolso'])); ?>
    </small>
<?php endif; ?>

<?php if($totalRechazos > 0): ?>
<span class="badge bg-danger ms-1"
      style="cursor:pointer;"
      onclick="mostrarHistorialRechazos(<?= $fila['id']; ?>)">
    <i class="fa fa-history"></i>
    Devuelto (<?= $totalRechazos ?>)
</span>
<?php endif; ?>
</td>

<td><?= $fila['folioPrest']; ?></td>
<td><?= $fila['nombreClient'].' '.$fila['apellidoClient']; ?></td>
<td><?= $fila['nombreAval'].' '.$fila['apellidoAval']; ?></td>
<td><?= $fila['nombre_tipo']; ?></td>
<td>$<?= number_format($fila['monto_prestado'],0,',','.'); ?></td>
<td><?= $fila['fecha_inicio']; ?></td>
<td>
<?= ($fila['fecha_vencimiento'] && $fila['fecha_vencimiento'] != '0000-00-00')
    ? $fila['fecha_vencimiento']
    : '-' ?>
</td>
<td><?= $fila['fechaRegistro']; ?></td>







<td class="d-flex align-items-center gap-2">

<?php if($fila['id_estp'] == 1): ?> 
    <!-- ============================= -->
    <!-- ESTADO 1 - AUTORIZADO -->
    <!-- ============================= -->
	
<?php if (tienePermiso('prestamos.pdf', $conexion)) { ?>
<button class="btn-darkblue btn-sm btn-action btnVerPDF"
        data-id="<?= $fila['id']; ?>"
        title="Generar PDF">
    <i class="fa fa-file-pdf"></i>
</button>
<?php } ?>



    <!-- ✏️ EDITAR -->
<?php if (tienePermiso('prestamos.editar', $conexion)) { ?>
<button class="btn-edit btn-sm btn-action"
        data-bs-toggle="modal"
        data-bs-target="#editar<?= $fila['id']; ?>"
        title="Editar préstamo">
    <i class="fa fa-edit"></i>
</button>
<?php } ?>




<?php endif; ?>


<?php if($fila['id_estp'] == 2): ?> 
    <!-- ============================= -->
    <!-- ESTADO 2 - EN REVISION -->
    <!-- ============================= -->




<?php endif; ?>

<?php if($fila['id_estp'] == 3): ?>
<?php if (tienePermiso('prestamos.desembolsar', $conexion)) { ?>
<button class="btn-main btn-sm btn-action btnDesembolsar"
        data-id="<?= $fila['id']; ?>"
        title="Registrar desembolso">
    <i class="fa fa-money-bill"></i>
</button>
<?php } ?>
<?php endif; ?>





<?php 
// 👁 VER DOCUMENTO SOLO SI EXISTE
$consultaDoc = mysqli_query($conexion, "
SELECT * FROM documentos_prestamo
WHERE id_prestamo = {$fila['id']}
ORDER BY fecha_subida DESC
LIMIT 1
");

$ultimoDoc = mysqli_fetch_assoc($consultaDoc);

/* ==============================
   NUEVA LÓGICA CORRECTA
============================== */

if($fila['id_estp'] == 1): // RADICADO

    if($ultimoDoc && $totalRechazos == 0):
?>

        <!-- 👁 VER DOCUMENTO -->
<button class="btn-view btn-sm btn-action"
        title="Ver documento"
        onclick="verDocumento('<?= $ultimoDoc['nombre_archivo']; ?>', <?= $fila['id']; ?>)">
    <i class="fa fa-eye"></i>
</button>

<?php else: ?>

        <!-- ⬆ SUBIR DOCUMENTO -->
<?php if (tienePermiso('prestamos.subir_documentos', $conexion)) { ?>
<button class="btn-upload btn-sm btn-action"
        title="Subir documentos"
        onclick="abrirModalSubida(<?= $fila['id']; ?>)">
    <i class="fa fa-upload"></i>
</button>
<?php } ?>

<?php 
    endif;

elseif($fila['id_estp'] == 2 && $ultimoDoc): // EN REVISION
?>

    <!-- 👁 VER DOCUMENTO -->
<?php if (tienePermiso('prestamos.ver_documento', $conexion)) { ?>
<button class="btn-view btn-sm btn-action"
        title="Ver documento"
        onclick="verDocumento('<?= $ultimoDoc['nombre_archivo']; ?>', <?= $fila['id']; ?>)">
    <i class="fa fa-eye"></i>
</button>
<?php } ?>

<?php endif; ?>


<?php if (tienePermiso('prestamos.hojavida', $conexion)) { ?>
<button class="btn-info-soft btn-sm btn-action btnHojaVida"
        data-id="<?= $fila['id']; ?>"
        title="Ver hoja de vida">
    <i class="fa fa-search"></i>
</button>
<?php } ?>




</td>


<?php include "./forms/editar_prest.php"; ?>
<?php include "./forms/editar_dprest.php"; ?>


<?php endwhile; ?>

</tbody>
</table>
</div>
</div>
</div>
</div>
</div>





</section>


<!-- ============================= -->
<!-- MODAL DOCUMENTO -->
<!-- ============================= -->

<div class="modal fade" id="modalVerDocumento" tabindex="-1">

    <div class="modal-dialog modal-xl">

        <div class="modal-content">

            <div class="modal-header bg-info text-white">

                <h5 class="modal-title">
                    Validación de Documento
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <!-- BODY -->
            <div class="modal-body p-0">

                <div class="row g-0">

                    <!-- VISOR PDF -->
                    <div class="col-md-9 border-end">

                        <iframe id="iframeDocumento"
                                width="100%"
                                height="700px"
                                style="border:none;">
                        </iframe>

                        <input type="hidden"
                               id="prestamo_id_modal">

                    </div>

                    <!-- PANEL DOCUMENTOS -->
                    <div class="col-md-3 bg-light">

                        <div class="p-3">

                            <h6 class="fw-bold mb-3">
                                Documentos Cargados
                            </h6>

                            <!-- LISTA -->
                            <div id="listaDocumentos">

                                <!-- SE LLENA DINÁMICAMENTE -->

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer flex-column">

                <!-- RECHAZO -->
                <div id="seccionRechazo"
                     class="w-100 mb-3"
                     style="display:none;">

                    <label>
                        Motivo de rechazo
                    </label>

                    <select id="motivoRechazo"
                            class="form-control">

                        <option value="">
                            Seleccione motivo
                        </option>

                        <option value="Documento ilegible">
                            Documento ilegible
                        </option>

                        <option value="Documento incompleto">
                            Documento incompleto
                        </option>

                        <option value="Documento incorrecto">
                            Documento incorrecto
                        </option>

                        <option value="Firma inválida">
                            Firma inválida
                        </option>

                    </select>

                </div>

                <!-- BOTONES -->
                <div class="d-flex justify-content-between w-100">

                    <a id="btnDescargarDocumento"
                       href="#"
                       class="btn btn-secondary"
                       download>

                        Descargar

                    </a>

                    <div>

                        <button class="btn btn-danger"
                                onclick="mostrarRechazo()">

                            ❌ Rechazar

                        </button>

                        <button class="btn btn-success"
                                onclick="aprobarDocumento()">

                            ✔ Aprobar

                        </button>
						
						<button
    class="btn btn-primary ms-2"
    onclick="finalizarValidacion()">

    ✔ Finalizar Validación

</button>

                        <button id="btnConfirmarRechazo"
                                class="btn btn-danger"
                                style="display:none;"
                                onclick="confirmarRechazo()">

                            Confirmar Rechazo

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- ============================= -->
<!-- MODAL VISOR PDF -->
<!-- ============================= -->

<div class="modal fade" id="modalPDF" tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header bg-dark text-white">

                <h5 class="modal-title">
                    Documentos del Crédito
                </h5>

                <?php if (tienePermiso('prestamos.firmar', $conexion)) { ?>

                    <button type="button"
                            class="btn btn-success btn-sm me-2"
                            id="btnFirmarDocumento">

                        ✍ Firmar

                    </button>

                <?php } ?>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body p-0">

                <div class="row g-0">

                    <!-- VISOR -->
                    <div class="col-md-9 border-end">

                        <iframe id="visorPDF"
                                src=""
                                width="100%"
                                height="700px"
                                style="border:none;">
                        </iframe>

                    </div>

                    <!-- PANEL -->
                    <div class="col-md-3 bg-light">

                        <div class="p-3">

                            <h6 class="fw-bold mb-3">
                                Lista de Documentos
                            </h6>

                            <div id="panelListaDocumentos">

                                <!-- SE LLENA DINÁMICAMENTE -->

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- ============================= -->
<!-- JAVASCRIPT -->
<!-- ============================= -->

<script>

function cargarDocumentosPrestamo(idPrestamo){

    fetch('../includes/obtenerDocumentosPrestamo.php?id=' + idPrestamo)

    .then(response => response.text())

    .then(data => {

        document.getElementById(
            "listaDocumentos"
        ).innerHTML = data;

    });

}



function cambiarEstadoDocumento(idDocumento, estado){

    fetch('../includes/cambiarEstadoDocumento.php', {

        method: 'POST',

        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },

        body:
            'id=' + idDocumento +
            '&estado=' + estado

    })

    .then(response => response.text())

    .then(data => {

        alert(data);

        let prestamo =
            document.getElementById(
                'prestamo_id_modal'
            ).value;

        cargarDocumentosPrestamo(prestamo);

    });

}





function verDocumento(nombreArchivo, idPrestamo){

    let ruta =
        "../includes/ver_documento.php?archivo=" +
        encodeURIComponent(nombreArchivo);

    // PDF
    document.getElementById(
        "iframeDocumento"
    ).src = ruta;

    // DESCARGA
    document.getElementById(
        "btnDescargarDocumento"
    ).href = ruta;

    // ID PRESTAMO
    document.getElementById(
        "prestamo_id_modal"
    ).value = idPrestamo;

    // 🔥 CARGAR DOCUMENTOS PANEL LATERAL
    cargarDocumentosPrestamo(idPrestamo);

    // OCULTAR RECHAZO
    document.getElementById(
        "seccionRechazo"
    ).style.display = "none";

    document.getElementById(
        "btnConfirmarRechazo"
    ).style.display = "none";

    // ABRIR MODAL
    new bootstrap.Modal(
        document.getElementById('modalVerDocumento')
    ).show();
}

let documentoSeleccionado = 0;

function verDocumentoPanel(ruta, idDocumento){

    // 🔥 GUARDAR DOCUMENTO ACTUAL
    documentoSeleccionado = idDocumento;

    // PDF
    document.getElementById(
        "iframeDocumento"
    ).src = ruta;

    // DESCARGA
    document.getElementById(
        "btnDescargarDocumento"
    ).href = ruta;

}

function mostrarRechazo(){
document.getElementById("seccionRechazo").style.display = "block";
document.getElementById("btnConfirmarRechazo").style.display = "inline-block";
}



function aprobarDocumento(){

    let id = documentoSeleccionado;

    fetch("../includes/validarDocumento.php", {

        method: "POST",

        headers: {
            "Content-Type":
            "application/x-www-form-urlencoded"
        },

        body:
            "id=" + id +
            "&accion=aprobar"

    })

    .then(response => response.text())

    .then(data => {

        Swal.fire({
            icon: 'success',
            title: 'Documento aprobado',
            showConfirmButton: false,
            timer: 1200
        });

        let idPrestamo = document.getElementById(
            "prestamo_id_modal"
        ).value;

        // 🔥 RECARGAR PANEL
        cargarDocumentosPrestamo(idPrestamo);

    });

}



function confirmarRechazo(){

     let id = documentoSeleccionado;

    let motivo = document.getElementById(
        "motivoRechazo"
    ).value;

    if(motivo == ""){

        Swal.fire({
            icon: 'warning',
            title: 'Motivo requerido',
            text: 'Debe seleccionar un motivo de rechazo.'
        });

        return;
    }

    fetch("../includes/validarDocumento.php", {

        method: "POST",

        headers: {
            "Content-Type":
            "application/x-www-form-urlencoded"
        },

        body:
            "id=" + id +
            "&accion=rechazar" +
            "&motivo=" + encodeURIComponent(motivo)

    })

    .then(response => response.text())

    .then(data => {

        Swal.fire({
            icon: 'success',
            title: 'Documento rechazado',
            showConfirmButton: false,
            timer: 1200
        });

        // 🔥 OCULTAR SECCIÓN
        document.getElementById(
            "seccionRechazo"
        ).style.display = "none";

        document.getElementById(
            "btnConfirmarRechazo"
        ).style.display = "none";

let idPrestamo = document.getElementById(
            "prestamo_id_modal"
        ).value;

        // 🔥 RECARGAR PANEL
        cargarDocumentosPrestamo(idPrestamo);

    });

}


function mostrarRechazo(){

    document.getElementById(
        "seccionRechazo"
    ).style.display = "block";

    document.getElementById(
        "btnConfirmarRechazo"
    ).style.display = "inline-block";

}


/* ======================================
   HISTORIAL COMPLETO DE RECHAZOS
====================================== */

function mostrarHistorialRechazos(idPrestamo){

    fetch("../includes/obtenerHistorialRechazos.php?id=" + idPrestamo)
    .then(response => response.json())
    .then(data => {

        if(data.length === 0){
            Swal.fire({
                icon: 'info',
                title: 'Sin historial',
                text: 'Este préstamo no tiene rechazos registrados.'
            });
            return;
        }

        let html = `
        <div style="text-align:left; max-height:400px; overflow:auto;">
        `;

        data.forEach((item, index) => {
    html += `
        <div style="margin-bottom:15px; padding:12px; background:#f8f9fa; border-radius:8px;">
            <b>Rechazo #${index + 1}</b><br>
            <small><b>Fecha:</b> ${item.fecha}</small><br>
            <small><b>Rechazado por:</b> ${item.usuario ?? 'Sistema'}</small><br><br>
            <b>Motivo:</b><br>
            ${item.motivo}
        </div>
    `;
});


        html += '</div>';

        Swal.fire({
            icon: 'info',
            title: 'Historial Completo de Rechazos',
            html: html,
            width: 650,
            confirmButtonColor: '#3085d6'
        });

    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo cargar el historial.'
        });
    });
}

</script>
<?php if(isset($_GET['msg'])): ?>
<script>
document.addEventListener("DOMContentLoaded", function() {

<?php if($_GET['msg'] == 'subido'): ?>

Swal.fire({
    icon: 'success',
    title: 'Documentos subidos correctamente',
    html: `
        <b>Los documentos fueron cargados exitosamente.</b><br><br>
        El préstamo ahora se encuentra en <b>En revisión</b>.
    `,
    confirmButtonColor: '#28a745'
});

<?php elseif($_GET['msg'] == 'aprobado'): ?>

Swal.fire({
    icon: 'success',
    title: 'Documento aprobado',
    html: `
        <b>Documentos aprobados correctamente.</b><br><br>
        El préstamo ahora se encuentra <b>Pendiente de desembolso</b>.
    `,
    confirmButtonColor: '#28a745'
});

<?php elseif($_GET['msg'] == 'rechazado'): ?>

Swal.fire({
    icon: 'error',
    title: 'Documento rechazado',
    html: `
        <b>El documento fue rechazado correctamente.</b><br><br>
        El préstamo fue devuelto al estado <b>Radicado</b>.
    `,
    confirmButtonColor: '#d33'
});

<?php elseif($_GET['msg'] == 'desembolsado'): ?>

Swal.fire({
    icon: 'success',
    title: 'Desembolso realizado',
    html: `
        <b>El préstamo fue desembolsado correctamente.</b><br><br>
        El estado ahora es <b>Desembolsado</b>.
    `,
    confirmButtonColor: '#28a745'
});

<?php endif; ?>

window.history.replaceState({}, document.title, "prestamos.php");

});
</script>
<?php endif; ?>


<!-- ============================= -->
<!-- MODAL SUBIR DOCUMENTOS -->
<!-- ============================= -->

<div class="modal fade" id="modalSubirDocumentos" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header bg-primary text-white">

                <h5 class="modal-title">
                    Subir Documentación del Crédito
                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                </button>

            </div>

           <form action="../includes/subirDocumentos.php"
      method="POST"
      enctype="multipart/form-data"
      onsubmit="return validarDocumentos()">

                <div class="modal-body">

                    <input type="hidden"
                           name="prestamo_id"
                           id="prestamo_id_upload_modal">

                    <!-- ===================================== -->
                    <!-- DOCUMENTOS DEL CRÉDITO -->
                    <!-- ===================================== -->

                    <div class="mb-4">

                        <label class="form-label fw-bold">

                            Documentos del Crédito
                            <span class="text-danger">*</span>

                        </label>

                        <div id="contenedor_documentos_credito">

                            <input type="file"
                                   name="documentos_credito[]"
                                   class="form-control"
                                   multiple
                                   accept="application/pdf"
								   required>

                            <small class="text-muted">

                                Adjunte pagarés, contratos,
                                solicitudes y documentos relacionados
                                con el crédito.

                            </small>

                        </div>

                    </div>

                    <!-- ===================================== -->
                    <!-- DOCUMENTOS DE IDENTIDAD -->
                    <!-- ===================================== -->

                    <div class="mb-4">

                        <label class="form-label fw-bold">

                            Documentos de Identidad
                            <span class="text-danger">*</span>

                        </label>

                        <div id="contenedor_documentos_identidad">

                            <input type="file"
                                   name="documentos_identidad[]"
                                   class="form-control"
                                   multiple
                                   accept=".pdf,.jpg,.jpeg,.png"
								   required>

                            <small class="text-muted">

                                Adjunte cédula, documentos personales
                                o soportes de identidad.

                            </small>

                        </div>

                    </div>

                    <!-- ===================================== -->
                    <!-- OTROS DOCUMENTOS -->
                    <!-- ===================================== -->

                    <div class="mb-2">

                        <label class="form-label fw-bold">

                            Otros Documentos

                        </label>

                        <div id="contenedor_otros_documentos">

                            <input type="file"
                                   name="otros_documentos[]"
                                   class="form-control"
                                   multiple
                                   accept=".pdf,.jpg,.jpeg,.png">

                            <small class="text-muted">

                                Campo opcional.

                            </small>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Cancelar

                    </button>

                    <button type="submit"
        class="btn btn-success"
        id="btnSubirDocs">

    Subir

</button>

                </div>

            </form>

        </div>

    </div>

</div>



<div class="modal fade" id="modalHojaVida" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">Hoja de Vida del Préstamo</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body" id="contenidoHojaVida">
        <div class="text-center p-4">
          <div class="spinner-border text-primary"></div>
          <p>Cargando información...</p>
        </div>
      </div>

    </div>
  </div>
</div>


<script>
function abrirModalSubida(idPrestamo){

    document.getElementById(
        "prestamo_id_upload_modal"
    ).value = idPrestamo;

    // =====================================
    // CONSULTAR DOCUMENTOS
    // =====================================

    fetch("../includes/consultarDocumentosSubida.php?id=" + idPrestamo)

    .then(response => response.json())

    .then(data => {

        // =====================================
        // DOCUMENTOS DEL CRÉDITO
        // =====================================

        if(data.credito == "Aprobado"){

            document.getElementById(
                "contenedor_documentos_credito"
            ).innerHTML = `

                <div class="alert alert-success p-2 mb-0">
                    ✅ Documento ya aprobado
                </div>

            `;

        }
        else if(data.credito == "Pendiente"){

            document.getElementById(
                "contenedor_documentos_credito"
            ).innerHTML = `

                <div class="alert alert-dark p-2 mb-0">
                    ⏳ Documento en validación
                </div>

            `;

        }
        else{

            document.getElementById(
                "contenedor_documentos_credito"
            ).innerHTML = `

                <input type="file"
                       name="documentos_credito[]"
                       class="form-control"
                       multiple
                       accept="application/pdf">

                <small class="text-muted">

                    Adjunte pagarés, contratos,
                    solicitudes y documentos relacionados
                    con el crédito.

                </small>

            `;
        }

        // =====================================
        // DOCUMENTOS IDENTIDAD
        // =====================================

        if(data.identidad == "Aprobado"){

            document.getElementById(
                "contenedor_documentos_identidad"
            ).innerHTML = `

                <div class="alert alert-success p-2 mb-0">
                    ✅ Documento ya aprobado
                </div>

            `;

        }
        else if(data.identidad == "Pendiente"){

            document.getElementById(
                "contenedor_documentos_identidad"
            ).innerHTML = `

                <div class="alert alert-dark p-2 mb-0">
                    ⏳ Documento en validación
                </div>

            `;

        }
        else{

            document.getElementById(
                "contenedor_documentos_identidad"
            ).innerHTML = `

                <input type="file"
                       name="documentos_identidad[]"
                       class="form-control"
                       multiple
                       accept=".pdf,.jpg,.jpeg,.png">

                <small class="text-muted">

                    Adjunte cédula, documentos personales
                    o soportes de identidad.

                </small>

            `;
        }

        // =====================================
        // OTROS DOCUMENTOS
        // =====================================

        if(data.otros == "Aprobado"){

            document.getElementById(
                "contenedor_otros_documentos"
            ).innerHTML = `

                <div class="alert alert-success p-2 mb-0">
                    ✅ Documento ya aprobado
                </div>

            `;

        }
        else if(data.otros == "Pendiente"){

            document.getElementById(
                "contenedor_otros_documentos"
            ).innerHTML = `

                <div class="alert alert-dark p-2 mb-0">
                    ⏳ Documento en validación
                </div>

            `;

        }
        else{

            document.getElementById(
                "contenedor_otros_documentos"
            ).innerHTML = `

                <input type="file"
                       name="otros_documentos[]"
                       class="form-control"
                       multiple
                       accept=".pdf,.jpg,.jpeg,.png">

                <small class="text-muted">

                    Campo opcional.

                </small>

            `;
        }

    });

    new bootstrap.Modal(
        document.getElementById('modalSubirDocumentos')
    ).show();

}
</script>



<script>

function validarDocumentos(){

    const docCredito = document.querySelector(
        'input[name="documentos_credito[]"]'
    );

    const docIdentidad = document.querySelector(
        'input[name="documentos_identidad[]"]'
    );

    // =====================================
    // VALIDAR DOCUMENTOS CRÉDITO
    // =====================================

    if(
        docCredito &&
        docCredito.offsetParent !== null &&
        docCredito.files.length === 0
    ){

        Swal.close();

        document.getElementById("loaderSubida").style.display = "none";
        document.getElementById("btnSubirDocs").disabled = false;

        Swal.fire({
            icon: 'warning',
            title: 'Documentos requeridos',
            text: 'Debe cargar los documentos del crédito'
        });

        return false;
    }

    // =====================================
    // VALIDAR DOCUMENTOS IDENTIDAD
    // =====================================

    if(
        docIdentidad &&
        docIdentidad.offsetParent !== null &&
        docIdentidad.files.length === 0
    ){

        Swal.close();

        document.getElementById("loaderSubida").style.display = "none";
        document.getElementById("btnSubirDocs").disabled = false;

        Swal.fire({
            icon: 'warning',
            title: 'Documentos requeridos',
            text: 'Debe cargar los documentos de identidad'
        });

        return false;
    }

    // =====================================
    // MOSTRAR LOADER SOLO SI TODO ES VÁLIDO
    // =====================================

    document.getElementById("loaderSubida").style.display = "flex";
    document.getElementById("btnSubirDocs").disabled = true;

    return true;
}
</script>



<script>
function verResumenPrestamo(idPrestamo){
    window.location.href = "resumen_prestamo.php?id=" + idPrestamo;
}
</script>


<script>
document.addEventListener("DOMContentLoaded", function() {

    document.querySelectorAll(".btnDesembolsar").forEach(btn => {

        btn.addEventListener("click", function() {

            let idPrestamo = this.dataset.id;

            Swal.fire({
    title: 'Registrar Desembolso',

    width: 500, // 🔥 AGREGA ESTO
    customClass: {
        popup: 'swal2-overflow-fix' // 🔥 Y ESTO
    },
                html: `
                <div style="text-align:left; width:100%;">

                    <div style="margin-bottom:10px;">
                        <label><b>Fecha de desembolso:</b></label>
                        <input type="datetime-local" id="fechaDesembolso" class="swal2-input" style="width:100%;">
                    </div>

                    <div style="margin-bottom:10px;">
                        <label><b>Medio de desembolso:</b></label>
                        <select id="medioDesembolso" class="swal2-input" style="width:100%;">
                            <option value="">Seleccione</option>
                            <option value="EFECTIVO">Efectivo</option>
                            <option value="TRANSFERENCIA">Transferencia bancaria</option>
                        </select>
                    </div>

                    <div id="bloqueBanco" style="display:none;">

                        <div style="margin-bottom:10px;">
                            <label><b>Banco:</b></label>
                            <select id="banco" class="swal2-input" style="width:100%;">
                                <option value="">Seleccione banco</option>
                                <option value="Bancolombia">Bancolombia</option>
                                <option value="Banco de Bogotá">Banco de Bogotá</option>
                                <option value="Davivienda">Davivienda</option>
                                <option value="BBVA">BBVA</option>
                                <option value="Banco de Occidente">Banco de Occidente</option>
                                <option value="Banco Popular">Banco Popular</option>
                                <option value="Scotiabank Colpatria">Scotiabank Colpatria</option>
                                <option value="Banco AV Villas">Banco AV Villas</option>
                                <option value="Nequi">Nequi</option>
                                <option value="Daviplata">Daviplata</option>
                            </select>
                        </div>

                        <div style="margin-bottom:5px;">
                            <label><b>Número de cuenta:</b></label>
                            <input type="text" id="numeroCuenta" class="swal2-input" placeholder="Ej: 123456789" style="width:100%;">
                        </div>

                    </div>

                </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                confirmButtonColor: '#28a745',
                cancelButtonText: 'Cancelar',

                didOpen: () => {
                    const medio = document.getElementById("medioDesembolso");
                    const bloqueBanco = document.getElementById("bloqueBanco");

                    medio.addEventListener("change", function () {
                        bloqueBanco.style.display =
                            this.value === "TRANSFERENCIA" ? "block" : "none";
                    });
                },

                preConfirm: () => {

                    let fecha = document.getElementById("fechaDesembolso").value;
                    let medio = document.getElementById("medioDesembolso").value;
                    let banco = document.getElementById("banco").value;
                    let cuenta = document.getElementById("numeroCuenta").value;

                    if (!fecha) {
                        Swal.showValidationMessage('Debes ingresar la fecha');
                        return false;
                    }

                    if (!medio) {
                        Swal.showValidationMessage('Selecciona el medio de desembolso');
                        return false;
                    }

                    if (medio === "TRANSFERENCIA") {

                        if (!banco) {
                            Swal.showValidationMessage('Selecciona el banco');
                            return false;
                        }

                        if (!cuenta) {
                            Swal.showValidationMessage('Ingresa el número de cuenta');
                            return false;
                        }
                    }

                    return {
                        fecha,
                        medio,
                        banco,
                        cuenta
                    };
                }

            }).then((result) => {

                if (result.isConfirmed) {

                    let datos = result.value;

                    fetch("../includes/desembolsarPrestamo.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body:
                            "id=" + idPrestamo +
                            "&fecha=" + encodeURIComponent(datos.fecha) +
                            "&medio=" + datos.medio +
                            "&banco=" + encodeURIComponent(datos.banco || "") +
                            "&cuenta=" + encodeURIComponent(datos.cuenta || "")
                    })
                    .then(response => response.json())
                    .then(data => {

                        if(data.status === "error"){

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.msg
                            });

                        } else {

                            Swal.fire({
                                icon: 'success',
                                title: 'Desembolso realizado',
                                text: 'El préstamo fue desembolsado correctamente'
                            }).then(() => {
                                location.reload();
                            });

                        }

                    })
                    .catch(() => {

                        Swal.fire({
                            icon: 'error',
                            title: 'Error inesperado',
                            text: 'No se pudo procesar la solicitud'
                        });

                    });

                }

            });

        });

    });

});
</script>


<script>
document.addEventListener("click", function(e){

    let boton = e.target.closest(".btnVerPDF");

    if(boton){

        let id = boton.getAttribute("data-id");

        let url = "../includes/generar_formato.php?id=" + id;

        document.getElementById("visorPDF").src = url;

        document.getElementById("btnFirmarDocumento")
            .setAttribute("data-id", id);

        let modal = new bootstrap.Modal(
            document.getElementById("modalPDF")
        );

        modal.show();
    }
});
</script>


<script>
document.getElementById("btnFirmarDocumento").addEventListener("click", function(){

    let idPrestamo = this.getAttribute("data-id");

    // Guardamos el id en el modal firma
    document.getElementById("idPrestamoFirma").value = idPrestamo;

    let modalFirma = new bootstrap.Modal(document.getElementById("modalFirma"));
    modalFirma.show();
});


</script>

<script>
document.addEventListener("DOMContentLoaded", function(){

    var canvas = document.getElementById('signature-pad');
    var signaturePad = new SignaturePad(canvas);

    // Limpiar firma
    document.getElementById("limpiarFirma").addEventListener("click", function(){
        signaturePad.clear();
    });

    // Guardar firma
    document.getElementById("guardarFirma").addEventListener("click", function(){

        if(signaturePad.isEmpty()){
            Swal.fire({
                icon: 'warning',
                title: 'Debe firmar primero'
            });
            return;
        }

        let idPrestamo = document.getElementById("idPrestamoFirma").value;
        let firma = signaturePad.toDataURL();

        fetch("../includes/guardar_firma.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "firma=" + encodeURIComponent(firma) +
                  "&id_credito=" + idPrestamo
        })
        .then(response => response.text())
        .then(data => {

            Swal.fire({
                icon: 'success',
                title: 'Firma guardada correctamente'
            });

            bootstrap.Modal.getInstance(
                document.getElementById('modalFirma')
            ).hide();

            // 🔥 Recargar PDF ya firmado
            let iframe = document.getElementById("visorPDF");
            iframe.src = iframe.src;

        });

    });

});

</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
    tooltipTriggerList.map(function (el) {
        return new bootstrap.Tooltip(el)
    })
});
</script>

<script>
document.addEventListener("click", function(e){

    let boton = e.target.closest(".btnHojaVida");

    if(boton){

        let id = boton.getAttribute("data-id");

        let modal = new bootstrap.Modal(
            document.getElementById("modalHojaVida")
        );

        modal.show();

        document.getElementById("contenidoHojaVida").innerHTML = `
            <div class="text-center p-4">
                <div class="spinner-border text-primary"></div>
                <p>Cargando información...</p>
            </div>
        `;

        fetch("../includes/hojaVidaPrestamo.php?id=" + id)
        .then(response => response.text())
        .then(data => {
            document.getElementById("contenidoHojaVida").innerHTML = data;
        });

    }

});

function finalizarValidacion(){

    let idPrestamo = document.getElementById(
        "prestamo_id_modal"
    ).value;

fetch(
    "../views/finalizarValidacion.php",
        {

            method: "POST",

            headers: {
                "Content-Type":
                "application/x-www-form-urlencoded"
            },

            body:
                "id_prestamo=" + idPrestamo
        }
    )

    .then(response => response.text())

    .then(data => {

        console.log(data);

        let json = JSON.parse(data);

        // ======================================
        // APROBADOS
        // ======================================

        if(json.status == "aprobado"){

            Swal.fire({

                icon: 'success',

                title: 'Validación completada',

                text: json.mensaje

            }).then(() => {

                location.reload();

            });

        }

        // ======================================
        // RECHAZADOS
        // ======================================

        else if(json.status == "rechazado"){

            Swal.fire({

                icon: 'warning',

                title: 'Documentos rechazados',

                text: json.mensaje

            }).then(() => {

                location.reload();

            });

        }

        // ======================================
        // PENDIENTES
        // ======================================

else if(json.status == "pendiente"){

    Swal.fire({

        icon: 'info',

        title: 'Documentos pendientes',

        text: json.mensaje

    }).then(() => {

        bootstrap.Modal
            .getInstance(
                document.getElementById(
                    'modalVerDocumento'
                )
            )
            .hide();

    });

}

})

.catch(error => {

    console.log(error);

    alert(error);

});

}




</script>




<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<?php include "./forms/modal_firma.php"; ?>
<script src="../js/filterTable.js"></script>

<!-- ===================================== -->
<!-- LOADER SUBIENDO DOCUMENTOS -->
<!-- ===================================== -->

<div id="loaderSubida" style="
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(255,255,255,0.85);
    z-index:999999;
    justify-content:center;
    align-items:center;
    flex-direction:column;
">

    <div class="spinner-border text-primary"
         style="width:4rem;height:4rem;">
    </div>

    <h4 class="mt-4 fw-bold text-dark">
        Subiendo documentos...
    </h4>

    <p class="text-muted">
        Por favor espere
    </p>

</div>

<script>

document.addEventListener("DOMContentLoaded", function(){

    const formulario = document.querySelector(
        '#modalSubirDocumentos form'
    );



});

</script>


<?php include "../includes/footer.php"; ?>

