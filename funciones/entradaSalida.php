<?php
if (!isset($_SESSION)) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$_POST = json_decode(file_get_contents("php://input"), true);

include_once "./conexDB.php";

$db = DB::getInstance();

// Declaración POST

$tipo = htmlspecialchars($_POST["tipo"], ENT_QUOTES);
$dni = $_SESSION["dni"];
$fecha = date("Y-m-d H:i:s", time());

// Código BD
// Inserción salida

$query = "INSERT INTO centrojoven.historial VALUES(null,?,?,?)"; // 3
$stmt = $db->prepare($query);
$stmt->bind_param('sss',$dni, $tipo, $fecha);
try{
    $stmt->execute();
    if ($stmt) {
        echo "Inserción enviada";    
    } else {
        echo "Error en la inserción de solicitud";
        exit;
    }
} catch (mysqli_sql_exception $e) {
    echo json_encode(array(
        "respuesta" => "Duplicado"
    ));
}

$db->close();