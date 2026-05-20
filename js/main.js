(function () {

  /* ========= Preloader ======== */
  const preloader = document.querySelectorAll('#preloader');

  window.addEventListener('load', function () {
    if (preloader.length) {
      document.getElementById('preloader').style.display = 'none';
    }
  });

  /* ========= Add Box Shadow in Header on Scroll ======== */
  window.addEventListener('scroll', function () {
    const header = document.querySelector('.header');
    if (header) {
      header.style.boxShadow = window.scrollY > 0
        ? '0px 0px 30px 0px rgba(200, 208, 216, 0.30)'
        : 'none';
    }
  });

  /* ========= sidebar toggle ======== */
  const sidebarNavWrapper = document.querySelector(".sidebar-nav-wrapper");
  const mainWrapper = document.querySelector(".main-wrapper");
  const menuToggleButton = document.querySelector("#menu-toggle");
  const menuToggleButtonIcon = document.querySelector("#menu-toggle i");
  const overlay = document.querySelector(".overlay");

  if (menuToggleButton && sidebarNavWrapper && mainWrapper && overlay) {
    menuToggleButton.addEventListener("click", () => {
      sidebarNavWrapper.classList.toggle("active");
      overlay.classList.add("active");
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

  if (overlay && sidebarNavWrapper && mainWrapper) {
    overlay.addEventListener("click", () => {
      sidebarNavWrapper.classList.remove("active");
      overlay.classList.remove("active");
      mainWrapper.classList.remove("active");
    });
  }

})();