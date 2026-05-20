<?php include "../includes/header.php"; ?>
<?php
require_once("../includes/db.php");

$id_prestamo = $_GET['id'];
$query = mysqli_query($conexion, "SELECT p.*, c.nombreClient, c.apellidoClient, dp.* 
    FROM prestamos p 
    INNER JOIN clientes c ON p.id_cliente = c.id 
    INNER JOIN detalle_prestamo dp ON dp.id_prestamo = p.id 
    WHERE p.id = $id_prestamo");

$prestamo = mysqli_fetch_assoc($query);

// Parámetros para generar cronograma
$fecha_inicio = $prestamo['fecha_inicio'];
$cuotas = $prestamo['num_cuotas'];
$monto_cuota = $prestamo['monto_cuota'];
$frecuencia = $prestamo['frecuencia_pago'];
?>
<div class="container mt-4">
    <h3 class="text-center">Cronograma de Pagos</h3>
    <p><strong>Cliente:</strong> <?= $prestamo['nombreClient'] . " " . $prestamo['apellidoClient'] ?></p>
    <p><strong>Fecha Inicio:</strong> <?= $fecha_inicio ?></p>
    <p><strong>Frecuencia:</strong> <?= ucfirst($frecuencia) ?></p>
    <p><strong>Cuotas Totales:</strong> <?= $cuotas ?></p>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Fecha de Pago</th>
                <th>Monto</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $fecha = new DateTime($fecha_inicio);
            for ($i = 1; $i <= $cuotas; $i++) {
                $fecha_pago = $fecha->format('Y-m-d');

                echo "<tr>
                        <td>$i</td>
                        <td>$fecha_pago</td>
                        <td>$$monto_cuota</td>
                        <td>Pendiente</td>
                        <td><button class='btn btn-sm btn-success'>Marcar Pagado</button></td>
                    </tr>";

                // Avanzar a la siguiente fecha
                switch ($frecuencia) {
                    case 'mensual': $fecha->modify('+1 month'); break;
                    case 'quincenal': $fecha->modify('+15 days'); break;
                    case 'semanal': $fecha->modify('+7 days'); break;
                    default: $fecha->modify('+1 month'); break;
                }
            }
            ?>
        </tbody>
    </table>
</div>
<?php include "../includes/footer.php"; ?>
