<?php

require_once("db.php");

if(isset($_POST['id'])){

    $id = intval($_POST['id']);

    $estado = $_POST['estado'];

    mysqli_query($conexion, "

        UPDATE documentos_prestamo

        SET estado = '$estado'

        WHERE id = $id

    ");

    echo "Estado actualizado";
}
?>