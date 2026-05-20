<?php
require_once("db.php");

$result = mysqli_query($conexion, "SELECT * FROM clientes");

// Crear el contenido CSV con las columnas adecuadas
$csvContent = "\"Folio\",\"Nombres\",\"Apellidos\",\"tipo Identificacion,\"#Documento Identidad\",\"Telefono\",\"Correo\",\"Direccion\"\n";

while ($fila = mysqli_fetch_assoc($result)) {
    $csvContent .= "\"" . $fila['folioClient'] . "\",\"" .
        $fila['nombreClient'] . "\",\"" .
        $fila['apellidoClient'] . "\",\"" .
		$fila['id_tipoIdentificacion'] . "\",\"" .
        $fila['docIdentClient'] . "\",\"" .
        $fila['telClient'] . "\",\"" .
        $fila['correoClient'] . "\",\"" .
        $fila['dirClient'] . "\"\n";  // <-- Aquí agregas salto de línea, y cierras bien la fila
}

// Definir las cabeceras para descargar el archivo CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="REPORTE_CLIENTES.csv"');

// Imprimir el contenido CSV
echo $csvContent;
?>
