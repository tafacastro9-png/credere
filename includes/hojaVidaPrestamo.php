<?php
require_once("db.php");

$id = intval($_GET['id']);

$consulta = mysqli_query($conexion,"
SELECT p.*, 

c.nombreClient, c.apellidoClient,

rp.nombreAval AS nombrePersonal,
rp.apellidoAval AS apellidoPersonal,

rf.nombreAval AS nombreFamiliar,
rf.apellidoAval AS apellidoFamiliar,

tp.nombre_tipo,
ep.statusPrest

FROM prestamos p 

INNER JOIN clientes c ON p.id_cliente = c.id 

LEFT JOIN avales rp ON p.id_aval = rp.id
LEFT JOIN avales rf ON p.id_avalFamiliar = rf.id

INNER JOIN tipo_prestamo tp ON p.id_tp = tp.id 
INNER JOIN estado_prestamo ep ON p.id_estp = ep.id

WHERE p.id = $id
");

$data = mysqli_fetch_assoc($consulta);


/* ==============================
   🔥 VALIDAR SI EL PRÉSTAMO ESTÁ RECHAZADO
   ============================== */

$rechazoQuery = mysqli_query($conexion,"
SELECT motivo 
FROM rechazo_documentos
WHERE id_prestamo = $id
LIMIT 1
");

$prestamoRechazado = false;
$motivoRechazo = "";

if($rechazoQuery && mysqli_num_rows($rechazoQuery) > 0){
    $prestamoRechazado = true;
    $rowRechazo = mysqli_fetch_assoc($rechazoQuery);
    $motivoRechazo = $rowRechazo['motivo'];
}
?>

<div class="row g-4">

    <!-- ================= INFORMACIÓN PRÉSTAMO ================= -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0" style="color: var(--bs-primary);">
                        📄 Información del Préstamo
                    </h5>

              
                </div>

                <ul class="list-group list-group-flush">

                    <li class="list-group-item d-flex justify-content-between">
                        <span><b>Folio</b></span>
                        <span><?= $data['folioPrest']; ?></span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between">
                        <span><b>Estado</b></span>
                        <span>
<?php
$estado = trim($data['statusPrest']);

if ($estado == 'Rechazado') {
    echo '<span class="badge bg-danger">Rechazado</span>';
} elseif ($estado == 'Desembolsado') {
    echo '<span class="badge bg-primary">Desembolsado</span>';
} elseif ($estado == 'Aprobado') {
    echo '<span class="badge bg-success">Aprobado</span>';
} else {
    echo '<span class="badge bg-secondary">'.$estado.'</span>';
}
?>
                        </span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between">
                        <span><b>Monto</b></span>
                        <span>$<?= number_format($data['monto_prestado'],0,',','.'); ?></span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between">
                        <span><b>Tipo</b></span>
                        <span><?= $data['nombre_tipo']; ?></span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between">
                        <span><b>Inicio</b></span>
                        <span><?= $data['fecha_inicio']; ?></span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between">
                        <span><b>Vencimiento</b></span>
                        <span><?= $data['fecha_vencimiento']; ?></span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between">
                        <span><b>Fecha Registro</b></span>
                        <span><?= $data['fechaRegistro']; ?></span>
                    </li>

                    <?php if($data['fecha_desembolso']): ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><b>Desembolso</b></span>
                        <span><?= $data['fecha_desembolso']; ?></span>
                    </li>
                    <?php endif; ?>

                </ul>

              

            </div>
        </div>
    </div>


    <!-- ================= CLIENTE Y REFERENCIAS ================= -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">

                <h5 class="fw-bold mb-3" style="color: var(--bs-primary);">
                    👤 Cliente y Referencias
                </h5>

                <ul class="list-group list-group-flush">

                    <li class="list-group-item">
                        <b>Cliente:</b><br>
                        <?= $data['nombreClient'].' '.$data['apellidoClient']; ?>
                    </li>

                    <li class="list-group-item">
                        <b>Referencia Personal:</b><br>
                        <?= $data['nombrePersonal'].' '.$data['apellidoPersonal']; ?>
                    </li>

                    <li class="list-group-item">
                        <b>Referencia Familiar:</b><br>
                        <?= $data['nombreFamiliar'].' '.$data['apellidoFamiliar']; ?>
                    </li>
					
					 <div class="d-flex gap-2 mt-3">

    <a href="../includes/generar_amortizacion.php?id=<?= $id; ?>" 
       target="_blank"
       class="btn btn-success btn-sm">
       📊 Tabla Amortización
    </a>

  <a href="../includes/generar_plan_pagos.php?id=<?= $id; ?>" 
   target="_blank"
   class="btn btn-primary btn-sm">
   Descargar Plan de Pagos
</a>
</div>

                </ul>

            </div>
        </div>
    </div>

</div>

<hr class="my-4">

<!-- ================= DOCUMENTOS ================= -->
<div class="card shadow-sm border-0">
    <div class="card-body">

        <h5 class="fw-bold mb-4" style="color: var(--bs-primary);">
            📂 Documentos Cargados
        </h5>

        <?php
        $docs = mysqli_query($conexion,"
        SELECT * FROM documentos_prestamo
        WHERE id_prestamo = $id
        ORDER BY fecha_subida DESC
        ");

        if($docs && mysqli_num_rows($docs) > 0):
        while($doc = mysqli_fetch_assoc($docs)):

            $nombreBonito = basename($doc['nombre_archivo']);
            $rutaArchivo = $doc['nombre_archivo'];

            $rutaCompleta = "../includes/ver_documento.php?archivo=" . urlencode($rutaArchivo);
        ?>

        <div class="border rounded p-3 mb-3 bg-light">

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <b><?= $nombreBonito; ?></b><br>
                    <small class="text-muted">
                        Subido: <?= $doc['fecha_subida']; ?>
                    </small>
                </div>

             <div>
    <?php
        $estadoPrestamoActual = trim($data['statusPrest']);

        // 1️⃣ Si tiene registro en rechazo_documentos
        if ($prestamoRechazado) {

            echo '<span class="badge bg-danger">Rechazado</span>';

        // 2️⃣ Si NO está rechazado y está en revisión
        } elseif ($estadoPrestamoActual == 'Revision' || $estadoPrestamoActual == 'Revisión') {

            echo '<span class="badge bg-warning text-dark">Pendiente de Revisión</span>';

        // 3️⃣ Si no está rechazado y no está en revisión
        } else {

            echo '<span class="badge bg-success">Aprobado</span>';

        }
    ?>
</div>
            </div>

            <?php if ($prestamoRechazado && !empty($motivoRechazo)): ?>
                <div class="text-danger small mt-2">
                    <b>Motivo:</b> <?= $motivoRechazo; ?>
                </div>
            <?php endif; ?>

           <a href="<?= $rutaCompleta; ?>" 
   target="_blank"
   class="btn btn-sm btn-primary mt-3">
   Ver Documento
</a>

        </div>

        <?php
        endwhile;
        else:
        ?>

        <p class="text-muted">No hay documentos cargados.</p>

        <?php endif; ?>

    </div>
</div>