<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once $_SERVER['DOCUMENT_ROOT'] . "/content/appMovil/conexDB.php";

$accion = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
$plataforma = filter_input(INPUT_POST, 'plataforma', FILTER_SANITIZE_STRING);

$db = DB::getInstance($plataforma);

// Variables para insertar
// Centralitas
$codigoCliente = filter_input(INPUT_POST, 'codigoCliente', FILTER_SANITIZE_NUMBER_INT);
$idCentralita = "AS" . $codigoCliente;
$numeroTelefono = filter_input(INPUT_POST, 'numeroTelefono', FILTER_SANITIZE_STRING);
$numeroTelefono2 = filter_input(INPUT_POST, 'numeroTelefono2', FILTER_SANITIZE_STRING);
$numeroTelefono3 = filter_input(INPUT_POST, 'numeroTelefono3', FILTER_SANITIZE_STRING);
$idc = $idCentralita . $numeroTelefono;
$nombreSede = filter_input(INPUT_POST, 'nombreSede', FILTER_SANITIZE_STRING);
$tipoConexion = filter_input(INPUT_POST, 'tipoConexion', FILTER_SANITIZE_NUMBER_INT);
$tipoElectronica = filter_input(INPUT_POST, 'tipoElectronica', FILTER_SANITIZE_NUMBER_INT);
$idAntiguo = filter_input(INPUT_POST, 'idAntiguo', FILTER_SANITIZE_STRING);
// Departamentos
$cDepartamento = filter_input(INPUT_POST, 'CDepartamento', FILTER_SANITIZE_NUMBER_INT);
$arrayDDepartamento = $_POST['dDepartamento'];
$arrayNumTlfDepartamento = $_POST['numTlfDepartamento'];
$arrayRutaLocucionDepartamento = $_FILES['locucionDepartamento']; //SUBIR ARCHIVOS
$arrayNombreDepartamento = $_POST['nombreDepartamento'];
$dAntiguo = filter_input(INPUT_POST, 'dAntiguo', FILTER_SANITIZE_STRING);
$idCent = filter_input(INPUT_POST, 'idCent', FILTER_SANITIZE_STRING);
$idcdDepartamento = filter_input(INPUT_POST, 'idcdDepartamento', FILTER_SANITIZE_STRING);
$idcdDepartamentoAntiguo = $idCent . $dAntiguo;

// Lineas Dialplan
$arrayDDialplan = $_POST['dDialplan'];
$arrayNumTlfDialplan = $_POST['numTlfDialplan'];
$arrayExtDialplan = $_POST['extDialplan'];
$arrayNombreDialplan = $_POST['nombreDialplan'];
$arrayTonoDialplan = $_POST['tonoDialplan'];
$arrayRetornoDialplan = $_POST['retornoDialplan'];
$dDialplanAntiguo = filter_input(INPUT_POST, 'dDialplanAntiguo', FILTER_SANITIZE_STRING);
$extensionAntiguo = filter_input(INPUT_POST, 'extensionAntiguo', FILTER_SANITIZE_STRING);

$cifCliente = filter_input(INPUT_POST, 'cifCliente', FILTER_SANITIZE_STRING);

$rutaCarpetaCliente = $_SERVER['DOCUMENT_ROOT'] . "/documentos/FIJA/centralitas/" . $cifCliente;


