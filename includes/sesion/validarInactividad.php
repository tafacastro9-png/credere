<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'].'/includes/db.php';

echo "<script>console.log('ENTRO VALIDAR');</script>";

if(!isset($conexion)){
    echo "<script>console.log('NO EXISTE CONEXION');</script>";
}else{
    echo "<script>console.log('SI EXISTE CONEXION');</script>";
}

// =========================================
// OBTENER TIEMPO DESDE BD
// =========================================

$query = mysqli_query(
    $conexion,
    "SELECT valor
     FROM parametrosinternos
     WHERE nombre = 'tiempoInactividad'
     LIMIT 1"
);

$tiempo = 10;

if($query && mysqli_num_rows($query) > 0){

    $row = mysqli_fetch_assoc($query);

    $tiempo = (int)$row['valor'];
}

// =========================================
// PASAR A JAVASCRIPT
// =========================================

$tiempoMilisegundos =
$tiempo * 60 * 1000;

echo "<script>console.log('TIEMPO DEBUG:', ".$tiempoMilisegundos.");</script>";

?>

<script>

let tiempoInactividad =
<?php echo $tiempoMilisegundos; ?>;

console.log("TIEMPO INACTIVIDAD:", tiempoInactividad);

let temporizador;

// =========================================
// REINICIAR TIMER
// =========================================

function reiniciarTemporizador(){

    clearTimeout(temporizador);

    temporizador = setTimeout(function(){

        window.location.href =
        '/includes/sesion/cerrarSesion.php?inactividad=1';

    }, tiempoInactividad);
}

// =========================================
// EVENTOS ACTIVIDAD
// =========================================

document.addEventListener('mousemove', reiniciarTemporizador);
document.addEventListener('keypress', reiniciarTemporizador);
document.addEventListener('click', reiniciarTemporizador);
document.addEventListener('scroll', reiniciarTemporizador);

// =========================================
// INICIAR
// =========================================

reiniciarTemporizador();

</script>