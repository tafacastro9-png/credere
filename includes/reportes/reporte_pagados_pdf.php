<?php
date_default_timezone_set('America/Mexico_City');
require('../../fpdf/fpdf.php');
require_once("../db.php");

class PDF extends FPDF
{
    public $fechaInicio;  // Propiedad para fecha inicio
    public $fechaFin;     // Propiedad para fecha fin

    function Header()
    {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'REPORTE DE PRESTAMOS PAGADOS', 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 10, 'Fecha de reporte: ' . date('d-m-Y'), 0, 1, 'C');

        // Mostrar rango de fechas si están definidas
        if (!empty($this->fechaInicio) && !empty($this->fechaFin)) {
            $this->Cell(0, 10, 'Rango: ' . date('d-m-Y', strtotime($this->fechaInicio)) . ' al ' . date('d-m-Y', strtotime($this->fechaFin)), 0, 1, 'C');
        }
        $this->Ln(10);

        // Establecer color de fondo (gris oscuro) y texto (blanco)
        $this->SetFillColor(60, 60, 60);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 9);

        // Encabezado tabla
        $this->Cell(60, 8, 'Cliente', 1, 0, 'C', true);
        $this->Cell(60, 8, 'Aval', 1, 0, 'C', true);
        $this->Cell(50, 8, 'Tipo', 1, 0, 'C', true);
        $this->Cell(20, 8, 'Monto', 1, 0, 'C', true);
        $this->Cell(25, 8, 'Inicio', 1, 0, 'C', true);
        $this->Cell(25, 8, 'Vencimiento', 1, 0, 'C', true);
        $this->Cell(20, 8, 'Estado', 1, 0, 'C', true);
        $this->Ln();
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Obtener y validar fechas
$star = isset($_GET['star']) ? date("Y-m-d", strtotime($_GET['star'])) : '';
$fin = isset($_GET['fin']) ? date("Y-m-d", strtotime($_GET['fin'])) : '';

if ($star == '' || $fin == '') {
    echo "<script>alert('Rango de fechas inválido.'); window.close();</script>";
    exit();
}

// Crear PDF y asignar fechas antes de AddPage()
$pdf = new PDF('L', 'mm', array(216, 279)); // Landscape, tamaño carta
$pdf->fechaInicio = $star;
$pdf->fechaFin = $fin;
$pdf->AddPage();
$pdf->SetFont('Arial', '', 9);

// Consulta SQL con filtro de fechas
$query = "
    SELECT p.*, c.nombreClient, c.apellidoClient, a.nombreAval, a.apellidoAval,
           tp.nombre_tipo, ep.statusPrest
    FROM prestamos p
    INNER JOIN clientes c ON p.id_cliente = c.id
    INNER JOIN avales a ON p.id_aval = a.id
    INNER JOIN tipo_prestamo tp ON p.id_tp = tp.id
    INNER JOIN estado_prestamo ep ON p.id_estp = ep.id
    WHERE p.id_estp = 5
      AND DATE(p.fecha_inicio) BETWEEN '$star' AND '$fin'
";

$result = mysqli_query($conexion, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $cliente = $row['nombreClient'] . ' ' . $row['apellidoClient'];
    $aval = $row['nombreAval'] . ' ' . $row['apellidoAval'];
    $tipo = $row['nombre_tipo'];
    $monto = '$' . number_format($row['monto_prestado'], 2);
    $inicio = $row['fecha_inicio'];
    $vencimiento = $row['fecha_vencimiento'];
    $estado = $row['statusPrest'];

    $pdf->Cell(60, 8, utf8_decode($cliente), 1);
    $pdf->Cell(60, 8, utf8_decode($aval), 1);
    $pdf->Cell(50, 8, utf8_decode($tipo), 1);
    $pdf->Cell(20, 8, $monto, 1, 0, 'R');
    $pdf->Cell(25, 8, $inicio, 1);
    $pdf->Cell(25, 8, $vencimiento, 1);
    $pdf->Cell(20, 8, utf8_decode($estado), 1);
    $pdf->Ln();
}

$pdf->Output('I', 'REPORTE_PRESTAMOS_PAGADOS.pdf');
