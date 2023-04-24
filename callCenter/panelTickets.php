<?php

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
    <title>Panel de Tickets</title>
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
    <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/themes/ui-lightness/jquery-ui.css" rel="stylesheet" />
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
                    <li class="active">Panel de Tickets</li>
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
                                        <strong>PANEL DE TICKETS</strong>
                                    </span>
                                </div>

                                <!-- panel content -->
                                <div class="panel-body" id="panelTicket">
                                    <div class="row">
                                        <h3>Lista de Tickets</h3>
                                        <table id="dt_tickets" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>FECHA CIERRE</th>
                                                    <th>ASUNTO</th>
                                                    <th>CONTENIDO</th>
                                                    <th>NOMBRE</th>
                                                    <th>TLF</th>
                                                    <th>PRIORIDAD</th>
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
            </div>
            <!-- Cerrar Ticket HTML -->
            <div id="cerrarTicketModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="cerrarTicket">
                            <div class="modal-header">
                                <h4 class="modal-title">Eliminar Campaña</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <p>Estás seguro de cerrar este tícket?</p>
                                <p class="text-warning"><small>Esta acción no se puede revertir</small></p>
                                <div class="form-group" style="display: none;">
                                    <input type="text" class="form-control" name="action" value="cerrarTicket">
                                    <input id="numTicket" type="text" class="form-control" name="numTicket">
                                    <input type="text" class="form-control" name="plataforma" value="OSTICKET">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                                <input type="submit" class="btn btn-danger" value="Cerrar">
                            </div>
                        </form>
                    </div>
                </div>
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
                            <p>Ha habido un error en la consulta</p>
                        </div>
                        <div class="modal-footer">
                            <input type="button" class="btn btn-default" data-dismiss="modal" value="OK">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <input id="plataforma" type="text" style="display: none;" value="<?php echo $_SESSION['PLATAFORMA'] ?>">
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

    <script type="text/javascript" src="js/panelTickets.js"></script>
</body>

</html>