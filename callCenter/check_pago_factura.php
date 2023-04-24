<?php

if (!isset($_SESSION)) {
    @session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . "/content/appMovil/conexDB.php";
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/util.php';
include_once $_SERVER['DOCUMENT_ROOT'] . "/clases/apiRedsys.php";
require_once($_SERVER['DOCUMENT_ROOT'] . '/clases/devoluciones/devoluciones.php');

$plataforma = filter_input(INPUT_POST, 'plataforma', FILTER_SANITIZE_STRING);
if ($plataforma == "") {
    $plataforma = filter_input(INPUT_GET, 'plataforma', FILTER_SANITIZE_STRING);
}

$_SESSION['PLATAFORMA'] = $plataforma;

$util = new util();
$devoluciones = new devoluciones();
$redsys = new RedsysAPI;
$db = DB::getInstance($plataforma);
$emailNetvoz = EMAIL_SOLICITUDES_FTTH;
if ($plataforma == "MOVIL") {
    $emailDestino = array("administracion.telefonia@nexwrf.es", "masmovil@11400.es");
    //$kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'; //Clave recuperada de CANALES PRUEBAS
    $kc = 'TgjU2xSGpobDbiANm2SgJ9kW/ZDDAMUz'; //Clave ENTORNO REAL
    // METER NUEVA CLAVE CUENTA

} elseif ($plataforma == "FTTH") {
    $emailDestino = array("ftth@netvoz.eu", "masmovil@11400.es");
    //$kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'; //Clave recuperada de CANALES PRUEBAS
    $kc = 'TApAogshz5G9RChinMvFD3X8+/Fte9h2'; //Clave ENTORNO REAL NEXVOLMAR
    // METER NUEVA CLAVE CUENTA
}

//$emailDestino = array("jmca111197@gmail.com", "masmovil@11400.es"); // PRUEBAS

$idFactura = filter_input(INPUT_POST, 'idFactura', FILTER_SANITIZE_STRING);
if ($idFactura == "") {
    $idFactura = filter_input(INPUT_GET, 'idFactura', FILTER_SANITIZE_STRING);
}
$revendedor = filter_input(INPUT_POST, 'revendedor', FILTER_SANITIZE_STRING);
if ($revendedor == "") {
    $revendedor = filter_input(INPUT_GET, 'revendedor', FILTER_SANITIZE_STRING);
}
$usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
if ($usuario == "") {
    $usuario = filter_input(INPUT_GET, 'usuario', FILTER_SANITIZE_STRING);
}

$revendedor = getDatosCliente($revendedor, $db);
$arrayDatos = getDatosFactura($idFactura, $db);
//echo "ARRAY DATOS: ";
//print_r($arrayDatos);

if (!empty($_POST)) { //URL DE RESP. ONLINE

    $version = $_POST["Ds_SignatureVersion"];
    $datos = $_POST["Ds_MerchantParameters"];
    $signatureRecibida = $_POST["Ds_Signature"];

    $decodec = $redsys->decodeMerchantParameters($datos);

    $codigoRespuesta = $redsys->getParameter("Ds_Response");
    $firma = $redsys->createMerchantSignatureNotif($kc, $datos);

    echo PHP_VERSION . "<br/>";
    echo $firma . "<br/>";
    echo $signatureRecibida . "<br/>";

    if ($firma === $signatureRecibida) {
        if (strval($codigoRespuesta == "0000")) { // COMPROBAMOS QUE SE HA REALIZADO EL PAGO CORRECTAMENTE
            $mensaje = "<html>
                                <body>
                                    <p>El cliente con el id de Factura " . $idFactura . " ha completado el pago de su factura.</p></br>
                                    <p>Pago realizado por: " . $revendedor . ".</p></br>
                                    <p>Código de Respuesta: " . $codigoRespuesta . "</p>
                                </body>
                            </html>";
            $util->enviarEmail($emailNetvoz, "NETVOZ", $emailDestino, "", "", "", "Pago de Factura Completado", $mensaje);
            cambiarEstadoDevolucion($arrayDatos, $plataforma, $util, $devoluciones, $emailNetvoz, $emailDestino, $revendedor, $usuario);
        } else {
            $mensaje = "<html>
                                <body>
                                    <p>El cliente con el id de Factura " . $idFactura . " no ha completado el pago de su factura.</p></br>
                                    <p>Código de Error: " . $codigoRespuesta . "</p>
                                </body>
                            </html>";
            $util->enviarEmail($emailNetvoz, "NETVOZ", $emailDestino, "", "", "", "ERROR Pago de Factura", $mensaje);
        }
    }
} else {
    if (!empty($_GET)) { //URL DE RESP. ONLINE

        $version = $_POST["Ds_SignatureVersion"];
        $datos = $_POST["Ds_MerchantParameters"];
        $signatureRecibida = $_POST["Ds_Signature"];

        $decodec = $redsys->decodeMerchantParameters($datos);
        $codigoRespuesta = $redsys->getParameter("Ds_Response");
        $firma = $redsys->createMerchantSignatureNotif($kc, $datos);

        if ($firma === $signatureRecibida) {
            if (strval($codigoRespuesta == "0000")) { // COMPROBAMOS QUE SE HA REALIZADO EL PAGO CORRECTAMENTE
                $mensaje = "<html>
                                <body>
                                    <p>El cliente con el id de Factura " . $idFactura . " ha completado el pago de su factura.</p></br>
                                    <p>Pago realizado por: " . $revendedor . ".</p></br>
                                    <p>Código de Respuesta: " . $codigoRespuesta . "</p>
                                </body>
                            </html>";
                $util->enviarEmail($emailNetvoz, "NETVOZ", $emailDestino, "", "", "", "Pago de Factura Completado", $mensaje);
                cambiarEstadoDevolucion($arrayDatos, $plataforma, $util, $devoluciones, $emailNetvoz, $emailDestino, $revendedor, $usuario);
            } else {
                $mensaje = "<html>
                                <body>
                                    <p>El cliente con el id de Factura " . $idFactura . " no ha completado el pago de su factura.</p></br>
                                    <p>Código de Error: " . $codigoRespuesta . "</p>
                                </body>
                            </html>";
                $util->enviarEmail($emailNetvoz, "NETVOZ", $emailDestino, "", "", "", "ERROR Pago de Factura", $mensaje);
            }
        }
    } else {
        die("No se recibió respuesta");
    }
}

function getDatosFactura($idFactura, $db)
{
    $campos = "f.cod_cli_factura, l.id_factu, l.fecha_factura, l.numero_telef, l.dni_cliente, l.nombre_cliente, l.id_tarifa, l.valor_total, l.estado_e, l.fecha_impago, l.notific_impago, l.notific_recobro, l.reactivacion, l.fecha_reactivacion, l.gastos_devolucion, l.importe_final_devol, l.referencia_devolucion, l.documento_devolucion, l.estadoDevolucion, l.id_customer, DATEDIFF(NOW(), l.fecha_impago) AS diasImpago";
    $query = "SELECT " . $campos . " FROM factusol.facturas_emitidas f INNER JOIN multiplataforma.clientes c ON c.ID = f.cod_cli_factura INNER JOIN factusol.lineas_facturadas l ON l.id_factu = f.num_factura WHERE f.num_factura = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $idFactura);
    $stmt->execute();
    $res = $stmt->get_result();
    $resultado = $res->fetch_array(MYSQLI_ASSOC);
    return $resultado;
}

function getDatosCliente($id, $db)
{
    $query = "SELECT CONCAT (NOMBRE, ' | ', APELLIDOS) as nombre FROM multiplataforma.empresas WHERE ID = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $resultado = $res->fetch_array(MYSQLI_ASSOC);
    return $resultado['nombre'];
}

function cambiarEstadoDevolucion($arrayDatos, $plataforma, $util, $devoluciones, $emailNetvoz, $emailDestino, $revendedor, $usuario)
{
    // VALORES
    $fecha = date('Y-m-d', time());
    if ($plataforma == "MOVIL") {
        $customer = $arrayDatos['CUSTOMER_ID'];
    } else {
        $customer = $arrayDatos['id_customer'];
    }

    $id_factu = $arrayDatos['id_factu'];
    $fecha_recobro = $fecha;
    $importe = 0;
    $gastos = $arrayDatos['gastos_devolucion'];
    $estado = 3;
    $numero = $arrayDatos['numero_telef'];
    $customerId = $customer;
    $accion = "desbloqueo";
    $codigocli = $arrayDatos['cod_cli_factura'];
    $nombrecli = $arrayDatos['nombre_cliente'];
    error_log("CAMBIAR_ESTADO_DEVOLUCION --> " . $id_factu . " | " . $fecha_recobro . " | " . $importe . " | " . $gastos . " | " . $estado . " | " . $numero . " | " . $customerId . " | " . $accion . " | " . $codigocli . " | " . $nombrecli . " | " . $revendedor . " | " . $usuario);

    if ($estado == 3) {
        $tabla = "factusol.lineas_facturadas";
        $campos = array('factusol.lineas_facturadas.fecha_actualizacion', 'factusol.lineas_facturadas.estado_e', 'factusol.lineas_facturadas.fecha_reactivacion', 'factusol.lineas_facturadas.fecha_recobro', 'factusol.lineas_facturadas.importe_final_devol', 'factusol.lineas_facturadas.gastos_devolucion', 'factusol.lineas_facturadas.ID_EMPRESA_RECOBRO', 'factusol.lineas_facturadas.ID_USER_RECOBRO');
        //$campos = array('Estado_Solicitud_ftthmb', $estado, );
        if ($importe > 0) {
            $valores = array(date("Y-m-d"), 2, $fecha_recobro, $fecha_recobro, $importe, $gastos, $revendedor, $usuario);
        } else {
            $valores = array(date("Y-m-d"), $estado, $fecha_recobro, $fecha_recobro, $importe, $gastos, $revendedor, $usuario);
        }

        $where = " factusol.lineas_facturadas.id_factu = " . $id_factu;
        $response = $util->updatePorPlataforma($plataforma, $tabla, $campos, $valores, $where);
        error_log("ACTUALIZADA --> " . $response);

        if ($_SESSION['PLATAFORMA'] == "MOVIL") {
            // SI LA PLATAFORMA ES MOVIL BLOQUEAMOS O DESBLOQUEAMOS SEGUN LA ACCION
            error_log("BLOQUEO --> " . $customerId . " | " . $numero . " | " . $accion);
            if ($importe <= 0) {
                error_log("DESBLOQUEANDO " . $importe);
                $devoluciones->bloquearLineaDevolucion($customerId, $numero, $accion);
                $response = 2;
            } else {
                $response = 3;
            }
        } elseif ($_SESSION['PLATAFORMA'] == "FTTH") {
            error_log("BLOQUEO --> " . $customerId . " | " . $numero . " | " . $accion);
            //Tenemos que enviar email con el excel
        }
    }

    //ENVIAMOS EMAIL CON RESULTADO
    switch ($response) {
        case 1:
            $mensaje = "<html>
                                <body>
                                    <p>La factura con ID " . $arrayDatos['id_factu'] . ": Se ha actualizado los importes, pero no se ha podido desbloquear la línea, hagalo desde Gestión de Líneas</p>
                                </body>
                            </html>";
            $util->enviarEmail($emailNetvoz, "NETVOZ", $emailDestino, "", "", "", "Informe Estado Devolución", $mensaje);
            break;
        case 2:
            $mensaje = "<html>
                                <body>
                                    <p>La factura con ID " . $arrayDatos['id_factu'] . ": Se ha actualizado los importes, pero sigue pendiente NO SE DESBLOQUEA LA LINEA.</p>
                                </body>
                            </html>";
            $util->enviarEmail($emailNetvoz, "NETVOZ", $emailDestino, "", "", "", "Informe Estado Devolución", $mensaje);
            break;
        case 3:
            $mensaje = "<html>
                                <body>
                                    <p>La factura con ID " . $arrayDatos['id_factu'] . ": Se ha actualizao los importes y se ha desbloqueado la línea.</p>
                                </body>
                            </html>";
            $util->enviarEmail($emailNetvoz, "NETVOZ", $emailDestino, "", "", "", "Informe Estado Devolución", $mensaje);
            break;
        case 4:
            $mensaje = "<html>
                                <body>
                                    <p>La factura con ID " . $arrayDatos['id_factu'] . ": Se ha generado la devolución manual correctamente.</p>
                                </body>
                            </html>";
            $util->enviarEmail($emailNetvoz, "NETVOZ", $emailDestino, "", "", "", "Informe Estado Devolución", $mensaje);
            break;

        default:
            $mensaje = "<html>
                                <body>
                                    <p>La factura con ID " . $arrayDatos['id_factu'] . ": Ocurrió un error y NO hemos podido desbloquear la línea, realice el desbloqueo desde Gestión Líneas</p>
                                </body>
                            </html>";
            $util->enviarEmail($emailNetvoz, "NETVOZ", $emailDestino, "", "", "", "Informe Estado Devolución", $mensaje);
            break;
    }
}
