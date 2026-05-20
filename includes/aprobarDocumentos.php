<?php
require_once("../includes/db.php");

if(isset($_GET['id'])){
    $id = intval($_GET['id']);

    mysqli_query($conexion, "
        UPDATE prestamos 
        SET id_estp = 3 
        WHERE id = $id
    ");

    header("Location: ../views/prestamos.php?mensaje=aprobado");
    exit;
}
?>
