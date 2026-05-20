<?php
include "../includes/configSession.php";
require_once "../includes/permisos.php";
require_once("../includes/db.php");
include "../includes/header.php";



header('Content-Type: application/json; charset=utf-8');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (
    !isset($_SESSION['permisos']) ||
    !in_array('gestioncartera.ver', $_SESSION['permisos'])
) {

    echo '
    <div class="container text-center p-5">
        <h3 class="text-danger">Acceso Restringido</h3>
        <a href="index.php" class="btn btn-primary">
            Volver
        </a>
    </div>';

    exit;
}
?>

<style>

body{
    background:#f4f7fb;
}

.card-style{
    border-radius:18px;
    border:none;
    box-shadow:0 6px 20px rgba(0,0,0,0.05);
}

.card-cliente{
    border-radius:18px;
    border:none;
    box-shadow:0 4px 15px rgba(0,0,0,0.08);
    transition:.3s;
    overflow:hidden;
}

.card-cliente:hover{
    transform:translateY(-4px);
}

.badge-mora{
    font-size:12px;
    padding:8px 12px;
    border-radius:30px;
}

.crm-table tbody tr{
    transition:.2s;
}

.crm-table tbody tr:hover{
    background:#f8f9ff;
}

.btn-gestionar{
    border-radius:12px;
    padding:8px 18px;
    font-weight:600;
}

.input-search{
    border-radius:14px;
    padding:14px;
    border:1px solid #dce1ec;
}

.avatar-cliente{
    width:42px;
    height:42px;
    border-radius:50%;
    background:#0d6efd;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:bold;
    font-size:15px;
}

.cliente-info{
    display:flex;
    align-items:center;
    gap:12px;
}

.crm-title{
    font-weight:700;
    color:#1b2559;
}

.crm-subtitle{
    color:#6c757d;
}

.timeline-crm{
    max-height:500px;
    overflow-y:auto;
    padding-right:10px;
}

.timeline-item-crm{
    border-left:4px solid #0d6efd;
    padding-left:15px;
    margin-bottom:25px;
    position:relative;
}

.timeline-item-crm::before{
    content:'';
    position:absolute;
    left:-9px;
    top:5px;
    width:14px;
    height:14px;
    background:#0d6efd;
    border-radius:50%;
}

.timeline-box{
    background:#f8f9fc;
    border-radius:14px;
    padding:15px;
}

</style>

<section class="table-components">
<div class="container-fluid">

<br>
<br>

<div class="card-style p-4 mb-30">

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="crm-title mb-1">
            Gestión de Cartera
        </h2>

        <p class="crm-subtitle mb-0">
            CRM de seguimiento y recuperación de cartera.
        </p>

    </div>

</div>

<div class="row mb-4">

    <div class="col-md-3 mb-3">

        <div class="card card-cliente p-3 bg-primary text-white">

            <h6>Clientes en Mora</h6>

            <h2 id="clientesMora">0</h2>

        </div>

    </div>

    <div class="col-md-3 mb-3">

        <div class="card card-cliente p-3 bg-success text-white">

            <h6>Promesas de Pago</h6>

            <h2 id="promesasPago">0</h2>

        </div>

    </div>

    <div class="col-md-3 mb-3">

        <div class="card card-cliente p-3 bg-warning text-dark">

            <h6>Gestiones Hoy</h6>

            <h2 id="gestionesHoy">0</h2>

        </div>

    </div>

    <div class="col-md-3 mb-3">

        <div class="card card-cliente p-3 bg-danger text-white">

            <h6>Clientes Críticos</h6>

            <h2 id="clientesCriticos">0</h2>

        </div>

    </div>

</div>

<div class="row mb-4">

    <div class="col-md-12">

        <input
        type="text"
        id="buscarCliente"
        class="form-control input-search"
        placeholder="Buscar cliente, teléfono o préstamo...">

    </div>

</div>

<div class="table-wrapper table-responsive">

