<?php
require_once("db.php");
require_once(__DIR__ . "/../fpdf/fpdf.php");

date_default_timezone_set('America/Bogota');

$id = intval($_GET['id']);

// ===============================
// 🔹 OBTENER DATOS DEL PRÉSTAMO + TIPO PROYECCIÓN
// ===============================
$prestamo = mysqli_query($conexion,"
SELECT p.monto_prestado,
       p.folioPrest,
       c.nombreClient,
       c.apellidoClient,
       tp.tipo_proyeccion
FROM prestamos p
INNER JOIN clientes c ON p.id_cliente = c.id
INNER JOIN tipo_prestamo tp ON p.id_tp = tp.id
WHERE p.id = $id
");

if(!$prestamo || mysqli_num_rows($prestamo) == 0){
    die("Préstamo no encontrado");
}

$dataPrestamo = mysqli_fetch_assoc($prestamo);

$monto = $dataPrestamo['monto_prestado'];
$cliente = $dataPrestamo['nombreClient'].' '.$dataPrestamo['apellidoClient'];
$tipoProyeccion = $dataPrestamo['tipo_proyeccion'];
$folio = $dataPrestamo['folioPrest'];

// ===============================
// 🔹 OBTENER CUOTAS
// ===============================
$cuotas = mysqli_query($conexion,"
SELECT numero_cuota, interes, capital, valor_cuota, fecha_pago
FROM cuotas_prestamo
WHERE id_prestamo = $id
ORDER BY numero_cuota ASC
");

if(!$cuotas){
    die("Error en cuotas: ".mysqli_error($conexion));
}

// ===============================
// 🔹 CREAR PDF
// ===============================
$pdf = new FPDF('L','mm','A4');
$pdf->AddPage();

// ===============================
// 🔹 LOGO
// ===============================
$pdf->Image(__DIR__.'/../images/logo.png',10,10,55);

$pdf->Ln(30);

// ===============================
// 🔹 TITULO
// ===============================
$pdf->SetFont('Arial','B',18);
$pdf->SetTextColor(0,51,102);
$pdf->Cell(0,12,'TABLA DE AMORTIZACION',0,1,'C');

$pdf->Ln(5);

// ===============================
// 🔹 INFORMACIÓN GENERAL
// ===============================
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','',11);

$pdf->Cell(0,8,"Cliente: $cliente",0,1,'C');
$pdf->Cell(0,8,"Prestamo #: $folio",0,1,'C');
$pdf->Cell(0,8,"Monto: $ ".number_format($monto,0,',','.'),0,1,'C');

$pdf->Ln(8);

// ===============================
// 🔹 TABLA CENTRADA
// ===============================

// ancho total tabla
$tableWidth = 15 + 40 + 40 + 40 + 40 + 40; 
$startX = (297 - $tableWidth) / 2; // 297mm ancho A4 horizontal
$pdf->SetX($startX);

// Encabezados azul
$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(0,91,187);
$pdf->SetTextColor(255,255,255);

$pdf->Cell(15,10,'#',1,0,'C',true);
$pdf->Cell(40,10,'Interes',1,0,'C',true);
$pdf->Cell(40,10,'Capital',1,0,'C',true);
$pdf->Cell(40,10,'Valor Cuota',1,0,'C',true);
$pdf->Cell(40,10,'Saldo',1,0,'C',true);
$pdf->Cell(40,10,'Fecha Pago',1,1,'C',true);

// Cuerpo
$pdf->SetFont('Arial','',9);
$pdf->SetTextColor(0,0,0);

$saldo = $monto;

while($row = mysqli_fetch_assoc($cuotas)){

    if($tipoProyeccion == 1){
        $valorCuota = $row['interes'] + $row['capital'];
        $saldo -= $row['capital'];
        $saldoMostrar = number_format($saldo,0,',','.');
    } else {
        $valorCuota = $row['interes'] + $row['capital'];
        $saldoMostrar = '';
    }

    $pdf->SetX($startX);

    $pdf->Cell(15,8,$row['numero_cuota'],1,0,'C');
    $pdf->Cell(40,8,number_format($row['interes'],0,',','.'),1,0,'R');
    $pdf->Cell(40,8,number_format($row['capital'],0,',','.'),1,0,'R');
    $pdf->Cell(40,8,number_format($valorCuota,0,',','.'),1,0,'R');
    $pdf->Cell(40,8,$saldoMostrar,1,0,'R');
    $pdf->Cell(40,8,$row['fecha_pago'],1,1,'C');
}

$pdf->Ln(10);

// ===============================
// 🔹 PIE
// ===============================
$pdf->SetFont('Arial','I',8);
$pdf->Cell(0,8,"Generado el ".date("d/m/Y H:i"),0,1,'R');

$pdf->Output("I","Amortizacion_Prestamo_$id.pdf");
?>