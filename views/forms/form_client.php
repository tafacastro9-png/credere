<!-- Modal -->

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- jQuery (si no lo tienes ya) -->


<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Formulario Crédito</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
	  <!-- PROGRESS WIZARD -->
<div class="wizard-progress mb-4">
    <div class="wizard-progress-bar" id="wizardProgressBar"></div>
</div>
       <form id="formClient" novalidate>

         <!-- ================= PASO 1 ================= -->
<div class="step">
<h5 class="section-title mb-4">INFORMACIÓN DEL SOLICITANTE</h5>

<div class="container-fluid">

<?php $folio = mt_rand(100000000, 999999999); ?>

<!-- FILA 1 -->
<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label"># ID Cliente</label>
    <input type="text" name="folioClient" class="form-control input-readonly" 
           value="<?php echo $folio; ?>" readonly>
  </div>
  
  
  
 
  

  <div class="col-md-6">
    <label class="form-label">Status</label>
    <select name="id_status" class="form-select" required>
      <option value="">Selecciona una opción</option>
      <?php
      $sql = "SELECT * FROM estado_registros";
      $resultado = mysqli_query($conexion, $sql);
      while ($consulta = mysqli_fetch_array($resultado)) {
          echo '<option value="'.$consulta['id'].'">'.$consulta['estado'].'</option>';
      }
      ?>
    </select>
  </div>
</div>

<!-- FILA 2 -->
<div class="row g-3 mt-1">
  <div class="col-md-6">
     <label class="form-label campo-obligatorio">Nombres</label>
    <input type="text" name="nombreClient" class="form-control" required>
  </div>

  <div class="col-md-6">
     <label class="form-label campo-obligatorio">Apellidos</label>
    <input type="text" name="apellidoClient" class="form-control" required>
  </div>
</div>

<!-- FILA 3 -->
<div class="row g-3 mt-1">
  <div class="col-md-3">
     <label class="form-label campo-obligatorio">Estado Civil</label>
      <select id="estado_civil" class="form-select" name="estado_civil" required>
      <option value="">Seleccione...</option>
      <option>Soltero</option>
      <option>Casado</option>
      <option>Union Libre</option>
      <option>Divorciado</option>
      <option>Viudo</option>
    </select>
  </div>
  
  

  
  

  <div class="col-md-3">
     <label class="form-label campo-obligatorio">Género</label>
    <select class="form-select" name="genero" required>
      <option value="">Seleccione...</option>
      <option>Masculino</option>
      <option>Femenino</option>
      <option>Otro</option>
    </select>
  </div>

  <div class="col-md-3">
     <label class="form-label campo-obligatorio">Tipo de Identificación</label>
    <select name="id_tipoIdentificacion" class="form-select" required>
      <option value="">Seleccione</option>
      <?php
      $sql = "SELECT * FROM tipo_identificacion";
      $resultado = mysqli_query($conexion, $sql);
      while ($consulta = mysqli_fetch_array($resultado)) {
        echo '<option value="'.$consulta['id'].'">'.$consulta['nombre'].'</option>';
      }
      ?>
    </select>
  </div>

  <div class="col-md-3">
     <label class="form-label campo-obligatorio">N° Documento</label>
    <input type="text" name="docIdentClient" class="form-control" required>
  </div>
</div>

<!-- FILA 4 -->
<div class="row g-3 mt-1">
  <div class="col-md-3">
     <label class="form-label campo-obligatorio">Lugar de Expedición</label>
    <select name="id_municipio" class="form-select" required>
      <option value="">Seleccione Municipio</option>
      <?php
      $sql = "SELECT * FROM ciudades ORDER BY nombre ASC";
      $resultado = mysqli_query($conexion, $sql);
      while ($row = mysqli_fetch_assoc($resultado)) {
        echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
      }
      ?>
    </select>
  </div>

  <div class="col-md-3">
     <label class="form-label campo-obligatorio">Fecha de Expedición</label>
    <input type="date" name="fecha_expedicion" class="form-control" required>
  </div>

  <div class="col-md-3">
     <label class="form-label campo-obligatorio">Lugar de Nacimiento</label>
    <select name="id_municipio_nacimiento" class="form-select" required>
      <option value="">Seleccione Municipio</option>
      <?php
      $resultado = mysqli_query($conexion, $sql);
      while ($row = mysqli_fetch_assoc($resultado)) {
        echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
      }
      ?>
    </select>
  </div>

  <div class="col-md-3">
     <label class="form-label campo-obligatorio">Fecha de Nacimiento</label>
    <input type="date" name="fecha_nacimiento" class="form-control" required>
  </div>
</div>

<!-- FILA 5 -->
<div class="row g-3 mt-1">
  <div class="col-md-3">
     <label class="form-label campo-obligatorio">Nivel de Escolaridad</label>
    <select name="nivel_escolaridad" class="form-select" required>
      <option value="">Seleccione...</option>
      <option>Primaria</option>
      <option>Secundaria</option>
      <option>Tecnico</option>
      <option>Tecnologo</option>
      <option>Profesional</option>
      <option>Especializacion</option>
      <option>Maestria</option>
      <option>Doctorado</option>
    </select>
  </div>

  <div class="col-md-3">
     <label class="form-label campo-obligatorio">Dirección</label>
    <input type="text" name="dirClient" class="form-control" required>
  </div>

  <div class="col-md-3">
     <label class="form-label campo-obligatorio">Ciudad de residencia</label>
    <select name="id_municipio_residencia" class="form-select" required>
      <option value="">Seleccione Municipio</option>
      <?php
      $resultado = mysqli_query($conexion, $sql);
      while ($row = mysqli_fetch_assoc($resultado)) {
        echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
      }
      ?>
    </select>
  </div>

  <div class="col-md-3">
     <label class="form-label campo-obligatorio">Barrio</label>
    <input type="text" name="barrioClient" class="form-control" required>
  </div>
</div>

<!-- FILA 6 -->
<div class="row g-3 mt-1">
  <div class="col-md-3 position-relative">

    <label class="form-label">Teléfono</label>

    <input 
        type="text"
        name="telClient"
        id="telClient"
        class="form-control">

    <small 
        id="errorTelefono"
        class="mensaje-error-celular">
        El teléfono debe iniciar con 60
    </small>

</div>
  
  

<div class="col-md-3 position-relative">

     <label class="form-label campo-obligatorio">Celular</label>

    <input 
        type="text"
        name="celClient"
        id="celClient"
        class="form-control"required>

    <small 
        id="errorCelular"
        class="mensaje-error-celular">
        El número de celular debe iniciar con 3
    </small>

</div>
  

  <div class="col-md-3">
     <label class="form-label campo-obligatorio">Estrato</label>
    <select name="estrato" class="form-select" required>
      <option value="">Seleccione...</option>
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
      <option value="5">5</option>
      <option value="6">6</option>
    </select>
  </div>

  <div class="col-md-3">
     <label class="form-label campo-obligatorio">Email</label>
    <input type="email" name="correoClient" class="form-control" required>
  </div>
</div>



