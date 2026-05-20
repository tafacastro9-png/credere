$("#editPerfil").click(function (e) {
    e.preventDefault();

    // Crear objeto FormData
    var datos = new FormData();
    datos.append('accion', 'editar_perfil');
    datos.append('id', $("#usuario").data("id"));
    datos.append('usuario', $("#usuario").val());
    datos.append('correo', $("#correo").val());
    datos.append('id_rol', $("#id_rol").val());
    datos.append('imagenPerfil', $("#imagenPerfil")[0].files[0]); // Obtener el archivo de imagen seleccionado

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
            // Mostrar mensaje de error al usuario
        });
});

function confirmation(r) {
    if (r) {
        if (r === "updated") {
            let timerInterval;
            Swal.fire({
                title: 'Guardando Cambios!',
                html: 'Por favor inicia sesión nuevamente... <b></b> cerrando sesión...',
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
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                    console.log('I was closed by the timer');
                }
            });
            setTimeout(function () {
                url = "../includes/sesion/cerrarSesion.php";
                $(location).attr('href', url);
            }, 3000);
        }
    }
}