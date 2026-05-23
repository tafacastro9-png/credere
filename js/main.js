document.addEventListener("DOMContentLoaded", function () {

    const menuToggleButton = document.querySelector("#menu-toggle");
    const sidebarNavWrapper = document.querySelector(".sidebar-nav-wrapper");
    const mainWrapper = document.querySelector(".main-wrapper");
    const overlay = document.querySelector(".overlay");

    if(menuToggleButton){

        menuToggleButton.addEventListener("click", function(){

            sidebarNavWrapper.classList.toggle("active");
            mainWrapper.classList.toggle("active");
            overlay.classList.toggle("active");

        });

    }

});