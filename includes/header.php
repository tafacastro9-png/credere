<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
    <link rel="stylesheet" href="../css/dataTables.bootstrap4.min.css">

    <!-- Fontawesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
          rel="stylesheet" />

    <!-- JQuery -->
    <script src="../js/jquery-3.7.1.min.js"></script>

    <style>

        .header .header-right button span {

            width: 20px;
            height: 20px;

        }

        .logo {

            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;

        }

    </style>

</head>

<body>

<!-- ======== PRELOADER ======== -->

<!--
<div id="preloader">
    <div class="spinner"></div>
</div>
-->

<!-- ======== SIDEBAR ======== -->

<aside class="sidebar-nav-wrapper">

    <div class="navbar-logo">

        <a href="../views/index.php">

            <div class="cover-image">

                <img src="/images/logo.png"
                     alt=""
                     width="200"
                     height="70">

            </div>

        </a>

    </div>

<nav class="sidebar-nav">

<ul>

<!-- CLIENTES -->
<?php if (isset($_SESSION['permisos']) && in_array('clientes.ver', $_SESSION['permisos'])): ?>

<li class="nav-item nav-item-has-children">

<a href="javascript:void(0)"
   class="collapsed"
   data-bs-toggle="collapse"
   data-bs-target="#ddmenu_2">

    <span class="icon">
        <span class="mdi mdi-account-group"></span>
    </span>

    <span class="text">Clientes</span>

</a>

<ul id="ddmenu_2" class="collapse dropdown-nav">

    <li>
        <a href="../views/clientes.php">
            Lista Clientes
        </a>
    </li>

</ul>

</li>

<span class="divider">
    <hr />
</span>

<?php endif; ?>


<!-- REFERENCIAS -->
<?php if (isset($_SESSION['permisos']) && in_array('referencias.ver', $_SESSION['permisos'])): ?>

<li class="nav-item nav-item-has-children">

<a href="javascript:void(0)"
   class="collapsed"
   data-bs-toggle="collapse"
   data-bs-target="#ddmenu_21">

    <span class="icon">
        <span class="mdi mdi-account-supervisor-circle"></span>
    </span>

    <span class="text">Referencias</span>

</a>

<ul id="ddmenu_21" class="collapse dropdown-nav">

    <li>
        <a href="../views/avales.php">
            Lista de Referencias
        </a>
    </li>

</ul>

</li>

<span class="divider">
    <hr />
</span>

<?php endif; ?>


<!-- SIMULADOR -->
<?php if (isset($_SESSION['permisos']) && in_array('simulador.ver', $_SESSION['permisos'])): ?>

<li class="nav-item nav-item-has-children">

<a href="javascript:void(0)"
   class="collapsed"
   data-bs-toggle="collapse"
   data-bs-target="#ddmenu_4">

    <span class="icon">
        <span class="mdi mdi-calculator-variant-outline"></span>
    </span>

    <span class="text">Simulador</span>

</a>

<ul id="ddmenu_4" class="collapse dropdown-nav">

    <li>
        <a href="../views/simulador.php">
            Simulador de Crédito
        </a>
    </li>

</ul>

</li>

<span class="divider">
    <hr />
</span>

<?php endif; ?>


<!-- CREDITOS -->
<?php if (
    in_array('prestamos.registro', $_SESSION['permisos']) ||
    in_array('prestamos.ver', $_SESSION['permisos'])
): ?>

<li class="nav-item nav-item-has-children">

<a href="javascript:void(0)"
   class="collapsed"
   data-bs-toggle="collapse"
   data-bs-target="#ddmenu_55">

    <span class="icon">
        <span class="mdi mdi-cash-multiple"></span>
    </span>

    <span class="text">Créditos</span>

</a>

<ul id="ddmenu_55" class="collapse dropdown-nav">

<?php if (in_array('prestamos.registro', $_SESSION['permisos'])): ?>

<li>
    <a href="../views/form_prestamo.php">
        Registro de Créditos
    </a>
</li>

<?php endif; ?>

<?php if (in_array('prestamos.ver', $_SESSION['permisos'])): ?>

<li>
    <a href="../views/prestamos.php">
        Lista de Créditos
    </a>
</li>

<?php endif; ?>

</ul>

</li>

<span class="divider">
<hr />
</span>

<?php endif; ?>


<!-- CARTERA -->
<?php if (isset($_SESSION['permisos']) && in_array('cartera.ver', $_SESSION['permisos'])): ?>

<li class="nav-item nav-item-has-children">

<a href="javascript:void(0)"
   class="collapsed"
   data-bs-toggle="collapse"
   data-bs-target="#ddmenu_5">

    <span class="icon">
        <span class="mdi mdi-wallet-outline"></span>
    </span>

    <span class="text">Cartera</span>

</a>

<ul id="ddmenu_5" class="collapse dropdown-nav">

<li>
    <a href="../views/registrarPago.php">
        Registrar Pago
    </a>
</li>

<?php if (isset($_SESSION['permisos']) && in_array('gestioncartera.ver', $_SESSION['permisos'])): ?>

<li>
    <a href="../views/gestionCartera.php">
        Gestión de Cartera
    </a>
</li>

<?php endif; ?>

</ul>

</li>

<span class="divider">
<hr />
</span>

<?php endif; ?>

</ul>

</nav>

</aside>

<div class="overlay"></div>

<!-- ======== MAIN ======== -->

<main class="main-wrapper">

<!-- ======== HEADER ======== -->

<header class="header">

<div class="container-fluid">

<div class="row">

<div class="col-lg-5 col-md-5 col-6">

<div class="header-left d-flex align-items-center">

<div class="menu-toggle-btn mr-15">

<button id="menu-toggle"
        class="main-btn primary-btn btn-hover">

<i class="lni lni-chevron-left me-2"></i>
Menu

</button>

</div>

<div class="header-search d-none d-md-flex">

<form action="#">

<input type="text" placeholder="Search..." />

<button>
<i class="lni lni-search-alt"></i>
</button>

</form>

</div>

</div>

</div>

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

<img src="<?php echo $ruta_imagen; ?>" alt="" />

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

</div>

</div>

</div>

</div>

</div>

</header>

<?php include "../views/ventanaLogout.php"; ?>
<?php include "../includes/sesion/validarInactividad.php"; ?>