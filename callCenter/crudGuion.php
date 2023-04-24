<?php

if (!isset($_SESSION)) {
    @session_start();
}

error_reporting(E_ALL);
ini_set("display_errors", 0);
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/util.php');

$util = new util();
check_session(3);
?>
<!doctype html>
<html lang="es-ES">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <!--  <title><?//php echo OWNER; ?> <?//php echo DEF_FACTURACION; ?> / Listado</title> -->
    <title>GUIÓN</title>
    <meta name="creareme" content="" />
    <meta name="Author" content="<?php echo AUTOR; ?>" />
    <!-- mobile settings -->
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />
    <!-- WEB FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&amp;subset=latin,latin-ext,cyrillic,cyrillic-ext" rel="stylesheet" type="text/css" />
    <!-- CORE CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- THEME CSS -->
    <link href="../../assets/css/essentials.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/css/layout.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/css/color_scheme/green.css" rel="stylesheet" type="text/css" id="color_scheme" />

    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />

    <!-- JQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Selectpicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <style>
        body {
            color: #566787;
            background: #f5f5f5;
            font-family: 'Varela Round', sans-serif;
            font-size: 13px;
        }

        .table-responsive {
            margin: 30px 0;
        }

        .table-wrapper {
            background: #fff;
            padding: 20px 25px;
            border-radius: 3px;
            min-width: 1000px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        }

        .table-title {
            padding-bottom: 15px;
            background: #435d7d;
            color: #fff;
            padding: 16px 30px;
            min-width: 100%;
            margin: -20px -25px 10px;
            border-radius: 3px 3px 0 0;
        }

        .table-title h2 {
            margin: 5px 0 0;
            font-size: 24px;
        }

        .table-title .btn-group {
            float: right;
        }

        .table-title .btn {
            color: #fff;
            float: right;
            font-size: 13px;
            border: none;
            min-width: 50px;
            border-radius: 2px;
            border: none;
            outline: none !important;
            margin-left: 10px;
        }

        .table-title .btn i {
            float: left;
            font-size: 21px;
            margin-right: 5px;
        }

        .table-title .btn span {
            float: left;
            margin-top: 2px;
        }

        table.table tr th,
        table.table tr td {
            border-color: #e9e9e9;
            padding: 12px 15px;
            vertical-align: middle;
        }

        table.table tr th:first-child {
            width: 60px;
        }

        table.table tr th:last-child {
            width: 100px;
        }

        table.table-striped tbody tr:nth-of-type(odd) {
            background-color: #fcfcfc;
        }

        table.table-striped.table-hover tbody tr:hover {
            background: #f5f5f5;
        }

        table.table th i {
            font-size: 13px;
            margin: 0 5px;
            cursor: pointer;
        }

        table.table td:last-child i {
            opacity: 0.9;
            font-size: 22px;
            margin: 0 5px;
        }

        table.table td a {
            font-weight: bold;
            color: #566787;
            display: inline-block;
            text-decoration: none;
            outline: none !important;
        }

        table.table td a:hover {
            color: #2196F3;
        }

        table.table td a.edit {
            color: #FFC107;
        }

        table.table td a.delete {
            color: #F44336;
        }

        table.table td i {
            font-size: 19px;
        }

        table.table .avatar {
            border-radius: 50%;
            vertical-align: middle;
            margin-right: 10px;
        }

        .pagination {
            float: right;
            margin: 0 0 5px;
        }

        .pagination li a {
            border: none;
            font-size: 13px;
            min-width: 30px;
            min-height: 30px;
            color: #999;
            margin: 0 2px;
            line-height: 30px;
            border-radius: 2px !important;
            text-align: center;
            padding: 0 6px;
        }

        .pagination li a:hover {
            color: #666;
        }

        .pagination li.active a,
        .pagination li.active a.page-link {
            background: #03A9F4;
        }

        .pagination li.active a:hover {
            background: #0397d6;
        }

        .pagination li.disabled i {
            color: #ccc;
        }

        .pagination li i {
            font-size: 16px;
            padding-top: 6px
        }

        .hint-text {
            float: left;
            margin-top: 10px;
            font-size: 13px;
        }

        /* Custom checkbox */
        .custom-checkbox {
            position: relative;
        }

        .custom-checkbox input[type="checkbox"] {
            opacity: 0;
            position: absolute;
            margin: 5px 0 0 3px;
            z-index: 9;
        }

        .custom-checkbox label:before {
            width: 18px;
            height: 18px;
        }

        .custom-checkbox label:before {
            content: '';
            margin-right: 10px;
            display: inline-block;
            vertical-align: text-top;
            background: white;
            border: 1px solid #bbb;
            border-radius: 2px;
            box-sizing: border-box;
            z-index: 2;
        }

        .custom-checkbox input[type="checkbox"]:checked+label:after {
            content: '';
            position: absolute;
            left: 6px;
            top: 3px;
            width: 6px;
            height: 11px;
            border: solid #000;
            border-width: 0 3px 3px 0;
            transform: inherit;
            z-index: 3;
            transform: rotateZ(45deg);
        }

        .custom-checkbox input[type="checkbox"]:checked+label:before {
            border-color: #03A9F4;
            background: #03A9F4;
        }

        .custom-checkbox input[type="checkbox"]:checked+label:after {
            border-color: #fff;
        }

        .custom-checkbox input[type="checkbox"]:disabled+label:before {
            color: #b8b8b8;
            cursor: auto;
            box-shadow: none;
            background: #ddd;
        }

        /* Modal styles */
        .modal .modal-dialog {
            max-width: 400px;
        }

        .modal .modal-header,
        .modal .modal-body,
        .modal .modal-footer {
            padding: 20px 30px;
        }

        .modal .modal-content {
            border-radius: 3px;
            font-size: 14px;
        }

        .modal .modal-footer {
            background: #ecf0f1;
            border-radius: 0 0 3px 3px;
        }

        .modal .modal-title {
            display: inline-block;
        }

        .modal .form-control {
            border-radius: 2px;
            box-shadow: none;
            border-color: #dddddd;
        }

        .modal textarea.form-control {
            resize: vertical;
        }

        .modal .btn {
            border-radius: 2px;
            min-width: 100px;
        }

        .modal form label {
            font-weight: normal;
        }
    </style>
