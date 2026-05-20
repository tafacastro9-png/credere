<div class="modal fade" id="impt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Importar CSV</h3>
                <button type="button" class="close btn btn-light" data-bs-dismiss="modal" aria-label="Close">
                    <span class="mdi mdi-window-close"></span>
                </button>
            </div>
            <div class="modal-body">
                <form id="importForm" action="" method="POST" enctype="multipart/form-data">


                    <div class="alert alert-warning" role="alert">
                        <p style="text-align: justify;">Le recomendamos descargar nuestro formato en <b>Excel</b> desde esta vista.
                            Asegúrese de guardar el <b>documento CSV</b> antes de subirlo.
                        </p>
                        <p style="text-align: justify;"><b>NOTA:</b> Si va agregar un nuevo cliente, verifique que no se
                            repita los siguientes campos: Folio, Documento de Identidad y Correo, ya que esto podria ocasionar problemas.</p>
                    </div>

                    <br>
                    <div class="form-group">
                        <input type="file" name="file" class="form-control">
                    </div>

                    <div class="modal-footer">
                        <button type="submit" name="importar" class="btn btn-primary">Guardar</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#importForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData();
            formData.append('file', $('input[type=file]')[0].files[0]);

            // Verificar si el archivo es de tipo CSV antes de enviarlo
            var fileType = formData.get('file').type;
            if (fileType !== 'text/csv') {
                // Mostrar una alerta personalizada usando SweetAlert2
                Swal.fire({
                    icon: 'info',
                    title: 'CSV Invalido',
                    text: 'Por favor, suba un archivo CSV.',
                });
                return; // Salir de la función si el archivo no es CSV
            }

            $.ajax({
                url: '../includes/subirCSV.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Proceso Finalizado',
                            html: response.message,
                            timer: 5000,
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
                            if (result.dismiss === Swal.DismissReason.timer) {
                                window.location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Ocurrió un error inesperado'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error inesperado'
                    });
                }
            });
        });
    });
</script>