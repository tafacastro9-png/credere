<?php
include "../includes/configSession.php";
require_once "../includes/db.php";
include "../includes/header.php";
?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<div class="container-fluid mt-4">

    <!-- 🔥 HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4>⚙️ Parametrización del sistema</h4>
            <small class="text-muted">Configura valores dinámicos del negocio</small>
        </div>
    </div>

    <!-- 🔥 TABLA -->
    <div class="card p-4 shadow-sm">

        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Parámetro</th>
                    <th>Descripción</th>
                    <th style="width:150px;">Valor</th>
                    <th style="width:120px;">Acción</th>
                </tr>
            </thead>
            <tbody id="tablaParametros"></tbody>
        </table>

    </div>

</div>

<script>

// ==========================
// 🔹 CARGAR PARAMETROS
// ==========================
function cargarParametros(){

    $("#tablaParametros").html(`
        <tr>
            <td colspan="4" class="text-center text-muted">
                Cargando parámetros...
            </td>
        </tr>
    `);

    $.ajax({
        url: "../ajax/parametrosAjax.php",
        method: "GET",
        dataType: "json",

        success: function(data){

            let html = "";

            data.forEach(p => {

                // 🔥 asegurar valor correcto sin tocarlo raro
                let valor = p.valor ?? 0;

                html += `
                <tr>
                    <td><b>${p.nombre}</b></td>
                    <td>${p.descripcion ?? ''}</td>
                    <td>
                        <div class="input-group">
                            <input type="text" inputmode="decimal"
                                class="form-control"
                                id="param_${p.id}"
                                value="${valor}">
                            <span class="input-group-text">%</span>
                        </div>
                    </td>
                    <td>
                        <button class="btn btn-success btn-sm ms-2"
                            onclick="guardarParametro(${p.id})">
                            💾
                        </button>
                    </td>
                </tr>
                `;
            });

            $("#tablaParametros").html(html);
        },

        error: function(xhr){
            console.error("ERROR AJAX:", xhr.responseText);

            $("#tablaParametros").html(`
                <tr>
                    <td colspan="4" class="text-center text-danger">
                        Error al cargar datos del servidor
                    </td>
                </tr>
            `);
        }
    });
}

// ============================
// 🔹 GUARDAR
// ============================
function guardarParametro(id){

    let valor = $("#param_" + id).val();

    // 🔥 limpiar coma
    valor = valor.replace(',', '.');

    $.ajax({
        url: "../ajax/parametrosAjax.php",
        method: "POST",
        data: {
            accion: "guardar",
            id: id,
            valor: valor
        },

        success: function(res){
            let data;

            try {
                data = JSON.parse(res);
            } catch(e){
                alert("Error en respuesta del servidor");
                return;
            }

            if(data.error){
                alert(data.error);
                return;
            }

            // 🔥 recargar limpio
            cargarParametros();
        }
    });
}

// ==========================
// 🔹 INICIO
// ==========================
$(document).ready(function(){
    cargarParametros();
});
</script>