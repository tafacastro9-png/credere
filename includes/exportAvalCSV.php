<?php
require_once("db.php");

$result = mysqli_query($conexion, "SELECT * FROM avales");

// Crear el contenido CSV con las columnas adecuadas
$csvContent = "\"Folio\",\"id_tiporeferencia\",\"Nombres\",\"Apellidos\",\"id_tipoidentificacion\",\"#Documento Identidad\",\"Telefono\",\"Correo\",\"Direccion\"\n";

while ($fila = mysqli_fetch_assoc($result)) {
    $csvContent .= "\"" . $fila['folioAval'] . "\",\"" .
	    $fila['id_tiporeferencia'] . "\",\"" .
        $fila['nombreAval'] . "\",\"" .
        $fila['apellidoAval'] . "\",\"" .
		$fila['id_tipoidentificacion'] . "\",\"" .
        $fila['docIdentAval'] . "\",\"" .
        $fila['telAval'] . "\",\"" .
        $fila['correoAval'] . "\",\"" .
        $fila['dirAval'] . "\"\n";  // <-- Aquí agregas salto de línea, y cierras bien la fila
}

// Definir las cabeceras para descargar el archivo CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="REPORTE_AVALES.csv"');

// Imprimir el contenido CSV
echo $csvContent;
