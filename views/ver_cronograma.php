<?php 
include "../includes/configSession.php";
require_once "../includes/permisos.php";
require_once("../includes/db.php");
include "../includes/header.php";

if (!isset($_SESSION['permisos']) || 
    !in_array('prestamos.vercronograma', $_SESSION['permisos'])) {

    echo '<div class="container text-center p-5">
        <h3 class="text-danger">Acceso Restringido</h3>
        <a href="index.php" class="btn btn-primary">Volver</a>
    </div>';
    exit;
}

$encrypted_id = $_GET['id'];
$decrypted_id = base64_decode($encrypted_id);

if (!$decrypted_id || !is_numeric($decrypted_id)) {
    echo "<script>alert('ID inválido');location.assign('index.php');</script>";
    exit();
}

$query = mysqli_query($conexion, "SELECT p.*, c.nombreClient, c.apellidoClient 
FROM prestamos p 
INNER JOIN clientes c ON p.id_cliente = c.id 
WHERE p.id = $decrypted_id");

$prestamo = mysqli_fetch_assoc($query);

$totalCartera = mysqli_fetch_assoc(mysqli_query($conexion, "
SELECT SUM(IFNULL(saldo, monto)) as total 
FROM cuotas_prestamo 
WHERE id_prestamo = $decrypted_id
"))['total'] ?? 0;

$totalPagado = mysqli_fetch_assoc(mysqli_query($conexion, "
SELECT SUM(valor) as total 
FROM pagos_cuotas pc
INNER JOIN cuotas_prestamo c ON pc.id_cuota = c.id
WHERE c.id_prestamo = $decrypted_id
"))['total'] ?? 0;

$totalMora = mysqli_fetch_assoc(mysqli_query($conexion, "
SELECT SUM(IFNULL(saldo, monto)) as total 
FROM cuotas_prestamo 
WHERE id_prestamo = $decrypted_id
AND estado = 'mora'
"))['total'] ?? 0;

$porcentaje = $totalCartera > 0 ? ($totalPagado / $totalCartera) * 100 : 0;
?>

<style>
.table-wrapper { overflow-x:auto; }
.table { min-width:1100px; }
table td, table th { white-space:nowrap; }
.progress { height:8px; }
.card-style {
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    padding: 25px;
    border-radius: 10px;
    background: #fff;
}
</style>

<section class="table-components">
<div class="container-fluid">
<br><br>

<div class="row">
<div class="col-lg-12">
<div class="card-style mb-30">

<h3 style="font-weight:700; color:#1b2a4e;">
📅 Cronograma de Pagos - <?= $prestamo['nombreClient']." ".$prestamo['apellidoClient'] ?>
</h3>
<hr>

<div class="table-wrapper">
<table class="table table-hover">
<thead>
<tr>
<th>#</th>
<th>Fecha</th>
<th>Monto</th>
<th>Saldo</th>
<th>Pagado</th>
<th>Días Mora</th>
<th>Progreso</th>
<th>Acción</th>
<th>Historial</th>
</tr>
</thead>

<tbody>

<?php
$cuotas = mysqli_query($conexion, "
SELECT * FROM cuotas_prestamo 
WHERE id_prestamo = $decrypted_id
ORDER BY numero_cuota ASC
");

while ($row = mysqli_fetch_assoc($cuotas)) {

    // --- AJUSTE DE LÓGICA EN TIEMPO REAL ---
    $id_cuota_actual = $row['id'];
    
    // 1. Consultar abonos reales directamente
    $qPagosReal = mysqli_query($conexion, "SELECT SUM(valor) as total FROM pagos_cuotas WHERE id_cuota = $id_cuota_actual");
    $pagado_real = mysqli_fetch_assoc($qPagosReal)['total'] ?? 0;

    // 2. Recalcular saldo real
    $monto_original = floatval($row['monto']);
    $saldo_real = $monto_original - $pagado_real;

    // 3. Determinar estado visual según saldo y fecha
    $hoy = date('Y-m-d');
    if ($saldo_real <= 0) {
        $badge = "<span class='badge bg-success'>Pagado</span>";
        $es_pagado = true;
    } else {
        $es_pagado = false;
        if ($row['fecha_pago'] < $hoy) {
            $badge = "<span class='badge bg-danger'>Mora</span>";
        } else {
            $badge = "<span class='badge bg-warning text-dark'>Pendiente</span>";
        }
    }

    // 4. Días Mora
    $diasMora = 0;
    if(!$es_pagado && $row['fecha_pago'] < $hoy){
        $diasMora = floor((strtotime($hoy) - strtotime($row['fecha_pago'])) / 86400);
    }

    // 5. Progreso
    $progreso = ($pagado_real / $monto_original) * 100;
    $progreso = min($progreso, 100);

    echo "<tr>
    <td>{$row['numero_cuota']}</td>
    <td>{$row['fecha_pago']}</td>
    <td>$".number_format($monto_original,0,',','.')."</td>
    <td class='fw-bold'>$".number_format($saldo_real,0,',','.')."</td>
    <td>$".number_format($pagado_real,0,',','.')."</td>
    <td>".($diasMora>0?"<span class='text-danger'>{$diasMora}</span>":"-")."</td>

    <td>
        <div style='display:flex; align-items:center; gap:10px; min-width:180px;'>
            <div class='progress' style='flex:1;'>
                <div class='progress-bar bg-success' style='width:{$progreso}%'></div>
            </div>
            $badge
        </div>
    </td>

    <td>";

    if(!$es_pagado){
        echo "<button class='btn btn-success btn-sm marcar-pago ms-2'
                data-id='{$row['id']}'
                data-saldo='{$saldo_real}'>
                Pagar
            </button>";
    }

    echo "</td>

    <td>
        <button class='btn btn-sm btn-outline-primary ver-historial'
        data-id='{$row['id']}'>
        🔍
        </button>
    </td>

    </tr>";
}
?>

</tbody>
</table>
</div>

</div>
</div>
</div>
</div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.marcar-pago').forEach(btn => {
        btn.addEventListener('click', () => {
            let id = btn.dataset.id;
            let saldo = parseFloat(btn.dataset.saldo);

            if (typeof Swal === 'undefined') {
                console.error('SweetAlert2 no está cargado');
                return;
            }

            Swal.fire({
                title: 'Registrar pago',
                width: '450px',
                html: `
                    <div style="display: flex; gap: 10px; align-items: center; justify-content: center; margin-bottom: 15px;">
                        <input type="number" id="valorPago" class="swal2-input" 
                               style="margin: 0; width: 65%;"
                               placeholder="Valor a pagar" value="${saldo}" min="1" max="${saldo}">
                        <button id="btnTotal" type="button" class="swal2-confirm swal2-styled" 
                                 style="background:#3085d6; margin: 0; padding: 10px 15px; flex-grow: 1;">
                            💰 Todo
                        </button>
                    </div>

                    <select id="medioPago" class="swal2-input" style="width: 100%; margin: 10px 0;">
                        <option value="">Seleccione medio de pago</option>
                        <option value="EFECTIVO">Efectivo</option>
                        <option value="TRANSFERENCIA">Transferencia</option>
                    </select>

                    <div id="grupoTransferencia" style="display:none;">
                        <select id="banco" class="swal2-input" style="width: 100%; margin: 10px 0;">
                            <option value="">Seleccione el banco</option>
                            <option value="Bancolombia">Bancolombia</option>
                            <option value="Nequi">Nequi</option>
                            <option value="Daviplata">Daviplata</option>
                            <option value="Davivienda">Davivienda</option>
                            <option value="Banco de Bogotá">Banco de Bogotá</option>
                        </select>
                        <input type="text" id="cuenta" class="swal2-input" 
                               style="width: 100%; margin: 10px 0;"
                               placeholder="Número de cuenta">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Pagar',
                cancelButtonText: 'Cancelar',
                didOpen: () => {
                    const medio = document.getElementById('medioPago');
                    const grupoTrans = document.getElementById('grupoTransferencia');
                    const btnTotal = document.getElementById('btnTotal');
                    const valorInput = document.getElementById('valorPago');

                    btnTotal.addEventListener('click', () => {
                        valorInput.value = saldo;
                    });

                    medio.addEventListener('change', () => {
                        if (medio.value === 'TRANSFERENCIA') {
                            grupoTrans.style.display = 'block';
                        } else {
                            grupoTrans.style.display = 'none';
                        }
                        Swal.update();
                    });
                },
                preConfirm: () => {
                    const valor = document.getElementById('valorPago').value;
                    const medio = document.getElementById('medioPago').value;
                    const banco = document.getElementById('banco').value;
                    const cuenta = document.getElementById('cuenta').value;

                    if (!valor || valor <= 0) return Swal.showValidationMessage('Ingrese un valor');
                    if (!medio) return Swal.showValidationMessage('Seleccione medio de pago');
                    if (medio === 'TRANSFERENCIA' && (!banco || !cuenta)) {
                        return Swal.showValidationMessage('Complete los datos de transferencia');
                    }

                    return { valor, medio, banco, cuenta };
                }
            }).then(result => {
                if (result.isConfirmed) {
                    let { valor, medio, banco, cuenta } = result.value;

                    fetch('../includes/registrar_pago.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: `id=${id}&valor=${valor}&medio=${medio}&banco=${banco}&cuenta=${cuenta}`
                    })
                    .then(r => r.json())
                    .then(data => {
                      if (data.success) {

    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: data.message,
        confirmButtonText: 'OK'
    })
    .then((result) => {

        if(result.isConfirmed){

            // Abrir comprobante
            window.open(
                '../includes/reportes/comprobante_cuota.php?id=' + btoa(id),
                '_blank'
            );

            // Recargar página
            location.reload();
        }

    });

} else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Error de conexión con el servidor', 'error');
                    });
                }
            });
        });
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('.ver-historial').forEach(btn => {

        btn.addEventListener('click', () => {

            let id = btn.dataset.id;

            fetch('../includes/historial_pagos.php?id=' + id)
            .then(r => r.json())
            .then(data => {

                if(!data.success){
                    Swal.fire('Error', data.message, 'error');
                    return;
                }

                let html = `<table class="table table-sm">
                <thead>
                <tr>
                <th>Fecha</th>
                <th>Valor</th>
                <th>Medio</th>
                <th>Banco</th>
                <th>Cuenta</th>
                <th>Acción</th>
                </tr>
                </thead><tbody>`;

                if(data.pagos.length === 0){
                    html += `<tr><td colspan="6">Sin pagos</td></tr>`;
                }else{
                    data.pagos.forEach(p => {
                        html += `<tr>
                        <td>${p.fecha}</td>
                        <td>$${Number(p.valor).toLocaleString()}</td>
                        <td>${p.medio}</td>
                        <td>${p.banco ?? '-'}</td>
                        <td>${p.cuenta ?? '-'}</td>
                     <td class="d-flex gap-2">

    <a href="../includes/reportes/comprobante_cuota.php?id=${btoa(p.id_cuota)}"
       target="_blank"
       class="btn btn-primary btn-sm"
       title="Imprimir Ticket">

       <i class="fa fa-file-text"></i>

    </a>

    <button class="btn btn-danger btn-sm btn-reversar"
    data-id="${p.id}">

        ↩ Reversar

    </button>

</td>
                        </tr>`;
                    });
                }

                html += `</tbody></table>`;

                Swal.fire({
                    title: 'Historial de Pagos',
                    html: html,
                    width: 700,
                    didOpen: () => {

                        document.querySelectorAll('.btn-reversar').forEach(btn => {

                            btn.addEventListener('click', () => {

                                let id_pago = btn.dataset.id;
                                
                                Swal.fire({
                                    title: '¿Reversar este pago?',
                                    text: 'Solo se eliminará este abono',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Sí, reversar'
                                }).then(result => {

                                    if(result.isConfirmed){

                                        fetch('../includes/reversar_pago.php', {
                                            method: 'POST',
                                            headers: {'Content-Type':'application/x-www-form-urlencoded'},
                                            body: "id_pago=" + id_pago
                                        })
                                        .then(r=>r.json())
                                        .then(data=>{

                                            if(data.success){
                                                Swal.fire('OK', data.message, 'success')
                                                .then(()=>location.reload());
                                            }else{
                                                Swal.fire('Error', data.message, 'error');
                                            }

                                        });

                                    }

                                });

                            });

                        });

                    }
                });

            });

        });

    });

});
</script>

<?php include "../includes/footer.php"; ?>