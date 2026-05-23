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
  const menuToggleButtonIcon = document.querySelector("#menu-toggle i");
  const overlay = document.querySelector(".overlay");

  if (menuToggleButton) {

    menuToggleButton.addEventListener("click", function () {

      sidebarNavWrapper.classList.toggle("active");
      mainWrapper.classList.toggle("active");
      overlay.classList.toggle("active");

      if (menuToggleButtonIcon.classList.contains("lni-chevron-left")) {

        menuToggleButtonIcon.classList.remove("lni-chevron-left");
        menuToggleButtonIcon.classList.add("lni-menu");

      } else {

        menuToggleButtonIcon.classList.remove("lni-menu");
        menuToggleButtonIcon.classList.add("lni-chevron-left");
      }

    });

  }

  if (overlay) {

    overlay.addEventListener("click", function () {

      sidebarNavWrapper.classList.remove("active");
      mainWrapper.classList.remove("active");
      overlay.classList.remove("active");

    });

  }

});