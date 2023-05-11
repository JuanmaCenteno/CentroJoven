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
$fechaComprobar = date("Y-m-d", time()) . "%";

// Código BD
// TODO - Comprobar que no ha salido antes de entrado
// Primero comprobamos que no hay ninguna entrada/salida
$query = "SELECT * FROM centrojoven.historial WHERE dniVoluntario like ? AND fechayhora like ? AND tipo LIKE ?";
$stmt = $db->prepare($query);
$stmt->bind_param('sss',$dni, $fechaComprobar, $tipo);
$stmt->execute();
$res = $stmt->get_result();
$resultado = [];
while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
    array_push($resultado, $row);
}

// Si está vacío significa que es la primera vez que ficha

if(empty($resultado)){
    // Inserción salida
    $query = "INSERT INTO centrojoven.historial VALUES(null,?,?,?)"; // 3
    $stmt = $db->prepare($query);
    $stmt->bind_param('sss',$dni, $tipo, $fecha);
    try{
        $stmt->execute();
        if ($stmt) {
            $resultado = [ "mensaje"  => ucfirst($tipo) . " registrada correctamente."];  
        } else {
            $resultado = [ "mensaje"  => "Error en la " . $tipo];
        }
    } catch (mysqli_sql_exception $e) {
        $resultado = [ "mensaje"  => "Ya has fichado la " . $tipo . " hoy."];
    }
}
else{
    $resultado = [ "mensaje"  => "Ya has fichado la " . $tipo . " hoy."];
}

$db->close();
echo json_encode($resultado);