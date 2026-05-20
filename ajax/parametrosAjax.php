<?php
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once(__DIR__ . "/../includes/db.php");

// 🔥 UTF-8
mysqli_set_charset($conexion, "utf8");

// ============================
// 🔹 ACCIÓN
// ============================
$accion = $_REQUEST['accion'] ?? 'listar';


// ============================
// 🔹 GUARDAR
// ============================
if($accion === "guardar"){

    $id = intval($_POST['id'] ?? 0);
    $valor = $_POST['valor'] ?? 0;

    // 🔒 Validación ID
    if($id <= 0){
        ob_clean();
        echo json_encode(["error" => "ID inválido"]);
        exit;
    }

    // 🔥 LIMPIEZA REAL DEL VALOR
    // 1. quitar espacios
   $valor = trim($_POST['valor']);
$valor = str_replace(',', '.', $valor);

if(!is_numeric($valor)){
    echo json_encode(["error" => "Valor inválido"]);
    exit;
}

// 🔥 TRUNCAR (NO REDONDEAR)
$valor = (float)$valor;
$valor = floor($valor * 100) / 100;

    // 🔥 QUERY SEGURA
    $sql = "
        UPDATE parametros 
        SET valor = '$valor'
        WHERE id = '$id'
    ";

    $q = mysqli_query($conexion, $sql);

    if(!$q){
        ob_clean();
        echo json_encode([
            "error" => mysqli_error($conexion)
        ]);
        exit;
    }

    ob_clean();
    echo json_encode([
        "ok" => true,
        "valor_guardado" => $valor
    ]);
    exit;
}


// ============================
// 🔹 LISTAR
// ============================
$sql = "
    SELECT 
        id,
        nombre,
        descripcion,
        valor
    FROM parametros
    ORDER BY id ASC
";

$q = mysqli_query($conexion, $sql);

if(!$q){
    ob_clean();
    echo json_encode([
        "error" => mysqli_error($conexion)
    ]);
    exit;
}

$data = [];

while($row = mysqli_fetch_assoc($q)){
    $data[] = [
        "id" => (int)$row['id'],
        "nombre" => $row['nombre'],
        "descripcion" => $row['descripcion'],
        "valor" => (float)$row['valor'] // 🔥 importante
    ];
}

// 🔥 LIMPIEZA TOTAL
ob_clean();

header('Content-Type: application/json; charset=utf-8');

echo json_encode($data, JSON_UNESCAPED_UNICODE);

exit;