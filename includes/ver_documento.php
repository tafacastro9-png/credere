<?php
session_start();

if(!isset($_GET['archivo'])){
    die("Archivo no especificado.");
}

$archivo = $_GET['archivo'];

// Seguridad básica
$archivo = str_replace(["..", "\\"], "", $archivo);

// Ruta física REAL
$ruta = __DIR__ . "/../documentos/" . $archivo;

// Verificar si existe
if(!file_exists($ruta)){
    die("Archivo no encontrado.");
}

header("Content-Type: application/pdf");
header("Content-Disposition: inline; filename=\"" . basename($ruta) . "\"");
header("Content-Length: " . filesize($ruta));

readfile($ruta);
exit;
?>