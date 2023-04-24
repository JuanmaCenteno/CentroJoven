<?php

if (!isset($_SESSION)) {
    session_start();
}

// Variable de Sesión ID Reseller/Distribuidor
if (!isset($_SESSION['REVENDEDOR'])) {
    $_SESSION['REVENDEDOR'] = 0;
}

include_once $_SERVER['DOCUMENT_ROOT'] . "/callCenter/conexDB.php";
include_once($_SERVER['DOCUMENT_ROOT'] . '/config/util.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/clases/Tickets.php');

$util = new util();

$accion = filter_input(INPUT_GET, 'accion', FILTER_SANITIZE_STRING);
$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
$plataforma = filter_input(INPUT_GET, 'plataforma', FILTER_SANITIZE_STRING);
$dniCliente = filter_input(INPUT_GET, 'dniCliente', FILTER_SANITIZE_STRING);
error_log("DNI CLIENTE --> " . $dniCliente);
$idEmp = filter_input(INPUT_GET, 'idEmp', FILTER_SANITIZE_STRING);
$fechaInicio = filter_input(INPUT_GET, 'fechaInicio', FILTER_SANITIZE_STRING);
$fechaFin = filter_input(INPUT_GET, 'fechaFin', FILTER_SANITIZE_STRING);
$filtro = filter_input(INPUT_GET, 'filtro', FILTER_SANITIZE_STRING);
$concepto = filter_input(INPUT_GET, 'concepto', FILTER_SANITIZE_STRING);
$tipo = filter_input(INPUT_GET, 'tipo', FILTER_SANITIZE_STRING);
$descripcion = filter_input(INPUT_GET, 'descripcion', FILTER_SANITIZE_STRING);
$asunto = filter_input(INPUT_GET, 'asunto', FILTER_SANITIZE_STRING);
$mensaje = filter_input(INPUT_GET, 'mensaje', FILTER_SANITIZE_STRING);
$imagen = filter_input(INPUT_GET, 'imagen', FILTER_SANITIZE_STRING);
$nombreCliente = filter_input(INPUT_GET, 'nombreCliente', FILTER_SANITIZE_STRING);
$numTlfCliente = filter_input(INPUT_GET, 'numTlfCliente', FILTER_SANITIZE_STRING);
$prioridad = filter_input(INPUT_GET, 'prioridad', FILTER_SANITIZE_STRING);
$estado = filter_input(INPUT_GET, 'estado', FILTER_SANITIZE_STRING);
$tipo = filter_input(INPUT_GET, 'tipo', FILTER_SANITIZE_STRING);

if ($tipo == "") {
    $tipo = "%";
}

if ($action != "") {
    $accion = $action;
    $numTicket = filter_input(INPUT_POST, 'numTicket', FILTER_SANITIZE_STRING);
    $plataforma = filter_input(INPUT_POST, 'plataforma', FILTER_SANITIZE_STRING);
}


if ($fechaInicio != "" && $fechaFin != "") {
    $fechaInicio = date("Y-m-d", strtotime($fechaInicio));
    $fechaFin = date("Y-m-d", strtotime($fechaFin));
} else {
    $fechaInicio = "2018-01-01";
    $fechaFin = date("Y-m-d", time());
}

if ($filtro == "") {
    $filtro = "fecha_factura";
} else {
    if (intval($filtro) == 2) {
        $filtro = "fecha_impago";
    } else {
        $filtro = "fecha_factura";
    }
}

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

// Función suma consumo
function sumaTodoMesAnio($util, $msidn, $mes, $anio)
{
    // pendiente tirar bien del mensual o del diario
    //$anioActual = intval(date('Y'));
    //$mesActual = intval(date('m'));
    $tabla = "cdrs_masmovil." . $anio . "_" . $mes . "_diario";
    //if ($anioActual == $anio && $mesActual == $mes)
    //    $tabla = "cdrs_masmovil." . $anio . "_" . $mes . "_diario";
    //else
    //    $tabla = "cdrs_masmovil." . $anio . "_" . $mes . "_mensual";
    $consulta = 'SELECT ROUND(SUM(TIME_TO_SEC(duracion))/60,2) as VOZ FROM ' . $tabla . ' where origen = ' . $msidn . ' AND tipo = "VOZ" 
    UNION ALL SELECT ROUND(SUM(descarga)/1024/1024,2) as DAT FROM ' . $tabla . ' where origen = ' . $msidn . ' AND tipo = "DAT"
    UNION ALL SELECT COUNT(importe) as SMS FROM ' . $tabla . ' where origen = ' . $msidn . ' AND tipo = "SMS"';
    error_log("CONSULTA DATOS CALL CENTER --> " . $consulta);
    $r = $util->consulta2($consulta, false);

    $a = array();
    while ($row = mysqli_fetch_array($r)) {
        array_push($a, $row);
    }

    //echo "ARRAY RESPUESTA SUMA: \n";
    //print_r($a);
    return $a;
}


