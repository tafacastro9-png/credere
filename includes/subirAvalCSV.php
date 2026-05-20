<?php

include_once 'db.php';

$response = array();

// Extensiones permitidas
$csvtipos = array('application/vnd.ms-excel', 'application/vnd.msexcel', 'text/csv', 'application/csv', 'text/plain');

// Validar si se subió el archivo y el tipo es válido
if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvtipos)) {

    // Validar si el archivo se subió correctamente
    if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {

        // Obtener la ubicación temporal del archivo subido
        $tmpFile = $_FILES['file']['tmp_name'];

        // Se abre, lee y carga el archivo CSV
        if (($csvFile = fopen($tmpFile, 'r')) !== FALSE) {
            // Saltar la primera línea (encabezado)
            fgetcsv($csvFile);
            // Contadores para estadísticas
            $insertados = 0;
            $actualizados = 0;
            $ignorados = 0;

            // Revisar los datos línea por línea
            while (($line = fgetcsv($csvFile)) !== FALSE) {
                // Validar que la línea no esté vacía (ni toda nula)
                if (count($line) > 1 && !empty($line[0])) {
                    // Obtener los datos de la fila
                    $folioAval = !empty($line[0]) ? $line[0] : null;
					$id_tiporeferencia = !empty($line[1]) ? $line[1] : null;
                    $nombreAval = !empty($line[2]) ? $line[2] : null;
					$apellidoAval = !empty($line[3]) ? $line[3] : null;
					$id_tipoidentificacion	 = !empty($line[4]) ? $line[4] : null;	
					$docIdentAval = !empty($line[5]) ? $line[5] : null;
                    $telAval = !empty($line[6]) ? $line[6] : null;
                    $correoAval = !empty($line[7]) ? $line[7] : null;
                    $dirAval = !empty($line[8]) ? $line[8] : null;
				
					
                    /*  if (empty($fecha_star)) {
                        $fecha_star = '0000-00-00'; // Valor por defecto si está vacío
                    }*/

                    date_default_timezone_set('America/Bogota');
                    $fecha_actual = date('Y-m-d');
                    $datetime = date("Y-m-d H:i:s");
                    $id_status = '1';
                    // Consultar si el código ya existe
                    // Verificar si ya existe por correo o documento
                    $consulta = "SELECT id FROM avales WHERE docIdentAval = '$docIdentAval' OR correoAval = '$correoAval'";
                    $resultado = $conexion->query($consulta);

                    if ($resultado->num_rows > 0) {
                        // Actualizar datos
                        $conexion->query("UPDATE avales SET folioAval = '$folioAval',id_tiporeferencia = '$id_tiporeferencia' ,nombreAval = '$nombreAval', apellidoAval = '$apellidoAval', 
                        id_tipoidentificacion = '$id_tipoidentificacion',docIdentAval = '$docIdentAval', telAval = '$telAval',correoAval = '$correoAval', dirAval = '$dirAval', 
                        fecha_registro = '$datetime' WHERE folioAval = '$folioAval'");
                        $actualizados++;
                        continue;
                    } else {
                        // Insertar datos en la base de datos
                        $conexion->query("INSERT INTO avales (folioAval,id_tiporeferencia, nombreAval, apellidoAval,id_tipoidentificacion ,docIdentAval, telAval, 
                        correoAval, dirAval,id_status, fecha_registro)  VALUES ('$folioAval','$id_tiporeferencia','$nombreAval','$apellidoAval','$id_tipoidentificacion','$docIdentAval',
                        '$telAval','$correoAval','$dirAval','$id_status', '$datetime')");
                        $insertados++;
                    }
                } else {
                    $ignorados++;
                }
            }

            // Cerrar el archivo CSV
            fclose($csvFile);
            // Respuesta final más detallada
            $response['status'] = 'success';
            $response['message'] = "Carga completada.<br><strong>Insertados:</strong> $insertados<br><strong>Actualizados:</strong> $actualizados<br><strong>Ignorados:</strong> $ignorados (vacíos o repetidos)";
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error al leer el archivo CSV.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error al subir el archivo.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Archivo inválido. Por favor, suba un archivo CSV.';
}

header('Content-Type: application/json');
echo json_encode($response);
