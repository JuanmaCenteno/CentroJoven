<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once $_SERVER['DOCUMENT_ROOT'] . "/callCenter/conexDB.php";

$accion = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
$plataforma = filter_input(INPUT_POST, 'plataforma', FILTER_SANITIZE_STRING);

$db = DB::getInstance($plataforma);

// Variables para insertar
// Centralitas
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
$tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_NUMBER_INT);
$caso = filter_input(INPUT_POST, 'caso', FILTER_SANITIZE_STRING);
$respuesta = filter_input(INPUT_POST, 'respuesta', FILTER_SANITIZE_STRING);
$visible = filter_input(INPUT_POST, 'visible', FILTER_SANITIZE_STRING);
$visibleReseller = filter_input(INPUT_POST, 'visibleReseller', FILTER_SANITIZE_STRING);
if ($visible == "on") {
    $visible = 1;
} else {
    $visible = 0;
}
if ($visibleReseller == "on") {
    $visibleReseller = 1;
} else {
    $visibleReseller = 0;
}
$usuarios = $_POST['usuarios'];
echo "VISIBLE: " . $visible . "\tRESELLER: " . $visibleReseller;
echo "USUARIOS ";
print_r($usuarios);

switch ($accion) {
    case 'delete':
        $query = "DELETE FROM multiplataforma.guion_callcenter WHERE ID = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $id);
        $stmt->execute();
        if ($stmt) {
            echo "La pregunta se ha borrado correctamente\n";
        } else {
            echo "Error en el borrado de la pregunta\n";
            exit;
        }
        break;

    case 'update':
        $query = "UPDATE multiplataforma.guion_callcenter SET TIPO = ?, CASO = ?, RESPUESTA = ?, VISIBLE = ?, VISIBLE_RESELLER = ? WHERE ID = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('isssss', $tipo, $caso, $respuesta, $visible, $visibleReseller, $id);
        $stmt->execute();
        if ($stmt) {
            echo "La pregunta se ha modificado correctamente\n";
        } else {
            echo "Error en la modificación de la pregunta\n";
            exit;
        }
        // INSERTO NUEVOS USUARIOS O UPDATEO PARA NO DUPLICADOS        
        for ($i = 0; $i < count($usuarios); $i++) {
            $user = $usuarios[$i];
            $query = "INSERT INTO multiplataforma.usuarios_guion_callcenter VALUES(null, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param('ss', $id, $user);
            $stmt->execute();
            if ($stmt) {
                echo "El usuario " . $user  . " se ha insertado correctamente\n";
            } else {
                echo "Error en la inserción de usuario\n";
                exit;
            }
        }
        break;

    case 'add':
        // INSERTO LA PREGUNTA
        $query = "INSERT INTO multiplataforma.guion_callcenter VALUES(null,?,?,?,?,?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param('issss', $tipo, $caso, $respuesta, $visible, $visibleReseller);
        $stmt->execute();
        if ($stmt) {
            echo "La pregunta " . $caso  . " se ha insertado correctamente\n";
        } else {
            echo "Error en la inserción de la pregunta\n";
            exit;
        }
        //SELECCIONO ID PREGUNTA
        $query = "SELECT ID FROM multiplataforma.guion_callcenter ORDER BY ID desc LIMIT 1;";
        $res = $db->query($query);
        $resultado = $res->fetch_array(MYSQLI_ASSOC);
        $idGuion = $resultado['ID'];
        echo "ID GUION: " . $idGuion . "\n";
        // INSERTO CADA USUARIO CON EL ID DE GUION
        for ($i = 0; $i < count($usuarios); $i++) {
            $user = $usuarios[$i];
            $query = "INSERT INTO usuarios_guion_callcenter VALUES(null,?,?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param('ss', $idGuion, $user);
            $stmt->execute();
            if ($stmt) {
                echo "El usuario " . $user  . " se ha insertado correctamente\n";
            } else {
                echo "Error en la inserción de usuario\n";
                exit;
            }
        }
        break;

    default:
        exit();
        break;
}
