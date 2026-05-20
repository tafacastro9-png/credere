<div class="modal fade" id="editar<?php echo $fila['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header  text-white">
                <h5 class="modal-title" id="exampleModalLabel">Editar Registro</h5>
                <button type="button" class="close btn btn-light" data-bs-dismiss="modal" aria-label="Close">
                    <span class="mdi mdi-window-close"></span>
                </button>
            </div>
            <form id="editarTipoPrestamo<?php echo $fila['id']; ?>">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nombre_tipo" class="form-label">Nombre del Tipo</label>
                            <input type="text" class="form-control" id="nombre_tipo" name="nombre_tipo" value="<?php echo $fila['nombre_tipo']; ?>" placeholder="Ej. Personal, Emprendedor" required>
                        </div>

                        <div class="col-md-6">
                            <label for="tasa_interes" class="form-label">Tasa de Interés Anual(%)</label>
                            <input type="number" class="form-control" id="tasa_interes" name="tasa_interes" step="0.01" value="<?php echo $fila['tasa_interes']; ?>" placeholder="Ej. 5.5" required>
                        </div>

                        <div class="col-md-6">
                            <label for="plazo_dias" class="form-label">Plazo (días)</label>
                            <input type="number" class="form-control" id="plazo_dias" name="plazo_dias" value="<?php echo $fila['plazo_dias']; ?>" placeholder="Ej. 30, 60, 90" required>
                        </div>

                        <div class="col-md-6">
                            <label for="frecuencia_pago" class="form-label">Frecuencia de Pago</label>
                            <select name="id_frp" id="id_frp" class="form-control" required>
                                <option <?php echo $fila['id_frp'] === 'id_frp' ? 'selected' : ''; ?> value="<?php echo $fila['id_frp']; ?>"><?php echo $fila['frecuencia']; ?></option>
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
                            <input type="number" class="form-control" id="multa_mora" name="multa_mora" step="0.01" value="<?php echo $fila['multa_mora']; ?>" placeholder="Ej. 3.0" required>
                        </div>

                        <div class="col-md-6">
                            <label for="monto_maximo" class="form-label">Monto Máximo Permitido</label>
                            <input type="number" class="form-control" id="monto_maximo" name="monto_maximo" value="<?php echo $fila['monto_maximo']; ?>" step="0.01" placeholder="Ej. 10000.00" required>
                        </div>

                        <div class="col-md-12">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Breve descripción del tipo de préstamo..."><?php echo $fila['descripcion']; ?></textarea>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="accion" value="editTypePrest">
                <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="editarTipoPrestamo(<?php echo $fila['id']; ?>)">Guardar</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
    function editarTipoPrestamo(id) {
        var datosFormulario = $("#editarTipoPrestamo" + id).serialize();

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