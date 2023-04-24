<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once $_SERVER['DOCUMENT_ROOT'] . "/solicitudesWeb/conexDB.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/clases/masmovil/SMSMasMovilAPI.php";
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/util.php';

$plataforma = filter_input(INPUT_POST, 'plataforma', FILTER_SANITIZE_STRING);

$util = new util();
$sClient = new SMSMasMovilAPI(USER_SMS_MASMOVIl, PASS_SMS_MASMOVIl);
$emailNetvoz = EMAIL_SOLICITUDES_FTTH;
$db = DB::getInstance($plataforma);

// Variables para insertar
// Declaracion en blanco
$numeroDocumento = "";
$nombreRazonSocial = "";
$nombreTitular = "";
$apellido1Titular = "";
$apellido2Titular = "";
$apellidosTitular = "";
$diaFechaNacimientoTitular = "";
$mesFechaNacimientoTitular = "";
$anioFechaNacimientoTitular = "";
$fechaNacimientoTitular = "";
$numeroTelefonoTitular = "";
$emailTitular = "";
$numeroTelefonoNuevo = "";
$numeroICCNuevo = "";
$titularCuentaBancaria = "";
$codigoCuentaBancaria = "";
$entidadCuentaBancaria = "";
$oficinaCuentaBancaria = "";
$dcCuentaBancaria = "";
$numeroCuentaBancaria = "";
$iban = "";
$codigoPostal = "";
$nombreCalle = "";
$numeroCalle = "";
$plantaCalle = "";
$puertaCalle = "";
$bloqueCalle = "";
$escaleraCalle = "";
$direccionEnvio = "";
$municipio = "";
$provincia = "";
$codigoPostalDireccionFacturacion = "";
$nombreCalleDireccionFacturacion = "";
$numeroCalleDireccionFacturacion = "";
$plantaCalleDireccionFacturacion = "";
$puertaCalleDireccionFacturacion = "";
$bloqueCalleDireccionFacturacion = "";
$escaleraCalleDireccionFacturacion = "";
$municipioDF = "";
$provinciaDF = "";
$direccionFacturacion = "";
$idTarifa = "";
$nombreTarifa = "";
$precioTarifa = "";
$alta = "SI";
$codigo = "";
$numTlf = "";

