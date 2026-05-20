<?php
require_once("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = intval($_POST['id']);

    // Revertir cuota a pendiente
    $update = mysqli_query($conexion, "
        UPDATE cuotas_prestamo 
        SET estado = 'pendiente',
            fecha_pagado = NULL
        WHERE id = $id
    ");

    if ($update) {

        // Obtener el préstamo asociado
        $cuota = mysqli_fetch_assoc(mysqli_query($conexion, "
            SELECT id_prestamo 
            FROM cuotas_prestamo 
            WHERE id = $id
        "));

        $id_prestamo = $cuota['id_prestamo'];

        // Si se revierte una cuota, el préstamo deja de estar finalizado
        mysqli_query($conexion, "
            UPDATE prestamos 
            SET id_estp = 6
            WHERE id = $id_prestamo
        ");

        echo json_encode(['success' => true]);

    } else {

        echo json_encode([
            'success' => false,
            'message' => 'Error al revertir cuota.'
        ]);
    }
}