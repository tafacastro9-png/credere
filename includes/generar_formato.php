<?php
require_once('../fpdf/fpdf.php');
require_once('../fpdi/src/autoload.php');
include("db.php");
date_default_timezone_set('America/Bogota');

use setasign\Fpdi\Fpdi;

function limpiar($texto){
    return utf8_decode($texto ?? '');
}

function formatearTexto($texto){
    $texto = trim($texto ?? '');
    
    // Evitar dañar correos
    if (filter_var($texto, FILTER_VALIDATE_EMAIL)) {
        return strtolower($texto);
    }

    return ucwords(strtolower($texto));
}

function mayusculas($texto){
    return mb_strtoupper(trim($texto ?? ''), 'UTF-8');
}



$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id <= 0){
    die("ID inválido");
}

$sql = "
SELECT 
    p.*,

    -- CLIENTE
    c.nombreClient,
    c.apellidoClient,
    c.docIdentClient,
    c.dirClient,
    c.telClient,
    c.correoClient,
	c.cabeza_hogar,
	c.estado_civil,
	c.genero,
	c.fecha_expedicion,
	c.fecha_nacimiento,
	c.nivel_escolaridad,
	c.barrioClient,
	c.estrato,
	c.celClient,
	c.personas_cargo,
	c.tipo_vivienda,
	c.ocupacion_general,
	c.condicion_medica,
	c.detalle_condicion_medica,
	c.tiene_vehiculo,
	c.placa_vehiculo,
	

    -- TIPO IDENTIFICACION
    ti.codigo,

    -- TIPO PRESTAMO
    tp.nombre_tipo,
	tp.tasa_interes,
	tp.multa_mora,

    -- TIPO CREDITO
    tc.nombre AS nombre_credito,

    -- AVAL
    av.nombreAval,
    av.apellidoAval,
    av.telAval,
    av.dirAval,
    av.correoAval,
	av.barAval,
	ciurp.nombre AS ciudadAval,
	av.celAval,


    -- AVAL FAMILIAR
    avf.nombreAval AS nombreAvalFamiliar,
    avf.apellidoAval AS apellidoAvalFamiliar,
    avf.telAval AS telAvalFamiliar,
    avf.dirAval AS dirAvalFamiliar,
    avf.correoAval AS corAvalFamiliar,
    avf.parentesco AS parentesco,
	avf.barAval AS barrioAvalFamiliar,
	ciurf.nombre AS ciudadAvalFamiliar,
	avf.celAval AS celularAvalFamiliar,
	
	-- MUNICIPIO expedicion
	ciu.nombre AS nombre_ciudad_expedicion,
	
	-- MUNICIPIO nacimiento
	ciuna.nombre AS nombre_ciudad_nacimiento,
	
	-- MUNICIPIO residencia
	ciure.nombre AS nombre_ciudad_residencia,
	
	-- DEPARTAMENTO residencia
	d.nombre AS nombre_departamento_residencia,
	
	-- Conyugue
	con.nombre_conyuge AS nombre_conyuge,
	con.correoconyugue AS correo_conyugue,
	tidconyugue.codigo AS tipoidentificacion_conyugue,
	con.doc_conyuge AS documento_conyugue,
	ciuc.nombre AS municipioexpedicion_conyugue,
	con.fecha_expedicion AS fechaexpedicion_conyugue,
	con.ceular_conyugue AS celular_conyugue,
	con.tel_conyuge AS tel_conyugue,
	con.empresa_conyugue AS empresa_conyugue,
	con.ocupacion_conyugue AS ocupacion_conyugue,
	
	-- Codeudor
	cod.nombrecodeudor,
	cod.apellidocodeudor,
	cod.estadocivilcodeudor,
	cod.generocodeudor,
	cod.tipoidentificacioncodeudor,
	cod.numerodocumentocodeudor,
	ciucodexp.nombre AS lugarexpedicioncodeudor,
	cod.fechaexpedicioncodeudor,
	ciuconaci.nombre AS lugarnacimientocodeudor,
	cod.fechanacimientocodeudor,
	cod.nivelescolaridadcodeudor,
	cod.direccioncodeudor,
	ciucoresi.nombre AS ciudadresidenciacodeudor,
	dep.nombre AS departamentoresidenciacodeudor,
	cod.barriocodeudor,
	cod.telefonocodeudor,
	cod.celularcodeudor,
	cod.estratocodeudor,
	cod.emailcodeudor,
	cod.personascargocodeudor,
	cod.tipoviviendacodeudor,
	cod.ocupacioncodeudor,
	cod.tienevehiculocodeudor,
	cod.placacodeudor,
	cod.condicion_codeudor,
	cod.detalle_condicion_codeudor,
	cod.fechaRegistro,
	cod.empresa_codeudor,
	cod.fecha_ingreso_codeudor,
	cod.devengado_codeudor,
	cod.descuentos_codeudor,
	cod.neto_codeudor,
	cod.direccion_laboral_codeudor,
	cod.telefono_laboral_codeudor,
	ciulacod.nombre AS ciudad_laboral_codeudor,
	cod.ocupacion_laboral_codeudor,
	cod.cargo_codeudor,
	cod.ciiu_codeudor,
	cod.sector_codeudor,
	cod.ingresos_codeudor,
	cod.otros_ingresos_codeudor,
	cod.egresos_codeudor,
	cod.activos_codeudor,
	cod.pasivos_codeudor,
	cod.patrimonio_codeudor,



	
	
	-- Informacion laboral
	infola.empresa AS nombre_empresa,
	infola.tipo_contrato AS tipocontrato_empresa,
	infola.fecha_ingreso_laboral AS fechaingreso_empresa,
	infola.totalDevengado AS totaldevengado_empresa,
	infola.totalDescuentos AS totaldescuentos_empresa,
	infola.netoPagar AS netopagar_empresa,
	infola.direccion_laboral AS direccion_empresa,
	ciul.nombre AS ciudad_empresa,
	infola.ocupacion_laboral AS ocupacion_empresa,
	infola.cargo_laboral AS cargo_empresa,
		
	-- Infromacion financiera
	infofi.totalIngresos AS totalingresos,
	infofi.otrosIngresos AS otrosingresos,
	infofi.totalEgresos AS totalegresos,
	infofi.activos AS activos,
	infofi.pasivos AS pasivos,
	infofi.patrimonios AS patrimonios,
	
	-- Coutas prestamos
	cp.fecha_pago AS fecha_pago,
    cp.monto AS monto_primera_cuota,
	dp.num_cuotas AS numero_cuota
	

