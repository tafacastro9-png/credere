<?php 
session_start();
error_reporting(0);
date_default_timezone_set('America/Bogota');

require('../../fpdf/fpdf.php');
require_once("../db.php");

// =======================
// VALIDAR SESIÓN
// =======================

$usuario = $_SESSION['usuario'];

if (!$usuario) {

    echo "<script>
    alert('Debes iniciar sesión para acceder al sistema.');
    location.assign('./sesion/login.php');
    </script>";

    exit();
}

// =======================
// VALIDAR ID
// =======================

if (!isset($_GET["id"])) {

    echo "<script>
    alert('No has seleccionado el ID del registro');
    location.assign('../../views/prestamos.php');
    </script>";

    exit();
}

$encrypted_id = $_GET['id'];
$decrypted_id = base64_decode($encrypted_id);

// Validar ID
if (!$decrypted_id || !is_numeric($decrypted_id)) {

    echo "<script>
    alert('ID inválido');
    location.assign('../../views/index.php');
    </script>";

    exit();
}

// =======================
// OBTENER CUOTA
// =======================

$query = mysqli_query($conexion, "

SELECT 
    cu.*,
    c.folioClient,
    c.nombreClient,
    c.apellidoClient,
    p.id AS id_prestamo,
    a.nombreAval,
    a.apellidoAval,
    tp.nombre_tipo,
    dp.num_cuotas

FROM cuotas_prestamo cu

INNER JOIN prestamos p 
ON cu.id_prestamo = p.id

INNER JOIN clientes c 
ON p.id_cliente = c.id

INNER JOIN avales a 
ON p.id_aval = a.id

INNER JOIN tipo_prestamo tp 
ON p.id_tp = tp.id

INNER JOIN detalle_prestamo dp 
ON dp.id_prestamo = p.id

WHERE cu.id = $decrypted_id

");

$cuota = mysqli_fetch_assoc($query);

if (!$cuota) {
    die("Cuota no encontrada");
}

// =======================
// OBTENER ÚLTIMO PAGO
// =======================

$queryPago = mysqli_query($conexion, "

SELECT *
FROM pagos_cuotas
WHERE id_cuota = $decrypted_id
ORDER BY id DESC
LIMIT 1

");

$pago = mysqli_fetch_assoc($queryPago);

$valorPagado = $pago['valor'] ?? 0;

// =======================
// VALIDAR TIPO DE PAGO
// =======================

if ($valorPagado < $cuota['monto']) {

    $estadoTexto = 'PENDIENTE - PAGO PARCIAL CUOTA ';

} else {

    $estadoTexto = 'PAGO TOTAL CUOTA';
}

// =======================
// CONFIGURAR PDF
// =======================

$pdf = new FPDF('P', 'mm', array(58, 180));
$pdf->AddPage();

// =======================
// LOGO
// =======================

$pdf->Image('../../images/logo.png', 14, 5, 30);

$pdf->Ln(22);

// =======================
// TITULO
// =======================

$pdf->SetFont('Arial', 'B', 10);

$pdf->Cell(
    0,
    5,
    utf8_decode('COMPROBANTE DE PAGO'),
    0,
    1,
    'C'
);

$pdf->Ln(3);

// Línea
$pdf->SetFont('Arial', '', 8);

$pdf->Cell(
    0,
    3,
    '----------------------------------------',
    0,
    1,
    'C'
);

$pdf->Ln(2);

// =======================
// CLIENTE
// =======================

$pdf->SetFont('Arial', '', 8);

$pdf->Cell(
    0,
    4,
    utf8_decode('Folio: '.$cuota['folioClient']),
    0,
    1
);

$pdf->MultiCell(
    0,
    4,
    utf8_decode(
        'Cliente: '.
        $cuota['nombreClient'].' '.
        $cuota['apellidoClient']
    )
);

$pdf->Ln(2);

$pdf->Cell(
    0,
    3,
    '----------------------------------------',
    0,
    1,
    'C'
);

$pdf->Ln(2);

// =======================
// DETALLE PRÉSTAMO
// =======================

$pdf->Cell(
    0,
    4,
    utf8_decode('Préstamo #: '.$cuota['id_prestamo']),
    0,
    1
);

$pdf->MultiCell(
    0,
    4,
    utf8_decode('Tipo: '.$cuota['nombre_tipo'])
);

$pdf->Cell(
    0,
    4,
    utf8_decode(
        'Cuota: '.
        $cuota['numero_cuota'].
        ' / '.
        $cuota['num_cuotas']
    ),
    0,
    1
);

$pdf->Cell(
    0,
    4,
    utf8_decode('Fecha Pago: '.$pago['fecha']),
    0,
    1
);

$pdf->Ln(2);

$pdf->Cell(
    0,
    3,
    '----------------------------------------',
    0,
    1,
    'C'
);

$pdf->Ln(2);

// =======================
// TOTAL PAGADO
// =======================

$pdf->SetFont('Arial', 'B', 10);

$pdf->Cell(
    0,
    6,
    utf8_decode('TOTAL PAGADO'),
    0,
    1,
    'C'
);

$pdf->SetFont('Arial', 'B', 12);

$pdf->Cell(
    0,
    6,
    '$'.number_format($valorPagado, 2, ',', '.'),
    0,
    1,
    'C'
);

$pdf->Ln(3);

// =======================
// ESTADO
// =======================

$pdf->SetFont('Arial', '', 8);

$pdf->MultiCell(
    0,
    4,
    utf8_decode('Estado: '.$estadoTexto),
    0,
    'C'
);

$pdf->Ln(6);

// =======================
// PIE
// =======================

$pdf->Cell(
    0,
    3,
    '----------------------------------------',
    0,
    1,
    'C'
);

$pdf->Ln(3);

$pdf->Cell(
    0,
    4,
    utf8_decode('Gracias por su pago'),
    0,
    1,
    'C'
);

$pdf->Cell(
    0,
    4,
    date('d/m/Y H:i'),
    0,
    1,
    'C'
);

$pdf->Ln(8);

$pdf->Cell(
    0,
    4,
    '________________________',
    0,
    1,
    'C'
);

$pdf->Cell(
    0,
    4,
    utf8_decode('Firma Autorizada'),
    0,
    1,
    'C'
);

// =======================
// SALIDA PDF
// =======================

$pdf->Output(
    'I',
    'comprobante_pago.pdf'
);
?>