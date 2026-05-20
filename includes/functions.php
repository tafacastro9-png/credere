

<?php
header('Content-Type: application/json');
date_default_timezone_set('America/Bogota');
require_once "db.php";


ini_set('display_errors', 1);
error_reporting(E_ALL);


//Archivo de funciones 

if (isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        //casos de registros
        case 'SaveUser':
            SaveUser();
            break;

        case 'SaveClient':
            SaveClient();
            break;

        case 'SaveAval':
            SaveAval();
            break;

        case 'SaveTypePrest':
            SaveTypePrest();
            break;

        case 'editUser':
            editUser();
            break;

        case 'editTypePrest':
            editTypePrest();
            break;

        case 'editClient':
            editClient();
            break;

        case 'editAval':
            editAval();
            break;

        case 'editDataPrest':
            editDataPrest();
            break;

        case 'editDetPrest':
            editDetPrest();
            break;

        case 'editar_perfil':
            editar_perfil();
            break;

        case 'editar_perfil':
            editar_perfil();
            break;

        case 'editarDatosEmpresa':
            editarDatosEmpresa();
            break;

        case 'change_password':
            change_password();
            break;
			
    case 'editClientCompleto':
    editClientCompleto();
    break;
    }
}

function SaveUser()
{
    global $conexion;
    extract($_POST);

    // Verificar usuario existente
    $consulta_existencia = "SELECT * FROM users WHERE usuario = '$usuario'";
    $resultado_existencia = mysqli_query($conexion, $consulta_existencia);

    if (mysqli_num_rows($resultado_existencia) > 0) {

        echo json_encode([
            'status' => 'user'
        ]);

        return;
    }

    // Verificar contraseñas
    if ($password !== $password2) {

        echo json_encode([
            'status' => 'password'
        ]);

        return;
    }

    // ==============================
    // FOTO DE PERFIL
    // ==============================

    $imagenPerfil  = '../images/users/default.png';

    if (
        isset($_FILES['foto_perfil']) &&
        $_FILES['foto_perfil']['error'] == 0
    ) {

        $directorio = "../images/users/";

        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $nombreArchivo = time() . "_" . basename($_FILES['foto_perfil']['name']);

        $rutaDestino = $directorio . $nombreArchivo;

        move_uploaded_file(
            $_FILES['foto_perfil']['tmp_name'],
            $rutaDestino
        );

        $imagenPerfil  = $rutaDestino;
    }

    // ==============================
    // HASH PASSWORD
    // ==============================

    $hash_clave = password_hash($password, PASSWORD_DEFAULT);

    // ==============================
    // TRANSACCIÓN
    // ==============================

    $conexion->begin_transaction();

    try {

        // Insertar usuario
        $consulta = "
            INSERT INTO users (
                usuario,
                correo,
                password,
                id_rol,
                imagenPerfil 
            ) 
            VALUES (
                '$usuario',
                '$correo',
                '$hash_clave',
                '$id_rol',
                '$imagenPerfil'
            )
        ";

        mysqli_query($conexion, $consulta);

        $idUsuario = $conexion->insert_id;

        // ==============================
        // GUARDAR PERMISOS
        // ==============================

        if (!empty($permisos)) {

            foreach ($permisos as $idPermiso) {

                $idPermiso = (int)$idPermiso;

                mysqli_query($conexion, "
                    INSERT INTO users_permisos (
                        user_id,
                        permiso_id
                    )
                    VALUES (
                        $idUsuario,
                        $idPermiso
                    )
                ");
            }
        }

        $conexion->commit();

        echo json_encode([
            'status' => 'success'
        ]);

    } catch (Exception $e) {

        $conexion->rollback();

        echo json_encode([
            'status' => 'error'
        ]);
    }
}

function editUser()
{

    global $conexion;
    extract($_POST);

    $consulta = "UPDATE users 
                 SET usuario ='$usuario',
                     correo = '$correo', 
                     id_rol = '$id_rol' 
                 WHERE id = '$id' ";
                     
    $resultado = mysqli_query($conexion, $consulta);

    if ($resultado) {

        // 🔥 BORRAR permisos actuales del usuario
        mysqli_query($conexion, "
            DELETE FROM users_permisos 
            WHERE user_id = '$id'
        ");

        // 🔥 INSERTAR nuevos permisos si existen
        if (isset($_POST['permisos'])) {
            foreach ($_POST['permisos'] as $permiso_id) {
                mysqli_query($conexion, "
                    INSERT INTO users_permisos (user_id, permiso_id)
                    VALUES ('$id', '$permiso_id')
                ");
            }
        }

        echo json_encode("correcto");

    } else {

        echo json_encode("error");
    }
}

function SaveTypePrest()
{
    global $conexion;
    extract($_POST);


    $datetime = date("Y-m-d H:i:s");
    $tipo = (int)$tipo_proyeccion;


    // ============================
    // INTERÉS SIMPLE
    // ============================
if ($tipo === 0) {

    $plazo_dias = $plazo_meses * 30;

    // 🔥 Convertir tasa anual a decimal
    $tasa_decimal = floatval($tasa_interes) / 100;

    // 🔥 Convertir plazo a meses
    $numero_periodos = $plazo_meses;

    // 🔥 Fórmula interés simple
    $factor = 1 + ($tasa_decimal * ($plazo_meses + $periodo_gracia));


    $sql = "INSERT INTO tipo_prestamo (
        nombre_tipo,
        descripcion,
        tasa_interes,
        periodo_gracia,
        plazo_dias,
        id_frp,
        multa_mora,
        monto_maximo,
        tipo_proyeccion,
        factor,
        fechaRegistro
    ) VALUES (
        '$nombre_tipo',
        '$descripcion',
        '$tasa_interes',
        '$periodo_gracia',
        '$plazo_dias',
        '$id_frp',
        '$multa_mora',
        '$monto_maximo',
        '$tipo',
        '$factor',
        '$datetime'
    )";
}


    // ============================
    // INTERÉS AMORTIZADO
    // ============================
    if ($tipo === 1) {

        $plazo_dias = $plazo_amort * 30;

        $sql = "INSERT INTO tipo_prestamo (
            nombre_tipo,
            descripcion,
            tasa_interes,
            tasa_mensual,
            plazo_dias,
            id_frp,
            multa_mora,
            monto_maximo,
            tipo_proyeccion,
            fechaRegistro
        ) VALUES (
            '$nombre_tipo_amort',
            '$descripcion_amort',
            '$tasa_anual_amor',
            '$tasa_mensual_amort',
            '$plazo_dias',
            '$frecuencia_pago_amort',
            '$multa_mora_amort',
            '$monto_maximo_amort',
            '$tipo',
            '$datetime'
        )";
    }

    $resultado = mysqli_query($conexion, $sql);

    if ($resultado) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Tipo de préstamo guardado correctamente'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => mysqli_error($conexion)
        ]);
    }
}




