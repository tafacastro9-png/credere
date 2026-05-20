<?php
include("../includes/db.php");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=reporte_comisiones.xls");

echo "<table border='1'>";
echo "<tr>
<th>OPERACIÓN</th>
<th>DIA</th>
<th>MES</th>
<th>AÑO</th>
<th>CC</th>
<th>CLIENTE</th>
<th>CREDITO</th>
<th>MONTO</th>
<th>INGRESO</th>
<th>GANANCIA</th>
<th>ASESOR</th>
<th>COMISION</th>
</tr>";

// aquí reutilizas TU QUERY del reporte 3

?>