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
    <link rel="shortcut icon" href="../images/logo_circular.png" style="border-radius: 50px;" type="image/x-icon" />
    <title>Credere bank</title>

    <!-- ========== All CSS files linkup ========= -->
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/lineicons.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../css/materialdesignicons.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../css/fullcalendar.css" />
    <link rel="stylesheet" href="../css/fullcalendar.css" />
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="../css/dataTables.bootstrap4.min.css">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="../js/jquery-3.7.1.min.js"></script>
</head>
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

<body>
    <!-- ======== Preloader =========== -->
     <style>
#preloader{
    display:none !important;
}
</style>
    <!-- ======== Preloader =========== -->

    <!-- ======== sidebar-nav start =========== -->
    <aside class="sidebar-nav-wrapper">
        <div class="navbar-logo">
            <a href="../views/index.php">
                  <div class="cover-image">
                            <img src="/images/logo.png" alt="" width="200" height="70">
                        </div>
            </a>
        </div>

<nav class="sidebar-nav">
    <ul>

        <?php if (isset($_SESSION['permisos']) && in_array('clientes.ver', $_SESSION['permisos'])): ?>
        <li class="nav-item nav-item-has-children">
            <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#ddmenu_2">
                <span class="icon">
                    <span class="mdi mdi-account-group"></span>
                </span>
                <span class="text">Clientes</span>
            </a>

            <ul id="ddmenu_2" class="collapse dropdown-nav">
                <li>
                    <a href="../views/clientes.php"> Lista Clientes </a>
                </li>
            </ul>
        </li>

        <span class="divider">
            <hr />
        </span>
        <?php endif; ?>


        <?php if (isset($_SESSION['permisos']) && in_array('referencias.ver', $_SESSION['permisos'])): ?>
        <li class="nav-item nav-item-has-children">
            <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#ddmenu_21">
                <span class="icon">
                    <span class="mdi mdi-account-supervisor-circle"></span>
                </span>
                <span class="text">Referencias</span>
            </a>

            <ul id="ddmenu_21" class="collapse dropdown-nav">
                <li>
                    <a href="../views/avales.php"> Lista de Referencias </a>
                </li>
            </ul>
        </li>

        <span class="divider">
            <hr />
        </span>
        <?php endif; ?>


        <?php if (isset($_SESSION['permisos']) && in_array('simulador.ver', $_SESSION['permisos'])): ?>
        <li class="nav-item nav-item-has-children">
            <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#ddmenu_4">
                <span class="icon">
                    <span class="mdi mdi-calculator-variant-outline"></span>
                </span>
                <span class="text">Simulador</span>
            </a>

            <ul id="ddmenu_4" class="collapse dropdown-nav">
                <li>
                    <a href="../views/simulador.php"> Simulador de Crédito </a>
                </li>
            </ul>
        </li>

        <span class="divider">
            <hr />
        </span>
        <?php endif; ?>


<?php if (
    in_array('prestamos.registro', $_SESSION['permisos']) ||
    in_array('prestamos.ver', $_SESSION['permisos'])
): ?>

<li class="nav-item nav-item-has-children">
    <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#ddmenu_55">
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


<?php if (isset($_SESSION['permisos']) && in_array('cartera.ver', $_SESSION['permisos'])): ?>
<li class="nav-item nav-item-has-children">

    <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#ddmenu_5">
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


        <?php if (isset($_SESSION['permisos']) && in_array('inversionistas.ver', $_SESSION['permisos'])): ?>
        <li class="nav-item nav-item-has-children">
            <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#ddmenu_6">
                <span class="icon">
                    <span class="mdi mdi-finance"></span>
                </span>
                <span class="text">Inversionistas</span>
            </a>

            <ul id="ddmenu_6" class="collapse dropdown-nav">
                <li>
                    <a href="../views/inversionistas.php"> Registrar Inversion </a>
                </li>
            </ul>
        </li>

        <span class="divider">
            <hr />
        </span>
        <?php endif; ?>


        <?php if (isset($_SESSION['permisos']) && in_array('caja.ver', $_SESSION['permisos'])): ?>
        <li class="nav-item">
            <a href="../views/caja.php">
                <span class="icon">
                   <span class="mdi mdi-cash-register"></span>
                </span>

                <span class="text">Caja</span>
            </a>
        </li>

        <span class="divider">
            <hr />
        </span>
        <?php endif; ?>