<!-- FILA 7 -->
<div class="row g-3 mt-1 align-items-end">

    <!-- Cabeza de Hogar -->
    <div class="col-md-3">

        <label class="form-label campo-obligatorio">
            ¿Cabeza de Hogar?
        </label>

        <div class="d-flex gap-3 mt-2">

            <div class="form-check">

                <input class="form-check-input"
                       type="radio"
                       name="cabeza_hogar"
                       value="Si">

                <label class="form-check-label">Sí</label>

            </div>

            <div class="form-check">

                <input class="form-check-input"
                       type="radio"
                       name="cabeza_hogar"
                       value="No"
                       checked>

                <label class="form-check-label">No</label>

            </div>

        </div>

    </div>

    <!-- Personas a cargo -->
    <div class="col-md-3">

       <label class="form-label campo-obligatorio">
            Personas a cargo
        </label>

        <input type="number" 
               name="persocargoClient" 
               class="form-control" required>

    </div>

    <!-- Tipo de Vivienda -->
    <div class="col-md-3">

       <label class="form-label campo-obligatorio">
            Tipo de Vivienda
        </label>

        <select class="form-select" name="tipo_vivienda" required>

            <option value="">Seleccione...</option>
            <option>Propia</option>
            <option>Arrendada</option>
            <option>Familiar</option>
            <option>Otra</option>

        </select>

    </div>

    <!-- Ocupación -->
    <div class="col-md-3">

        <label class="form-label campo-obligatorio">
            Ocupación
        </label>

        <select class="form-select" name="ocupacion_general" required>

            <option value="">Seleccione...</option>
            <option>Empleado</option>
            <option>No empleado</option>
            <option>Independiente</option>
            <option>Pensionado</option>
            <option>Estudiante</option>
            <option>Hogar</option>
            <option>Cesante</option>
            <option>Inversionista</option>

        </select>

    </div>

</div>

<!-- FILA 8 -->
<div class="row g-4 mt-3 align-items-end">

    <!-- Condición médica -->
<!-- Condición médica -->
<div class="col-md-4">

    <label class="form-label fw-bold campo-obligatorio">
        ¿Presenta alguna condición física y/o enfermedad?
    </label>

    <div class="d-flex gap-4 mt-2">

        <div class="form-check">

            <input class="form-check-input"
                   type="radio"
                   name="condicion_medica"
                   value="No"
                   checked
                   onclick="toggleCondicionMedica(false)">

            <label class="form-check-label">
                No
            </label>

        </div>

        <div class="form-check">

            <input class="form-check-input"
                   type="radio"
                   name="condicion_medica"
                   value="Si"
                   onclick="toggleCondicionMedica(true)">

            <label class="form-check-label">
                Sí
            </label>

        </div>

    </div>

</div>

<!-- ¿Cuál? -->
<div class="col-md-3">

    <label class="form-label">
        ¿Cuál?
    </label>

    <input type="text"
           id="detalleCondicionMedica"
           name="detalleCondicionMedica"
           class="form-control"
           disabled>

</div>

    <!-- Tiene Vehículo -->
    <div class="col-md-2">

        <label class="form-label fw-bold campo-obligatorio">
            ¿Tiene vehículo?
        </label>

        <div class="d-flex gap-3 mt-2">

            <div class="form-check">

                <input class="form-check-input"
                       type="radio"
                       name="tieneVehiculoCliente"
                       value="No"
                       checked
                       onclick="togglePlacaCliente(false)">

                <label class="form-check-label">
                    No
                </label>

            </div>

            <div class="form-check">

                <input class="form-check-input"
                       type="radio"
                       name="tieneVehiculoCliente"
                       value="Si"
                       onclick="togglePlacaCliente(true)">

                <label class="form-check-label">
                    Sí
                </label>

            </div>

        </div>

    </div>

    <!-- Placa -->
    <div class="col-md-3">

        <label class="form-label">
            N° Placa
        </label>

        <input type="text"
               id="placaVehiculoCliente"
               name="placaVehiculoCliente"
               class="form-control"
               disabled>

    </div>

    <!-- Tiene codeudor -->
    <div class="col-md-3">

        <label class="form-label fw-bold campo-obligatorio">
            ¿Tiene codeudor?
        </label>

        <select id="tiene_codeudor"
                name="tiene_codeudor"
                class="form-select">

            <option value="0">No</option>
            <option value="1">Sí</option>

        </select>

    </div>

</div>
<!-- ================= SECCIÓN CODEUDOR (OCULTA) ================= -->

<div id="datos_codeudor" style="display:none; margin-top:20px;">




<h5 class="section-title"><b>DATOS DEL CODEUDOR</b></h5>

<div class="row">

<div class="row g-3 mt-1">
  <div class="col-md-6">
    <label class="form-label campo-obligatorio">Nombres</label>
    <input type="text" name="nombrecodeudor" class="form-control" >
  </div>

  <div class="col-md-6">
    <label class="form-label campo-obligatorio">Apellidos</label>
    <input type="text" name="apellidocodeudor" class="form-control" >
  </div>
</div>

<!-- FILA 3 -->
<div class="row g-3 mt-1">
  <div class="col-md-3">
    <label class="form-label campo-obligatorio">Estado Civil</label>
      <select id="estadocivilcodeudor" class="form-select" name="estadocivilcodeudor" >
      <option value="">Seleccione...</option>
      <option>Soltero</option>
      <option>Casado</option>
      <option>Unión Libre</option>
      <option>Divorciado</option>
      <option>Viudo</option>
    </select>
  </div>
  
  

  
  

  <div class="col-md-3">
    <label class="form-label campo-obligatorio">Género</label>
    <select class="form-select" name="generocodeudor" >
      <option value="">Seleccione...</option>
      <option>Masculino</option>
      <option>Femenino</option>
      <option>Otro</option>
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label campo-obligatorio">Tipo de Identificación</label>
    <select name="tipoidentificacioncodeudor" class="form-select" >
      <option value="">Seleccione</option>
      <?php
      $sql = "SELECT * FROM tipo_identificacion";
      $resultado = mysqli_query($conexion, $sql);
      while ($consulta = mysqli_fetch_array($resultado)) {
        echo '<option value="'.$consulta['id'].'">'.$consulta['nombre'].'</option>';
      }
      ?>
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label campo-obligatorio">N° Documento</label>
    <input type="text" name="numerodocumentocodeudor" class="form-control" >
  </div>
</div>

<!-- FILA 4 -->
<div class="row g-3 mt-1">
  <div class="col-md-3">
    <label class="form-label campo-obligatorio">Lugar de Expedición</label>
    <select name="lugarexpedicioncodeudor" class="form-select" >
      <option value="">Seleccione Municipio</option>
      <?php
      $sql = "SELECT * FROM ciudades ORDER BY nombre ASC";
      $resultado = mysqli_query($conexion, $sql);
      while ($row = mysqli_fetch_assoc($resultado)) {
        echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
      }
      ?>
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label campo-obligatorio">Fecha de Expedición</label>
    <input type="date" name="fechaexpedicioncodeudor" class="form-control" >
  </div>

  <div class="col-md-3">
    <label class="form-label campo-obligatorio">Lugar de Nacimiento</label>
    <select name="lugarnacimientocodeudor" class="form-select" >
      <option value="">Seleccione Municipio</option>
      <?php
      $resultado = mysqli_query($conexion, $sql);
      while ($row = mysqli_fetch_assoc($resultado)) {
        echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
      }
      ?>
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label campo-obligatorio">Fecha de Nacimiento</label>
    <input type="date" name="fechanacimientocodeudor" class="form-control" >
  </div>
