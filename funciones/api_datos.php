<?php

if (!isset($_SESSION)) {
    session_start();
}

include_once "./conexDB.php";
header('Content-type: application/json; charset=utf-8');

$id = "";
$accion = htmlspecialchars($_GET["accion"], ENT_QUOTES);
if(!empty($_GET["id"])){
    $id = htmlspecialchars($_GET["id"], ENT_QUOTES);
}

//echo "accion: " . $accion;

$db = DB::getInstance();
$row = null;
$resultado = [];

switch ($accion) {
        // Lista de Nombres para el ComboBox
    case "voluntarios":
        $query = "SELECT * FROM voluntarios";
        $res = $db->query($query);
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            array_push($resultado, $row);
        }
        break;

    case "historial":
        $query = "SELECT CONCAT(Nombre,' ',Apellido1, ' ', Apellido2, ' (', DNI, ')') as voluntario, tipo, fechayhora as fecha 
        FROM voluntarios v INNER JOIN historial h
        ON v.DNI = h.dniVoluntario";
        $res = $db->query($query);
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            array_push($resultado, $row);
        }
        break;

    case "historialVoluntario":
        $query = "SELECT CONCAT(Nombre,' ',Apellido1, ' ', Apellido2, ' (', DNI, ')') as voluntario, tipo, fechayhora as fecha 
        FROM voluntarios v INNER JOIN historial h
        ON v.DNI = h.dniVoluntario WHERE DNI like ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            array_push($resultado, $row);
        }
        break;
}        

//print_r($resultado);
$db->close();
echo (json_encode($resultado));
//echo json_last_error_msg();