<?php if (isset($_SESSION['permisos']) && in_array('reportes.ver', $_SESSION['permisos'])): ?>

<li class="nav-item nav-item-has-children">
    <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#ddmenu_555">
        <span class="icon">
          <span class="mdi mdi-file-chart-outline"></span>
        </span>
        <span class="text">Reportes</span>
    </a>

    <ul id="ddmenu_555" class="collapse dropdown-nav">

        <?php if (in_array('reportes.cartera', $_SESSION['permisos'])): ?>
        <li>
            <a href="../views/reportesCartera.php">
                Reportes Cartera
            </a>
        </li>
        <?php endif; ?>


        <?php if (in_array('reportes.comisiones', $_SESSION['permisos'])): ?>
        <li>
            <a href="../views/reportesComisiones.php">
                Reportes Comisiones
            </a>
        </li>
        <?php endif; ?>


        <?php if (in_array('reportes.inversionistas', $_SESSION['permisos'])): ?>
        <li>
            <a href="../views/reportesInversiones.php">
                Reportes Inversionistas
            </a>
        </li>
        <?php endif; ?>


        <?php if (in_array('reportes.contables', $_SESSION['permisos'])): ?>
        <li>
            <a href="../views/reportesContables.php">
                Reportes Contables
            </a>
        </li>
        <?php endif; ?>

    </ul>
</li>

<span class="divider">
    <hr />
</span>

<?php endif; ?>


        <?php if (isset($_SESSION['permisos']) && in_array('estadisticas.ver', $_SESSION['permisos'])): ?>

<li class="nav-item nav-item-has-children">
    <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#ddmenu_85">
        <span class="icon">
           <span class="mdi mdi-chart-line"></span>
        </span>
        <span class="text">Estadisticas</span>
    </a>

    <ul id="ddmenu_85" class="collapse dropdown-nav">

        <?php if (in_array('estadisticas.cartera', $_SESSION['permisos'])): ?>
        <li>
            <a href="../views/dashboardCartera.php">
                Dashboard Cartera
            </a>
        </li>
        <?php endif; ?>


        <?php if (in_array('estadisticas.inversionistas', $_SESSION['permisos'])): ?>
        <li>
            <a href="../views/dashboard_inversionistas.php">
                Dashboard Inversionistas
            </a>
        </li>
        <?php endif; ?>


        <?php if (in_array('estadisticas.contabilidad', $_SESSION['permisos'])): ?>
        <li>
            <a href="../ajax/dashboardContable.php">
                Dashboard Contabilidad
            </a>
        </li>
        <?php endif; ?>

    </ul>
</li>

<span class="divider">
    <hr />
</span>

<?php endif; ?>


        <?php if (isset($_SESSION['permisos']) && in_array('parametros.ver', $_SESSION['permisos'])): ?>
        <li class="nav-item">
            <a href="../views/parametrizacion.php">
                <span class="icon">
                    <span class="mdi mdi-cog-outline"></span>
                </span>

                <span class="text">Parametros</span>
            </a>
        </li>

        <span class="divider">
            <hr />
        </span>
        <?php endif; ?>


        <?php if (isset($_SESSION['permisos']) && in_array('usuarios.ver', $_SESSION['permisos'])): ?>
        <li class="nav-item">
            <a href="../views/usuarios.php">
                <span class="icon">
                   <span class="mdi mdi-account-multiple"></span>
                </span>

                <span class="text">Usuarios</span>
            </a>
        </li>

        <span class="divider">
            <hr />
        </span>
        <?php endif; ?>


        <?php if (isset($_SESSION['permisos']) && in_array('notificaciones.ver', $_SESSION['permisos'])): ?>
        <li class="nav-item">
            <a href="../views/notificaciones.php">
                <span class="icon">
                   <span class="mdi mdi-bell-outline"></span>
                </span>

                <span class="text">Notificaciones</span>
            </a>
        </li>
        <?php endif; ?>

    </ul>
