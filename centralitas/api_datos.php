<?php

if (!isset($_SESSION)) {
    session_start();
}

include_once $_SERVER['DOCUMENT_ROOT'] . "/content/appMovil/conexDB.php";
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/util.php';
header('Content-type: application/json; charset=utf-8');


$accion = filter_input(INPUT_GET, 'accion', FILTER_SANITIZE_STRING);
$n = filter_input(INPUT_GET, 'num', FILTER_SANITIZE_STRING);
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
$c = filter_input(INPUT_GET, 'c', FILTER_SANITIZE_STRING);
$d = filter_input(INPUT_GET, 'd', FILTER_SANITIZE_STRING);
$idEmp = filter_input(INPUT_GET, 'idEmp', FILTER_SANITIZE_STRING);
$idc = filter_input(INPUT_GET, 'idc', FILTER_SANITIZE_STRING);
$plataforma = filter_input(INPUT_GET, 'plataforma', FILTER_SANITIZE_STRING);
$emailNetvoz = "solicitudes@netvoz.eu";

if ($id == "1") {
    $id = '%';
}

$util = new util();
$db = DB::getInstance($plataforma);
$row = null;
$resultado = [];

function convert_from_latin1_to_utf8_recursively($dat)
{
    if (is_string($dat)) {
        return utf8_encode($dat);
    } elseif (is_array($dat)) {
        $ret = [];
        foreach ($dat as $i => $d) $ret[$i] = convert_from_latin1_to_utf8_recursively($d);

        return $ret;
    } elseif (is_object($dat)) {
        foreach ($dat as $i => $d) $dat->$i = convert_from_latin1_to_utf8_recursively($d);

        return $dat;
    } else {
        return $dat;
    }
}

