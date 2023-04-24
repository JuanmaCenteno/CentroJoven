<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once $_SERVER['DOCUMENT_ROOT'] . "/content/appMovil/conexDB.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/clases/masmovil/SMSMasMovilAPI.php";
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/util.php';
include_once $_SERVER['DOCUMENT_ROOT'] . "/clases/apiRedsys.php";

$util = new util();
$redsys = new RedsysAPI;
$sClient = new SMSMasMovilAPI(USER_SMS_MASMOVIl, PASS_SMS_MASMOVIl);

$plataforma = filter_input(INPUT_POST, 'plataforma', FILTER_SANITIZE_STRING);
if ($plataforma != "") {
    $db = DB::getInstance($plataforma);
}

// Variables para insertar
$fechaHora = date('Y-m-d h:i:s', time());
$idFact = filter_input(INPUT_GET, 'idFactura', FILTER_SANITIZE_STRING);
$idFactura = filter_input(INPUT_POST, 'idFactura', FILTER_SANITIZE_STRING);
$importe = filter_input(INPUT_POST, 'importe', FILTER_SANITIZE_STRING);
$importa = filter_input(INPUT_GET, 'importe', FILTER_SANITIZE_STRING);
$gastos = filter_input(INPUT_POST, 'gastos', FILTER_SANITIZE_STRING);
$importe = filter_input(INPUT_POST, 'importe', FILTER_SANITIZE_STRING);
$idClienteFactura = filter_input(INPUT_POST, 'idClienteFactura', FILTER_SANITIZE_STRING);
$numTlf = filter_input(INPUT_POST, 'numTlf', FILTER_SANITIZE_STRING);
$idCliente = filter_input(INPUT_POST, 'idCliente', FILTER_SANITIZE_STRING);
$nombreCliente = filter_input(INPUT_POST, 'nombreCliente', FILTER_SANITIZE_STRING);

if ($numTlf == "" && $plataforma != "") {
    $numTlf = getTlfCliente($idFactura, $db);
}

$plata = filter_input(INPUT_GET, 'plataforma', FILTER_SANITIZE_STRING);
$accion = filter_input(INPUT_POST, 'accion', FILTER_SANITIZE_STRING);
if ($accion == "") {
    $accion = filter_input(INPUT_GET, 'accion', FILTER_SANITIZE_STRING);
}
$revendedor = filter_input(INPUT_POST, 'revendedor', FILTER_SANITIZE_STRING);
if ($revendedor == "") {
    $revendedor = $_SESSION['REVENDEDOR'];
}
$usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
if ($usuario == "") {
    $usuario = $_SESSION['USER_ID'];
}

if ($plata != "" && $plataforma == "") {
    $plataforma = $plata;
}

if ($plataforma == "MOVIL") {
    $emailDestino = array("administracion.telefonia@nexwrf.es", "masmovil@11400.es");
    //$kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'; //Clave recuperada de CANALES PRUEBAS
    $kc = 'TgjU2xSGpobDbiANm2SgJ9kW/ZDDAMUz'; //Clave ENTORNO REAL
    $merchant = "352092555";
    // METER NUEVA CLAVE CUENTA
    // METER CÓDIGO MERCHANT    
} elseif ($plataforma == "FTTH") {
    $emailDestino = array("ftth@netvoz.eu", "masmovil@11400.es");
    //$kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'; //Clave recuperada de CANALES PRUEBAS
    $kc = 'TApAogshz5G9RChinMvFD3X8+/Fte9h2'; //Clave ENTORNO REAL NEXVOLMAR
    $merchant = "352225460";
    // METER NUEVA CLAVE CUENTA
    // METER CÓDIGO MERCHANT
} else {
    //$kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'; //Clave recuperada de CANALES PRUEBAS
    $kc = 'TgjU2xSGpobDbiANm2SgJ9kW/ZDDAMUz'; //Clave ENTORNO REAL
    $merchant = "352092555";
}

error_log("USUARIO: " . $usuario . " REVENDEDOR: " . $revendedor . "\n");
//$url = "https://sis-t.redsys.es:25443/sis/realizarPago"; // PRUEBAS
$url = "https://sis.redsys.es/sis/realizarPago"; // REAL
$urlOKKO = "https://aiongest.internetinfraestructuras.es/callCenter/check_pago_factura.php?idFactura=" . $idFact . "&revendedor=" . $revendedor . "&usuario=" . $usuario . "&plataforma=" . $plata;
$order = random_int(11111111, 99999999);
$clave = "sq7HjrUOBfKmC576ILgskD5srU870gJ7";
$importa = str_replace(array(",", "."), "", strval($importa));


