<?php

if(!isset($_GET['archivo'])){
    die("Archivo no especificado");
}

$archivo = $_GET['archivo'];

$archivo = str_replace(["..", "\\"], "", $archivo);

$ruta = __DIR__ . "/../documentos/" . $archivo;

echo "<h3>DEBUG</h3>";

echo "<b>Ruta:</b><br>";
echo $ruta . "<br><br>";

echo "<b>Existe:</b> ";
echo file_exists($ruta) ? "SI" : "NO";
echo "<br><br>";

echo "<b>Readable:</b> ";
echo is_readable($ruta) ? "SI" : "NO";
echo "<br><br>";

echo "<b>Tamaño:</b> ";
echo file_exists($ruta) ? filesize($ruta) : "0";
echo "<br><br>";

if(file_exists($ruta)){
    echo "<a href='/documentos/$archivo' target='_blank'>
            ABRIR DIRECTO
          </a>";
}