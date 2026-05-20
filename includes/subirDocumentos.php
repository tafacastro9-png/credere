<?php

require_once("db.php");

session_start();

date_default_timezone_set('America/Bogota');

if(isset($_POST['prestamo_id'])){

    $idPrestamo = intval($_POST['prestamo_id']);

    $anio = date("Y");
    $mes  = date("m");
    $dia  = date("d");

    // 📂 Ruta base
    $rutaBase = "../documentos/";

    // =========================================
    // FUNCIÓN PARA SUBIR ARCHIVOS
    // =========================================

    function subirDocumentos(
        $files,
        $carpetaTipo,
        $rutaBase,
        $anio,
        $mes,
        $dia,
        $idPrestamo,
        $conexion
    ){

        // 📁 Ruta final
        $rutaFinalCarpeta =
            $rutaBase .
            $carpetaTipo . "/" .
            $anio . "/" .
            $mes . "/" .
            $dia;

        // 📁 Crear carpetas
        if(!file_exists($rutaFinalCarpeta)){

            mkdir($rutaFinalCarpeta, 0777, true);
        }

        // =========================================
        // ELIMINAR DOCUMENTOS RECHAZADOS
        // =========================================

        mysqli_query($conexion, "

            DELETE FROM documentos_prestamo

            WHERE id_prestamo = $idPrestamo
            AND tipo_documento = '$carpetaTipo'
            AND estado = 'Rechazado'

        ");

        // 🔁 Recorrer archivos
        foreach($files['tmp_name'] as $key => $tmp_name){

            if($files['error'][$key] == 0){

                $nombreOriginal = basename($files['name'][$key]);

                // 🔹 Insertar primero para obtener ID
                mysqli_query($conexion, "

                    INSERT INTO documentos_prestamo (

                        id_prestamo,
                        tipo_documento,
                        nombre_archivo,
                        estado,
                        fecha_subida

                    ) VALUES (

                        $idPrestamo,
                        '$carpetaTipo',
                        '',
                        'Pendiente',
                        NOW()

                    )

                ");

                $idDocumento = mysqli_insert_id($conexion);

                // 🔹 Nuevo nombre
                $nuevoNombre =
                    $idDocumento . "_" . $nombreOriginal;

                // 📄 Ruta física
                $rutaArchivo =
                    $rutaFinalCarpeta . "/" . $nuevoNombre;

                // 🔹 Mover archivo
                if(move_uploaded_file($tmp_name, $rutaArchivo)){

                    // 🔹 Ruta para BD
                    $rutaGuardar =
                        $carpetaTipo . "/" .
                        $anio . "/" .
                        $mes . "/" .
                        $dia . "/" .
                        $nuevoNombre;

                    mysqli_query($conexion, "

                        UPDATE documentos_prestamo

                        SET nombre_archivo = '$rutaGuardar'

                        WHERE id = $idDocumento

                    ");
                }
            }
        }
    }

    // =========================================
    // DOCUMENTOS DEL CRÉDITO
    // =========================================

    if(
        isset($_FILES['documentos_credito']) &&
        !empty($_FILES['documentos_credito']['name'][0])
    ){

        subirDocumentos(
            $_FILES['documentos_credito'],
            'Documentos del Crédito',
            $rutaBase,
            $anio,
            $mes,
            $dia,
            $idPrestamo,
            $conexion
        );
    }

    // =========================================
    // DOCUMENTOS IDENTIDAD
    // =========================================

    if(
        isset($_FILES['documentos_identidad']) &&
        !empty($_FILES['documentos_identidad']['name'][0])
    ){

        subirDocumentos(
            $_FILES['documentos_identidad'],
            'Documentos de Identidad',
            $rutaBase,
            $anio,
            $mes,
            $dia,
            $idPrestamo,
            $conexion
        );
    }

    // =========================================
    // OTROS DOCUMENTOS
    // =========================================

    if(
        isset($_FILES['otros_documentos']) &&
        !empty($_FILES['otros_documentos']['name'][0])
    ){

        subirDocumentos(
            $_FILES['otros_documentos'],
            'Otros',
            $rutaBase,
            $anio,
            $mes,
            $dia,
            $idPrestamo,
            $conexion
        );
    }

    // =========================================
    // CAMBIAR A EN REVISIÓN
    // =========================================

    mysqli_query($conexion, "

        UPDATE prestamos

        SET id_estp = 2

        WHERE id = $idPrestamo

    ");

    header("Location: ../views/prestamos.php?msg=subido");

    exit();
}
?>