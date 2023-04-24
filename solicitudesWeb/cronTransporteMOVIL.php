<?php

/* COMPRUEBA EL ESTADO DE ENVÍO DE LOS PAQUETES */

if (!isset($_SESSION)) {
    session_start();
}

// Variable de Sesión ID Reseller/Distribuidor
if (!isset($_SESSION['REVENDEDOR'])) {
    $_SESSION['REVENDEDOR'] = 1;
}

include_once $_SERVER['DOCUMENT_ROOT'] . "/solicitudesWeb/conexDB.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/clases/masmovil/SMSMasMovilAPI.php";
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/util.php';

$util = new util();
$sClient = new SMSMasMovilAPI(USER_SMS_MASMOVIl, PASS_SMS_MASMOVIl);
header('Content-type: application/json; charset=utf-8');

// COMPROBAMOS SI HA LLEGADO
if (strpos("", "ENTREGADO") !== false) {
    echo "ENCONTRADO";
} else {
    echo "NO ENCONTRADO";
}

$db = DB::getInstance('MOVIL');
$row = null;
$arrado = [];
$url = "https://www.genei.es/json_interface/obtener_codigo_envio"; // COMPROBAR STADO ENVÍO

// PRIMERO CARGAMOS LA LISTA DE SOLICITUDES

$query = "SELECT * FROM solicitudesweb WHERE ESTADO = 2 OR ESTADO = 3";
$res = $db->query($query);
$arrado = [];
while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
    array_push($arrado, $row);
}
$arrado = convert_from_latin1_to_utf8_recursively($arrado);

// RECORREMOS LA LISTA DE SOLICITUDES PARA VER SI HA CAMBIADO EL ESTADO DEL ENVÍO
for ($i = 0; $i < sizeof($arrado); $i++) {

    $tr = $arrado[$i]['ID_TRANSPORTE'];
    $est = $arrado[$i]['ESTADO_SIM'];
    $tlf = $arrado[$i]['NUM_TLF_PORTABILIDAD'];
    $alta = $arrado[$i]['ALTA'];
    if ($alta == "SI") {
        $alta = "Alta";
    } else {
        $alta = "Portabilidad";
    }
    echo "OK --> " . $tr . " --> " . $est . " --> " . $tlf . " --> " . $alta;
    // HACEMOS EL POST A GENEI PARA COMPROBAR EL ESTADO DEL ENVÍO
    $field = [
        "usuario_servicio" => "administracion.telefonia@nexwrf.es75",
        "password_servicio" => "6cnk4gpq",
        "servicio" => "api",
        "codigo_envio_plataforma" => $tr
    ];
    // Para ver lo que está mandando
    echo json_encode($field);

    // JSON DATA
    $field = json_encode($field);

    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FAILONERROR, true); // Required for HTTP error codes to be reported via our call to curl_error($ch)
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $field);

    //So that curl_exec returns the contents of the cURL; rather than echoing it
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //execute post
    $arr = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Request Error:' . curl_error($ch);
    }
    curl_close($ch);
    echo "RESULTADO\n";
    error_log("RESULTADO APITRANS --> " . $arr);
    var_dump($arr);

    $arr = json_decode($arr, true);
    echo "ARRAY RESULTADO\n";
    print_r($arr);
    // SI EL ESTADO ES DIFERENTE SE CAMBIA EN LA DB
    if ($arr['nombre_estado'] != $est) {
        echo "DIFERENTE\n";
        echo $arr['nombre_estado']. "\n";
        cambiarEstado($db, 3, $arr['nombre_estado'], $tr);
        // SI EL PEDIDO HA SIDO ENTREGADO, SE MANDA EL SMS CON LAS INSTRUCCIONES
        if (strpos($arr['nombre_estado'], "ENTREGADO") !== false) {
            echo "DENTRO SMS ";
            cambiarEstado($db, 4, $arr['nombre_estado'], $tr);
            $enlace = "http://aiongest.internetinfraestructuras.es/solicitudesWeb/validarPorta.php?id=" . $tr;
            $msg = "Al recibir su SIM de Netvoz accede para finalizar su " . $alta . ": " .  $enlace;
            enviarSms($sClient, $tlf, $msg);
        }
    }
}

//print_r($arrado);
echo (json_encode($arrado));
//echo json_last_error_msg();

function convert_from_latin1_to_utf8_recursively($dat)
{
    if (is_string($dat)) {
        return utf8_encode($dat);
    } elseif (is_array($dat)) {
        $ret = [];
        foreach ($dat as $i => $d) $ret[$i] = convert_from_latin1_to_utf8_recursively($d);

        return $ret;
    } elseif (is_object($dat)) {
        foreach ($dat as $i => $d) $dat->$i = convert_from_latin1_to_utf8_recursively($d);

        return $dat;
    } else {
        return $dat;
    }
}

function enviarSms($sClient, $numTlf, $msg)
{
    try {
        $marca = MARCA_COMERCIAL_MOVIL;
        $response = $sClient->checkBalanceMasMovil();
        $respuestaSMS = new SimpleXMLElement($response, null, false);
        echo "SALDO : " . $respuestaSMS->checkBalance->saldo;
        error_log("SALDO : " . $respuestaSMS->checkBalance->saldo);
        if ($respuestaSMS->checkBalance->saldo > 0) {
            echo "Saldo : " . $respuestaSMS->checkBalance->saldo;
            error_log("Saldo : " . $respuestaSMS->checkBalance->saldo);
            $dst = '34' . $numTlf;
            $response = $sClient->sendSmsMasMovil($marca, $dst, $msg, null);
            error_log($response);
            echo $response;
        } else {
            error_log("No hay saldo : " . $respuestaSMS->checkBalance->saldo);
            echo "No hay saldo : " . $respuestaSMS->checkBalance->saldo;
        }
    } catch (SoapFault $e) {
        var_dump($e);
    }
}

function cambiarEstado($db, $estado, $sim, $idTransporte)
{
    if ($estado == 4) {
        $fecha = date('Y-m-d', time());
    } else {
        $fecha = null;
    }

    $query = "UPDATE solicitudesweb SET ESTADO = ?, ESTADO_SIM = ?, FECHA_ENTREGA_SIM = ? WHERE ID_TRANSPORTE = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('isss', $estado, $sim, $fecha, $idTransporte);
    $stmt->execute();
}