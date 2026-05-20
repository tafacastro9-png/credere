<?php
session_start();
sleep(1);
include_once "db.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Validar y convertir fechas
$star = isset($_POST['star']) ? date("Y-m-d", strtotime($_POST['star'])) : '';
$fin  = isset($_POST['fin']) ? date("Y-m-d", strtotime($_POST['fin'])) : '';

if (!$star || !$fin) {
    exit;
}

// Consulta con filtro de fechas
$consulta = " SELECT p.*, c.folioClient, c.nombreClient, c.apellidoClient,a.nombreAval, a.apellidoAval, tp.nombre_tipo,
ep.statusPrest, dp.total_pagar, dp.num_cuotas,dp.monto_cuota, dp.frecuencia_pago, dp.multa_mora FROM prestamos p
INNER JOIN clientes c ON p.id_cliente = c.id INNER JOIN avales a ON p.id_aval = a.id INNER JOIN tipo_prestamo tp ON p.id_tp = tp.id
INNER JOIN estado_prestamo ep ON p.id_estp = ep.id INNER JOIN detalle_prestamo dp ON dp.id_prestamo = p.id WHERE p.id_estp = 5
AND DATE(p.fecha_inicio) BETWEEN '$star' AND '$fin'";

$resultado = mysqli_query($conexion, $consulta);

if (!$resultado || mysqli_num_rows($resultado) == 0) {
    exit; // No devuelve nada, lo que activará el "No hay registros" en JS
}

while ($fila = mysqli_fetch_assoc($resultado)) {
    $encrypted_id = base64_encode($fila['id']);
?>
    <tr>
        <td><?php echo $fila['nombreClient'] . ' ' . $fila['apellidoClient']; ?></td>
        <td><?php echo $fila['nombreAval'] . ' ' . $fila['apellidoAval']; ?></td>
        <td><?php echo $fila['nombre_tipo']; ?></td>
        <td><?php echo '$' . number_format($fila['monto_prestado'], 2); ?></td>
        <td><?php echo $fila['fecha_inicio']; ?></td>
        <td><?php echo $fila['fecha_vencimiento']; ?></td>
        <td><span class="badge bg-success"><?php echo $fila['statusPrest']; ?></span></td>
        <td><?php echo $fila['fechaRegistro']; ?></td>
        <td>
            <a href="ver_cronograma.php?id=<?php echo $encrypted_id; ?>" class="btn btn-warning">
                <i class="fa fa-calendar-check"></i>
            </a>
        </td>
    </tr>
<?php
}
?>