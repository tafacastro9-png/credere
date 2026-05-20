<!-- Modal -->

<div class="modal fade" id="editar<?php echo $fila['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Registro</h5>
                <button type="button" class="close btn btn-light" data-bs-dismiss="modal" aria-label="Close">
                    <span class="mdi mdi-window-close"></span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editarAval<?php echo $fila['id']; ?>">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">#Consecutivo Referencia</label>
                                <input type="text" id="folioAval" name="folioAval" class="form-control" disabled value="<?php echo $fila['folioAval']; ?>" required>
                            </div>
                        </div>
						
						<div class="col-sm-6">
                            <div class="mb-3">
                                <label for="password">Tipo de Referencia</label><br>
                               	<select name="id_tiporeferencia"
                                    id="id_tiporeferencia<?php echo $fila['id']; ?>"
                                    class="form-control" required>

                                <option value="">Seleccione tipo</option>

                                <?php
                                include "../includes/db.php";
                                $sql = "SELECT * FROM tipo_referencia";
                                $resultado = mysqli_query($conexion, $sql);

                                while ($consulta = mysqli_fetch_assoc($resultado)) {
                                    $selected = ($consulta['id'] == $fila['id_tiporeferencia']) ? 'selected' : '';
                                    echo '<option value="'.$consulta['id'].'" '.$selected.'>'.$consulta['nombre'].'</option>';
                                }
                                ?>
                            </select>
                            </div>
                        </div>
						</div>

 <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombres</label>
                                <input type="text" id="nombreAval" name="nombreAval" class="form-control" value="<?php echo $fila['nombreAval']; ?>" required>
                            </div>
                        </div>
                  

                 
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="password">Apellidos</label><br>
                                <input type="text" name="apellidoAval" id="apellidoAval" class="form-control" value="<?php echo $fila['apellidoAval']; ?>" required>
                            </div>
                        </div>
						  </div>
						<div class="row">
					      <div class="col-sm-6 mb-3">
                            <label>Tipo Identificación</label>
                            <select name="id_tipoidentificacion"
                                    id="id_tipoidentificacion<?php echo $fila['id']; ?>"
                                    class="form-control" required>

                                <option value="">Seleccione tipo</option>

                                <?php
                                include "../includes/db.php";
                                $sql = "SELECT * FROM tipo_identificacion";
                                $resultado = mysqli_query($conexion, $sql);

                                while ($consulta = mysqli_fetch_assoc($resultado)) {
                                    $selected = ($consulta['id'] == $fila['id_tipoidentificacion']) ? 'selected' : '';
                                    echo '<option value="'.$consulta['id'].'" '.$selected.'>'.$consulta['nombre'].'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="password">#Documento de Identidad</label><br>
                                <input type="text" name="docIdentAval" id="docIdentAval" class="form-control" value="<?php echo $fila['docIdentAval']; ?>" placeholder="Ingrese el número de su CURP, DNI o Pasaporte" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="password">Telefono</label><br>
                                <input type="tel" name="telAval" id="telAval" class="form-control" value="<?php echo $fila['telAval']; ?>" required>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="password">Correo</label><br>
                                <input type="email" name="correoAval" id="correoAval" class="form-control" value="<?php echo $fila['correoAval']; ?>" placeholder="example@genotipo.com" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="password">Direccion</label><br>
                                <input type="text" name="dirAval" id="dirAval" class="form-control" value="<?php echo $fila['dirAval']; ?>" required>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="password">Status</label><br>
                                <select name="id_status" id="id_status" class="form-control" required>
                                    <option <?php echo $fila['id_status'] === 'id_status' ? 'selected' : ''; ?> value="<?php echo $fila['id_status']; ?>"><?php echo $fila['estado']; ?></option>
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


                    <input type="hidden" name="accion" value="editAval">
                    <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">

            </div>
            <br>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="editarAval(<?php echo $fila['id']; ?>)">Guardar</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
            </div>
            </form>
        </div>
    </div>
</div>


<script>
    function editarAval(id) {
        var datosFormulario = $("#editarAval" + id).serialize();

        $.ajax({
            url: '../includes/functions.php',
            type: "POST",
            data: datosFormulario,
            dataType: "json",
            success: function(response) {
                if (response === "correcto") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Datos Modificados',
                        html: 'El registro se ha actualizado correctamente, los datos se estan guardando en <b></b> milliseconds.',
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                            const b = Swal.getHtmlContainer().querySelector('b')
                            timerInterval = setInterval(() => {
                                b.textContent = Swal.getTimerLeft()
                            }, 100)
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                    }).then((result) => {
                        /* Read more about handling dismissals below */
                        if (result.dismiss === Swal.DismissReason.timer) {
                            window.location.reload();
                        }
                    })
                } else {
                    Swal.fire({
                        title: "Error",
                        text: "Ha ocurrido un error al actualizar el registro",
                        icon: "error"
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: "Error",
                    text: "Ha ocurrido un error al comunicarse con el servidor",
                    icon: "error"
                });
            }
        });
    }
</script>