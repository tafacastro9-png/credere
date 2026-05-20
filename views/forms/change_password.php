<div class="modal fade" id="change<?php echo $fila['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cambiar Contraseña</h5>
                <button type="button" class="close btn btn-light" data-bs-dismiss="modal" aria-label="Close">
                    <span class="mdi mdi-window-close"></span>
                </button>
            </div>
            <div class="modal-body">

                <form id="changePass<?php echo $fila['id']; ?>" method="POST">

                    <div class="form-group">
                        <label for="password">Usuario</label>
                        <input type="text" readonly class="form-control" value="<?php echo $fila['usuario']; ?>">
                    </div>

                    <?php
                    $passrand = rand(1000, 9999);
                    ?>
                    <div class="form-group">
                        <label for="password" style="text-align: justify;">Password: Es necesario generar una nueva contraseña o intruduce esta contraseña aleatoria.</label><br>
                        <input type="text" name="password2" id="password2" readonly class="form-control" value="Defaultpass<?php echo $passrand; ?>">
                    </div>

                    <div class="form-group">
                        <label for="nombre" class="form-label">Nuevo Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <br>

                    <input type="hidden" name="accion" value="change_password">
                    <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="changePass(<?php echo $fila['id']; ?>)">Guardar</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    </div>

            </div>

            </form>
        </div>
    </div>
</div>
<script>
    function changePass(id) {
        var datosFormulario = $("#changePass" + id).serialize();

        $.ajax({
            url: "../includes/functions.php",
            type: "POST",
            data: datosFormulario,
            dataType: "json",
            success: function(response) {
                if (response === "correcto") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Password Modificado',
                        html: 'El password fue modificado, los datos se estan guardando en <b></b> milliseconds.',
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
                        text: "Ha ocurrido un error al actualizar el password",
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