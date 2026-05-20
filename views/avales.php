<?php
include "../includes/configSession.php";
require_once "../includes/permisos.php";
require_once "../includes/header.php";
require_once "../includes/db.php";

if (!isset($_SESSION['permisos']) || 
    !in_array('referencias.ver', $_SESSION['permisos'])) {
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card shadow-lg border-0 text-center p-5" style="max-width: 500px; border-radius: 15px;">
        
        <div class="mb-4">
            <i class="bi bi-shield-lock-fill" style="font-size: 60px; color: #dc3545;"></i>
        </div>

        <h3 class="mb-3 fw-bold text-danger">Acceso Restringido</h3>
        
        <p class="text-muted mb-4">
            No tienes permisos para acceder a este módulo.
            <br>
            Si crees que esto es un error, contacta al administrador.
        </p>

        <a href="../dashboard.php" class="btn btn-primary px-4">
            Volver al inicio
        </a>

    </div>
</div>

<?php
    exit;
}
?>

<!-- ========== table components start ========== -->
<section class="table-components">
    <div class="container-fluid">
        <br><br>
        <div class="row">
            <div class="col-lg-12">
                <div class="card-style mb-30">

                    <div class="titulo-modulo mb-4">
                        <i class="fa fa-users me-2"></i>
                        Gestion de Referencias
                    </div>

                    <br>

                    <div class="bloque-acciones">

                        <?php if (tienePermiso('referencias.crear', $conexion)) { ?>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                            Agregar <i class="fa fa-plus"></i>
                        </button>
                        <?php } ?>

                        <?php include("./forms/form_aval.php"); ?>

                        <?php if (tienePermiso('referencias.exportar', $conexion)) { ?>
                        <button onclick="exportarCSV()" class="btn btn-primary blue">
                            Exportar a Excel <i class="fas fa-download fa-sm text-white-50"></i>
                        </button>
                        <?php } ?>

                        <?php if (tienePermiso('referencias.importar', $conexion)) { ?>
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#impt">
                            Importar <i class="fas fa-upload"></i>
                        </button>
                        <?php } ?>

                        <?php include('./forms/formImportarAval.php'); ?>

                    </div>

                    <br><br>

                    <div class="tabla-clientes">
                        <div class="table-wrapper table-responsive">
                            <table class="table" id="datatable">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Estado</th>
                                        <th>#Consecutivo</th>
                                        <th>Tipo de Referencia</th>
                                        <th>Nombre</th>
                                        <th>Tipo de Identificacion</th>
                                        <th>Numero identificacion</th>
                                        <th>Telefono</th>
                                        <th>Correo</th>
                                        <th>Direccion</th>
                                        <th>FechaRegistro</th>
                                    </tr>
                                </thead>
                                <tbody>

                                <?php
                                $result = mysqli_query($conexion, "
                                    SELECT a.*, 
                                           est.estado, 
                                           ref.nombre AS nombre_referencia,
                                           tid.nombre AS nombre_identificacion
                                    FROM avales a 
                                    INNER JOIN estado_registros est ON a.id_status = est.id 
                                    INNER JOIN tipo_referencia ref ON a.id_tiporeferencia = ref.id 
                                    INNER JOIN tipo_identificacion tid ON a.id_tipoidentificacion = tid.id
                                ");

                                while ($fila = mysqli_fetch_assoc($result)) :
                                ?>

                                    <tr>
                                        <td>

                                            <?php if (tienePermiso('referencias.editar', $conexion)) { ?>
                                            <button type="button" class="btn btn-warning btn-xs"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editar<?php echo $fila['id']; ?>">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <?php } ?>

                                            <?php if (tienePermiso('referencias.eliminar', $conexion)) { ?>
                                            <a href="../includes/delete_aval.php?id=<?php echo $fila['id'] ?>"
                                               class="btn btn-danger btn-xs btn-del">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <?php } ?>

                                        </td>

                                        <td>
                                            <span class="estado <?php echo strtolower($fila['estado']) == 'activo' ? 'activo' : 'inactivo'; ?>">
                                                <?php echo $fila['estado']; ?>
                                            </span>
                                        </td>

                                        <td><?php echo $fila['folioAval']; ?></td>
                                        <td><?php echo $fila['nombre_referencia']; ?></td>
                                        <td><?php echo $fila['nombreAval'] . ' ' . $fila['apellidoAval']; ?></td>
                                        <td><?php echo $fila['nombre_identificacion']; ?></td>
                                        <td><?php echo $fila['docIdentAval']; ?></td>
                                        <td><?php echo $fila['telAval']; ?></td>
                                        <td><?php echo $fila['correoAval']; ?></td>
                                        <td><?php echo $fila['dirAval']; ?></td>
                                        <td><?php echo $fila['fecha_registro']; ?></td>

                                    </tr>

                                    <?php include "./forms/editar_aval.php"; ?>
                                <?php endwhile; ?>

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<script>
function exportarCSV() {
    $.ajax({
        url: '../includes/exportAvalCSV.php',
        method: 'GET',
        dataType: 'text',
        success: function(response) {
            var blob = new Blob([response], { type: 'text/csv;charset=utf-8;' });
            var link = document.createElement("a");
            if (link.download !== undefined) {
                var url = URL.createObjectURL(blob);
                link.setAttribute("href", url);
                link.setAttribute("download", "REPORTE_AVALES.csv");
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        },
        error: function(xhr, status, error) {
            console.log('Error en la solicitud AJAX:', error);
        }
    });
}
</script>

<style>
.estado {
    display: inline-block;
    padding: 4px 14px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 600;
    text-align: center;
    min-width: 80px;
}
.estado.activo { background-color: #2e7d32; color: #fff; }
.estado.inactivo { background-color: #dc3545; color: #fff; }
.btn-xs { padding: 2px 6px !important; font-size: 12px !important; line-height: 1.2 !important; }
.btn-xs i { font-size: 12px !important; }
.titulo-modulo {
    font-size: 28px;
    font-weight: 700;
    color: #0b1e4f;
    padding-bottom: 8px;
    border-bottom: 4px solid #0b1e4f;
    display: inline-block;
    letter-spacing: 0.5px;
}
.titulo-modulo i { color: #1e3a8a; }
.bloque-acciones { margin-top: 25px; }
.tabla-clientes { margin-top: 35px; }
.card { box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
</style>

<?php include "../includes/footer.php"; ?>