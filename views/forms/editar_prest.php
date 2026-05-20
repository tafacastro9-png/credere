<!-- Modal -->

<div class="modal fade" id="editar<?php echo $fila['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Datos del Prestamo</h5>
                <button type="button" class="close btn btn-light" data-bs-dismiss="modal" aria-label="Close">
                    <span class="mdi mdi-window-close"></span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editarDataPrest<?php echo $fila['id']; ?>">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">#FolioCliente</label>
                                <input type="text" id="folioClient" name="folioClient" class="form-control" disabled value="<?php echo $fila['folioClient']; ?>" required>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Cliente</label>
                                <input type="text" id="nombreClient" name="nombreClient" class="form-control" disabled value="<?php echo $fila['nombreClient'] . ' ' . $fila['apellidoClient']; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="password">Aval</label><br>
                                <input type="text" name="nombreAval" id="nombreAval" class="form-control" disabled value="<?php echo $fila['nombreAval'] . ' ' . $fila['apellidoAval']; ?>" required>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="password">Tipo de Prestamo</label><br>
                                <input type="text" name="nombre_tipo" id="nombre_tipo" class="form-control" disabled value="<?php echo $fila['nombre_tipo']; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="password">Monto Prestado $</label><br>
                                <input type="number" name="monto_prestado" id="monto_prestado" class="form-control" disabled value="<?php echo $fila['monto_prestado']; ?>" required>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="password">Fecha Inicio</label><br>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?php echo $fila['fecha_inicio']; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="password">Fecha de Vencimiento</label><br>
                                <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" class="form-control" value="<?php echo $fila['fecha_vencimiento']; ?>" required>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="password">Status del Prestamo</label><br>
                                <select name="id_estp" id="id_estp" class="form-control" required>
                                    <option <?php echo $fila['id_estp'] === 'id_estp' ? 'selected' : ''; ?> value="<?php echo $fila['id_estp']; ?>"><?php echo $fila['statusPrest']; ?></option>
                                    <?php
                                    //Consulta para llamar a las categorias 
                                    include "../includes/db.php";
                                    $sql = "SELECT * FROM estado_prestamo";
                                    $resultado = mysqli_query($conexion, $sql);
                                    while ($consulta = mysqli_fetch_array($resultado)) {
                                        echo '<option value="' . $consulta['id'] . '">' . $consulta['statusPrest'] . '</option>';
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>
                    </div>


                    <input type="hidden" name="accion" value="editDataPrest">
                    <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">

            </div>
            <br>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="editarDataPrest(<?php echo $fila['id']; ?>)">Guardar</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
            </div>
            </form>
        </div>
    </div>
</div>


<script>
    function editarDataPrest(id) {
        var datosFormulario = $("#editarDataPrest" + id).serialize();

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