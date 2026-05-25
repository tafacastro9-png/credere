<?php
include_once("db.php");
date_default_timezone_set('America/Bogota');
$lunes = date('Y-m-d', strtotime('monday this week'));
$domingo = date('Y-m-d', strtotime('sunday this week'));

$sql = "SELECT p.*, c.folioClient, c.nombreClient, c.apellidoClient,a.nombreAval,a.apellidoAval,tp.nombre_tipo,
ep.statusPrest, dp.total_pagar,dp.num_cuotas,dp.monto_cuota,dp.frecuencia_pago,dp.multa_mora FROM prestamos p INNER JOIN clientes c 
ON p.id_cliente = c.id INNER JOIN avales a ON p.id_aval = a.id 
INNER JOIN tipo_prestamo tp ON p.id_tp = tp.id INNER JOIN estado_prestamo ep ON p.id_estp = ep.id INNER JOIN detalle_prestamo dp
ON dp.id_prestamo = p.id WHERE p.id_estp IN ('1') AND DATE(p.fechaRegistro) BETWEEN '$lunes' AND '$domingo'";

$result = mysqli_query($conexion, $sql);
if (!$result) {
    die("Error en verificarNotification: " . mysqli_error($conexion));
}

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<li>
                <a href="#0">
                    <div class="image">
                        <img src="/images/undraw_profile.svg" alt="" />
                    </div>
                    <div class="content">
                        <h6>' . $row['nombreClient'] . ' ' . $row['apellidoClient'] . '
                            <span class="text-regular">
                                solicitó un préstamo (' . $row['statusPrest'] . ')
                            </span>
                        </h6>
                        <p>Folio: <b>' . $row['folioClient'] . '</b></p>
                        <span>' . date("d-m-Y", strtotime($row['fechaRegistro'])) . '</span>
                    </div>
                </a>
            </li>
            <br>
             <a href="/views/notificaciones.php" class="text-primary"><center>Ver todas las notificaciones</center></a>
            ';
    }
} else {
    echo '<li class="text-center p-2">No hay nuevas solicitudes de préstamo.</li>';
}