</div>

<!-- FILA 5 -->
<div class="row g-3 mt-1">
  <div class="col-md-3">
    <label class="form-label campo-obligatorio">Nivel de Escolaridad</label>
    <select name="nivelescolaridadcodeudor" class="form-select" >
      <option value="">Seleccione...</option>
      <option>Primaria</option>
      <option>Secundaria</option>
      <option>Tecnico</option>
      <option>Tecnologo</option>
      <option>Profesional</option>
      <option>Especializacion</option>
      <option>Maestria</option>
      <option>Doctorado</option>
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label campo-obligatorio">Dirección</label>
    <input type="text" name="direccioncodeudor" class="form-control">
  </div>

  <div class="col-md-3">
    <label class="form-label campo-obligatorio">Ciudad de residencia</label>
    <select name="ciudadresidenciacodeudor" class="form-select" >
      <option value="">Seleccione Municipio</option>
      <?php
      $resultado = mysqli_query($conexion, $sql);
      while ($row = mysqli_fetch_assoc($resultado)) {
        echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
      }
      ?>
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label campo-obligatorio">Barrio</label>
    <input type="text" name="barriocodeudor" class="form-control">
  </div>
</div>

<!-- FILA 6 -->
<div class="row g-3 mt-1">



  <div class="col-md-3 position-relative">

    <label class="form-label">Teléfono</label>

    <input 
        type="text"
        name="telefonocodeudor"
        id="telefonocodeudor"
        class="form-control">

    <small 
        id="errorTelefonoCodeudor"
        class="mensaje-error-celular">
        El teléfono debe iniciar con 60
    </small>

</div>





<div class="col-md-3 position-relative">

<label class="form-label campo-obligatorio">Celular</label>

    <input 
        type="text"
        name="celularcodeudor"
        id="celularcodeudor"
        class="form-control">

    <small 
        id="errorCelularCodeudor"
        class="mensaje-error-celular">
        El número de celular debe iniciar con 3
    </small>

</div>
  







  <div class="col-md-3">
<label class="form-label campo-obligatorio">Estrato</label>
    <select name="estratocodeudor" class="form-select">
      <option value="">Seleccione...</option>
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
      <option value="5">5</option>
      <option value="6">6</option>
    </select>
  </div>

  <div class="col-md-3">
<label class="form-label campo-obligatorio">Email</label>
    <input type="email" name="emailcodeudor" class="form-control">
  </div>
</div>

<!-- FILA 7 -->
<div class="row g-3 mt-1">
  <div class="col-md-3">
<label class="form-label campo-obligatorio">Personas a cargo</label>
    <input type="number" name="personascargocodeudor" class="form-control">
  </div>

  <div class="col-md-3">
<label class="form-label campo-obligatorio">Tipo de Vivienda</label>
    <select class="form-select" name="tipoviviendacodeudor">
      <option value="">Seleccione...</option>
      <option>Propia</option>
      <option>Arrendada</option>
      <option>Familiar</option>
      <option>Otra</option>
    </select>
  </div>

  <div class="col-md-3">
<label class="form-label campo-obligatorio">Ocupación</label>
    <select class="form-select" name="ocupacioncodeudor">
      <option value="">Seleccione...</option>
      <option>Empleado</option>
      <option>Independiente</option>
      <option>Pensionado</option>
      <option>Estudiante</option>
      <option>Hogar</option>
	  <option>Cesante</option>
    </select>
  </div>
</div>

<div class="row g-3 mt-2 align-items-end">

    <!-- Tiene Vehículo -->
    <div class="col-md-3">
        <label class="form-label fw-bold campo-obligatorio">¿Tiene vehículo?</label>

        <div class="d-flex gap-3">
            <div class="form-check">
                <input class="form-check-input" 
                       type="radio" 
                       name="tieneVehiculoCodeudor" 
                       value="No" 
                       checked
                       onclick="togglePlaca(false)">

                <label class="form-check-label">No</label>
            </div>

            <div class="form-check">

                <input class="form-check-input" 
                       type="radio" 
                       name="tieneVehiculoCodeudor" 
                       value="Si" 
                       onclick="togglePlaca(true)">

                <label class="form-check-label">Sí</label>
            </div>
        </div>
    </div>

    <!-- N° Placa -->
    <div class="col-md-3">
        <label class="form-label">N° Placa</label>

        <input type="text" 
               id="placaVehiculo" 
               name="placaVehiculo"
               class="form-control">
    </div>

    <!-- Condición física -->
    <div class="col-md-3">

        <label class="form-label fw-bold campo-obligatorio">
            ¿Presenta alguna condición física y/o enfermedad?
        </label>

        <div class="d-flex gap-3">

            <div class="form-check">

                <input class="form-check-input"
                       type="radio"
                       name="condicion_codeudor"
                       value="No"
                       checked
                       onclick="toggleCondicionCodeudor(false)">

                <label class="form-check-label">No</label>

            </div>

            <div class="form-check">

                <input class="form-check-input"
                       type="radio"
                       name="condicion_codeudor"
                       value="Si"
                       onclick="toggleCondicionCodeudor(true)">

                <label class="form-check-label">Sí</label>

            </div>

        </div>

    </div>

    <!-- ¿Cuál? -->
    <div class="col-md-3">

        <label class="form-label">¿Cuál?</label>

        <input type="text"
               name="detalle_condicion_codeudor"
               id="detalle_condicion_codeudor"
               class="form-control"
               disabled>

    </div>

</div>




<h5 class="section-title mt-4">
<b>INFORMACIÓN LABORAL CODEUDOR</b>
</h5>

<div class="row g-3">

<div class="col-md-3">
<label class="form-label campo-obligatorio">Empresa</label>
<input type="text" name="empresa_codeudor" class="form-control">
</div>

<div class="col-md-3">
<label class="form-label campo-obligatorio">Fecha de Ingreso</label>
<input type="date" name="fecha_ingreso_codeudor" class="form-control">
</div>

<div class="col-md-3">
<label class="form-label campo-obligatorio">Total Devengado</label>
<input type="number" name="devengado_codeudor" class="form-control">
</div>

<div class="col-md-3">
<label class="form-label campo-obligatorio">Total Descuentos</label>
<input type="number" name="descuentos_codeudor" class="form-control">
</div>


<div class="col-md-3">
<label class="form-label campo-obligatorio">Neto a Pagar</label>
<input type="number" name="neto_codeudor" class="form-control">
</div>

<div class="col-md-3">
<label class="form-label campo-obligatorio">Dirección Laboral</label>
<input type="text" name="direccion_codeudor" class="form-control">
</div>

<div class="col-md-3">
<label class="form-label campo-obligatorio">Celular Laboral</label>
<input type="text" name="telefono_codeudor" class="form-control">
</div>

