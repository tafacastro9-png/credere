document.addEventListener("DOMContentLoaded", function () {

    /* ========= PRELOADER ========= */
    const preloader = document.getElementById("preloader");

    if (preloader) {
        preloader.style.display = "none";
    }

    /* ========= HEADER SHADOW ========= */
    window.addEventListener("scroll", function () {

        const header = document.querySelector(".header");

        if (header) {

            header.style.boxShadow = window.scrollY > 0
                ? "0px 0px 30px 0px rgba(200, 208, 216, 0.30)"
                : "none";

        }

    });

    /* ========= SIDEBAR TOGGLE ========= */
    const sidebarNavWrapper = document.querySelector(".sidebar-nav-wrapper");
    const mainWrapper = document.querySelector(".main-wrapper");
    const menuToggleButton = document.querySelector("#menu-toggle");
    const menuToggleButtonIcon = document.querySelector("#menu-toggle i");
    const overlay = document.querySelector(".overlay");

    if (menuToggleButton && sidebarNavWrapper && mainWrapper && overlay) {

        menuToggleButton.addEventListener("click", () => {

            sidebarNavWrapper.classList.toggle("active");
            overlay.classList.toggle("active");
            mainWrapper.classList.toggle("active");

            if (menuToggleButtonIcon) {

                if (menuToggleButtonIcon.classList.contains("lni-chevron-left")) {

                    menuToggleButtonIcon.classList.remove("lni-chevron-left");
                    menuToggleButtonIcon.classList.add("lni-menu");

                } else {

                    menuToggleButtonIcon.classList.remove("lni-menu");
                    menuToggleButtonIcon.classList.add("lni-chevron-left");

                }

            }

        });

    }

    /* ========= OVERLAY CLICK ========= */
    if (overlay && sidebarNavWrapper && mainWrapper) {

        overlay.addEventListener("click", () => {

            sidebarNavWrapper.classList.remove("active");
            overlay.classList.remove("active");
            mainWrapper.classList.remove("active");

        });

    }

});

/* ========= SUBMENUS MANUALES ========= */

document.querySelectorAll(".nav-item-has-children > a")
.forEach(function(menu){

    menu.addEventListener("click", function(e){

        e.preventDefault();

        const submenu = this.parentElement.querySelector(".dropdown-nav");

        if(submenu){

            if(submenu.style.display === "block"){

                submenu.style.display = "none";

            }else{

                submenu.style.display = "block";

            }

        }

    });

});