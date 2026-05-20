<?php
date_default_timezone_set('America/Mexico_City');
require('../../fpdf/fpdf.php');
require_once("../db.php");

session_start();
error_reporting(0);
$usuario = $_SESSION['usuario'];
if (!$usuario) { // Verifica si el usuario está autenticado
    echo "<script>
    alert('Debes iniciar sesión para acceder al sistema.');
    location.assign('./sesion/login.php');
    </script>";
    exit();
}

if (!isset($_GET["id"])) {
    echo "<script>
    alert('No has seleccionado el ID del registro');
    location.assign('../../views/prestamosAutorizados.php');
    </script>";
    exit();
}

$encrypted_id = $_GET['id']; // ID encriptado recibido
$decrypted_id = base64_decode($encrypted_id); // Decodifica el ID

// Validar si la decodificación fue exitosa y si es numérico
if (!$decrypted_id || !is_numeric($decrypted_id)) {
    echo "<script>
    alert('ID inválido');
    location.assign('../../views/index.php');
    </script>";
    exit();
}

$query = "SELECT p.*, c.nombreClient, c.apellidoClient, c.dirClient, c.docIdentClient, c.telClient, c.correoClient, c.folioClient,
a.nombreAval, a.apellidoAval, tp.nombre_tipo, ep.statusPrest, dp.total_pagar, dp.num_cuotas, dp.monto_cuota, dp.frecuencia_pago
FROM prestamos p INNER JOIN clientes c ON p.id_cliente = c.id INNER JOIN avales a ON p.id_aval = a.id INNER JOIN tipo_prestamo tp ON p.id_tp = tp.id
INNER JOIN estado_prestamo ep ON p.id_estp = ep.id INNER JOIN detalle_prestamo dp ON dp.id_prestamo = p.id WHERE p.id = $decrypted_id AND p.id_estp = 1";

$resultado = mysqli_query($conexion, $query);
if (mysqli_num_rows($resultado) == 0) {
    die('Préstamo no autorizado o no encontrado.');
}

$prestamo = mysqli_fetch_assoc($resultado);

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, utf8_decode('CONTRATO DE PRÉSTAMO DE DINERO'), 0, 1, 'C');
$pdf->Ln(5);

// Cliente y aval
$clienteNombreCompleto = $prestamo['nombreClient'] . ' ' . $prestamo['apellidoClient'];
$avalNombreCompleto = $prestamo['nombreAval'] . ' ' . $prestamo['apellidoAval'];

// Información del cliente
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode("Datos del Cliente"), 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(0, 8, utf8_decode("Nombre: $clienteNombreCompleto" . ' - ' . "#" . $prestamo['folioClient']));
$pdf->MultiCell(0, 8, utf8_decode("Dirección: " . $prestamo['dirClient']));
$pdf->MultiCell(0, 8, utf8_decode("Documento de identidad: " . $prestamo['docIdentClient']));
$pdf->MultiCell(0, 8, utf8_decode("Correo electrónico: " . $prestamo['correoClient'] . ', ' . "Teléfono: " . $prestamo['telClient']));

$pdf->Ln(5);

// Texto principal del contrato
$pdf->SetFont('Arial', '', 12);
$texto = "Yo, $clienteNombreCompleto, con domicilio en {$prestamo['dirClient']}, declaro haber recibido en calidad de préstamo la cantidad correspondiente al tipo de préstamo '{$prestamo['nombre_tipo']}'.";

$texto .= " El monto total a pagar es de " . number_format($prestamo['total_pagar'], 2) . " unidades monetarias, dividido en {$prestamo['num_cuotas']} cuotas de " . number_format($prestamo['monto_cuota'], 2) . " cada una, con una frecuencia de pago de tipo '{$prestamo['frecuencia_pago']}'.";

$texto .= " El aval que respalda este contrato es $avalNombreCompleto.";

$pdf->MultiCell(0, 8, utf8_decode($texto));
$pdf->Ln(10);

// Detalles del préstamo
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode("Detalles del Préstamo"), 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(0, 8, utf8_decode("Tipo de préstamo: " . $prestamo['nombre_tipo']));
$pdf->MultiCell(0, 8, "Monto prestado: " . number_format($prestamo['monto_prestado'], 2));
$pdf->MultiCell(0, 8, "Fecha de inicio: " . $prestamo['fecha_inicio'] . ', ' . "Vencimiento: " . $prestamo['fecha_vencimiento']);
$pdf->MultiCell(0, 8, "Total a pagar: " . number_format($prestamo['total_pagar'], 2));
$pdf->MultiCell(0, 8, utf8_decode("Número de cuotas: " . $prestamo['num_cuotas']));
$pdf->MultiCell(0, 8, "Monto por cuota: " . number_format($prestamo['monto_cuota'], 2));
$pdf->MultiCell(0, 8, "Frecuencia de pago: " . ucfirst($prestamo['frecuencia_pago']));
$pdf->Ln(10);

// Fecha
$fecha = date("d-m-Y");
$pdf->Cell(0, 8, utf8_decode("Lugar y Fecha: ____________________, $fecha"), 0, 1, 'R');
$pdf->Ln(20);

// Firmas
$pdf->Cell(90, 10, "_________________________", 0, 0, 'C');
$pdf->Cell(90, 10, "_________________________", 0, 1, 'C');
$pdf->Cell(90, 10, utf8_decode("$clienteNombreCompleto"), 0, 0, 'C');
$pdf->Cell(90, 10, utf8_decode("$avalNombreCompleto"), 0, 1, 'C');
$pdf->Ln(1);
$pdf->Cell(90, 10, utf8_decode("Firma del Cliente"), 0, 0, 'C');
$pdf->Cell(90, 10, utf8_decode("Firma del Aval"), 0, 1, 'C');

$pdf->Output('I', 'ContratoPrestamo_' . $prestamo['id'] . '.pdf');
