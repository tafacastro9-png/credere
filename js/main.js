document.addEventListener("DOMContentLoaded", function () {

    /* ========= PRELOADER ======== */
    const preloader = document.getElementById("preloader");

    if (preloader) {
        preloader.style.display = "none";
    }

    /* ========= HEADER SHADOW ======== */
    window.addEventListener("scroll", function () {

        const header = document.querySelector(".header");

        if (header) {
            header.style.boxShadow =
                window.scrollY > 0
                    ? "0px 0px 30px 0px rgba(200, 208, 216, 0.30)"
                    : "none";
        }

    });

    /* ========= SIDEBAR ======== */

    const sidebar = document.querySelector(".sidebar-nav-wrapper");
    const overlay = document.querySelector(".overlay");
    const menuBtn = document.getElementById("menu-toggle");

    // LIMPIAR estados rotos al iniciar
    if (overlay) {
        overlay.classList.remove("active");
        overlay.style.display = "none";
    }

    if (sidebar) {
        sidebar.classList.remove("active");
    }

    // TOGGLE MENU
    if (menuBtn && sidebar) {

        menuBtn.addEventListener("click", function () {

            sidebar.classList.toggle("active");

            if (overlay) {

                if (sidebar.classList.contains("active")) {
                    overlay.style.display = "block";
                } else {
                    overlay.style.display = "none";
                }

            }

        });

    }

    // CERRAR OVERLAY
    if (overlay && sidebar) {

        overlay.addEventListener("click", function () {

            sidebar.classList.remove("active");
            overlay.style.display = "none";

        });

    }

});