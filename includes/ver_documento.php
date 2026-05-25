<?php
session_start();

if(!isset($_GET['archivo'])){
    die("Archivo no especificado.");
}

$archivo = $_GET['archivo'];

// Seguridad
$archivo = str_replace(["..", "\\"], "", $archivo);

// Ruta real
$ruta = __DIR__ . "/../documentos/" . $archivo;

// DEBUG
if(!file_exists($ruta)){
    die("Archivo no encontrado: " . $ruta);
}

// Limpiar buffers
if (ob_get_length()) {
    ob_end_clean();
}

// Headers
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . basename($ruta) . '"');
header('Content-Length: ' . filesize($ruta));
header('Cache-Control: private');
header('Pragma: public');

readfile($ruta);
exit;
?>