// Declaración POST
$numeroDocumento = filter_input(INPUT_POST, 'numeroDocumento', FILTER_SANITIZE_STRING);
$nombreRazonSocial = filter_input(INPUT_POST, 'nombreRazonSocial', FILTER_SANITIZE_STRING);
$nombreTitular = filter_input(INPUT_POST, 'nombreTitular', FILTER_SANITIZE_STRING);
$apellido1Titular = filter_input(INPUT_POST, 'apellido1Titular', FILTER_SANITIZE_STRING);
$apellido2Titular = filter_input(INPUT_POST, 'apellido2Titular', FILTER_SANITIZE_STRING);
$apellidosTitular = $apellido1Titular . " " . $apellido2Titular;
$nombreCompleto = $nombreTitular . " " . $apellidosTitular;
$diaFechaNacimientoTitular = filter_input(INPUT_POST, 'diaFechaNacimientoTitular', FILTER_SANITIZE_STRING);
$mesFechaNacimientoTitular = filter_input(INPUT_POST, 'mesFechaNacimientoTitular', FILTER_SANITIZE_STRING);
$anioFechaNacimientoTitular = filter_input(INPUT_POST, 'anioFechaNacimientoTitular', FILTER_SANITIZE_STRING);
$fechaNacimientoTitular = $anioFechaNacimientoTitular . "-" . $mesFechaNacimientoTitular . "-" . $diaFechaNacimientoTitular;
$numeroTelefonoTitular = filter_input(INPUT_POST, 'numeroTelefonoTitular', FILTER_SANITIZE_STRING);
$emailTitular = filter_input(INPUT_POST, 'emailTitular', FILTER_SANITIZE_STRING);
$botonPortabilidad = filter_input(INPUT_POST, 'botonPortabilidad', FILTER_SANITIZE_STRING);
$botonAlta = filter_input(INPUT_POST, 'botonAlta', FILTER_SANITIZE_STRING);
if ($botonPortabilidad == "on") {
    $alta = "NO";
}
if ($botonAlta == "on") {
    $alta = "SI";
}
$numeroTelefonoNuevo = filter_input(INPUT_POST, 'numeroTelefonoNuevo', FILTER_SANITIZE_STRING);
if ($numeroTelefonoNuevo == "") {
    $numeroTelefonoNuevo = $numeroTelefonoTitular;
}
$numeroICCNuevo = filter_input(INPUT_POST, 'numeroICCNuevo', FILTER_SANITIZE_STRING);
$titularCuentaBancaria = filter_input(INPUT_POST, 'titularCuentaBancaria', FILTER_SANITIZE_STRING);
$codigoCuentaBancaria = filter_input(INPUT_POST, 'codigoCuentaBancaria', FILTER_SANITIZE_STRING);
$entidadCuentaBancaria = filter_input(INPUT_POST, 'entidadCuentaBancaria', FILTER_SANITIZE_STRING);
$oficinaCuentaBancaria = filter_input(INPUT_POST, 'oficinaCuentaBancaria', FILTER_SANITIZE_STRING);
$numCuenta1 = filter_input(INPUT_POST, 'numCuenta1', FILTER_SANITIZE_STRING);
$numCuenta2 = filter_input(INPUT_POST, 'numCuenta2', FILTER_SANITIZE_STRING);
$numCuenta3 = filter_input(INPUT_POST, 'numCuenta3', FILTER_SANITIZE_STRING);
$iban = $codigoCuentaBancaria . " " . $entidadCuentaBancaria . " " . $oficinaCuentaBancaria . " " . $numCuenta1 . " " . $numCuenta2 . " " . $numCuenta3;
$codigoPostal = filter_input(INPUT_POST, 'codigoPostalInstalacion', FILTER_SANITIZE_STRING);
$nombreCalle = filter_input(INPUT_POST, 'nombreCalleInstalacion', FILTER_SANITIZE_STRING);
$numeroCalle = filter_input(INPUT_POST, 'numeroCalleInstalacion', FILTER_SANITIZE_STRING);
$plantaCalleDireccionInstalacion = filter_input(INPUT_POST, 'plantaCalleDireccionInstalacion', FILTER_SANITIZE_STRING);
$puertaCalleDireccionInstalacion = filter_input(INPUT_POST, 'puertaCalleDireccionInstalacion', FILTER_SANITIZE_STRING);
$bloqueCalleDireccionInstalacion = filter_input(INPUT_POST, 'bloqueCalleDireccionInstalacion', FILTER_SANITIZE_STRING);
$escaleraCalleDireccionInstalacion = filter_input(INPUT_POST, 'escaleraCalleDireccionInstalacion', FILTER_SANITIZE_STRING);
$municipio = filter_input(INPUT_POST, 'municipio', FILTER_SANITIZE_STRING);
$provincia = filter_input(INPUT_POST, 'provincia', FILTER_SANITIZE_STRING);
$direccionEnvio = $nombreCalle . ", " . $numeroCalle;
if ($bloqueCalle != "") {
    $direccionEnvio = $direccionEnvio . " Bloque: " . $bloqueCalle;
}
if ($escaleraCalle != "") {
    $direccionEnvio = $direccionEnvio . " Escalera: " . $escaleraCalle;
}
if ($plantaCalle != "") {
    $direccionEnvio = $direccionEnvio . " Planta: " . $plantaCalle;
}
if ($puertaCalle != "") {
    $direccionEnvio = $direccionEnvio . " Puerta: " . $puertaCalle;
}
// DIRECCION FACTURACION
$codigoPostalDireccionFacturacion = filter_input(INPUT_POST, 'codigoPostalDireccionFacturacion', FILTER_SANITIZE_STRING);
$nombreCalleDireccionFacturacion = filter_input(INPUT_POST, 'nombreCalleDireccionFacturacion', FILTER_SANITIZE_STRING);
$numeroCalleDireccionFacturacion = filter_input(INPUT_POST, 'numeroCalleDireccionFacturacion', FILTER_SANITIZE_STRING);
$plantaCalleDireccionFacturacion = filter_input(INPUT_POST, 'plantaCalleDireccionFacturacion', FILTER_SANITIZE_STRING);
$puertaCalleDireccionFacturacion = filter_input(INPUT_POST, 'puertaCalleDireccionFacturacion', FILTER_SANITIZE_STRING);
$bloqueCalleDireccionFacturacion = filter_input(INPUT_POST, 'bloqueCalleDireccionFacturacion', FILTER_SANITIZE_STRING);
$escaleraCalleDireccionFacturacion = filter_input(INPUT_POST, 'escaleraCalleDireccionFacturacion', FILTER_SANITIZE_STRING);
$municipioDF = filter_input(INPUT_POST, 'municipioDF', FILTER_SANITIZE_STRING);
$provinciaDF = filter_input(INPUT_POST, 'provinciaDF', FILTER_SANITIZE_STRING);
if ($bloqueCalleDireccionFacturacion != "") {
    $direccionFacturacion = $direccionFacturacion . " Bloque: " . $bloqueCalleDireccionFacturacion;
}
if ($escaleraCalleDireccionFacturacion != "") {
    $direccionFacturacion = $direccionFacturacion . " Escalera: " . $escaleraCalleDireccionFacturacion;
}
if ($plantaCalleDireccionFacturacion != "") {
    $direccionFacturacion = $direccionFacturacion . " Planta: " . $plantaCalleDireccionFacturacion;
}
if ($puertaCalleDireccionFacturacion != "") {
    $direccionFacturacion = $direccionFacturacion . " Puerta: " . $puertaCalleDireccionFacturacion;
}
if ($direccionFacturacion == "") {
    $direccionFacturacion = $direccionEnvio;
    $codigoPostalDireccionFacturacion = $codigoPostal;
    $municipioDF = $municipio;
    $provinciaDF = $provincia;
}
$idTarifa = filter_input(INPUT_POST, 'idTarifa', FILTER_SANITIZE_STRING);
$nombreTarifa = filter_input(INPUT_POST, 'nombreTarifa', FILTER_SANITIZE_STRING);
$precioTarifa = filter_input(INPUT_POST, 'precioTarifa', FILTER_SANITIZE_STRING);