</head>
<style>
    .table-responsive {
        width: 100%;
    }
</style>

<body>
    <!-- WRAPPER -->
    <div id="wrapper">
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
                    <li class="active">Guión</li>
                </ol>
            </header>
            <!-- /page title -->
            <div style="padding-left: 20px; padding-right: 20px;">
                <div class="table-responsive" >
                    <div class="table-wrapper">
                        <div class="table-title">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h2 style="color:white">Gestionar <b>Guión</b></h2>
                                </div>
                                <div class="col-sm-6">
                                    <a href="#addGuionModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Añadir Nueva Pregunta</span></a>
                                </div>
                            </div>
                        </div>
                        <table id="dt_guion" class="table table-striped table-hover" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>TIPO</th>
                                    <th>CASO</th>
                                    <th>RESPUESTA</th>
                                    <th>VISIBLE</th>
                                    <th>VIS RESELLER</th>
                                    <th>USUARIOS</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Add Modal HTML -->
            <div id="addGuionModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content" style="width: 650px;">
                        <form id="addGuion">
                            <div class="modal-header">
                                <h4 class="modal-title">Añadir Pregunta</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label>Tipo</label>
                                    </div>
                                    <div class="col-md-1">
                                        <label>Visible</label>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Visible Reseller</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <select id="addSelectTipo" class="selectpicker" title="Selecciona un Tipo" name="tipo" required>
                                            <?php if ($_SESSION['PLATAFORMA'] == "FTTH") { ?>
                                                <option value="1">Incidencia</option>
                                                <option value="2">Avería</option>
                                                <option value="3">Baja</option>
                                                <option value="4">Otros</option>
                                            <?php } else { ?>
                                                <option value="1">Consulta</option>
                                                <option value="2">Baja</option>
                                                <option value="3">Activar Bonos</option>
                                                <option value="4">Gestión de Líneas</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <input id="addVisible" type="checkbox" class="form-control" name="visible">
                                    </div>
                                    <div class="col-md-1">
                                        <input id="addVisibleReseller" type="checkbox" class="form-control" name="visibleReseller">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <label>Usuarios autorizados</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <select id="addSelectUsuarios" class="selectpicker" title="Selecciona un Usuario" name="usuarios[]" data-live-search="true" data-dropup-auto="false" multiple required></select>
                                    </div>
                                    <div class="col-md-2">
                                        <input id="btnAddSelectAll" class="btn btn-success btn-block" type="button" value="Todos">
                                    </div>
                                    <div class="col-md-2">
                                        <input id="btnAddSelectNone" class="btn btn-warning btn-block" type="button" value="Ninguno">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Caso</label>
                                    <input id="addCaso" type="text" class="form-control" name="caso" required>
                                </div>
                                <label>Respuesta</label>
                                <div class="form-group">
                                    <textarea id="addRespuesta" style="margin-top: 0.5%;" rows="15" cols="75" name="respuesta" required></textarea>
                                </div>
                                <div class="form-group" style="display: none;">
                                    <input type="text" class="form-control" name="action" value="add">
                                    <input type="text" class="form-control" name="plataforma" value="<?php echo $_SESSION['PLATAFORMA'] ?>">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                                <input type="submit" class="btn btn-success" value="Añadir">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Edit Modal HTML -->
            <div id="editGuionModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content" style="width: 650px;">
                        <form id="editGuion">
                            <div class="modal-header">
                                <h4 class="modal-title">Modificar Pregunta</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label>Tipo</label>
                                    </div>
                                    <div class="col-md-1">
                                        <label>Visible</label>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Visible Reseller</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <select id="editSelectTipo" title="Selecciona un Tipo" name="tipo" required>
                                            <?php if ($_SESSION['PLATAFORMA'] == "FTTH") { ?>
                                                <option value="1">Incidencia</option>
                                                <option value="2">Avería</option>
                                                <option value="3">Baja</option>
                                                <option value="4">Otros</option>
                                            <?php } else { ?>
                                                <option value="1">Consulta</option>
                                                <option value="2">Baja</option>
                                                <option value="3">Activar Bonos</option>
                                                <option value="4">Gestión de Líneas</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <input id="editVisible" type="checkbox" class="form-control" name="visible">
                                    </div>
                                    <div class="col-md-1">
                                        <input id="editVisibleReseller" type="checkbox" class="form-control" name="visibleReseller">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <label>Usuarios autorizados</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <select id="editSelectUsuarios" class="selectpicker" title="Selecciona un Usuario" name="usuarios[]" data-live-search="true" data-dropup-auto="false" multiple required></select>
                                    </div>
                                    <div class="col-md-2">
                                        <input id="btnEditSelectAll" class="btn btn-success btn-block" type="button" value="Todos">
                                    </div>
                                    <div class="col-md-2">
                                        <input id="btnEditSelectNone" class="btn btn-warning btn-block" type="button" value="Ninguno">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Caso</label>
                                    <input id="editCaso" type="text" class="form-control" name="caso" required>
                                </div>
                                <label>Respuesta</label>
                                <div class="form-group">
                                    <textarea id="editRespuesta" style="margin-top: 0.5%;" rows="15" cols="75" name="respuesta" required></textarea>
                                </div>
                                <div class="form-group" style="display: none;">
                                    <input id="editId" type="text" class="form-control" name="id" value="aa">
                                    <input type="text" class="form-control" name="action" value="update">
                                    <input type="text" class="form-control" name="plataforma" value="<?php echo $_SESSION['PLATAFORMA'] ?>">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                                <input type="submit" class="btn btn-info" value="Modificar">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Delete Modal HTML -->
            <div id="deleteGuionModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="deleteGuion">
                            <div class="modal-header">
                                <h4 class="modal-title">Eliminar Pregunta</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <p>Estás seguro de eliminar esta Pregunta?</p>
                                <p class="text-warning"><small>Esta acción no se puede revertir</small></p>
                                <div class="form-group" style="display: none;">
                                    <input id="deleteAction" type="text" class="form-control" name="action" value="delete">
                                    <input id="deleteId" type="text" class="form-control" name="id" value="">
                                    <input id="deletePlat" type="text" class="form-control" name="plataforma" value="<?php echo $_SESSION['PLATAFORMA'] ?>">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                                <input type="submit" class="btn btn-danger" value="Eliminar">
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
        <!-- /MIDDLE -->
    </div>


    <!-- JAVASCRIPT FILES -->
    <script type="text/javascript">
        var plugin_path = '../../assets/plugins/';
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.colVis.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- Selectpicker -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script>
        var arrayData = [];
        $(document).ready(function() {
            // Activate tooltip
            $('[data-toggle="tooltip"]').tooltip();
            var plataforma = "<?php echo $_SESSION['PLATAFORMA'] ?>";
            //var id = "<?php echo $_SESSION['REVENDEDOR'] ?>";

            // POST
            $('#addGuion').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData($('#addGuion')[0]);
                $.ajax({
                    url: 'formGuion.php',
                    type: 'POST',
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    enctype: 'multipart/form-data',
                    processData: false,
                    success: function() {
                        // MODAL OK
                        location.reload();
                    },
                    error: function(e) {
                        $('#modalErrorCif').modal('show');
                    }
                });
                return false;
            });

            $('#deleteGuion').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData($('#deleteGuion')[0]);
                $.ajax({
                    url: 'formGuion.php',
                    type: 'POST',
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    enctype: 'multipart/form-data',
                    processData: false,
                    success: function() {
                        // MODAL OK
                        location.reload();
                    },
                    error: function(e) {
                        //console.log("There was an error with your request...");
                        //console.log("error: " + JSON.stringify(e));
                        $('#modalErrorConsulta').modal('show');
                    }
                });
                return false;
            });

            $('#editGuion').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData($('#editGuion')[0]);
                $.ajax({
                    url: 'formGuion.php',
                    type: 'POST',
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    enctype: 'multipart/form-data',
                    processData: false,
                    success: function() {
                        // MODAL OK
                        location.reload();
                    },
                    error: function(e) {
                        $('#modalErrorConsulta').modal('show');
                    }
                });
                return false;
            });

            $('#dt_guion tbody').on('click', 'tr', function() {
                //console.log("TIPO: " + arrayData[1]);
                //loadUsuarios(0, plataforma);
                $('#editSelectUsuarios').selectpicker('val', -1);
                arrayData = $('#dt_guion').DataTable().row(this).data();
                $('#editId').prop('value', arrayData[0]);
                $("#editSelectTipo option").filter(function() {
                    return this.text == arrayData[1];
                }).attr('selected', true);
                $('#editCaso').prop('value', arrayData[2]);
                $('#editRespuesta').prop('value', arrayData[3]);
                if (arrayData[4] == "SI") {
                    $('#editVisible').prop('checked', true);
                } else {
                    $('#editRespuesta').prop('checked', false);
                }
                if (arrayData[5] == "SI") {
                    $('#editVisibleReseller').prop('checked', true);
                } else {
                    $('#editVisibleReseller').prop('checked', false);
                }
                arrayUsuarios = arrayData[6].substr(1, arrayData[6].length - 2);
                arrayUsuarios = arrayUsuarios.split(',');
                $('#editSelectUsuarios').selectpicker('val', arrayUsuarios);
                //console.log(arrayUsuarios);
                $('#deleteId').prop('value', arrayData[0]);
            });

            // SELECCIONAR TODOS USUARIOS
            $('#btnAddSelectAll').on('click', function() {
                loadUsuarios(1, plataforma);
            });
            $('#btnAddSelectNone').on('click', function() {
                loadUsuarios(plataforma);
                $('#addSelectUsuarios').selectpicker('val', -1);
                //deselectAll
            });
            $('#btnEditSelectAll').on('click', function() {
                loadUsuarios(1, plataforma);
            });
            $('#btnEditSelectNone').on('click', function() {
                loadUsuarios(plataforma);
                $('#editSelectUsuarios').selectpicker('val', -1);
            });

            loadUsuarios(0, plataforma);
            loadGuion(plataforma);
        });

        var loadGuion = function(plataforma) {
            $.ajax({
                type: 'GET',
                url: 'api_datos.php',
                contentType: "text/plain",
                dataType: 'json',
                data: {
                    accion: 'listaPreguntas',
                    plataforma: plataforma
                },
                success: function(data) {
                    myJsonData = data;
                    window.arrayGuion = myJsonData;
                    loadUsuariosGuion(plataforma);
                },
                error: function(e) {
                    //console.log("There was an error with your request...");
                    //console.log("error: " + JSON.stringify(e));
                }
            });
        };

        var loadUsuariosGuion = function(plataforma) {
            $.ajax({
                type: 'GET',
                url: 'api_datos.php',
                contentType: "text/plain",
                dataType: 'json',
                data: {
                    accion: 'listaUsuariosGuion',
                    plataforma: plataforma
                },
                success: function(data) {
                    myJsonData = data;
                    //console.log("USUARIOS");
                    //console.log(myJsonData);
                    var arrayFinal = addUsuarios(myJsonData);
                    $("#dt_guion").dataTable().fnDestroy();
                    definirDataTable();
                    populateDataTable(arrayFinal);
                },
                error: function(e) {
                    //console.log("There was an error with your request...");
                    //console.log("error: " + JSON.stringify(e));
                }
            });
        };

        var addUsuarios = function(data) {
            var resultado = window.arrayGuion;
            var length = Object.keys(resultado).length;
            var length1 = Object.keys(data).length;
            for (var i = 0; i < length; i++) {
                var res = resultado[i];
                var str = "(";
                for (var j = 0; j < length1; j++) {
                    var resu = data[j];
                    //console.log("RES ID:  " + res.id + "\tRES ID GUION: " + resu.ID_GUION);
                    if (res.id == resu.ID_GUION) {
                        str += resu.ID_USUARIO + ",";
                    }
                }
                str += ")";
                str = str.replace(',)', ')');
                //console.log("STRING USERS:  " + str);
                res.usuarios = str;
            }
            //console.log("ARRAY FINAL: " + resultado);
            return resultado;
        };

        var loadUsuarios = function(load, plataforma) {
            window.load = load;
            $.ajax({
                type: 'GET',
                url: 'api_datos.php',
                contentType: "text/plain",
                dataType: 'json',
                data: {
                    accion: 'listaUsuarios',
                    plataforma: plataforma
                },
                success: function(data) {
                    myJsonData = data;
                    //console.log(myJsonData);
                    cargarCombo('addSelectUsuarios', myJsonData);
                    cargarCombo('editSelectUsuarios', myJsonData);
                },
                error: function(e) {
                    //console.log("There was an error with your request...");
                    //console.log("error: " + JSON.stringify(e));
                }
            });
        };

        var cargarCombo = function(id, datos) {
            var combo = document.getElementById(id);
            $('#' + id).children().remove().end();
            for (var i = 0; i < datos.length; i++) {
                var opt = datos[i];
                //console.log(opt);
                var el = document.createElement("option");
                el.textContent = opt.apellidos;
                el.value = opt.id;
                combo.appendChild(el);
            }
            $('#' + id).selectpicker('refresh');
            if (load == 1) {
                $('#' + id).selectpicker('selectAll');
            }
        };

        var definirDataTable = function() {
            $('#dt_guion').DataTable({
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'copyHtml5',
                        text: '<i class="fa fa-copy"/> Copiar',
                        titleAttr: 'Copiar'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"/> Exportar a Excel',
                        titleAttr: 'Copiar'
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fa fa-file-text-o"/> Exportar a CSV',
                        titleAttr: 'Copiar'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf-o"/> Exportar a PDF',
                        titleAttr: 'Copiar'
                    }
                ],
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                "columnDefs": [{
                    "width": "10%",
                    "targets": 6
                }],
                sort: false
            });
        };

        var populateDataTable = function(data) {
            //console.log("populating data table...");
            // clear the table before populating it with more data
            $("#dt_guion").DataTable().clear();
            var length = Object.keys(data).length;
            for (var i = 0; i < length; i++) {
                var res = data[i];
                var visible = "NO";
                var visibleReseller = "NO";
                if (res.visible == "1") {
                    var visible = "SI";
                }
                if (res.visibleReseller == "1") {
                    var visibleReseller = "SI";
                }
                // Se puede cargar la info desde la inicializacion de la tabla
                $('#dt_guion').dataTable().fnAddData([
                    res.id,
                    res.tipo,
                    res.caso,
                    res.respuesta,
                    visible,
                    visibleReseller,
                    res.usuarios,
                    "<a href='#editGuionModal' class='edit' data-toggle='modal'><i class='material-icons' data-toggle='tooltip' title='Modificar'>&#xE254;</i></a><a href='#deleteGuionModal' class='delete' data-toggle='modal'><i class='material-icons' data-toggle='tooltip' title='Eliminar'>&#xE872;</i></a>"
                ]);
                $('#dt_guion').dataTable().fnSetColumnVis(6, false);
            }
        };
    </script>

</body>

</html>