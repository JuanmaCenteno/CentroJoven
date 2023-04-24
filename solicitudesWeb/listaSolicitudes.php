<?php

if (!isset($_SESSION)) {
    @session_start();
}
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/util.php');
check_session(2);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>Lista de Solicitudes Web</title>
    <!-- CORE CSS -->
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

    <!-- THEME CSS -->
    <link href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/assets/css/essentials.css" rel="stylesheet" type="text/css" />
    <link href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/assets/css/layout.css" rel="stylesheet" type="text/css" />
    <link href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/assets/css/color_scheme/green.css" rel="stylesheet" type="text/css" id="color_scheme" />

    <!-- JQGRID TABLE -->
    <link href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/assets/plugins/jqgrid/css/ui.jqgrid.css" rel="stylesheet" type="text/css" />
    <link href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/assets/css/layout-jqgrid.css" rel="stylesheet" type="text/css" />

    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />

    <link href="<?php $_SERVER['DOCUMENT_ROOT']; ?>/solicitudesWeb/css/styles.css" rel="stylesheet" type="text/css" />
    <!-- Selectpicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
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
                    <li><a href="#">Lista de Solicitudes Web</a></li>
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
                                        <strong>LISTADO DE SOLICITUDES WEB</strong>
                                    </span>
                                </div>

                                <!-- panel content -->
                                <div class="panel-body">
                                    <table id="solicitudes" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID_TRANSPORTE</th>
                                                <th>FECHA</th>
                                                <th>ESTADO</th>
                                                <th>ESTADO_SIM</th>
                                                <th>ID_TARIFA</th>
                                                <th>NOMBRE_TARIFA</th>
                                                <th>PRECIO TARIFA</th>
                                                <th>NUM_DOCUMENTO</th>
                                                <th>RAZON_SOCIAL</th>
                                                <th>NOMBRE_TITULAR</th>
                                                <th>APELLIDOS_TITULAR</th>
                                                <th>FECHA_NACIMIENTO</th>
                                                <th>NUM_TLF_TITULAR</th>
                                                <th>EMAIL</th>
                                                <th>ALTA</th>
                                                <th>NUM_TLF_PORTABILIDAD</th>
                                                <th>NUM_ICC_PORTABILIDAD</th>
                                                <th>TITULAR_CUENTA_BANCARIA</th>
                                                <th>IBAN</th>
                                                <th>DIRECCION_ENVIO</th>
                                                <th>CODIGO_POSTAL_ENVIO</th>
                                                <th>MUNICIPIO_ENVIO</th>
                                                <th>PROVINCIA_ENVIO</th>
                                                <th>DIRECCION_FACTURACION</th>
                                                <th>CODIGO_POSTAL_FACTURACION</th>
                                                <th>MUNICIPIO_FACTURACION</th>
                                                <th>PROVINCIA_FACTURACION</th>
                                                <th>ACCIÓN</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Transporte ICC -->
            <div id="modalIcc" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="enviarSim">
                            <div class="modal-header">
                                <h4 class="modal-title">Enviar SIM</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>ICC</label>
                                    <input id="envioIcc" type="text" class="form-control" name="icc" required>
                                    <label id="labelIcc" class="errorMsg">El ICC introducido no es correcto.</label>
                                </div>
                                <div class="form-group">
                                    <label>Agencia de Envío</label>
                                    <select id="selectAgencia" class="selectpicker ml-4" title="Selecciona una agencia">
                                        <option value="2">Correos Express</option>
                                        <option value="122">GLS</option>
                                    </select>
                                </div>
                                <div class="form-group" style="display: none;">
                                    <input id="idTransporte" type="text" class="form-control" name="idTransporte">
                                    <input id="municipio" type="text" class="form-control" name="municipio">
                                    <input id="provincia" type="text" class="form-control" name="provincia">
                                    <input id="codPostal" type="text" class="form-control" name="codPostal">
                                    <input id="direccion" type="text" class="form-control" name="direccion">
                                    <input id="telefono" type="text" class="form-control" name="telefono">
                                    <input id="email" type="text" class="form-control" name="email">
                                    <input id="nombre" type="text" class="form-control" name="nombre">
                                    <input id="dni" type="text" class="form-control" name="dni">
                                    <input id="editPlat" type="text" class="form-control" name="plat" value="<?php echo $_SESSION['PLATAFORMA'] ?>">
                                    <input id="agencia" type="text" class="form-control" name="agencia">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                                <input id="btnEnviarSim" type="submit" class="btn btn-info" disabled value="Enviar">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <input type="text" id="plataforma" value="<?php echo $_SESSION['PLATAFORMA'] ?>" style="display: none;">
    </div>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/listados/listadoIncidencias/js/bootstrap.min.js"></script>
    <script src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/listados/listadoIncidencias/js/jquery.dataTables.min.js"></script>
    <script src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/listados/listadoIncidencias/js/dataTables.bootstrap.js"></script>
    <script src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/listados/listadoIncidencias/js/dataTables.buttons.min.js"></script>
    <script src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/listados/listadoIncidencias/js/buttons.bootstrap.min.js"></script>
    <script src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/listados/listadoIncidencias/js/jszip.min.js"></script>
    <script src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/listados/listadoIncidencias/js/pdfmake.min.js"></script>
    <script src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/listados/listadoIncidencias/js/vfs_fonts.js"></script>
    <script src="<?php $_SERVER['DOCUMENT_ROOT']; ?>/listados/listadoIncidencias/js/buttons.html5.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <script type="text/javascript" src="js/lista.js"></script>
</body>

</html>