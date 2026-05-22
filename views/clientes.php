
<?php
include "../includes/configSession.php";
require_once "../includes/permisos.php";
require_once "../includes/header.php";
require_once "../includes/db.php";



error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['permisos']) || 
    !in_array('clientes.ver', $_SESSION['permisos'])) {

    echo "<h2 style='color:red; text-align:center; margin-top:100px;'>
    No tienes permisos para acceder a este módulo.
    </h2>";
    exit;
}
?>



<!-- ========== table components start ========== -->
<section class="table-components">
    <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <br>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <div class="card-style mb-30">
                    <div class="titulo-modulo mb-4">
    <i class="fa fa-users me-2"></i>
    Gestión de Clientes
</div>
                    <br>
					<div class="bloque-acciones">
                   <?php if (tienePermiso('clientes.crear', $conexion)) { ?>
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
        Agregar <i class="fa fa-plus"></i>
    </button>
<?php } ?>
                    <?php include("./forms/form_client.php"); ?>
                    <?php if (tienePermiso('clientes.exportar', $conexion)) { ?>
<button onclick="exportarCSV()" class="btn btn-primary blue">
    Exportar a Excel <i class="fas fa-download fa-sm text-white-50"></i>
</button>
<?php } ?>

 <?php if (tienePermiso('clientes.importar', $conexion)) { ?>
    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#impt">
        Importar <i class="fas fa-upload"></i>
    </button>
<?php } ?>
                    <?php include('./forms/formImportar.php'); ?>
					</div>
                    <br>
                    <br>
					<div class="tabla-clientes">
                    <div class="table-wrapper table-responsive">
                        <table class="table" id="datatable">
<thead>
    <tr>
        <th>Action</th>
        <th>Estado</th>
        <th>Consecutivo</th>
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



require_once("../includes/db.php");

$result = mysqli_query($conexion, "

SELECT 
c.*, 
est.estado,
ide.nombre,

il.empresa,
il.tipo_contrato,
il.fecha_ingreso_laboral,
il.totalDevengado,
il.totalDescuentos,
il.netoPagar,

inf.totalIngresos,
inf.totalEgresos,
inf.otrosIngresos,
inf.activos,
inf.pasivos,
inf.patrimonios

FROM clientes c 

LEFT JOIN estado_registros est 
    ON c.id_status = est.id 

LEFT JOIN tipo_identificacion ide 
    ON c.id_tipoIdentificacion = ide.id

LEFT JOIN informacion_laboral il 
    ON il.cliente_id = c.id

LEFT JOIN informacion_financiera inf 
    ON inf.cliente_id = c.id

");

if (!$result) {

    die("Error SQL: " . mysqli_error($conexion));

}



while ($fila = mysqli_fetch_assoc($result)) :


								
	?>							
<tr>

<td>

<?php if (tienePermiso('clientes.ver', $conexion)) { ?>
<a href="ver_cliente.php?id=<?php echo $fila['id']; ?>" 
   class="btn btn-primary btn-xs">
   <i class="fa fa-search"></i>
</a>
<?php } ?>

<?php if (tienePermiso('clientes.editar', $conexion)) { ?>
<button type="button"
        class="btn btn-warning btn-xs"
        data-bs-toggle="modal"
        data-bs-target="#editar<?php echo $fila['id']; ?>">
    <i class="fa fa-edit"></i>
</button>
<?php } ?>



</td>

<td>
<span class="estado <?php echo strtolower($fila['estado']) == 'activo' ? 'activo' : 'inactivo'; ?>">
<?php echo $fila['estado']; ?>
</span>
</td>

<td><?php echo $fila['folioClient']; ?></td>
<td><?php echo $fila['nombreClient'] . ' ' . $fila['apellidoClient']; ?></td>
<td><?php echo $fila['nombre']; ?></td>
<td><?php echo $fila['docIdentClient']; ?></td>
<td><?php echo $fila['telClient']; ?></td>
<td><?php echo $fila['correoClient']; ?></td>
<td><?php echo $fila['dirClient']; ?></td>
<td><?php echo $fila['fecha_registro']; ?></td>

</tr>             

                                    <?php include "./forms/editar_client.php"; ?>
                                <?php endwhile; ?>

                        </tbody>
                    </table>
                </div> <!-- table-wrapper -->
            </div> <!-- card-style -->
        </div> <!-- col -->
    </div> <!-- row -->
</div> <!-- container-fluid -->
</section>
<!-- ========== table components end ========== -->

<script>
    function exportarCSV() {
        $.ajax({
            url: '../includes/exportCSV.php', // Cambia la URL al script que genera el archivo CSV
            method: 'GET',
            dataType: 'text', // Cambia a 'text' para recibir datos de tipo texto
            success: function(response) {
                // Descargar el archivo CSV
                var blob = new Blob([response], {
                    type: 'text/csv;charset=utf-8;'
                });
                var link = document.createElement("a");
                if (link.download !== undefined) {
                    var url = URL.createObjectURL(blob);
                    link.setAttribute("href", url);
                    link.setAttribute("download", "REPORTE_CLIENTES.csv");
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
    padding: 5px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.estado.activo {
    background-color: #2e7d32;
    color: #fff;
}

.estado.inactivo {
    background-color: #dc3545;
    color: #fff;
}
.btn-xs {
    padding: 2px 6px !important;
    font-size: 12px !important;
    line-height: 1.2 !important;
}

.btn-xs i {
    font-size: 12px !important;
}
.titulo-modulo {
    font-size: 28px;
    font-weight: 700;
    color: #0b1e4f;
    padding-bottom: 8px;
    border-bottom: 4px solid #0b1e4f;
    display: inline-block;
    letter-spacing: 0.5px;
}
.titulo-modulo i {
    color: #1e3a8a;
}

.bloque-acciones {
    margin-top: 25px;
}

.tabla-clientes {
    margin-top: 25px;
}
.tabla-clientes {
    margin-top: 35px;
}
.card {
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}




</style>
//<?php include "../includes/footer.php"; ?>

<h1 style="color:red;">PRUEBA FINAL</h1>

</main>
</body>
</html>