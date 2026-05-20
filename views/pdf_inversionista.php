<?php
require('../fpdf/fpdf.php');
include("../includes/db.php");

$id = $_GET['id'] ?? 0;

// 🔹 CONSULTAR INVERS
$q = mysqli_query($conexion, "
    SELECT * FROM inversionistas WHERE id='$id'
");

$inv = mysqli_fetch_assoc($q);

if(!$inv){
    die("Inversionista no encontrado");
}

// 🔹 SALDO
$q2 = mysqli_query($conexion, "
    SELECT IFNULL(SUM(
        CASE 
            WHEN UPPER(tipo)='APORTE' THEN valor
            ELSE -valor
        END
    ),0) as saldo
    FROM movimientos_inversionista
    WHERE id_inversionista='$id'
");

$rowSaldo = mysqli_fetch_assoc($q2);
$saldo = $rowSaldo['saldo'] ?? 0;

// ============================
// 🔥 CREAR PDF
// ============================
$pdf = new FPDF();
$pdf->AddPage();

// 🔹 TÍTULO
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'HOJA DE VIDA DEL INVERSIONISTA',0,1,'C');

$pdf->Ln(5);

// 🔹 DATOS
$pdf->SetFont('Arial','',12);

$pdf->Cell(50,8,'Nombre:',0,0);
$pdf->Cell(0,8,$inv['nombre'],0,1);

$pdf->Cell(50,8,'Documento:',0,0);
$pdf->Cell(0,8,$inv['documento'],0,1);

$pdf->Cell(50,8,'Telefono:',0,0);
$pdf->Cell(0,8,$inv['telefono'],0,1);

$pdf->Cell(50,8,'Email:',0,0);
$pdf->Cell(0,8,$inv['email'],0,1);

$pdf->Cell(50,8,'Direccion:',0,0);
$pdf->Cell(0,8,$inv['direccion'],0,1);

$pdf->Cell(50,8,'Barrio:',0,0);
$pdf->Cell(0,8,$inv['barrio'],0,1);

$pdf->Ln(5);

// 🔹 SALDO
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Saldo: $ '.number_format($saldo,0,',','.'),0,1);

// 🔹 SALIDA
$pdf->Output("I","Inversionista_".$inv['documento'].".pdf");