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

</head>

<body>

<!-- PRELOADER -->
<div id="preloader">
    <div class="spinner"></div>
</div>

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

                            Menu

                        </button>

                    </div>

                    <!-- SEARCH -->
                    <div class="header-search d-none d-md-flex">

                        <form>

                            <input type="text"
                                placeholder="Search..." />

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
                            data-bs-toggle="dropdown">

                            <div class="profile-info">

                                <div class="info d-flex align-items-center">

                                    <div class="image">

                                        <img src="<?php echo $ruta_imagen; ?>"
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

                        <ul class="dropdown-menu dropdown-menu-end">

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

<script src="../js/jquery-3.7.1.min.js"></script>
<script src="../js/bootstrap.bundle.min.js"></script>
<script src="../js/main.js"></script>