$codigo = filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_STRING);
$numTlf = filter_input(INPUT_POST, 'numTlf', FILTER_SANITIZE_STRING);

if ($alta == "SI") {
    $mensajePorta = "Alta";
} else {
    $mensajePorta = "Portabilidad";
}

// Mensaje Solicitud

$mensaje = "<html>
<body>
  <h2 style='color:#cc3366'>Tu solicitud</h2>
  <ul>
    <li>Nombre: <strong>" . $nombreTitular . "</strong></li>
    <li>Apellidos: <strong>" . $apellidosTitular . "</strong></li>
    <li>Documento de identidad: <strong>" . $numeroDocumento . "</strong></li>
    <li>Fecha de Nacimiento: <strong>" . $fechaNacimientoTitular . "</strong></li>
    <li>Dirección: <strong>" . $direccionEnvio . "</strong></li>
    <li>Teléfono de contacto: <strong>" . $numeroTelefonoTitular . "</strong> </li>
    <li>Fecha de la contratación: <strong>" . date("Y/m/d") . "</strong></li>
    <li>Tarifa contratada: <strong>" . $nombreTarifa . "</strong></li>
    <li>Tipo de alta: <strong>Móvil - " . $mensajePorta . "</strong></li>
    <li>Precio: <strong>" . $precioTarifa . "/mes (IVA incl. - 21%)</strong></li>
  </ul>
  <br />
  <p>Excluído Roaming fuera del Espacio Económico Europeo y num. especiales, premium e internacionales. Llamadas y SMS no
    incluidos según el catálogo de precios publicados en la web de Netvoz.</p>
  <br />
