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

    <link rel="shortcut icon"
        href="../images/logo_circular.png"
        type="image/x-icon" />

    <title>Credere bank</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/lineicons.css" />
    <link rel="stylesheet" href="../css/materialdesignicons.min.css" />
    <link rel="stylesheet" href="../css/fullcalendar.css" />
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

    <link rel="stylesheet"
        href="../css/dataTables.bootstrap4.min.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
        rel="stylesheet" />

    <script src="../js/jquery-3.7.1.min.js"></script>

    <style>

        #preloader{
            display:none !important;
        }

        .header .header-right button span{
            width:20px;
            height:20px;
        }

        .logo{
            max-width:100%;
            height:auto;
            display:block;
            margin:0 auto;
        }

    </style>

</head>

<body>

<!-- SIDEBAR -->
<aside class="sidebar-nav-wrapper active">

    <div class="navbar-logo">

        <a href="../views/index.php">

            <div class="cover-image">

                <img src="../images/logo.png"
                    alt=""
                    width="200"
                    height="70">

            </div>

        </a>

    </div>

    <nav class="sidebar-nav">

        <ul>

            <?php if (isset($_SESSION['permisos']) && in_array('clientes.ver', $_SESSION['permisos'])): ?>

            <li class="nav-item nav-item-has-children">

                <a href="javascript:void(0)"
                    class="collapsed"
                    data-bs-toggle="collapse"
                    data-bs-target="#ddmenu_2">

                    <span class="icon">
                        <span class="mdi mdi-account-group"></span>
                    </span>

                    <span class="text">
                        Clientes
                    </span>

                </a>

                <ul id="ddmenu_2"
                    class="collapse dropdown-nav">

                    <li>

                        <a href="../views/clientes.php">
                            Lista Clientes
                        </a>

                    </li>

                </ul>

            </li>

            <?php endif; ?>

        </ul>

    </nav>

</aside>

<!-- overlay -->
<div class="overlay"></div>

<!-- MAIN -->
<main class="main-wrapper active">

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

                            <form onsubmit="return false;">

                                <input type="text"
                                    placeholder="Search..." />

                                <button type="button">

                                    <i class="lni lni-search-alt"></i>

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

                <!-- RIGHT -->
                <div class="col-lg-7 col-md-7 col-6">

                    <div class="header-right">

                        <!-- PROFILE -->
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
                                                alt="" />

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

                                <?php if ($_SESSION["type"] == 1 || $_SESSION["type"] == 3) { ?>

                                <li>

                                    <a href="../views/configuracionEmpresa.php">

                                        <i class="lni lni-cog"></i>
                                        Configuracion

                                    </a>

                                </li>

                                <?php } ?>

                                <li>

                                    <a href="../views/soporte.php">

                                        <span class="mdi mdi-information"></span>
                                        Soporte

                                    </a>

                                </li>

                                <li>

                                    <a href="javascript:void(0)"
                                        data-bs-toggle="modal"
                                        data-bs-target="#logoutModal">

                                        <i class="lni lni-exit"></i>
                                        Logout

                                    </a>

                                </li>

                            </ul>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </header>

        <?php include "../views/ventanaLogout.php"; ?>
    <?php include "../includes/sesion/validarInactividad.php"; ?>

</main>

<!-- JS -->
<script src="../js/bootstrap.bundle.min.js"></script>
<script src="../js/notificaciones.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="../js/jquery.dataTables.min.js"></script>
<script src="../js/dataTables.bootstrap4.min.js"></script>

<script src="../js/main.js"></script>

</body>
</html>