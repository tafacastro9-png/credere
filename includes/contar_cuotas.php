<?php

require_once("db.php");
date_default_timezone_set('America/Mexico_City');
$hoy = date('Y-m-d');
$SQL = "SELECT id,fecha_pagado, sum(monto) as monto FROM cuotas_prestamo WHERE fecha_pagado = '$hoy' AND estado = 'Pagado' ";
$dato = mysqli_query($conexion, $SQL);
if ($dato->num_rows > 0) {
    while ($fila = mysqli_fetch_array($dato)) {

        echo '$' . number_format($fila['monto'], 2);
    }
}
