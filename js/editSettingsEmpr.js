 $("#editDatos").click(function(e) {
        e.preventDefault();

        var datos = new FormData();
        datos.append('accion', 'editarDatosEmpresa');
        datos.append('id', $("#empresa").data("id"));
        datos.append('empresa', $("#empresa").val());
        datos.append('telefono', $("#telefono").val());
        datos.append('cp', $("#cp").val());
        datos.append('calles', $("#calles").val());
        datos.append('direccion', $("#direccion").val());
        datos.append('representante', $("#representante").val());
        datos.append('imagenEmpresa', $("#imagenEmpresa")[0].files[0]);

        fetch('../includes/functions.php', {
                method: 'POST',
                body: datos
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la solicitud: ' + response.statusText);
                }
                return response.json();
            })
            .then(response => {
                confirmation(response);
            })
            .catch(error => {
                console.error(error);

            });
    });

    function confirmation(r) {
        if (r) {
            if (r === "updated") {
                let timerInterval;
                Swal.fire({
                    title: 'Datos Guardados',
                    html: 'La informacion esta siendo guardada en la base de datos en <b></b> segundos...',
                    timer: 3000,
                    icon: 'success',
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                        const b = Swal.getHtmlContainer().querySelector('b');
                        timerInterval = setInterval(() => {
                            b.textContent = Swal.getTimerLeft();
                        }, 100);
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    }
                }).then((result) => {

                    if (result.dismiss === Swal.DismissReason.timer) {
                        console.log('I was closed by the timer');
                    }
                });
                setTimeout(function() {
                    url = "configuracionEmpresa.php";
                    $(location).attr('href', url);
                }, 3000);
            }
        }
    }