<?php

// obtener_datos.php
date_default_timezone_set('America/Mexico_City');
if (isset($_POST['year'])) {
    $year = $_POST['year'];
    // Realizar la consulta para obtener los datos del año seleccionado
    include "db.php";
    $SQL = "SELECT MONTH(fecha_pagado) as mes, SUM(monto) as total_mes FROM cuotas_prestamo 
    WHERE YEAR(fecha_pagado) = $year AND estado = 'Pagado' GROUP BY MONTH(fecha_pagado)";
    $consulta = mysqli_query($conexion, $SQL);

    $data = array();
    // Inicializar el array con ceros para todos los meses del año
    for ($mes = 1; $mes <= 12; $mes++) {
        $data[$mes - 1] = 0;
    }

    while ($resultado = mysqli_fetch_assoc($consulta)) {
        $mes = (int) $resultado['mes'];
        $total_mes = (float) $resultado['total_mes'];
        // Asignar el total de ganancias al mes correspondiente en el array
        $data[$mes - 1] = $total_mes;
    }

    $response = array(
        "data" => $data,
        "has_ganancias" => (array_sum($data) > 0) // Verificar si hay ganancias sumando todos los elementos del array
    );

    echo json_encode($response);
} else {
    echo "Error: No se proporcionó el año.";
}