<table class="table align-middle crm-table">

<thead style="background:#f1f4fa;">

<tr>

    <th>Cliente</th>
    <th>Teléfono</th>
    <th>Préstamo</th>
    <th>Días Mora</th>
    <th>Saldo</th>
    <th>Estado</th>
    <th>Prioridad</th>
    <th>Acción</th>

</tr>

</thead>

<tbody id="tablaGestion"></tbody>

</table>

</div>

</div>

</div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

function cargarGestion(){

    fetch('../includes/getGestionCartera.php')

    .then(r => r.json())

    .then(data => {

        let html = '';

        let mora = 0;
        let criticos = 0;

        data.clientes.forEach(c => {

            let color = 'success';

            if(parseInt(c.dias_mora) >= 30){

                color = 'danger';
                criticos++;

            }else if(parseInt(c.dias_mora) >= 10){

                color = 'warning';

            }

            if(parseInt(c.dias_mora) > 0){

                mora++;

            }

            let prioridad = 'Baja';

            if(parseInt(c.dias_mora) >= 30){

                prioridad = 'Crítica';

            }else if(parseInt(c.dias_mora) >= 10){

                prioridad = 'Media';

            }

            let iniciales = c.cliente
            .split(' ')
            .map(n => n[0])
            .join('')
            .substring(0,2);

            html += `
            <tr>

                <td>

                    <div class="cliente-info">

                        <div class="avatar-cliente">
                            ${iniciales}
                        </div>

                        <div>

                            <strong>
                                ${c.cliente}
                            </strong>

                        </div>

                    </div>

                </td>

                <td>
                    ${c.telefono ?? '-'}
                </td>

                <td>

                    <strong>
                          ${c.prestamo}
                    </strong>

                </td>

                <td>

                    <span class="badge bg-${color} badge-mora">

                        ${c.dias_mora} días

                    </span>

                </td>

                <td>

                    <strong>

                    $${Number(c.saldo).toLocaleString('es-CO')}

                    </strong>

                </td>

                <td>

                    <span class="badge bg-primary">

                        ${c.estado}

                    </span>

                </td>

                <td>

                    <span class="badge bg-${color}">

                        ${prioridad}

                    </span>

                </td>

                <td>

                    <div class="d-flex gap-2">

                        <button
                        class="btn btn-primary btn-gestionar"
                        onclick="abrirGestion(${c.id_cliente}, ${c.id_prestamo})">

                            Gestionar

                        </button>

                        <a
href="https://wa.me/57${c.telefono}"
target="_blank"
class="btn btn-success d-flex align-items-center justify-content-center"
style="
    width:42px;
    height:42px;
    border-radius:12px;
">

    <i class="fa-brands fa-whatsapp"
    style="
        font-size:20px;
        color:white;
    "></i>

</a>

                    </div>

                </td>

            </tr>
            `;
        });

        document.getElementById('tablaGestion').innerHTML = html;

        document.getElementById('clientesMora').innerText = mora;

        document.getElementById('clientesCriticos').innerText =
        data.indicadores.criticos ?? 0;

        document.getElementById('promesasPago').innerText =
        data.indicadores.promesas ?? 0;

        document.getElementById('gestionesHoy').innerText =
        data.indicadores.gestiones_hoy ?? 0;

    })

    .catch(error => {

        console.error(error);

        Swal.fire(
            'Error',
            'No se pudo cargar la gestión de cartera',
            'error'
        );

    });

}

