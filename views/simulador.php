
<?php
include "../includes/header.php";
include "../includes/db.php";

date_default_timezone_set('America/Bogota');

function obtenerParametro($conexion, $nombre){

    $q = mysqli_query($conexion,"
        SELECT valor
        FROM parametros
        WHERE nombre='$nombre'
        LIMIT 1
    ");

    $row = mysqli_fetch_assoc($q);

    return floatval($row['valor'] ?? 0);
}

$seguro_porcentaje =
obtenerParametro($conexion,'SEGURO_CREDITO');
?>

<section class="table-components">

<div class="container-fluid px-4">

<div class="row justify-content-center">

<div class="col-xl-10">

<div class="card shadow-lg border-0 rounded-4">

<div class="card-body p-4">

<!-- TITULO -->
<div class="titulo-modulo mb-4">

    <i class="fa fa-calculator me-2"></i>

    Simulador de Créditos

</div>

<!-- PDF -->
<div id="contenidoPDF">

<!-- FORM -->
<form id="formSimulador">

<div class="row g-4">

    <!-- TIPO -->
    <div class="col-md-3">

        <label class="form-label fw-semibold">
            Tipo Proyección
        </label>

        <select id="tipo_proyeccion"
                class="form-select">

            <option value="">Seleccione</option>

            <option value="0">
                Interés Simple
            </option>

            <option value="1">
                Amortizado
            </option>

        </select>

    </div>

    <!-- MONTO -->
    <div class="col-md-3">

        <label class="form-label fw-semibold">
            Monto
        </label>

        <input type="text"
               id="monto"
               class="form-control">

    </div>

    <!-- PLAZO -->
    <div class="col-md-2">

        <label class="form-label fw-semibold">
            Plazo (Meses)
        </label>

        <input type="number"
               id="plazo"
               class="form-control">

    </div>

    <!-- TASA ANUAL -->
    <div class="col-md-2">

        <label class="form-label fw-semibold">
            Tasa Anual (%)
        </label>

        <input type="number"
               step="0.01"
               id="tasa_anual"
               class="form-control">

    </div>

    <!-- TASA MENSUAL -->
    <div class="col-md-2">

        <label class="form-label fw-semibold">
            Tasa Mensual (%)
        </label>

        <input type="number"
               step="0.0001"
               id="tasa_mensual"
               class="form-control">

    </div>

</div>

<div class="mt-4">

<button type="button"
        class="btn btn-primary btn-lg px-5"
        id="btnCalcular">

    🧮 Calcular Crédito

</button>

</div>

</form>

<!-- RESULTADO -->
<div id="resultado"
     class="d-none mt-5">

<div class="row g-4">

    <!-- CUOTA -->
    <div class="col-md-4">

        <div class="card shadow-sm border-0 rounded-4 h-100">

            <div class="card-body">

                <h6 class="text-muted">
                    💳 Cuota
                </h6>

                <h3 id="txtCuota"
                    class="fw-bold text-primary">

                    $0

                </h3>

            </div>

        </div>

    </div>

    <!-- SEGURO -->
    <div class="col-md-4">

        <div class="card shadow-sm border-0 rounded-4 h-100">

            <div class="card-body">

                <h6 class="text-muted">
                    🛡️ Seguro Total
                </h6>

                <h3 id="txtSeguro"
                    class="fw-bold text-success">

                    $0

                </h3>

            </div>

        </div>

    </div>

    <!-- DESEMBOLSO -->
    <div class="col-md-4">

        <div class="card shadow-sm border-0 rounded-4 h-100">

            <div class="card-body">

                <h6 class="text-muted">
                    💵 Desembolso
                </h6>

                <h3 id="txtDesembolso"
                    class="fw-bold text-danger">

                    $0

                </h3>

            </div>

        </div>

    </div>

    <!-- PAGARE -->
    <div class="col-md-4">

        <div class="card shadow-sm border-0 rounded-4 h-100">

            <div class="card-body">

                <h6 class="text-muted">
                    📄 Valor Pagaré
                </h6>

                <h3 id="txtPagare"
                    class="fw-bold text-dark">

                    $0

                </h3>

            </div>

        </div>

    </div>

    <!-- SEGURO CUOTA -->
    <div class="col-md-4">

        <div class="card shadow-sm border-0 rounded-4 h-100">

            <div class="card-body">

                <h6 class="text-muted">
                    🛡️ Seguro por Cuota
                </h6>

                <h3 id="txtSeguroCuota"
                    class="fw-bold text-warning">

                    $0

                </h3>

            </div>

        </div>

    </div>

    <!-- CUOTA + SEGURO -->
    <div class="col-md-4">

        <div class="card shadow-sm border-0 rounded-4 h-100">

            <div class="card-body">

                <h6 class="text-muted">
                    💰 Cuota + Seguro
                </h6>

                <h3 id="txtCuotaSeguro"
                    class="fw-bold text-info">

                    $0

                </h3>

            </div>

        </div>

    </div>

</div>

<!-- BOTON PDF -->
<div class="mt-4 mb-3">

<button type="button"
        id="btnPDF"
        class="btn btn-success">

    📄 Descargar PDF

</button>

</div>

<!-- TABLA -->
<div class="table-responsive mt-4">

<table class="table table-bordered table-hover align-middle">

<thead class="table-dark text-center">

<tr>

<th>#</th>
<th>Interés</th>
<th>Capital</th>
<th>Cuota</th>
<th>Saldo</th>

</tr>

</thead>

<tbody id="tablaBody"></tbody>

</table>

</div>

</div>

</div>

</div>

</div>

</div>

</div>

</section>

<style>

.titulo-modulo{

    font-size:1.7rem;
    font-weight:700;
    color:#000a38;
    border-bottom:3px solid #000a38;
    padding-bottom:10px;
}

.card{

    transition:0.2s ease;
}

.card:hover{

    transform:translateY(-2px);
}

.table thead th{

    background:#000a38 !important;
    color:white;
}

</style>

<!-- HTML2PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>

let SEGURO_PORCENTAJE =
<?= $seguro_porcentaje ?>;

// =========================================
// FORMATO MONTO
// =========================================

const inputMonto =
document.getElementById("monto");

inputMonto.addEventListener("input", function(){

    let valor =
    this.value.replace(/\D/g, "");

    this.value =
    Number(valor).toLocaleString('es-CO');

});

// =========================================
// CONVERSIÓN TASAS
// =========================================

let bloqueando = false;

function calcularAnualDesdeMensual(mensual){

    let m = mensual / 100;

    let anual =
    (Math.pow(1 + m, 12) - 1) * 100;

    return Math.round(anual * 2) / 2;
}

function calcularMensualDesdeAnual(anual){

    let a = anual / 100;

    let mensual =
    (Math.pow(1 + a, 1/12) - 1) * 100;

    return mensual;
}

const tasaAnual =
document.getElementById("tasa_anual");

const tasaMensualInput =
document.getElementById("tasa_mensual");

const tipoProyeccion =
document.getElementById("tipo_proyeccion");

// =========================================
// MENSUAL -> ANUAL
// =========================================

tasaMensualInput.addEventListener("input", function(){

    if(bloqueando) return;

    bloqueando = true;

    let mensual = parseFloat(this.value);

    if(!isNaN(mensual)){

        let anual =
        calcularAnualDesdeMensual(mensual);

        tasaAnual.value =
        anual.toFixed(2);

    }else{

        tasaAnual.value = "";
    }

    bloqueando = false;
});

// =========================================
// ANUAL -> MENSUAL
// =========================================

tasaAnual.addEventListener("input", function(){

    if(bloqueando) return;

    bloqueando = true;

    let anual = parseFloat(this.value);

    if(!isNaN(anual)){

        let mensual =
        calcularMensualDesdeAnual(anual);

        tasaMensualInput.value =
        mensual.toFixed(4);

    }else{

        tasaMensualInput.value = "";
    }

    bloqueando = false;
});

// =========================================
// BLOQUEO AUTOMÁTICO
// =========================================




// =========================================
// AMORTIZADO
// =========================================


// =========================================
// CALCULAR
// =========================================

document.getElementById("btnCalcular")
.addEventListener("click", function(){

    let tipo =
    document.getElementById("tipo_proyeccion").value;

    let monto = parseFloat(
        document.getElementById("monto").value
        .replace(/\./g,'')
        .replace(/,/g,'')
    );

    let plazo = parseInt(
        document.getElementById("plazo").value
    );

  let tasaMensualValor = 0;

let tasaMensualInputValor =
document.getElementById("tasa_mensual").value;

let tasaAnualInputValor =
document.getElementById("tasa_anual").value;

// =========================================
// PRIORIDAD A MENSUAL
// =========================================

if(
    tasaMensualInputValor &&
    parseFloat(tasaMensualInputValor) > 0
){

let anual =
parseFloat(tasaAnualInputValor);

tasaMensualValor =
(
    Math.pow(1 + (anual / 100), 1/12) - 1
) * 100;

}else if(
    tasaAnualInputValor &&
    parseFloat(tasaAnualInputValor) > 0
){

    let anual =
    parseFloat(tasaAnualInputValor);

    tasaMensualValor =
    (
        Math.pow(1 + (anual / 100), 1/12) - 1
    ) * 100;
}

    if(!tipo || !monto || !plazo || !tasaMensualValor){

        Swal.fire(
            "Campos requeridos",
            "Complete todos los campos",
            "warning"
        );

        return;
    }

    const tabla =
    document.getElementById("tablaBody");

    tabla.innerHTML = "";

    let cuota = 0;

    let saldo = monto;

    // =========================================
    // TASA MENSUAL
    // =========================================

    let tasaMensual =
    tasaMensualValor / 100;

    // =========================================
    // AMORTIZADO
    // =========================================

    if(Number(tipo) === 1){

        cuota = monto * (

            tasaMensual *
            Math.pow(1 + tasaMensual, plazo)

        ) / (

            Math.pow(1 + tasaMensual, plazo) - 1
        );

        cuota = Math.round(cuota);

        for(let i = 1; i <= plazo; i++){

            let interes =
            saldo * tasaMensual;

            let capital =
            cuota - interes;

            if(i === plazo){

                capital = saldo;

                saldo = 0;

            }else{

                saldo =
                saldo - capital;
            }

            tabla.innerHTML += `

            <tr>

                <td class="text-center">
                    ${i}
                </td>

                <td class="text-end">
                    $${Math.round(interes).toLocaleString('es-CO')}
                </td>

                <td class="text-end">
                    $${Math.round(capital).toLocaleString('es-CO')}
                </td>

                <td class="text-end">
                    $${Math.round(cuota).toLocaleString('es-CO')}
                </td>

                <td class="text-end">
                    $${Math.round(saldo).toLocaleString('es-CO')}
                </td>

            </tr>

            `;
        }

    }else{

        // SIMPLE

        let factor =
        (plazo * tasaMensual) + 1;

        cuota =
        (monto * factor) / plazo;

        cuota = Math.round(cuota);

        let capitalFijo =
        Math.round(monto / plazo);

        let totalPagar =
        cuota * plazo;

        let totalInteres =
        totalPagar - monto;

        let interesFijo =
        Math.round(totalInteres / plazo);

        for(let i = 1; i <= plazo; i++){

            saldo -= capitalFijo;

            tabla.innerHTML += `

            <tr>

                <td class="text-center">
                    ${i}
                </td>

                <td class="text-end">
                    $${interesFijo.toLocaleString('es-CO')}
                </td>

                <td class="text-end">
                    $${capitalFijo.toLocaleString('es-CO')}
                </td>

                <td class="text-end">
                    $${cuota.toLocaleString('es-CO')}
                </td>

                <td class="text-end">
                    $${Math.round(saldo).toLocaleString('es-CO')}
                </td>

            </tr>

            `;
        }
    }

    // =========================================
    // RESUMEN
    // =========================================

    let seguro =
    monto * (SEGURO_PORCENTAJE / 100);

    let seguroCuota =
    seguro / plazo;

    let cuotaSeguro =
    cuota + seguroCuota;

    let pagare =
    cuota * plazo;

    let gastoTramite =
    monto * 0.20;

    let desembolso =
    monto - gastoTramite;

    document.getElementById("txtCuota").textContent =
    "$" + cuota.toLocaleString('es-CO');

    document.getElementById("txtSeguro").textContent =
    "$" + Math.round(seguro).toLocaleString('es-CO');

    document.getElementById("txtDesembolso").textContent =
    "$" + Math.round(desembolso).toLocaleString('es-CO');

    document.getElementById("txtPagare").textContent =
    "$" + Math.round(pagare).toLocaleString('es-CO');

    document.getElementById("txtSeguroCuota").textContent =
    "$" + Math.round(seguroCuota).toLocaleString('es-CO');

    document.getElementById("txtCuotaSeguro").textContent =
    "$" + Math.round(cuotaSeguro).toLocaleString('es-CO');

    document.getElementById("resultado")
    .classList.remove("d-none");

});

// =========================================
// PDF
// =========================================

document.addEventListener("DOMContentLoaded", function(){

    document.getElementById("btnPDF")
    .addEventListener("click", function(){

        const elemento =
        document.getElementById("resultado");

        const opciones = {

            margin: 0.5,

            filename: 'Simulador_Credito.pdf',

            image: {
                type: 'jpeg',
                quality: 1
            },

            html2canvas: {
                scale: 2
            },

            jsPDF: {
                unit: 'in',
                format: 'a4',
                orientation: 'landscape'
            }

        };

        html2pdf()
        .set(opciones)
        .from(elemento)
        .save();

    });

});

</script>

<?php include "../includes/footer.php"; ?>

