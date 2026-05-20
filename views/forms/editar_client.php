<div class="modal fade" id="editar<?php echo $fila['id']; ?>" tabindex="-1">
<div class="modal-dialog modal-xl modal-dialog-scrollable">
<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title">Editar Cliente</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<form id="formEditClient<?php echo $fila['id']; ?>">

<input type="hidden" name="accion" value="editClientCompleto">
<input type="hidden" name="id" value="<?php echo $fila['id']; ?>">

<div class="wizard-progress mb-4">
<div class="wizard-progress-bar" id="wizardProgressBarEdit<?php echo $fila['id']; ?>"></div>
</div>

<!-- ================= PASO 1 ================= -->
<div class="stepEdit active" style="display:block;">

<h5 class="section-title mb-4">INFORMACIÓN DEL SOLICITANTE</h5>

<div class="container-fluid">

<div class="row g-3">

<div class="col-md-6">
<label># ID Cliente</label>
<input type="text" name="folioClient" class="form-control input-readonly"
value="<?php echo $fila['folioClient']; ?>" readonly>
</div>

<div class="col-md-6">
<label>Status</label>
<select name="id_status" class="form-select" required>
<option value="">Seleccione</option>
<?php
$estados=mysqli_query($conexion,"SELECT * FROM estado_registros");
while($e=mysqli_fetch_assoc($estados)){
$selected=($e['id']==$fila['id_status'])?'selected':'';
echo "<option value='{$e['id']}' $selected>{$e['estado']}</option>";
}
?>
</select>
</div>

</div>

<div class="row g-3 mt-1">

<div class="col-md-6">
<label>Nombres</label>
<input type="text" name="nombreClient" class="form-control"
value="<?php echo $fila['nombreClient']; ?>" required>
</div>

<div class="col-md-6">
<label>Apellidos</label>
<input type="text" name="apellidoClient" class="form-control"
value="<?php echo $fila['apellidoClient']; ?>" required>
</div>

</div>

<div class="row g-3 mt-1">

<div class="col-md-3">
<label>Estado Civil</label>
<select name="estado_civil" class="form-select" required>
<option value="">Seleccione</option>
<?php
$estadosCiviles=["Soltero","Casado","Unión Libre","Divorciado","Viudo"];
foreach($estadosCiviles as $ec){
$sel=($fila['estado_civil']==$ec)?'selected':'';
echo "<option $sel>$ec</option>";
}
?>
</select>
</div>

<div class="col-md-3">
<label>Género</label>
<select name="genero" class="form-select" required>
<option value="">Seleccione</option>
<?php
$generos=["Masculino","Femenino","Otro"];
foreach($generos as $g){
$sel=($fila['genero']==$g)?'selected':'';
echo "<option $sel>$g</option>";
}
?>
</select>
</div>

<div class="col-md-3">
<label>Tipo Identificación</label>
<select name="id_tipoIdentificacion" class="form-select" required>
<option value="">Seleccione</option>
<?php
$tipos=mysqli_query($conexion,"SELECT * FROM tipo_identificacion");
while($t=mysqli_fetch_assoc($tipos)){
$sel=($t['id']==$fila['id_tipoIdentificacion'])?'selected':'';
echo "<option value='{$t['id']}' $sel>{$t['nombre']}</option>";
}
?>
</select>
</div>

<div class="col-md-3">
<label>N° Documento</label>
<input type="text" name="docIdentClient" class="form-control"
value="<?php echo $fila['docIdentClient']; ?>" required>
</div>

</div>

<!-- DIRECCION -->
<div class="row g-3 mt-1">
<div class="col-md-3">
<label>Dirección</label>
<input type="text" name="dirClient" class="form-control"
value="<?php echo $fila['dirClient']; ?>">
</div>

<div class="col-md-3">
<label>Barrio</label>
<input type="text" name="barrioClient" class="form-control"
value="<?php echo $fila['barrioClient']; ?>">
</div>

