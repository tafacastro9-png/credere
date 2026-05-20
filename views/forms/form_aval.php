<!-- Modal -->

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Formulario Referencias</h5>
                <button type="button" class="close btn btn-light" data-bs-dismiss="modal" aria-label="Close">
                    <span class="mdi mdi-window-close"></span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAval">
                    <?php $folio = mt_rand(100000000, 888888888); ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">#Consecutivo Referencia</label>
                                <input type="text" id="folioAval" name="folioAval" class="form-control" readonly value="<?php echo $folio; ?>" required>
                            </div>
                        </div>
						
						<div class="col-sm-6">
                            <div class="mb-3">
                                <label for="password">Tipo de Referencia</label><br>
                                <select name="id_tiporeferencia" id="id_tiporeferencia" class="form-control" required>
                                    <option value="">Selecciona una opcion</option>
                                    <?php
                                    //Consulta para llamar a las categorias 
                                    include "../includes/db.php";
                                    $sql = "SELECT * FROM tipo_referencia";
                                    $resultado = mysqli_query($conexion, $sql);
                                    while ($consulta = mysqli_fetch_array($resultado)) {
                                        echo '<option value="' . $consulta['id'] . '">' . $consulta['nombre'] . '</option>';
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>
						
						<div class="mb-3" id="divParentesco" style="display:none;">
  <label for="parentesco" class="form-label">Parentesco</label>
  <select name="parentesco" id="parentesco" class="form-control">
    <option value="">Seleccione...</option>
    <option value="Padre">Padre</option>
    <option value="Madre">Madre</option>
    <option value="Hijo">Hijo</option>
    <option value="Hija">Hija</option>
    <option value="Hermano">Hermano</option>
    <option value="Hermana">Hermana</option>
    <option value="Abuelo">Abuelo</option>
    <option value="Abuela">Abuela</option>
    <option value="Tío">Tío</option>
    <option value="Tía">Tía</option>
    <option value="Primo">Primo</option>
    <option value="Prima">Prima</option>
    <option value="Cónyuge">Cónyuge</option>
    <option value="Suegro">Suegro</option>
    <option value="Suegra">Suegra</option>
    <option value="Cuñado">Cuñado</option>
    <option value="Cuñada">Cuñada</option>
    <option value="Otro">Otro</option>
  </select>
</div>
						
						
						
</div>

                       <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombres</label>
                                <input type="text" id="nombreAval" name="nombreAval" class="form-control" required>
                            </div>
                        </div>
                    

                   
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="password">Apellidos</label><br>
                                <input type="text" name="apellidoAval" id="apellidoAval" class="form-control" required>
                            </div>
                        </div>
						
						</div>
						
								<div class="row">
					<div class="col-sm-6">
                            <div class="mb-3">
                                <label for="password">Tipo de Documento</label><br>
                                <select name="id_tipoidentificacion" id="id_tipoidentificacion" class="form-control" required>
                                    <option value="">Selecciona una opcion</option>
                                    <?php
                                    //Consulta para llamar a las categorias 
                                    include "../includes/db.php";
                                    $sql = "SELECT * FROM tipo_identificacion";
                                    $resultado = mysqli_query($conexion, $sql);
                                    while ($consulta = mysqli_fetch_array($resultado)) {
                                        echo '<option value="' . $consulta['id'] . '">' . $consulta['nombre'] . '</option>';
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>
						


                       
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="password">#Documento de Identidad</label><br>
                                <input type="text" name="docIdentAval" id="docIdentAval" class="form-control" placeholder="Ingrese el número de su CURP, DNI o Pasaporte" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="password">Telefono</label><br>
                                <input type="tel" name="telAval" id="telAval" class="form-control" required>
                            </div>
                        </div>
						
						    <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="password">Celular</label><br>
                                <input type="cel" name="celAval" id="celAval" class="form-control" required>
                            </div>
                        </div>
						
						</div>

 <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="password">Correo</label><br>
                                <input type="email" name="correoAval" id="correoAval" class="form-control" placeholder="example@genotipo.com" required>
                            </div>
                        </div>
                    

                   
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="password">Direccion</label><br>
                                <input type="text" name="dirAval" id="dirAval" class="form-control" required>
                            </div>
                        </div>
						
						</div>
						
			<div class="row">			
  <div class="col-md-6">
    <label class="form-label">Ciudad de residencia</label>
    <select name="id_municipioAval" class="form-select" required>
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
						
					
			 
			   
						    <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="barrio">Barrio</label><br>
                                <input type="text" name="barAval" id="barAval" class="form-control" required>
                            </div>
                        </div>
						
						
				 </div>			 
						
 <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="password">Status</label><br>
                                <select name="id_status" id="id_status" class="form-control" required>
                                    <option value="">Selecciona una opcion</option>
                                    <?php
                                    //Consulta para llamar a las categorias 
                                    include "../includes/db.php";
                                    $sql = "SELECT * FROM estado_registros";
                                    $resultado = mysqli_query($conexion, $sql);
                                    while ($consulta = mysqli_fetch_array($resultado)) {
                                        echo '<option value="' . $consulta['id'] . '">' . $consulta['estado'] . '</option>';
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>
                   </div>
	
                    <input type="hidden" name="accion" value="SaveAval">

            </div>
            <br>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="register" name="registrar">Guardar</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
            </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#formAval').submit(function(e) {
            e.preventDefault(); // Evita que el formulario se envíe de forma predeterminada
            var formData = $(this).serialize(); // Serializa los datos del formulario
            $.ajax({
                url: '../includes/functions.php',
                type: 'POST',
                data: formData,
                dataType: 'json', // Espera una respuesta en formato JSON
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Datos Guardados',
                            text: response.message, // ✅ usa el mensaje real
                            timer: 1500,
                            showConfirmButton: false
                        }).then(function() {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Alerta',
                            text: response.message // ✅ usa el mensaje real de error
                        });
                    }
                },

                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error inesperado'
                    });
                }
            });
        });
    });
	
	
	// Mostrar u ocultar parentesco según tipo de referencia
$('#id_tiporeferencia').on('change', function () {

    var textoSeleccionado = $("#id_tiporeferencia option:selected").text().toLowerCase();

    if (textoSeleccionado.includes('familiar')) {
        $('#divParentesco').show();
        $('#parentesco').prop('required', true);
    } else {
        $('#divParentesco').hide();
        $('#parentesco').prop('required', false);
        $('#parentesco').val('');
    }

});
	
	
</script>