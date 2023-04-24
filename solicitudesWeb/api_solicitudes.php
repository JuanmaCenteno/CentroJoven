<?php

if (!isset($_SESSION)) {
    session_start();
}

// Variable de Sesión ID Reseller/Distribuidor
if (!isset($_SESSION['REVENDEDOR'])) {
    $_SESSION['REVENDEDOR'] = 0;
}

include_once $_SERVER['DOCUMENT_ROOT'] . "/solicitudesWeb/conexDB.php";
header('Content-type: application/json; charset=utf-8');

$accion = filter_input(INPUT_GET, 'accion', FILTER_SANITIZE_STRING);
$estado = filter_input(INPUT_GET, 'estado', FILTER_SANITIZE_STRING);
$plataforma = filter_input(INPUT_GET, 'plataforma', FILTER_SANITIZE_STRING);
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
$icc = filter_input(INPUT_GET, 'icc', FILTER_SANITIZE_STRING);
$est = filter_input(INPUT_GET, 'est', FILTER_SANITIZE_NUMBER_INT);
$fijo = filter_input(INPUT_GET, 'fijo', FILTER_SANITIZE_STRING);
$idTransporte = filter_input(INPUT_GET, 'idTransporte', FILTER_SANITIZE_STRING);
$idEmp = filter_input(INPUT_GET, 'idEmp', FILTER_SANITIZE_STRING);

if ($plataforma != "") {
    $db = DB::getInstance($plataforma);
}

$row = null;
$resultado = [];

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

switch ($accion) {
        // Lista de Nombres para el ComboBox
    case "listaSolicitudes":
        $query = "SELECT * FROM solicitudesweb
        WHERE ESTADO like ?
        ORDER BY ESTADO desc;";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $estado);
        $stmt->execute();
        $res = $stmt->get_result();
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            array_push($resultado, $row);
        }
        $resultado = convert_from_latin1_to_utf8_recursively($resultado);
        break;

    case "listaTarifas":
        if ($plataforma == "MOVIL") {
            $query = "SELECT t.ID as id, CONCAT(NOMBRE_PUBLICO, ' | ', PVP, '€') as apellidos 
                      FROM multiplataforma.tarifas_masmovil_reseller t 
                      INNER JOIN multiplataforma.tarifas_masmovil_reseller_precios p ON p.ID_TARIFA = t.ID 
                      INNER JOIN multiplataforma.empresas e ON e.ID = t.ID_RESELLER WHERE e.ID = ? AND ACTIVO = 1 AND VISIBLE = 1";
            $stmt = $db->prepare($query);
            $stmt->bind_param('s', $idEmp);
            $stmt->execute();
            $res = $stmt->get_result();
            $resultado = [];
            while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
                array_push($resultado, $row);
            }
        } else {
            $query = "SELECT t.codigoProductoFtth as id, CONCAT(descripcionProductoFtth, ' | ', precioVentaFtth, '€') as apellidos 
            FROM multiplataforma.FTTHProductos p INNER JOIN multiplataforma.FTTHTarifasReseller t
            ON p.idProductoFtth = t.codigoProductoFtth INNER JOIN multiplataforma.FTTHPrecios pr
            ON pr.idPrecioFtth = t.codigoPrecioFtth
            WHERE t.codigoResellerFtth = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param('s', $idEmp);
            $stmt->execute();
            $res = $stmt->get_result();
            $resultado = [];
            while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
                array_push($resultado, $row);
            }
        }
        break;

    case "cambiarIcc":
        error_log("CAMBIAR ICC --> " . $icc . " --> " . $id);
        $query = "UPDATE solicitudesweb SET NUM_ICC_PORTABILIDAD = ? WHERE ID_TRANSPORTE = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('ss', $icc, $id);
        $stmt->execute();
        break;

    case "cambiarEstado":
        $query = "UPDATE solicitudesweb SET ESTADO = ? WHERE ID_TRANSPORTE = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('is', $est, $id);
        $stmt->execute();
        break;

    case "comprobarFijo":
        $query = "SELECT * FROM multiplataforma.LineasFijasActivas WHERE NumeroFijo = ?;";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $fijo);
        $stmt->execute();
        $res = $stmt->get_result();
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            array_push($resultado, $row);
        }
        $resultado = convert_from_latin1_to_utf8_recursively($resultado);
        break;

    case "imprimirEtiqueta":
        $url = "https://www.genei.es/json_interface/obtener_etiquetas_envio"; // OBTENER ETIQUETAS ENVÍO
        // HACEMOS EL POST A GENEI PARA COMPROBAR EL ESTADO DEL ENVÍO
        $field = [
            "usuario_servicio" => "administracion.telefonia@nexwrf.es75",
            "password_servicio" => "6cnk4gpq",
            "codigo_envio" => $idTransporte,
            "servicio" => "api",
        ];
        error_log("IMPRIMIR ETIQUETA --> " . print_r($field, true));
        $fields_string = json_encode($field);
        error_log("IMPRIMIR ETIQUETA JSON --> " . $fields_string);
        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, true); // Required for HTTP error codes to be reported via our call to curl_error($ch)
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $resultado = curl_exec($ch);
        error_log("RESULTADO ETIQUETA --> " . $resultado);
        break;

    case "seguimientoPedido":
        $url = "https://www.genei.es/json_interface/obtener_codigo_envio"; // OBTENER CÓDIGO SEGUIMIENTO
        // HACEMOS EL POST A GENEI PARA COMPROBAR EL ESTADO DEL ENVÍO
        $field = [
            "usuario_servicio" => "administracion.telefonia@nexwrf.es75",
            "password_servicio" => "6cnk4gpq",
            "codigo_envio_plataforma" => $idTransporte,
            "servicio" => "api",
        ];
        error_log("CODIGO ENVIO --> " . print_r($field, true));
        $fields_string = json_encode($field);
        error_log("CODIGO ENVIO JSON --> " . $fields_string);
        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, true); // Required for HTTP error codes to be reported via our call to curl_error($ch)
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $resultado = curl_exec($ch);
        error_log("RESULTADO CODIGO ENVÍO --> " . $resultado);
        break;
    case 'AltaAPI':
        $query = "SELECT * FROM multiplataforma.solicitudesweb WHERE ID_TRANSPORTE = ?;";
        error_log("ALTA API --> " . $query);
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $fijo);
        $stmt->execute();
        $res = $stmt->get_result();
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            array_push($resultado, $row);
        }
        $resultado = convert_from_latin1_to_utf8_recursively($resultado);
        error_log("RESULTADO ALTA API --> " . print_r($resultado));

        break;
    case 'PortaAPI':
        $query = "SELECT * FROM multiplataforma.solicitudesweb WHERE ID_TRANSPORTE = ?;";
        error_log("PORTA API --> " . $query);
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $fijo);
        $stmt->execute();
        $res = $stmt->get_result();
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            array_push($resultado, $row);
        }
        $resultado = convert_from_latin1_to_utf8_recursively($resultado);
        error_log("RESULTADO PORTA API --> " . print_r($resultado));

        break;
}

//print_r($resultado);
echo (json_encode($resultado));
//echo json_last_error_msg();
