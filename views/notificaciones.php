<?php 
include "../includes/configSession.php";
require_once "../includes/permisos.php";
require_once "../includes/db.php";
include "../includes/header.php";

if (!isset($_SESSION['permisos']) || 
    !in_array('notificaciones.ver', $_SESSION['permisos'])) {

    echo '
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="card shadow-lg border-0 text-center p-5" style="max-width: 500px; border-radius: 15px;">
            
            <div class="mb-4">
                <i class="bi bi-shield-lock-fill" style="font-size: 60px; color: #dc3545;"></i>
            </div>

            <h3 class="mb-3 fw-bold text-danger">Acceso Restringido</h3>
            <p class="text-muted mb-4">
                No tienes permisos para acceder a este módulo.
            </p>

            <a href="index.php" class="btn btn-primary px-4">
                Volver al inicio
            </a>
        </div>
    </div>
    ';
    exit;
}

date_default_timezone_set('America/Mexico_City');


$query = "SELECT p.*, c.folioClient, c.nombreClient, c.apellidoClient, a.nombreAval, a.apellidoAval,
tp.nombre_tipo, ep.statusPrest, dp.total_pagar, dp.num_cuotas, dp.monto_cuota,dp.frecuencia_pago, dp.multa_mora
FROM prestamos p INNER JOIN clientes c ON p.id_cliente = c.id INNER JOIN avales a ON p.id_aval = a.id
INNER JOIN tipo_prestamo tp ON p.id_tp = tp.id INNER JOIN estado_prestamo ep ON p.id_estp = ep.id INNER JOIN detalle_prestamo dp 
ON dp.id_prestamo = p.id WHERE p.id_estp IN ('1')";

$resultado = mysqli_query($conexion, $query);

?>


<!-- ========== notification-wrapper start ========== -->
<div class="notification-wrapper">
    <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <div class="title-wrapper pt-30">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="title">
                        <h2>Notificaciones</h2>
                    </div>
                </div>
                <!-- end col -->
                <div class="col-md-6">
                    <div class="breadcrumb-wrapper">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="#0">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Notificaciones
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- ========== title-wrapper end ========== -->

        <div class="card-style">
            <?php while ($row = mysqli_fetch_assoc($resultado)) { ?>
                <div class="single-notification">
                    <div class="checkbox">
                        <div class="form-check checkbox-style mb-20">
                            <input class="form-check-input" type="checkbox" value="" />
                        </div>
                    </div>
                    <div class="notification">
                        <div class="image warning-bg">
                            <span><?php echo strtoupper(substr($row['nombreClient'], 0, 1)); ?></span>
                        </div>
                        <a href="#0" class="content">
                            <h6>
                                <?php echo $row['nombreClient'] . " " . $row['apellidoClient']; ?> tiene un préstamo en estado:
                                <strong><?php echo $row['statusPrest']; ?></strong>
                            </h6>
                            <p class="text-sm text-gray">
                                Monto a pagar: <strong>$<?php echo number_format($row['total_pagar'], 2); ?></strong><br>
                                Cuotas: <?php echo $row['num_cuotas']; ?> | Cuota individual: $<?php echo number_format($row['monto_cuota'], 2); ?><br>
                                Frecuencia: <?php echo ucfirst($row['frecuencia_pago']); ?> | Multa por mora: $<?php echo number_format($row['multa_mora'], 2); ?><br>
                                Tipo de préstamo: <?php echo $row['nombre_tipo']; ?><br>
                                Aval: <?php echo $row['nombreAval'] . " " . $row['apellidoAval']; ?>
                            </p>
                            <span class="text-sm text-medium text-gray"><?php echo date("d-m-Y", strtotime($row['fechaRegistro'])); ?></span>
                        </a>
                    </div><!--
                    <div class="action">
                        <button class="delete-btn">
                            <i class="lni lni-trash-can"></i>
                        </button>
                        <button class="more-btn dropdown-toggle" id="moreAction" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="lni lni-more-alt"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="moreAction">
                            <li class="dropdown-item">
                                <a href="#0" class="text-gray">Marcar como leído</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="#0" class="text-gray">Detalles</a>
                            </li>
                        </ul>
                    </div>-->
                </div>
            <?php } ?>

        </div>
        <!-- end container -->
    </div>
    <!-- ========== notification-wrapper start ========== -->

    <?php include "../includes/footer.php"; ?>