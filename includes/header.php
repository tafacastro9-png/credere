<?php
include "configSession.php";
include "consultUserSession.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Credere bank</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/lineicons.css" />
    <link rel="stylesheet" href="../css/materialdesignicons.min.css" />
    <link rel="stylesheet" href="../css/fullcalendar.css" />
    <link rel="stylesheet" href="../css/main.css" />

    <script src="../js/jquery-3.7.1.min.js"></script>

    <style>

        #preloader{
            display:none !important;
        }

    </style>

</head>

<body>

<!-- ======== sidebar-nav start =========== -->
<aside class="sidebar-nav-wrapper active">

    <div class="navbar-logo">

        <a href="../views/index.php">

            <img src="../images/logo.png"
                alt=""
                width="180">

        </a>

    </div>

    <nav class="sidebar-nav">

        <ul>

            <li class="nav-item">

                <a href="../views/index.php">

                    <span class="icon">
                        <span class="mdi mdi-view-dashboard"></span>
                    </span>

                    <span class="text">
                        Dashboard
                    </span>

                </a>

            </li>

            <li class="nav-item nav-item-has-children">

                <a href="javascript:void(0)"
                    class="collapsed"
                    data-bs-toggle="collapse"
                    data-bs-target="#ddmenu_1">

                    <span class="icon">
                        <span class="mdi mdi-account-group"></span>
                    </span>

                    <span class="text">
                        Clientes
                    </span>

                </a>

                <ul id="ddmenu_1"
                    class="collapse dropdown-nav">

                    <li>

                        <a href="../views/clientes.php">

                            Lista Clientes

                        </a>

                    </li>

                </ul>

            </li>

        </ul>

    </nav>

</aside>
<!-- ======== sidebar-nav end =========== -->


<!-- overlay -->
<div class="overlay"></div>


<!-- ======== main-wrapper start =========== -->
<main class="main-wrapper active">

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

                    <div class="header-right">

                        <div class="profile-box ml-15">

                            <button class="dropdown-toggle bg-transparent border-0"
                                type="button"
                                id="profile"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">

                                <div class="profile-info">

                                    <div class="info">

                                        <div class="image">

                                            <img src="<?php echo $ruta_imagen; ?>"
                                                alt=""
                                                width="40"
                                                style="border-radius:50%;">

                                        </div>

                                        <div>

                                            <h6 class="fw-500">
                                                <?php echo $usuario; ?>
                                            </h6>

                                            <p>
                                                <?php echo $user['rol']; ?>
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            </button>

                            <ul class="dropdown-menu dropdown-menu-end"
                                aria-labelledby="profile">

                                <li>

                                    <a href="../views/perfilUser.php">

                                        <i class="lni lni-user"></i>

                                        Perfil

                                    </a>

                                </li>

                                <li>

                                    <a href="../views/configuracionEmpresa.php">

                                        <i class="lni lni-cog"></i>

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
    <!-- ======== header end =========== -->


    <!-- ======== content start =========== -->
    <section class="section">

        <div class="container-fluid">

            <div class="row">

                <div class="col-12">

                    <div class="card-style mb-30">

                        <h2>

                            Bienvenido
                            <?php echo $usuario; ?>

                        </h2>

                        <p>

                            Header funcionando correctamente.

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>
    <!-- ======== content end =========== -->


    <?php include "../views/ventanaLogout.php"; ?>
    <?php include "../includes/sesion/validarInactividad.php"; ?>

</main>
<!-- ======== main-wrapper end =========== -->


<!-- JS -->
<script src="../js/bootstrap.bundle.min.js"></script>
<script src="../js/main.js"></script>

</body>
</html>