switch ($accion) {
    case 'delete':
        // Borrado Centralita
        $query = "DELETE FROM centralitas WHERE IDC = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $idCentralita);
        $stmt->execute();
        if ($stmt) {
            echo "La centralita se ha borrado correctamente\n";
            // Borra Carpeta con locuciones
            echo "RUTA CARPETA CLIENTE: " . $rutaCarpetaCliente;
            deleteDir($rutaCarpetaCliente);
            echo "La carpeta de centralita se ha borrado correctamente\n";
        } else {
            echo "Error en el borrado de la centralita\n";
            //exit;
        }
        break;
    case 'deleteDepartamento':
        // Borrado Departamento
        $query = "DELETE FROM locucionesdialplan WHERE IDCD = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $idCent);
        $stmt->execute();
        if ($stmt) {
            echo "El departamento se ha borrado correctamente\n";
            // Borra Carpeta con locuciones
            $rutaCarpetaCliente = $rutaCarpetaCliente . "/locuciones";
            $rutaCarpetaDepartamento = $rutaCarpetaCliente . "/DP_" . $arrayDDepartamento;
            deleteDir($rutaCarpetaDepartamento);
            echo "La carpeta del departamento se ha borrado correctamente\n";
        } else {
            echo "Error en el borrado del departamento\n";
            //exit;
        }
        break;
    case 'deleteLinea':
        // Borrado Línea        
        $query = "DELETE FROM dialplan WHERE IDCD = ? AND extension = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('ss', $idCent, $arrayExtDialplan);
        $stmt->execute();
        if ($stmt) {
            echo "La línea se ha borrado correctamente\n";
        } else {
            echo "Error en el borrado de la línea\n";
            //exit;
        }
        break;
    case 'update':
        $rutaCarpetaCliente = $_SERVER['DOCUMENT_ROOT'] . "/documentos/FIJA/centralitas/" . $cifCliente . "/" . $idAntiguo .  "/locuciones";
        $rutaLocucionPpal = $rutaCarpetaCliente . "/LOC_" . $idCentralita . ".mp3";
        $rutaLocucionPpalAntigua = $rutaCarpetaCliente . "/LOC_" . $idAntiguo . ".mp3";
        // UPDATE LOCUCION PPAL
        if ($_FILES['locucionIDC']['size'] != 0) {
            if (file_exists($rutaLocucionPpalAntigua)) {
                unlink($rutaLocucionPpalAntigua);
            }
            if (move_uploaded_file($_FILES['locucionIDC']['tmp_name'], $rutaLocucionPpal)) {
                echo "La locución ppal se ha actualizado correctamente\n";
            } else {
                echo "Error al subir los archivos\n";
                //exit;
            }
        } else {
            echo "RUTA ANTIGUA: " . $rutaLocucionPpalAntigua . "\n";
            if (file_exists($rutaLocucionPpalAntigua)) {
                if (rename($rutaLocucionPpalAntigua, $rutaLocucionPpal)) {
                    echo "RUTA ANTIGUA: " . $rutaLocucionPpalAntigua . "\nRUTA NUEVA: " . $rutaLocucionPpal;
                    echo "La locución ppal se ha modificado correctamente\n";
                } else {
                    echo "Error al modificar la locución ppal cambio ID\n";
                    //exit;
                }
            } else {
                echo "El fichero antiguo no existe\n";
            }
        }
        // UPDATE CENTRALITA
        $query = "UPDATE centralitas SET ID = ?, cliente = ?, numeroTelefono = ?, numeroTelefono2 = ?, numeroTelefono3 = ?, IDC = ?, nombreSede = ?, locucionPrincipal = ?, tipoConexion = ?, tipoElectronica = ? WHERE IDC = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('sissssssiis', $idCentralita, $codigoCliente, $numeroTelefono, $numeroTelefono2, $numeroTelefono3, $idc, $nombreSede, $rutaLocucionPpal, $tipoConexion, $tipoElectronica, $idAntiguo);
        $stmt->execute();
        if ($stmt) {
            echo "La centralita se ha modificado correctamente\n";
        } else {
            echo "Error en la modificación de centralitas\n";
            //exit;
        }
        break;
    case 'updateDepartamento':
        echo ("IDC: " . $idCent);
        $rutaCarpetaCliente = $_SERVER['DOCUMENT_ROOT'] . "/documentos/FIJA/centralitas/" . $cifCliente . "/" . $idCent . "/locuciones";
        $rutaCarpetaDepartamento = $rutaCarpetaCliente . "/DP_" . $arrayDDepartamento;
        $rutaCarpetaDepartamentoAntigua = $rutaCarpetaCliente . "/DP_" . $dAntiguo;
        $rutaLocucionDepartamento = $rutaCarpetaDepartamento . "/LOC_" . $idcdDepartamento . ".mp3";
        $rutaLocucionDepartamentoAntigua = $rutaCarpetaDepartamento . "/LOC_" . $idcdDepartamentoAntiguo . ".mp3";
        // UPDATE LOCUCION DEPARTAMENTO
        // Cambiar nombre a la carpeta de departamento
        rename($rutaCarpetaDepartamentoAntigua, $rutaCarpetaDepartamento);
        // Cambiar nombre al archivo de locucion
        echo "RUTA: " . $rutaLocucionDepartamentoAntigua;
        if ($_FILES['locucionDepartamento']['size'] != 0) {
            if (file_exists($rutaLocucionDepartamentoAntigua)) {
                if (unlink($rutaLocucionDepartamentoAntigua)) {
                    echo "La locución de departamento se ha eliminado correctamente\n";
                    echo "RUTA: " . $rutaLocucionDepartamentoAntigua;
                } else {
                    echo "No se ha podido borrar la locución del departamento\n";
                    echo "RUTA: " . $rutaLocucionDepartamentoAntigua;
                }
            }
            if (move_uploaded_file($_FILES['locucionDepartamento']['tmp_name'], $rutaLocucionDepartamento)) {
                echo "La locución de departamento se ha actualizado correctamente\n";
            } else {
                echo "Error al subir los archivos\n";
                //exit;
            }
        } else {
            echo "RUTA ANTIGUA: " . $rutaLocucionDepartamentoAntigua . "\n";
            if (file_exists($rutaLocucionDepartamentoAntigua)) {
                if (rename($rutaLocucionDepartamentoAntigua, $rutaLocucionDepartamento)) {
                    echo "RUTA ANTIGUA: " . $rutaLocucionDepartamentoAntigua . "\nRUTA NUEVA: " . $rutaLocucionDepartamento;
                    echo "La locución ppal se ha modificado correctamente\n";
                } else {
                    echo "Error al modificar la locución ppal cambio ID\n";
                    //exit;
                }
            } else {
                echo "El fichero antiguo no existe\n";
            }
        }
        // UPDATE DIALPLAN
        $query = "UPDATE locucionesdialplan SET IDC = ?, D = ?, IDCD = ?, telefono = ?, locucion = ?, nombre = ? WHERE IDC = ? AND D = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('ssssssss', $idCent, $arrayDDepartamento, $idcdDepartamento, $arrayNumTlfDepartamento, $rutaLocucionDepartamento, $arrayNombreDepartamento, $idCent, $dAntiguo);
        $stmt->execute();
        if ($stmt) {
            echo "El departamento se ha modificado correctamente\n";
            echo "ID: " . $idCent . "\nC: " . $cDepartamento . "D: " . $arrayDDepartamento . "\nLocucion: " . $rutaLocucionDepartamento . "Nombre: " . $nombreDepartamento . "C ANT: " . $cDepartamentoAntiguo . "\nD ANT: " . $dAntiguo;
        } else {
            echo "Error en la modificación de departamento\n";
            //exit;
        }
        break;
    case 'updateLinea':
        // UPDATE LINEA        
        $query = "UPDATE dialplan SET telefono = ?, extension = ?, nombre = ?, tonos = ?, extRetorno = ? WHERE IDCD = ? AND extension = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('sssssss', $arrayNumTlfDialplan, $arrayExtDialplan, $arrayNombreDialplan, $arrayTonoDialplan, $arrayRetornoDialplan, $idCent, $extensionAntiguo);
        $stmt->execute();
        if ($stmt) {
            echo "La línea se ha modificado correctamente\n";
        } else {
            echo "Error en la modificación de la línea\n";
            //exit;
        }
        break;
    case 'insert':
        // Crear Carpeta de Cliente en el servidor 
        if (!is_dir($rutaCarpetaCliente)) {
            mkdir($rutaCarpetaCliente, 0777, true);
        }
        $rutaCarpetaCliente = $_SERVER['DOCUMENT_ROOT'] . "/documentos/FIJA/centralitas/" . $cifCliente . "/" . $idc .  "/locuciones";
        if (!is_dir($rutaCarpetaCliente)) {
            mkdir($rutaCarpetaCliente, 0777, true);
        }
        // Subida Archivo Locución Ppal
        //$rutaLocucionPpal = $rutaCarpetaCliente . "/" . $_FILES['locucionIDC']['name'];
        $rutaLocucionPpal = $rutaCarpetaCliente . "/LOC_" . $idc . ".mp3";

        if (move_uploaded_file($_FILES['locucionIDC']['tmp_name'], $rutaLocucionPpal)) {
            echo "La locución ppal se ha subido correctamente\n";
        } else {
            echo "Error al subir los archivos\n";
            echo "RUTA LOC: " . $rutaLocucionPpal;
            //exit;
        }

        // Inserción Centralita
        $query = "INSERT INTO centralitas VALUES(null,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param('sissssssii', $idCentralita, $codigoCliente, $numeroTelefono, $numeroTelefono2, $numeroTelefono3, $idc, $nombreSede, $rutaLocucionPpal, $tipoConexion, $tipoElectronica);
        $stmt->execute();
        if ($stmt) {
            echo "Las centralitas se han insertado correctamente\n";
            // Borra Carpeta con locuciones
            //rmdir($rutaCarpetaCliente);
        } else {
            echo "Error en la inserción de centralitas\n";
            //exit;
        }

        // Inserción Departamentos
        for ($i = 0; $i < count($arrayDDepartamento); $i++) {
            $dDepartamento = filter_var($arrayDDepartamento[$i], FILTER_SANITIZE_NUMBER_INT);
            $nombreDepartamento = filter_var($arrayNombreDepartamento[$i], FILTER_SANITIZE_STRING);
            $numTlf = filter_var($arrayNumTlfDepartamento[$i], FILTER_SANITIZE_STRING);
            $idc = $idCentralita . $numeroTelefono;
            $idcd = $idc . $dDepartamento;
            $rutaCarpetaDepartamento = $rutaCarpetaCliente . "/DP_" . $dDepartamento;
            if (!is_dir($rutaCarpetaDepartamento)) {
                mkdir($rutaCarpetaDepartamento, 0777, true);
            }

            // Subida Archivo Locución por Departamento
            //$rutaLocucionDepartamento = $rutaCarpetaDepartamento . "/" . $arrayRutaLocucionDepartamento['name'][$i];
            $rutaLocucionDepartamento = $rutaCarpetaDepartamento . "/LOC_" . $idcd . ".mp3";

            if (move_uploaded_file($arrayRutaLocucionDepartamento["tmp_name"][$i], $rutaLocucionDepartamento)) {
                echo "La locución del departamento " . $nombreDepartamento . " se ha subido correctamente\n";
            } else {
                var_dump($arrayRutaLocucionDepartamento);
                echo "Error al subir los archivos de departamento\n";
                echo "Not uploaded because of error #" . $arrayRutaLocucionDepartamento["error"];
                echo "RUTA: " . $rutaLocucionDepartamento;
                echo "IDCD: " . $idcd;
                //exit;
            }
            // Inserción
            $query = "INSERT INTO locucionesdialplan VALUES(null,?,?,?,?,?,?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param(
                'sissss',
                $idc,
                $dDepartamento,
                $idcd,
                $numTlf,
                $rutaLocucionDepartamento,
                $nombreDepartamento
            );
            $stmt->execute();
            if ($stmt) {
                echo "El departamento " . $nombreDepartamento . " se ha insertado correctamente\n";
            } else {
                echo "Error en la inserción de departamento\n";
                //exit;
            }
        }

        // Inserción Dialplans
        for ($i = 0; $i < count($arrayNombreDialplan); $i++) {
            $dDialplan = filter_var($arrayDDialplan[$i], FILTER_SANITIZE_NUMBER_INT);
            $numTlf = filter_var($arrayNumTlfDialplan[$i], FILTER_SANITIZE_STRING);
            $extDialplan = filter_var($arrayExtDialplan[$i], FILTER_SANITIZE_NUMBER_INT);
            $nombreDialplan = filter_var($arrayNombreDialplan[$i], FILTER_SANITIZE_STRING);
            $tonoDialplan = filter_var($arrayTonoDialplan[$i], FILTER_SANITIZE_NUMBER_INT);
            $retornoDialplan = filter_var($arrayRetornoDialplan[$i], FILTER_SANITIZE_NUMBER_INT);
            $idcd = $idCentralita . $numeroTelefono . $dDialplan;
            $query = "INSERT INTO dialplan VALUES(null,?,?,?,?,?,?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param('ssssss', $idcd, $numTlf, $extDialplan, $nombreDialplan, $tonoDialplan, $retornoDialplan);
            $stmt->execute();
            if ($stmt) {
                echo "El dialplan " . $nombreDialplan  . " se ha insertado correctamente\n";
            } else {
                echo "Error en la inserción del dialplan\n";
                //exit;
            }
        }
        break;

    case 'insertDepartamento':
        $idcd = $idCent . $arrayDDepartamento;
        $rutaCarpetaCliente = $_SERVER['DOCUMENT_ROOT'] . "/documentos/FIJA/centralitas/" . $cifCliente . "/" . $idCent .  "/locuciones";
        $rutaCarpetaDepartamento = $rutaCarpetaCliente . "/DP_" . $arrayDDepartamento;
        if (!is_dir($rutaCarpetaDepartamento)) {
            mkdir($rutaCarpetaDepartamento, 0777, true);
        }

        // Subida Archivo Locución por Departamento
        $rutaLocucionDepartamento = $rutaCarpetaDepartamento . "/LOC_" . $idcd . ".mp3";

        if (move_uploaded_file($arrayRutaLocucionDepartamento['tmp_name'], $rutaLocucionDepartamento)) {
            echo "La locución del departamento " . $arrayNombreDepartamento . " se ha subido correctamente\n";
        } else {
            echo "Error al subir los archivos de departamento\n";
            //exit;
        }
        // Inserción
        $query = "INSERT INTO locucionesdialplan VALUES(null,?,?,?,?,?,?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param(
            'ssssss',
            $idCent,
            $arrayDDepartamento,
            $idcd,
            $arrayNumTlfDepartamento,
            $rutaLocucionDepartamento,
            $arrayNombreDepartamento
        );
        $stmt->execute();
        if ($stmt) {
            echo "El departamento " . $arrayNombreDepartamento . " se ha insertado correctamente\n";
        } else {
            echo "Error en la inserción de departamento\n";
            //exit;
        }

        break;
    case 'insertLinea':
        // Inserción Línea
        $idcd = $idCent . $arrayDDialplan;
        $query = "INSERT INTO dialplan VALUES(null,?,?,?,?,?,?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param('ssssss', $idcd, $arrayNumTlfDialplan, $arrayExtDialplan, $arrayNombreDialplan, $arrayTonoDialplan, $arrayRetornoDialplan);
        $stmt->execute();
        if ($stmt) {
            echo "El dialplan " . $arrayNombreDialplan  . " se ha insertado correctamente\n";
        } else {
            echo "Error en la inserción del dialplan\n";
            //exit;
        }

        break;
    default:
        //exit();
        break;
}


function deleteDir($dirPath)
{
    if (!is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}




/*
//Filtros Subida Archivo

$uploadedfile_size = $_FILES['uploadedfile'][size];

if ($_FILES[uploadedfile][size] > 200000) {
    $msg = $msg . "El archivo es mayor que 200KB, debes reduzcirlo antes de subirlo<BR>";
    $uploadedfileload = "false";
}

if (!($_FILES[uploadedfile][type] == "image/pjpeg" or $_FILES[uploadedfile][type] == "image/gif")) {
    $msg = $msg . " Tu archivo tiene que ser JPG o GIF. Otros archivos no son permitidos<BR>";
    $uploadedfileload = "false";
}

$file_name = $_FILES[uploadedfile][name];
$add = "uploads/$file_name";


// Array of allowed image file formats 
$allowedExtensions = array('aif', 'aiff', 'au', 'flac', 'mp3', 'm4a', 'ogg', 'snd', 'wav', 'w64');

foreach ($_FILES as $file) {
    if ($file['tmp_name'] > '') {
        if (!in_array(
            end(explode(
                ".",
                strtolower($file['name'])
            )),
            $allowedExtensions
        )) {
            $uploadedfileload = false;
        }
    }
}
*/