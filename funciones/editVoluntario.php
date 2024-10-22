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

//var_dump($_POST);

$nombre = htmlspecialchars($_POST["nombre"], ENT_QUOTES);
$apellido1 = htmlspecialchars($_POST["apellido1"], ENT_QUOTES);
$dni = htmlspecialchars($_POST["dni"], ENT_QUOTES);
$apellido2 = htmlspecialchars($_POST["apellido2"], ENT_QUOTES);
$email = htmlspecialchars($_POST["email"], ENT_QUOTES);
$fechaNacimiento = htmlspecialchars($_POST["fechaNacimiento"], ENT_QUOTES);
$password = htmlspecialchars($_POST["password"], ENT_QUOTES);
$direccion = htmlspecialchars($_POST["direccion"], ENT_QUOTES);
$cPostal = htmlspecialchars($_POST["cPostal"], ENT_QUOTES);
$movil = htmlspecialchars($_POST["movil"], ENT_QUOTES);


//echo $dni;

// Código BD
// LOGIN
$query = "UPDATE voluntarios SET Nombre = ?, Apellido1 = ?, Apellido2 = ?, FechaNacimiento = ?, Email = ?, Direccion = ?, CPostal = ?, TlfMovil = ? WHERE dni like ?";
$stmt = $db->prepare($query);
$stmt->bind_param('sssssssss', $nombre, $apellido1, $apellido2, $fechaNacimiento, $email, $direccion, $cPostal, $movil, $dni);
$stmt->execute();
if($stmt){
    $resultado = [ "mensaje"  => "Voluntario editado correctamente"];
}else{
    $resultado = [ "mensaje"  => "Voluntario no se ha editado"];
}

$db->close();
echo json_encode($resultado);
