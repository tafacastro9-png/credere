<?php

include "configSession.php";
include "consultUserSession.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <title>TEST</title>

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

    <header class="header">

        <div class="container-fluid">

            <div class="row">

                <div class="col-lg-6 col-md-6 col-6">

                    <div class="header-left d-flex align-items-center">

                        <!-- BOTON MENU -->
                        <div class="menu-toggle-btn mr-15">

                            <button id="menu-toggle"
                                class="main-btn primary-btn btn-hover">

                                <i class="lni lni-chevron-left me-2"></i>
                                Menu

                            </button>

                        </div>

                    </div>

                </div>

                <div class="col-lg-6 col-md-6 col-6">

                    <div class="header-right d-flex justify-content-end align-items-center">

                        <!-- PERFIL -->
                        <div class="profile-box ml-15">

                            <button class="dropdown-toggle bg-transparent border-0"
                                type="button"
                                id="profile"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">

                                <div class="profile-info">

                                    <div class="info d-flex align-items-center">

                                        <div class="image">

                                            <img src="/images/user.png" alt="" width="40">

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

                            <ul class="dropdown-menu dropdown-menu-end"
                                aria-labelledby="profile">

                                <li>

                                    <a class="dropdown-item"
                                        href="../views/perfilUser.php">

                                        Perfil

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