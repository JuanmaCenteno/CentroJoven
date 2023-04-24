<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); // MOVIL 232 al 512

include_once $_SERVER['DOCUMENT_ROOT'] . "/solicitudesWeb/conexDB.php";

$db = DB::getInstance("MOVIL");
$query = "SELECT * FROM solicitudesweb WHERE (ID between 232 AND 512) AND ESTADO = 1;";
$res = $db->query($query);
$arrayLineas = [];
while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
    array_push($arrayLineas, $row);
}

//echo "ARRAY LINEAS";
//var_dump($arrayLineas);
//
for ($i = 0; $i < sizeof($arrayLineas); $i++) {
    $linea = $arrayLineas[$i];
    $municipio = $linea['MUNICIPIO_ENVIO'];
    $codPostal = $linea['CODIGO_POSTAL_ENVIO'];
    $provincia = $linea['PROVINCIA_ENVIO'];
    $direccion = $linea['DIRECCION_ENVIO'];
    $telefono = $linea['NUM_TLF_TITULAR'];
    $emailDestino = $linea['EMAIL'];
    $nombre = $linea['NOMBRE_TITULAR'] . " " . $linea['APELLIDOS_TITULAR'];
    $dni = $linea['NUM_DOCUMENTO'];
    $agencia = "122";
    $idTransporte = $linea['ID_TRANSPORTE'];
    $fecha = "";
    $fecha = date('d-m-Y', strtotime("$fecha + 1 days"));
    $cont = 2;
    while (isweekend($fecha)) {
        $fecha = date('d-m-Y', strtotime("$fecha + " . $cont . " days"));
        $cont += 1;
    }
    $fecha = date("d/m/Y", strtotime($fecha));
    error_log("FECHA ENVIO --> " . $fecha);
    //date('d/m/Y');
    $poblacion = $municipio . ", " . $provincia; // PREGUNTAR POBLACIÓN

    //echo "VARIABLE LINEA\n";
    var_dump($linea);



    //The url you wish to send the POST request to
    //$url = "https://www.genei.es/json_interface/crear_envio_sandbox"; // ENVÍO SANDBOX
    $url = "https://www.genei.es/json_interface/crear_envio"; // ENVÍO NORMAL
    $email = "solicitudes@netvoz.eu"; //EMAIL_SOLICITUDES_FTTH;

    // Cambiar valores por los de la SIM
    $field = [
        [], [
            "peso" => "0.03",
            "largo" => "22",
            "ancho" => "22",
            "alto" => "2",
            "contenido" => "Tarjeta SIM compañia",
            "taric" => "",
            "dni_contenido" => "",
            "valor" => ""
        ]
    ];

    //The data you want to send via POST
    $fields = [
        'array_bultos' => $field,
        "valor_mercancia" => 0,
        "contenido_envio" => "Sobre con tarjeta SIM",
        "contrareembolso" => 0,
        "cantidad_reembolso" => 0,
        "seguro" => 0,
        "importe_seguro" => 0,
        "dropshipping" => 0,
        "codigos_origen" => "11407",
        "poblacion_salida" => "JEREZ DE LA FRONTERA",
        "iso_pais_salida" => "ES",
        "direccion_salida" => "Calle del Comercio, 18",
        "email_salida" => $email,
        "nombre_salida" => "NETVOZ",
        "telefono_salida" => "956922992",
        "codigos_destino" => $codPostal,
        "poblacion_llegada" => $poblacion,
        "iso_pais_llegada" => "ES",
        "direccion_llegada" => $direccion,
        "telefono_llegada" => $telefono,
        "email_llegada" => $emailDestino,
        "nombre_llegada" => $nombre,
        "dni_llegada" => $dni,
        "observaciones_salida" => "",
        "contacto_salida" => "",
        "observaciones_llegada" => "-",
        "contacto_llegada" => "",
        "codigo_mercancia" => "24",
        "recoger_tienda" => "0",
        "cod_promo" => "",
        "select_oficinas_destino" => null,
        "fecha_recogida" => $fecha,
        "hora_recogida_desde" => "09:00",
        "hora_recogida_hasta" => "17:00",
        "unidad_correo" => null,
        "codigo_envio_servicio" => $idTransporte,
        "id_agencia" => $agencia,
        "usuario_servicio" => "administracion.telefonia@nexwrf.es75",
        "password_servicio" => "6cnk4gpq",
        "cn" => 200,
        "servicio" => "api",
        "id_usuario" => "95370"
    ];

    // Para ver lo que está mandando
    //echo json_encode($fields);
    //var_dump($fields);

    // JSON DATA
    $fields = json_encode($fields);

    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FAILONERROR, true); // Required for HTTP error codes to be reported via our call to curl_error($ch)
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

    //So that curl_exec returns the contents of the cURL; rather than echoing it
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //execute post
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Request Error:' . curl_error($ch);
    }
    curl_close($ch);
    //echo "RESULTADO\n";
    error_log("RESULTADO APITRANS --> " . $result);
    //var_dump($result);

    $arr = json_decode($result, true);
    echo "ARRAY RESULTADO LÍNEA " . $i . "\n";
    print_r($arr);

    if (strval($arr['estado']) == "1") { // ENVÍO CORRECTO
        $tr = $arr['codigo_envio'];
        echo "OK";
        // ACTUALIZAR ESTADO ENVÍO        
        $query = "UPDATE solicitudesweb SET ID_TRANSPORTE = ?, ESTADO = 2 WHERE ID_TRANSPORTE = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('ss', $tr, $idTransporte);
        $stmt->execute();
        

        // TODO: ENVIAR ETIQUETA
    }
}
$db->close();

function isweekend($date)
{
    $date = strtotime($date);
    $date = date("l", $date);
    $date = strtolower($date);
    echo "FECHA " . $date;
    if ($date == "saturday" || $date == "sunday") {
        return true;
    } else {
        return false;
    }
}