FROM prestamos p
INNER JOIN clientes c ON p.id_cliente = c.id
INNER JOIN tipo_prestamo tp ON p.id_tp = tp.id
INNER JOIN tipo_credito tc ON p.id_tipo_credito = tc.id
INNER JOIN tipo_identificacion ti ON c.id_tipoIdentificacion = ti.id
INNER JOIN avales av ON av.id = p.id_aval
INNER JOIN avales avf ON avf.id = p.id_avalFamiliar
INNER JOIN ciudades ciurf ON avf.id_municipioAval = ciurf.id
INNER JOIN ciudades ciurp ON av.id_municipioAval = ciurp.id
INNER JOIN ciudades ciu ON c.municipio_expedicion_id = ciu.id
INNER JOIN ciudades ciuna ON c.municipio_nacimiento_id = ciuna.id
INNER JOIN ciudades ciure ON C.municipio_residencia_id = ciure.id
INNER JOIN departamentos d  ON LEFT(ciure.id, 2) = d.id
LEFT JOIN conyuges con ON c.id = con.cliente_id
LEFT JOIN tipo_identificacion tidconyugue ON con.tipo_identificacion_id = tidconyugue.id
LEFT JOIN ciudades ciuc ON con.municipio_expedicion_id = ciuc.id
INNER JOIN informacion_laboral infola ON c.id = infola.cliente_id
INNER JOIN ciudades ciul ON  infola.id_municipio_laboral = ciul.id
INNER JOIN informacion_financiera infofi ON c.id = infofi.cliente_id
LEFT JOIN (SELECT cp1.id_prestamo, cp1.fecha_pago, cp1.monto FROM cuotas_prestamo cp1 INNER JOIN (SELECT id_prestamo, MIN(fecha_pago) AS primera_fecha FROM cuotas_prestamo GROUP BY id_prestamo) cp2 
ON cp1.id_prestamo = cp2.id_prestamo AND cp1.fecha_pago = cp2.primera_fecha) cp ON cp.id_prestamo = p.id
LEFT JOIN detalle_prestamo dp ON dp.id_prestamo = p.id
LEFT JOIN codeudor_prestamo cod ON cod.cliente_id = c.id
LEFT JOIN ciudades ciucodexp ON cod.lugarexpedicioncodeudor = ciucodexp.id
LEFT JOIN ciudades ciuconaci ON cod.lugarnacimientocodeudor = ciuconaci.id
LEFT JOIN ciudades ciucoresi ON cod.ciudadresidenciacodeudor = ciucoresi.id
LEFT JOIN departamentos dep ON ciucoresi.departamento_id = dep.id
LEFT JOIN ciudades ciulacod ON cod.ciudad_laboral_codeudor = ciulacod.id
WHERE p.id = ?
";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$prestamo = $resultado->fetch_assoc();

if(!$prestamo){
    die("Préstamo no encontrado");
}

$id_tipo_credito = $prestamo['id_tipo_credito'];



$fecha = new DateTime($prestamo['fechaRegistro']);
$dia = $fecha->format('d');
$mes = $fecha->format('m');
$anio = $fecha->format('y');

$nombreCompleto = formatearTexto($prestamo['nombreClient'].' '.$prestamo['apellidoClient']);
$monto = number_format($prestamo['monto_prestado'],0,',','.');
$fechaInicio = date("d/m/Y", strtotime($prestamo['fecha_inicio']));
$folio = mayusculas($prestamo['folioPrest']);
$tipocredito = mayusculas($prestamo['nombre_credito']);
$tipoId = $prestamo['codigo'];
$direccion = formatearTexto($prestamo['dirClient']);
$telfijo = formatearTexto($prestamo['telClient']);
$correo = formatearTexto($prestamo['correoClient']);
$cabeza_hogar = formatearTexto($prestamo['cabeza_hogar']);
$doccliente = formatearTexto($prestamo['docIdentClient']);
$tiene_vehiculo = formatearTexto($prestamo['tiene_vehiculo']);
$placa_vehiculo = formatearTexto($prestamo['placa_vehiculo']);
	

$nombreCompletoAvalFamiliar = formatearTexto($prestamo['nombreAvalFamiliar'].' '.$prestamo['apellidoAvalFamiliar']);
$dirAvalFamiliar = formatearTexto($prestamo['dirAvalFamiliar']);
$corAvalFamiliar = formatearTexto($prestamo['corAvalFamiliar']);
$telAvalFamiliar = formatearTexto($prestamo['telAvalFamiliar']);

$nombreCompletoAval = formatearTexto($prestamo['nombreAval'].' '.$prestamo['apellidoAval']);
$dirAval = formatearTexto($prestamo['dirAval']);
$corAval = formatearTexto($prestamo['correoAval']);
$telAval = formatearTexto($prestamo['telAval']);
$estado_civil = formatearTexto($prestamo['estado_civil']);
$genero = formatearTexto($prestamo['genero']);
$nombre_ciudad_expedicion = formatearTexto($prestamo['nombre_ciudad_expedicion']);
$fecha_expedicion = formatearTexto($prestamo['fecha_expedicion']);
$nombre_ciudad_nacimiento = formatearTexto($prestamo['nombre_ciudad_nacimiento']);
$fecha_nacimiento = formatearTexto($prestamo['fecha_nacimiento']);
$nivel_escolaridad = formatearTexto($prestamo['nivel_escolaridad']);
$nombre_ciudad_residencia = formatearTexto($prestamo['nombre_ciudad_residencia']);
$nombre_departamento_residencia = formatearTexto($prestamo['nombre_departamento_residencia']);
$barrioClient = formatearTexto($prestamo['barrioClient']);
$estrato = formatearTexto($prestamo['estrato']);
$celClient = formatearTexto($prestamo['celClient']);
$personas_cargo = formatearTexto($prestamo['personas_cargo']);
$tipo_vivienda = formatearTexto($prestamo['tipo_vivienda']);
$ocupacion_general = formatearTexto($prestamo['ocupacion_general']);
$condicion_medica = formatearTexto($prestamo['condicion_medica']);
$detalle_condicion_medica = formatearTexto($prestamo['detalle_condicion_medica']);
$nombre_conyuge = formatearTexto($prestamo['nombre_conyuge']);
$correo_conyugue = formatearTexto($prestamo['correo_conyugue']);
$tipoidentificacion_conyugue = formatearTexto($prestamo['tipoidentificacion_conyugue']);
$documento_conyugue = formatearTexto($prestamo['documento_conyugue']);
$municipioexpedicion_conyugue = formatearTexto($prestamo['municipioexpedicion_conyugue']);
$fechaexpedicion_conyugue = formatearTexto($prestamo['fechaexpedicion_conyugue']);
$celular_conyugue = formatearTexto($prestamo['celular_conyugue']);
$tel_conyugue = formatearTexto($prestamo['tel_conyugue']);
$empresa_conyugue = formatearTexto($prestamo['empresa_conyugue']);
$ocupacion_conyugue = formatearTexto($prestamo['ocupacion_conyugue']);
$nombre_empresa = formatearTexto($prestamo['nombre_empresa']);
$tipocontrato_empresa = formatearTexto($prestamo['tipocontrato_empresa']);
$fechaingreso_empresa = formatearTexto($prestamo['fechaingreso_empresa']);
$totaldevengado_empresa = $prestamo['totaldevengado_empresa'];
$totaldescuentos_empresa = $prestamo['totaldescuentos_empresa'];
$netopagar_empresa = $prestamo['netopagar_empresa'];
$direccion_empresa = formatearTexto($prestamo['direccion_empresa']);
$ciudad_empresa = formatearTexto($prestamo['ciudad_empresa']);
$ocupacion_empresa = formatearTexto($prestamo['ocupacion_empresa']);
$cargo_empresa = formatearTexto($prestamo['cargo_empresa']);
$totalingresos = $prestamo['totalingresos'];
$otrosingresos = formatearTexto($prestamo['otrosingresos']);
$totalegresos = formatearTexto($prestamo['totalegresos']);
$activos = formatearTexto($prestamo['activos']);
$pasivos = formatearTexto($prestamo['pasivos']);
$patrimonios = formatearTexto($prestamo['patrimonios']);
$parentesco = formatearTexto($prestamo['parentesco']);
$barrioAvalFamiliar = formatearTexto($prestamo['barrioAvalFamiliar']);
$ciudadAvalFamiliar = formatearTexto($prestamo['ciudadAvalFamiliar']);
$celularAvalFamiliar = formatearTexto($prestamo['celularAvalFamiliar']);
$barAval = formatearTexto($prestamo['barAval']);
$ciudadAval = formatearTexto($prestamo['ciudadAval']);
$celAval = formatearTexto($prestamo['celAval']);
$fecha_pago  = formatearTexto($prestamo['fecha_pago']);
$monto_primera_cuota = number_format(floatval($prestamo['monto_primera_cuota']),  0,  ',', '.');
$numero_cuota = formatearTexto($prestamo['numero_cuota']);
$tasa_interes = formatearTexto($prestamo['tasa_interes']);
$multa_mora = formatearTexto($prestamo['multa_mora']);
$fecha_actual = date('d/m/Y');
$nombrecompletocodeudor  = formatearTexto($prestamo['nombrecodeudor'].' '.$prestamo['apellidocodeudor']);
$estadocivilcodeudor = formatearTexto($prestamo['estadocivilcodeudor']);
$generocodeudor = formatearTexto($prestamo['generocodeudor']);
$tipoidentificacioncodeudor = formatearTexto($prestamo['tipoidentificacioncodeudor']);
$numerodocumentocodeudor = formatearTexto($prestamo['numerodocumentocodeudor']);
$lugarexpedicioncodeudor = formatearTexto($prestamo['lugarexpedicioncodeudor']);
$fechaexpedicioncodeudor = formatearTexto($prestamo['fechaexpedicioncodeudor']);
$lugarnacimientocodeudor = formatearTexto($prestamo['lugarnacimientocodeudor']);
$fechanacimientocodeudor = formatearTexto($prestamo['fechanacimientocodeudor']);
$nivelescolaridadcodeudor = formatearTexto($prestamo['nivelescolaridadcodeudor']);
$direccioncodeudor = formatearTexto($prestamo['direccioncodeudor']);
$ciudadresidenciacodeudor = formatearTexto($prestamo['ciudadresidenciacodeudor']);
$departamentoresidenciacodeudor = formatearTexto($prestamo['departamentoresidenciacodeudor']);
$barriocodeudor = formatearTexto($prestamo['barriocodeudor']);
$telefonocodeudor = formatearTexto($prestamo['telefonocodeudor']);
$celularcodeudor = formatearTexto($prestamo['celularcodeudor']);
$estratocodeudor = formatearTexto($prestamo['estratocodeudor']);
$emailcodeudor = formatearTexto($prestamo['emailcodeudor']);
$personascargocodeudor = formatearTexto($prestamo['personascargocodeudor']);
$tipoviviendacodeudor = formatearTexto($prestamo['tipoviviendacodeudor']);
$ocupacioncodeudor = formatearTexto($prestamo['ocupacioncodeudor']);
$tienevehiculocodeudor = formatearTexto($prestamo['tienevehiculocodeudor']);
$placacodeudor = formatearTexto($prestamo['placacodeudor']);
$condicion_codeudor  = formatearTexto($prestamo['condicion_codeudor']);
$detalle_condicion_codeudor = formatearTexto($prestamo['detalle_condicion_codeudor']);
$fechaRegistro = formatearTexto($prestamo['fechaRegistro']);
$empresa_codeudor = formatearTexto($prestamo['empresa_codeudor']);
$fecha_ingreso_codeudor = formatearTexto($prestamo['fecha_ingreso_codeudor']);
$devengado_codeudor = $prestamo['devengado_codeudor'];
$descuentos_codeudor = $prestamo['descuentos_codeudor'];
$neto_codeudor = $prestamo['neto_codeudor'];
$direccion_laboral_codeudor = formatearTexto($prestamo['direccion_laboral_codeudor']);
$telefono_laboral_codeudor = formatearTexto($prestamo['telefono_laboral_codeudor']);
$ciudad_laboral_codeudor = formatearTexto($prestamo['ciudad_laboral_codeudor']);
$ocupacion_laboral_codeudor = formatearTexto($prestamo['ocupacion_laboral_codeudor']);
$cargo_codeudor = formatearTexto($prestamo['cargo_codeudor']);
$ciiu_codeudor = formatearTexto($prestamo['ciiu_codeudor']);
$sector_codeudor = formatearTexto($prestamo['sector_codeudor']);
$ingresos_codeudor = $prestamo['ingresos_codeudor'];
$otros_ingresos_codeudor = $prestamo['otros_ingresos_codeudor'];
$egresos_codeudor = $prestamo['egresos_codeudor'];
$activos_codeudor = $prestamo['activos_codeudor'];
$pasivos_codeudor = $prestamo['pasivos_codeudor'];
$patrimonio_codeudor = $prestamo['patrimonio_codeudor'];






