<?php

/* COMPRUEBA EL ESTADO DE ENVÍO DE LOS PAQUETES */

//if (!isset($_SESSION)) {
//    session_start();
//}

// Variable de Sesión ID Reseller/Distribuidor
if (!isset($_SESSION['REVENDEDOR'])) {
    $_SESSION['REVENDEDOR'] = 0;
}
$_SESSION['PLATAFORMA'] = 'MOVIL';
define ('DB_SERVER' , '10.211.2.6:3306');
define ('DB_DATABASENAME', 'multiplataforma');
define ('DB_USER', 'root');
define ('DB_PASSWORD', 'mysql456456tel*');

error_log("CAMINO --> " . $_SERVER['DOCUMENT_ROOT'] . "/solicitudesWeb/conexDB.php");
include_once $_SERVER['DOCUMENT_ROOT'] . "/solicitudesWeb/conexDB.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/clases/masmovil/SMSMasMovilAPI.php";
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/util.php';

include_once $_SERVER['DOCUMENT_ROOT'] . '/clases/Servicio.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/clases/AltaTecnica.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/clases/Clientes.php';

$util = new util();
$sClient = new SMSMasMovilAPI(USER_SMS_MASMOVIl, PASS_SMS_MASMOVIl);

$servicio = new Servicio();
$cli = new Clientes();
$altatecnica = new AltaTecnica();
$cifSuperUsuario = $_SESSION['CIF'];
date_default_timezone_set('Etc/UTC');
header('Content-type: application/json; charset=utf-8');

$db = DB::getInstance('MOVIL'); 
$row = null;
$resultado = [];

// PRIMERO CARGAMOS LA LISTA DE SOLICITUDES

