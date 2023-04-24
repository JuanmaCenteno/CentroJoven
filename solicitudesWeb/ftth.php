<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['REVENDEDOR'])) {
    $_SESSION['REVENDEDOR'] = 1;
}

include_once $_SERVER['DOCUMENT_ROOT'] . "/solicitudesWeb/conexDB.php";

//$tarifa = "";
$nombre = "";
$precio = "";
$idTarifa = "";
$dbRemota = DB::getInstance('FTTH');

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

$query = "SELECT t.codigoProductoFtth as id, descripcionProductoFtth as nombre, precioVentaFtth as precio 
FROM multiplataforma.FTTHProductos p INNER JOIN multiplataforma.FTTHTarifasReseller t
ON p.idProductoFtth = t.codigoProductoFtth INNER JOIN multiplataforma.FTTHPrecios pr
ON pr.idPrecioFtth = t.codigoPrecioFtth
WHERE t.codigoProductoFtth = ?";
$stmt = $dbRemota->prepare($query);
$stmt->bind_param('s', $tarifa);
$stmt->execute();
$res = $stmt->get_result();
$resultado = $res->fetch_array(MYSQLI_ASSOC);
$resultado = convert_from_latin1_to_utf8_recursively($resultado);
$idTarifa = $resultado["id"];
$nombre = $resultado["nombre"];
$precio = $resultado["precio"];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario Registro Netvoz</title>
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
    <link rel="stylesheet" href="solicitudesWeb/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="solicitudesWeb/css/styles.css">
    <!-- Selectpicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <!-- Dispositivos móviles -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
</head>
<style>
    .bootstrap-select .dropdown-menu {
        max-width: 130%;
    }
</style>

