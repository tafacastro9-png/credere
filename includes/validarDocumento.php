<?php

require_once("db.php");

session_start();

// ======================================
// VALIDAR DATOS
// ======================================

if(
    !isset($_POST['id']) ||
    !isset($_POST['accion'])
){
    die("Datos incompletos");
}

$idDocumento = intval($_POST['id']);
$accion = $_POST['accion'];

// ======================================
// APROBAR DOCUMENTO
// ======================================

if($accion == "aprobar"){

    mysqli_query($conexion, "

        UPDATE documentos_prestamo

        SET estado = 'Aprobado'

        WHERE id = $idDocumento

    ");

    echo "Documento aprobado";

    exit;
}

// ======================================
// RECHAZAR DOCUMENTO
// ======================================

if($accion == "rechazar"){

    if(empty($_SESSION['id_usuario'])){
        die("Sesión no válida");
    }

    $motivo = mysqli_real_escape_string(
        $conexion,
        $_POST['motivo'] ?? ''
    );

    $idUsuario = intval($_SESSION['id_usuario']);

    // ======================================
    // CAMBIAR ESTADO DOCUMENTO
    // ======================================

    mysqli_query($conexion, "

        UPDATE documentos_prestamo

        SET estado = 'Rechazado'

        WHERE id = $idDocumento

    ");

    // ======================================
    // OBTENER ID PRESTAMO
    // ======================================

    $query = mysqli_query($conexion, "

        SELECT id_prestamo

        FROM documentos_prestamo

        WHERE id = $idDocumento

    ");

    $data = mysqli_fetch_assoc($query);

    $idPrestamo = $data['id_prestamo'];

    // ======================================
    // DEVOLVER PRESTAMO A RADICADO
    // ======================================

    mysqli_query($conexion, "

        UPDATE prestamos

        SET id_estp = 1

        WHERE id = $idPrestamo

    ");

    // ======================================
    // GUARDAR HISTORIAL DE RECHAZO
    // ======================================

    mysqli_query($conexion, "

        INSERT INTO rechazo_documentos (

            id_prestamo,
            motivo,
            fecha,
            id_usuario

        ) VALUES (

            $idPrestamo,
            '$motivo',
            NOW(),
            $idUsuario

        )

    ");

    echo "Documento rechazado";

    exit;
}

?>