<div class="col-md-3">
<label>Teléfono</label>
<input type="number" name="telClient" class="form-control"
value="<?php echo $fila['telClient']; ?>">
</div>

<div class="col-md-3">
<label>Celular</label>
<input type="number" name="celClient" class="form-control"
value="<?php echo $fila['celClient']; ?>">
</div>



<!-- 🔥 AQUI AGREGAS LOS CAMPOS NUEVOS -->

<div class="col-md-3">
<label>Email</label>
<input type="email" name="correoClient" class="form-control"
value="<?php echo $fila['correoClient']; ?>">
</div>


<div class="col-md-3">
<label>Ciudad Residencia</label>
<input type="text" name="municipio_residencia_id" class="form-control"
value="<?php echo $fila['municipio_residencia_id']; ?>">
</div>

<div class="col-md-3">
<label>Estrato</label>
<input type="number" name="estrato" class="form-control"
value="<?php echo $fila['estrato']; ?>">
</div>

 <!-- 👈 aqui se cierra el row -->





<div class="col-md-3">
<label>Personas a Cargo</label>
<input type="number" name="personas_cargo" class="form-control"
value="<?php echo $fila['personas_cargo']; ?>">
</div>

</div>

<div class="row g-3 mt-1">

<div class="col-md-3">
<label>Tipo de Vivienda</label>
<input type="text" name="tipo_vivienda" class="form-control"
value="<?php echo $fila['tipo_vivienda']; ?>">
</div>

<div class="col-md-3">
<label>Ocupación</label>
<input type="text" name="ocupacion_general" class="form-control"
value="<?php echo $fila['ocupacion_general']; ?>">
</div>

<div class="col-md-3">
<label>¿Presenta condición física o enfermedad?</label>
<select name="condicion_medica" class="form-select">
<option value="No" <?php if($fila['condicion_medica']=="No") echo "selected"; ?>>No</option>
<option value="Si" <?php if($fila['condicion_medica']=="Si") echo "selected"; ?>>Sí</option>
</select>
</div>

</div>



</div>


<div class="d-flex justify-content-end mt-4">
<button type="button" class="btn btn-primary"
onclick="nextStepEdit(<?php echo $fila['id']; ?>)">Siguiente →</button>
</div>

</div>



</div>



<!-- ================= PASO 2 ================= -->
<div class="stepEdit" style="display:none;">

<h5 class="section-title">INFORMACIÓN LABORAL</h5>

<div class="container-fluid">

<div class="row">

<div class="col-md-3 mb-3">
<label>Empresa</label>
<input type="text" name="empresa" class="form-control"
value="<?php echo $fila['empresa']; ?>">
</div>

<div class="col-md-3 mb-3">
<label>Tipo de Contrato</label>
<input type="text" name="tipo_contrato" class="form-control"
value="<?php echo $fila['tipo_contrato']; ?>">
</div>

<div class="col-md-3 mb-3">
<label>Fecha Ingreso</label>
<input type="date" name="fecha_ingreso_laboral" class="form-control"
value="<?php echo $fila['fecha_ingreso_laboral']; ?>">
</div>

<div class="col-md-3 mb-3">
<label>Total Devengado</label>
<input type="number" name="totalDevengado" class="form-control"
value="<?php echo $fila['totalDevengado']; ?>">
</div>

</div>

</div>

<div class="d-flex justify-content-between mt-4">
<button type="button" class="btn btn-secondary"
onclick="prevStepEdit(<?php echo $fila['id']; ?>)">← Anterior</button>
<button type="button" class="btn btn-primary"
onclick="nextStepEdit(<?php echo $fila['id']; ?>)">Siguiente →</button>
</div>

</div>

<!-- ================= PASO 3 ================= -->
<div class="stepEdit" style="display:none;">

<h5 class="section-title">INFORMACIÓN FINANCIERA</h5>

<div class="container-fluid">

<div class="row">

