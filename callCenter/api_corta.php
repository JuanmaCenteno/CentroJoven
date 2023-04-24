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
error_log("DNI CLIENTE --> " . $dniCliente);

if ($action != "") {
    $accion = $action;
    $numTicket = filter_input(INPUT_POST, 'numTicket', FILTER_SANITIZE_STRING);
    $plataforma = filter_input(INPUT_POST, 'plataforma', FILTER_SANITIZE_STRING);
}


if ($plataforma != "") {
    $db = DB::getInstance($plataforma);
}
$row = null;
$resultado = [];


switch ($accion) {
        // Lista de Nombres para el ComboBox
    case "listaTickets":
        $query = "SELECT number as id, est_duedate as fecha, subject as asunto, s.content as contenido , name as nombre, phone as tlf, priority as prioridad
        FROM osticket.ost_ticket t NATURAL JOIN osticket.ost_ticket__cdata d INNER JOIN osticket.ost__search s ON s.title = d.subject
        WHERE user_id = 459;";
        $res = $db->query($query);
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            $utf8 = $row['contenido']; // file must be UTF-8 encoded
            $iso88591_1 = utf8_decode($utf8);
            $row['contenido'] = $iso88591_1;
            array_push($resultado, $row);
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
}
//print_r($resultado);
echo (json_encode($resultado));
//echo json_last_error_msg();
