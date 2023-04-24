<?php

if (!isset($_SESSION)) {
    @session_start();
}

//check_session(3);
?>
<!doctype html>
<html lang="es-ES">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <!--  <title><?//php echo OWNER; ?> <?//php echo DEF_FACTURACION; ?> / Listado</title> -->
    <title>Gestión Tipos Electrónica</title>
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

        <section id="middle">
            <!-- page title -->
            <header id="page-header">
                <h1>Usted esta en</h1>
                <ol class="breadcrumb">
                    <li><a href="#">Centalitas</a></li>
                    <li class="active"> / Tipos Electrónica</li>
                </ol>
            </header>
            <!-- /page title -->
            <div class="container full" style="margin-left: 0px;">
                <div class="table-responsive" style="width: 190%;">
                    <div class="table-wrapper">
                        <div class="table-title">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h2 style="color:white">Gestionar <b>Tipos Electrónica</b></h2>
                                </div>
                                <div class="col">
                                    <a href='#addElectronicaModal' class="btn btn-success" data-toggle='modal'><i class="material-icons">&#xE147;</i> <span>Añadir Nueva Electrónica</span></a>
                                </div>
                            </div>
                        </div>
                        <table id="dt_electronica" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Add Electrónica HTML -->
            <div id="addElectronicaModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="addElectronica">
                            <div class="modal-header">
                                <h4 class="modal-title">Añadir Electrónica</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                                <div class="form-group" style="display: none;">
                                    <input type="text" class="form-control" name="action" value="insertElectronica">
                                    <input type="text" class="form-control" name="plataforma" value="<?php echo $_SESSION['PLATAFORMA'] ?>">
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

            <!-- Edit electronica HTML -->
            <div id="editElectronicaModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="editElectronica">
                            <div class="modal-header">
                                <h4 class="modal-title">Modificar Electrónica</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input id="editNombre" type="text" class="form-control" name="nombre" required>
                                </div>
                                <div class="form-group" style="display: none;">
                                    <input id="editAction" type="text" class="form-control" name="action" value="updateElectronica">
                                   <input id="editNombreAntiguo" type="text" class="form-control" name="nombreAntiguo">
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
            <div id="deleteElectronicaModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="deleteElectronica">
                            <div class="modal-header">
                                <h4 class="modal-title">Eliminar Electrónica</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <p>Estás seguro de eliminar a esta Electrónica?</p>
                                <p class="text-warning"><small>Esta acción no se puede revertir</small></p>
                                <div class="form-group" style="display: none;">
                                    <input type="text" class="form-control" name="action" value="deleteElectronica">
                                    <input id="deleteNombre" type="text" class="form-control" name="nombre">
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
            //var id = "<?php echo $_SESSION['REVENDEDOR'] ?>";

            // POST
            $('#addElectronica').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData($('#addElectronica')[0]);
                $.ajax({
                    url: 'postCrudConexElec.php',
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

            $('#editElectronica').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData($('#editElectronica')[0]);
                $.ajax({
                    url: 'postCrudConexElec.php',
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

            $('#deleteElectronica').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData($('#deleteElectronica')[0]);
                $.ajax({
                    url: 'postCrudConexElec.php',
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
            $('#dt_electronica tbody').on('click', 'tr', function() {
                arrayData = $('#dt_electronica').DataTable().row(this).data();
                $('#editNombre').prop('value', arrayData[0]);
                $('#editNombreAntiguo').prop('value', arrayData[0]);
                $('#deleteNombre').prop('value', arrayData[0]);
            });

            loadElectronicaes(plataforma);
        });

        var loadElectronicaes = function(plataforma) {
            $.ajax({
                type: 'GET',
                url: 'api_datos.php',
                contentType: "text/plain",
                dataType: 'json',
                data: {
                    accion: 'tiposElectronica',
                    plataforma: plataforma
                },
                success: function(data) {
                    myJsonData = data;
                    //console.log(myJsonData);
                    $("#dt_electronica").dataTable().fnDestroy();
                    definirDataTable("#dt_electronica");
                    populateDataTable(myJsonData);
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
            $("#dt_electronica").DataTable().clear();
            var length = Object.keys(data).length;
            for (var i = 0; i < length; i++) {
                var res = data[i];

                // Se puede cargar la info desde la inicializacion de la tabla
                $('#dt_electronica').dataTable().fnAddData([
                    res.nombre,
                    "<a href='#editElectronicaModal' class='edit' data-toggle='modal'><i class='material-icons' data-toggle='tooltip' title='Modificar'>&#xE254;</i></a><a href='#deleteElectronicaModal' class='delete' data-toggle='modal'><i class='material-icons' data-toggle='tooltip' title='Eliminar'>&#xE872;</i></a>"
                ]);

            }
        };
    </script>

</body>

</html>
}