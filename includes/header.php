<?php

// include "configSession.php";
// include "consultUserSession.php";


$usuario = "admin";
$user['rol'] = "Administrador";


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <title>TEST</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/lineicons.css" />

</head>


<body>

<!-- SIDEBAR -->
<aside class="sidebar-nav-wrapper">

    <div class="navbar-logo">
        <ul class="sidebar-nav">

    <li class="nav-item">

        <a href="#">

            <span class="text">
                Dashboard
            </span>

        </a>

    </li>

</ul>
    </div>

</aside>

<!-- OVERLAY -->
<div class="overlay"></div>

<!-- MAIN -->
<main class="main-wrapper">

    <!-- HEADER -->
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

                        <h5>PROFILE TEST</h5>

                    </div>

                </div>

            </div>

        </div>

    </header>

</main>
<script src="../js/jquery-3.7.1.min.js"></script>
<!-- JS -->
<script src="../js/bootstrap.bundle.min.js"></script>
<script src="../js/main.js"></script>

</body>
</html>