$query = "SELECT clientes.ID AS ID_CLIENTE, solicitudesweb.* FROM multiplataforma.solicitudesweb
LEFT JOIN clientes on clientes.DNI = solicitudesweb.NUM_DOCUMENTO
WHERE clientes.ID_EMPRESA = 64 
AND FECHA_ENTREGA_SIM = '0000-00-00'
AND solicitudesweb.NUM_DOCUMENTO IN ('02241523N','03454475J','05272643P','05709121S','05722845P','06281177S','07256111W','07257459Q','07269133Y','07557611H','07989525S','08115775H','08497216G','08890982X','09059202P','09152770N','09197726A','09231338N','09738815V','09794521V','09798121Y','11068105E','11085640P','11411636W','11700164H','12420194X','15409320X','15441519D','15441874L','15442091Y','16611146V','16627144F','17200390R','17721910L','18421505T','18436056S','18439066N','18459278F','20032599J','20035947A','20235120L','20606562B','21014227R','21035142D','23114688X','23330464T','23727444T','23812219C','24266985F','25334970X','25353057L','25610311H','26028278Y','26045977H','26052406F','26255122R','26259111B','26466830V','26795201V','28437477R','28818097V','28818440S','29622268Q','29718821S','30262167D','30393651W','30943731Z','31025767D','31339911L','31661996N','31670924Q','31725741R','31731341N','31853214P','32057090N','32072687S','32073249W','32081973D','32732753G','32885060M','32897765Z','32899435M','32904074K','33382846W','33410684X','33564013K','34833862V','34858084C','40678530V','40963475S','41697340L','42995467A','43081784R','43127046E','44027756Y','44032778Z','44069206X','44069316M','44242892T','44243306T','44244707K','44275141A','44407826R','44414893F','45065926W','45114816V','45384971Z','45573481Q','45717483S','45718760G','45736928W','45922287G','46544349P','46750381Y','47861346W','48515772D','48807762Z','48968224M','48970472E','49045118X','49062910T','49072062K','49085493C','49088282A','49134074W','49234724G','49238503B','49340318M','49396065T','49506494Y','49562443L','49564428A','49836870X','49972223P','50609302X','50684473V','50974755Q','52292657H','52350654D','52808513F','53285919W','53703787Y','53911153G','53926613P','53934293Y','54791232B','70228225W','70232980L','70315874K','70587301W','71357337K','71411583X','71424178R','71431397K','71779745B','71783096G','71784103E','72122504R','72154712D','73578038A','73597763V','75020495S','75125734Y','75556666X','75737128Z','75738161N','75738916P','75740546M','75744448C','75747669K','75770014X','75796914T','75898730H','75900022E','75932988Y','75943704G','76635123C','77194060B','77232418M','77249835B','77468037N','77645628C','77847954S','78026539M','78103700R','78112750N','78635357J','78850531E','79190076H','79193186T','80127583E','80127663X','80138972A','B01639814','B01974955','B07680911','B11821261','B72361249','F93715738','G90434465','X3488239J','X3658601Z','X3975845L','X4680090G','X5185193G','X6169624N','X6723880Z','X7658888A','X8299926P','X9112306M','X9242498V','X9603247B','X9608395F','X9664769P','X9836999Z','Y0423767Y','Y1487941Q','Y1929944M','Y4075023N','Y4533411X','Y5341841J','Y5666787S','Y6996134P','Y7201117S','Y7532349R','Y8025611M')";
$res = $db->query($query);
$resultado = [];
while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
    array_push($resultado, $row);
}
$resultado = convert_from_latin1_to_utf8_recursively($resultado);
//error_log("LOGS -->" . print_r($resultado, true));
// RECORREMOS LA LISTA DE SOLICITUDES PARA VER SI HA CAMBIADO EL ESTADO DEL ENVÍO
for ($i = 0; $i < sizeof($resultado); $i++) {
    echo "OK";

    $cliente = $resultado[$i]['ID_CLIENTE'];
    //$amoviles = $_POST['amoviles'];
    $descripcionTarifa = 'MiniNet';
    $NombreCliente = $resultado[$i]['TITULAR_CUENTA_BANCARIA'];
    $aItems = array();
    $resultCode = "";

    $fecha = date('Y-m-d');
    error_log("CLIENTE --> " . $cliente);
    $datosCliente = $cli->getClienteAltaMasMovil($cliente);
    $datosCliente = mysqli_fetch_array($datosCliente);
    error_log("DATOS_CLIENTE --> " . $datosCliente[3]);
    $codBanco = substr($datosCliente[13], 4, 4);
    $oficina = substr($datosCliente[13], 8, 4);
    $dc = substr($datosCliente[13], 12, 2);
    $ccc = substr($datosCliente[13], 14, 10);
    $codProv = substr($datosCliente[11], 0, 2);

    $icc = $resultado[$i]['NUM_ICC_PORTABILIDAD'];
    $idServicio = $resultado[$i]['ID_TARIFA'];
    $idExterno = 'CM_POSTPAGO_MINIMAX';
    $bonos = 'B0357;B0364';
    $MaxPorcentaje = '';

    error_log("ID TARIFA EXTERNA --> " . $idExterno . " idTarifa --> " . $idServicio);
    switch($idExterno)
    {
        /** separar bonosAlta para las tarifas a granel */
        case "CM_POST_TRV012":
            $bonosAlta = explode(";", $bonos)[0];
            $idBono = explode(";", $bonos)[1];
            $idUnicoBono = "";
            break;
        /** id unico bono para tarifas compartidas */
        case "CM_POST_ILIMIT_COMP":
            error_log("IDUNICOBONO COMPARTIDAS --> " . $_POST['idUnicoBono']);
            $idUnicoBono = $_POST['idUnicoBono'];
            $bonosAlta =$bonos;
            $idBono = explode(";", $bonos)[1];
            $MaxPorcentaje = $bonos;
            break;
        /** Ahora los Bonos de las tarifas NOV19 son Promociones!!!!!!! */
        case "CM_POST_ILIMITADA_LA4_NOV19":
        case "CM_POST_ILIMITADA_LA10_NOV19":
        case "CM_POST_ILIMITADA_LA20_NOV19":
            $bonosAlta = explode(";", $bonos)[0];
            $idBono = "";
            $idUnicoBono = "";
            break;
        default:
            //$bonosAlta = explode(";", $bonos)[0];
            $bonosAlta = $bonos;
            $idBono = explode(";", $bonos)[1];
            $idUnicoBono = "";
            break;
    }



    $res = AltaTecnica::addNuevaLineaMasMovil($datosCliente[0] . " " . $datosCliente[1],
        $datosCliente[0] . " " . $datosCliente[1], $datosCliente[2], $datosCliente[3], $datosCliente[0], $datosCliente[4],
        $datosCliente[5], $datosCliente[4], $datosCliente[6], $datosCliente[7], $datosCliente[8], $codProv,
        $datosCliente[10], $datosCliente[11], $datosCliente[0] . " " . $datosCliente[1], $datosCliente[12],
        $codBanco, $oficina, $dc, $ccc, $icc, $idServicio, $bonosAlta, $idBono, $MaxPorcentaje, $idUnicoBono);


    $resultCode = $res->activationCode;
    $resultMsg = $res->activateDescription;

    $aItem = array(
        'resultCode' => $resultCode,
        'resultMsg' => $resultMsg,
        'icc' => $icc,
        'tarifa' => $idServicio
    );
    array_push($aItems, $aItem);

    if ($resultCode == OPERACION_OK_MASMOVIL)
    {
        $serviciosMasmovil = $util->selectWhere3('servicios', array('ID'), 'ID_PROVEEDOR=' . ID_PROVEEDOR_MASMOVIL);
        $util->consulta("INSERT INTO altas_mas_movil (ID_LINEA_DETALLE, ESTADO, ICC, ID_EMPRESA, ERROR_CODE, ERROR_MSG, ID_CLIENTE, ID_SERVICIO, IDUNICOBONO) 
							VALUES (0, 3, '" . $icc . "', " . $_SESSION['REVENDEDOR'] . ", '" . $resultCode . "', '" . $resultMsg . "', " . $cliente . ", " . $idServicio . ", '" . $idUnicoBono . "')");

        $mensaje = "El cliente " . $NombreCliente . " ha creado una solicitud de alta nueva con icc: " . $icc . "<br>Tarifa Seleccionada: " . $descripcionTarifa;
        $mensaje .= "Nombre: " . $NombreCliente . "<br><br>";
        $mensaje .= "ICC: " . $icc . "<br><br>";
        $mensaje .= "Tarifa Seleccionada: " . $descripcionTarifa . "<br><br>";
        $mensaje .= "Tarifa: " . $idServicio . "<br><br>";
        $mensaje .= "Tarifa Interna: " . $idExterno. "<br><br>";
        $mensaje .= "Revendedor: " . $_SESSION['REVENDEDOR'] . "<br><br>";
        $mensaje .= "COD: " . $resultCode . "<br><br>";
        $mensaje .= "MENSAJE: " . $resultMsg . "<br><br>";
        $mensaje .= "IdUnicoBono: " . $idUnicoBono . "<br><br>";
        $mensaje .= "BonosAlta: " . $bonosAlta . "<br><br>";
        $mensaje .= "IdBono: " . $idBono . "<br><br>";
        $mensaje .= "MaxPorcentaje: " . $MaxPorcentaje . "<br><br>";
        $mensaje .= "IdUnicoBono: " . $idUnicoBono . "<br><br>";

        $util->enviarEmail("movil@netvoz.eu","Altas Móviles",array("soporte.telefonia@internetinfraestructuras.es", "administracion.telefonia@internetinfraestructuras.es","altanueva@nexwrf.es"),"Soporte Altas", array("software@nexwrf.es", "masmovil@11400.es"), "", "Solicitud de Alta Nueva", $mensaje);
        // TODO: ENVIAMOS SMS
        $response = $sClient->checkBalanceMasMovil();
        error_log("SMS BALANCE --> " . $response);
        $respuestaSMS = new SimpleXMLElement($response, null, false);
        error_log("SALDO : " . $respuestaSMS->checkBalance->saldo);
        if ($respuestaSMS->checkBalance->saldo > 0)
        {
            $dst = $resultado[$i]['NUM_TLF_TITULAR'];
            //$dst = "656927956";
            $msg = "Recibirá para estas navidades y como cliente de NETVOZ, una tarjeta con 5GB de forma gratuita durante 6 meses.";
            $response = $sClient->sendSmsMasMovil("NETVOZ", "34" . $dst, $msg, null);
            error_log($response);
        }
        $util->update("productos", array("ESTADO"), array("2"), "ICC = '" . $icc . "'");
    }
    else
    {
        $serviciosMasmovil = $util->selectWhere3('servicios', array('ID'), 'ID_PROVEEDOR=' . ID_PROVEEDOR_MASMOVIL);
        $util->consulta("INSERT INTO AltasMasMovilErrores (ID_LINEA_DETALLE, ESTADO, ICC, ID_EMPRESA, ERROR_CODE, ERROR_MSG, ID_CLIENTE, ID_SERVICIO, IDUNICOBONO) 
							VALUES (0, 3, '" . $icc . "', " . $_SESSION['REVENDEDOR'] . ", '" . $resultCode . "', '" . $resultMsg . "', " . $cliente . ", " . $idServicio . ", '" . $idUnicoBono . "')");

        $mensaje  = "Se ha producido un ERROR al solicitar un ALTA <br><br>";
        $mensaje .= "Nombre: " . $NombreCliente . "<br><br>";
        $mensaje .= "ICC: " . $icc . "<br><br>";
        $mensaje .= "Tarifa Seleccionada: " . $descripcionTarifa . "<br><br>";
        $mensaje .= "Tarifa: " . $idServicio . "<br><br>";
        $mensaje .= "Tarifa Interna: " . $idExterno . "<br><br>";
        $mensaje .= "Revendedor: " . $_SESSION['REVENDEDOR'] . "<br><br>";
        $mensaje .= "COD: " . $resultCode . "<br><br>";
        $mensaje .= "MENSAJE: " . $resultMsg . "<br><br>";
        $mensaje .= "IdUnicoBono: " . $idUnicoBono . "<br><br>";
        $mensaje .= "BonosAlta: " . $bonosAlta . "<br><br>";
        $mensaje .= "IdBono: " . $idBono . "<br><br>";
        $mensaje .= "MaxPorcentaje: " . $MaxPorcentaje . "<br><br>";
        $mensaje .= "IdUnicoBono: " . $idUnicoBono . "<br><br>";

        $util->enviarEmail("movil@netvoz.eu","Altas Móviles",array("soporte.telefonia@internetinfraestructuras.es", "administracion.telefonia@internetinfraestructuras.es","altanueva@nexwrf.es"),"Soporte Altas", array("software@nexwrf.es", "masmovil@11400.es"), "", "Error en Solicitud de Alta Nueva", $mensaje);
    }











}

//print_r($resultado);
//echo (json_encode($resultado));
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
