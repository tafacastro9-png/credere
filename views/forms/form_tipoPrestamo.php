<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header text-white">
                <h5 class="modal-title">Formulario Tipo de Préstamo</h5>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <span class="mdi mdi-window-close"></span>
                </button>
            </div>

            <form id="formTipoPrestamo">
                <div class="modal-body">

                    <!-- TIPO DE PROYECCIÓN -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tipo de proyección</label>
                            <select id="tipo_proyeccion" name="tipo_proyeccion" class="form-control" required>
                                <option value="">Seleccione</option>
                                   <?php
                                //Consulta para llamar a las categorias 
                                include "../includes/db.php";
                                $sql = "SELECT * FROM tipo_proyeccion";
                                $resultado = mysqli_query($conexion, $sql);
                                while ($consulta = mysqli_fetch_array($resultado)) {
                                    echo '<option value="' . $consulta['id'] . '">' . $consulta['nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
					
				      <!-- <div class="row g-3 mb-3">
                        <div class="col-md-6">
                             <label class="form-label">Tipo de proyección</label>
                           <select id="tipo_proyeccion" name="tipo_proyeccion" class="form-control" required>
                                <option value="">Seleccione</option>
                             
					</div>
					 </div>

                    <!-- BLOQUE SIMPLE -->
                    <div id="bloque_simple" style="display:none;">
                        <div class="row g-3">

                             <div class="col-md-6">
                            <label for="nombre_tipo" class="form-label">Nombre del Tipo</label>
                            <input type="text" class="form-control" id="nombre_tipo" name="nombre_tipo" placeholder="Ej. Personal, Emprendedor" required>
                        </div>


                       <div class="col-md-6">
                            <label for="tasa_interes" class="form-label">Tasa de Interés Anual (%)</label>
                            <input type="number" class="form-control" id="tasa_interes" name="tasa_interes" step="0.01" placeholder="Ej. 5.5" required>
                        </div>

                            <div class="col-md-6">
                                <label class="form-label">Periodo de gracia</label>
                                <input type="number" class="form-control" name="periodo_gracia">
                            </div>

                            <div class="col-md-6">
                           <label for="plazo_meses" class="form-label">Plazo (meses)</label>
                            <input type="number" class="form-control" id="plazo_meses" name="plazo_meses" placeholder="Ej. 30, 60, 90" required>
                        </div>

                      
                        <div class="col-md-6">
                            <label for="frecuencia_pago" class="form-label">Frecuencia de Pago</label>
                            <select name="id_frp" id="id_frp" class="form-control" required>
                                <option value="">Selecciona una opcion</option>
                                <?php
                                //Consulta para llamar a las categorias 
                                include "../includes/db.php";
                                $sql = "SELECT * FROM frecuencia_pago";
                                $resultado = mysqli_query($conexion, $sql);
                                while ($consulta = mysqli_fetch_array($resultado)) {
                                    echo '<option value="' . $consulta['id'] . '">' . $consulta['frecuencia'] . '</option>';
                                }
                                ?>

                            </select>
                        </div>

                             <div class="col-md-6">
                            <label for="multa_mora" class="form-label">Multa por Mora (%)</label>
                            <input type="number" class="form-control" id="multa_mora" name="multa_mora" step="0.01" placeholder="Ej. 3.0" required>
                        </div>

                              <div class="col-md-6">
                            <label for="monto_maximo" class="form-label">Monto Máximo Permitido</label>
                            <input type="number" class="form-control" id="monto_maximo" name="monto_maximo" step="0.01" placeholder="Ej. 10000.00" required>
                        </div>

                        </div>
						  <div class="col-md-12">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Breve descripción del tipo de préstamo..."></textarea>
                        </div>
                    </div>
					
					
                      

                    <!-- BLOQUE AMORTIZADO -->
                    <div id="bloque_amortizado" style="display:none;">
                        <div class="row g-3 mt-3">

                            <div class="col-md-6">
                                <label class="form-label">Nombre del tipo</label>
                                <input type="text" class="form-control" name="nombre_tipo_amort">
                            </div>

                         
                     
							   <div class="col-md-6">
                            <label for="tasa_anual_amor" class="form-label">Tasa de Interés Anual (%)</label>
                            <input type="number" class="form-control" id="tasa_anual_amor" name="tasa_anual_amor" step="0.01" placeholder="Ej. 5.5" required>
                        </div>

            
									   <div class="col-md-6">
                            <label for="tasa_mensual_amort" class="form-label">Tasa interés mensual (%)</label>
                            <input type="number" class="form-control" id="tasa_mensual_amort" name="tasa_mensual_amort" step="0.01" placeholder="Ej. 5.5" required>
                        </div>
							

                            <div class="col-md-6">
                                <label class="form-label">Plazo (meses)</label>
                                <input type="number" class="form-control" name="plazo_amort">
                            </div>

                                    <div class="col-md-6">
                            <label for="frecuencia_pago_amort" class="form-label">Frecuencia de Pago</label>
                            <select name="frecuencia_pago_amort" id="frecuencia_pago_amort" class="form-control" required>
                                <option value="">Selecciona una opcion</option>
                                <?php
                                //Consulta para llamar a las categorias 
                                include "../includes/db.php";
                                $sql = "SELECT * FROM frecuencia_pago";
                                $resultado = mysqli_query($conexion, $sql);
                                while ($consulta = mysqli_fetch_array($resultado)) {
                                    echo '<option value="' . $consulta['id'] . '">' . $consulta['frecuencia'] . '</option>';
                                }
                                ?>

                            </select>
                        </div>

                            <div class="col-md-6">
                                <label class="form-label">Multa por mora (%)</label>
                                <input type="number" class="form-control" name="multa_mora_amort">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Monto máximo</label>
                                <input type="number" class="form-control" name="monto_maximo_amort">
                            </div>
							
								  <div class="col-md-12">
                            <label for="descripcion_amort" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion_amort" name="descripcion_amort" rows="3" placeholder="Breve descripción del tipo de préstamo..."></textarea>
                        </div>

                        </div>
                    </div>

                </div>

                <input type="hidden" name="accion" value="SaveTypePrest">

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                </div>

            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#formTipoPrestamo').submit(function(e) {
            e.preventDefault();
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
                            title: 'Datos Guardados',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(function() {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Alerta',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error inesperado'
                    });
                }
            });
        });
    });


document.getElementById('tipo_proyeccion').addEventListener('change', function () {

    const tipoId = this.value; // 0 = simple | 1 = amortizado

    const bloqueSimple = document.getElementById('bloque_simple');
    const bloqueAmort = document.getElementById('bloque_amortizado');

    const simpleInputs = bloqueSimple.querySelectorAll('input, select, textarea');
    const amortInputs  = bloqueAmort.querySelectorAll('input, select, textarea');

    // ================= RESET TOTAL =================
    bloqueSimple.style.display = 'none';
    bloqueAmort.style.display = 'none';

    simpleInputs.forEach(el => {
        el.required = false;
        el.disabled = true;
    });

    amortInputs.forEach(el => {
        el.required = false;
        el.disabled = true;
    });

    // ================= SIMPLE =================
    if (tipoId === '0') {
        bloqueSimple.style.display = 'block';

        simpleInputs.forEach(el => {
            el.disabled = false;
            if (el.name !== 'periodo_gracia') { // opcional
                el.required = true;
            }
        });
    }

    // ================= AMORTIZADO =================
    if (tipoId === '1') {
        bloqueAmort.style.display = 'block';

        amortInputs.forEach(el => {
            el.disabled = false;
            el.required = true;
        });
    }
});


</script>