<div class="col-md-3">
<label class="form-label campo-obligatorio">Ciudad</label>
<select name="ciudad_codeudor" class="form-select">
<option value="">Seleccione</option>
<?php
$sql="SELECT * FROM ciudades ORDER BY nombre ASC";
$resultado=mysqli_query($conexion,$sql);
while($row=mysqli_fetch_assoc($resultado)){
echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
}
?>
</select>
</div>

<div class="col-md-3">
<label>Ocupación</label>
<input type="text" name="ocupacion_codeudor" class="form-control">
</div>

<div class="col-md-3">
<label>Cargo</label>
<input type="text" name="cargo_codeudor" class="form-control">
</div>

<div class="col-md-3">

    <label class="form-label d-block">
        Actividad Económica (CIIU)
    </label>

    <div class="form-check">
        <input 
            class="form-check-input"
            type="checkbox"
            name="ciiu_codeudor"
            value="1">

        <label class="form-check-label">
            
        </label>
    </div>

</div>

<div class="col-md-3">

    <label class="form-label d-block">
        Sector Económico
    </label>

    <div class="form-check">
        <input 
            class="form-check-input"
            type="checkbox"
            name="sector_codeudor"
            value="1">

        <label class="form-check-label">
            
        </label>
    </div>

</div>

</div>


<hr class="mt-4 mb-3">

<h5 class="section-title mt-4">
<b>INFORMACIÓN FINANCIERA CODEUDOR</b>
</h5>

<div class="row g-3">

<div class="col-md-4">
<label class="form-label campo-obligatorio">Total Ingresos</label>
<input type="number" name="ingresos_codeudor" class="form-control">
</div>

<div class="col-md-4">
<label class="form-label campo-obligatorio">Otros Ingresos</label>
<input type="number" name="otros_ingresos_codeudor" class="form-control">
</div>

<div class="col-md-4">
<label class="form-label campo-obligatorio">Total Egresos</label>
<input type="number" name="egresos_codeudor" class="form-control">
</div>

<div class="col-md-4">
<label class="form-label campo-obligatorio">Activos</label>
<input 
    type="number"
    name="activos_codeudor"
    id="activos_codeudor"
    class="form-control">
</div>

<div class="col-md-4">
<label class="form-label campo-obligatorio">Pasivos</label>
<input 
    type="number"
    name="pasivos_codeudor"
    id="pasivos_codeudor"
    class="form-control">
</div>

<div class="col-md-4">
<label class="form-label campo-obligatorio">Patrimonio</label>
<input 
    type="text"
    name="patrimonio_codeudor"
    id="patrimonio_codeudor"
    class="form-control"
    readonly>
</div>

</div>






</div>
</div>

<!-- ================= SECCIÓN CÓNYUGE (OCULTA) ================= -->
<div id="stepConyuge" style="display:none; margin-top:40px;">

<hr>

<h5 class="section-title">DATOS DEL CÓNYUGUE</h5>

<div class="container-fluid">

<!-- FILA 1 -->
<div class="row">

<div class="col-md-3 mb-3">
<label class="form-label campo-obligatorio">Nombre del Cónyuge</label>
<input type="text" id="nombreConyuge" name="nombre_conyuge" class="form-control campo-conyugue"  >
</div>

<div class="col-md-3 mb-3">
  <label class="form-label campo-obligatorio">Tipo de identificación</label>
<select name="tipo_identificacion_conyugue"  class="form-control campo-conyugue" >
<option value="">Seleccione</option>
<?php
$sql="SELECT * FROM tipo_identificacion";
$resultado=mysqli_query($conexion,$sql);
while($consulta=mysqli_fetch_array($resultado)){
echo '<option value="'.$consulta['id'].'">'.$consulta['nombre'].'</option>';
}
?>
</select>
</div>

<div class="col-md-3 mb-3">
  <label class="form-label campo-obligatorio">Documento del Cónyuge</label>
<input type="text" class="form-control campo-conyugue" id="docConyuge" name="doc_conyuge"  >
</div>

<div class="col-md-3 mb-3">
  <label class="form-label campo-obligatorio">Lugar de Expedición</label>
<select name="id_municipio_conyugue" class="form-control campo-conyugue" >
<option value="">Seleccione Municipio</option>
<?php
$sql="SELECT * FROM ciudades ORDER BY nombre ASC";
$resultado=mysqli_query($conexion,$sql);
while($row=mysqli_fetch_assoc($resultado)){
echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
}
?>
</select>
</div>

</div>

<!-- FILA 2 -->
<div class="row">

<div class="col-md-3 mb-3">
  <label class="form-label campo-obligatorio">Fecha de Expedición</label>
<input type="date" name="fecha_expedicion_conyugue" class="form-control campo-conyugue" >
</div>

<div class="col-md-3 mb-3">
  <label class="form-label campo-obligatorio">Celular del Cónyuge</label>
<div class="position-relative">

    <input 
        type="text"
        name="ceular_conyugue"
        id="celularConyugue"
        class="form-control campo-conyugue">

    <small 
        id="errorCelularConyugue"
        class="mensaje-error-celular">
        El número de celular debe iniciar con 3
    </small>

</div>
</div>

<div class="col-md-3 mb-3">
<label>Teléfono del Cónyuge</label>
<input type="text" class="form-control campo-conyugue" id="telConyuge" name="tel_conyuge" >
</div>

<div class="col-md-3 mb-3">
  <label class="form-label campo-obligatorio">Empresa</label>
<input type="text" name="empresa_conyugue" class="form-control campo-conyugue" >
</div>

</div>

<!-- FILA 3 -->
<div class="row">

<div class="col-md-3 mb-3">
  <label class="form-label campo-obligatorio">Email</label>
<input type="email" name="correoconyugue" class="form-control campo-conyugue">
</div>

<div class="col-md-3 mb-3">
  <label class="form-label campo-obligatorio">Ocupación</label>
<select class="form-control campo-conyugue" id="ocupacion_select_conyugue" name="ocupacion_conyugue">
<option value="">Seleccione...</option>
<option value="Empleado">Empleado</option>
<option value="No empleado">No empleado</option>
<option value="Independiente">Independiente</option>
<option value="Pensionado">Pensionado</option>
<option value="Estudiante">Estudiante</option>
<option value="Hogar">Hogar</option>
<option value="Cesante">Cesante</option>
<option value="Inversionista">Inversionista</option>
</select>
</div>

</div>

</div>



</div>


<!-- BOTÓN -->
<div class="d-flex justify-content-end mt-4">
  <button type="button" class="btn btn-primary px-4" onclick="nextStep()">
    Siguiente →
  </button>
</div>

</div>
</div>






<!-- ================= PASO 3 ================= -->
<div class="step">

<h5 class="section-title">INFORMACIÓN LABORAL</h5>

<div class="container-fluid">

<div class="row">

<div class="col-md-3 mb-3">
  <label class="form-label campo-obligatorio">Empresa</label>
<input type="text" name="empresa" class="form-control" required>
</div>


