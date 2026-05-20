<?php
include("../includes/db.php");

// 🔹 HEADERS PARA DESCARGA EXCEL
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=inversionistas.xls");
header("Pragma: no-cache");
header("Expires: 0");

// 🔹 CONSULTA CON SALDO
$sql = "
SELECT 
    i.nombre,
    i.documento,
    i.telefono,
    i.email,
    IFNULL(SUM(
        CASE 
            WHEN UPPER(m.tipo)='APORTE' THEN m.valor
            ELSE -m.valor
        END
    ),0) as saldo
FROM inversionistas i
LEFT JOIN movimientos_inversionista m 
    ON m.id_inversionista = i.id
GROUP BY i.id
";

$res = mysqli_query($conexion, $sql);

// 🔹 TABLA EXCEL
echo "<table border='1'>";
echo "<tr>
        <th>Nombre</th>
        <th>Documento</th>
        <th>Teléfono</th>
        <th>Email</th>
        <th>Saldo</th>
      </tr>";

while($row = mysqli_fetch_assoc($res)){

    echo "<tr>
        <td>{$row['nombre']}</td>
        <td>{$row['documento']}</td>
        <td>{$row['telefono']}</td>
        <td>{$row['email']}</td>
        <td>{$row['saldo']}</td>
    </tr>";
}

echo "</table>";