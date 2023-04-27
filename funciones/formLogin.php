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

$email = htmlspecialchars($_POST["email"], ENT_QUOTES);
$contrasena = htmlspecialchars($_POST["contrasena"], ENT_QUOTES);
$contrasena = hash('sha256', $contrasena);

// Código BD
// LOGIN
$query = "SELECT DNI as dni, idVoluntario as id FROM voluntarios WHERE Email like ? AND Password like ?";
$stmt = $db->prepare($query);
$stmt->bind_param('ss', $email, $contrasena);
$stmt->execute();
$res = $stmt->get_result();
$resultado = [];
while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
    array_push($resultado, $row);
}

if (count($resultado) > 0) {    
    $_SESSION['dni'] = $resultado[0]['dni'];
    $_SESSION['id'] = $resultado[0]['id'];
    //echo $_SESSION['dni'];
}

$db->close();
echo json_encode($resultado);