<div class="col-md-3 mb-3">
<label class="form-label campo-obligatorio">Tipo de Contrato</label>
<select class="form-select" name="tipo_contrato" id="tipo_contrato" required>
<option value="">Seleccione...</option>
<option value="Termino Fijo">Término fijo</option>
<option value="Termino Indefinido">Término indefinido</option>
<option value="Obra o Labor">Obra o labor</option>
<option value="Prestacion de Servicios">Prestación de servicios</option>
<option value="Aprendizaje">Aprendizaje (SENA)</option>
<option value="Temporal">Temporal</option>
<option value="Medio Tiempo">Medio tiempo</option>
<option value="Tiempo Completo">Tiempo completo</option>
<option value="Otro">Otro</option>
</select>
</div>

<div class="col-md-3 mb-3">
<label class="form-label campo-obligatorio">Fecha de Ingreso</label>
<input type="date" name="fecha_ingreso_laboral" class="form-control" required>
</div>

<div class="col-md-3 mb-3">
<label class="form-label campo-obligatorio">Total Devengado</label>
<input type="number" name="totalDevengado" id="totalDevengado" class="form-control" required>
</div>

</div>

<div class="row">

<div class="col-md-3 mb-3">
<label class="form-label campo-obligatorio">Total Descuentos</label>
<input type="number" name="totalDescuentos" id="totalDescuentos" class="form-control" required>
</div>


<div class="col-md-3 mb-3">
<label class="form-label campo-obligatorio">Neto a Pagar</label>
<input type="number" name="netoPagar" id="netoPagar" class="form-control" readonly>
</div>



<div class="col-md-3 mb-3">
<label>Direccion Laboral</label>
<input type="text" name="direccion_laboral" class="form-control">
</div>

<div class="col-md-3 mb-3">
<label class="form-label campo-obligatorio">Ciudad</label>
<select name="id_municipio_laboral" class="form-select" required>
<option value="">Seleccione Municipio</option>
<?php
$sql="SELECT * FROM ciudades ORDER BY nombre ASC";
$resultado=mysqli_query($conexion,$sql);
while($row=mysqli_fetch_assoc($resultado)){
echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
}
?>
</select>
</div>

</div>

<div class="row">
<div class="col-md-3 mb-3">
<label>Ocupacion</label>
<input type="text" name="ocupacion_laboral" class="form-control">
</div>

<div class="col-md-3 mb-3">
<label>Grado/Cargo</label>
<input type="text" name="cargo_laboral" class="form-control">
</div>

</div>

</div>

<div class="d-flex justify-content-between mt-4">
    <button type="button" class="btn btn-outline-secondary px-4" onclick="prevStep()">
        ← Anterior
    </button>
    <button type="button" class="btn btn-primary px-4" onclick="nextStep()">
        Siguiente →
    </button>
</div>
</div>

<!-- ================= PASO 4 ================= -->
<div class="step">

<h5 class="section-title">INFORMACIÓN FINANCIERA</h5>

<div class="container-fluid">

<div class="row">

<div class="col-md-6 mb-3">
<label class="form-label">Total Ingresos</label>
<input type="number" name="totalIngresos" class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Otros Ingresos</label>
<input type="number" name="otrosIngresos" class="form-control">
</div>

</div>

<div class="row">

<div class="col-md-6 mb-3">
<label>Total Egresos</label>
<input type="number" name="totalEgresos" class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Activos</label>
<input type="number" name="activos" class="form-control">
</div>

</div>

<div class="row">

<div class="col-md-6 mb-3">
<label>Pasivos</label>
<input type="number" name="pasivos" class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Patrimonios</label>
<input type="number" name="patrimonios" id="patrimonios" class="form-control" readonly>
</div>

</div>

</div>

<div class="d-flex justify-content-between mt-4">
    <button type="button" class="btn btn-outline-secondary px-4" onclick="prevStep()">
        ← Anterior
    </button>
    <button type="button" class="btn btn-primary px-4" onclick="nextStep()">
        Siguiente →
    </button>
</div>

</div>



<!-- ================= PASO 5 ================= -->
<div class="step" id="stepFoto">

<h5 class="section-title">FOTO DEL CLIENTE</h5>

<div class="container-fluid">

<div class="row">

<div class="col-md-6 text-center">

<div class="camera-box" id="cameraBox">
<video id="video" width="100%" height="250" autoplay class="w-100"></video>
</div>

<div class="mt-3">
<button type="button" id="btnTomarFoto" class="btn btn-primary">
📸 Tomar Foto
</button>

<button type="button" id="btnRepetirFoto" class="btn btn-warning d-none">
🔄 Repetir Foto
</button>
</div>

</div>

<div class="col-md-6 text-center">
<div class="camera-box">
<canvas id="canvas" width="300" height="250" class="w-100"></canvas>
</div>
</div>

</div>

</div>

<input type="hidden" name="fotoBase64" id="fotoBase64">
<input type="hidden" name="accion" value="SaveClient">

<div class="d-flex justify-content-between mt-4">
    <button type="button" class="btn btn-outline-secondary px-4" onclick="prevStep()">
        ← Anterior
    </button>
    <button type="submit" class="btn btn-success px-4">
        💾 Guardar Cliente
    </button>
</div>

</div>


          </div>

        </form>
      </div>

    </div>
  </div>
  
  <style>
/* Contenedor de cámara */
.camera-box {
    border: 3px dashed #0d6efd;
    border-radius: 15px;
    padding: 10px;
    background: #f8f9fa;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.camera-box:hover {
    border-color: #198754;
    transform: scale(1.01);
}

/* Video */
#video {
    border-radius: 10px;
    object-fit: cover;
}

/* Canvas (foto tomada) */
#canvas {
    border-radius: 10px;
    background: #ffffff;
}

/* Animación cuando está activa */
.camera-active {
    border-color: #198754 !important;
    box-shadow: 0 0 20px rgba(25,135,84,0.4);
}

.section-title {
    color: #000a38;
    font-weight: 700;
    border-left: 5px solid #6424ff;
    padding-left: 12px;
    margin-bottom: 20px;
}

.input-error {
    border: 2px solid #dc3545 !important;
    box-shadow: 0 0 5px rgba(220,53,69,0.5);
}


/* WIZARD PRO */
.wizard-progress {
    height: 8px;
    background: #e9ecef;
    border-radius: 50px;
    overflow: hidden;
}

.wizard-progress-bar {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, #0d6efd, #20c997);
    transition: width 0.4s ease;
}

.step {
    display: none;
    opacity: 0;
    transform: translateX(20px);
    transition: all 0.4s ease;
}

.step.active {
    display: block;
    opacity: 1;
    transform: translateX(0);
}

.hidden-step {
    display: none !important;
}

.input-readonly {
    background-color: #e9ecef !important;
    color: #495057;
    font-weight: 600;
    border: 1px solid #ced4da;
    cursor: not-allowed;
}

.mensaje-error-celular{
    color: red;
    font-size: 12px;
    display: none;
    position: absolute;
    margin-top: 2px;
}


.campo-obligatorio::after{
    content: " *";
    color: red;
    font-weight: bold;
}


</style>



