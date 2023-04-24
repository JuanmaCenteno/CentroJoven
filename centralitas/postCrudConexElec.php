<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/content/appMovil/conexDB.php";

$accion = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
$plataforma = filter_input(INPUT_POST, 'plataforma', FILTER_SANITIZE_STRING);

$db = DB::getInstance($plataforma);

// Variables para insertar
// Centralitas
$nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
$nombreAntiguo = filter_input(INPUT_POST, 'nombreAntiguo', FILTER_SANITIZE_STRING);

switch ($accion) {
    case 'deleteConexion':
        $query = "DELETE FROM tipos_conexion WHERE nombre = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $nombre);
        $stmt->execute();
        if ($stmt) {
            echo "La conexión se ha borrado correctamente\n";
        } else {
            echo "Error en el borrado de la conexión\n";
            exit;
        }
        break;

    case 'deleteElectronica':
        $query = "DELETE FROM tipos_electronica WHERE nombre = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $nombre);
        $stmt->execute();
        if ($stmt) {
            echo "La electrónica se ha borrado correctamente\n";
        } else {
            echo "Error en el borrado de la electrónica\n";
            exit;
        }
        break;

    case 'updateConexion':
        $query = "UPDATE tipos_conexion SET nombre = ? WHERE nombre = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('ss', $nombre, $nombreAntiguo);
        $stmt->execute();
        if ($stmt) {
            echo "La conexión se ha modificado correctamente\n";
        } else {
            echo "Error en la modificación de la conexión\n";
            exit;
        }
        break;

    case 'updateElectronica':
        $query = "UPDATE tipos_electronica SET nombre = ? WHERE nombre = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('ss', $nombre, $nombreAntiguo);
        $stmt->execute();
        if ($stmt) {
            echo "La electrónica se ha modificado correctamente\n";
        } else {
            echo "Error en la modificación de la electrónica\n";
            exit;
        }
        break;

    case 'insertConexion':
        $query = "INSERT INTO tipos_conexion VALUES(null,?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $nombre);
        $stmt->execute();
        if ($stmt) {
            echo "La conexión " . $nombre  . " se ha insertado correctamente\n";
        } else {
            echo "Error en la inserción de la conexión\n";
            exit;
        }
        break;

    case 'insertElectronica':
        $query = "INSERT INTO tipos_electronica VALUES(null,?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $nombre);
        $stmt->execute();
        if ($stmt) {
            echo "La electrónica " . $nombre  . " se ha insertado correctamente\n";
        } else {
            echo "Error en la inserción de la electrónica\n";
            exit;
        }
        break;

    default:
        exit();
        break;
}