</nav>

    </aside>
    <div class="overlay"></div>
    <!-- ======== sidebar-nav end =========== -->

    <!-- ======== main-wrapper start =========== -->
    <main class="main-wrapper">
        <!-- ========== header start ========== -->
        <header class="header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-6">
                        <div class="header-left d-flex align-items-center">
                            <div class="menu-toggle-btn mr-15">
                                <button id="menu-toggle" class="main-btn primary-btn btn-hover">
                                    <i class="lni lni-chevron-left me-2"></i> Menu
                                </button>
                            </div>
                            <div class="header-search d-none d-md-flex">
                                <form action="#">
                                    <input type="text" placeholder="Search..." />
                                    <button><i class="lni lni-search-alt"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-7 col-6">
                        <div class="header-right">
                            <!-- notification start -->
                            <div class="notification-box ml-15 d-none d-md-flex">
                                <button class="dropdown-toggle" type="button" id="notification" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11 20.1667C9.88317 20.1667 8.88718 19.63 8.23901 18.7917H13.761C13.113 19.63 12.1169 20.1667 11 20.1667Z" fill="" />
                                        <path d="M10.1157 2.74999C10.1157 2.24374 10.5117 1.83333 11 1.83333C11.4883 1.83333 11.8842 2.24374 11.8842 2.74999V2.82604C14.3932 3.26245 16.3051 5.52474 16.3051 8.24999V14.287C16.3051 14.5301 16.3982 14.7633 16.564 14.9352L18.2029 16.6342C18.4814 16.9229 18.2842 17.4167 17.8903 17.4167H4.10961C3.71574 17.4167 3.5185 16.9229 3.797 16.6342L5.43589 14.9352C5.6017 14.7633 5.69485 14.5301 5.69485 14.287V8.24999C5.69485 5.52474 7.60672 3.26245 10.1157 2.82604V2.74999Z" fill="" />
                                    </svg>
                                    <span id="count-label" class="badge bg-danger position-absolute top-0 start-100 translate-middle" style="display: none;">0</span>

                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notification" id="notificationContent">
                                    <!-- Notificaciones AJAX aquí -->
                                </ul>

                            </div>
                            <!-- notification end -->


                            <!-- profile start -->
                            <div class="profile-box ml-15">
                                <button class="dropdown-toggle bg-transparent border-0" type="button" id="profile"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="profile-info">
                                        <div class="info">
                                            <div class="image">
                                                <img src="<?php echo $ruta_imagen; ?>" alt="" />
                                            </div>
                                            <div>
                                                <h6 class="fw-500"><?php echo $usuario; ?></h6>
                                                <p><?php echo $user['rol']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profile">

                                    <li class="divider"></li>
                                    <li>
                                        <a href="../views/perfilUser.php">
                                            <i class="lni lni-user"></i> Perfil
                                        </a>
                                    </li>

                                    <?php if ($_SESSION["type"] == 1 || $_SESSION["type"] == 3) { ?>
                                        <li>
                                            <a href="../views/configuracionEmpresa.php"> <i class="lni lni-cog"></i> Configuracion </a>
                                        </li>

                                    <?php } ?>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="../views/soporte.php">

                                            <span class="mdi mdi-information"></span>Soporte
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"> <i class="lni lni-exit"></i> Logout </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- profile end -->
                        </div>
                    </div>
                </div>
            </div>
        </header>

      <div class="container-fluid pt-30">

    <div class="row">
        <div class="col-12">

            <div class="card-style mb-30">
                <h6 class="mb-10">Dashboard</h6>
                <p>Sistema cargado correctamente.</p>
            </div>

        </div>
    </div>

</div>
        <?php include "../views/ventanaLogout.php"; ?>
		<?php include "../includes/sesion/validarInactividad.php"; ?>

        </main>
        <!-- ========== header end ========== -->
        <script src="../js/notificaciones.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
		<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="../js/jquery.dataTables.min.js"></script>
<script src="../js/dataTables.bootstrap4.min.js"></script>

<!-- Tu JS principal si existe -->
 <script src="../js/main.js"></script>

 </body>
</html>