<script>
$(document).ready(function() {

    $('#formClient').submit(function(e) {

        e.preventDefault();

        // 🔴 VALIDAR FOTO
        if (!$('#fotoBase64').val()) {
            Swal.fire({
                icon: 'warning',
                title: 'Foto obligatoria',
                text: 'Debe tomar la foto antes de guardar',
                confirmButtonColor: '#6424ff'
            });
            return;
        }

		console.log($(this).serializeArray());
        var formData = $(this).serialize();

        $.ajax({
            url: '../includes/functions.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {

    if (response.status === 'success') {

        Swal.fire({
            icon: 'success',
            title: 'El cliente se guardo correctamente',
            confirmButtonColor: '#6424ff'
        }).then(() => {

            // ✅ Forma correcta en Bootstrap 5
            let modal = bootstrap.Modal.getInstance(document.getElementById('addModal'));
            modal.hide();

            // 🔥 Limpiar fondo oscuro si queda
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            document.body.style = '';

            // Resetear formulario
            document.getElementById('formClient').reset();
			
			   location.reload(); // 🔥 ESTA LÍNEA ACTUALIZA LA TABLA

            // Volver al primer paso del wizard
            showStep(0);

        });

    } else {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Ocurrió un error',
                        confirmButtonColor: '#6424ff'
                    });

                }

            },
          error: function(xhr) {

    console.log(xhr.responseText);

    Swal.fire({
        icon: 'error',
        title: 'Error del servidor',
        text: xhr.responseText,
        confirmButtonColor: '#6424ff'
    });
}
        });

    });

});
</script>












<script>
$(document).ready(function() {
    $('#lugar_expedicion').select2({
        placeholder: "Buscar municipio...",
        minimumInputLength: 2,
        ajax: {
            url: '../includes/buscar_municipios.php',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    term: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
});
</script>

<script>
$(document).ready(function(){

  $('#tipo_vivienda').change(function(){

    if($(this).val() === 'Otra'){

      // Cambiar label
      $('#label_dinamico').text('¿Cuál?');

      // Mostrar input
      $('#otro_vivienda').removeClass('d-none');

      // Ocultar select ocupación
      $('#ocupacion_select').addClass('d-none');

      // Crear fila nueva para ocupación abajo
      if($('#fila_ocupacion_extra').length === 0){
        $('#fila_vivienda').after(`
          <div class="row" id="fila_ocupacion_extra">
            <div class="col-md-3 mb-3">
              <label class="form-label">Ocupación</label>
              <select class="form-select">
                <option value="">Seleccione...</option>
                <option>Empleado</option>
                <option>No empleado</option>
                <option>Independiente</option>
                <option>Pensionado</option>
                <option>Estudiante</option>
                <option>Hogar</option>
                <option>Cesante</option>
                <option>Inversionista</option>
              </select>
            </div>
          </div>
        `);
      }

    } else {

      // Volver a estado normal
      $('#label_dinamico').text('Ocupación');
      $('#otro_vivienda').addClass('d-none').val('');
      $('#ocupacion_select').removeClass('d-none');

      $('#fila_ocupacion_extra').remove();

    }

  });

});
</script>

<script>
$(document).ready(function(){

  $('input[name="condicion_medica"]').change(function(){

    if($(this).val() === 'Si'){
      $('#campo_cual_condicion').removeClass('d-none');
    } else {
      $('#campo_cual_condicion').addClass('d-none');
      $('input[name="detalle_condicion"]').val('');
    }

  });

});



function toggleConyugue(required) {

    const campos = document.querySelectorAll('.campo-conyugue');

    campos.forEach(campo => {

        if (required) {

            campo.removeAttribute('disabled'); // 🔥 HABILITA

            // 🔥 NO poner required a checkbox
            if (
    campo.type !== "checkbox" &&
    campo.name !== "tel_conyuge"
) {
    campo.setAttribute('required', 'required');
}

        } else {

            campo.removeAttribute('required');

            // 🔥 LIMPIAR CHECKBOX
            if (campo.type === "checkbox") {
                campo.checked = false;
            } else {
                campo.value = '';
            }

            campo.setAttribute('disabled', 'disabled'); // 🔥 DESHABILITA
        }

    });
}



</script>

<script>

let currentStep = 0; // 🔥 MOVER AFUERA

document.addEventListener("DOMContentLoaded", function () {
	
function validarConyugue() {

    $(document).on('change', '#estado_civil', function () {

        const estado = $(this).val();

        const seccion =
            document.getElementById('stepConyuge');

        const nombreConyuge =
            document.getElementById('nombreConyuge');

        console.log("CAMBIO DETECTADO:", estado);

        if (
            estado === 'Casado'
            || estado === 'Union Libre'
        ) {

            seccion.style.display = 'block';

            // 🔥 ACTIVAR REQUIRED
            toggleConyugue(true);

        } else {

            seccion.style.display = 'none';

            // 🔥 QUITAR REQUIRED
            toggleConyugue(false);

        }

    });

}

  function getVisibleSteps() {
    return Array.from(document.querySelectorAll(".step"))
        .filter(step => !step.classList.contains("hidden-step"));
}

    function showStep(index) {

        const steps = getVisibleSteps();

        steps.forEach(step => step.classList.remove("active"));

        if (!steps[index]) {
    index = 0;
}

        steps[index].classList.add("active");

        let progressPercent = ((index + 1) / steps.length) * 100;
        document.getElementById("wizardProgressBar").style.width = progressPercent + "%";

        currentStep = index;
		
		 if (typeof activarCamaraSiEsPasoFoto === "function") {
    activarCamaraSiEsPasoFoto();
}
    }

    window.nextStep = function () {

        const steps = getVisibleSteps();

        if (!validateStep(currentStep)) return;

        if (currentStep < steps.length - 1) {
            showStep(currentStep + 1);
        }
    };

    window.prevStep = function () {

        if (currentStep > 0) {
            showStep(currentStep - 1);
        }
    };

window.validateStep = function (stepIndex) {

    const steps = getVisibleSteps();
    const currentSection = steps[stepIndex];
    const inputs = currentSection.querySelectorAll("input, select, textarea");

    for (let input of inputs) {

        input.classList.remove("input-error");

        // IGNORAR RADIOS
        if (input.type === "radio") {
            continue;
        }

        // TELÉFONO NO OBLIGATORIO
      // TELÉFONOS NO OBLIGATORIOS
if (
    input.name === "telClient" ||
    input.name === "telefonocodeudor"
) {
    continue;
}

        // VALIDAR REQUIRED
    // VALIDAR CHECKBOX
if (
    input.type === "checkbox" &&
    input.hasAttribute("required") &&
    !input.checked
) {

    input.classList.add("input-error");

    let label = input.closest(".col-md-3, .col-md-4, .col-md-6")
        ?.querySelector("label")?.innerText || input.name;

    Swal.fire({
        icon: 'warning',
        title: 'Campo obligatorio',
        text: label + " es obligatorio",
        confirmButtonColor: '#6424ff'
    });

    input.focus();
    return false;
}

// VALIDAR REQUIRED NORMAL
if (
    input.hasAttribute("required") &&
    input.type !== "checkbox" &&
    !input.value.trim()
) {

    input.classList.add("input-error");

    let label = input.closest(".col-md-3, .col-md-4, .col-md-6")
        ?.querySelector("label")?.innerText || input.name;

    Swal.fire({
        icon: 'warning',
        title: 'Campo obligatorio',
        text: label + " es obligatorio",
        confirmButtonColor: '#6424ff'
    });

    input.focus();
    return false;
}

        // VALIDAR EMAIL
        if (input.type === "email" && input.value.trim() !== "") {

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(input.value.trim())) {

                input.classList.add("input-error");

                Swal.fire({
                    icon: 'warning',
                    title: 'Email inválido',
                    text: "El correo no tiene un formato válido",
                    confirmButtonColor: '#6424ff'
                });

                input.focus();
                return false;
            }
        }
    }
	
	// VALIDAR MENSAJES DE ERROR VISIBLES
const errores = currentSection.querySelectorAll('.mensaje-error-celular');

for (let error of errores) {

    if (error.style.display === "block") {

        Swal.fire({
            icon: 'warning',
            title: 'Campos inválidos',
            text: 'Corrige los teléfonos o celulares antes de continuar',
            confirmButtonColor: '#6424ff'
        });

        return false;
    }
}

    return true;
};

   showStep(0);
validarConyugue(); // 👈 AGREGAR ESTA LÍNEA

});

</script>


</script>



<script>
document.addEventListener("DOMContentLoaded", function () {

    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const btnTomarFoto = document.getElementById('btnTomarFoto');
    const btnRepetirFoto = document.getElementById('btnRepetirFoto');
    const fotoInput = document.getElementById('fotoBase64');
    const cameraBox = document.getElementById('cameraBox');

    let stream = null;

    async function iniciarCamara() {

        if (stream) return; // 🔥 evita múltiples activaciones

        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
            cameraBox.classList.add('camera-active');
        } catch (error) {
            console.error("Error cámara:", error);
            Swal.fire({
                icon: 'error',
                title: 'No se pudo activar la cámara',
                text: 'Permite acceso a la cámara en el navegador',
                confirmButtonColor: '#6424ff'
            });
        }
    }

    function detenerCamara() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }

        video.srcObject = null;
        cameraBox.classList.remove('camera-active');
    }

    // 📸 TOMAR FOTO
    btnTomarFoto.addEventListener('click', function () {

        if (!stream) {
            Swal.fire({
                icon: 'warning',
                title: 'Cámara no activa',
                text: 'Primero debe activarse la cámara',
                confirmButtonColor: '#6424ff'
            });
            return;
        }

        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const imageData = canvas.toDataURL('image/png');
        fotoInput.value = imageData;

        btnTomarFoto.classList.add('d-none');
        btnRepetirFoto.classList.remove('d-none');
    });

    // 🔄 REPETIR FOTO
    btnRepetirFoto.addEventListener('click', function () {

        const context = canvas.getContext('2d');
        context.clearRect(0, 0, canvas.width, canvas.height);

        fotoInput.value = '';

        btnRepetirFoto.classList.add('d-none');
        btnTomarFoto.classList.remove('d-none');
    });

    // 🚀 Activar o detener según paso
    window.activarCamaraSiEsPasoFoto = function () {
        const stepFoto = document.getElementById('stepFoto');

        if (stepFoto.classList.contains('active')) {
            iniciarCamara();
        } else {
            detenerCamara();
        }
    };

    // 🔥 Apagar cámara al cerrar modal
    $('#addModal').on('hidden.bs.modal', function () {
        detenerCamara();
    });

});

