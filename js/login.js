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

        if (bloqueado) {
            return;
        }

        loginBtn.disabled = true;

        btnText.innerHTML =
        "Verificando...";

        let userData =
        new FormData(form);

        fetch(

            "/CrederePruebas/includes/sesion/validarAcceso.php",

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
            // LOGIN OK
            // =========================================

            if(response.response === "success"){

                window.location.replace(
                    "../../views/index.php"
                );

                return;
            }

            // =========================================
            // USUARIO BLOQUEADO
            // =========================================

            if(
                response.response ===
                "usuario_bloqueado"
            ){

                alertBox.style.display =
                "block";

                alertBox.className =
                "alert-error";

                alertBox.innerHTML =

                "Usuario bloqueado.<br>" +
                "Por favor contacte al administrador del sistema.";

                loginBtn.disabled = true;

                bloqueado = true;

                return;
            }

            // =========================================
            // ERROR LOGIN
            // =========================================

            alertBox.style.display =
            "block";

            alertBox.className =
            "alert-error";

            alertBox.innerHTML =

            "Usuario o contraseña incorrectos.<br>" +

            "Intento " +

            response.intentos +

            " de " +

            response.maxIntentos +

            ".";

            // =========================================
            // TEMPORIZADOR ÚLTIMO INTENTO
            // =========================================

            let intentosRestantes =

            response.maxIntentos -
            response.intentos;

            if(intentosRestantes === 1){

                bloquear(
                    response.tiempoBloqueo
                );
            }

        })

        .catch(error => {

            console.error(error);

            loginBtn.disabled = false;

            btnText.innerHTML =
            "Iniciar Sesión";
        });

    });

    // =========================================
    // TEMPORIZADOR
    // =========================================

    function bloquear(segundos){

        bloqueado = true;

        loginBtn.disabled = true;

        let intervalo =

        setInterval(() => {

            alertBox.style.display =
            "block";

            alertBox.className =
            "alert-error";

            alertBox.innerHTML =

            "Demasiados intentos.<br>" +

            "Intenta nuevamente en <b>" +

            segundos +

            "</b> segundos.";

            segundos--;

            if(segundos < 0){

                clearInterval(intervalo);

                bloqueado = false;

                loginBtn.disabled = false;

                alertBox.style.display =
                "none";
            }

        }, 1000);
    }

});