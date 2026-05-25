<?php

require_once("db.php");

if(isset($_GET['id'])){

    $idPrestamo = intval($_GET['id']);

    // =========================================
    // TRAER SOLO EL ÚLTIMO DOCUMENTO
    // DE CADA TIPO
    // =========================================

    $query = mysqli_query($conexion, "

        SELECT dp.*

        FROM documentos_prestamo dp

        INNER JOIN (

            SELECT
                tipo_documento,
                MAX(id) as ultimo_id

            FROM documentos_prestamo

            WHERE id_prestamo = $idPrestamo

            GROUP BY tipo_documento

        ) ultimos

        ON dp.id = ultimos.ultimo_id

    ");

    $html = '';

    while($doc = mysqli_fetch_assoc($query)){

        $ruta = "../includes/ver_documento.php?archivo=" . urlencode($doc['nombre_archivo']);

        // =========================================
        // ICONO SEGÚN ESTADO
        // =========================================

        $icono = "⏳";

        if($doc['estado'] == 'Aprobado'){
            $icono = "✅";
        }

        if($doc['estado'] == 'Rechazado'){
            $icono = "❌";
        }

        $html .= "

        <div class='mb-2'>

            <button
                class='btn btn-outline-primary w-100 text-start'

                onclick=\"verDocumentoPanel('$ruta', {$doc['id']})\">

                $icono {$doc['tipo_documento']}

            </button>

        </div>

        ";

    }

    echo $html;
}
?>