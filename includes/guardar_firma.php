<?php
include "db.php";

$firma = $_POST['firma'];
$id_credito = $_POST['id_credito'];

/* ==============================
   1️⃣ BUSCAR Y ELIMINAR FIRMA ANTERIOR
============================== */

$sqlBuscar = "SELECT firma_cliente FROM prestamos WHERE id='$id_credito'";
$resBuscar = mysqli_query($conexion, $sqlBuscar);
$filaBuscar = mysqli_fetch_assoc($resBuscar);

if (!empty($filaBuscar['firma_cliente'])) {
    $rutaAnterior = '../firmas/' . $filaBuscar['firma_cliente'];
    if (file_exists($rutaAnterior)) {
        unlink($rutaAnterior);
    }
}

/* ==============================
   2️⃣ PROCESAR NUEVA FIRMA
============================== */

$firma = str_replace('data:image/png;base64,', '', $firma);
$firma = str_replace(' ', '+', $firma);
$data = base64_decode($firma);

/* ==============================
   3️⃣ CREAR NOMBRE ÚNICO
============================== */

$nombreArchivo = 'firma_' . $id_credito . '_' . time() . '.png';
$ruta = '../firmas/' . $nombreArchivo;

file_put_contents($ruta, $data);

/* ==============================
   4️⃣ GUARDAR EVIDENCIA
============================== */

$ip = $_SERVER['REMOTE_ADDR'];
$fecha = date("Y-m-d H:i:s");

$sql = "UPDATE prestamos
        SET firma_cliente='$nombreArchivo',
            ip_firma='$ip',
            fecha_firma='$fecha'
        WHERE id='$id_credito'";

mysqli_query($conexion, $sql);

echo "ok";
?>