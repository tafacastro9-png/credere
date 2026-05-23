document.addEventListener("DOMContentLoaded", function () {

    console.log("MAIN OK");

    document.querySelectorAll(".nav-item-has-children > a")
    .forEach(function(menu){

        menu.addEventListener("click", function(e){

            e.preventDefault();

            const parent = this.parentElement;
            const submenu = parent.querySelector(".dropdown-nav");

            if(submenu){

                submenu.classList.toggle("show");

            }

        });

    });

});