switch ($accion) {
        // Lista de Nombres para el ComboBox
    case "listaEmpresas":
        $query = "SELECT ID as id, CONCAT(NOMBRE,' | ',APELLIDOS) as apellidos FROM clientes WHERE ID like ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            array_push($resultado, $row);
        }
        $resultado = convert_from_latin1_to_utf8_recursively($resultado);
        break;

    case "tiposConexion":
        $query = "SELECT ID as id, NOMBRE as nombre FROM tipos_conexion";
        $res = $db->query($query);
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            array_push($resultado, $row);
        }
        $resultado = convert_from_latin1_to_utf8_recursively($resultado);
        break;
    case "tiposElectronica":
        $query = "SELECT ID as id, NOMBRE as nombre FROM tipos_electronica";
        $res = $db->query($query);
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            array_push($resultado, $row);
        }
        $resultado = convert_from_latin1_to_utf8_recursively($resultado);
        break;
    case "loadCif":
        $query = "SELECT DNI as cif FROM clientes WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            array_push($resultado, $row);
        }
        $resultado = convert_from_latin1_to_utf8_recursively($resultado);
        break;
    case "listaCentralitas":
        $query = "SELECT * FROM centralitas";
        $res = $db->query($query);
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            array_push($resultado, $row);
        }
        $resultado = convert_from_latin1_to_utf8_recursively($resultado);
        break;
    case "listaDepartamentos":
        $query = "SELECT * FROM locucionesdialplan WHERE IDC = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            array_push($resultado, $row);
        }
        $resultado = convert_from_latin1_to_utf8_recursively($resultado);
        break;
    case "listaDiaplans":
        $query = "SELECT * FROM dialplan WHERE IDCD = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $resultado = [];
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            array_push($resultado, $row);
        }
        $resultado = convert_from_latin1_to_utf8_recursively($resultado);
        break;

    case "mandarCorreo":
        $cifCliente = getCif($db, $idEmp);
        // IF FILE EXISTS
        $rutaDirectorioCsv = $_SERVER['DOCUMENT_ROOT'] . "/documentos/FIJA/centralitas/" . $cifCliente . "/" . $idc . "/download.zip";
        if (file_exists($rutaDirectorioCsv)) {
            $resultado = $util->enviarEmail($emailNetvoz, "NETVOZ", "masmovil@11400.es", "", "", "", "Ficheros de centralita", "Estos son sus ficheros de centralita", $rutaDirectorioCsv, "");
        } else {
            $resultado = [
                'msg' => "ERROR"
            ];
        }
        break;

    case "exportCsv":
        $query = "SELECT  c.pk as cpk, c.ID as cid, c.cliente as cc, c.numeroTelefono as cnum, c.numeroTelefono2 as cnum2, c.numeroTelefono3 as cnum3, c.IDC as cidc, c.nombreSede as cnombre, c.locucionPrincipal as cloc, c.tipoConexion as ctc, c.tipoElectronica as cte, l.pk as lpk, l.IDC as lidc, l.D as ld, l.IDCD as lidcd, l.telefono as ltlf, l.locucion as lloc, l.nombre as lnombre, d.pk as dpk, d.IDCD as didcd, d.telefono as dtlf ,d.extension as dext, d.nombre as dnombre, d.tonos as dtonos, d.extRetorno as dret, tc.id as tcid, tc.nombre as tcnombre, te.id as teid, te.nombre as tenombre
        FROM multiplataforma.centralitas c INNER JOIN multiplataforma.locucionesdialplan l
        ON c.IDC = l.IDC
        INNER JOIN multiplataforma.dialplan d
        ON d.IDCD = l.IDCD
        INNER JOIN multiplataforma.tipos_conexion tc
        ON tc.id = c.tipoConexion
        INNER JOIN multiplataforma.tipos_electronica te
        ON te.id = c.tipoElectronica WHERE c.IDC = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $resultado = [];
        $cifCliente = getCif($db, $idEmp);
        // FUNCION CSV        
        $cabecera = "pk;ID;cliente;numeroTelefono;numeroTelefono2;numeroTelefono3;IDC;nombreSede;locucionPrincipal;tipoConexion;tipoElectronica;pk;IDC;D;IDCD;telefono;locucion;nombre;pk;IDCD;telefono;extension;nombre;tonos;extRetorno;id;nombre;id;nombre\n";
        $rutaDirectorioCsv = $_SERVER['DOCUMENT_ROOT'] . "/documentos/FIJA/centralitas/" . $cifCliente . "/" . $id;
        if (!is_dir($rutaDirectorioCsv)) {
            mkdir($rutaDirectorioCsv, 0777, true);
        } 
        $archivoCsv = fopen($rutaDirectorioCsv . '/export.csv', "w");
        fwrite($archivoCsv, $cabecera);
        fclose($archivoCsv);
        while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
            $rutaDirectorioCsv = $_SERVER['DOCUMENT_ROOT'] . "/documentos/FIJA/centralitas/" . $cifCliente . "/" . $id;
            if (!is_dir($rutaDirectorioCsv)) {
                mkdir($rutaDirectorioCsv, 0777, true);
            }

            $archivoCsv = fopen($rutaDirectorioCsv . '/export.csv', "a");
            if ($archivoCsv == NULL) {
                print_r(error_get_last());
            }

            if (!file_exists($rutaDirectorioCsv . '/export.csv')) {
                fwrite($archivoCsv, $cabecera);
            }
            $row['cloc'] = substr($row['cloc'], 60);
            $row['lloc'] = substr($row['lloc'], 60);
            $lineaCSV = implode(';', $row) . "\n";
            fwrite($archivoCsv, $lineaCSV);
            fclose($archivoCsv);
        }

        // Get real path for our folder
        $rootPath = $_SERVER['DOCUMENT_ROOT'] . "/documentos/FIJA/centralitas/" . $cifCliente . "/" . $id;
        $zip_file = $rootPath . "/download.zip";
        if (file_exists($zip_file)) {
            unlink($zip_file);
        }
        // Initialize archive object
        $zip = new ZipArchive();
        $zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            // Skip directories (they would be added automatically)
            if (!$file->isDir()) {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();
        $ruta = "http://aiongest.internetinfraestructuras.es/documentos/FIJA/centralitas/" . $cifCliente . "/" . $id . "/download.zip";

        $resultado = [
            'ruta' => $ruta
        ];
        break;

        // TODO: SUBIMOS FICHEROS AL SFTP DEL RESELLER CORRESPONDIENTE
}

function getCif($db, $id)
{
    $query = "SELECT DNI FROM multiplataforma.clientes WHERE ID = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $fila = $res->fetch_array(MYSQLI_ASSOC);
    $fila = convert_from_latin1_to_utf8_recursively($fila);
    return $fila['DNI'];
}

//print_r($resultado);
$db->close();
echo (json_encode($resultado));
//echo json_last_error_msg();
