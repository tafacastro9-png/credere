<?php 

include "../includes/configSession.php";
require_once "../includes/permisos.php";
require_once "../includes/db.php";
include "../includes/header.php";

ini_set('display_errors', 1);
error_reporting(E_ALL); 

$tipos = [];
$ciudades = [];

// Tipos de identificación
$result = mysqli_query($conexion, "SELECT * FROM tipo_identificacion");
while($row = mysqli_fetch_assoc($result)){
    $tipos[] = $row;
}

// Ciudades
$result = mysqli_query($conexion, "SELECT * FROM ciudades");
while($row = mysqli_fetch_assoc($result)){
    $ciudades[] = $row;
}
?>

<style>
.table-dark th {
    color: #ffffff !important;
    font-weight: 600;
}

.btn-gestionar {
    background-color: #1b2a4e;
    color: white !important;
    border: none;
    padding: 5px 10px;
    border-radius: 4px;
    transition: 0.3s;
}

.btn-gestionar:hover {
    background-color: #3d5a9c;
    transform: scale(1.05);
}
</style>

<section class="table-components">
<div class="container-fluid">

<br><br>

<div class="row">
<div class="col-lg-12">

<div class="card-style mb-30">

<div class="d-flex justify-content-between align-items-center">


    <h5>Módulo de Inversionistas</h5>

    <div>
	
	   <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalInversionista">
        Agregar +
    </button>
	
        <a href="../views/exportar_inversionistas.php" class="btn btn-success">
            📥 Descargar Excel
        </a>

  
    </div>

</div>



<hr>

<div class="table-responsive">
<table class="table table-bordered table-striped align-middle">

<thead class="table-dark">
<tr>
    <th>Nombre</th>
    <th>Documento</th>
    <th>Saldo</th>
    <th class="text-center">Acciones</th>
</tr>
</thead>

<tbody id="tablaInversionistas">
<tr>
<td colspan="4" class="text-center text-muted">
Cargando...
</td>
</tr>
</tbody>

</table>
</div>

</div>
</div>
</div>
</div>
</section>

<!-- 🔥 MODAL -->
<div class="modal fade" id="modalInversionista" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<div class="modal-header">
    <h5 class="modal-title">Registrar Inversionista</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form id="formInversionista" enctype="multipart/form-data">

<div class="modal-body">

<!-- PASO 1 -->
<div id="paso1">
<div class="row g-3">

    <div class="col-md-6">
        <label>Nombre*</label>
        <input type="text" name="nombre" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label>Tipo Identificación*</label>
        <select name="tipo_identificacion_id" class="form-control" required>
            <option value="">Seleccione...</option>
            <?php foreach($tipos as $t): ?>
                <option value="<?= $t['id'] ?>"><?= $t['nombre'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-6">
        <label>Documento*</label>
        <input type="text" name="documento" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label>Celular*</label>
        <input type="text" name="telefono" class="form-control" required maxlength="10">
    </div>

    <div class="col-md-6">
        <label>Email*</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label>Ciudad*</label>
        <select name="ciudad_id" class="form-control" required>
            <option value="">Seleccione...</option>
            <?php foreach($ciudades as $c): ?>
                <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-6">
        <label>Dirección*</label>
        <input type="text" name="direccion" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label>Barrio*</label>
        <input type="text" name="barrio" class="form-control" required>
    </div>

</div>
</div>

<!-- PASO 2 -->
<div id="paso2" style="display:none;">
<div class="row g-3">

    <div class="col-md-12">
        <label>Documento de identidad*</label>
        <input type="file" name="doc_identidad" class="form-control">
    </div>

    <div class="col-md-12">
        <label>Comprobante de inversion*</label>
        <input type="file" name="doc_direccion" class="form-control">
    </div>

</div>
</div>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

    <button type="button" class="btn btn-primary" id="btnSiguiente" onclick="siguientePaso()">
        Siguiente →
    </button>

    <button type="submit" class="btn btn-success" id="btnGuardar" style="display:none;">
        Guardar
    </button>
</div>

</form>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

// 🔒 SOLO NÚMEROS TELÉFONO
$("input[name='telefono']").on("input", function(){
    this.value = this.value.replace(/[^0-9]/g, '');
});

// 🔹 LISTAR
function cargarInversionistas(){
    $.post("../ajax/inversionistasAjax.php", {accion:"listar_tabla"}, function(data){
        $("#tablaInversionistas").html(data);
    });
}

// 🔹 PASO
function siguientePaso(){

    let campos = [
        {name:"nombre", label:"Nombre"},
        {name:"tipo_identificacion_id", label:"Tipo de identificación"},
        {name:"documento", label:"Documento"},
        {name:"telefono", label:"Teléfono"},
        {name:"email", label:"Email"},
        {name:"ciudad_id", label:"Ciudad"},
        {name:"direccion", label:"Dirección"},
        {name:"barrio", label:"Barrio"}
    ];

    for(let c of campos){
        let valor = $(`[name="${c.name}"]`).val();

        if(!valor || valor.trim() === ""){
            Swal.fire("Campo obligatorio","Debe completar: " + c.label,"warning");
            return;
        }
    }

    let documento = $("[name='documento']").val();
    if(!/^\d+$/.test(documento)){
        Swal.fire("Error","El documento solo debe contener números","error");
        return;
    }

    let telefono = $("[name='telefono']").val();
    if(!/^3\d{9}$/.test(telefono)){
        Swal.fire("Error","Debe ser un número de 10 dígitos que inicie en 3","error");
        return;
    }

    let direccion = $("[name='direccion']").val().trim();
    if(direccion.length < 8){
        Swal.fire("Error","Dirección demasiado corta","error");
        return;
    }

    $("#paso1").hide();
    $("#paso2").show();
    $("#btnSiguiente").hide();
    $("#btnGuardar").show();
}

// 🔹 GUARDAR
$("#formInversionista").on("submit", function(e){
    e.preventDefault();

    let archivo1 = $("input[name='doc_identidad']")[0].files[0];
    let archivo2 = $("input[name='doc_direccion']")[0].files[0];

    if(!archivo1){
        Swal.fire("Campo obligatorio","Debe subir Documento de identidad","warning");
        return;
    }

    if(!archivo2){
        Swal.fire("Campo obligatorio","Debe subir Comprobante de inversión","warning");
        return;
    }

    let tiposPermitidos = ["application/pdf","image/jpeg","image/png"];

    if(!tiposPermitidos.includes(archivo1.type)){
        Swal.fire("Error","Documento de identidad debe ser PDF o imagen","error");
        return;
    }

    if(!tiposPermitidos.includes(archivo2.type)){
        Swal.fire("Error","Comprobante debe ser PDF o imagen","error");
        return;
    }

    let formData = new FormData(this);
    formData.append("accion","guardar");

    $.ajax({
        url: "../ajax/inversionistasAjax.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(res){

            if(res === "error_campos"){
                Swal.fire("Error","Faltan campos obligatorios","error");
                return;
            }

            Swal.fire("OK","Guardado correctamente","success");

            $("#modalInversionista").modal("hide");
            $("#formInversionista")[0].reset();

            $("#paso1").show();
            $("#paso2").hide();
            $("#btnSiguiente").show();
            $("#btnGuardar").hide();

            cargarInversionistas();
        }
    });
});

// INIT
cargarInversionistas();

</script>

<?php include "../includes/footer.php"; ?>