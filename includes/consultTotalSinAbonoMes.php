<?php
// obtener_ganancias.php
date_default_timezone_set('America/Mexico_City');
if (isset($_POST['year'])) {
    $year = $_POST['year'];
    // Realizar la consulta para obtener las ganancias acumuladas del año
    include "db.php";
    $SQL = "SELECT SUM(monto) as total_ganancias FROM cuotas_prestamo WHERE YEAR(fecha_pago) = $year AND estado = 'Pendiente'";
    $consulta = mysqli_query($conexion, $SQL);

    $totalGanancias = 0;
    if ($resultado = mysqli_fetch_assoc($consulta)) {
        $totalGanancias = $resultado['total_ganancias'];
    }

    echo json_encode(array('total_ganancias' => $totalGanancias));
} else {
    echo "Error: No se proporcionó el año.";
}
