<?php
error_log("HOLA KE ASE");
if (!isset($_SESSION)) {
    @session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/util.php');

check_session(10);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel de Incidencias</title>
    <!-- CORE CSS -->
    <meta charset="utf-8" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="description" content="" />
    <meta name="Author" content="<?php echo AUTOR; ?>" />
    <!--
    <meta http-equiv="refresh" content="900">
    -->

    <!-- mobile settings -->
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />

    <!-- WEB FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&amp;subset=latin,latin-ext,cyrillic,cyrillic-ext" rel="stylesheet" type="text/css" />

    <!-- CORE CSS -->
    <link href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/estilos.css" rel="stylesheet" type="text/css" />

    <!-- THEME CSS -->
    <link href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/assets/css/essentials.css" rel="stylesheet" type="text/css" />
    <link href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/assets/css/layout.css" rel="stylesheet" type="text/css" />
    <link href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/assets/css/color_scheme/green.css" rel="stylesheet" type="text/css" id="color_scheme" />

    <!-- JQGRID TABLE -->
    <link href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/assets/plugins/jqgrid/css/ui.jqgrid.css" rel="stylesheet" type="text/css" />
    <link href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/assets/css/layout-jqgrid.css" rel="stylesheet" type="text/css" />

    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/themes/ui-lightness/jquery-ui.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />
</head>

<body>
    <div id="wrapper" class="wrapper">

        <aside id="aside" style="position:fixed;left:0">
            <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/menu-izquierdo.php'); ?>
            <span id="asidebg">
                <!-- aside fixed background --></span>
        </aside>
        <!-- /ASIDE -->

        <!-- HEADER -->
        <header id="header">
            <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/menu-superior.php'); ?>
        </header>
        <!-- /HEADER -->
        <section id="middle">
            <!-- page title -->
            <header id="page-header">
                <h1>Usted esta en</h1>
                <ol class="breadcrumb">
                    <li><a href="#">Call Center</a></li>
                    <li class="active">Panel de Incidencias</li>
                </ol>
            </header>
            <!-- /page title -->

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body" id="listado">
                            <div id="panel-1" class="panel panel-default">
                                <div class="panel-heading">
                                    <span class="title elipsis">
                                        <strong>PANEL DE INCIDENCIAS</strong>
                                    </span>
                                </div>

                                <!-- panel content -->
                                <div class="panel-body">
                                    <div class="row">
                                        <!--
                                        <div class="col-md-1" style="margin-right: 0px;">
                                            <label>Fecha Inicio:</label>
                                            <input type="date" id="fechaInicio" value="2018-01-01">
                                        </div>
                                        <div class="col-md-1" style="margin-left: 20px;">
                                            <label>Fecha Fin:</label>
                                            <input type="date" id="fechaFin" value="<?php echo date('Y-m-d') ?>">
                                        </div>
                                        -->
                                        <div class="col-md-1" style="margin-left: 20px;">
                                            <label>DNI Cliente:</label>
                                            <input type="text" id="dniCliente">
                                        </div>
                                        <div class="col-md-1" style="margin-left: 1%;">
                                            <button class="btn btn-success" id="btnExec" style="margin-top: 8%">MOSTRAR</button>
                                        </div>
                                    </div>
                                    <!--
                                    <div class="row" style="margin-top: 2%;">
                                        <div class="col-md-1">
                                            <label>Reseller:</label>
                                            <select id="comboBoxResellers" class="selectpicker" title="Selecciona un Reseller" data-live-search="true"></select>
                                        </div>
                                        <div class="col-md-1" style="margin-left: 4%;">
                                            <button id="btnClearReseller" style="margin-left: 0px;"><i class="fa fa-times-circle"></i></button>
                                        </div>
                                        <div class="col-md-1">
                                            <label>Filtrar por:</label>
                                            <select id="comboBoxFiltro" class="selectpicker" title="Filtrar por">
                                                <option value="1">Fecha Factura</option>
                                                <option value="2">Fecha Impago</option>
                                            </select>
                                        </div>
                                        <div class="col-xs-1" style="margin-left: 4%; margin-right: 0px;"><button id="btnClearFiltro" style="margin-left: 0px;"><i class="fa fa-times-circle"></i></button></div>                                        
                                    </div>
                                    -->
                                    <hr />
                                    <div class="row" style="margin-top: 2%; margin-left: 1%; display: none;" id="panelTabla">
                                        <h3>Info Cliente</h3>
                                        <table id="dt_cliente" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>NOMBRE</th>
                                                    <th>DNI</th>
                                                    <th>DIRECCION</th>
                                                    <th>RESELLER/DISTRIBUIDOR</th>
                                                    <th>EMAIL</th>
                                                    <th>FIJO</th>
                                                    <th>MOVIL</th>
                                                    <th>FECHA</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                        <h3>Líneas Activas</h3>
                                        <table id="lineasActivas" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th width="10%">MÓVIL</th>
                                                    <th width="10%">DNI CLIENTE</th>
                                                    <th width="20%">NOMBRE CLIENTE</th>
                                                    <th width="20%">DIRECCION</th>
                                                    <th width="20%">SERVICIO</th>
                                                    <th width="20%">RESELLER/DISTRIBUIDOR</th>
                                                    <th width="10%">FECHA ALTA</th>
                                                    <th width="10%">FECHA BAJA</th>
                                                    <th width="10%">ESTADO</th>
                                                    <th width="10%">PUK</th>
                                                    <th width="10%">Min mes</th>
                                                    <th width="10%">MB mes</th>
                                                    <th width="10%">SMS mes</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <h2 id="labelDevoluciones" style="color: red; display: none;">El Cliente pertenece a un Reseller con devoluciones. No podemos continuar</h2>
                                    <div class="row" style="margin-top: 2%; margin-left: 1%; display: none;" id="panelDevoluciones">
                                        <h3>Devoluciones</h3>
                                        <table id="devoluciones" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>FECHA IMPAGO</th>
                                                    <th>DNI</th>
                                                    <th>NOMBRE</th>
                                                    <th>TELEFONO</th>
                                                    <th>RESELLER/DISTRIBUIDOR</th>
                                                    <th>TARIFA</th>
                                                    <th>TOTAL</th>
                                                    <th>CONCEPTO</th>
                                                    <th>DESCRIPCION</th>
                                                    <th>ACCIÓN</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                        <h4>Información Extra (Opcional)</h4>
                                        <div class="col-md-10">
                                            <textarea id="infoExtra1" style="margin-top: 0.5%;" rows="10" cols="220"></textarea>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="row">
                                                <label for="selectPrioridad1">Prioridad:</label>
                                                <select id="selectPrioridad1">
                                                    <option value="2">Normal</option>
                                                    <option value="1">Baja</option>
                                                    <option value="3">Alta</option>
                                                    <option value="4">Emergencia</option>
                                                </select>
                                            </div>
                                            <div class="row">
                                                <button class="btn btn-info btn-block" id="btnTicket1" style="margin-top: 1%;">ENVIAR TÍCKET</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body ocultar" id="panelTicket" style="margin-left: 1%;">
                                    <h3 style="color: red;">Si el cliente no tiene devoluciones, se crea un ticket.</h3>
                                    <?php if ($_SESSION['PLATAFORMA'] == "FTTH") { ?>
                                        <div class="row">
                                            <div class="col-md-1">
                                                <input type="button" class="btn btn-info btn-block" style="background-color: #3366ff; border-color: #3366ff;" value="Incidencia" onclick="mostrarTicket('Incidencia')">
                                            </div>
                                            <div class="col-md-1">
                                                <input type="button" class="btn btn-info btn-block" style="background-color: #ff8c00; border-color: #ff8c00;" value="Avería" onclick="mostrarTicket('Avería')">
                                            </div>
                                            <div class="col-md-1">
                                                <input type="button" class="btn btn-danger btn-block" value="Baja" onclick="mostrarTicket('Baja')">
                                            </div>
                                            <div class="col-md-1">
                                                <input type="button" class="btn btn-info btn-block" style="background-color: grey; border-color: grey;" value="Otros" onclick="mostrarTicket('Otros')">
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="row">
                                            <div class="col-md-1" id="btnConsulta">
                                                <input type="button" class="btn btn-info btn-block" style="background-color: #3366ff; border-color: #3366ff;" value="Consulta" onclick="mostrarTicket('Consulta')">
                                            </div>
                                            <div class="col-md-1" id="btnBaja">
                                                <input type="button" class="btn btn-danger btn-block" value="Baja" onclick="mostrarTicket('Baja')">
                                            </div>
                                            <div class="col-md-1" id="btnBonos">
                                                <input type="button" class="btn btn-success btn-block" value="Activar Bonos" onclick="mostrarTicket('Activar Bonos')">
                                            </div>
                                            <div class="col-md-1" id="btnGestion">
                                                <input type="button" class="btn btn-warning btn-block" value="Gestión de Líneas" onclick="mostrarTicket('Gestión de Líneas')">
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="row ocultar" id="tablaSolicitudes" style="margin-top: 4%;margin-left: 0%;">
                                        <h3>Incidencias Solicitudes</h3>
                                        <table id="solicitudes" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ESTADO</th>
                                                    <th>DIRECCION</th>
                                                    <th>NOMBRE</th>
                                                    <th>DNI</th>
                                                    <th>EMAIL</th>
                                                    <th>TELEFONO</th>
                                                    <th>OBSERVACIONES</th>
                                                    <th>ESTADO INC</th>
                                                    <th>MOTIVO INC</th>
                                                    <th>DESCRIPCION</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <div class="row ocultar" id="panelEnviarTicket" style="margin-left: 1%; margin-top: 2%;">
                                        <div class="col-md-5">
                                            <h4>Guión de Preguntas</h4>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <select id="comboBoxPreguntas" class="selectpicker" title="Selecciona una pregunta" data-live-search="true"></select>
                                                </div>
                                                <div class="col-md-6" >
                                                    <a id="documentosAdicionales" href="#" target="_blank"><i class="fa fa-download"></i></a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <textarea id="infoRespuesta" style="margin-top: 0.5%;" rows="20" cols="100"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6" style="margin-top: 1%;">
                                            <h4>Información Extra (Opcional)</h4>
                                            <div class="col-md-10">
                                                <textarea id="infoExtra" style="margin-top: 2%;" rows="10" cols="100"></textarea>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <label for="selectPrioridad">Prioridad:</label>
                                                    <select id="selectPrioridad">
                                                        <option value="2">Normal</option>
                                                        <option value="1">Baja</option>
                                                        <option value="3">Alta</option>
                                                        <option value="4">Emergencia</option>
                                                    </select>
                                                </div>
                                                <div class="row" style="margin-top: 0.5%;">
                                                    <input type="file" id="adjunto" name="file" />
                                                </div>
                                                <div class="row" style="margin-top: 0.5%;">
                                                    <button class="btn btn-info btn-block" id="btnTicket" style="margin-top: 1%;">ENVIAR TÍCKET</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div style="display: none;">
            <input id="plataforma" type="text" style="display: none;" value="<?php echo $_SESSION['PLATAFORMA'] ?>">
            <input id="userId" type="text" style="display: none;" value="<?php echo $_SESSION['USER_ID'] ?>">
            <input type="text" id="idFactura" value="">
            <input id="importe" type="text" value="">
            <input id="numTlf" type="text" value="">
        </div>
        <!-- FORM REDSYS https://sis-t.redsys.es:25443/sis/realizarPago -->
        <form name="frm" style="display: none;" action="https://sis.redsys.es/sis/realizarPago" method="POST" target="_blank">
            Ds_Merchant_SignatureVersion <input id="version" type="text" name="Ds_SignatureVersion" value="" /></br>
            Ds_Merchant_MerchantParameters <input id="params" type="text" name="Ds_MerchantParameters" value="" /></br>
            Ds_Merchant_Signature <input id="firma" type="text" name="Ds_Signature" value="" /></br>
            <input type="submit" value="Enviar">
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/listados/listadoDevoluciones/js/bootstrap.min.js"></script>
    <script src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/listados/listadoDevoluciones/js/jquery.dataTables.min.js"></script>
    <script src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/listados/listadoDevoluciones/js/dataTables.bootstrap.js"></script>
    <script src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/listados/listadoDevoluciones/js/dataTables.buttons.min.js"></script>
    <script src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/listados/listadoDevoluciones/js/buttons.bootstrap.min.js"></script>
    <script src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/listados/listadoDevoluciones/js/jszip.min.js"></script>
    <script src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/listados/listadoDevoluciones/js/pdfmake.min.js"></script>
    <script src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/listados/listadoDevoluciones/js/vfs_fonts.js"></script>
    <script src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/listados/listadoDevoluciones/js/buttons.html5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <script type="text/javascript" src="js/incidencias.js"></script>
</body>

</html>