document.addEventListener("DOMContentLoaded", function () {

    const selectCodeudor = document.getElementById("tiene_codeudor");
    const seccion = document.getElementById("datos_codeudor");

    function toggleCodeudor() {

        let campos = document.querySelectorAll(
            "#datos_codeudor input, #datos_codeudor select"
        );

        if (selectCodeudor.value == "1") {

            seccion.style.display = "block";

            campos.forEach(campo => {

                // 🔥 NO obligar ciertos campos
                if (
                    campo.type !== "hidden" &&
                    campo.type !== "checkbox" &&
                    campo.name !== "ocupacion_codeudor" &&
                    campo.name !== "cargo_codeudor" &&
                    campo.name !== "telefono_codeudor"
                    
                ) {

                    campo.setAttribute(
                        "required",
                        "required"
                    );

                }

            });

        } else {

            seccion.style.display = "none";

            campos.forEach(campo => {

                campo.removeAttribute("required");

                if (campo.type === "checkbox") {

                    campo.checked = false;

                } else {

                    campo.value = "";

                }

            });

        }

    }

    // CAMBIO DEL SELECT
    selectCodeudor.addEventListener(
        "change",
        toggleCodeudor
    );

    // EJECUTAR AL CARGAR
    toggleCodeudor();

});


    



function togglePlaca(tieneVehiculo) {

    var placa = document.getElementById("placaVehiculo");

    if (tieneVehiculo) {

        placa.disabled = false;
        placa.setAttribute("required", "required");

    } else {

        placa.disabled = true;
        placa.removeAttribute("required");
        placa.value = "";

    }

}





document.addEventListener("DOMContentLoaded", function(){

    const devengado = document.getElementById("totalDevengado");
    const descuentos = document.getElementById("totalDescuentos");
    const neto = document.getElementById("netoPagar");

    function calcularNeto() {

        let valDev = parseFloat(devengado.value) || 0;
        let valDesc = parseFloat(descuentos.value) || 0;

        let resultado = valDev - valDesc;

        neto.value = resultado >= 0 ? resultado : 0;
    }

    devengado.addEventListener("input", calcularNeto);
    descuentos.addEventListener("input", calcularNeto);

});

function calcularNeto() {

    let devengado = parseFloat(document.querySelector('[name="totalDevengado"]').value) || 0;
    let descuentos = parseFloat(document.querySelector('[name="totalDescuentos"]').value) || 0;

    // 🚨 VALIDACIÓN CLAVE
    if (descuentos > devengado) {

        Swal.fire({
            icon: 'warning',
            title: 'Valor inválido',
            text: 'Los descuentos no pueden ser mayores al total devengado',
            confirmButtonColor: '#6424ff'
        });

        document.querySelector('[name="totalDescuentos"]').value = "";
        document.querySelector('[name="netoPagar"]').value = "";

        return;
    }

    let neto = devengado - descuentos;

    document.querySelector('[name="netoPagar"]').value = neto;
}

// 🔥 Eventos automáticos
document.querySelector('[name="totalDevengado"]').addEventListener("input", calcularNeto);
document.querySelector('[name="totalDescuentos"]').addEventListener("input", calcularNeto);


function calcularPatrimonio() {

    let activos = parseFloat(document.querySelector('[name="activos"]').value) || 0;
    let pasivos = parseFloat(document.querySelector('[name="pasivos"]').value) || 0;

    // 🚨 Validación: pasivos no pueden ser mayores
    if (pasivos > activos) {

        Swal.fire({
            icon: 'warning',
            title: 'Valor inválido',
            text: 'Los pasivos no pueden ser mayores a los activos',
            confirmButtonColor: '#6424ff'
        });

        document.querySelector('[name="pasivos"]').value = "";
        document.querySelector('[name="patrimonios"]').value = "";

        return;
    }

    let patrimonio = activos - pasivos;

    document.querySelector('[name="patrimonios"]').value = patrimonio;
}

// 🔥 Eventos automáticos
document.querySelector('[name="activos"]').addEventListener("input", calcularPatrimonio);
document.querySelector('[name="pasivos"]').addEventListener("input", calcularPatrimonio);



