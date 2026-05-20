<?php
include "../includes/configSession.php";
require_once "../includes/permisos.php";
require_once "../includes/db.php";
include "../includes/header.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

// 🔹 VALIDAR ID
$id = $_GET['id'] ?? 0;

if(!$id){
    echo "<div class='alert alert-danger'>ID inválido</div>";
    exit;
}

// 🔹 CARGAR DATOS
$q = mysqli_query($conexion, "
    SELECT * FROM inversionistas WHERE id = '$id'
");

$inv = mysqli_fetch_assoc($q);

if(!$inv){
    echo "<div class='alert alert-danger'>Inversionista no encontrado</div>";
    exit;
}

// 🔹 TIPOS
$tipos = [];
$r = mysqli_query($conexion, "SELECT * FROM tipo_identificacion");
while($row = mysqli_fetch_assoc($r)){
    $tipos[] = $row;
}

// 🔹 CIUDADES
$ciudades = [];
$r = mysqli_query($conexion, "SELECT * FROM ciudades");
while($row = mysqli_fetch_assoc($r)){
    $ciudades[] = $row;
}
?>

<div class="container-fluid mt-4">
<div class="card-style">

<h5>Editar Inversionista</h5>
<hr>

<form id="formEditar" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $inv['id'] ?>">

<div class="row g-3">

    <div class="col-md-6">
        <label>Nombre*</label>
        <input type="text" name="nombre" class="form-control" value="<?= $inv['nombre'] ?>" required>
    </div>

    <div class="col-md-6">
        <label>Tipo Identificación*</label>
        <select name="tipo_identificacion_id" class="form-control" required>
            <?php foreach($tipos as $t): ?>
                <option value="<?= $t['id'] ?>"
                    <?= $t['id'] == $inv['tipo_identificacion_id'] ? 'selected' : '' ?>>
                    <?= $t['nombre'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-6">
        <label>Documento*</label>
        <input type="text" name="documento" class="form-control" value="<?= $inv['documento'] ?>" required>
    </div>

    <div class="col-md-6">
        <label>Celular*</label>
        <input type="text" name="telefono" class="form-control" value="<?= $inv['telefono'] ?>" required maxlength="10">
    </div>

    <div class="col-md-6">
        <label>Email*</label>
        <input type="email" name="email" class="form-control" value="<?= $inv['email'] ?>" required>
    </div>

    <div class="col-md-6">
        <label>Ciudad*</label>
        <select name="ciudad_id" class="form-control" required>
            <?php foreach($ciudades as $c): ?>
                <option value="<?= $c['id'] ?>"
                    <?= $c['id'] == $inv['ciudad_id'] ? 'selected' : '' ?>>
                    <?= $c['nombre'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-6">
        <label>Dirección*</label>
        <input type="text" name="direccion" class="form-control" value="<?= $inv['direccion'] ?>" required>
    </div>

    <div class="col-md-6">
        <label>Barrio*</label>
        <input type="text" name="barrio" class="form-control" value="<?= $inv['barrio'] ?>" required>
    </div>
	
	<div class="col-md-12">
    <label>Actualizar Documento de identidad</label>
    <input type="file" name="doc_identidad" class="form-control">
</div>

<div class="col-md-12">
    <label>Actualizar Comprobante</label>
    <input type="file" name="doc_direccion" class="form-control">
</div>

</div>

<br>

<div class="text-end">
    <a href="inversionistas.php" class="btn btn-secondary">Volver</a>
    <button type="submit" class="btn btn-primary">Actualizar</button>
</div>

</form>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

$("input[name='telefono']").on("input", function(){
    this.value = this.value.replace(/[^0-9]/g, '');
});

$("#formEditar").on("submit", function(e){
    e.preventDefault();

    let formData = new FormData(this);
    formData.append("accion", "editar");

    $.ajax({
        url: "../ajax/inversionistasAjax.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(res){

            if(res.trim() === "ok"){
                Swal.fire("Actualizado","Datos guardados correctamente","success")
                .then(() => {
                    window.location.href = "inversionistas.php";
                });
            }else{
                console.log(res); // 👈 para debug
                Swal.fire("Error","No se pudo actualizar","error");
            }
        },
        error: function(err){
            console.log(err);
            Swal.fire("Error","Error en la petición AJAX","error");
        }
    });
});

</script>

<?php include "../includes/footer.php"; ?>