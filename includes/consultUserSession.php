<?php

include "../includes/db.php";

$usuarioSesion = $_SESSION['usuario'];

$sql = "SELECT 
u.usuario, 
u.id_rol, 
u.imagenPerfil, 
r.rol 
FROM users u
LEFT JOIN roles r ON u.id_rol = r.id
WHERE u.usuario = '$usuarioSesion'
LIMIT 1";

$resultadoUsuario = mysqli_query($conexion, $sql);

$datosUsuario = mysqli_fetch_assoc($resultadoUsuario);

$ruta_imagen = $datosUsuario['imagenPerfil'] ?? '../images/user.png';

$nombre_usuario = $datosUsuario['usuario'] ?? '';

$rol_usuario = $datosUsuario['rol'] ?? '';