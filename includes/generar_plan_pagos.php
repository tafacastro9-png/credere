<?php
require('../fpdf/fpdf.php');
require_once("db.php");

date_default_timezone_set('America/Bogota');

$id = intval($_GET['id']);

if (!$id) {
    die("ID inválido");
}

/* ================================
   DATOS DEL PRÉSTAMO
================================ */
$prestamo = mysqli_query($conexion,"
SELECT p.*, 
c.nombreClient, c.apellidoClient,
tp.tipo_proyeccion
FROM prestamos p
INNER JOIN clientes c ON p.id_cliente = c.id
INNER JOIN tipo_prestamo tp ON p.id_tp = tp.id
WHERE p.id = $id
");

if(mysqli_num_rows($prestamo) == 0){
    die("Préstamo no encontrado");
}

$data = mysqli_fetch_assoc($prestamo);

/* ================================
   CUOTAS
================================ */
$cuotas = mysqli_query($conexion,"
SELECT *
FROM cuotas_prestamo
WHERE id_prestamo = $id
ORDER BY numero_cuota ASC
");

class PDF extends FPDF {

    function Header(){

        // Logo
        $this->Image('../images/logo.png',10,8,40);

        $this->SetFont('Arial','B',14);
        $this->Cell(0,10,'PLAN DE PAGOS',0,1,'C');

        $this->SetFont('Arial','',10);
        $this->Cell(0,5,'Fecha de Generacion: '.date("d/m/Y H:i"),0,1,'C');

        $this->Ln(10);
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);

/* ================================
   INFORMACIÓN CLIENTE
================================ */
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,6,'Cliente: '.$data['nombreClient'].' '.$data['apellidoClient'],0,1);
$pdf->Cell(0,6,'Folio: '.$data['folioPrest'],0,1);
$pdf->Ln(5);

/* ================================
   ENCABEZADO TABLA
================================ */
$pdf->SetFillColor(0,70,140); // azul
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Arial','B',9);

$pdf->Cell(15,8,'#',1,0,'C',true);
$pdf->Cell(25,8,'Fecha',1,0,'C',true);
$pdf->Cell(30,8,'Cuota',1,0,'C',true);
$pdf->Cell(30,8,'Capital',1,0,'C',true);
$pdf->Cell(30,8,'Interes',1,0,'C',true);
$pdf->Cell(30,8,'Saldo',1,0,'C',true);
$pdf->Cell(25,8,'Estado',1,1,'C',true);

/* ================================
   CUERPO TABLA
================================ */
$pdf->SetFont('Arial','',9);
$pdf->SetTextColor(0,0,0);

$saldo = $data['monto_prestado'];

while($row = mysqli_fetch_assoc($cuotas)){

    $estado = $row['estado'];

    if($estado == 'Pagado'){
        $estadoTexto = "Pagado";
    } elseif($estado == 'Vencido'){
        $estadoTexto = "Vencido";
    } else {
        $estadoTexto = "Pendiente";
    }

    $pdf->Cell(15,7,$row['numero_cuota'],1,0,'C');
    $pdf->Cell(25,7,$row['fecha_pago'],1,0,'C');

    if($row['valor_cuota'] > 0){
        $cuotaMostrar = $row['valor_cuota'];
    } else {
        $cuotaMostrar = $row['capital'] + $row['interes'];
    }

    $pdf->Cell(30,7,'$'.number_format($cuotaMostrar,0,',','.'),1,0,'R');
    $pdf->Cell(30,7,'$'.number_format($row['capital'],0,',','.'),1,0,'R');
    $pdf->Cell(30,7,'$'.number_format($row['interes'],0,',','.'),1,0,'R');

    // 🔥 SALDO SEGÚN TIPO
    if($data['tipo_proyeccion'] == 1){ 
        // AMORTIZADO → baja saldo
        $saldo = $saldo - $row['capital'];
        $saldoMostrar = '$'.number_format($saldo,0,',','.');
    } else {
        // INTERÉS SIMPLE → saldo vacío
        $saldoMostrar = '';
    }

    $pdf->Cell(30,7,$saldoMostrar,1,0,'R');
    $pdf->Cell(25,7,$estadoTexto,1,1,'C');
}

$pdf->Ln(10);

/* ================================
   TOTAL
================================ */
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,8,'Total Prestado: $'.number_format($data['monto_prestado'],0,',','.'),0,1,'R');

$pdf->Output('I','Plan_Pagos_'.$data['folioPrest'].'.pdf');