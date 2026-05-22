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
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/lineicons.css" />

</head>

<body>

<!-- SIDEBAR -->
<aside class="sidebar-nav-wrapper">

    <div class="navbar-logo">
        <h3>SIDEBAR OK</h3>
    </div>

</aside>

<!-- OVERLAY -->
<div class="overlay"></div>

<!-- MAIN -->
<main class="main-wrapper">

    <h1>MAIN WRAPPER OK</h1>
    <h1>HEADER OK</h1>

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

<!-- JS -->
<script src="../js/bootstrap.bundle.min.js"></script>
<script src="../js/main.js"></script>

</body>
</html>