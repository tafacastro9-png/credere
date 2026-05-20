<?php
require_once("db.php");

$busqueda = mysqli_real_escape_string($conexion, $_POST['consulta']);

$sql = "SELECT p.*, c.folioClient, c.nombreClient, c.apellidoClient, a.nombreAval, a.apellidoAval, tp.nombre_tipo,
        ep.statusPrest, dp.total_pagar, dp.num_cuotas, dp.monto_cuota, dp.frecuencia_pago, dp.multa_mora 
        FROM prestamos p 
        INNER JOIN clientes c ON p.id_cliente = c.id 
        INNER JOIN avales a ON p.id_aval = a.id 
        INNER JOIN tipo_prestamo tp ON p.id_tp = tp.id 
        INNER JOIN estado_prestamo ep ON p.id_estp = ep.id 
        INNER JOIN detalle_prestamo dp ON dp.id_prestamo = p.id 
        WHERE p.id_estp = 6 AND (
            p.folioPrest LIKE '%$busqueda%' OR 
            c.folioClient LIKE '%$busqueda%' OR 
            CONCAT(c.nombreClient, ' ', c.apellidoClient) LIKE '%$busqueda%' OR 
            CONCAT(a.nombreAval, ' ', a.apellidoAval) LIKE '%$busqueda%'
        )";

$resultado = mysqli_query($conexion, $sql);

if (mysqli_num_rows($resultado) > 0) {
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $encrypted_id = base64_encode($fila['id']);
        echo "<tr>
                <td style='color: green;'>{$fila['folioPrest']}</td>
                <td>{$fila['nombreClient']} {$fila['apellidoClient']}</td>
                <td>{$fila['nombreAval']} {$fila['apellidoAval']}</td>
                <td>{$fila['nombre_tipo']}</td>
                <td>$ {$fila['monto_prestado']}</td>
                <td>{$fila['fecha_inicio']}</td>
                <td>{$fila['fecha_vencimiento']}</td>
                <td><span class='badge bg-success'>{$fila['statusPrest']}</span></td>
                <td>{$fila['fechaRegistro']}</td>
                <td>
                    <a href='ver_cronograma.php?id={$encrypted_id}' class='btn btn-warning'><i class='fa fa-calendar-check'></i></a>
                  
                      
                    </a>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='10' class='text-center'>No se encontraron resultados.</td></tr>";
}
?>
