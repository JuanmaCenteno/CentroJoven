<?php

if (!isset($_SESSION)) {
    session_start();
}

// Variable de Sesión ID Reseller/Distribuidor
if (!isset($_SESSION['REVENDEDOR'])) {
    $_SESSION['REVENDEDOR'] = 0;
}

include_once($_SERVER['DOCUMENT_ROOT'] . '/clases/Tickets.php');

$asunto = filter_input(INPUT_POST, 'asunto', FILTER_SANITIZE_STRING);
$mensaje = filter_input(INPUT_POST, 'mensaje', FILTER_SANITIZE_STRING);
$archivo = $_FILES['file'];
$rutaArchivo = $archivo['tmp_name'];
$nombreArchivo = $archivo['name'];
$tipoArchivo = $archivo['type'];
$data = file_get_contents($rutaArchivo);
$base64 = 'data:' . $tipoArchivo . ';base64,' . base64_encode($data);

//echo "BASE 64: " . $base64;
//var_dump($_FILES);
//var_dump($_POST);

$row = null;
$resultado = [];

//$key = 'CAC9B816B8750509CC77D9F8736523C4'; // PRUEBAS
$key = "AB35422D78117B5C0144418375B1BD0E"; // PRODUCCIÓN


$ticket = new Ticket(array(
    'url' => 'http://soporte.nexwrf.es/api/tickets.json',
    'key' => $key
));

$ticket->set_email('callcenter@netvoz.eu');
$ticket->set_source('Web');
$ticket->set_team('41');
$ticket->set_staff('46');
$ticket->set_topic('30');
$ticket->set_name('Call Center');
$ticket->set_phone('34722774556');
$ticket->set_subject($_SESSION['NOM_USER'] . " - " . $asunto);
$ticket->set_message($mensaje);
$ticket->add_attachment($nombreArchivo, $base64);

//echo json_encode($ticket->get_data());

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