function abrirGestion(id_cliente, id_prestamo){

    fetch(`../includes/getHistorialGestion.php?id_cliente=${id_cliente}&id_prestamo=${id_prestamo}`)

    .then(r => r.json())

    .then(data => {

        let historial = '';

        if(data.historial.length === 0){

            historial = `
            <div class="text-muted">
                Sin gestiones registradas
            </div>
            `;
        }

        data.historial.forEach(h => {

            let fechaPromesaHTML = '';
            let valorPromesaHTML = '';
            let proximaGestionHTML = '';

            if(h.fecha_promesa != null){

                fechaPromesaHTML = `
                <div style="
                    background:#fff8e1;
                    border-radius:10px;
                    padding:10px;
                    margin-bottom:10px;
                    font-size:14px;
                ">
                    📅 <strong>Fecha promesa:</strong>
                    ${h.fecha_promesa}
                </div>
                `;
            }

            if(h.valor_promesa != null){

                valorPromesaHTML = `
                <div style="
                    background:#e8f5e9;
                    border-radius:10px;
                    padding:10px;
                    margin-bottom:10px;
                    font-size:14px;
                ">
                    💰 <strong>Valor promesa:</strong>
                    $${Number(h.valor_promesa).toLocaleString('es-CO')}
                </div>
                `;
            }

            if(h.proxima_gestion != null){

                proximaGestionHTML = `
                <div style="
                    background:#e3f2fd;
                    border-radius:10px;
                    padding:10px;
                    margin-bottom:10px;
                    font-size:14px;
                ">
                    📞 <strong>Próxima gestión:</strong>
                    ${h.proxima_gestion}
                </div>
                `;
            }

            historial += `

            <div class="timeline-item-crm">

                <div class="timeline-box">

                    <div style="
                        display:flex;
                        justify-content:space-between;
                        align-items:center;
                        margin-bottom:12px;
                        flex-wrap:wrap;
                        gap:10px;
                    ">

                        <div>

                            <strong style="
                                color:#1b2559;
                                font-size:16px;
                            ">

                                ${h.tipo_gestion}

                            </strong>

                            <div style="
                                font-size:12px;
                                color:#6c757d;
                                margin-top:2px;
                            ">

                                👤 ${h.usuario}

                            </div>

                        </div>

                        <span style="
                            font-size:12px;
                            color:#6c757d;
                            background:#f1f3f5;
                            padding:6px 12px;
                            border-radius:20px;
                        ">

                            ${h.fecha}

                        </span>

                    </div>

<div class="d-flex gap-2 flex-wrap mb-3">

    <span class="badge bg-primary">

        ${h.resultado ?? 'Sin resultado'}

    </span>

    <span class="badge ${
        h.estado_seguimiento == 'CERRADO'
        ? 'bg-danger'
        : 'bg-success'
    }">

        ${
            h.estado_seguimiento == 'CERRADO'
            ? '🔒 Seguimiento Cerrado'
            : '✅ Seguimiento Activo'
        }

    </span>

</div>
                    ${fechaPromesaHTML}

                    ${valorPromesaHTML}

                    ${proximaGestionHTML}

                    <div style="
                        background:#f8f9fa;
                        padding:14px;
                        border-radius:12px;
                        color:#495057;
                        line-height:1.5;
                        font-size:14px;
                    ">

                        ${h.observacion}

                    </div>

                </div>

            </div>
            `;
        });

        Swal.fire({

            title:false,

            width:1100,

            background:'#f4f7fb',

            showConfirmButton:false,

            showCloseButton:true,

            html:`

            <div style="padding:10px;">

                <h3 style="
                    margin-bottom:20px;
                    font-weight:700;
                    color:#1b2559;
                ">
                    Gestión de Cliente
                </h3>

                <div class="row">

                    <div class="col-md-5">

                        <div style="
                            background:white;
                            border-radius:15px;
                            padding:20px;
                            box-shadow:0 3px 12px rgba(0,0,0,0.05);
                        ">

                            <h5 class="mb-4">
                                Nueva Gestión
                            </h5>
							
							<label class="fw-bold mb-2">
    Tipo Gestión
</label>

                            <select
                            id="tipoGestion"
                            class="form-select mb-3">

                                <option value="Llamada">📞 Llamada</option>
                                <option value="WhatsApp">💬 WhatsApp</option>
                                <option value="Correo">📧 Correo</option>
                                <option value="Visita">🏠 Visita</option>

                            </select>
							
							
							<label class="fw-bold mb-2">
    Resultado
</label>

                            <select
                            id="resultadoGestion"
                            class="form-select mb-3">

                                <option value="No responde">❌ No responde</option>
                                <option value="Promesa de pago">💰 Promesa de pago</option>
                                <option value="Pagara hoy">✅ Pagará hoy</option>
                                <option value="En negociacion">🤝 En negociación</option>

                            </select>
							
							
							<label class="fw-bold mb-2">
    Fecha Promesa
</label>

                            <input
                            type="date"
                            id="fechaPromesa"
                            class="form-control mb-3">
							
							
							<label class="fw-bold mb-2">
    Valor Promesa
</label>

                            <input
                            type="number"
                            id="valorPromesa"
                            class="form-control mb-3"
                            placeholder="Valor promesa">
							
							<label class="fw-bold mb-2">
    Próxima Gestión
</label>

                            <input
                            type="date"
                            id="proximaGestion"
                            class="form-control mb-3">
							
							
							<label class="fw-bold mb-2">
    Observación
</label>

                            <textarea
                            id="observacion"
                            class="form-control"
                            rows="5"
                            placeholder="Observación..."></textarea>

                            <div class="d-grid mt-4">

                                <button
                                id="btnGuardarGestion"
                                class="btn btn-primary btn-lg">

                                    💾 Guardar Gestión

                                </button>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-7">

                        <div style="
                            background:white;
                            border-radius:15px;
                            padding:20px;
                            box-shadow:0 3px 12px rgba(0,0,0,0.05);
                        ">

                            <h5 class="mb-4">
                                Historial de Gestión
                            </h5>

                            <div class="timeline-crm">

                                ${historial}

                            </div>

                        </div>

                    </div>

                </div>

            </div>
            `,

            didOpen: () => {

                document
                .getElementById('btnGuardarGestion')

                .addEventListener('click', () => {

                    let tipo =
                    document.getElementById('tipoGestion').value;

                    let resultado =
                    document.getElementById('resultadoGestion').value;

                    let observacion =
                    document.getElementById('observacion').value;

                    let proximaGestion =
                    document.getElementById('proximaGestion').value;

                    let fechaPromesa =
                    document.getElementById('fechaPromesa').value;

                    let valorPromesa =
                    document.getElementById('valorPromesa').value;

                    fetch('../includes/saveGestion.php', {

                        method:'POST',

                        headers:{
                            'Content-Type':'application/x-www-form-urlencoded'
                        },

                        body:
                        `id_cliente=${id_cliente}
                        &id_prestamo=${id_prestamo}
                        &tipo=${encodeURIComponent(tipo)}
                        &resultado=${encodeURIComponent(resultado)}
                        &observacion=${encodeURIComponent(observacion)}
                        &fecha_promesa=${encodeURIComponent(fechaPromesa)}
                        &valor_promesa=${encodeURIComponent(valorPromesa)}
                        &proxima_gestion=${encodeURIComponent(proximaGestion)}`

                    })

                    .then(r => r.json())

                    .then(data => {

                        if(data.success){

                            Swal.fire(
                                'Éxito',
                                'Gestión registrada correctamente',
                                'success'
                            );

                            cargarGestion();

                        }else{

                            Swal.fire(
                                'Error',
                                data.message,
                                'error'
                            );

                        }

                    });

                });

            }

        });

    });

}

document
.getElementById('buscarCliente')

.addEventListener('keyup', function(){

    let valor = this.value.toLowerCase();

    let filas =
    document.querySelectorAll('#tablaGestion tr');

    filas.forEach(fila => {

        let texto =
        fila.innerText.toLowerCase();

        fila.style.display =
        texto.includes(valor)
        ? ''
        : 'none';

    });

});

cargarGestion();

</script>

<?php include "../includes/footer.php"; ?>