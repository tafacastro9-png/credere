document.addEventListener("DOMContentLoaded", function () {

    let bloqueado = false;

    const form =
    document.getElementById("loginForm");

    const loginBtn =
    document.getElementById("loginBtn");

    const btnText =
    document.getElementById("btnText");

    const alertBox =
    document.getElementById("alert");

    // =========================================
    // LOGIN
    // =========================================

    form.addEventListener("submit", function (e) {

        e.preventDefault();

if(bloqueado){
    return;
}

        loginBtn.disabled = true;

        btnText.innerHTML =
        "Verificando...";

        let userData =
        new FormData(form);

        fetch(
            "/CredereProduccion/includes/sesion/validarAcceso.php",
            {
                method: "POST",
                body: userData
            }
        )

        .then(res => res.json())

        .then(response => {

            console.log(response);

            loginBtn.disabled = false;

            btnText.innerHTML =
            "Iniciar Sesión";

            // =========================================
            // LOGIN EXITOSO
            // =========================================

            if (response.response === "success") {

                window.location.replace(
                    "../../views/index.php"
                );

                return;
            }

            // =========================================
            // USUARIO BLOQUEADO DEFINITIVO
            // =========================================

          if(response.response === "usuario_bloqueado"){

 bloqueado = false;

    loginBtn.disabled = false;

                alertBox.style.display =
                "block";

                alertBox.className =
                "alert-error";

                alertBox.innerHTML =

                    "<b>Usuario bloqueado</b><br><br>" +

                    "Por favor contacte al " +

                    "administrador del sistema.";

                return;
            }

            // =========================================
            // ERROR LOGIN + BLOQUEO TEMPORAL
            // =========================================

            bloquear(

                response.tiempoBloqueo,

                response.intentos,

                response.maxIntentos

            );

        })

        .catch(error => {

            console.error(error);

            loginBtn.disabled = false;

            btnText.innerHTML =
            "Iniciar Sesión";

            alertBox.style.display =
            "block";

            alertBox.className =
            "alert-error";

            alertBox.innerHTML =
            "Error al procesar la solicitud.";
        });

    });

    // =========================================
    // BLOQUEAR TEMPORALMENTE
    // =========================================

    function bloquear(

        segundos,

        intentos,

        maxIntentos

    ) {

        bloqueado = true;

        loginBtn.disabled = true;

        let tiempoRestante =
        segundos;

        // =========================================
        // MOSTRAR INMEDIATAMENTE
        // =========================================

        mostrarMensaje();

        let intervalo =
        setInterval(() => {

            tiempoRestante--;

            if (tiempoRestante < 0) {

                clearInterval(intervalo);

                bloqueado = false;

                loginBtn.disabled = false;

                alertBox.style.display =
                "none";

                return;
            }

            mostrarMensaje();

        }, 1000);

        // =========================================
        // FUNCIÓN MENSAJE
        // =========================================

        function mostrarMensaje(){

            alertBox.style.display =
            "block";

            alertBox.className =
            "alert-error";

            alertBox.innerHTML =

                "Usuario o contraseña incorrectos.<br>" +

                "Intento " +

                intentos +

                " de " +

                maxIntentos +

                ".<br><br>" +

                "Intenta nuevamente en <b>" +

                tiempoRestante +

                "</b> segundos.";
        }
    }

});