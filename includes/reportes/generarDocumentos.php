<?php
require_once("../db.php");


// Validar ID
if (!isset($_GET['id'])) {
    die("ID no válido");
}

$idPrestamo = intval($_GET['id']);

// Obtener datos básicos del préstamo
$sql = "SELECT p.folioPrest, c.nombreClient, c.apellidoClient
        FROM prestamos p
        INNER JOIN clientes c ON p.id_cliente = c.id
        WHERE p.id = $idPrestamo";

$result = mysqli_query($conexion, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Préstamo no encontrado");
}

$data = mysqli_fetch_assoc($result);

// ==========================
// GENERAR PDF SIMPLE
// ==========================

// Forzar descarga
header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename=documentos_prestamo_" . $data['folioPrest'] . ".pdf");

// Contenido PDF básico
echo "%PDF-1.4
1 0 obj
<< /Type /Catalog /Pages 2 0 R >>
endobj
2 0 obj
<< /Type /Pages /Kids [3 0 R] /Count 1 >>
endobj
3 0 obj
<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R >>
endobj
4 0 obj
<< /Length 44 >>
stream
BT
/F1 12 Tf
100 700 Td
(Documentos del Prestamo) Tj
0 -20 Td
(Folio: " . $data['folioPrest'] . ") Tj
0 -20 Td
(Cliente: " . $data['nombreClient'] . " " . $data['apellidoClient'] . ") Tj
ET
endstream
endobj
5 0 obj
<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>
endobj
xref
0 6
0000000000 65535 f 
0000000010 00000 n 
0000000060 00000 n 
0000000110 00000 n 
0000000200 00000 n 
0000000350 00000 n 
trailer
<< /Size 6 /Root 1 0 R >>
startxref
450
%%EOF";
exit;
