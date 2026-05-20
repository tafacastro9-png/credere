<?php
session_start();
require_once("db.php");

header('Content-Type: application/json');

$id_cliente = intval($_POST['id_cliente'] ?? 0);
$id_prestamo = intval($_POST['id_prestamo'] ?? 0);
$tipo = trim($_POST['tipo'] ?? '');
$resultado = trim($_POST['resultado'] ?? '');
$observacion = trim($_POST['observacion'] ?? '');
$fecha_promesa = $_POST['fecha_promesa'] ?? null;
$valor_promesa = $_POST['valor_promesa'] ?? null;
$proxima_gestion = $_POST['proxima_gestion'] ?? null;

$usuario = $_SESSION['usuario'];

if($id_cliente <= 0 || $id_prestamo <= 0){

    echo json_encode([
        'success' => false,
        'message' => 'Datos inválidos'
    ]);

    exit;
}

$insert = mysqli_query($conexion, "

INSERT INTO gestion_cartera(

    id_cliente,
    id_prestamo,
    tipo_gestion,
    resultado,
    observacion,
    fecha,
    usuario,
    seguimiento_activo,
    estado_seguimiento,
	fecha_promesa,
	valor_promesa,
	proxima_gestion

)

VALUES(

    $id_cliente,
    $id_prestamo,
    '$tipo',
    '$resultado',
    '$observacion',
    NOW(),
    '$usuario',
    1,
    'ACTIVO',
	'$fecha_promesa',
	'$valor_promesa',
	'$proxima_gestion'
	

)

");

if($insert){

    echo json_encode([
        'success' => true
    ]);

}else{

    echo json_encode([
        'success' => false,
        'message' => 'Error al guardar'
    ]);
}