function editTypePrest()
{

    global $conexion;
    extract($_POST);

    $consulta = "UPDATE tipo_prestamo SET nombre_tipo = '$nombre_tipo',tasa_interes = '$tasa_interes',plazo_dias = '$plazo_dias', 
    id_frp = '$id_frp',multa_mora = '$multa_mora', monto_maximo = '$monto_maximo', descripcion = '$descripcion' WHERE id = '$id' ";
    $resultado = mysqli_query($conexion, $consulta);

    if ($resultado) {

        echo json_encode("correcto");
    } else {
        echo json_encode("error");
    }
}

function SaveClient()
{
    global $conexion;

    header('Content-Type: application/json');
    $conexion->begin_transaction();

    try {
   mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $datetime = date("Y-m-d H:i:s");

// ===============================
// CLIENTE
// ===============================

$folioClient = intval($_POST['folioClient']);
$id_status = intval($_POST['id_status']);
$id_tipoIdentificacion = intval($_POST['id_tipoIdentificacion']);

$estrato = !empty($_POST['estrato'])
    ? intval($_POST['estrato'])
    : 0;

$personas_cargo = !empty($_POST['persocargoClient'])
    ? intval($_POST['persocargoClient'])
    : 0;

// =====================================
// MUNICIPIOS (VARCHAR / CÓDIGO DANE)
// =====================================

$id_municipio = $_POST['id_municipio'] ?? '';

$id_municipio_nacimiento =
    $_POST['id_municipio_nacimiento'] ?? '';

$id_municipio_residencia =
    $_POST['id_municipio_residencia'] ?? '';

// =====================================

$tipo_vivienda = $_POST['tipo_vivienda'] ?? '';
$ocupacion_general = $_POST['ocupacion_general'] ?? '';

$cabeza_hogar = $_POST['cabeza_hogar'] ?? 'No';

$condicion_medica =
    $_POST['condicion_medica'] ?? '';

$detalle_condicion_medica =
    $_POST['detalleCondicionMedica'] ?? '';

$tiene_vehiculo_cliente =
    $_POST['tieneVehiculoCliente'] ?? 'No';

$placa_vehiculo_cliente =
    $_POST['placaVehiculoCliente'] ?? '';


// ===============================
// INSERT CLIENTE
// ===============================

$stmt = $conexion->prepare("

INSERT INTO clientes (

    folioClient,
    id_status,
    nombreClient,
    apellidoClient,
    estado_civil,
    genero,
    id_tipoIdentificacion,
    municipio_expedicion_id,
    fecha_expedicion,
    municipio_nacimiento_id,
    fecha_nacimiento,
    nivel_escolaridad,
    correoClient,
    cabeza_hogar,
    dirClient,
    municipio_residencia_id,
    barrioClient,
    estrato,
    docIdentClient,
    fecha_registro,
    telClient,
    celClient,
    personas_cargo,
    tipo_vivienda,
    ocupacion_general,
    condicion_medica,
    detalle_condicion_medica,
    tiene_vehiculo,
    placa_vehiculo

) VALUES (

    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?

)

");

if(!$stmt){

    throw new Exception($conexion->error);

}


// ===============================
// BIND PARAM
// ===============================

$stmt->bind_param(

    "iissssissssssssisissssissssss",

    $folioClient,
    $id_status,
    $_POST['nombreClient'],
    $_POST['apellidoClient'],
    $_POST['estado_civil'],
    $_POST['genero'],
    $id_tipoIdentificacion,
    $id_municipio,
    $_POST['fecha_expedicion'],
    $id_municipio_nacimiento,
    $_POST['fecha_nacimiento'],
    $_POST['nivel_escolaridad'],
    $_POST['correoClient'],
    $cabeza_hogar,
    $_POST['dirClient'],
    $id_municipio_residencia,
    $_POST['barrioClient'],
    $estrato,
    $_POST['docIdentClient'],
    $datetime,
    $_POST['telClient'],
    $_POST['celClient'],
    $personas_cargo,
    $tipo_vivienda,
    $ocupacion_general,
    $condicion_medica,
    $detalle_condicion_medica,
    $tiene_vehiculo_cliente,
    $placa_vehiculo_cliente

);


// ===============================
// EJECUTAR
// ===============================

if(!$stmt->execute()){

    die($stmt->error);

}

$idCliente = $conexion->insert_id;

$stmt->close();


// ===============================
// CONYUGE (solo si aplica)
// ===============================
if (
    !empty($_POST['nombre_conyuge'])
    && !empty($_POST['tipo_identificacion_conyugue'])
) {

    $idTipoConyugue = intval(
        $_POST['tipo_identificacion_conyugue']
    );

    // VALIDAR QUE EXISTA
    $validarTipo = mysqli_query($conexion, "

        SELECT id

        FROM tipo_identificacion

        WHERE id = $idTipoConyugue

        LIMIT 1

    ");

    if(mysqli_num_rows($validarTipo) == 0){

        throw new Exception(
            "El tipo de identificación del cónyuge no es válido"
        );
    }

    $stmt2 = $conexion->prepare("
        INSERT INTO conyuges
        (
            cliente_id,
            nombre_conyuge,
            tipo_identificacion_id,
            doc_conyuge,
            municipio_expedicion_id,
            fecha_expedicion,
            ceular_conyugue,
            tel_conyuge,
            empresa_conyugue,
            correoconyugue,
            ocupacion_conyugue
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt2->bind_param(

        "isissssssss",

        $idCliente,
        $_POST['nombre_conyuge'],
        $idTipoConyugue,
        $_POST['doc_conyuge'],
        $_POST['id_municipio_conyugue'],
        $_POST['fecha_expedicion_conyugue'],
        $_POST['ceular_conyugue'],
        $_POST['tel_conyuge'],
        $_POST['empresa_conyugue'],
        $_POST['correoconyugue'],
        $_POST['ocupacion_conyugue']

    );

    $stmt2->execute();
    $stmt2->close();
}
		
// ============================
// 🔹 GUARDAR CODEUDOR
// ============================

if (!empty($_POST['nombrecodeudor'])) {

    $nombre = mysqli_real_escape_string($conexion, $_POST['nombrecodeudor']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellidocodeudor']);
    $estado_civil = $_POST['estadocivilcodeudor'] ?? '';
    $genero = $_POST['generocodeudor'] ?? '';

    $tipo_identificacion = intval($_POST['tipoidentificacioncodeudor'] ?? 0);
    $numero_documento = mysqli_real_escape_string($conexion, $_POST['numerodocumentocodeudor']);

    $lugar_expedicion = intval($_POST['lugarexpedicioncodeudor'] ?? 0);
    $fecha_expedicion = $_POST['fechaexpedicioncodeudor'] ?? null;

    $lugar_nacimiento = intval($_POST['lugarnacimientocodeudor'] ?? 0);
    $fecha_nacimiento = $_POST['fechanacimientocodeudor'] ?? null;

    $nivel_escolaridad = $_POST['nivelescolaridadcodeudor'] ?? '';
    $direccion = mysqli_real_escape_string($conexion, $_POST['direccioncodeudor']);

    $ciudad_residencia = intval($_POST['ciudadresidenciacodeudor'] ?? 0);
    $barrio = $_POST['barriocodeudor'] ?? '';

    $telefono = $_POST['telefonocodeudor'] ?? '';
    $celular = $_POST['celularcodeudor'] ?? '';
    $estrato = $_POST['estratocodeudor'] ?? '';
    $email = $_POST['emailcodeudor'] ?? '';

    $personas_cargo = $_POST['personascargocodeudor'] ?? 0;
    $tipo_vivienda = $_POST['tipoviviendacodeudor'] ?? '';
    $ocupacion = $_POST['ocupacioncodeudor'] ?? '';

    $tiene_vehiculo = $_POST['tieneVehiculoCodeudor'] ?? 'No';
    $placa = $_POST['placaVehiculo'] ?? '';
	
	$condicion_codeudor = $_POST['condicion_codeudor'] ?? 'No';
    $detalle_condicion_codeudor = $_POST['detalle_condicion_codeudor'] ?? '';

    // ============================
    // NUEVOS CAMPOS LABORALES
    // ============================

    $empresa_codeudor = $_POST['empresa_codeudor'] ?? '';
    $fecha_ingreso_codeudor = $_POST['fecha_ingreso_codeudor'] ?? null;
    $devengado_codeudor = $_POST['devengado_codeudor'] ?? 0;
	$descuentos_codeudor = $_POST['descuentos_codeudor'] ?? 0;
    $neto_codeudor = $_POST['neto_codeudor'] ?? 0;
    $direccion_laboral_codeudor = $_POST['direccion_codeudor'] ?? '';
    $telefono_laboral_codeudor = $_POST['telefono_codeudor'] ?? '';
    $ciudad_laboral_codeudor = $_POST['ciudad_codeudor'] ?? 0;
    $ocupacion_laboral_codeudor = $_POST['ocupacion_codeudor'] ?? '';
    $cargo_codeudor = $_POST['cargo_codeudor'] ?? '';
    $ciiu_codeudor = isset($_POST['ciiu_codeudor']) ? 1 : 0;
    $sector_codeudor = isset($_POST['sector_codeudor']) ? 1 : 0;

    // ============================
    // NUEVOS CAMPOS FINANCIEROS
    // ============================

    $ingresos_codeudor = $_POST['ingresos_codeudor'] ?? 0;
    $otros_ingresos_codeudor = $_POST['otros_ingresos_codeudor'] ?? 0;
    $egresos_codeudor = $_POST['egresos_codeudor'] ?? 0;
    $activos_codeudor = $_POST['activos_codeudor'] ?? 0;
    $pasivos_codeudor = $_POST['pasivos_codeudor'] ?? 0;
    $patrimonio_codeudor = $_POST['patrimonio_codeudor'] ?? 0;

    $sql_codeudor = "INSERT INTO codeudor_prestamo (

        cliente_id,
        nombrecodeudor,
        apellidocodeudor,
        estadocivilcodeudor,
        generocodeudor,
        tipoidentificacioncodeudor,
        numerodocumentocodeudor,
        lugarexpedicioncodeudor,
        fechaexpedicioncodeudor,
        lugarnacimientocodeudor,
        fechanacimientocodeudor,
        nivelescolaridadcodeudor,
        direccioncodeudor,
        ciudadresidenciacodeudor,
        barriocodeudor,
        telefonocodeudor,
        celularcodeudor,
        estratocodeudor,
        emailcodeudor,
        personascargocodeudor,
        tipoviviendacodeudor,
        ocupacioncodeudor,
        tienevehiculocodeudor,
        placacodeudor,
		condicion_codeudor,
        detalle_condicion_codeudor,

        empresa_codeudor,
        fecha_ingreso_codeudor,
        devengado_codeudor,
		descuentos_codeudor,
        neto_codeudor,
        direccion_laboral_codeudor,
        telefono_laboral_codeudor,
        ciudad_laboral_codeudor,
        ocupacion_laboral_codeudor,
        cargo_codeudor,
        ciiu_codeudor,
        sector_codeudor,

        ingresos_codeudor,
        otros_ingresos_codeudor,
        egresos_codeudor,
        activos_codeudor,
        pasivos_codeudor,
        patrimonio_codeudor,

        fechaRegistro

    ) VALUES (

        '$idCliente',
        '$nombre',
        '$apellido',
        '$estado_civil',
        '$genero',
        '$tipo_identificacion',
        '$numero_documento',
        '$lugar_expedicion',
        '$fecha_expedicion',
        '$lugar_nacimiento',
        '$fecha_nacimiento',
        '$nivel_escolaridad',
        '$direccion',
        '$ciudad_residencia',
        '$barrio',
        '$telefono',
        '$celular',
        '$estrato',
        '$email',
        '$personas_cargo',
        '$tipo_vivienda',
        '$ocupacion',
        '$tiene_vehiculo',
        '$placa',
		'$condicion_codeudor',
        '$detalle_condicion_codeudor',

        '$empresa_codeudor',
        '$fecha_ingreso_codeudor',
        '$devengado_codeudor',
		'$descuentos_codeudor',
        '$neto_codeudor',
        '$direccion_laboral_codeudor',
        '$telefono_laboral_codeudor',
        '$ciudad_laboral_codeudor',
        '$ocupacion_laboral_codeudor',
        '$cargo_codeudor',
        '$ciiu_codeudor',
        '$sector_codeudor',

        '$ingresos_codeudor',
        '$otros_ingresos_codeudor',
        '$egresos_codeudor',
        '$activos_codeudor',
        '$pasivos_codeudor',
        '$patrimonio_codeudor',

        '$datetime'
    )";

    if (!mysqli_query($conexion, $sql_codeudor)) {
        throw new Exception(mysqli_error($conexion));
    }
}


        // ===============================
        // INFORMACIÓN LABORAL
        // ===============================
        $stmt3 = $conexion->prepare("
            INSERT INTO informacion_laboral
            (cliente_id, empresa, tipo_contrato,
             fecha_ingreso_laboral, totalDevengado,
             totalDescuentos, netoPagar,
             direccion_laboral, id_municipio_laboral,
             ocupacion_laboral, cargo_laboral)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        if (!$stmt3) {
            throw new Exception($conexion->error);
        }

        $stmt3->bind_param(
            "isssdddssss",
            $idCliente,
            $_POST['empresa'],
            $_POST['tipo_contrato'],
            $_POST['fecha_ingreso_laboral'],
            $_POST['totalDevengado'],
            $_POST['totalDescuentos'],
            $_POST['netoPagar'],
            $_POST['direccion_laboral'],
            $_POST['id_municipio_laboral'],
            $_POST['ocupacion_laboral'],
            $_POST['cargo_laboral']
        );

        $stmt3->execute();
        $stmt3->close();


        // ===============================
        // INFORMACIÓN FINANCIERA
        // ===============================
        $stmt4 = $conexion->prepare("
            INSERT INTO informacion_financiera
            (cliente_id, totalIngresos, otrosIngresos,
             totalEgresos, activos, pasivos, patrimonios)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        if (!$stmt4) {
            throw new Exception($conexion->error);
        }

        $stmt4->bind_param(
            "idddddd",
            $idCliente,
            $_POST['totalIngresos'],
            $_POST['otrosIngresos'],
            $_POST['totalEgresos'],
            $_POST['activos'],
            $_POST['pasivos'],
            $_POST['patrimonios']
        );

        $stmt4->execute();
        $stmt4->close();


     // ===============================
// FOTO
// ===============================
if (!empty($_POST['fotoBase64'])) {

    $foto = $_POST['fotoBase64'];

    // Limpiar encabezado base64
    $foto = str_replace('data:image/png;base64,', '', $foto);
    $foto = base64_decode($foto);

    // ===============================
    // CREAR CARPETAS POR FECHA
    // ===============================
    $anio = date("Y");
    $mes  = date("m");
    $dia  = date("d");

    $basePath = "../documentos_clientes/";
    $rutaCarpeta = $basePath . $anio . "/" . $mes . "/" . $dia . "/";

    // Crear carpetas si no existen
    if (!file_exists($rutaCarpeta)) {
        mkdir($rutaCarpeta, 0777, true);
    }

    // ===============================
    // RENOMBRAR FOTO
    // nombre-guion-cedula.png
    // ===============================
    $nombreLimpio = preg_replace('/[^A-Za-z0-9]/', '', $_POST['nombreClient']);
    $cedula = $_POST['docIdentClient'];

    $nombreArchivo = $nombreLimpio . "-" . $cedula . ".png";

    $rutaFinal = $rutaCarpeta . $nombreArchivo;

    file_put_contents($rutaFinal, $foto);

    // ===============================
    // GUARDAR EN BD
    // ===============================
    $stmt5 = $conexion->prepare("
        INSERT INTO documentos_clientes
        (cliente_id, tipo_documento, ruta_archivo)
        VALUES (?, 'foto', ?)
    ");

    $stmt5->bind_param("is", $idCliente, $rutaFinal);
    $stmt5->execute();
    $stmt5->close();
}


        // ===============================
        // CONFIRMAR
        // ===============================
        $conexion->commit();

        echo json_encode([
            "status" => "success",
            "message" => "Cliente guardado correctamente con toda la información."
        ]);

    } catch (Exception $e) {

        $conexion->rollback();

        echo json_encode([
            "status" => "error",
            "message" => "Error al guardar: " . $e->getMessage()
        ]);
    }

    exit;
}

function editClient()
{

    global $conexion;
    extract($_POST);

    $consulta = "UPDATE clientes SET nombreClient = '$nombreClient',apellidoClient = '$apellidoClient',docIdentClient = '$docIdentClient', 
    telClient = '$telClient',correoClient = '$correoClient', dirClient = '$dirClient', id_status = '$id_status',id_tipoIdentificacion = '$id_tipoIdentificacion' WHERE id = '$id' ";
    $resultado = mysqli_query($conexion, $consulta);

    if ($resultado) {

        echo json_encode("correcto");
    } else {
        echo json_encode("error");
    }
}



function SaveAval()
{
    global $conexion;
    date_default_timezone_set('America/Bogota');

    extract($_POST);
    $datetime = date("Y-m-d H:i:s");

    // Validar folio duplicado
    $sql = "SELECT id FROM avales WHERE folioAval = '$folioAval'";
    $res = mysqli_query($conexion, $sql);

    if (mysqli_num_rows($res) > 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'El folio ingresado ya está registrado a un aval. No es posible duplicarlo'
        ]);
        return;
    }

    // Validar documento duplicado
    $sql = "SELECT id FROM avales WHERE docIdentAval = '$docIdentAval'";
    $res = mysqli_query($conexion, $sql);

    if (mysqli_num_rows($res) > 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'El documento de identidad ya está registrado a un aval'
        ]);
        return;
    }

    // Insertar aval
    $consulta = "INSERT INTO avales
    (
        folioAval,
        nombreAval,
        apellidoAval,
        docIdentAval,
        telAval,
		celAval,
        correoAval,
        dirAval,
		id_municipioAval,
		barAval,
        id_status,
        fecha_registro,
        id_tiporeferencia,
		parentesco,
        id_tipoidentificacion
    )
    VALUES
    (
        '$folioAval',
        '$nombreAval',
        '$apellidoAval',
        '$docIdentAval',
        '$telAval',
		'$celAval',
        '$correoAval',
        '$dirAval',
		'$id_municipioAval',
		'$barAval',
        '$id_status',
        '$datetime',		
        '$id_tiporeferencia',
		'$parentesco',
        '$id_tipoidentificacion'
    )";

    $resultado = mysqli_query($conexion, $consulta);

    if ($resultado) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Los datos se guardaron correctamente'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => mysqli_error($conexion) // 👈 útil para debug
        ]);
    }
}