switch ($accion) {
    case 'enviarSms':
        //$numTlf = "653967952";
        $enlace = "https://aiongest.internetinfraestructuras.es/redirect2.php?a=fP&i=" . $idFactura . "&im=" . $importe . "&r=" . $revendedor . "&u=" . $usuario . "&p=" . $plataforma;
        $texto = "Complete el pago de factura en este enlace: " . $enlace;
        echo ($texto);
        $remitente = "NETVOZ";
        $resultado = new SimpleXMLElement(enviarSms($sClient, $remitente, $numTlf, $texto));
        break;

    case 'fP':
        $redsys->setParameter("DS_MERCHANT_AMOUNT", $importa);
        $redsys->setParameter("DS_MERCHANT_ORDER", $order);
        $redsys->setParameter("DS_MERCHANT_MERCHANTCODE", $merchant);
        $redsys->setParameter("DS_MERCHANT_CURRENCY", "978");
        $redsys->setParameter("DS_MERCHANT_TRANSACTIONTYPE", "0");
        $redsys->setParameter("DS_MERCHANT_TERMINAL", "1");
        $redsys->setParameter("DS_MERCHANT_MERCHANTURL", $urlOKKO);
        $redsys->setParameter("DS_MERCHANT_URLOK", "");
        $redsys->setParameter("DS_MERCHANT_URLKO", "");

        //Datos de configuración
        $version = "HMAC_SHA256_V1";
        // Se generan los parámetros de la petición
        $request = "";
        $params = $redsys->createMerchantParameters();
        $signature = $redsys->createMerchantSignature($kc);
        break;

    case 'formPagoBoton':
        $importe = str_replace(array(",", "."), "", strval($importe));
        $urlOKKO = "https://aiongest.internetinfraestructuras.es/callCenter/check_pago_factura.php?idFactura=" . $idFactura . "&revendedor=" . $revendedor . "&usuario=" . $usuario . "&plataforma=" . $plataforma;
        $redsys->setParameter("DS_MERCHANT_AMOUNT", $importe);
        $redsys->setParameter("DS_MERCHANT_ORDER", $order);
        $redsys->setParameter("DS_MERCHANT_MERCHANTCODE", $merchant);
        $redsys->setParameter("DS_MERCHANT_CURRENCY", "978");
        $redsys->setParameter("DS_MERCHANT_TRANSACTIONTYPE", "0");
        $redsys->setParameter("DS_MERCHANT_TERMINAL", "1");
        $redsys->setParameter("DS_MERCHANT_MERCHANTURL", $urlOKKO);
        $redsys->setParameter("DS_MERCHANT_URLOK", "");
        $redsys->setParameter("DS_MERCHANT_URLKO", "");

        //Datos de configuración
        $version = "HMAC_SHA256_V1";
        // Se generan los parámetros de la petición
        $request = "";
        $params = $redsys->createMerchantParameters();
        $signature = $redsys->createMerchantSignature($kc);
        $resultado = [
            "version" => $version,
            "params" => $params,
            "signature" => $signature
        ];
        break;
}

function enviarSms($sClient, $remitente, $numTlf, $msg)
{
    try {
        $response = $sClient->checkBalanceMasMovil();
        $respuestaSMS = new SimpleXMLElement($response, null, false);
        error_log("SALDO : " . $respuestaSMS->checkBalance->saldo);
        if ($respuestaSMS->checkBalance->saldo > 0) {
            error_log("Saldo : " . $respuestaSMS->checkBalance->saldo);
            $dst = '34' . $numTlf;
            $response = $sClient->sendSmsMasMovil($remitente, $dst, $msg, null);
            error_log($response);
        } else {
            error_log("No hay saldo : " . $respuestaSMS->checkBalance->saldo);
        }
    } catch (SoapFault $e) {
        var_dump($e);
    }
    return $response;
}

function getTlfCliente($numFactura, $db)
{
    $query = "SELECT c.MOVIL FROM factusol.facturas_emitidas f INNER JOIN multiplataforma.clientes c ON c.ID = f.cod_cli_factura  WHERE f.num_factura = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $numFactura);
    $stmt->execute();
    $res = $stmt->get_result();
    $resultado = $res->fetch_array(MYSQLI_ASSOC);
    return $resultado['MOVIL'];
}

?>
<?php
if ($accion == "fP") { ?>
    <html lang="es">

    <head>
    </head>

    <body>

        <form name="frm" action="https://sis.redsys.es/sis/realizarPago" method="POST" target="_self">
            Ds_Merchant_SignatureVersion <input type="text" name="Ds_SignatureVersion" value="<?php echo $version; ?>" /></br>
            Ds_Merchant_MerchantParameters <input type="text" name="Ds_MerchantParameters" value="<?php echo $params; ?>" /></br>
            Ds_Merchant_Signature <input type="text" name="Ds_Signature" value="<?php echo $signature; ?>" /></br>
            <input type="submit" value="Enviar">
        </form>

        <script type="text/javascript">
            window.onload = function() {
                document.forms["frm"].submit();
            }
        </script>

    </body>

    </html>
<?php } elseif ($accion == "formPagoBoton") {
    echo json_encode($resultado);
}
?>