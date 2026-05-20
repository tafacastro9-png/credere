<?php

require_once(__DIR__ . '/../db.php');

header('Content-Type: application/json');

session_start();

// =========================================
// TIEMPO BLOQUEO LOGIN
// =========================================

$tiempoBloqueo = 10;

$queryTiempo = mysqli_query(

    $conexion,

    "SELECT valor
     FROM parametrosInternos
     WHERE nombre = 'tiempoBloqueoLogin'
     LIMIT 1"
);

if($queryTiempo && mysqli_num_rows($queryTiempo) > 0){

    $rowTiempo = mysqli_fetch_assoc($queryTiempo);

    $tiempoBloqueo =
    (int)$rowTiempo['valor'];
}

// =========================================
// MAX INTENTOS BLOQUEO
// =========================================

$maxIntentos = 3;

$queryIntentos = mysqli_query(

    $conexion,

    "SELECT valor
     FROM parametrosInternos
     WHERE nombre = 'intentosBloqueoUsuario'
     LIMIT 1"
);

if($queryIntentos && mysqli_num_rows($queryIntentos) > 0){

    $rowIntentos = mysqli_fetch_assoc($queryIntentos);

    $maxIntentos =
    (int)$rowIntentos['valor'];
}

// =========================================
// VALIDAR MÉTODO
// =========================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    echo json_encode([

        'response' => 'error'

    ]);

    exit();
}

// =========================================
// DATOS LOGIN
// =========================================

$usuario =
trim($_POST['usuario']);

$password =
trim($_POST['password']);

// =========================================
// BUSCAR USUARIO
// =========================================

$query_login = "

    SELECT *
    FROM users
    WHERE usuario = '$usuario'
    LIMIT 1

";

$db_consult =
mysqli_query($conexion, $query_login);

// =========================================
// USUARIO EXISTE
// =========================================

if ($db_consult && mysqli_num_rows($db_consult) > 0) {

    $fetch_result =
    mysqli_fetch_assoc($db_consult);

    $idUsuario =
    $fetch_result['id'];

    // =========================================
    // VALIDAR SI ESTÁ BLOQUEADO
    // =========================================

    if($fetch_result['bloqueado'] == 1){

        echo json_encode([

            'response' =>
            'usuario_bloqueado',

            'mensaje' =>
            'Usuario bloqueado. Por favor contacte al administrador del sistema.'

        ]);

        exit();
    }

    // =========================================
    // PASSWORD CORRECTO
    // =========================================

    if (

        password_verify(
            $password,
            $fetch_result['password']
        )

    ) {

        // =========================================
        // LIMPIAR INTENTOS
        // =========================================

        mysqli_query(

            $conexion,

            "UPDATE users
             SET intentos_fallidos = 0,
                 ultimo_intento = NULL
             WHERE id = '$idUsuario'"
        );

        unset($_SESSION['intentos_login']);

        // =========================================
        // REGENERAR SESSION
        // =========================================

        session_regenerate_id(true);

        // =========================================
        // CREAR SESIÓN
        // =========================================

        $_SESSION['id_usuario'] =
        $fetch_result['id'];

        $_SESSION['correo'] =
        $fetch_result['correo'];

        $_SESSION['usuario'] =
        $fetch_result['usuario'];

        $_SESSION['type'] =
        $fetch_result['id_rol'];

        // =========================================
        // CARGAR PERMISOS
        // =========================================

        $_SESSION['permisos'] = [];

        $queryPermisos = mysqli_query(

            $conexion,

            "SELECT p.codigo
             FROM users_permisos up
             INNER JOIN permisos p
             ON p.id = up.permiso_id
             WHERE up.user_id =
             {$fetch_result['id']}"
        );

        while($row = mysqli_fetch_assoc($queryPermisos)){

            $_SESSION['permisos'][] =
            $row['codigo'];
        }

        // =========================================
        // LOGIN EXITOSO
        // =========================================

        echo json_encode([

            'response' => 'success'

        ]);

        exit();

    } else {

        // =========================================
        // SUMAR INTENTOS EN BD
        // =========================================

        mysqli_query(

            $conexion,

            "UPDATE users
             SET intentos_fallidos = intentos_fallidos + 1,
                 ultimo_intento = NOW()
             WHERE id = '$idUsuario'"
        );

        // =========================================
        // CONSULTAR NUEVO VALOR
        // =========================================

        $queryIntentosActualizados = mysqli_query(

            $conexion,

            "SELECT intentos_fallidos
             FROM users
             WHERE id = '$idUsuario'
             LIMIT 1"
        );

        $rowIntentos =
        mysqli_fetch_assoc($queryIntentosActualizados);

        $nuevosIntentos =
        (int)$rowIntentos['intentos_fallidos'];

        // =========================================
        // BLOQUEAR USUARIO DEFINITIVO
        // =========================================

        if($nuevosIntentos >= $maxIntentos){

            mysqli_query(

                $conexion,

                "UPDATE users
                 SET bloqueado = 1
                 WHERE id = '$idUsuario'"
            );

            echo json_encode([

                'response' =>
                'usuario_bloqueado',

                'mensaje' =>
                'Usuario bloqueado. Por favor contacte al administrador del sistema.'

            ]);

            exit();
        }

        // =========================================
        // ERROR PASSWORD
        // =========================================

        echo json_encode([

            'response' => 'error',

            'intentos' =>
            $nuevosIntentos,

            'maxIntentos' =>
            $maxIntentos,

            'tiempoBloqueo' =>
            $tiempoBloqueo

        ]);

        exit();
    }

} else {

    // =========================================
// USUARIO NO EXISTE
// =========================================

if(!isset($_SESSION['intentos_login'])){

    $_SESSION['intentos_login'] = 0;
}

$_SESSION['intentos_login']++;

// =========================================
// BLOQUEO DEFINITIVO
// =========================================

if($_SESSION['intentos_login'] >= $maxIntentos){

    echo json_encode([

        'response' =>
        'usuario_bloqueado',

        'mensaje' =>
        'Usuario bloqueado. Por favor contacte al administrador del sistema.'

    ]);

    exit();
}

// =========================================
// BLOQUEO TEMPORAL
// =========================================

echo json_encode([

    'response' =>
    'bloqueado_temporal',

    'segundos' =>
    $tiempoBloqueo,

    'intentos' =>
    $_SESSION['intentos_login'],

    'maxIntentos' =>
    $maxIntentos

]);

exit();
}

?>