<?php
include "../includes/header.php";
require_once("../includes/db.php");

if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>Cliente no encontrado</div>";
    exit;
}

$id = intval($_GET['id']);

$sql = "
SELECT 
    c.*,
    est.estado,
    ide.nombre AS tipo_identificacion,

    d.ruta_archivo,

    cony.nombre_conyuge,
    cony.doc_conyuge,
    cony.ceular_conyugue,
    cony.tel_conyuge,
    cony.empresa_conyugue,
    cony.ocupacion_conyugue,
    cony.correoconyugue,
	
	cod.nombrecodeudor,
	cod.numerodocumentocodeudor,
	cod.celularcodeudor,
	cod.tienevehiculocodeudor,
	cod.placacodeudor,

    fin.totalIngresos,
    fin.otrosIngresos,
    fin.totalEgresos,
    fin.activos,
    fin.pasivos,
    fin.patrimonios,

    lab.empresa,
    lab.tipo_contrato,
    lab.fecha_ingreso_laboral,
    lab.totalDevengado,
    lab.totalDescuentos,
    lab.netoPagar,
    lab.direccion_laboral,
    lab.ocupacion_laboral,
    lab.cargo_laboral

FROM clientes c

INNER JOIN estado_registros est ON c.id_status = est.id
INNER JOIN tipo_identificacion ide ON c.id_tipoIdentificacion = ide.id

LEFT JOIN documentos_clientes d 
    ON d.cliente_id = c.id 
    AND d.tipo_documento = 'foto'

LEFT JOIN conyuges cony 
    ON cony.cliente_id = c.id

LEFT JOIN informacion_financiera fin 
    ON fin.cliente_id = c.id

LEFT JOIN informacion_laboral lab 
    ON lab.cliente_id = c.id
	
LEFT JOIN codeudor_prestamo cod 
    ON cod.cliente_id = c.id	
	

WHERE c.id = $id
";

$result = mysqli_query($conexion, $sql);

if (!$result) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

$cliente = mysqli_fetch_assoc($result);

if (!$cliente) {
    echo "<div class='alert alert-danger'>Cliente no encontrado</div>";
    exit;
}

/* ================= A V A L E S ================= */

$sqlAvales = "
SELECT a.*, tr.nombre AS tipo_referencia
FROM avales a
LEFT JOIN tipo_referencia tr ON a.id_tiporeferencia = tr.id
WHERE a.folioAval = '".$cliente['folioClient']."'
";

$resultAvales = mysqli_query($conexion, $sqlAvales);
?>

<section class="container mt-4 mb-5">

<div class="card expediente-card">
<div class="card-header expediente-header">
    <h3>
        <i class="fa fa-folder-open me-2"></i>
        Expediente del Cliente
    </h3>
</div>
</div>


<div class="card-body">
<div class="row">

<!-- FOTO -->
<div class="col-md-3 text-center border-end">
<img src="<?php echo !empty($cliente['ruta_archivo']) ? $cliente['ruta_archivo'] : '../img/sin-foto.png'; ?>" 
class="img-fluid rounded expediente-foto">

<h5 class="mt-3 nombre-cliente">
    <?php echo $cliente['nombreClient'] . ' ' . $cliente['apellidoClient']; ?>
</h5>

<span class="estado <?php echo strtolower($cliente['estado'])=='activo'?'activo':'inactivo'; ?>">
<?php echo $cliente['estado']; ?>
</span>
</div>

<div class="col-md-9">

<!-- DATOS PERSONALES -->
<h5 class="seccion-titulo">DATOS PERSONALES</h5>
<div class="row mb-3">
<div class="col-md-6"><strong>Identificación:</strong> <?php echo $cliente['docIdentClient']; ?></div>
<div class="col-md-6"><strong>Tipo:</strong> <?php echo $cliente['tipo_identificacion']; ?></div>
<div class="col-md-6"><strong>Estado Civil:</strong> <?php echo $cliente['estado_civil']; ?></div>
<div class="col-md-6"><strong>Género:</strong> <?php echo $cliente['genero']; ?></div>
<div class="col-md-6"><strong>Fecha Nacimiento:</strong> <?php echo $cliente['fecha_nacimiento']; ?></div>
<div class="col-md-6"><strong>Personas a Cargo:</strong> <?php echo $cliente['personas_cargo']; ?></div>
<div class="col-md-12"><strong>Dirección:</strong> <?php echo $cliente['dirClient']; ?></div>
<div class="col-md-6"><strong>Teléfono:</strong> <?php echo $cliente['telClient']; ?></div>
<div class="col-md-6"><strong>Celular:</strong> <?php echo $cliente['celClient']; ?></div>
<div class="col-md-12"><strong>Correo:</strong> <?php echo $cliente['correoClient']; ?></div>
</div>

