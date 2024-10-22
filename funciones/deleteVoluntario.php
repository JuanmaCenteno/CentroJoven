<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION)) {
    session_start();
}

include_once "./conexDB.php";

$db = DB::getInstance();

// Declaración POST

$dni = htmlspecialchars($_POST["dni"], ENT_QUOTES);
//echo $dni;

// Código BD
// LOGIN
$query = "DELETE FROM voluntarios WHERE dni like ?";
$stmt = $db->prepare($query);
$stmt->bind_param('s', $dni);
$stmt->execute();
if($stmt){
    $resultado = [ "mensaje"  => "Voluntario borrado correctamente"];
}else{
    $resultado = [ "mensaje"  => "Voluntario no se ha borrado"];
}

$db->close();
echo json_encode($resultado);
