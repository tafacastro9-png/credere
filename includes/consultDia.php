<?php
include "db.php";

date_default_timezone_set('America/Mexico_City');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $diasSemana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
    $pagosPorDia = array_fill(0, 7, 0);

    // Obtener la fecha de hoy y calcular el inicio de la semana
    $hoy = date('Y-m-d');
    $inicioSemana = date('Y-m-d', strtotime('monday this week', strtotime($hoy)));
    $finSemana = date('Y-m-d', strtotime('sunday this week', strtotime($hoy)));
    // Consulta para obtener los pagos de la semana
    $SQL = "SELECT DAYOFWEEK(fecha_pagado) as dia, SUM(monto) as total FROM cuotas_prestamo WHERE DATE(fecha_pagado) BETWEEN '$inicioSemana' AND '$finSemana' GROUP BY DAYOFWEEK(fecha_pagado)";
    $resultado = mysqli_query($conexion, $SQL);

    while ($fila = mysqli_fetch_assoc($resultado)) {
        // Ajustar el índice para que lunes sea el primer día
        $indiceDia = ($fila['dia'] + 5) % 7; // Mapea Domingo (1) a índice 6, Lunes (2) a índice 0, etc.
        $pagosPorDia[$indiceDia] = $fila['total'];
    }


    echo json_encode([
        'labels' => $diasSemana,
        'data' => $pagosPorDia
    ]);
}