document.addEventListener("DOMContentLoaded", function () {

    const activos = document.getElementById("activos_codeudor");
    const pasivos = document.getElementById("pasivos_codeudor");
    const patrimonio = document.getElementById("patrimonio_codeudor");

function calcularPatrimonio() {

    let valorActivos = parseFloat(activos.value) || 0;
    let valorPasivos = parseFloat(pasivos.value) || 0;

    // VALIDAR PASIVOS
    if (valorPasivos > valorActivos) {

        Swal.fire({
            icon: 'warning',
            title: 'Valores inválidos',
            text: 'Los pasivos no pueden ser mayores que los activos',
            confirmButtonColor: '#6424ff'
        });

        pasivos.value = "";
        patrimonio.value = "";

        pasivos.focus();

        return;
    }

    let total = valorActivos - valorPasivos;

    patrimonio.value = total;
}

    activos.addEventListener("input", calcularPatrimonio);
    pasivos.addEventListener("input", calcularPatrimonio);

});


document.addEventListener("DOMContentLoaded", function () {

    // FUNCIÓN GENERAL
    function validarCelular(input, errorElement) {

        let valor = input.value;

        // SOLO NÚMEROS
        valor = valor.replace(/\D/g, '');

        input.value = valor;

        let mensajes = [];

        // DEBE INICIAR EN 3
        if (
            valor.length > 0 &&
            !valor.startsWith("3")
        ) {
            mensajes.push("El número debe iniciar con 3");
        }

        // DEBE TENER 10 DÍGITOS
        if (
            valor.length > 0 &&
            valor.length < 10
        ) {
            mensajes.push("El número debe tener 10 dígitos");
        }

        // MÁXIMO 10 DÍGITOS
        if (valor.length > 10) {

            valor = valor.substring(0, 10);
            input.value = valor;
        }

        // MOSTRAR / OCULTAR ERRORES
        if (mensajes.length > 0) {

            input.classList.add("input-error");

            errorElement.innerHTML = mensajes.join("<br>");
            errorElement.style.display = "block";

        } else {

            input.classList.remove("input-error");

            errorElement.style.display = "none";
        }
    }

    // =========================
    // CLIENTE
    // =========================

    const celular = document.getElementById("celClient");
    const errorCelular = document.getElementById("errorCelular");

    if (celular) {

        celular.addEventListener("input", function () {
            validarCelular(celular, errorCelular);
        });

    }

    // =========================
    // CODEUDOR
    // =========================

    const celularCodeudor = document.getElementById("celularcodeudor");
    const errorCodeudor = document.getElementById("errorCelularCodeudor");

    if (celularCodeudor) {

        celularCodeudor.addEventListener("input", function () {
            validarCelular(celularCodeudor, errorCodeudor);
        });

    }

    // =========================
    // CÓNYUGUE
    // =========================

    const celularConyugue = document.getElementById("celularConyugue");
    const errorConyugue = document.getElementById("errorCelularConyugue");

    if (celularConyugue) {

        celularConyugue.addEventListener("input", function () {
            validarCelular(celularConyugue, errorConyugue);
        });

    }

});


function validarTelefono(input, errorElement) {

    let valor = input.value;

    // SOLO NÚMEROS
    valor = valor.replace(/\D/g, '');

    input.value = valor;

    let mensajes = [];

    // DEBE INICIAR EN 60
    if (
        valor.length > 0 &&
        !valor.startsWith("60")
    ) {
        mensajes.push("El teléfono debe iniciar con 60");
    }

    // DEBE TENER 10 DÍGITOS
    if (
        valor.length > 0 &&
        valor.length < 10
    ) {
        mensajes.push("El teléfono debe tener 10 dígitos");
    }

    // MÁXIMO 10
    if (valor.length > 10) {

        valor = valor.substring(0, 10);
        input.value = valor;
    }

    // MOSTRAR ERRORES
    if (mensajes.length > 0) {

        input.classList.add("input-error");

        errorElement.innerHTML = mensajes.join("<br>");
        errorElement.style.display = "block";

    } else {

        input.classList.remove("input-error");

        errorElement.style.display = "none";
    }
}

const telefono = document.getElementById("telClient");
const errorTelefono = document.getElementById("errorTelefono");

if (telefono) {

    telefono.addEventListener("input", function () {
        validarTelefono(telefono, errorTelefono);
    });

}

const telefonoCodeudor = document.getElementById("telefonocodeudor");
const errorTelefonoCodeudor = document.getElementById("errorTelefonoCodeudor");

if (telefonoCodeudor) {

    telefonoCodeudor.addEventListener("input", function () {
        validarTelefono(telefonoCodeudor, errorTelefonoCodeudor);
    });

}

const telefonoConyugue = document.getElementById("telefonoConyugue");
const errorTelefonoConyugue = document.getElementById("errorTelefonoConyugue");

if (telefonoConyugue) {

    telefonoConyugue.addEventListener("input", function () {
        validarTelefono(telefonoConyugue, errorTelefonoConyugue);
    });

}

function toggleCondicionCodeudor(required) {

    const campo = document.getElementById(
        "detalle_condicion_codeudor"
    );

    if (required) {

        campo.removeAttribute("disabled");
        campo.setAttribute("required", "required");

    } else {

        campo.value = "";
        campo.setAttribute("disabled", "disabled");
        campo.removeAttribute("required");
    }
}

function togglePlacaCliente(required) {

    const placa = document.getElementById(
        "placaVehiculoCliente"
    );

    if (required) {

        placa.removeAttribute("disabled");
        placa.setAttribute("required", "required");

    } else {

        placa.value = "";

        placa.setAttribute(
            "disabled",
            "disabled"
        );

        placa.removeAttribute("required");
    }
}

function toggleCondicionMedica(required) {

    const campo = document.getElementById(
        "detalleCondicionMedica"
    );

    if (required) {

        campo.removeAttribute("disabled");
        campo.setAttribute("required", "required");

    } else {

        campo.value = "";

        campo.setAttribute(
            "disabled",
            "disabled"
        );

        campo.removeAttribute("required");
    }
}

document.addEventListener("DOMContentLoaded", function () {

    const modalFormulario = document.getElementById(
        "addModal"
    );

    modalFormulario.addEventListener(
        "hidden.bs.modal",
        function () {

            // LIMPIAR FORMULARIO
            document.getElementById(
                "formClient"
            ).reset();

            // LIMPIAR VALIDACIONES
            document.querySelectorAll(
                "#formClient input, #formClient select, #formClient textarea"
            ).forEach(campo => {

                campo.classList.remove(
                    "is-invalid",
                    "is-valid",
                    "input-error"
                );

            });

            // OCULTAR SECCIONES
            const conyuge = document.getElementById(
                "stepConyuge"
            );

            if (conyuge) {
                conyuge.style.display = "none";
            }

            const codeudor = document.getElementById(
                "datos_codeudor"
            );

            if (codeudor) {
                codeudor.style.display = "none";
            }

            // VOLVER AL PASO 1
            if (typeof showStep === "function") {
                showStep(0);
            }

        }
    );

});


</script>