</body>
</html>";

// Código BD

if ($codigo == "") {
    $idTransporte = strtoupper(substr(sha1(mt_rand()), 17, 8));

    // Inserción Solicitud
    $query = "INSERT INTO multiplataforma.solicitudesweb VALUES(null,3,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,6,null,null)";
    $stmt = $db->prepare($query);
    $stmt->bind_param('ssssssssssssssssssssssss', $idTransporte, $idTarifa, $nombreTarifa, $precioTarifa, $numeroDocumento, $nombreRazonSocial, $nombreTitular, $apellidosTitular, $fechaNacimientoTitular, $numeroTelefonoTitular, $emailTitular, $alta, $numeroTelefonoNuevo, $numeroICCNuevo, $titularCuentaBancaria, $iban, $direccionEnvio, $codigoPostal, $municipio, $provincia, $direccionFacturacion, $codigoPostalDireccionFacturacion, $municipioDF, $provinciaDF);
    $stmt->execute();
    if ($stmt) {
        echo "La solicitud se ha insertado correctamente";
        $util->enviarEmail($emailNetvoz, "NETVOZ", $emailTitular, $nombreCompleto, "", "", "Informe de tu Solicitud de Netvoz", $mensaje);
        $msg = "Tu solicitud de " . $mensajePorta  . " en Netvoz se ha realizado correctamente.";
        enviarSms($sClient, $numeroTelefonoTitular, $msg);
    } else {
        echo "Error en la inserción de solicitud";
        exit;
    }
} else {
    // Código Envío SMS e Email
    $msg = $codigo . " es el código de verificación que has solicitado en netvoz.eu";
    enviarSms($sClient, $numTlf, $msg);
}

function enviarSms($sClient, $numTlf, $msg)
{
    try {
        $marca = MARCA_COMERCIAL_MOVIL;
        $response = $sClient->checkBalanceMasMovil();
        $respuestaSMS = new SimpleXMLElement($response, null, false);
        error_log("SALDO : " . $respuestaSMS->checkBalance->saldo);
        if ($respuestaSMS->checkBalance->saldo > 0) {
            error_log("Saldo : " . $respuestaSMS->checkBalance->saldo);
            $dst = '34' . $numTlf;
            $response = $sClient->sendSmsMasMovil($marca, $dst, $msg, null);
            error_log($response);
        } else {
            error_log("No hay saldo : " . $respuestaSMS->checkBalance->saldo);
        }
    } catch (SoapFault $e) {
        var_dump($e);
    }
}

function hashearFilas($data)
{
    $hash = md5(serialize($data));
    return $hash;
}

function crearArray($idTarifa, $nombreTarifa, $precioTarifa, $numeroDocumento, $nombreRazonSocial, $nombreTitular, $apellidosTitular, $fechaNacimientoTitular, $numeroTelefonoTitular, $emailTitular, $alta, $numeroTelefonoNuevo, $numeroICCNuevo, $titularCuentaBancaria, $iban, $direccionEnvio, $direccionFacturacion)
{
    $res = array("idTarifa" => $idTarifa, "nombreTarifa" => $nombreTarifa, "precioTarifa" => $precioTarifa, "numeroDocumento" => $numeroDocumento,  "nombreRazonSocial" => $nombreRazonSocial, "nombreTitular" => $nombreTitular, "apellidosTitular" => $apellidosTitular, "fechaNacimientoTitular" => $fechaNacimientoTitular, "numeroTelefonoTitular" => $numeroTelefonoTitular, "emailTitular" => $emailTitular, "alta" => $alta, "numeroTelefonoNuevo" => $numeroTelefonoNuevo, "numeroICCNuevo" => $numeroICCNuevo,  "titularCuentaBancaria" => $titularCuentaBancaria, "iban" => $iban, "direccionEnvio" => $direccionEnvio, "direccionFacturacion" => $direccionFacturacion);
    return $res;
}

$db->close();
