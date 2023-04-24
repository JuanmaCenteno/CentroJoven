<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "./conexDB.php";

$db = DB::getInstance();

// Declaración POST

$nombreCompleto = htmlspecialchars($_POST["nombreCompleto"], ENT_QUOTES);
$apellido1 = htmlspecialchars($_POST["apellido1"], ENT_QUOTES);
$apellido2 = htmlspecialchars($_POST["apellido2"], ENT_QUOTES);
$dni = htmlspecialchars($_POST["dni"], ENT_QUOTES);
$fechaNacimiento = htmlspecialchars($_POST["fechaNacimiento"], ENT_QUOTES);
$email = htmlspecialchars($_POST["email"], ENT_QUOTES);
$contrasena = htmlspecialchars($_POST["contrasena"], ENT_QUOTES);
$contrasena = hash('sha256', $contrasena);
$direccion = htmlspecialchars($_POST["direccion"], ENT_QUOTES);
$cpostal = htmlspecialchars($_POST["cpostal"], ENT_QUOTES);
$movil = htmlspecialchars($_POST["movil"], ENT_QUOTES);

// Código BD
// Inserción Usuario
$query = "INSERT INTO centrojoven.voluntarios VALUES(null,?,?,?,?,?,?,?,?,?,?)"; // 10
$stmt = $db->prepare($query);
$stmt->bind_param('ssssssssss',$nombreCompleto, $apellido1, $apellido2, $dni, $fechaNacimiento, $email, $contrasena, $direccion, $cpostal, $movil);
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