<body>
    <!-- Header Netvoz -->
    <header>
        <?php if (!isset($_SESSION['MASTER'])) { ?>
            <a href="https://www.netvoz.eu/" title="Netvoz"><img src="solicitudesWeb/resources/netvoz.png" style="margin-left: 2%; padding: 1%; width: 20%; height: 20%;"></a>
            <p style="float: right; margin-right: 15%;"><strong>¿Te ayudamos? Llama al 623 271 110</strong></p>
        <?php } else if ($_SESSION['MASTER'] == 0) { ?>
            <a href="https://www.netvoz.eu/" title="Netvoz"><img src="solicitudesWeb/resources/netvoz.png" style="margin-left: 2%; padding: 1%; width: 20%; height: 20%;"></a>
            <p style="float: right; margin-right: 15%;"><strong>¿Te ayudamos? Llama al 623 271 110</strong></p>
        <?php } ?>
    </header>

    <div class="container-full">
        <div class="row" id="rowForm">
            <div class="col mt-3" style="margin-left: 15%; height: 100%;">
                <form id="formData" class="mt-4">
                    <ul class="nav nav-tabs" id="myTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="cobertura-tab" href="#tabCobertura" role="tab" aria-controls="home" aria-selected="true">Cobertura</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="datos-tab" href="#tabDatos" role="tab" aria-controls="home" aria-selected="true">Datos del Titular</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="configurar-tab" href="#tabConfigurar" role="tab" aria-controls="profile" aria-selected="false">Configurar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pago-tab" href="#tabPaga" role="tab" aria-controls="messages" aria-selected="false">Pago y Envío</a>
                        </li>
                    </ul>

                    <div class="tab-content mt-4">
                        <!-- Tab Cobertura -->
                        <div class="tab-pane active" id="tabCobertura" role="tabpanel" aria-labelledby="cobertura-tab">
                            <h2>Consulta tu cobertura</h2>
                            <p>Introduce tu dirección para comprobar si tienes cobertura de fibra</p>
                            <!-- Datos Dirección de Instalación -->
                            <div id="divDatosDireccionInstalacion" class="mt-3">
                                <div class="row mt-4 ml-0">
                                    <div class="form-group">
                                        <label>Selecciona una provincia</label>
                                        <select id="selectProvincia" class="selectpicker ml-4" data-live-search="true" title="-">
                                        </select>
                                        <input type="text" class="oculto initial_inputs form-control col-md-12" disabled="disabled" id="municipio" name="municipio">
                                    </div>
                                </div>
                                <div class="row mt-4 ml-0">
                                    <div class="form-group">
                                        <label>Selecciona un Municipio</label>
                                        <select id="selectMunicipio" class="selectpicker ml-4" data-live-search="true" title="-">
                                        </select>
                                        <input type="text" class="oculto initial_inputs form-control col-md-12" disabled="disabled" id="provincia" name="provincia">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>CP*</label>
                                        <input type="text" class="form-control numericInput" maxlength="5" id="codigoPostalInstalacion" name="codigoPostalInstalacion">
                                        <div class="form-group col-md-12">
                                            <div class="invalid-feedback">Introduce un código postal de 5 dígitos.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-8 disabled">
                                        <label>Nombre de calle*</label>
                                        <select id="selectCalle" class="selectpicker ml-0 form-control col-md-12" data-live-search="true" data-dropup-auto="false" title="-"></select>
                                        <input type="text" class="oculto initial_inputs form-control col-md-12" disabled="disabled" id="nombreCalleInstalacion" name="nombreCalleInstalacion">
                                        <div class="invalid-feedback">Escribe el nombre de la calle sin tipo de vía.</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-8">
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label>Número*</label>
                                                <input type="text" class="form-control" id="numeroCalleInstalacion" name="numeroCalleInstalacion" disabled="disabled">
                                                <div class="form-group col-md-12">
                                                    <div class="invalid-feedback">Revisa que el número sea correcto</div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-8">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" disabled="disabled" id="checkSinNumeroInstalacion">
                                                    <label class="custom-control-label" for="checkSinNumeroInstalacion">Sin número</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group align-self-end">
                                            <input type="button" id="btnValidarDireccionInstalacion" class="btn btn-success w-25" disabled="" value="Validar">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Info Extra Dirección Instalación -->
                            <div id="divCompletarDireccionInstalacion" class="oculto ml-3 mt-4">
                                <div id="divBotonElegirDireccionInstalación">
                                    <button type="button" id="btnElegirOtraDireccionInstalacion" class="form-group btn btn-secondary btn-sm text-left">Elegir otra dirección</button>
                                </div>
                                <p>Completa tu dirección, solo si es necesario</p>
                                <div class="row">
                                    <div class="form-group col-md-3 col-12">
                                        <label>Planta</label>
                                        <input type="text" id="plantaCalleDireccionInstalacion" name="plantaCalleDireccionInstalacion" class="form-control">
                                    </div>
                                    <div class="form-group col-md-3 col-12">
                                        <label>Puerta</label>
                                        <input type="text" id="puertaCalleDireccionInstalacion" name="puertaCalleDireccionInstalacion" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-3 col-12">
                                        <label>Bloque</label>
                                        <input type="text" class="form-control" id="bloqueCalleDireccionInstalacion" name="bloqueCalleDireccionInstalacion">
                                    </div>
                                    <div class="form-group col-md-3 col-12">
                                        <label>Escalera</label>
                                        <input type="text" class="form-control" id="escaleraCalleDireccionInstalacion" name="escaleraCalleDireccionInstalacion">
                                    </div>
                                </div>
                            </div>
                            <div class="row ml-0 mt-2">
                                <label id="labelFibraSi" class="oculto okMsg">La fibra está disponible en tu zona</label>
                                <label id="labelFibraNo" class="oculto errorMsg err">La fibra no está disponible en tu zona</label>
                            </div>
                            <div class="col-12 mt-5">
                                <div class="row  justify-content-between">
                                    <button type="button" id="btnContinuarDatos" class="form-group btn btn-primary btn-sm col-md-3 col-12" disabled="">Continuar</button>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Datos -->
                        <div class="tab-pane" id="tabDatos" role="tabpanel" aria-labelledby="datos-tab">
                            <h2>Datos de titular</h2>
                            <p>Ahora introduce los datos para continuar</p>

                            <div class="row form-group">
                                <div class="row" style="width:100%;margin:0">
                                    <div class="col-md-6">
                                        <div class="row align-items-end">
                                            <div class="col-md-6">
                                                <label for="selectTipoDocumento">Documento*</label>
                                                <div class="select_container">
                                                    <select id="selectTipoDocumento" class="form-control">
                                                        <option value="1">DNI</option>
                                                        <option value="2">CIF</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <label for="numeroDocumento" class="hidden">Nº documento</label>
                                                <input type="text" class="form-control" id="numeroDocumento" name="numeroDocumento" placeholder="Nº documento">
                                            </div>
                                            <div class="col">
                                                <label id="labelDocumento" class="errorMsg" style="float: right;">Introduce un Nº de Documento válido</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- DNI CIF, NOMBRE, APELLIDOS -->
                            <div id="contenedorCif" class="oculto mt-4">
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label for="razonSocial">Razón social*</label>
                                        <input type="text" class="form-control required-element" id="razonSocial" name="nombreRazonSocial" maxlength="50" placeholder="Nombre razón social">
                                        <label id="labelRazonSocial" class="errorMsg">El formato de Razón Social es incorrecto</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="form-group col-md-6 col-12">
                                    <label for="ownerName" id="labelDni">Nombre y apellidos del titular*</label>
                                    <label for="ownerName" id="labelCif" class="oculto">Nombre y apellidos de la persona de contacto*</label>
                                    <input type="text" class="form-control" id="nombreTitular" name="nombreTitular" placeholder="Nombre" maxlength="30">
                                    <div class="invalid-feedback">El formato es incorrecto. Asegúrate de que no has incluido caracteres especiales.</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-12">
                                    <input type="text" class="form-control" id="apellido1Titular" name="apellido1Titular" placeholder="Apellido 1" maxlength="30">
                                    <div class="invalid-feedback">El formato es incorrecto. Asegúrate de que no has incluido caracteres especiales.</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-12">
                                    <input type="text" class="form-control" id="apellido2Titular" name="apellido2Titular" placeholder="Apellido 2" maxlength="20">
                                    <div class="invalid-feedback">El formato es incorrecto. Asegúrate de que no has incluido caracteres especiales.</div>
                                </div>
                                <label id="labelApellidos" class="errorMsg">Debes rellenar los campos de nombre y apellidos</label>
                            </div>
                            <!-- Fecha Nacimiento -->
                            <div class="row mt-3">
                                <div class="col-md-6 col-12" id="contenedorFecha">
                                    <label for="diaFechaNacimientoTitular">Fecha de nacimiento* (formato dd/mm/yyyy)</label>
                                    <div class="row form-group inputDate">
                                        <div class="col-4">
                                            <input type="text" class="form-control inputDateField required-element numericInput" id="diaFechaNacimientoTitular" name="diaFechaNacimientoTitular" placeholder="Día" max="2">
                                        </div>
                                        <div class="col-4">
                                            <input type="text" class="form-control inputDateField required-element numericInput" id="mesFechaNacimientoTitular" name="mesFechaNacimientoTitular" placeholder="Mes" max="2">
                                        </div>
                                        <div class="col-4">
                                            <input type="text" class="form-control inputDateField required-element numericInput" id="anioFechaNacimientoTitular" name="anioFechaNacimientoTitular" placeholder="Año" max="4">
                                        </div>
                                        <label id="labelFecha" class="errorMsg ml-3">Formato de la fecha no es el correcto.</label>
                                        <span class="input-caption col-12 mt-2">Recuerda que el titular debe ser mayor de edad</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Teléfono Móvil -->
                            <div class="row mt-2">
                                <div class="form-group col-md-6 col-12">
                                    <label for="numeroTelefonoTitular">Teléfono de contacto*</label>
                                    <input type="number" class="form-control numericInput" id="numeroTelefonoTitular" name="numeroTelefonoTitular" placeholder="Teléfono de contacto*" max="9">
                                    <span id="labelMovil" class="errorMsg">Introduce un número móvil válido</span>
                                </div>
                            </div>
                            <!-- Email -->
                            <div class="row mt-4">
                                <div class="form-group col-md-6 col-12">
                                    <label for="emailTitular">E-mail de contacto*</label>
                                    <input type="email" class="form-control lowercaseField" id="emailTitular" name="emailTitular" placeholder="e-mail de contacto*">
                                    <label id="labelEmail" class="errorMsg">Revisa que el email sea correcto.</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-12">
                                    <!-- Disable copypaste -->
                                    <input type="email" class="form-control" id="emailTitularRepetido" name="emailTitularRepetido" placeholder="Repite e-mail">
                                    <label id="labelEmailRep" class="errorMsg">El formato no es válido o no coinciden.</label>
                                </div>
                            </div>
                            <!-- CheckBox Privacidad -->
                            <div>
                                <div class="form-group mt-4">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkoutConditions">
                                        <label class="custom-control-label" for="checkoutConditions">He leído y acepto la <a href="https://netvoz.eu/politica-de-privacidad/">Política de Privacidad.</a></label>
                                    </div>
                                </div>
                                <!-- Botón Continuar -->
                                <div class="col-12">
                                    <div class="row  justify-content-between">
                                        <button type="button" id="btnContinuarConfiguracion" class="form-group btn btn-primary btn-sm col-md-3 col-12" disabled="">Continuar</button>
                                    </div>
                                    <div class="row">
                                        <button type="button" id="btnAtrasCobertura" class="form-group btn btn-secondary text-left">Volver al paso anterior</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tab Configurar -->
                        <div class="tab-pane" id="tabConfigurar" role="tabpanel" aria-labelledby="configurar-tab">
                            <h2>Configurar</h2>
                            <div class="form_module hidden mt-3" style="display: block;">
                                <h4>Tu fijo principal </h4>
                                <!-- Conservar Móvil Activar -->
                                <div class="row row-custom-radios">
                                    <div class="col-md-12">
                                        <label>¿Quieres conservar tu número fijo actual en Netvoz?</label>
                                    </div>
                                    <div class="custom-control custom-radio col-12 ml-4">
                                        <input type="radio" id="btnNumeroNuevo" name="botonAlta" class="custom-control-input">
                                        <label class="custom-control-label" for="btnNumeroNuevo">No. Quiero un número nuevo. </label>
                                    </div>
                                    <div class="custom-control custom-radio col-12 ml-4">
                                        <input type="radio" id="btnNumeroAntiguo" name="botonPortabilidad" class="custom-control-input">
                                        <label class="custom-control-label" for="btnNumeroAntiguo">Sí. Quiero conservar mi número actual.</label>
                                    </div>
                                </div>
                                <div class="textIndentProduct mt-3">
                                    <div id="mobilePortinPhoneNumber" class="hidden" style="display: block;">
                                        <div style="display:none"></div>
                                        <div class="row">
                                            <!-- Número de Móvil -->
                                            <div class="oculto form-group col-12 col-md-6" id="divMovil">
                                                <label for="numeroTelefonoNuevo">Introduce el número fijo que quieres traer a Netvoz</label>
                                                <input type="number" class="form-control required-element numericInput" id="numeroTelefonoNuevo" name="numeroTelefonoNuevo" placeholder="" max="9" required>
                                                <label id="labelMovilNuevo" class="errorMsg oculto">Revisa que el número sea correcto</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="modalityButtons" class="container">
                                        <!-- Modalidad -->
                                        <div class="row">
                                            <div class="oculto row form-group" id="divModalidad">
                                                <div class="row ml-3">
                                                    <label>Elige tu modalidad actual</label>
                                                </div>
                                                <div class="custom-radio-button col-6 mt-2">
                                                    <input type="button" class="btn mt-2" name="contrato" id="btnModalidadContrato" value="Contrato" style="width: 300px; background-color:lightskyblue; font-weight: bold;">
                                                </div>
                                                <div class="custom-radio-button col-6">
                                                    <input type="button" class="btn mt-3" name="prepago" id="btnModalidadPrepago" value="Prepago" style="width: 300px; background-color:lightskyblue; font-weight: bold;">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="iccInformation" class="row">
                                            <!-- ICC -->
                                            <div class="row oculto" id="divIcc">
                                                <div class="form-group col-12">
                                                    <label for="current_icc">ICC</label>
                                                    <input type="text" class="form-control required-element numericInput" name="numeroICCNuevo" id="current_icc" placeholder="" maxlength="19" required>
                                                    <div class="invalid-feedback">Revisa que el ICC sea correcto.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Titular -->
                                    <div id="divSelectTitular" class="oculto mt-2">
                                        <div class="row row-custom-radios">
                                            <div class="col-md-12">
                                                <label>¿Eres el titular de la línea que quieres portar?</label>
                                            </div>
                                            <div class="custom-control custom-radio col-12 ml-4">
                                                <input type="radio" id="comprobarTitularSi" name="comprobarTitular" class="custom-control-input">
                                                <label class="custom-control-label" for="comprobarTitularSi">Sí, soy el titular </label>
                                            </div>
                                            <div class="custom-control custom-radio col-12 ml-4">
                                                <input type="radio" id="comprobarTitularNo" name="comprobarTitular" class="custom-control-input">
                                                <label class="custom-control-label" for="comprobarTitularNo">No soy el titular</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- SMS Validation -->
                                    <div id="smsValidation" class="oculto mt-2">
                                        <div class="row row-custom-radios">
                                            <div class="col-md-12 mt-2">
                                                <label>Te vamos a mandar un SMS para comprobar que eres el titular</label>
                                            </div>
                                            <div class="col-3 mt-2">
                                                <input type="text" class="mt-2" id="inputCodigoSms">
                                            </div>
                                            <div class="col">
                                                <input type="button" class="btn mt-2" id="btnCheckCodigoSms" value="Comprobar Código" style="margin-left:0px; background-color:lightskyblue; font-weight: bold;" disabled="true">
                                            </div>
                                        </div>
                                        <div class="row ml-0 mt-2">
                                            <small>¿No has recibido el SMS? <a href="javascript:titularSi();">Enviar de nuevo.</a></small>
                                        </div>
                                    </div>
                                    <label id="labelSmsValidado" class="okMsg mt-2">Número de teléfono validado</label>
                                    <label id="labelSmsNoValidado" class="errorMsg mt-2">Código Incorrecto</label>
                                    <!-- Boton Continuar -->
                                    <div class="row footerForm mt-5 ml-2">
                                        <div class="container-full ml-2">
                                            <div class="row">
                                                <button type="button" id="btnContinuarPagos" class="form-group btn btn-primary" disabled="">Continuar</button>
                                            </div>
                                            <div class="row">
                                                <button type="button" id="btnAtrasDatos" class="form-group btn btn-secondary text-left">Volver al paso anterior</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Tab Pagos y Envíos -->
                        <div class="tab-pane" id="tabPaga" role="tabpanel" aria-labelledby="pago-tab">
                            <h2>Pago y envío</h2>

                            <h4>Cuenta bancaria</h4>
                            <div class="row col-12 accountOwnerField">
                                <div class="row form-group col-12 accountOwnerField">
                                    <label for="accountOwner">Nombre del titular de la cuenta</label>
                                    <input type="text" class="form-control" id="accountOwner" name="titularCuentaBancaria" placeholder="">
                                </div>
                            </div>
                            <!-- Datos Cuenta Bancaria -->
                            <div class="row col-12">
                                <div class="form-group col-md-2 col-6 pl-0">
                                    <label for="accountCode">Cód. ESXX</label>
                                    <input type="text" class="form-control" id="accountCode" name="codigoCuentaBancaria" maxlength="4" placeholder="ES _ _" style="text-transform: uppercase">
                                </div>
                                <div class="form-group col-md-2 col-6">
                                    <label for="accountEntity">Entidad</label>
                                    <input type="text" class="form-control numericInput" id="accountEntity" name="entidadCuentaBancaria" maxlength="4" format-validation="accountNumber" target-office-id="accountOffice" target-dc-id="numCuenta1" target-account-id="numCuenta2" target-code-id="accountCode">
                                </div>
                                <div class="form-group col-md-2 col-6">
                                    <label for="accountOffice">Oficina</label>
                                    <input type="text" class="form-control numericInput" id="accountOffice" name="oficinaCuentaBancaria" maxlength="4">
                                </div>
                                <div class="form-group col-md-2 col-6">
                                    <label for="numCuenta1">Cuenta</label>
                                    <input type="text" class="form-control numericInput" id="numCuenta1" name="numCuenta1" maxlength="4">
                                </div>
                                <div class="form-group col-md-2">
                                    <input type="text" class="form-control numericInput" id="numCuenta2" name="numCuenta2" maxlength="4" style="width: 95%; margin-top: 24%;">
                                </div>
                                <div class="form-group col-md-2">
                                    <input type="text" class="form-control numericInput" id="numCuenta3" name="numCuenta2" maxlength="4" style="width: 95%; margin-top: 24%;">
                                </div>
                                <label id="labelIban" class="errorMsg oculto">Nº de IBAN incorrecto, comprueba los datos</label>
                            </div>
                            <hr />
                            <h4 id="hTarjeta">Tarjeta de crédito </h4>
                            <div id="panelTarjeta" class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="numTarjeta">Nº Tarjeta</label>
                                            <input type="number" class="form-control" id="numTarjeta" name="numTarjeta" maxlength="16">
                                            <label id="labelTarjeta" class="errorMsg">Nº de Tarjeta incorrecto, comprueba los datos</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Caducidad</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-2">
                                            <label for="caducidad1">Mes</label>
                                            <div class="select_container">
                                                <select id="caducidad1" class="form-control" placeholder="mm">
                                                    <option value="01">01</option>
                                                    <option value="02">02</option>
                                                    <option value="03">03</option>
                                                    <option value="04">04</option>
                                                    <option value="05">05</option>
                                                    <option value="06">06</option>
                                                    <option value="07">07</option>
                                                    <option value="08">08</option>
                                                    <option value="09">09</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <div class="select_container">
                                                <label for="caducidad1">Año</label>
                                                <select id="caducidad2" class="form-control" placeholder="aa">
                                                    <option value="20">2020</option>
                                                    <option value="21">2021</option>
                                                    <option value="22">2022</option>
                                                    <option value="23">2023</option>
                                                    <option value="24">2024</option>
                                                    <option value="25">2025</option>
                                                    <option value="26">2026</option>
                                                    <option value="27">2027</option>
                                                    <option value="28">2028</option>
                                                    <option value="29">2029</option>
                                                    <option value="30">2030</option>
                                                    <option value="31">2031</option>
                                                    <option value="32">2032</option>
                                                    <option value="33">2033</option>
                                                    <option value="34">2034</option>
                                                    <option value="35">2035</option>
                                                    <option value="36">2036</option>
                                                    <option value="37">2037</option>
                                                    <option value="38">2038</option>
                                                    <option value="39">2039</option>
                                                    <option value="40">2040</option>
                                                    <option value="42">2042</option>
                                                    <option value="43">2043</option>
                                                    <option value="44">2044</option>
                                                    <option value="45">2045</option>
                                                    <option value="46">2046</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label id="labelFech" class="errorMsg" style="margin-top: 1%; margin-left: 1%;">Fecha de caducidad no válida, comprueba los datos</label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-2">
                                            <label for="numCcv">CCV/CVC</label>
                                            <input type="number" class="form-control numericInput" id="numCcv" name="numCcv" maxlength="3" minlength="3" placeholder="ccv">
                                        </div>
                                        <input id="fechaCaducidad" name="fechaCaducidad" type="text" class="oculto" id="fechaCaducidad" value="">
                                    </div>
                                </div>
                            </div>
                            <hr/>
                            <div class="form_module mt-4">
                                <!-- Dirección facturación y comunicación -->
                                <div class="form_module gradient_shadow mt-3">
                                    <h3>Dirección de facturación y comunicación </h3>
                                    <label class="mt-2">Esta es la dirección que utilizaremos cuando necesitemos contactar contigo.</label>
                                    <div class="row">
                                        <div class="row form-group col-12">
                                            <!-- Direccion Instalacion? -->
                                            <div class="custom-radio-button col-md-4 hidden" style="display: none;">
                                                <input type="radio" name="billing_address" id="internet_billing_address">
                                                <label class="custom-radio-label" for="internet_billing_address">Dirección de instalación</label>
                                            </div>
                                            <div class="custom-radio-button col-md-4 hidden ml-3 mt-3" style="display: block;">
                                                <input type="button" class="btn w-100" style="background-color: lightskyblue; font-weight: bold;" id="btnDireccionSim" value="Dirección de Instalación">
                                            </div>
                                            <div class="custom-radio-button col-md-4 mt-3">
                                                <input type="button" class="btn w-75" style="background-color: lightskyblue; font-weight: bold;" id="btnDireccionNueva" value="Nueva Dirección">
                                            </div>
                                            <label class="oculto col-12 mt-4 ml-3" id="labelInfoDireccion">Misma dirección que la fibra</label>
                                            <div class="col-12">
                                                <!-- Nueva Dirección de Facturación -->
                                                <div id="divDatosDireccionFacturacion" class="oculto ml-3 mt-3">
                                                    <div class="row">
                                                        <div class="form-group col-md-4">
                                                            <label>CP*</label>
                                                            <input type="text" class="form-control numericInput" maxlength="5" id="codigoPostalDireccionFacturacion" name="codigoPostalDireccionFacturacion">
                                                            <div class="form-group col-md-12">
                                                                <div class="invalid-feedback">Introduce un código postal de 5 dígitos.</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-8 disabled">
                                                            <label>Nombre de calle*</label>
                                                            <input type="text" class="initial_inputs form-control col-md-12" disabled="disabled" id="nombreCalleDireccionFacturacion" name="nombreCalleDireccionFacturacion">
                                                            <div class="invalid-feedback">Escribe el nombre de la calle sin tipo de vía.</div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-8 disabled">
                                                            <label>Municipio</label>
                                                            <input type="text" class="initial_inputs form-control col-md-12" disabled="disabled" id="municipioDF" name="municipioDF">
                                                            <div class="invalid-feedback">Escribe el nombre del municipio</div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-8 disabled">
                                                            <label>Provincia</label>
                                                            <input type="text" class="initial_inputs form-control col-md-12" disabled="disabled" id="provinciaDF" name="provinciaDF">
                                                            <div class="invalid-feedback">Escribe el nombre de la calle sin tipo de vía.</div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-8">
                                                            <div class="row">
                                                                <div class="form-group col-md-4">
                                                                    <label>Número*</label>
                                                                    <input type="text" class="numericAndSNinput initial_inputs form-control" id="numeroCalleDireccionFacturacion" name="numeroCalleDireccionFacturacion" disabled="disabled">
                                                                    <div class="mvne_street_form_errors form-group col-md-12">
                                                                        <div class="invalid-feedback">Revisa que el número sea correcto</div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-8">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox" class="custom-control-input" disabled="disabled" id="checkSinNumeroDireccionFacturacion">
                                                                        <label class="custom-control-label" for="checkSinNumeroDireccionFacturacion">Sin número</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group align-self-end">
                                                                <input type="button" id="btnValidarDireccionFacturacion" class="btn btn-success w-25" disabled="" value="Validar">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Info Extra Dirección Facturación -->
                                                <div id="divCompletarDireccionFacturacion" class="oculto ml-3 mt-4">
                                                    <div id="divBotonElegirDireccionFacturacion">
                                                        <button type="button" id="btnElegirOtraDireccionFacturacion" class="form-group btn btn-secondary btn-sm text-left">Elegir otra dirección</button>
                                                    </div>
                                                    <p>Completa tu dirección, solo si es necesario</p>
                                                    <div class="row">
                                                        <div class="form-group col-md-3 col-12">
                                                            <label>Planta</label>
                                                            <input type="text" id="plantaCalleDireccionFacturacion" name="plantaCalleDireccionFacturacion" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label>Puerta</label>
                                                            <input type="text" id="puertaCalleDireccionFacturacion" name="puertaCalleDireccionFacturacion" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-3 col-12">
                                                            <label>Bloque</label>
                                                            <input type="text" class="form-control" id="bloqueCalleDireccionFacturacion" name="bloqueCalleDireccionFacturacion">
                                                        </div>
                                                        <div class="form-group col-md-3 col-12">
                                                            <label>Escalera</label>
                                                            <input type="text" class="form-control" id="escaleraCalleDireccionFacturacion" name="escaleraCalleDireccionFacturacion">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <!-- Política Privacidad -->
                                <div class="row footerForm mt-4">
                                    <div class="form-group">
                                        <div class="ml-3">
                                            <input type="checkbox" id="btnCheckPrestacion">
                                            <label for="btnCheckPrestacion">He leído y acepto las
                                                <a href="https://netvoz.eu/aviso-legal/">Condiciones de Prestación de los Servicios</a>
                                                y la
                                                <a href="https://netvoz.eu/politica-de-privacidad/">política de privacidad.</a></label>
                                        </div>
                                    </div>
                                    <!-- Boton Confirmar Compra -->
                                    <div class="col-12 ml-2">
                                        <div class="container-full mt-3 ml-2">
                                            <div class="row">
                                                <button type="button" id="btnConfirmarCompra" class="form-group btn btn-success w-25" style="height: 60px; font-weight: bold;" disabled="">Confirmar Compra</button>
                                            </div>
                                            <div class="row">
                                                <button type="button" id="btnAtrasConfigurar" class="form-group btn btn-secondary text-left">Volver al paso anterior</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="text" id="idEmpresa" name="idEmp" value="<?= $idEmp ?>" style="display: none;">
                    <input type="text" id="idTarifa" name="idTarifa" value="<?= $idTarifa ?>" style="display: none;">
                    <input type="text" id="nombreTarifa" name="nombreTarifa" value="<?= $nombre ?>" style="display: none;">
                    <input type="text" id="precioTarifa" name="precioTarifa" value="<?= $precio ?>" style="display: none;">
                    <input type="text" name="plataforma" value="FTTH" style="display: none;">
                </form>
            </div>
            <!-- Cart View -->
            <div class="col-4 mt-5" id="cart_responsive" style="background: #f9f9f9; float: right;">
                <div id="cart_view" class="container_blue mt-2" style="display: block;">
                    <div class="container mt-4">
                        <h2 class="selectionColumn">Tu pedido</h2>
                        <hr />
                        <div class="container_blue_secundary">
                            <div class="container pl-0 pr-0">
                                <div id="currentPackCart" class="row cartElement hidden" style="display: block;">
                                    <div class="col-12">
                                        <h4 class="cart_element_title">Tarifa</h4>
                                        <?php if ($idEmp != "") { ?>
                                            <select id="selectTarifa" class="selectpicker w-75" title="Selecciona una tarifa" data-live-search="true"></select>
                                            </br></br>
                                        <?php } ?>
                                        <span id="spanNombreTarifa" class="cart_element_msg packSelectedData mt-3"><?= $nombre ?></span>
                                    </div>
                                </div>
                                <div id="divInfoInstalacion" class="row cartElement hidden" style="display: none;">
                                    <div class="col-12 ml-3">
                                        <hr />
                                        <div class="row">
                                            <h4 class="cart_element_title">Dirección de instalación</h4>
                                        </div>
                                        <div class="row">
                                            <span id="direccionInstalacion" class="oculto"></span>
                                        </div>
                                    </div>
                                    <span id="editInstalationAddress" class="editButtonCart" data-step="0"></span>
                                </div>
                                <div id="divInfoTitular" class="row cartElement hidden" style="display: none;">
                                    <div class="col-12 ml-3">
                                        <hr />
                                        <div class="row">
                                            <h4 class="cart_element_title">Titular</h4>
                                        </div>
                                        <div class="row">
                                            <span id="nombreTitularColumna" class="oculto"></span>
                                        </div>
                                        <div class="row">
                                            <span id="razonSocialTitularColumna" class="oculto"></span>
                                        </div>
                                        <div class="row">
                                            <span id="dniTitularColumna" class="oculto"></span>
                                        </div>
                                        <div class="row">
                                            <span id="fechaNacimientoTitularColumna" class="oculto"></span>
                                        </div>
                                        <div class="row">
                                            <span id="emailTitularColumna" class="oculto"></span>
                                        </div>
                                    </div>
                                    <span id="editPersonalInfo" class="editButtonCart" data-step="1" style="display: none;"></span>
                                    <hr />
                                </div>
                                <div id="divDatosPortabilidad" class="row oculto">
                                    <div class="col-12">
                                        <hr />
                                        <h4>Datos de portabilidad</h4>
                                        <span id="labelMovilPortabilidad" class="oculto"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="cart_footer" class="container cart_footer hidden" style="display: block;">
                        <hr />
                        <div class="row">
                            <div class="col-7">
                                <h4 class="monthly_fee_label">Precio total</h4>
                                <span>(Impuestos no incluidos)</span>
                            </div>
                        </div>
                        <div class="row mt-3 ml-0">
                            <h2 id="spanPrecio" class="price_rate"><?= $precio ?>€</h2>
                        </div>
                        <hr />
                    </div>
                </div>
            </div>
        </div>
        <div id="formFinalText" class="row mt-5" style="justify-content: center; display: none;">
            <h3>El formulario se ha enviado correctamente</h3>
        </div>
        <div id="formFinalLink" class="row mt-3" style="justify-content: center; display: none;">
            <a href="https://netvoz.eu" class="btn" style="background-color:#c36; font-weight: bold;">Volver a Netvoz</a>
        </div>
    </div>
    <script type="text/javascript" src="solicitudesWeb/js/ftth.js"></script>
</body>

</html>