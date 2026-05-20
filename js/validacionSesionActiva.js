var url = "../includes/sesion/cerrarSesion.php";

document.addEventListener("DOMContentLoaded", function () {

    let title =
    "Debe iniciar sesión nuevamente";

    if(document.getElementById('denegado')){

        title =
        "No tienes permiso para acceder aquí";
    }

    Swal.fire({

        icon: 'warning',

        title: title,

        html: `
            Por seguridad del sistema
            <br><br>
            Serás redireccionado al login
        `,

        timer: 3000,

        timerProgressBar: true,

        allowOutsideClick:false,

        showConfirmButton:false

    });

    setTimeout(function(){

        window.location.href = url;

    },3000);

});