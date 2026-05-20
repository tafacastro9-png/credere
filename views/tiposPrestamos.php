<?php
include "../includes/configSession.php";
require_once "../includes/permisos.php";
require_once "../includes/db.php";
include "../includes/header.php";

if (!isset($_SESSION['permisos']) || 
    !in_array('tiposprestamos.ver', $_SESSION['permisos'])) {
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card shadow-lg border-0 text-center p-5" style="max-width: 500px; border-radius: 15px;">
        
        <div class="mb-4">
            <span class="mdi mdi-shield-lock" style="font-size: 60px; color: #dc3545;"></span>
        </div>

        <h3 class="mb-3 fw-bold text-danger">Acceso Restringido</h3>
        
        <p class="text-muted mb-4">
            No tienes permisos para acceder a este módulo.
            <br>
            Si crees que esto es un error, contacta al administrador.
        </p>

        <a href="index.php" class="btn btn-primary px-4">
            Volver al inicio
        </a>

    </div>
</div>

<?php
include "../includes/footer.php";
exit;
}
?>
<!-- ========== table components start ========== -->
<section class="table-components">
    <div class="container-fluid">
        <br>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <div class="card-style mb-30">
                    <h2 class="mb-10 text-center">TIPOS DE PRESTAMOS</h2>
                    <br>

                    <?php if (tienePermiso('tiposprestamos.crear', $conexion)) { ?>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                        <span class="glyphicon glyphicon-plus"></span> Agregar <i class="fa fa-plus"></i>
                    </button>
                    <?php } ?>

                    <?php include("./forms/form_tipoPrestamo.php"); ?>
                    <br>
                    <br>

                    <div class="table-wrapper table-responsive">
                        <table class="table" id="datatable">
                            <thead>
                                <tr>
                                    <th>Tipo Prestamo</th>
                                    <th>Descripcion</th>
                                    <th>Taza_Int%</th>
                                    <th>Periodo de Gracia</th>
                                    <th>Plazo(meses)</th>
                                    <th>Frecuencia</th>
                                    <th>Multa%</th>
                                    <th>Monto Max$</th>
                                    <th>FechaRegistro</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $result = mysqli_query($conexion, "SELECT tp.*, frp.frecuencia FROM tipo_prestamo tp 
                                INNER JOIN frecuencia_pago frp ON tp.id_frp = frp.id");

                                while ($fila = mysqli_fetch_assoc($result)) :
                                ?>
                                    <tr>
                                        <td><?php echo $fila['nombre_tipo'] ?></td>
                                        <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <?php echo $fila['descripcion']; ?>
                                        </td>
                                        <td><?php echo $fila['tasa_interes'] . '%'; ?></td>
                                        <td><?php echo $fila['periodo_gracia']; ?></td>
                                        <td><?php echo $fila['plazo_dias']; ?></td>
                                        <td><?php echo $fila['frecuencia']; ?></td>
                                        <td><?php echo $fila['multa_mora'] . '%'; ?></td>
                                        <td><?php echo '$' . $fila['monto_maximo']; ?></td>
                                        <td><?php echo $fila['fechaRegistro']; ?></td>
                                        <td>

                                            <?php if (tienePermiso('tiposprestamos.editar', $conexion)) { ?>
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editar<?php echo $fila['id']; ?>">
                                                <i class="fa fa-edit "></i>
                                            </button>
                                            <?php } ?>

                                            <?php if (tienePermiso('tiposprestamos.eliminar', $conexion)) { ?>
                                                <a href="../includes/delete_typePrest.php?id=<?php echo $fila['id'] ?>" class="btn btn-danger btn-del">
                                                    <i class="fa fa-trash "></i>
                                                </a>
                                            <?php } ?>

                                        </td>
                                    </tr>

                                    <?php include "./forms/editar_tiposPrestamos.php"; ?>
                                <?php endwhile; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "../includes/footer.php"; ?>