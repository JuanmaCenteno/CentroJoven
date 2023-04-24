<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['REVENDEDOR'])) {
    $_SESSION['REVENDEDOR'] = 1;
}
if (!isset($_SESSION['PLATAFORMA'])) {
    $_SESSION['PLATAFORMA'] = 'MOVIL';
}
include_once $_SERVER['DOCUMENT_ROOT'] . "/solicitudesWeb/conexDB.php";

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
error_log("ID --> " . $id);
$dbRemota = DB::getInstance($_SESSION['PLATAFORMA']);

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

// Sentencia obtener Número e ICC

$query = "SELECT NUM_TLF_PORTABILIDAD as tlf, NUM_ICC_PORTABILIDAD as icc, ALTA FROM solicitudesweb WHERE ID_TRANSPORTE = '". $id ."'";
error_log($query);
$stmt = $dbRemota->query($query);
//$stmt->bind_param('s', $id);
//$stmt->execute();
//$res = $stmt->get_result();

$resultado = $stmt->fetch_array(MYSQLI_ASSOC);
$resultado = convert_from_latin1_to_utf8_recursively($resultado);
error_log(print_r($resultado, true));
$tlf = $resultado["tlf"];
$icc = $resultado["icc"];
$alta = $resultado["ALTA"];
// $tlf = "653967952";
// $icc = "1234567890123456789";
$iccCheck = substr($icc, 15, 4);
$iccSub = substr($iccCheck, 0, 2);
error_log($tlf . " - " . $icc . " - " . $alta . " - " . $iccCheck . " - " . $iccSub);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Activar SIM</title>
    <!--Bootstrap 4 y JQuery-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <!--JQuery y UI por si acaso-->
    <!--
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
-->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/style-valida.css">
    <!-- Dispositivos móviles -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
</head>

<body>
    <div class="container">
        <div class="row">
            <img class="imagen" src="resources/netvoz.png">
        </div>
        <div class="row">
            <div class="col-12">
                <h2>Siguiente paso para activar tu SIM</h2>
                <?php if ($alta == "SI") { ?>
                    <p>Para continuar con el alta de nuevo número en Netvoz, ten a mano la SIM de Netvoz que te hemos enviado</p>
                    <p>Necesitamos que valides los siguientes datos.</p>
                    <p>Te envíaremos el número asignado, una vez finalizado el proceso de alta</p>
                <?php } else { ?>
                    <p>Para continuar con el cambio de tu actual compañía a Netvoz y traer tu número móvil (proceso de portabilidad), ten a mano la SIM de Netvoz que te hemos enviado</p>
                    <p>Necesitamos que valides los siguientes datos.</p>
                    <p>Este es el número que vas a traer a Netvoz:</p>
                    <h3><?php echo $tlf ?></h3>
                <?php } ?>

                <p>En la tajeta SIM fíjate en los cuatro últimos números del ICC.</p>
                <p>Tu ICC termina en <?php echo $iccSub ?>XX.<br><strong>Completa los dos números que faltan:</strong></p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <input class="pequeno" id="num1" type="text" value="<?php echo substr($iccSub, 0, 1); ?>" size="1">
            </div>
            <div class="col">
                <input class="pequeno" id="num2" type="text" value="<?php echo substr($iccSub, 1, 1); ?>" size="1">
            </div>
            <div class="col">
                <input class="pequeno" id="num3" type="text" size="1">
            </div>
            <div class="col">
                <input class="pequeno" id="num4" type="text" size="1">
            </div>
        </div>
        <div class="row mt-3">
            <input id="btnValidar" type="button" class="btn btn-lg btn-success btn-block bot" value="Validar">
        </div>
        <!-- Modal Error Consulta -->
        <div id="modalErrorConsulta" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">ERROR</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Rellena los campos con los números</p>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="OK">
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Error ICC -->
        <div id="modalErrorICC" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">ERROR</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>El ICC no coincide. Inténtalo de nuevo</p>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="OK">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('body').bind('touchmove', function(e) {
                e.preventDefault()
            });

            $('#btnValidar').on('click', function() {
                validar();
            });
        });

        var validar = function() {
            if ($('#num1').val() != "" && $('#num2').val() != "" && $('#num3').val() != "" && $('#num4').val() != "") {
                var num = $('#num1').val() + $('#num2').val() + $('#num3').val() + $('#num4').val();
                if (num == <?php echo $iccCheck; ?>) {
                    cambiarEstado();
                    console.log("CORRECTO --> ", num, <?php echo $iccCheck; ?>);
                    <?php if ($alta == 'SI') { ?>
                        // LLAMADA ALTA API MASMOVIL
                        $.ajax({
                            type: 'GET',
                            url: 'api_solicitudes.php',
                            contentType: "text/plain",
                            dataType: 'json',
                            data: {
                                id: id,
                                accion: 'AltaAPI',
                                plataforma: plataforma
                            },
                            success: function(data) {
                                console.log("ALTA REALIZADA CORRECTAMENTE");
                            },
                            error: function(e) {
                                console.log("There was an error with your request...");
                                console.log("error: " + JSON.stringify(e));
                            }
                        });
                        $.ajax({
                            type: 'GET',
                            url: 'api_solicitudes.php',
                            contentType: "text/plain",
                            dataType: 'json',
                            data: {
                                id: id,
                                accion: 'cambiarEstado',
                                est: 6,
                                plataforma: plataforma
                            },
                            success: function(data) {
                                console.log("CORRECTO");
                            },
                            error: function(e) {
                                console.log("There was an error with your request...");
                                console.log("error: " + JSON.stringify(e));
                            }
                        });
                        <?php } else { ?>
                            // LLAMADA PORTA API MASMOVIL
                            $.ajax({
                                type: 'GET',
                                url: 'api_solicitudes.php',
                                contentType: "text/plain",
                                dataType: 'json',
                                data: {
                                    id: id,
                                    accion: 'PortaAPI',
                                    plataforma: plataforma
                                },
                                success: function(data) {
                                    console.log("PORTABILIDAD REALIZADA CORRECTAMENTE");
                                },
                                error: function(e) {
                                    console.log("There was an error with your request...");
                                    console.log("error: " + JSON.stringify(e));
                                }
                            });
                            $.ajax({
                                type: 'GET',
                                url: 'api_solicitudes.php',
                                contentType: "text/plain",
                                dataType: 'json',
                                data: {
                                    id: id,
                                    accion: 'cambiarEstado',
                                    est: 6,
                                    plataforma: plataforma
                                },
                                success: function(data) {
                                    console.log("CORRECTO");
                                },
                                error: function(e) {
                                    console.log("There was an error with your request...");
                                    console.log("error: " + JSON.stringify(e));
                                }
                            });
                        <?php } ?>
                    ?>
                    location.href = "validacionOK.php?a=<?php echo $alta; ?>"
                } else {
                    $('#modalErrorICC').modal('show');
                }
            } else {
                $('#modalErrorConsulta').modal('show');
            }
        }

        var cambiarEstado = function() {
            var plataforma = "<?php echo $_SESSION['PLATAFORMA']; ?>";
            var id = "<?php echo $id; ?>";
            $.ajax({
                type: 'GET',
                url: 'api_solicitudes.php',
                contentType: "text/plain",
                dataType: 'json',
                data: {
                    id: id,
                    accion: 'cambiarEstado',
                    est: 6,
                    plataforma: plataforma
                },
                success: function(data) {
                    console.log("CORRECTO");
                },
                error: function(e) {
                    console.log("There was an error with your request...");
                    console.log("error: " + JSON.stringify(e));
                }
            });
        }
    </script>
</body>

</html>