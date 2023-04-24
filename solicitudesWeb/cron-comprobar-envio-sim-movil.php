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
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/util.php';

$util = new util();
header('Content-type: application/json; charset=utf-8');

$db = DB::getInstance('MOVIL');
$row = null;
$arrado = [];

// PRIMERO CARGAMOS LA LISTA DE SOLICITUDES

$query = "SELECT * FROM solicitudesweb WHERE ESTADO = 4 AND FECHA_ENTREGA_SIM IS NOT NULL";
$res = $db->query($query);
$arrado = [];
while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
    array_push($arrado, $row);
}
$arrado = convert_from_latin1_to_utf8_recursively($arrado);

$arrayCorreo = array();

// RECORREMOS LA LISTA DE SOLICITUDES PARA VER SI HA CAMBIADO EL ESTADO DEL ENVÍO
for ($i = 0; $i < sizeof($arrado); $i++) {
    $arr = $arrado[$i];
    $tr = $arrado[$i]['ID_TRANSPORTE'];
    $fechaEntrega = new DateTime($arrado[$i]['FECHA_ENTREGA_SIM']);
    $fecha = new DateTime("now");
    $dias = date_diff($fecha, $fechaEntrega);
    $dias = intval($dias->format('%a')); // DIAS DE DIFERENCIA

    if ($dias >= 7) {
        $array = array($arr['TIPO'], $arr['ID_TRANSPORTE'], $arr['ID_TARIFA'], $arr['NOMBRE_TARIFA'], $arr['PRECIO_TARIFA'], $arr['NUM_DOCUMENTO'], $arr['RAZON_SOCIAL'], $arr['NOMBRE_TITULAR'], $arr['APELLIDOS_TITULAR'], $arr['FECHA_NACIMIENTO'], $arr['NUM_TLF_TITULAR'], $arr['EMAIL'], $arr['ALTA'], $arr['NUM_TLF_PORTABILIDAD'], $arr['NUM_ICC_PORTABILIDAD'], $arr['TITULAR_CUENTA_BANCARIA'], $arr['IBAN'], $arr['DIRECCION_ENVIO'], $arr['CODIGO_POSTAL_ENVIO'], $arr['MUNICIPIO_ENVIO'], $arr['PROVINCIA_ENVIO'], $arr['DIRECCION_FACTURACION'], $arr['CODIGO_POSTAL_FACTURACION'], $arr['MUNICIPIO_FACTURACION'], $arr['PROVINCIA_FACTURACION'], $arr['ESTADO'], $arr['ESTADO_SIM'], $arr['FECHA_ENTREGA_SIM']);
        array_push($arrayCorreo, $array);
    }
}

if (sizeof($arrayCorreo > 0)) {
    $emailNetvoz = EMAIL_SOLICITUDES_FTTH;
    //$emailDestino = array("jmca111197@gmail.com", "masmovil@11400.es"); // PRUEBAS
    $emailDestino = array("ventaonline@netvoz.eu", "masmovil@11400.es");
    $mensaje = crearMensaje($arrayCorreo);
    $util->enviarEmail($emailNetvoz, "NETVOZ", $emailDestino, "", "", "", "Informe de Líneas no Validadas en más de 7 días", $mensaje);
}


function crearMensaje($array)
{
    $mensaje = "<html>
        <body>
        <h2 style='color:#cc3366'>Informe de Clientes No Validados</h2>
        <h2 style='color:#cc3366'>Total: " . sizeof($array) . "</h2>";

    for ($i = 0; $i < sizeof($array); $i++) {
        $fila = $array[$i];
        $mensaje = $mensaje .  "
        <h2 style='color:#cc3366'>Nuevo Cliente</h2>
        <ul>
            <li>ID: <strong>" . $fila[0] . "</strong></li>
            <li>ID_TRANSPORTE: <strong>" . $fila[1] . "</strong></li>
            <li>ID_TARIFA: <strong>" . $fila[2] . "</strong></li>
            <li>NOMBRE_TARIFA: <strong>" . $fila[3] . "</strong></li>
            <li>PRECIO_TARIFA: <strong>" . $fila[4] . "</strong></li>
            <li>NUM_DOCUMENTO: <strong>" . $fila[5] . "</strong> </li>
            <li>RAZON_SOCIAL: <strong>" . $fila[6] . "</strong></li>
            <li>NOMBRE_TITULAR: <strong>" . $fila[7] . "</strong></li>
            <li>APELLIDOS_TITULAR: <strong>" . $fila[8] . "</strong></li>
            <li>FECHA_NACIMIENTO: <strong>" . $fila[9] . "</strong></li>
            <li>NUM_TLF_TITULAR: <strong>" . $fila[10] . "</strong></li>
            <li>EMAIL: <strong>" . $fila[11] . "</strong></li>
            <li>ALTA: <strong>" . $fila[12] . "</strong></li>
            <li>NUM_TLF_PORTABILIDAD: <strong>" . $fila[13] . "</strong></li>
            <li>NUM_ICC_PORTABILIDAD: <strong>" . $fila[14] . "</strong></li>
            <li>TITULAR_CUENTA_BANCARIA: <strong>" . $fila[15] . "</strong> </li>
            <li>IBAN: <strong>" . $fila[16] . "</strong></li>
            <li>DIRECCION_ENVIO: <strong>" . $fila[17] . "</strong></li>
            <li>CODIGO_POSTAL_ENVIO: <strong>" . $fila[18] . "</strong></li>
            <li>MUNICIPIO_ENVIO: <strong>" . $fila[19] . "/mes (IVA incl. - 21%)</strong></li>
            <li>PROVINCIA_ENVIO: <strong>" . $fila[20] . "</strong></li>
            <li>ESTADO: <strong>" . $fila[25] . "</strong></li>
            <li>ESTADO_SIM: <strong>" . $fila[26] . "</strong></li>
            <li>FECHA_ENTREGA_SIM: <strong>" . $fila[27] . "</strong></li>            
        </ul>
        <br />
        <hr />";
    }

    $mensaje = $mensaje . "</body></html>";
    return $mensaje;
}

echo (json_encode($arrayCorreo));
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
