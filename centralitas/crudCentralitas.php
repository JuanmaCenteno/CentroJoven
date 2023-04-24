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
    <title>Gestión Centralitas</title>
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
                    <li><a href="#">Centralitas</a></li>
                    <li class="active"> / Crud Centralitas</li>
                </ol>
            </header>
            <!-- /page title -->
            <div class="container full" style="margin-left: 0px;">
                <div class="table-responsive" style="width: 190%;">
                    <div class="table-wrapper">
                        <div class="table-title">
                            <div class="row">
                                <div class="col-sm-3">
                                    <h2 style="color:white">Gestionar <b>Centralitas</b></h2>
                                </div>
                                <div class="col-sm-3">
                                    <a href="index.php" target="_blank" class="btn btn-success"><i class="material-icons">&#xE147;</i> <span>Añadir Nueva Centralita</span></a>
                                </div>
                            </div>
                        </div>
                        <table id="dt_centralitas" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Número Teléfono</th>
                                    <th>Número Teléfono 2</th>
                                    <th>Número Teléfono 2</th>
                                    <th>IDC</th>
                                    <th>Nombre Sede</th>
                                    <th>Locución Ppal</th>
                                    <th>Tipo Conexión</th>
                                    <th>Tipo Electrónica</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <!-- Tabla Dialplans -->
                    <div class="table-wrapper">
                        <table id="dt_dialplans" class="table table-striped table-hover" style="display: none;">
                            <thead>
                                <tr>
                                    <th>IDC</th>
                                    <th>D</th>
                                    <th>IDCD</th>
                                    <th>Teléfono</th>
                                    <th>Locución</th>
                                    <th>Nombre</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <!-- Tabla Líneas -->
                    <div class="table-wrapper">
                        <table id="dt_lineas" class="table table-striped table-hover" style="display: none;">
                            <thead>
                                <tr>
                                    <th>IDCD</th>
                                    <th>Teléfono</th>
                                    <th>Extensión</th>
                                    <th>Nombre</th>
                                    <th>Tonos</th>
                                    <th>Ext. Retorno</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Add Departamento HTML -->
            <div id="addDepartamentoModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="addDepartamento">
                            <div class="modal-header">
                                <h4 class="modal-title">Añadir Departamento</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="inputDDepartamento">D</label>
                                    <input type="text" class="form-control" id="inputDDepartamento" name="dDepartamento" required>
                                </div>
                                <div class="form-group">
                                    <label for="inputNumTlfDepartamento">Número Teléfono</label>
                                    <input type="number" class="form-control" id="inputNumTlfDepartamento" name="numTlfDepartamento" placeholder="Introduce el número">
                                </div>
                                <div class="form-group">
                                    <label for="inputNombreIDCD">Nombre Departamento</label>
                                    <input type="text" class="form-control" id="inputNombreDepartamento" name="nombreDepartamento" placeholder="Introduce el nombre" required>
                                </div>
                                <div class="form-group">
                                    <label for="inputLocucionIDCD">Locución</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="locucionDepartamento" id="inputLocucionIDCD">
                                        <label class="custom-file-label" for="inputLocucionIDCD">Selecciona un fichero de locución</label>
                                    </div>
                                </div>
                                <div class="form-group" style="display: none;">
                                    <input type="text" class="form-control" name="action" value="insertDepartamento">
                                    <input type="text" class="form-control" name="plataforma" value="<?php echo $_SESSION['PLATAFORMA'] ?>">
                                    <input id="addDCif" type="text" class="form-control" name="cifCliente">
                                    <input id="addIDCent" type="text" class="form-control" name="idCent">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                                <input type="submit" class="btn btn-info" value="Añadir">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Add Linea HTML -->
            <div id="addLineaModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="addLinea">
                            <div class="modal-header">
                                <h4 class="modal-title">Añadir Línea</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="inputNumTlfDialplan">Número Teléfono</label>
                                    <input type="number" class="form-control" id="inputNumTlfDialplan" name="numTlfDialplan" placeholder="Introduce el número">
                                </div>
                                <div class="form-group">
                                    <label for="inputExtDialplan">Nº Ext</label>
                                    <input type="number" class="form-control" name="extDialplan" id="inputExtDialplan" value="200" required>
                                </div>
                                <div class="form-group">
                                    <label for="inputNombreDialplan">Nombre</label>
                                    <input type="text" class="form-control" name="nombreDialplan" id="inputNombreDialplan" required>
                                </div>
                                <div class="form-group">
                                    <label for="inputTonoDialplan">Tono</label>
                                    <input type="number" class="form-control" name="tonoDialplan" id="inputTonoDialplan" value="1" required>
                                </div>
                                <div class="form-group">
                                    <label for="inputRetotnoDialplan">Retorno</label>
                                    <input type="number" class="form-control" name="retornoDialplan" id="inputRetotnoDialplan" value="100" required>
                                </div>
                                <div class="form-group" style="display: none;">
                                    <input type="text" class="form-control" name="action" value="insertLinea">
                                    <input type="text" class="form-control" name="plataforma" value="<?php echo $_SESSION['PLATAFORMA'] ?>">
                                    <input id="addLD" type="text" class="form-control" name="dDialplan">
                                    <input id="addLIDCD" type="text" class="form-control" name="idCent">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                                <input type="submit" class="btn btn-info" value="Añadir">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Edit Centralita HTML -->
            <div id="editCentralitaModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="editCentralita">
                            <div class="modal-header">
                                <h4 class="modal-title">Modificar Centralita</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Cliente</label>
                                    <input id="editCliente" type="text" class="form-control" name="codigoCliente" required>
                                </div>
                                <div class="form-group">
                                    <label>Numero de Teléfono</label>
                                    <input id="editNumTlf" type="text" class="form-control" name="numeroTelefono" required>
                                </div>
                                <div class="form-group">
                                    <label>Numero de Teléfono 2</label>
                                    <input id="editNumTlf2" type="text" class="form-control" name="numeroTelefono2" required>
                                </div>
                                <div class="form-group">
                                    <label>Numero de Teléfono 3</label>
                                    <input id="editNumTlf3" type="text" class="form-control" name="numeroTelefono3" required>
                                </div>
                                <div class="form-group">
                                    <label>Nombre Sede</label>
                                    <input id="editNombreSede" type="text" class="form-control" name="nombreSede" required>
                                </div>
                                <div class="form-group">
                                    <label>Locución Ppal</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="locucionIDC" id="inputLocucionIDC">
                                        <label class="custom-file-label" for="inputLocucionIDC">Selecciona un fichero de locución</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Tipo Conexión</label>
                                    <select class="selectpicker ml-3 w-50" id="selectTipoConexion" name="tipoConexion" title="Nada seleccionado" required>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Tipo Electrónica</label>
                                    <select class="selectpicker ml-3 w-50" id="selectTipoElectronica" name="tipoElectronica" title="Nada seleccionado" required>
                                    </select>
                                </div>
                                <div class="form-group" style="display: none;">
                                    <input id="editAction" type="text" class="form-control" name="action" value="update">
                                    <input id="editPlat" type="text" class="form-control" name="plataforma" value="<?php echo $_SESSION['PLATAFORMA'] ?>">
                                    <input id="editCif" type="text" class="form-control" name="cifCliente">
                                    <input id="editIdAntiguo" type="text" class="form-control" name="idAntiguo">
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
            <!-- Delete Centralita HTML -->
            <div id="deleteCentralitaModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="deleteCentralita">
                            <div class="modal-header">
                                <h4 class="modal-title">Eliminar Centralita</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <p>Estás seguro de eliminar a esta Centralita?</p>
                                <p class="text-warning"><small>Esta acción no se puede revertir</small></p>
                                <div class="form-group" style="display: none;">
                                    <input type="text" class="form-control" name="action" value="delete">
                                    <input id="deleteCliente" type="text" class="form-control" name="codigoCliente">
                                    <input id="deleteCif" type="text" class="form-control" name="cifCliente">
                                    <input type="text" class="form-control" name="plataforma" value="<?php echo $_SESSION['PLATAFORMA'] ?>">
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
            <!-- Edit Departamentos HTML -->
            <div id="editDepartamentosModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="editDepartamentos">
                            <div class="modal-header">
                                <h4 class="modal-title">Modificar Dialplan</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>D</label>
                                    <input id="editDD" type="text" class="form-control" name="dDepartamento" required>
                                </div>
                                <div class="form-group">
                                    <label>IDCD</label>
                                    <input id="editIDCD" type="text" class="form-control" name="idcdDepartamento" required>
                                </div>
                                <div class="form-group">
                                    <label for="editNumTlfDepartamento">Número Teléfono</label>
                                    <input type="number" class="form-control" id="editNumTlfDepartamento" name="numTlfDepartamento" placeholder="Introduce el número">
                                </div>
                                <div class="form-group">
                                    <label>Locucion</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="locucionDepartamento" id="inputlocucionDepartamento">
                                        <label class="custom-file-label" for="inputlocucionDepartamento">Selecciona un fichero de locución</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input id="editDNombre" type="text" class="form-control" name="nombreDepartamento" required>
                                </div>
                                <div class="form-group" style="display: none;">
                                    <input id="editDID" type="text" class="form-control" name="idCent">
                                    <input id="editDAction" type="text" class="form-control" name="action" value="updateDepartamento">
                                    <input id="editDPlat" type="text" class="form-control" name="plataforma" value="<?php echo $_SESSION['PLATAFORMA'] ?>">
                                    <input id="editDCif" type="text" class="form-control" name="cifCliente">
                                    <input id="editDLocucion" type="text" class="form-control" name="locu">
                                    <input id="editDAntiguo" type="text" class="form-control" name="dAntiguo">
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
            <!-- Delete Departamentos HTML -->
            <div id="deleteDepartamentosModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="deleteDepartamentos">
                            <div class="modal-header">
                                <h4 class="modal-title">Eliminar Dialplan</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <p>Estás seguro de eliminar este Dialplan?</p>
                                <p class="text-warning"><small>Esta acción no se puede revertir</small></p>
                                <div class="form-group" style="display: none;">
                                    <input type="text" class="form-control" name="action" value="deleteDepartamento">
                                    <input id="deleteDCliente" type="text" class="form-control" name="idCent">
                                    <input id="deleteDD" type="text" class="form-control" name="dDepartamento">
                                    <input id="deleteDCif" type="text" class="form-control" name="cifCliente">
                                    <input type="text" class="form-control" name="plataforma" value="<?php echo $_SESSION['PLATAFORMA'] ?>">
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
            <!-- Edit Lineas HTML -->
            <div id="editLineasModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="editLineas">
                            <div class="modal-header">
                                <h4 class="modal-title">Modificar Linea</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="editNumTlfDialplan">Número Teléfono</label>
                                    <input type="number" class="form-control" id="editNumTlfDialplan" name="numTlfDialplan" placeholder="Introduce el número">
                                </div>
                                <div class="form-group">
                                    <label>Extensión</label>
                                    <input id="editExt" type="text" class="form-control" name="extDialplan" required>
                                </div>
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input id="editNombre" type="text" class="form-control" name="nombreDialplan" required>
                                </div>
                                <div class="form-group">
                                    <label>Tonos</label>
                                    <input id="editTonos" type="text" class="form-control" name="tonoDialplan" required>
                                </div>
                                <div class="form-group">
                                    <label>Ext Retorno</label>
                                    <input id="editRetorno" type="text" class="form-control" name="retornoDialplan" required>
                                </div>
                                <div class="form-group" style="display: none;">
                                    <input id="editLAction" type="text" class="form-control" name="action" value="updateLinea">
                                    <input id="editLPlat" type="text" class="form-control" name="plataforma" value="<?php echo $_SESSION['PLATAFORMA'] ?>">
                                    <input id="editLID" type="text" class="form-control" name="idCent">
                                    <input id="editLExtAntiguo" type="text" class="form-control" name="extensionAntiguo">
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
            <!-- Delete Lineas HTML -->
            <div id="deleteLineasModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="deleteLineas">
                            <div class="modal-header">
                                <h4 class="modal-title">Eliminar Línea</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <p>Estás seguro de eliminar esta Línea?</p>
                                <p class="text-warning"><small>Esta acción no se puede revertir</small></p>
                                <div class="form-group" style="display: none;">
                                    <input id="deleteLAction" type="text" class="form-control" name="action" value="deleteLinea">
                                    <input id="deleteLCliente" type="text" class="form-control" name="idCent">
                                    <input id="deleteLExt" type="text" class="form-control" name="extDialplan">
                                    <input type="text" class="form-control" name="plataforma" value="<?php echo $_SESSION['PLATAFORMA'] ?>">
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

            <!-- Modal CSV -->
            <div id="modalCsv" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Generar CSV</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>Estás seguro de que quieres generar un CSV?</p>
                        </div>
                        <div class="modal-footer">
                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                            <input id="btnCsv" type="button" class="btn btn-warning" value="Generar">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Correo -->
            <div id="modalCorreo" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Enviar Email</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>Estás seguro de que quieres enviar el email?</p>
                        </div>
                        <div class="modal-footer">
                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                            <input id="btnCorreo" type="button" class="btn btn-danger" value="Enviar">
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
    <script src="httpks://code.jquery.com/jquery-3.5.1.min.js"></script>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script>
        var arrayData = [];
        $(document).ready(function() {
            // Activate tooltip
            $('[data-toggle="tooltip"]').tooltip();
            var plataforma = "<?php echo $_SESSION['PLATAFORMA'] ?>";
            var ruta = "";
            //var id = "<?php echo $_SESSION['REVENDEDOR'] ?>";

            // GENERAR CSV
            $('#btnCsv').on('click', function(e) {
                generarCsv(e, $('#editIdAntiguo').val(), $('#editCliente').val(), plataforma);
            });

            $('#btnCorreo').on('click', function() {
                mandarCorreo($('#editCliente').val(), $('#editIdAntiguo').val(), plataforma);
            });

            // POST
            $('#addDepartamento').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData($('#addDepartamento')[0]);
                $.ajax({
                    url: 'infoForm.php',
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

            $('#addLinea').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData($('#addLinea')[0]);
                $.ajax({
                    url: 'infoForm.php',
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
                        console.log("There was an error with your request...");
                        console.log("error: " + JSON.stringify(e));
                        $('#modalErrorConsulta').modal('show');
                    }
                });
                return false;
            });

            $('#editCentralita').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData($('#editCentralita')[0]);
                $.ajax({
                    url: 'infoForm.php',
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

            $('#deleteCentralita').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData($('#deleteCentralita')[0]);
                $.ajax({
                    url: 'infoForm.php',
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
                        console.log("There was an error with your request...");
                        console.log("error: " + JSON.stringify(e));
                        $('#modalErrorConsulta').modal('show');
                    }
                });
                return false;
            });

            $('#editDepartamentos').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData($('#editDepartamentos')[0]);
                $.ajax({
                    url: 'infoForm.php',
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

            $('#deleteDepartamentos').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData($('#deleteDepartamentos')[0]);
                $.ajax({
                    url: 'infoForm.php',
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
                        console.log("There was an error with your request...");
                        console.log("error: " + JSON.stringify(e));
                        $('#modalErrorConsulta').modal('show');
                    }
                });
                return false;
            });

            $('#editLineas').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData($('#editLineas')[0]);
                $.ajax({
                    url: 'infoForm.php',
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

            $('#deleteLineas').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData($('#deleteLineas')[0]);
                $.ajax({
                    url: 'infoForm.php',
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
                        console.log("There was an error with your request...");
                        console.log("error: " + JSON.stringify(e));
                        $('#modalErrorConsulta').modal('show');
                    }
                });
                return false;
            });

            // TBODY
            $('#dt_centralitas tbody').on('click', 'tr', function() {
                arrayData = $('#dt_centralitas').DataTable().row(this).data();
                var conex = cambiarConexiones(arrayData[8], "conex");
                var elect = cambiarConexiones(arrayData[9], "elect");
                $('#editIdAntiguo').prop('value', arrayData[5]);
                $('#editCliente').prop('value', arrayData[1]);
                $('#editNumTlf').prop('value', arrayData[2]);
                $('#editNumTlf2').prop('value', arrayData[3]);
                $('#editNumTlf3').prop('value', arrayData[4]);
                $('#editNombreSede').prop('value', arrayData[6]);
                $('#selectTipoConexion').selectpicker('val', conex);
                $('#selectTipoElectronica').selectpicker('val', elect);
                $('#deleteCliente').prop('value', arrayData[5]);
                $('#addIDCent').prop('value', arrayData[5]);
                // Carga CIF para borrado o modificación carpeta de locuciones
                loadCif(arrayData[1], plataforma);
                loadDialplans(arrayData[5], plataforma);
            });

            $('#dt_dialplans tbody').on('click', 'tr', function() {
                arrayData = $('#dt_dialplans').DataTable().row(this).data();
                $('#editDID').prop('value', arrayData[0]);
                $('#editDD').prop('value', arrayData[1]);
                $('#editDAntiguo').prop('value', arrayData[1]);
                $('#editIDCD').prop('value', arrayData[2]);
                $('#editNumTlfDepartamento').prop('value', arrayData[3]);
                $('#editDLocucion').prop('value', arrayData[4]);
                $('#editDNombre').prop('value', arrayData[5]);
                $('#deleteDCliente').prop('value', arrayData[2]);
                $('#deleteDD').prop('value', arrayData[1]);
                $('#addLIDCD').prop('value', arrayData[0]);
                $('#addLD').prop('value', arrayData[1]);
                // Carga CIF para borrado o modificación carpeta de locuciones
                loadCif(arrayData[0].substr(2, 2), plataforma);
                loadLineas(arrayData[2], plataforma);
            });

            $('#dt_lineas tbody').on('click', 'tr', function() {
                arrayData = $('#dt_lineas').DataTable().row(this).data();
                $('#editNumTlfDialplan').prop('value', arrayData[1]);
                $('#editExt').prop('value', arrayData[2]);
                $('#editNombre').prop('value', arrayData[3]);
                $('#editTonos').prop('value', arrayData[4]);
                $('#editRetorno').prop('value', arrayData[5]);
                $('#editLID').prop('value', arrayData[0]);
                $('#editLExtAntiguo').prop('value', arrayData[2]);
                $('#deleteLCliente').prop('value', arrayData[0]);
                $('#deleteLExt').prop('value', arrayData[2]);
            });

            // CAMBIO IDCD
            $('#editDD').on('input', function name() {
                if ($('#editDD').val() != "") {
                    var text = $('#editIDCD').val();
                    text = text.substr(0, text.length - 1);
                    console.log(text);
                    $('#editIDCD').prop('value', text + $('#editDD').val());
                }
            });

            loadCentralitas(plataforma);
            loadCombos(plataforma);
        });

        var mandarCorreo = function(idEmp, idc, plataforma) {
            $.ajax({
                type: 'GET',
                url: 'api_datos.php',
                contentType: "text/plain",
                dataType: 'json',
                data: {
                    accion: 'mandarCorreo',
                    idEmp: idEmp,
                    idc: idc,
                    plataforma: plataforma
                },
                success: function(data) {
                    myJsonData = data;
                    console.log(myJsonData);
                    if (myJsonData = true) {
                        location.reload();
                    } else {
                        $('#modalErrorConsulta').modal('show');
                    }
                },
                error: function(e) {
                    console.log("There was an error with your request...");
                    console.log("error: " + JSON.stringify(e));
                }
            });
        }

        var generarCsv = function(e, id, idEmp, plataforma) {
            $.ajax({
                type: 'GET',
                url: 'api_datos.php',
                contentType: "text/plain",
                dataType: 'json',
                data: {
                    accion: 'exportCsv',
                    id: id,
                    idEmp: idEmp,
                    plataforma: plataforma
                },
                success: function(data) {
                    myJsonData = data;
                    ruta = myJsonData.ruta;
                    console.log(ruta);
                    e.preventDefault(); //stop the browser from following
                    /*                    
                    var link = document.createElement('a');
                    link.href = ruta;
                    link.download = ruta.substr(ruta.lastIndexOf('/') + 1);
                    link.click();
                    */
                    window.location.href = ruta;
                },
                error: function(e) {
                    console.log("There was an error with your request...");
                    console.log("error: " + JSON.stringify(e));
                }
            });
        }

        var cambiarConexiones = function(num, tipo) {
            var res = "";
            switch (tipo) {
                case "conex":
                    switch (num) {
                        case "FTTH":
                            res = "1";
                            break;
                        case "Radioláser":
                            res = "2";
                            break;
                        case "Wifi":
                            res = "5";
                            break;
                        case "Móvil":
                            res = "6";
                            break;
                    }
                    break;
                case "elect":
                    switch (num) {
                        case "Router":
                            res = "2";
                            break;
                        case "Switch":
                            res = "3";
                            break;
                        case "Enrutador Servidor":
                            res = "4";
                            break;
                        case "Antena":
                            res = "5";
                            break;
                    }
                    break;
            }
            return res;
        };

        var loadCentralitas = function(plataforma) {
            $.ajax({
                type: 'GET',
                url: 'api_datos.php',
                contentType: "text/plain",
                dataType: 'json',
                data: {
                    accion: 'listaCentralitas',
                    plataforma: plataforma
                },
                success: function(data) {
                    myJsonData = data;
                    //console.log(myJsonData);
                    $("#dt_centralitas").dataTable().fnDestroy();
                    definirDataTable("#dt_centralitas");
                    populateDataTable(myJsonData);
                },
                error: function(e) {
                    console.log("There was an error with your request...");
                    console.log("error: " + JSON.stringify(e));
                }
            });
        }

        var loadDialplans = function(id, plataforma) {
            $("#dt_dialplans").fadeIn();
            $.ajax({
                type: 'GET',
                url: 'api_datos.php',
                contentType: "text/plain",
                dataType: 'json',
                data: {
                    accion: 'listaDepartamentos',
                    plataforma: plataforma,
                    id: id
                },
                success: function(data) {
                    myJsonData = data;
                    //console.log(myJsonData);
                    $("#dt_dialplans").dataTable().fnDestroy();
                    definirDataTable('#dt_dialplans');
                    populateDataTableDialplans(myJsonData);
                },
                error: function(e) {
                    console.log("There was an error with your request...");
                    console.log("error: " + JSON.stringify(e));
                }
            });
        }

        var loadLineas = function(id, plataforma) {
            $("#dt_lineas").fadeIn();
            $.ajax({
                type: 'GET',
                url: 'api_datos.php',
                contentType: "text/plain",
                dataType: 'json',
                data: {
                    accion: 'listaDiaplans',
                    plataforma: plataforma,
                    id: id
                },
                success: function(data) {
                    myJsonData = data;
                    //console.log(myJsonData);
                    $("#dt_lineas").dataTable().fnDestroy();
                    definirDataTable('#dt_lineas');
                    populateDataTableLineas(myJsonData);
                },
                error: function(e) {
                    console.log("There was an error with your request...");
                    console.log("error: " + JSON.stringify(e));
                }
            });
        }

        var definirDataTable = function(id) {
            $(id).DataTable({
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
                sort: false
            });
        }

        var populateDataTable = function(data) {
            //console.log("populating data table...");
            // clear the table before populating it with more data
            $("#dt_centralitas").DataTable().clear();
            var length = Object.keys(data).length;
            for (var i = 0; i < length; i++) {
                var res = data[i];
                switch (res.tipoConexion) {
                    case "1":
                        res.tipoConexion = "FTTH";
                        break;
                    case "2":
                        res.tipoConexion = "Radioláser";
                        break;
                    case "5":
                        res.tipoConexion = "Wifi";
                        break;
                    case "6":
                        res.tipoConexion = "Móvil";
                        break;
                }
                switch (res.tipoElectronica) {
                    case "2":
                        res.tipoElectronica = "Router";
                        break;
                    case "3":
                        res.tipoElectronica = "Switch";
                        break;
                    case "4":
                        res.tipoElectronica = "Enrutador Servidor";
                        break;
                    case "5":
                        res.tipoElectronica = "Antena";
                        break;
                }
                // Se puede cargar la info desde la inicializacion de la tabla
                $('#dt_centralitas').dataTable().fnAddData([
                    res.ID,
                    res.cliente,
                    res.numeroTelefono,
                    res.numeroTelefono2,
                    res.numeroTelefono3,
                    res.IDC,
                    res.nombreSede,
                    res.locucionPrincipal,
                    res.tipoConexion,
                    res.tipoElectronica,
                    "<a href='#addDepartamentoModal' data-toggle='modal'><i class='material-icons' data-toggle='tooltip' title='Añadir'>&#xE147;</i></a><a href='#editCentralitaModal' class='edit' data-toggle='modal'><i class='material-icons' data-toggle='tooltip' title='Modificar'>&#xE254;</i></a><a href='#deleteCentralitaModal' class='delete' data-toggle='modal'><i class='material-icons' data-toggle='tooltip' title='Eliminar'>&#xE872;</i></a><a href='#modalCsv' data-toggle='modal'><i class='material-icons' data-toggle='tooltip' title='Descargar'>&#xe2c0;</i></a><a href='#modalCorreo' data-toggle='modal'><i class='material-icons' data-toggle='tooltip' title='Descargar'>&#xe0be;</i></a>"
                ]);

            }
        }

        var populateDataTableDialplans = function(data) {
            //console.log("populating data table...");
            // clear the table before populating it with more data
            $("#dt_dialplans").DataTable().clear();
            var length = Object.keys(data).length;
            for (var i = 0; i < length; i++) {
                var res = data[i];

                // Se puede cargar la info desde la inicializacion de la tabla
                $('#dt_dialplans').dataTable().fnAddData([
                    res.IDC,
                    res.D,
                    res.IDCD,
                    res.telefono,
                    res.locucion,
                    res.nombre,
                    "<a href='#addLineaModal' data-toggle='modal'><i class='material-icons' data-toggle='tooltip' title='Añadir'>&#xE147;</i></a><a href='#editDepartamentosModal' class='edit' data-toggle='modal'><i class='material-icons' data-toggle='tooltip' title='Modificar'>&#xE254;</i></a><a href='#deleteDepartamentosModal' class='delete' data-toggle='modal'><i class='material-icons' data-toggle='tooltip' title='Eliminar'>&#xE872;</i></a>"
                ]);

            }
        }

        var populateDataTableLineas = function(data) {
            //console.log("populating data table...");
            // clear the table before populating it with more data
            $("#dt_lineas").DataTable().clear();
            var length = Object.keys(data).length;
            for (var i = 0; i < length; i++) {
                var res = data[i];

                // Se puede cargar la info desde la inicializacion de la tabla
                $('#dt_lineas').dataTable().fnAddData([
                    res.IDCD,
                    res.telefono,
                    res.extension,
                    res.nombre,
                    res.tonos,
                    res.extRetorno,
                    "<a href='#editLineasModal' class='edit' data-toggle='modal'><i class='material-icons' data-toggle='tooltip' title='Modificar'>&#xE254;</i></a><a href='#deleteLineasModal' class='delete' data-toggle='modal'><i class='material-icons' data-toggle='tooltip' title='Eliminar'>&#xE872;</i></a>"
                ]);

            }
        }

        var loadCombos = function(plataforma) {
            $.ajax({
                type: 'GET',
                url: 'api_datos.php',
                contentType: "text/plain",
                dataType: 'json',
                data: {
                    accion: "tiposConexion",
                    plataforma: plataforma
                },
                success: function(data) {
                    myJsonData = data;
                    //console.log(myJsonData);
                    cargarComboConexion(myJsonData, "selectTipoConexion");
                },
                error: function(e) {
                    console.log("There was an error with your request...");
                    console.log("error: " + JSON.stringify(e));
                }
            });

            $.ajax({
                type: 'GET',
                url: 'api_datos.php',
                contentType: "text/plain",
                dataType: 'json',
                data: {
                    accion: "tiposElectronica",
                    plataforma: plataforma
                },
                success: function(data) {
                    myJsonData = data;
                    //console.log(myJsonData);
                    cargarComboConexion(myJsonData, "selectTipoElectronica");
                },
                error: function(e) {
                    console.log("There was an error with your request...");
                    console.log("error: " + JSON.stringify(e));
                }
            });

        };

        var cargarComboConexion = function(datos, elem) {
            var combo = document.getElementById(elem);
            for (var i = 0; i < datos.length; i++) {
                var opt = datos[i];
                var el = document.createElement("option");
                el.textContent = opt.nombre;
                el.value = opt.id;
                combo.appendChild(el);
            }
            $('#' + elem).selectpicker('refresh');
        };

        var loadCif = function(id, plataforma) {
            $.ajax({
                type: 'GET',
                url: 'api_datos.php',
                contentType: "text/plain",
                dataType: 'json',
                data: {
                    accion: "loadCif",
                    id: id,
                    plataforma: plataforma
                },
                success: function(data) {
                    myJsonData = data;
                    //console.log(myJsonData);
                    document.getElementById("editCif").value = myJsonData[0].cif;
                    document.getElementById("deleteCif").value = myJsonData[0].cif;
                    document.getElementById("editDCif").value = myJsonData[0].cif;
                    document.getElementById("deleteDCif").value = myJsonData[0].cif;
                    document.getElementById("addDCif").value = myJsonData[0].cif;
                    document.getElementById("addLCif").value = myJsonData[0].cif;
                },
                error: function(e) {
                    console.log("There was an error with your request...");
                    console.log("error: " + JSON.stringify(e));
                }
            });
        };
    </script>

</body>

</html>
}