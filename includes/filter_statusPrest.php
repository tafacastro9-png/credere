<?php
session_start();
require_once("db.php");

$id_estp = isset($_POST['id_estp']) ? intval($_POST['id_estp']) : 0;

$sql = "SELECT p.*, c.folioClient, c.nombreClient, c.apellidoClient, a.nombreAval, a.apellidoAval,
               tp.nombre_tipo, ep.statusPrest, dp.total_pagar, dp.num_cuotas, dp.monto_cuota,
               dp.frecuencia_pago, dp.multa_mora 
        FROM prestamos p
        INNER JOIN clientes c ON p.id_cliente = c.id
        INNER JOIN avales a ON p.id_aval = a.id
        INNER JOIN tipo_prestamo tp ON p.id_tp = tp.id
        INNER JOIN estado_prestamo ep ON p.id_estp = ep.id
        INNER JOIN detalle_prestamo dp ON dp.id_prestamo = p.id";

if ($id_estp != 0) {
    $sql .= " WHERE p.id_estp = $id_estp";
} else {
    $sql .= " WHERE p.id_estp IN (2, 3, 4)";
}

$result = mysqli_query($conexion, $sql);

while ($fila = mysqli_fetch_assoc($result)) {
    $badgeClass = '';

    switch ($fila['id_estp']) {
        case 2:
            $badgeClass = 'bg-info';
            break;
        case 3:
            $badgeClass = 'bg-warning';
            break;
        case 4:
            $badgeClass = 'bg-danger';
            break;
        default:
            $badgeClass = 'bg-secondary';
            break;
    }

    echo "<tr>
    <td>{$fila['folioPrest']}</td>
    <td>{$fila['nombreClient']} {$fila['apellidoClient']}</td>
    <td>{$fila['nombreAval']} {$fila['apellidoAval']}</td>
    <td>{$fila['nombre_tipo']}</td>
    <td>\${$fila['monto_prestado']}</td>
    <td>{$fila['fecha_inicio']}</td>
    <td>{$fila['fecha_vencimiento']}</td>
    <td><span class='badge {$badgeClass}'>{$fila['statusPrest']}</span></td>
    <td>{$fila['fechaRegistro']}</td>
    <td>
        <button type='button' class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#editar{$fila['id']}'>
            <i class='fa fa-edit'></i>
        </button>
        <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#editarDPrest{$fila['id']}'>
            <i class='fa fa-info-circle'></i>
</button>";
    if ($_SESSION['type'] == 1 || $_SESSION['type'] == 3) {
        echo "<a href='../includes/delete_typePrest.php?id={$fila['id']}' class='btn btn-danger btn-del'>
                <i class='fa fa-trash'></i>
              </a>";
    }

    echo "</td></tr>";
}
