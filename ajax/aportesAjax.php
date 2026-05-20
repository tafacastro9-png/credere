<?php
include("../includes/db.php");

// GUARDAR
if($_POST){

    $id_inv = $_POST['id_inversionista'];
    $valor = $_POST['valor'];
    $tipo = $_POST['tipo'];

    mysqli_query($conexion,"
        INSERT INTO aportes_inversionistas
        (id_inversionista,valor,tipo,fecha)
        VALUES('$id_inv','$valor','$tipo',NOW())
    ");
}

// LISTAR
if(isset($_GET['id'])){

    $id = $_GET['id'];

    $r = mysqli_query($conexion,"
        SELECT * FROM aportes_inversionistas
        WHERE id_inversionista='$id'
        ORDER BY fecha DESC
    ");

    while($row = mysqli_fetch_assoc($r)){

        echo "<tr>
            <td>{$row['fecha']}</td>
            <td>{$row['tipo']}</td>
            <td>$ ".number_format($row['valor'],0,',','.')."</td>
        </tr>";
    }
}