<!-- INFORMACION LABORAL -->
<h5 class="seccion-titulo">INFORMACIÓN LABORAL</h5>
<div class="row mb-3">
<div class="col-md-6"><strong>Empresa:</strong> <?php echo $cliente['empresa']; ?></div>
<div class="col-md-6"><strong>Tipo Contrato:</strong> <?php echo $cliente['tipo_contrato']; ?></div>
<div class="col-md-6"><strong>Cargo:</strong> <?php echo $cliente['cargo_laboral']; ?></div>
<div class="col-md-6"><strong>Ocupación:</strong> <?php echo $cliente['ocupacion_laboral']; ?></div>
<div class="col-md-6"><strong>Devengado:</strong> $ <?php echo number_format($cliente['totalDevengado']); ?></div>
<div class="col-md-6"><strong>Neto:</strong> $ <?php echo number_format($cliente['netoPagar']); ?></div>
</div>

<!-- INFORMACION FINANCIERA -->
<h5 class="seccion-titulo">INFORMACIÓN FINANCIERA</h5>
<div class="row mb-3">
<div class="col-md-6"><strong>Total Ingresos:</strong> $ <?php echo number_format($cliente['totalIngresos']); ?></div>
<div class="col-md-6"><strong>Otros Ingresos:</strong> $ <?php echo number_format($cliente['otrosIngresos']); ?></div>
<div class="col-md-6"><strong>Total Egresos:</strong> $ <?php echo number_format($cliente['totalEgresos']); ?></div>
<div class="col-md-6"><strong>Activos:</strong> $ <?php echo number_format($cliente['activos']); ?></div>
<div class="col-md-6"><strong>Pasivos:</strong> $ <?php echo number_format($cliente['pasivos']); ?></div>
<div class="col-md-6"><strong>Patrimonio:</strong> $ <?php echo number_format($cliente['patrimonios']); ?></div>
</div>

<!-- CONYUGE -->
<?php if(!empty($cliente['nombre_conyuge'])): ?>
<h5 class="seccion-titulo">DATOS DEL CÓNYUGE</h5>
<div class="row mb-3">
<div class="col-md-6"><strong>Nombre:</strong> <?php echo $cliente['nombre_conyuge']; ?></div>
<div class="col-md-6"><strong>Documento:</strong> <?php echo $cliente['doc_conyuge']; ?></div>
<div class="col-md-6"><strong>Celular:</strong> <?php echo $cliente['ceular_conyugue']; ?></div>
<div class="col-md-6"><strong>Empresa:</strong> <?php echo $cliente['empresa_conyugue']; ?></div>
</div>
<?php endif; ?>




<!-- CODEUDOR -->
<?php if(!empty($cliente['nombrecodeudor'])): ?>
<h5 class="seccion-titulo">DATOS DEL CODEUDOR</h5>
<div class="row mb-3">
<div class="col-md-6"><strong>Nombre:</strong> <?php echo $cliente['nombrecodeudor']; ?></div>
<div class="col-md-6"><strong>Documento:</strong> <?php echo $cliente['numerodocumentocodeudor']; ?></div>
<div class="col-md-6"><strong>Celular:</strong> <?php echo $cliente['celularcodeudor']; ?></div>
<div class="col-md-6"><strong>Tiene Vehiculo?:</strong> <?php echo $cliente['tienevehiculocodeudor']; ?></div>
<div class="col-md-6"><strong>Placa:</strong> <?php echo $cliente['placacodeudor']; ?></div>
</div>
<?php endif; ?>




</div>
</div>
</div>
</div>

<div class="text-center mt-4">
<a href="clientes.php" class="btn btn-primary">
<i class="fa fa-arrow-left"></i> Volver
</a>
</div>

</section>
<style>
/* ===== ESTILO EXPEDIENTE BANCARIO ===== */

.expediente-card {
    border: none;
    box-shadow: 0 4px 18px rgba(0,0,0,0.08);
    border-radius: 12px;
}

.expediente-header {
    background-color: #000a38;
    color: #fff;
    padding: 15px 25px;
    border-radius: 12px 12px 0 0;
}

.expediente-header h3 {
    margin: 0;
    font-weight: 600;
}

.seccion-titulo {
    margin-top: 20px;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 2px solid #000a38;
    color: #000a38;
    font-weight: 600;
}

.expediente-foto {
    width: 180px;
    height: 220px;
    object-fit: cover;
    border: 4px solid #000a38;
    box-shadow: 0 3px 10px rgba(0,0,0,0.15);
}

.estado {
    display: inline-block;
    padding: 6px 18px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
    margin-top: 10px;
}

.estado.activo {
    background-color: #2e7d32;
    color: #fff;
}

.estado.inactivo {
    background-color: #dc3545;
    color: #fff;
}

.expediente-header {
    background-color: #0b1e4f;
    color: white;
    font-weight: bold;
}
.expediente-header {
    background: linear-gradient(90deg, #0b1e4f, #122a6b);
    color: white;
    padding: 18px 25px;
    border-radius: 12px 12px 0 0;
}

.expediente-header h3 {
    margin: 0;
    font-size: 22px;
    font-weight: 600;
    letter-spacing: 0.5px;
}
.expediente-header {
    background-color: #1e3570; /* azul */
    padding: 18px 25px;
}

.expediente-header h3 {
    color: #ffffff !important;
    margin: 0;
}

.expediente-header i {
    color: #ffffff !important;
}
.col-md-3.text-center {
    padding-top: 25px;
}

.nombre-cliente {
    text-transform: uppercase;
    font-weight: 600;
}


</style>

<?php include "../includes/footer.php"; ?>