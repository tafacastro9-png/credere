<?php

include "configSession.php";
include "consultUserSession.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <title>TEST</title>

    <!-- CSS -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/lineicons.css">

</head>

<body>

<!-- SIDEBAR -->
<aside class="sidebar-nav-wrapper">

    <div class="navbar-logo">

        <a href="#">
            LOGO
        </a>

    </div>

    <nav class="sidebar-nav">

        <ul>

            <li class="nav-item">

                <a href="#0">

                    <span class="text">
                        Dashboard
                    </span>

                </a>

            </li>

        </ul>

    </nav>

</aside>

<!-- overlay -->
<div class="overlay"></div>

<!-- ======== main-wrapper start =========== -->
<main class="main-wrapper">

    <!-- ======== header start =========== -->
    <header class="header">

        <div class="container-fluid">

            <div class="row">

                <!-- LEFT -->
                <div class="col-lg-5 col-md-5 col-6">

                    <div class="header-left d-flex align-items-center">

                        <div class="menu-toggle-btn mr-15">

                            <button id="menu-toggle"
                                class="main-btn primary-btn btn-hover">

                                <i class="lni lni-chevron-left me-2"></i>
                                Menu

                            </button>

                        </div>

                    </div>

                </div>

                <!-- RIGHT -->
                <div class="col-lg-7 col-md-7 col-6">

                    <div class="header-right d-flex justify-content-end align-items-center">

                        <h5 class="mb-0">
                            PROFILE TEST
                        </h5>

                    </div>

                </div>

            </div>

        </div>

    </header>
    <!-- ======== header end =========== -->


    <!-- CONTENT -->
    <section class="section">

        <div class="container-fluid">

            <h1>
                Bienvenido <?php echo $usuario; ?>
            </h1>

        </div>

    </section>

</main>
<!-- ======== main-wrapper end =========== -->


<!-- JS -->
<script src="/js/jquery-3.7.1.min.js"></script>
<script src="/js/bootstrap.bundle.min.js"></script>
<script src="/js/main.js"></script>

</body>
</html>