<?php
include "../includes/db.php";
$sql = "SELECT  u.usuario, u.id_rol, u.imagenPerfil, r.rol FROM users u 
LEFT JOIN roles r ON u.id_rol= r.id  WHERE usuario ='$usuario'";
$usuarios = mysqli_query($conexion, $sql);
if ($usuarios->num_rows > 0) {
    foreach ($usuarios as $key => $user) {
        $ruta_imagen = $user["imagenPerfil"];
    }
}