if($id_tipo_credito == 3){

    // HIPOTECA
    $documentos = [
        "../plantillas/SOLICITUD DE CREDITO.pdf",
        "../plantillas/9 AUTORIZACION DECLARACION DE INFORMACION DE CONDICIONES DE CRÉDITO.pdf",
        "../plantillas/AUTORIZACION CONSULTA SUDAMERIS (8) (8).pdf",
        "../plantillas/Contrato Garantía Inmobiliaria.pdf",
        "../plantillas/PAGARE CREDERE.pdf",
        "../plantillas/SOLICITUD DE INGRESO ASOCIADO.pdf",
        "../plantillas/SOLICITUD DE RETIRO ASOCIADO.pdf"
    ];

} elseif($id_tipo_credito == 1 || $id_tipo_credito == 2){

    // CARRO Y MOTO
    $documentos = [
        "../plantillas/vehiculosymoto/SOLICITUD DE CREDITO.pdf",
        "../plantillas/vehiculosymoto/9 AUTORIZACION DECLARACION DE INFORMACION DE CONDICIONES DE CRÉDITO.pdf",
        "../plantillas/vehiculosymoto/AUTORIZACION CONSULTA SUDAMERIS (8) (8).pdf",
        "../plantillas/vehiculosymoto/Contrato de Mandato ( VYM ).pdf",
        "../plantillas/vehiculosymoto/Contrato Garantía Inmobiliaria.pdf",
        "../plantillas/vehiculosymoto/Contrato mandato PJ.pdf",
        "../plantillas/vehiculosymoto/FUNRUNT (1).pdf",
        "../plantillas/vehiculosymoto/Mandato tránsito.pdf",
        "../plantillas/vehiculosymoto/PAGARE CREDERE.pdf",
        "../plantillas/vehiculosymoto/SOLICITUD DE INGRESO ASOCIADO.pdf",
        "../plantillas/vehiculosymoto/SOLICITUD DE RETIRO ASOCIADO.pdf"
    ];

} else {

    die("Tipo de crédito no válido");

}
$pdf = new Fpdi();
    foreach($documentos as $archivo){

        if(!file_exists($archivo)){
            continue;
        }

        $pageCount = $pdf->setSourceFile($archivo);

        for($i = 1; $i <= $pageCount; $i++){

            $template = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($template);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($template);

            // 🔥 SOLO LLENAR SOLICITUD DE CREDITO
            if(basename($archivo) == "SOLICITUD DE CREDITO.pdf"){

                if($i == 1){

                    $pdf->SetTextColor(0,0,0);

                    // Fecha
                    $pdf->SetFont('Arial','B',15);

$pdf->SetXY(133, 17);
$pdf->Write(5, $dia);

$pdf->SetXY(144, 17);
$pdf->Write(5, $mes);

$pdf->SetXY(154, 17);
$pdf->Write(5, substr($anio,-2));

                    $pdf->SetXY(179, 16);
                    $pdf->Write(5, $folio);

                    // Ciudad
					  $pdf->SetFont('Arial','B',10);
                    $pdf->SetXY(25, 24);
                    $pdf->Write(5, "Cali");

                 // Tipo crédito
$textoCredito = $tipocredito;

if($id_tipo_credito == 3){
    $textoCredito = 'HIPOTECA CON PACTO DE RETROVENTA';
}

$pdf->SetXY(77, 24);
$pdf->Write(5, $textoCredito);

$pdf->SetFont('Arial','',10);
					
					     $pdf->SetFont('Arial','',10);

                    // Nombre cliente
                    $pdf->SetXY(45, 40);
                    $pdf->Write(5, $nombreCompleto);

                    // Tipo identificación
                   

                    if($tipoId == 'CC'){
                        $pdf->SetXY(52, 45);
                        $pdf->Write(5, 'X');
                    }

                    if($tipoId == 'NIT'){
                        $pdf->SetXY(62, 45);
                        $pdf->Write(5, 'X');
                    }

                    if($tipoId == 'CE'){
                        $pdf->SetXY(71, 45);
                        $pdf->Write(5, 'X');
                    }

                    // Documento
                     $pdf->SetFont('Arial','',10);
                    $pdf->SetXY(87, 45);
                    $pdf->Write(5, $doccliente);

                    // Dirección
                    $pdf->SetXY(45, 54);
                    $pdf->MultiCell(100, 5, $direccion);

                    // Teléfono
                    $pdf->SetXY(184, 54);
                    $pdf->Write(5, $telfijo);

                    // Correo
                    $pdf->SetXY(152, 58);
                    $pdf->Write(5, $correo);
					
			
					
					   $cabeza_hogar = strtolower(trim($cabeza_hogar));
   
   if ($cabeza_hogar == 'si') {

    $pdf->SetXY(31, 63);  // ← ajustar
    $pdf->Write(5, 'X');
   
   } else {

    $pdf->SetXY(37, 63); // ← ajustar (OTRO)
    $pdf->Write(5, 'X');
}
	
	
						   $tiene_vehiculo = strtolower(trim($tiene_vehiculo));
   
   if ($tiene_vehiculo == 'si') {

    $pdf->SetXY(23, 67);  // ← ajustar
    $pdf->Write(5, 'X');
   
   } else {

    $pdf->SetXY(27, 67); // ← ajustar (OTRO)
    $pdf->Write(5, 'X');
}


  $pdf->SetXY(43, 67);
$pdf->Write(5, strtoupper($placacodeudor));
	
					
					
			
					
					
					
					
					
					
				

                    // Aval Familiar
                    $pdf->SetXY(22, 144);
                    $pdf->Write(5, $nombreCompletoAvalFamiliar);

                    $pdf->SetXY(86, 144);
                    $pdf->Write(5, $dirAvalFamiliar);
                    
					 $pdf->SetFont('Arial','',8);
                    $pdf->SetXY(132, 144);
                    $pdf->Write(5, $corAvalFamiliar);
					 $pdf->SetFont('Arial','',10);
					
					$pdf->SetXY(183, 144);
                    $pdf->Write(5, $parentesco);

                    $pdf->SetXY(130, 149);
                    $pdf->Write(5, $telAvalFamiliar);
					
					 $pdf->SetXY(22, 149);
                    $pdf->Write(5, $barrioAvalFamiliar);
					
					
						 $pdf->SetXY(86, 149);
                    $pdf->Write(5, $ciudadAvalFamiliar);
					
					
						 $pdf->SetXY(183, 149);
                    $pdf->Write(5, $celularAvalFamiliar);
					
					
					
					

					 
					
					
					
					

                    // Aval Principal
                    $pdf->SetXY(22, 162);
                    $pdf->Write(5, $nombreCompletoAval);

                    $pdf->SetXY(86, 162);
                    $pdf->Write(5, $dirAval);

				    $pdf->SetFont  ('Arial','',8);
                    $pdf->SetXY(132, 162);
                    $pdf->Write(5, $corAval);
					$pdf->SetFont('Arial','',10);

                    $pdf->SetXY(131, 166);
                    $pdf->Write(5, $telAval);
					
					 $pdf->SetXY(23, 166);
                    $pdf->Write(5, $barAval);
					
					$pdf->SetXY(87, 166);
                    $pdf->Write(5, $ciudadAval);
					
					$pdf->SetXY(182, 166);
                    $pdf->Write(5, $celAval);
					
					 

					
					
					
					
					
					
					
					 $pdf->SetFont('Arial','',8);
				    $pdf->SetXY(152, 40);
                    $pdf->Write(5, $estado_civil);
					 $pdf->SetFont('Arial','',10);
					 
					 
					 
					 
					 
					 
					   $pdf->SetXY(45, 175);
			      $pdf->Write(5, $nombrecompletocodeudor);
   
   
   		 
					   $pdf->SetXY(153, 175);
			      $pdf->Write(5, $estadocivilcodeudor);
				  
				  
				  $generocodeudor = strtolower(trim($generocodeudor));

if ($generocodeudor == 'femenino' || $genero == 'f') {
    // Posición de la casilla F
    $pdf->SetXY(192, 175); 
    $pdf->Write(5, 'X');
}
elseif ($generocodeudor == 'masculino' || $genero == 'm') {
    // Posición de la casilla M
    $pdf->SetXY(201, 175); 
    $pdf->Write(5, 'X');
}
else {
    // Posición de la casilla Otro
    $pdf->SetXY(180, 175); 
    $pdf->Write(5, 'X');
}
					 
					 
					 
					 
					  if($tipoidentificacioncodeudor == '1'){
                        $pdf->SetXY(52, 180);
                        $pdf->Write(5, 'X');
                    }

                    if($tipoidentificacioncodeudor == '2'){
                        $pdf->SetXY(62, 180);
                        $pdf->Write(5, 'X');
                    }

                    if($tipoidentificacioncodeudor == '3'){
                        $pdf->SetXY(71, 180);
                        $pdf->Write(5, 'X');
                    }

			   $pdf->SetXY(87, 180);
			      $pdf->Write(5, $numerodocumentocodeudor);
				  
				  
				   $pdf->SetXY(139, 179);
			      $pdf->Write(5, $lugarexpedicioncodeudor);
				  
				  
				   $timestamp = strtotime($fechaexpedicioncodeudor);

$dia  = date('d', $timestamp);
$mes  = date('m', $timestamp);
$anio = date('y', $timestamp); // solo 2 dígitos
 
 


// Día
$pdf->SetXY(188, 180);
$pdf->Write(5, $dia);

// Mes
$pdf->SetXY(193, 180);
$pdf->Write(5, $mes);

// Año (2 dígitos)
$pdf->SetXY(200, 180);
$pdf->Write(5, $anio);

   $pdf->SetXY(45, 184);
   $pdf->Write(5, $lugarnacimientocodeudor);
   
   
   $timestamp = strtotime($fechanacimientocodeudor);

$dia  = date('d', $timestamp);
$mes  = date('m', $timestamp);
$anio = date('y', $timestamp); // solo 2 dígitos
 
 


// Día
$pdf->SetXY(118, 184);
$pdf->Write(5, $dia);

// Mes
$pdf->SetXY(125, 184);
$pdf->Write(5, $mes);

// Año (2 dígitos)
$pdf->SetXY(131, 184);
$pdf->Write(5, $anio);
   
   
      $pdf->SetXY(152, 184);
   $pdf->Write(5, $nivelescolaridadcodeudor);
   
      
      $pdf->SetXY(45, 189);
   $pdf->Write(5, $direccioncodeudor);
   
   
         $pdf->SetXY(112, 189);
   $pdf->Write(5, $ciudadresidenciacodeudor);
   
   
           $pdf->SetXY(153, 189);
   $pdf->Write(5, $departamentoresidenciacodeudor);
   
   
   
   
         $pdf->SetXY(184, 190);
   $pdf->Write(5, $telefonocodeudor);
   
   
            $pdf->SetXY(20, 193);
   $pdf->Write(5, $barriocodeudor);
   
   
   
   
            $pdf->SetXY(72, 193);
   $pdf->Write(5, $estratocodeudor);
   
   
   
   
            $pdf->SetXY(113, 193);
   $pdf->Write(5, $celularcodeudor);
   
   
      
            $pdf->SetXY(153, 193);
   $pdf->Write(5, $emailcodeudor);
   
   
   
         $pdf->SetXY(63, 198);
   $pdf->Write(5, $personascargocodeudor);
   
   
   
   
   $tipoviviendacodeudor = strtolower(trim($tipoviviendacodeudor));

if ($tipoviviendacodeudor == 'propia') {

    $pdf->SetXY(98, 198);  // ← ajustar
    $pdf->Write(5, 'X');

} elseif ($tipoviviendacodeudor == 'arrendada') {

    $pdf->SetXY(115, 198);  // ← ajustar
    $pdf->Write(5, 'X');

} elseif ($tipoviviendacodeudor == 'familiar') {

    $pdf->SetXY(131, 198); // ← ajustar
    $pdf->Write(5, 'X');
	

} else {

    $pdf->SetXY(142, 198); // ← ajustar (OTRO)
    $pdf->Write(5, 'X');
}
   
   
   
   /////////////////////////////7
   
   

   $tienevehiculocodeudor = strtolower(trim($tienevehiculocodeudor));
   
   if ($tienevehiculocodeudor == 'si') {

    $pdf->SetXY(143, 202);  // ← ajustar
    $pdf->Write(5, 'X');
   
   } else {

    $pdf->SetXY(148, 202); // ← ajustar (OTRO)
    $pdf->Write(5, 'X');
}
   
   
   
 $pdf->SetXY(165, 202);
$pdf->Write(5, strtoupper($placacodeudor));
   
   
    $ocupacioncodeudor = strtolower(trim($ocupacioncodeudor));


if ($ocupacioncodeudor == 'empleado') {

    $pdf->SetXY(52, 207);
    $pdf->Write(5, 'X');

} elseif ($ocupacioncodeudor == 'independiente') {

    $pdf->SetXY(84, 207);
    $pdf->Write(5, 'X');

} elseif ($ocupacioncodeudor == 'pensionado') {

   $pdf->SetXY(109, 207);
    $pdf->Write(5, 'X');

} elseif ($ocupacioncodeudor == 'estudiante') {

    $pdf->SetXY(136, 207);
    $pdf->Write(5, 'X');

} elseif ($ocupacioncodeudor == 'hogar') {

    $pdf->SetXY(159, 207);
   $pdf->Write(5, 'X');


} elseif ($ocupacioncodeudor == 'cesante') {

    $pdf->SetXY(190, 207);
   $pdf->Write(5, 'X');

} elseif ($ocupacioncodeudor == 'inversionista') {

    $pdf->SetXY(195, 72);
    $pdf->Write(5, 'X');
}
   
 
    $condicion_codeudor = strtolower(trim($condicion_codeudor));
   
   if ($condicion_codeudor == 'si') {

    $pdf->SetXY(67, 211);  // ← ajustar
    $pdf->Write(5, 'X');
   
   } else {

    $pdf->SetXY(71, 211); // ← ajustar (OTRO)
    $pdf->Write(5, 'X');
}
 
             $pdf->SetXY(84, 211);
   $pdf->Write(5, $detalle_condicion_codeudor);
   
   
   
   
             $pdf->SetXY(43, 220);
   $pdf->Write(5, $empresa_codeudor);
   
   
   
      
   $timestamp = strtotime($fecha_ingreso_codeudor);

$dia  = date('d', $timestamp);
$mes  = date('m', $timestamp);
$anio = date('y', $timestamp); // solo 2 dígitos
 
 


// Día
$pdf->SetXY(180, 220);
$pdf->Write(5, $dia);

// Mes
$pdf->SetXY(190, 220);
$pdf->Write(5, $mes);

// Año (2 dígitos)
$pdf->SetXY(200, 220);
$pdf->Write(5, $anio);



         
   
$pdf->SetXY(43, 225);

$pdf->Write(
    5,
    '$ ' . number_format((float)$devengado_codeudor, 0, ',', '.')
);
   
   
           $pdf->SetXY(106, 225);

   
   
   $pdf->Write(
    5,
    '$ ' . number_format((float)$descuentos_codeudor, 0, ',', '.')
);
   
   
   
   
      
           $pdf->SetXY(160, 225);

   
   
   
      $pdf->Write(
    5,
    '$ ' . number_format((float)$neto_codeudor, 0, ',', '.')
);
   
   
         
           $pdf->SetXY(43, 229);
   $pdf->Write(5, $direccion_laboral_codeudor);
   
     
           $pdf->SetXY(125, 229);
   $pdf->Write(5, $telefono_laboral_codeudor);
 
 
 
           $pdf->SetXY(170, 229);
   $pdf->Write(5, $ciudad_laboral_codeudor);
   
             $pdf->SetXY(43, 233);
   $pdf->Write(5, $ocupacion_laboral_codeudor);
   
   
       $pdf->SetXY(79, 233);
   $pdf->Write(5, $cargo_codeudor);
 
 
 
 
    $ciiu_codeudor = strtolower(trim($ciiu_codeudor));
   
   if ($ciiu_codeudor == '1') {

    $pdf->SetXY(159, 234);  // ← ajustar
    $pdf->Write(5, 'X');
   
   } else {

    $pdf->SetXY(183, 234); // ← ajustar (OTRO)
    $pdf->Write(5, '');
}


    $sector_codeudor = strtolower(trim($sector_codeudor));
   
   if ($sector_codeudor == '1') {

    $pdf->SetXY(183, 234);  // ← ajustar
    $pdf->Write(5, 'X');
   
   } else {

    $pdf->SetXY(183, 234); // ← ajustar (OTRO)
    $pdf->Write(5, '');
}
   
 
 
         $pdf->SetXY(30, 243);

   
   
         $pdf->Write(
    5,
    '$ ' . number_format((float)$ingresos_codeudor, 0, ',', '.')
);
   
   
         $pdf->SetXY(104, 243);
   
            $pdf->Write(
    5,
    '$ ' . number_format((float)$otros_ingresos_codeudor, 0, ',', '.')
);
   
   
   
   
               $pdf->SetXY(168, 243);

   
   
               $pdf->Write(
    5,
    '$ ' . number_format((float)$egresos_codeudor, 0, ',', '.')
);
   
   
   
               $pdf->SetXY(30, 247);

   
   
                  $pdf->Write(
    5,
    '$ ' . number_format((float)$activos_codeudor, 0, ',', '.')
);
   
   
   
                 $pdf->SetXY(105, 247);

   
   
                     $pdf->Write(
    5,
    '$ ' . number_format((float)$pasivos_codeudor, 0, ',', '.')
);
   
   
   
   
                 $pdf->SetXY(168, 247);

   
      
                     $pdf->Write(
    5,
    '$ ' . number_format((float)$patrimonio_codeudor, 0, ',', '.')
);
   
   
   
   
   
 
 


//$ingresos_codeudor = formatearTexto($prestamo['ingresos_codeudor']);
//$otros_ingresos_codeudor = formatearTexto($prestamo['otros_ingresos_codeudor']);
//$egresos_codeudor = formatearTexto($prestamo['egresos_codeudor']);
//$activos_codeudor = formatearTexto($prestamo['activos_codeudor']);
//$pasivos_codeudor = formatearTexto($prestamo['pasivos_codeudor']);
//$patrimonio_codeudor = formatearTexto($prestamo['patrimonio_codeudor']);
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   


					 
					 
					
					// Normalizamos por si viene en minúsculas o con espacios
$genero = strtolower(trim($genero));

if ($genero == 'femenino' || $genero == 'f') {
    // Posición de la casilla F
    $pdf->SetXY(179, 40); 
    $pdf->Write(5, 'X');
}
elseif ($genero == 'masculino' || $genero == 'm') {
    // Posición de la casilla M
    $pdf->SetXY(188, 40); 
    $pdf->Write(5, 'X');
}
else {
    // Posición de la casilla Otro
    $pdf->SetXY(199, 40); 
    $pdf->Write(5, 'X');
}

  $pdf->SetXY(140, 45);
 $pdf->Write(5, $nombre_ciudad_expedicion);
 
 
 $timestamp = strtotime($fecha_expedicion);

$dia  = date('d', $timestamp);
$mes  = date('m', $timestamp);
$anio = date('y', $timestamp); // solo 2 dígitos
 
 


// Día
$pdf->SetXY(188, 45);
$pdf->Write(5, $dia);

// Mes
$pdf->SetXY(193, 45);
$pdf->Write(5, $mes);

// Año (2 dígitos)
$pdf->SetXY(200, 45);
$pdf->Write(5, $anio);


  $pdf->SetXY(48, 50);
 $pdf->Write(5, $nombre_ciudad_nacimiento);
 
 
 
 $timestamp = strtotime($fecha_nacimiento);

$dia  = date('d', $timestamp);
$mes  = date('m', $timestamp);
$anio = date('y', $timestamp); // solo 2 dígitos
 
 


// Día
$pdf->SetXY(118, 50);
$pdf->Write(5, $dia);

// Mes
$pdf->SetXY(125, 50);
$pdf->Write(5, $mes);

// Año (2 dígitos)
$pdf->SetXY(132, 50);
$pdf->Write(5, $anio);


  $pdf->SetXY(152, 50);
 $pdf->Write(5, $nivel_escolaridad);
 
   $pdf->SetXY(112, 54);
 $pdf->Write(5, $nombre_ciudad_residencia);
 
 $pdf->SetFont('Arial','',8);
    $pdf->SetXY(152, 54);
 $pdf->Write(5, $nombre_departamento_residencia);
 $pdf->SetFont('Arial','',10);
 
  
    $pdf->SetXY(20, 58);
 $pdf->Write(5, $barrioClient);
 
 
     $pdf->SetXY(73, 58);
 $pdf->Write(5, $estrato);
 
 
      $pdf->SetXY(115, 58);
 $pdf->Write(5, $celClient);
 
       $pdf->SetXY(62, 63);
 $pdf->Write(5, $personas_cargo);
 
 
 
 $tipo_vivienda = strtolower(trim($tipo_vivienda));

if ($tipo_vivienda == 'propia') {

    $pdf->SetXY(98, 63);  // ← ajustar
    $pdf->Write(5, 'X');

} elseif ($tipo_vivienda == 'arrendada') {

    $pdf->SetXY(115, 63);  // ← ajustar
    $pdf->Write(5, 'X');

} elseif ($tipo_vivienda == 'familiar') {

    $pdf->SetXY(131, 63); // ← ajustar
    $pdf->Write(5, 'X');
	

} else {

    $pdf->SetXY(142, 63); // ← ajustar (OTRO)
    $pdf->Write(5, 'X');
}


 $ocupacion = strtolower(trim($ocupacion_general));


if ($ocupacion == 'empleado') {

    $pdf->SetXY(38, 72);
    $pdf->Write(5, 'X');

} elseif ($ocupacion == 'no empleado') {

    $pdf->SetXY(62, 72);
    $pdf->Write(5, 'X');

} elseif ($ocupacion == 'independiente') {

    $pdf->SetXY(88, 72);
    $pdf->Write(5, 'X');

} elseif ($ocupacion == 'pensionado') {

   $pdf->SetXY(112, 72);
    $pdf->Write(5, 'X');

} elseif ($ocupacion == 'estudiante') {

    $pdf->SetXY(134, 72);
    $pdf->Write(5, 'X');

} elseif ($ocupacion == 'hogar') {

    $pdf->SetXY(151, 72);
   $pdf->Write(5, 'X');


} elseif ($ocupacion == 'cesante') {

    $pdf->SetXY(171, 72);
   $pdf->Write(5, 'X');

} elseif ($ocupacion == 'inversionista') {

    $pdf->SetXY(195, 72);
    $pdf->Write(5, 'X');
}



 $condicion = strtolower(trim($condicion_medica));


if ($condicion == 'si' || $condicion == 'sí' || $condicion == '1') {

    // Casilla SI
    $pdf->SetXY(66, 76); 
   $pdf->Write(5, 'X');

} elseif ($condicion == 'no' || $condicion == '0') {

    // Casilla NO
    $pdf->SetXY(70, 76); 
   $pdf->Write(5, 'X');
}


   $pdf->SetXY(84, 76);
 $pdf->Write(5, $detalle_condicion_medica);
 





    $pdf->SetXY(23, 85);
 $pdf->Write(5, $nombre_conyuge);
 
 
 
     $pdf->SetXY(121, 85);
 $pdf->Write(5, $correo_conyugue);
 
 
 // Tipo identificación
                    
					 $tipoidentificacion_conyugue = strtolower(trim($tipoidentificacion_conyugue));

                    if($tipoidentificacion_conyugue == 'cc'){
                        $pdf->SetXY(52, 90);
                        $pdf->Write(5, 'X');
                    }

                    if($tipoidentificacion_conyugue == 'nit'){
                        $pdf->SetXY(62, 90);
                        $pdf->Write(5, 'X');
                    }

                    if($tipoidentificacion_conyugue == 'ce'){
                        $pdf->SetXY(71, 90);
                        $pdf->Write(5, 'X');
                    }


      $pdf->SetXY(89, 90);
 $pdf->Write(5, $documento_conyugue);
 
      $pdf->SetXY(139, 90);
 $pdf->Write(5, $municipioexpedicion_conyugue);
 
 
 
  $timestamp = strtotime($fechaexpedicion_conyugue);

$dia  = date('d', $timestamp);
$mes  = date('m', $timestamp);
$anio = date('y', $timestamp); // solo 2 dígitos
 
 


// Día
$pdf->SetXY(188, 90);
$pdf->Write(5, $dia);

// Mes
$pdf->SetXY(193, 90);
$pdf->Write(5, $mes);

// Año (2 dígitos)
$pdf->SetXY(199, 90);
$pdf->Write(5, $anio);

 
      $pdf->SetXY(23, 95);
 $pdf->Write(5, $celular_conyugue);
 
       $pdf->SetXY(75, 95);
 $pdf->Write(5, $tel_conyugue);
 
 
// Guardar tamaño actual (opcional si luego quieres restaurarlo)
 $pdf->SetFont('Arial','',8);

$pdf->SetXY(113, 95);
$pdf->Write(5, $empresa_conyugue);

// Volver al tamaño normal (ejemplo 12)
 $pdf->SetFont('Arial','',10);



 $ocupacioncon = strtolower(trim($ocupacion_conyugue));


if ($ocupacioncon == 'empleado') {

    $pdf->SetXY(52, 99);
    $pdf->Write(5, 'X');

} elseif ($ocupacioncon == 'independiente') {

     $pdf->SetXY(84, 99);
    $pdf->Write(5, 'X');

} elseif ($ocupacioncon == 'pensionado') {

   $pdf->SetXY(109, 99);
    $pdf->Write(5, 'X');

} elseif ($ocupacioncon == 'estudiante') {

    $pdf->SetXY(136, 99);
    $pdf->Write(5, 'X');

} elseif ($ocupacioncon == 'hogar') {

    $pdf->SetXY(159, 99);
   $pdf->Write(5, 'X');


} elseif ($ocupacioncon == 'cesante') {

    $pdf->SetXY(189, 99);
   $pdf->Write(5, 'X');

} 
 
 
 
      $pdf->SetXY(41, 108);
 $pdf->Write(5, $nombre_empresa);
 
 
       $pdf->SetXY(123, 108);
 $pdf->Write(5, $tipocontrato_empresa);


 
  $timestamp = strtotime($fechaingreso_empresa);

$dia  = date('d', $timestamp);
$mes  = date('m', $timestamp);
$anio = date('y', $timestamp); // solo 2 dígitos
 
 


// Día
$pdf->SetXY(180, 108);
$pdf->Write(5, $dia);

// Mes
$pdf->SetXY(190, 108);
$pdf->Write(5, $mes);

// Año (2 dígitos)
$pdf->SetXY(200, 108);
$pdf->Write(5, $anio);



       $pdf->SetXY(45, 113);
$pdf->Write(
    5,
    '$ ' . number_format((float)$totaldevengado_empresa, 0, ',', '.')
);
 
 
 
       $pdf->SetXY(105, 113);

 
 
 $pdf->Write(
    5,
    '$ ' . number_format((float)$totaldescuentos_empresa, 0, ',', '.')
);


 
    $pdf->SetXY(163, 113);

 
 
 
  $pdf->Write(
    5,
    '$ ' . number_format((float)$netopagar_empresa, 0, ',', '.')
);
 
 
     $pdf->SetXY(42, 117);
 $pdf->Write(5, $direccion_empresa);
 
 
      $pdf->SetXY(169, 117);
 $pdf->Write(5, $ciudad_empresa);
 
 
       $pdf->SetXY(42, 121);
 $pdf->Write(5, $ocupacion_empresa);
 
 
       $pdf->SetXY(78, 121);
 $pdf->Write(5, $cargo_empresa);
 
 
  
       $pdf->SetXY(33, 131);

 
 
   $pdf->Write(
    5,
    '$ ' . number_format((float)$totalingresos, 0, ',', '.')
);
 
 
        $pdf->SetXY(105, 131);

 
 
    $pdf->Write(
    5,
    '$ ' . number_format((float)$otrosingresos, 0, ',', '.')
);
 
 
     $pdf->SetXY(170, 131);

 
 
     $pdf->Write(
    5,
    '$ ' . number_format((float)$totalegresos, 0, ',', '.')
);
 
 
 
 
 
  
      $pdf->SetXY(33, 135);

 
      $pdf->Write(
    5,
    '$ ' . number_format((float)$activos, 0, ',', '.')
);
 
 
  
      $pdf->SetXY(105, 135);

 
       $pdf->Write(
    5,
    '$ ' . number_format((float)$pasivos, 0, ',', '.')
);
 
 
 
 
  
     $pdf->SetXY(170, 135);

 
        $pdf->Write(
    5,
    '$ ' . number_format((float)$patrimonios, 0, ',', '.')
);
 
 			
                }
				
				if($i == 2){

    if(!empty($prestamo['firma_cliente'])){

        $rutaFirma = __DIR__ . '/../firmas/' . $prestamo['firma_cliente'];

        if(file_exists($rutaFirma)){
			
			$pdf->Image($rutaFirma, 13, 205, 60);
        }
    }

}




				
				
				
			
if (strpos($archivo, 'SOLICITUD DE CREDITO') !== false && $i == 2) {		
				
 $timestamp = strtotime($fecha_pago);

$dia  = date('d', $timestamp);
$mes  = date('m', $timestamp);
$anio = date('y', $timestamp); // solo 2 dígitos
 
 


// Día
$pdf->SetXY(32, 76);
$pdf->Write(5, $dia);

// Mes
$pdf->SetXY(38, 76);
$pdf->Write(5, $mes);

// Año (2 dígitos)
$pdf->SetXY(43, 76);
$pdf->Write(5, $anio);


  $pdf->SetXY(74, 76);
   $pdf->Write(5, $monto_primera_cuota);

  $pdf->SetXY(190, 76);
   $pdf->Write(5, $numero_cuota);
   
   
   
//----------------------Aqui Inicia la pagina 2 --------------------


        // Documento Firma
                     $pdf->SetFont('Arial','',10);
                    $pdf->SetXY(22, 40);
                    $pdf->Write(5, $doccliente);
					

					
					
					

                     $pdf->SetFont('Arial','',10);
                    $pdf->SetXY(52, 40);
                    $pdf->Write(5, $nombre_ciudad_expedicion);
					
					
					
					
        // Documento Firma deudor
                     $pdf->SetFont('Arial','',10);
                    $pdf->SetXY(120, 40);
                    $pdf->Write(5, $numerodocumentocodeudor);
					
					
				

   					
					
        // Documento Firma deudor
                     $pdf->SetFont('Arial','',10);
                    $pdf->SetXY(165, 40);
                    $pdf->Write(5, $lugarexpedicioncodeudor);
					
   
   
   
   
  }  



} // ← CIERRE DE SOLICITUD DE CREDITO



// 🔥 AHORA VA EL OTRO PDF
if(strpos($archivo, 'AUTORIZACION DECLARACION') !== false && $i == 1){

 $pdf->SetFont('Arial','',15);
    $pdf->SetXY(36, 50);
    $pdf->Write(5, $nombreCompleto);



 $pdf->SetXY(158, 38);
                    $pdf->Write(5, $folio);

 
                    $pdf->SetXY(158, 50);
                    $pdf->Write(5, $doccliente);


 $pdf->SetXY(69, 120);

$pdf->Write(
    5,
    number_format((float)$tasa_interes, 2, '.', '') . '%'
);
					
					
					       $pdf->SetXY(159, 120);
                   $pdf->Write(5, $multa_mora.'%');
					
					
					
					       $pdf->SetXY(37, 207);
                   $pdf->Write(5, $nombre_ciudad_residencia.',');		
				   
				   
				   
				   $pdf->SetXY(60, 207); // ajusta la coordenada exacta
$pdf->Write(5, $fecha_actual);
				   
				   
				   
				       if(!empty($prestamo['firma_cliente'])){

        $rutaFirma = __DIR__ . '/../firmas/' . $prestamo['firma_cliente'];

        if(file_exists($rutaFirma)){
			
			$pdf->Image($rutaFirma, 25, 208, 60);
        }
    }
	
	
	
				   
				   
					
					
}



if(strpos($archivo, 'AUTORIZACION CONSULTA SUDAMERIS') !== false && $i == 1){
	
	
	
	
					       if(!empty($prestamo['firma_cliente'])){

        $rutaFirma = __DIR__ . '/../firmas/' . $prestamo['firma_cliente'];

        if(file_exists($rutaFirma)){
			
			$pdf->Image($rutaFirma, 45, 128, 60);
        }
    }
	
	  $pdf->SetXY(100, 139);
                    $pdf->Write(5, $doccliente);



 $pdf->SetFont('Arial','',10);
    $pdf->SetXY(49, 145);
    $pdf->Write(5, $nombreCompleto);
	
	
	        // Dirección
                    $pdf->SetXY(52, 150);
                    $pdf->MultiCell(100, 5, $direccion);
					
					      $pdf->SetXY(52, 156);
							$pdf->Write(5, $celClient);
							
							
											   $pdf->SetXY(48, 216); // ajusta la coordenada exacta
												$pdf->Write(5, $fecha_actual);
							
							
							       $pdf->SetXY(115, 217);
                   $pdf->Write(5, $nombre_ciudad_residencia);		
				   
							
							
	
	
	
}



if (strpos($archivo, 'Contrato de Mandato') !== false && $i == 1) {

  $pdf->SetFont('Arial','',10);
    $pdf->SetXY(47, 78);
    $pdf->Write(5, $nombreCompleto);
	
	  $pdf->SetXY(50, 83);
      $pdf->Write(5, $doccliente);



}


if (strpos($archivo, 'Contrato Garantía Inmobiliaria') !== false && $i == 10) {

 $pdf->SetFont('Arial','',10);

    // Ciudad
    $pdf->SetXY(135, 100); // Ajustar si es necesario
    $pdf->Write(5, $nombre_ciudad_residencia);

    // Fecha
   $fechaContrato = new DateTime($prestamo['fechaRegistro']);

    $diaContrato  = $fechaContrato->format('d');
    $mesContrato  = $fechaContrato->format('m');
    $anioContrato = $fechaContrato->format('y');

    $pdf->SetXY(48, 105);
    $pdf->Write(5, $diaContrato);

    $pdf->SetXY(69, 105);
    $pdf->Write(5, $mesContrato);

    $pdf->SetXY(81, 105);
    $pdf->Write(5, $anioContrato);

    // Firma cliente
    if(!empty($prestamo['firma_cliente'])){

        $rutaFirma = __DIR__ . '/../firmas/' . $prestamo['firma_cliente'];

        if(file_exists($rutaFirma)){
            $pdf->Image($rutaFirma, 25, 130, 60); // Ajustar si es necesario
        }
    }

}

if (strpos($archivo, 'Mandato tránsito.pdf') !== false && $i == 1) {
	
	   $pdf->SetFont('Arial','',10);

    // Ciudad
    $pdf->SetXY(29, 49); // Ajustar si es necesario
    $pdf->Write(5, $nombre_ciudad_residencia);
	
	
	
$pdf->SetXY(145, 49);
$pdf->Write(5, (new DateTime($prestamo['fechaRegistro']))->format('d/m/y'));
	


 }

if (strpos($archivo, 'PAGARE CREDERE.pdf') !== false && $i == 2) {
	
	
    // Firma cliente
    if(!empty($prestamo['firma_cliente'])){

        $rutaFirma = __DIR__ . '/../firmas/' . $prestamo['firma_cliente'];

        if(file_exists($rutaFirma)){
            $pdf->Image($rutaFirma, 40, 272, 60); // Ajustar si es necesario
        }
    }
	
	 $pdf->SetFont('Arial','',10);
    $pdf->SetXY(49, 292);
    $pdf->Write(5, $nombreCompleto);
	
	  $pdf->SetXY(49, 304);
      $pdf->Write(5, $doccliente);
	  
	  
	      // Ciudad
    $pdf->SetXY(49, 310); // Ajustar si es necesario
    $pdf->Write(5, $nombre_ciudad_residencia);
	


                    $pdf->SetXY(49, 316);
                    $pdf->MultiCell(100, 5, $direccion);
	
	
	 $pdf->SetXY(49, 322);
							$pdf->Write(5, $celClient);
	
	

}

if (strpos($archivo, 'SOLICITUD DE INGRESO ASOCIADO.pdf') !== false && $i == 1) {

 $pdf->SetFont('Arial','',10);
    $pdf->SetXY(15, 46);
    $pdf->Write(5, $nombreCompleto);



 $ingresoasoc = strtolower(trim($tipoId));

                    if($tipoId == 'CC'){
                        $pdf->SetXY(139, 47);
                        $pdf->Write(5, 'X');
                    }


                    if($tipoId == 'CE'){
                        $pdf->SetXY(151, 47);
                        $pdf->Write(5, 'X');
                    }
					
					
					
						
$pdf->SetXY(47, 89);
$pdf->Write(5, (new DateTime($prestamo['fechaRegistro']))->format('d/m/y'));




    $pdf->SetXY(45, 116);
 $pdf->Write(5, $nombre_conyuge);
 

 
       $pdf->SetXY(162, 117);
 $pdf->Write(5, $documento_conyugue);
	

      // Aval Familiar
                    $pdf->SetXY(45, 125);
                    $pdf->Write(5, $nombreCompletoAvalFamiliar);
					
					
					
					 $pdf->SetXY(42, 160);
       $pdf->Write(
    5,
    '$ ' . number_format((float)$totalingresos, 0, ',', '.')
);
 
 
      $pdf->SetXY(107, 160);


       $pdf->Write(
    5,
    '$ ' . number_format((float)$otrosingresos, 0, ',', '.')
);


//
//
    $pdf->SetXY(170, 160);


       $pdf->Write(
    5,
    '$ ' . number_format((float)$totalegresos, 0, ',', '.')
);
//
//
//
//
// 
    $pdf->SetXY(42, 166);


       $pdf->Write(
    5,
    '$ ' . number_format((float)$activos, 0, ',', '.')
);
//
//
//
// 
 $pdf->SetXY(107, 166);


       $pdf->Write(
    5,
    '$ ' . number_format((float)$pasivos, 0, ',', '.')
);
//
//
//
// 
   $pdf->SetXY(170, 166);




       $pdf->Write(
    5,
    '$ ' . number_format((float)$patrimonios, 0, ',', '.')
);


	





}


if (strpos($archivo, 'SOLICITUD DE INGRESO ASOCIADO.pdf') !== false && $i == 2) {
	
	
						       if(!empty($prestamo['firma_cliente'])){

        $rutaFirma = __DIR__ . '/../firmas/' . $prestamo['firma_cliente'];

        if(file_exists($rutaFirma)){
			
			$pdf->Image($rutaFirma, 18, 55, 60);
        }
    }

   $pdf->SetXY(23, 67);
    $pdf->Write(5, $nombreCompleto);
	
	
	
	
 $ingresoasoc2 = strtolower(trim($tipoId));

                    if($tipoId == 'CC'){
                        $pdf->SetXY(25, 75);
                        $pdf->Write(5, 'X');
                    }


                    if($tipoId == 'CE'){
                        $pdf->SetXY(33, 81);
                        $pdf->Write(5, 'X');
                    }
					
					
$pdf->SetXY(28, 80);
$pdf->Write(5, (new DateTime($prestamo['fechaRegistro']))->format('d/m/y'));


	
	
				
	}			
	
	if (strpos($archivo, 'SOLICITUD DE RETIRO ASOCIADO.pdf') !== false && $i == 1) {
		
		
	    $pdf->SetXY(160, 19);
                    $pdf->Write(5, $folio);	
					
					
					  $pdf->SetXY(20, 88);
                    $pdf->Write(5, $nombreCompleto);	
	
	
	
	$pdf->SetXY(20, 93);
      $pdf->Write(5, $doccliente);
	  

		      // Ciudad
    $pdf->SetXY(80, 93); // Ajustar si es necesario
    $pdf->Write(5, $nombre_ciudad_residencia);
	
	
	
	  $pdf->SetXY(35, 176);
                    $pdf->Write(5, $nombreCompleto);	
	
	
		$pdf->SetXY(35, 183);
      $pdf->Write(5, $doccliente);
	  
	  
	  $pdf->SetXY(35, 189);
$pdf->Write(5, (new DateTime($prestamo['fechaRegistro']))->format('d/m/y'));
	  
	
	
	
	
	
	
    // Firma cliente
    if(!empty($prestamo['firma_cliente'])){

        $rutaFirma = __DIR__ . '/../firmas/' . $prestamo['firma_cliente'];

        if(file_exists($rutaFirma)){
            $pdf->Image($rutaFirma, 35, 173, 60); // Ajustar si es necesario
        }
    }
	
	
	
	
	
	}
	
	
	
	
	
	
				
        } // cierre for

    } // cierre foreach

    $pdf->Output("DOCUMENTOS_HIPOTECA.pdf", "I");
    exit;

?>