<div class="col-md-6 mb-3">
<label>Total Ingresos</label>
<input type="number" name="totalIngresos" class="form-control"
value="<?php echo $fila['totalIngresos']; ?>">
</div>

<div class="col-md-6 mb-3">
<label>Otros Ingresos</label>
<input type="number" name="otrosIngresos" class="form-control"
value="<?php echo $fila['otrosIngresos']; ?>">
</div>

<div class="col-md-6 mb-3">
<label>Total Egresos</label>
<input type="number" name="totalEgresos" class="form-control"
value="<?php echo $fila['totalEgresos']; ?>">
</div>

<div class="col-md-6 mb-3">
<label>Activos</label>
<input type="number" name="activos" class="form-control"
value="<?php echo $fila['activos']; ?>">
</div>

<div class="col-md-6 mb-3">
<label>Pasivos</label>
<input type="number" name="pasivos" class="form-control"
value="<?php echo $fila['pasivos']; ?>">
</div>

<div class="col-md-6 mb-3">
<label>Patrimonios</label>
<input type="number" name="patrimonios" class="form-control"
value="<?php echo $fila['patrimonios']; ?>">
</div>

</div>

</div>

<div class="d-flex justify-content-between mt-4">
<button type="button" class="btn btn-secondary"
onclick="prevStepEdit(<?php echo $fila['id']; ?>)">← Anterior</button>
<button type="button" class="btn btn-success"
onclick="guardarEdicion(<?php echo $fila['id']; ?>)">💾 Guardar Cambios</button>
</div>

</div>

</form>

</div>
</div>
</div>
</div>


<style>
.modal:not(.show) {
    display: none !important;
}
</style>


<script>

document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".stepEdit").forEach((step, index) => {
        if(!step.classList.contains("active")){
            step.style.display = "none";
        }
    });
});


function editarClient(id) {

    let datosFormulario = $("#editarClient" + id).serialize();

    $.ajax({
        url: '../includes/functions.php',
        type: "POST",
        data: datosFormulario,
        dataType: "json",

        success: function(response) {

            if (response.status === "success") {

                Swal.fire({
                    icon: 'success',
                    title: 'Actualizado correctamente',
                    text: 'Los datos fueron guardados exitosamente.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });

            } else {

                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: response.message || "Ha ocurrido un problema al actualizar"
                });

            }
        },

        error: function() {
            Swal.fire({
                icon: "error",
                title: "Error de servidor",
                text: "No se pudo procesar la solicitud"
            });
        }
    });
}
</script>
<script>
function guardarEdicion(id){

let formData = $("#formEditClient"+id).serialize();

$.ajax({
    url: '../includes/functions.php',
    type: 'POST',
    data: formData + "&accion=editClientCompleto&id="+id,
    dataType: 'json',
    success: function(response){

        if(response.status === 'success'){

            Swal.fire({
                icon:'success',
                title:'Cliente actualizado correctamente'
            }).then(()=>{
                location.reload();
            });

        }else{
            Swal.fire({
                icon:'error',
                title:'Error',
                text: response.message
            });
        }

    },
    error:function(){
        Swal.fire({
            icon:'error',
            title:'Error de servidor'
        });
    }
});
}
</script>

<script>
let currentStepEdit = {};

function nextStepEdit(id){

let modal=document.querySelector("#editar"+id);
let steps=modal.querySelectorAll(".stepEdit");

if(!currentStepEdit[id]) currentStepEdit[id]=0;

if(currentStepEdit[id]<steps.length-1){
steps[currentStepEdit[id]].style.display="none";
currentStepEdit[id]++;
steps[currentStepEdit[id]].style.display="block";
}
}

function prevStepEdit(id){

let modal=document.querySelector("#editar"+id);
let steps=modal.querySelectorAll(".stepEdit");

if(currentStepEdit[id]>0){
steps[currentStepEdit[id]].style.display="none";
currentStepEdit[id]--;
steps[currentStepEdit[id]].style.display="block";
}
}
</script>