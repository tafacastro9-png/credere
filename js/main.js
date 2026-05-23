document.addEventListener("DOMContentLoaded", function () {

    // PRELOADER
    const preloader = document.getElementById("preloader");

    if (preloader) {
        preloader.style.display = "none";
    }

    // SIDEBAR
    const sidebarNavWrapper = document.querySelector(".sidebar-nav-wrapper");
    const mainWrapper = document.querySelector(".main-wrapper");
    const menuToggleButton = document.querySelector("#menu-toggle");
    const overlay = document.querySelector(".overlay");

    // BOTÓN MENU
    if (menuToggleButton) {

        menuToggleButton.addEventListener("click", function () {

            sidebarNavWrapper.classList.toggle("active");
            mainWrapper.classList.toggle("active");

            if (overlay) {
                overlay.classList.toggle("active");
            }

        });

    }

    // OVERLAY
    if (overlay) {

        overlay.addEventListener("click", function () {

            sidebarNavWrapper.classList.remove("active");
            mainWrapper.classList.remove("active");
            overlay.classList.remove("active");

        });

    }

});