switch ($accion) {
        // Lista de Nombres para el ComboBox
    case "listaUsuarios":
        $query = "SELECT ID as id, CONCAT('(',ID,') ', NOMBRE, ' | ', APELLIDOS) as apellidos FROM multiplataforma.usuarios;";
        $res = $db->query($query);
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            array_push($resultado, $row);
        }
        //$resultado = convert_from_latin1_to_utf8_recursively($resultado);
        break;
    case "listaNombres":
        // Comprobamos si es Reseller o Distribuidor
        if ($_SESSION['USER_LEVEL'] == 0) {
            $query = "SELECT DISTINCT id, CONCAT(NOMBRE,' | ',APELLIDOS) as apellidos FROM multiplataforma.empresas e 
            INNER JOIN factusol.lineas_facturadas f ON e.ID = f.id_customer
            WHERE concepto_devolucion IS NOT null AND concepto_devolucion not like '';";
            $res = $db->query($query);
        } else {
            $query = "SELECT DISTINCT id, CONCAT(NOMBRE,' | ',APELLIDOS) as apellidos FROM multiplataforma.empresas e 
            INNER JOIN factusol.lineas_facturadas f ON e.ID = f.id_customer
            WHERE concepto_devolucion IS NOT null AND concepto_devolucion not like '' AND ID like ? OR ID_PADRE like ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param('ss', $idReseller, $idReseller);
            $stmt->execute();
            $res = $stmt->get_result();
        }
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            array_push($resultado, $row);
        }
        $resultado = convert_from_latin1_to_utf8_recursively($resultado);
        break;

    case "listaTickets":
        $query = "SELECT number as id, est_duedate as fecha, subject as asunto, name as nombre, phone as tlf, priority as prioridad 
        FROM osticket.ost_ticket t NATURAL JOIN osticket.ost_ticket__cdata d
        WHERE t.closed IS NULL AND t.isanswered = 0 AND user_id = 459;";
        $res = $db->query($query);
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            array_push($resultado, $row);
        }
        break;

    case "insertTicket":
        //COJO ÚLTIMO ID DEL TICKET
        $query = "SELECT number as num FROM osticket.ost_ticket WHERE number like 'CC-%' ORDER BY ticket_id desc LIMIT 1;";
        $res = $db->query($query);
        $resultado = $res->fetch_array(MYSQLI_ASSOC);
        if (empty($resultado)) {
            $numTicket = "CC-0000";
        } else {
            $numTicket = "CC-" . (intval(substr($resultado['num'], strpos($resultado['num'], '-') + 1)) + 1);
        }
        error_log("NUM TICKET: " . $numTicket);

        //COJO ÚLTIMO ID DEL CALLCENTER
        $query = "SELECT ticket_id as id FROM osticket.ost_ticket ORDER BY id desc LIMIT 1;";
        $res = $db->query($query);
        $resultado = $res->fetch_array(MYSQLI_ASSOC);
        $idTicket = intval($resultado['id']) + 1;
        error_log("ID TICKET: " . $idTicket);

        // INSERCIÓN DEL TICKET en ost_ticket
        $fecha = date('Y-m-d h:m:s', strtotime(date('Y-m-d h:m:s') . " + 2 day"));
        $userId = "459";
        $deptId = "21";
        $topicId = "30";
        $staffId = "46";
        $teamId = "41";
        $ip = "5.40.81.41";
        $source = "Web";
        $query = "INSERT INTO osticket.ost_ticket VALUES(null,?,?,0,1,?,1,?,?,?,0,0,0,?,?,null,0,0,null,?,null,null,?,?,?,1)";
        $stmt = $db->prepare($query);
        $stmt->bind_param('ssssssssssss', $numTicket, $userId, $deptId, $topicId, $staffId, $teamId, $ip, $source, $fecha, $fecha, $fecha, $fecha);
        $stmt->execute();
        if ($stmt) {
            echo "El ticket se ha insertado correctamente\n";
        } else {
            echo "Error en la inserción del ticket\n";
            exit;
        }

        //INSERCIÓN DEL MENSAJE DEL TICKET
        $query = "INSERT INTO osticket.ost_ticket__cdata VALUES(?,?,?,?,?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param('sssss', $idTicket, $asunto, $nombreCliente, $numTlfCliente, $prioridad);
        $stmt->execute();
        if ($stmt) {
            echo "El asunto se ha insertado correctamente\n";
        } else {
            echo "Error en la inserción del asunto\n";
            exit;
        }
        break;

    case 'cerrarTicket':
        $fecha = date('Y-m-d h:m:s');
        $query = "UPDATE osticket.ost_ticket SET isanswered = 1, closed = ?, lastupdate = ?, updated = ? WHERE number = ?;";
        $stmt = $db->prepare($query);
        $stmt->bind_param('ssss', $fecha, $fecha, $fecha, $numTicket);
        $stmt->execute();
        if ($stmt) {
            echo "El tícket se ha modificado correctamente\n";
        } else {
            echo "Error en la modificación del tícket\n";
            exit;
        }
        break;

    case "listaDesglose":
        $query = "SELECT DISTINCT concepto_devolucion as concepto, numero_telef as numeroFijo, e.CIF as dni, CONCAT(e.NOMBRE,' | ',e.APELLIDOS) as apellidos, f.nombre_cliente, c.MOVIL as movil, f.importe_final_devol as importe, f.fecha_factura, f.fecha_impago 
            FROM multiplataforma.empresas e INNER JOIN factusol.lineas_facturadas f ON e.ID = f.id_customer
            INNER JOIN multiplataforma.clientes c ON c.DNI = f.dni_cliente
            WHERE estado_e = 2 AND c.DNI = ? AND fecha_factura BETWEEN ? AND ?;";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $dniCliente);
        $stmt->execute();
        $res = $stmt->get_result();
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            array_push($resultado, $row);
        }
        //$resultado = convert_from_latin1_to_utf8_recursively($resultado);
        break;

    case "lineasActivas":
        if ($plataforma == "MOVIL") {
            $query = "SELECT DISTINCT NUMERO as numero, r.CIF as dni, CLIENTE as nombre, t.NOMBRE_PUBLICO as nombreTarifa, clientes.DIRECCION as direccionInstalacion, CONCAT(empresas.NOMBRE, ' | ', empresas.APELLIDOS) As nombreEmpresa, r.FECHA_ALTA as fechaAltaAiongest, '' as fechaBajaAiongest, ESTADO as estado, PUK as puk, empresas.ID as idEmpresa
            FROM multiplataforma.resumen_mensual_lineas r 
            INNER JOIN multiplataforma.tarifas_masmovil_reseller t ON t.ID = r.ID_TARIFA
            LEFT JOIN empresas ON empresas.ID = r.ID_EMPRESA
            LEFT JOIN clientes ON clientes.DNI = r.CIF
            WHERE clientes.DNI = ? AND r.ESTADO not like 'B' AND clientes.ID_EMPRESA = r.ID_EMPRESA;";
            $stmt = $db->prepare($query);
            $stmt->bind_param('s', $dniCliente);
            $stmt->execute();
            $res = $stmt->get_result();
            $resultado = [];
            while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
                $numTlf = $row['numero'];
                $anio = intval(date('Y'));
                $mes = date('m');
                //$anio = 2020;
                //$mes = 12;
                //echo "Número: " . $numTlf . "\n Mes: " . $mes . "\n Año: " . $anio;
                $suma = sumaTodoMesAnio($util, $numTlf, $mes, $anio);
                $row["voz"] = $suma[0][0];
                $row["dat"] = $suma[1][0];
                $row["sms"] = $suma[2][0];
                array_push($resultado, $row);
            }
        } else {
            $query = "SELECT DISTINCT NumeroFijo as numero, DniCliente as dni, concat(clientes.NOMBRE, ' ', clientes.APELLIDOS) AS nombre, FTTHProductos.descripcionProductoFtth AS nombreTarifa, direccionInstalacion,  empresas.APELLIDOS As nombreEmpresa, FechaAltaAiongest as fechaAltaAiongest, FechaBajaAiongest as fechaBajaAiongest, Estado as estado
            FROM LineasFijasActivas
            LEFT JOIN empresas ON empresas.ID = LineasFijasActivas.IdReseller
            LEFT JOIN clientes ON clientes.DNI = LineasFijasActivas.DNICliente and clientes.ID_EMPRESA = empresas.ID
            LEFT JOIN FTTHProductos ON FTTHProductos.idProductoFtth = LineasFijasActivas.IdServicio
            LEFT JOIN FTTHTarifasReseller ON empresas.ID = FTTHTarifasReseller.codigoResellerFtth AND FTTHProductos.idProductoFtth = FTTHTarifasReseller.codigoProductoFtth 
            LEFT JOIN FTTHPrecios ON FTTHTarifasReseller.codigoPrecioFtth = FTTHPrecios.idPrecioFtth
            WHERE clientes.DNI = ?;";
            $stmt = $db->prepare($query);
            $stmt->bind_param('s', $dniCliente);
            $stmt->execute();
            $res = $stmt->get_result();
            $resultado = [];
            while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
                array_push($resultado, $row);
            }
        }
        break;

    case "listaSolicitudes":
        // TODO Solicitudes Incidencias Móvil
        if ($plataforma == "MOVIL") {
            $query = "";
            $stmt = $db->prepare($query);
            $stmt->bind_param('s', $dniCliente);
            $stmt->execute();
            $res = $stmt->get_result();
            $resultado = [];
            while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
                array_push($resultado, $row);
            }
        } else {
            $query = "SELECT * FROM multiplataforma.solicitudes_ftthmb WHERE Estado_Solicitud_ftthmb NOT LIKE 'Completada' AND DNI_Cliente_ftthmb = ?;";
            $stmt = $db->prepare($query);
            $stmt->bind_param('s', $dniCliente);
            $stmt->execute();
            $res = $stmt->get_result();
            $resultado = [];
            while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
                array_push($resultado, $row);
            }
        }
        break;

    case "listaDevoluciones":
        $tablalistado = "factusol.lineas_facturadas ";
        $tablalistado .= "LEFT JOIN factusol.facturas_emitidas ON factusol.facturas_emitidas.num_factura = factusol.lineas_facturadas.numero_factura ";
        $tablalistado .= "LEFT JOIN multiplataforma.empresas ON multiplataforma.empresas.ID = factusol.lineas_facturadas.id_customer ";

        $campos = array(
            'factusol.facturas_emitidas.cod_cli_factura', 'factusol.lineas_facturadas.id_factu', 'factusol.lineas_facturadas.fecha_factura', 'factusol.lineas_facturadas.numero_telef',
            'factusol.lineas_facturadas.dni_cliente', 'factusol.lineas_facturadas.nombre_cliente', 'factusol.lineas_facturadas.id_tarifa', 'factusol.lineas_facturadas.valor_total',
            'factusol.lineas_facturadas.estado_e', 'factusol.lineas_facturadas.fecha_impago', 'factusol.lineas_facturadas.notific_impago', 'factusol.lineas_facturadas.notific_recobro',
            'factusol.lineas_facturadas.reactivacion', 'factusol.lineas_facturadas.fecha_reactivacion', 'factusol.lineas_facturadas.gastos_devolucion',
            'factusol.lineas_facturadas.importe_final_devol', 'factusol.lineas_facturadas.referencia_devolucion', 'factusol.lineas_facturadas.documento_devolucion',
            'factusol.lineas_facturadas.estadoDevolucion', 'factusol.lineas_facturadas.id_customer', 'DATEDIFF(NOW(), factusol.lineas_facturadas.fecha_impago) AS diasImpago',
            'empresas.ID', 'empresas.ID_PADRE', 'empresas.NOMBRE', 'empresas.APELLIDOS', 'factusol.lineas_facturadas.concepto_devolucion AS concepto'
        );

        if ($plataforma == "MOVIL") {
            $tablalistado .= 'LEFT JOIN multiplataforma.resumen_mensual_lineas ON multiplataforma.resumen_mensual_lineas.NUMERO = factusol.lineas_facturadas.numero_telef INNER JOIN multiplataforma.tarifas_masmovil_reseller r ON r.ID = factusol.lineas_facturadas.id_tarifa';
            array_push($campos, "multiplataforma.resumen_mensual_lineas.CUSTOMER_ID as customer");
            array_push($campos, "multiplataforma.resumen_mensual_lineas.FECHA_ALTA as fecha_alta");
            array_push($campos, "r.NOMBRE_PUBLICO as nombreTarifa");
        }
        if ($plataforma == "FTTH") {
            $tablalistado .= "LEFT JOIN multiplataforma.LineasFijasActivas ON multiplataforma.LineasFijasActivas.DNICliente = factusol.lineas_facturadas.dni_cliente INNER JOIN multiplataforma.FTTHProductos r ON r.idProductoFtth = factusol.lineas_facturadas.id_tarifa";
            array_push($campos, "multiplataforma.LineasFijasActivas.FechaAltaAiongest AS fecha_alta");
            array_push($campos, "r.descripcionProductoFtth as nombreTarifa");
        }
        $wherelistado .= "(factusol.lineas_facturadas.estado_e = 2 OR factusol.lineas_facturadas.estado_e = 4) AND factusol.lineas_facturadas.dni_cliente = '" . $dniCliente . "' AND factusol.lineas_facturadas.id_factu LIKE '1202%'";
        $orderlistado = "factusol.lineas_facturadas.numero_telef";
        $group = "factusol.lineas_facturadas.id_factu";

        $res = $util->selectWhere($tablalistado, $campos,  $wherelistado, $orderlistado, $group);
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            array_push($resultado, $row);
        }
        break;

    case "listaDevolucionesReseller":
        $tablalistado = "factusol.lineas_facturadas ";
        $tablalistado .= "LEFT JOIN factusol.facturas_emitidas ON factusol.facturas_emitidas.num_factura = factusol.lineas_facturadas.numero_factura ";
        $tablalistado .= "LEFT JOIN multiplataforma.empresas ON multiplataforma.empresas.ID = factusol.lineas_facturadas.id_customer ";

        $campos = array(
            'factusol.facturas_emitidas.cod_cli_factura', 'factusol.lineas_facturadas.id_factu', 'factusol.lineas_facturadas.fecha_factura', 'factusol.lineas_facturadas.numero_telef',
            'factusol.lineas_facturadas.dni_cliente', 'factusol.lineas_facturadas.nombre_cliente', 'factusol.lineas_facturadas.id_tarifa', 'factusol.lineas_facturadas.valor_total',
            'factusol.lineas_facturadas.estado_e', 'factusol.lineas_facturadas.fecha_impago', 'factusol.lineas_facturadas.notific_impago', 'factusol.lineas_facturadas.notific_recobro',
            'factusol.lineas_facturadas.reactivacion', 'factusol.lineas_facturadas.fecha_reactivacion', 'factusol.lineas_facturadas.gastos_devolucion',
            'factusol.lineas_facturadas.importe_final_devol', 'factusol.lineas_facturadas.referencia_devolucion', 'factusol.lineas_facturadas.documento_devolucion',
            'factusol.lineas_facturadas.estadoDevolucion', 'factusol.lineas_facturadas.id_customer', 'DATEDIFF(NOW(), factusol.lineas_facturadas.fecha_impago) AS diasImpago',
            'empresas.ID', 'empresas.ID_PADRE', 'empresas.NOMBRE', 'empresas.APELLIDOS', 'factusol.lineas_facturadas.concepto_devolucion AS concepto'
        );

        if ($plataforma == "MOVIL") {
            $tablalistado .= 'LEFT JOIN multiplataforma.resumen_mensual_lineas ON multiplataforma.resumen_mensual_lineas.NUMERO = factusol.lineas_facturadas.numero_telef INNER JOIN multiplataforma.tarifas_masmovil_reseller r ON r.ID = factusol.lineas_facturadas.id_tarifa';
            array_push($campos, "multiplataforma.resumen_mensual_lineas.CUSTOMER_ID as customer");
            array_push($campos, "multiplataforma.resumen_mensual_lineas.FECHA_ALTA as fecha_alta");
            array_push($campos, "r.NOMBRE_PUBLICO as nombreTarifa");
        }
        if ($plataforma == "FTTH") {
            $tablalistado .= "LEFT JOIN multiplataforma.LineasFijasActivas ON multiplataforma.LineasFijasActivas.DNICliente = factusol.lineas_facturadas.dni_cliente INNER JOIN multiplataforma.FTTHProductos r ON r.idProductoFtth = factusol.lineas_facturadas.id_tarifa";
            array_push($campos, "multiplataforma.LineasFijasActivas.FechaAltaAiongest AS fecha_alta");
            array_push($campos, "r.descripcionProductoFtth as nombreTarifa");
        }
        $wherelistado .= "(factusol.lineas_facturadas.estado_e = 2 OR factusol.lineas_facturadas.estado_e = 4) AND factusol.lineas_facturadas.id_customer = '" . $dniCliente . "' AND factusol.lineas_facturadas.id_factu LIKE '1202%'";
        $orderlistado = "factusol.lineas_facturadas.numero_telef";
        $group = "factusol.lineas_facturadas.id_factu";

        $res = $util->selectWhere($tablalistado, $campos,  $wherelistado, $orderlistado, $group);
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            array_push($resultado, $row);
        }
        break;

    case "listaPreguntas":
        $query = "SELECT ID as id, TIPO as tipo, CASO as caso, RESPUESTA as respuesta, VISIBLE as visible, VISIBLE_RESELLER as visibleReseller 
                  FROM multiplataforma.guion_callcenter 
                  WHERE TIPO like ? AND VISIBLE = 1;";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $tipo);
        $stmt->execute();
        $res = $stmt->get_result();
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            array_push($resultado, $row);
        }
        break;

    case "listaPreguntasReseller":
        $query = "SELECT ID as id, TIPO as tipo, CASO as caso, RESPUESTA as respuesta, VISIBLE as visible, VISIBLE_RESELLER as visibleReseller FROM multiplataforma.guion_callcenter 
        WHERE TIPO like ? AND VISIBLE = 1 AND VISIBLE_RESELLER = 1;";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $tipo);
        $stmt->execute();
        $res = $stmt->get_result();
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            array_push($resultado, $row);
        }
        break;

    case "listaUsuariosGuion":
        $query = "SELECT * FROM multiplataforma.usuarios_guion_callcenter WHERE ID_USUARIO = " . $_SESSION['USER_ID'] . " ORDER BY ID_USUARIO asc;";
        $res = $db->query($query);
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            array_push($resultado, $row);
        }
        break;

    case "infoCliente":
        $query = "SELECT CONCAT(c.NOMBRE, ' ' , c.APELLIDOS) as cliente, c.DNI as dni, c.DIRECCION as direccion, CONCAT(e.NOMBRE, ' | ' , e.APELLIDOS) as reseller, c.EMAIL as email, FIJO as fijo, MOVIL as movil, FECHA_NACIMIENTO as fecha, e.MASTER as master, e.ID as idReseller, CALL_CENTER as cc  
        FROM multiplataforma.clientes c
        INNER JOIN multiplataforma.empresas e ON c.ID_EMPRESA = e.ID WHERE DNI = ?;";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $dniCliente);
        $stmt->execute();
        $res = $stmt->get_result();
        $resultado = $res->fetch_array(MYSQLI_ASSOC);
        break;

    case "infoRespuesta":
        $query = "SELECT RESPUESTA as respuesta FROM multiplataforma.guion_callcenter 
        WHERE ID = ?;";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $tipo);
        $stmt->execute();
        $res = $stmt->get_result();
        $resultado = $res->fetch_array(MYSQLI_ASSOC);
        break;

    case "postTicket":
        $ticket = new Ticket(array(
            'url' => 'http://soporte.nexwrf.es/api/tickets.json',
            'key' => 'CAC9B816B8750509CC77D9F8736523C4'
        ));

        $ticket->set_email('callcenter@netvoz.eu');
        $ticket->set_source('Web');
        $ticket->set_team('41');
        $ticket->set_staff('46');
        $ticket->set_topic('30');
        $ticket->set_name('Call Center');
        $ticket->set_phone('34656927956');
        $ticket->set_subject($asunto);
        $ticket->set_message($mensaje);
        $ticket->add_attachment('adjunto.png', $imagen);

        $result = $ticket->send_ticket();

        switch ($result[0]) {
            case 0: {
                    echo 'Ticket ID: ' . $result[2] . "<br>";
                    echo print_r($result, true) . "<br>";
                    break;
                }

            case -1: {
                    echo 'cURL error';
                    break;
                }

            case -2: {
                    echo "osTicket's API error" . '<br>';
                    echo "code: " . $result[1]  . '<br>';
                    echo "msg: "  . $result[2]  . '<br>';
                    break;
                }
            default:
                echo "Default" . "<br>";
                echo print_r($result, true);
                break;
        }
        break;
}
//print_r($resultado);
echo (json_encode($resultado));
//echo json_last_error_msg();
