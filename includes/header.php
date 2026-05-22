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

                        <!-- MENU -->
                        <div class="menu-toggle-btn mr-15">

                            <button id="menu-toggle"
                                class="main-btn primary-btn btn-hover">

                                <i class="lni lni-chevron-left me-2"></i>
                                Menu

                            </button>

                        </div>

                        <!-- SEARCH -->
                        <div class="header-search d-none d-md-flex">

                            <form action="#">

                                <input type="text"
                                    placeholder="Search..." />

                                <button type="submit">
                                    <i class="lni lni-search-alt"></i>
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

                <!-- RIGHT -->
                <div class="col-lg-7 col-md-7 col-6">

                    <div class="header-right d-flex justify-content-end align-items-center">

                        <!-- PROFILE -->
                        <div class="profile-box ml-15">

                            <button class="dropdown-toggle bg-transparent border-0"
                                type="button"
                                id="profile"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">

                                <div class="profile-info">

                                    <div class="info d-flex align-items-center">

                                        <div class="image">

                                            <img src="/images/user.png"
                                                alt=""
                                                width="40"
                                                style="border-radius:50%;">

                                        </div>

                                        <div class="ms-2">

                                            <h6 class="fw-500 mb-0">
                                                <?php echo $usuario; ?>
                                            </h6>

                                            <p class="mb-0">
                                                <?php echo $user['rol']; ?>
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            </button>

                            <!-- DROPDOWN -->
                            <ul class="dropdown-menu dropdown-menu-end"
                                aria-labelledby="profile">

                                <li>

                                    <a class="dropdown-item"
                                        href="../views/perfilUser.php">

                                        Perfil

                                    </a>

                                </li>

                                <li>

                                    <a class="dropdown-item"
                                        href="../views/configuracionEmpresa.php">

                                        Configuración

                                    </a>

                                </li>

                            </ul>

                        </div>

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