document.addEventListener("DOMContentLoaded", function () {

    alert("MAIN FUNCIONANDO");

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

    /* ========= SUBMENUS ========= */

    const menus = document.querySelectorAll(".nav-item-has-children > a");

    menus.forEach(function(menu){

        menu.addEventListener("click", function(e){

            e.preventDefault();

            const submenu = this.parentElement.querySelector(".dropdown-nav");

            if(submenu){

                if(submenu.style.display === "block"){

                    submenu.style.display = "none";

                } else {

                    submenu.style.display = "block";

                }

            }

        });

    });

});