function editAval()
{
    global $conexion;
    extract($_POST);

    $datetime = date("Y-m-d H:i:s");

    $consulta = "UPDATE avales SET 
        nombreAval = '$nombreAval',
        apellidoAval = '$apellidoAval',
        docIdentAval = '$docIdentAval',
        telAval = '$telAval',
        correoAval = '$correoAval',
        dirAval = '$dirAval',
        id_status = '$id_status',
        id_tiporeferencia = '$id_tiporeferencia',
        id_tipoidentificacion = '$id_tipoidentificacion',
        fecha_modificacion = '$datetime'
    WHERE id = '$id'";

    $resultado = mysqli_query($conexion, $consulta);

    if ($resultado) {
        echo json_encode("correcto");
    } else {
        echo json_encode("error");
    }
}


function editDataPrest()
{

    global $conexion;
    extract($_POST);

    // ======================================
    // VALIDAR DOCUMENTOS
    // ======================================

    // SI QUIERE PASAR A PENDIENTE DE DESEMBOLSO
    if($id_estp == 3){

        // 🔴 VALIDAR RECHAZADOS
        $rechazados = mysqli_query($conexion, "

            SELECT COUNT(*) total

            FROM documentos_prestamo

            WHERE id_prestamo = $id
            AND estado = 'Rechazado'

        ");

        $rowRechazados = mysqli_fetch_assoc($rechazados);

        // SI HAY RECHAZADOS
        if($rowRechazados['total'] > 0){

            // DEVOLVER A RADICADO
            mysqli_query($conexion, "

                UPDATE prestamos

                SET id_estp = 1

                WHERE id = $id

            ");

            echo json_encode([
                "status" => "error",
                "message" => "Hay documentos rechazados. El crédito vuelve a Radicado."
            ]);

            return;
        }

        // 🟡 VALIDAR PENDIENTES
        $pendientes = mysqli_query($conexion, "

            SELECT COUNT(*) total

            FROM documentos_prestamo

            WHERE id_prestamo = $id
            AND estado = 'Pendiente'

        ");

        $rowPendientes = mysqli_fetch_assoc($pendientes);

        // SI HAY DOCUMENTOS PENDIENTES
        if($rowPendientes['total'] > 0){

            echo json_encode([
                "status" => "error",
                "message" => "Todos los documentos deben estar aprobados."
            ]);

            return;
        }
    }

    // ======================================
    // ACTUALIZAR PRÉSTAMO
    // ======================================

    $consulta = "

        UPDATE prestamos

        SET fecha_inicio = '$fecha_inicio',
            fecha_vencimiento = '$fecha_vencimiento',
            id_estp = '$id_estp'

        WHERE id = '$id'

    ";

    $resultado = mysqli_query($conexion, $consulta);

    // ======================================
    // GENERAR CUOTAS SI ES AUTORIZADO
    // ======================================

    if ($resultado && $id_estp == 1) {

        // VALIDAR SI YA EXISTEN CUOTAS
        $check = mysqli_query($conexion, "

            SELECT COUNT(*) as total

            FROM cuotas_prestamo

            WHERE id_prestamo = $id

        ");

        $row = mysqli_fetch_assoc($check);

        // SOLO GENERAR SI NO EXISTEN
        if ($row['total'] == 0) {

            // OBTENER DATOS DEL PRESTAMO
            $query = mysqli_query($conexion, "

                SELECT
                    p.fecha_inicio,
                    dp.num_cuotas,
                    dp.monto_cuota,
                    dp.frecuencia_pago

                FROM prestamos p

                INNER JOIN detalle_prestamo dp
                    ON dp.id_prestamo = p.id

                WHERE p.id = $id

            ");

            $data = mysqli_fetch_assoc($query);

            $fecha = new DateTime($data['fecha_inicio']);

            $frecuencia = strtolower(
                $data['frecuencia_pago']
            );

            for ($i = 1; $i <= $data['num_cuotas']; $i++) {

                $fecha_str = $fecha->format('Y-m-d');

                // INSERTAR CUOTA
                mysqli_query($conexion, "

                    INSERT INTO cuotas_prestamo (
                        id_prestamo,
                        numero_cuota,
                        fecha_pago,
                        monto
                    )

                    VALUES (
                        $id,
                        $i,
                        '$fecha_str',
                        {$data['monto_cuota']}
                    )

                ");

                // AVANZAR FECHA
                switch ($frecuencia) {

                    case 'diaria':
                        $fecha->modify('+1 day');
                    break;

                    case 'semanal':
                        $fecha->modify('+7 days');
                    break;

                    case 'quincenal':
                        $fecha->modify('+15 days');
                    break;

                    case 'mensual':
                        $fecha->modify('+1 month');
                    break;

                    case 'bimestral':
                        $fecha->modify('+2 months');
                    break;

                    case 'trimestral':
                        $fecha->modify('+3 months');
                    break;

                    case 'unico pago':
                        break 2;
                }
            }
        }
    }

    // ======================================
    // RESPUESTA
    // ======================================

    if ($resultado) {

        echo json_encode([
            "status" => "success",
            "message" => "Estado actualizado correctamente"
        ]);

    } else {

        echo json_encode([
            "status" => "error",
            "message" => "Error al actualizar"
        ]);
    }
}

function editDetPrest()
{

    global $conexion;
    extract($_POST);

    $consulta = "UPDATE detalle_prestamo SET multa_mora = '$multa_mora' WHERE id = '$id' ";
    $resultado = mysqli_query($conexion, $consulta);

    if ($resultado) {

        echo json_encode("correcto");
    } else {
        echo json_encode("error");
    }
}


function editar_perfil()
{
    global $conexion;
    extract($_POST);

    // Verificar si se ha seleccionado una nueva imagen
    if (!empty($_FILES['imagenPerfil']['name'])) {
        $imagen_tmp = $_FILES['imagenPerfil']['tmp_name'];
        $imagen_ruta = '../images/perfiles/' . $_FILES['imagenPerfil']['name'];
        move_uploaded_file($imagen_tmp, $imagen_ruta);

        // Actualizar la ruta de la imagen en la base de datos
        $consulta = "UPDATE users SET usuario = '$usuario', correo = '$correo', imagenPerfil = '$imagen_ruta' WHERE id = '$id' ";
    } else {
        // No se ha seleccionado una nueva imagen, actualizar solo los datos sin cambiar la imagen
        $consulta = "UPDATE users SET usuario = '$usuario', correo = '$correo' WHERE id = '$id' ";
    }

    $resultado = mysqli_query($conexion, $consulta);
    if ($resultado === true) {
        echo json_encode("updated");
    } else {
        echo json_encode("error");
    }
}


function editarDatosEmpresa()
{
    global $conexion;
    extract($_POST);

    // Verificar si se ha seleccionado una nueva imagen
    if (!empty($_FILES['imagenEmpresa']['name'])) {
        $imagen_tmp = $_FILES['imagenEmpresa']['tmp_name'];
        $imagen_ruta = '../images/imagenEmpresas/' . $_FILES['imagenEmpresa']['name'];
        move_uploaded_file($imagen_tmp, $imagen_ruta);

        // Actualizar la ruta de la imagen en la base de datos
        $consulta = "UPDATE datos SET empresa = '$empresa', telefono = '$telefono', cp = '$cp', calles = '$calles', direccion = '$direccion', 
        representante = '$representante',imagenEmpresa = '$imagen_ruta' WHERE id = '$id' ";
    } else {
        // No se ha seleccionado una nueva imagen, actualizar solo los datos sin cambiar la imagen
        $consulta = "UPDATE datos SET empresa = '$empresa', telefono = '$telefono', cp = '$cp', calles = '$calles', direccion = '$direccion',
        representante = '$representante' WHERE id = '$id' ";
    }

    $resultado = mysqli_query($conexion, $consulta);
    if ($resultado === true) {
        echo json_encode("updated");
    } else {
        echo json_encode("error");
    }
}


function change_password()
{
    global $conexion;
    extract($_POST);
    $password = trim($_POST['password']);
    $password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 5]);
    $consulta = "UPDATE users SET password = '$password' WHERE id = '$id' ";
    $resultado = mysqli_query($conexion, $consulta);

    if ($resultado) {
        echo json_encode("correcto");
    } else {
        echo json_encode("error");
    }
}

function editClientCompleto()
{
    global $conexion;

    $conexion->begin_transaction();

    try {

        $id = intval($_POST['id']);

        // ===============================
        // ACTUALIZAR CLIENTE
        // ===============================
        $stmt = $conexion->prepare("
            UPDATE clientes SET
                id_status = ?,
                telClient = ?,
                celClient = ?,
                correoClient = ?,
                dirClient = ?,
                barrioClient = ?,
                estrato = ?,
                personas_cargo = ?,
                tipo_vivienda = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "issssiissi",
            $_POST['id_status'],
            $_POST['telClient'],
            $_POST['celClient'],
            $_POST['correoClient'],
            $_POST['dirClient'],
            $_POST['barrioClient'],
            $_POST['estrato'],
            $_POST['personas_cargo'],
            $_POST['tipo_vivienda'],
            $id
        );

        $stmt->execute();
        $stmt->close();


        // ===============================
        // ACTUALIZAR INFORMACION LABORAL
        // ===============================
        $stmt2 = $conexion->prepare("
            UPDATE informacion_laboral SET
                empresa = ?,
                tipo_contrato = ?,
                fecha_ingreso_laboral = ?,
                totalDevengado = ?,
                totalDescuentos = ?,
                netoPagar = ?
            WHERE cliente_id = ?
        ");

        $stmt2->bind_param(
            "sssdddi",
            $_POST['empresa'],
            $_POST['tipo_contrato'],
            $_POST['fecha_ingreso_laboral'],
            $_POST['totalDevengado'],
            $_POST['totalDescuentos'],
            $_POST['netoPagar'],
            $id
        );

        $stmt2->execute();
        $stmt2->close();


        // ===============================
        // ACTUALIZAR INFORMACION FINANCIERA
        // ===============================
        $stmt3 = $conexion->prepare("
            UPDATE informacion_financiera SET
                totalIngresos = ?,
                otrosIngresos = ?,
                totalEgresos = ?,
                activos = ?,
                pasivos = ?,
                patrimonios = ?
            WHERE cliente_id = ?
        ");

        $stmt3->bind_param(
            "ddddddi",
            $_POST['totalIngresos'],
            $_POST['otrosIngresos'],
            $_POST['totalEgresos'],
            $_POST['activos'],
            $_POST['pasivos'],
            $_POST['patrimonios'],
            $id
        );

        $stmt3->execute();
        $stmt3->close();


        $conexion->commit();

        echo json_encode([
            "status" => "success",
            "message" => "Cliente actualizado correctamente"
        ]);

    } catch (Exception $e) {

        $conexion->rollback();

        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage()
        ]);
    }

    exit;
}
