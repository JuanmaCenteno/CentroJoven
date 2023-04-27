<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    if($_SESSION['id'] != 1 || $_SESSION['id'] != "1"){
        header("Location: http://localhost/Centrojoven/fichar.php");
        die();
    }
    
    ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Administración Voluntarios</title>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <!-- Custom styles -->
        <link rel="stylesheet" href="styles.css" />
       <!-- mobile settings -->
        <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />
        <!-- WEB FONTS -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&amp;subset=latin,latin-ext,cyrillic,cyrillic-ext" rel="stylesheet" type="text/css" />
        <!-- CORE CSS -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Datatables -->
        <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
        <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
        <!-- Selectpicker -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    </head>
    <body class="bg-black">
        <div class="container py-5 h-100 bg-primary">
            <div class="row d-flex justify-content-center align-items-center h-100 bg-secondary">
                <div class="row">
                </div>
                <div class="row">
                    <div class="table-responsive" style="width: 190%;">
                        <div class="table-wrapper">
                            <div class="table-title">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h2 style="color:white">Gestionar <b>Voluntarios</b></h2>
                                    </div>
                                    <div class="col">
                                        <a href='#addVoluntarioModal' class="btn btn-success" data-toggle='modal'><i class="material-icons">&#xE147;</i> <span>Añadir Nuevo Voluntario</span></a>
                                    </div>
                                </div>
                            </div>
                            <table id="dt_voluntarios" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Apellido 1</th>
                                        <th>Apellido 2</th>
                                        <th>DNI</th>
                                        <th>Fecha Nacimiento</th>
                                        <th>Email</th>
                                        <th>Contraseña</th>
                                        <th>Dirección</th>
                                        <th>C. Postal</th>
                                        <th>Tlf Móvil</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!-- Tabla historial -->
                        <div class="table-wrapper">
                            <table id="dt_historial" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Voluntario</th>
                                        <th>Tipo</th>
                                        <th>Fecha y Hora</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Add Voluntario HTML -->
        <div id="addVoluntarioModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="addVoluntario">
                        <div class="modal-header">
                            <h4 class="modal-title">Añadir Voluntario</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Apellido1</label>
                                <input type="text" class="form-control" id="apellido1" name="apellido1" required>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Apellido2</label>
                                <input type="text" class="form-control" id="apellido2" name="apellido2" required>
                            </div>
                            <div class="form-group">
                                <label for="nombre">DNI</label>
                                <input type="text" class="form-control" id="dni" name="dni" required>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Fecha de nacimiento</label>
                                <input type="date" class="form-control" id="fechaNac" name="fechaNacimiento" required>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Email</label>
                                <input type="text" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Contraseña</label>
                                <input type="text" class="form-control" id="pass" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" required>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Código Postal</label>
                                <input type="text" class="form-control" id="cPostal" name="cPostal" required>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Tlf. Móvil</label>
                                <input type="text" class="form-control" id="movil" name="movil" required>
                            </div>
                            <div class="form-group" style="display: none;">
                                <input type="text" class="form-control" name="action" value="addVoluntario">
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

        <!-- Edit Voluntario HTML -->
        <div id="editVoluntarioModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="editVoluntario">
                        <div class="modal-header">
                            <h4 class="modal-title">Modificar Voluntario</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="editNombre" name="nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Apellido1</label>
                                <input type="text" class="form-control" id="editApellido1" name="apellido1" required>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Apellido2</label>
                                <input type="text" class="form-control" id="editApellido2" name="apellido2" required>
                            </div>
                            <div class="form-group">
                                <label for="nombre">DNI</label>
                                <input type="text" class="form-control" id="editDni" name="dni" disabled>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Fecha de nacimiento</label>
                                <input type="date" class="form-control" id="editFechaNac" name="fechaNacimiento" required>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Email</label>
                                <input type="text" class="form-control" id="editEmail" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Contraseña</label>
                                <input type="text" class="form-control" id="editPass" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Dirección</label>
                                <input type="text" class="form-control" id="editDireccion" name="direccion" required>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Código Postal</label>
                                <input type="text" class="form-control" id="editCPostal" name="cPostal" required>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Tlf. Móvil</label>
                                <input type="text" class="form-control" id="editMovil" name="movil" required>
                            </div>
                            <div class="form-group" style="display: none;">
                                <input type="text" class="form-control" name="action" value="editVoluntario">
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

        <!-- Delete Voluntario HTML -->
        <div id="deleteVoluntarioModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="deleteVoluntario">
                        <div class="modal-header">
                            <h4 class="modal-title">Eliminar Voluntario</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>Estás seguro de eliminar este voluntario?</p>
                            <p class="text-warning"><small>Esta acción no se puede revertir</small></p>
                            <div class="form-group" style="display: none;">
                                <input type="text" class="form-control" name="action" value="deleteVoluntario">
                                <input id="deleteDni" type="text" class="form-control" name="dni">
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
        <!-- JAVASCRIPT FILES -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
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
            
                // POST
                $('#addVoluntario').on('submit', function(e) {
                    e.preventDefault();
                    var formData = new FormData($('#addVoluntario')[0]);
                    $.ajax({
                        url: './funciones/formLogin.php',
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
            
                $('#editVoluntario').on('submit', function(e) {
                    e.preventDefault();
                    var formData = new FormData($('#editVoluntario')[0]);
                    $.ajax({
                        url: 'editVoluntario.php',
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
            
                $('#deleteVoluntario').on('submit', function(e) {
                    e.preventDefault();
                    var formData = new FormData($('#deleteVoluntario')[0]);
                    $.ajax({
                        url: 'deleteVoluntario.php',
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
                $('#dt_voluntarios tbody').on('click', 'tr', function() {
                    arrayData = $('#dt_voluntarios').DataTable().row(this).data();
                    $('#editNombre').prop('value', arrayData[0]);
                    $('#editApellido1').prop('value', arrayData[1]);
                    $('#editApellido2').prop('value', arrayData[2]);
                    $('#editDni').prop('value', arrayData[3]);
                    $('#editFechaNac').prop('value', arrayData[4]);
                    $('#editEmail').prop('value', arrayData[5]);
                    $('#editPass').prop('value', arrayData[6]);
                    $('#editDireccion').prop('value', arrayData[7]);
                    $('#editCPostal').prop('value', arrayData[8]);
                    $('#editMovil').prop('value', arrayData[9]);
                    $('#deleteDni').prop('value', arrayData[3]);
                    loadHistorialDni(arrayData[3]);
                });
            
                loadVoluntarios();
                loadHistorial();
            });
            
            // Voluntarios
            var loadVoluntarios = function() {
                $.ajax({
                    type: 'GET',
                    url: './funciones/api_datos.php',
                    contentType: "text/plain",
                    dataType: 'json',
                    data: {
                        accion: 'voluntarios'
                    },
                    success: function(data) {
                        myJsonData = data;
                        console.log(myJsonData);
                        $("#dt_voluntarios").dataTable().fnDestroy();
                        definirDataTable("#dt_voluntarios");
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
                    columnDefs: [
                        {
                            targets: 6,
                            visible: false,
                            searchable: false,
                        }
                    ],
                    sort: false
                });
            }
            
            var populateDataTable = function(data) {
                //console.log("populating data table...");
                // clear the table before populating it with more data
                $("#dt_voluntarios").DataTable().clear();
                var length = Object.keys(data).length;
                for (var i = 0; i < length; i++) {
                    var res = data[i];
            
                    // Se puede cargar la info desde la inicializacion de la tabla
                    $('#dt_voluntarios').dataTable().fnAddData([
                        res.Nombre,
                        res.Apellido1,
                        res.Apellido2,
                        res.DNI,
                        res.FechaNacimiento,
                        res.Email,
                        res.Password,
                        res.Direccion,
                        res.CPostal,
                        res.TlfMovil,
                        "<a href='#editVoluntarioModal' class='edit' data-toggle='modal'><i class='material-icons' data-toggle='tooltip' title='Modificar'>&#xE254;</i></a><a href='#deleteVoluntarioModal' class='delete' data-toggle='modal'><i class='material-icons' data-toggle='tooltip' title='Eliminar'>&#xE872;</i></a>"
                    ]);
            
                }
            };

            // Historial

            var loadHistorial = function() {
                $.ajax({
                    type: 'GET',
                    url: './funciones/api_datos.php',
                    contentType: "text/plain",
                    dataType: 'json',
                    data: {
                        accion: 'historial'
                    },
                    success: function(data) {
                        myJsonData = data;
                        console.log(myJsonData);
                        $("#dt_historial").dataTable().fnDestroy();
                        definirDataTableHistorial("#dt_historial");
                        populateDataTableHistorial(myJsonData);
                    },
                    error: function(e) {
                        console.log("There was an error with your request...");
                        console.log("error: " + JSON.stringify(e));
                    }
                });
            }

            var loadHistorialDni = function(dni) {
                $.ajax({
                    type: 'GET',
                    url: './funciones/api_datos.php',
                    contentType: "text/plain",
                    dataType: 'json',
                    data: {
                        accion: 'historialVoluntario',
                        id: dni
                    },
                    success: function(data) {
                        myJsonData = data;
                        console.log(myJsonData);
                        $("#dt_historial").dataTable().fnDestroy();
                        definirDataTableHistorial("#dt_historial");
                        populateDataTableHistorial(myJsonData);
                    },
                    error: function(e) {
                        console.log("There was an error with your request...");
                        console.log("error: " + JSON.stringify(e));
                    }
                });
            }
            
            var definirDataTableHistorial = function(id) {
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
            
            var populateDataTableHistorial = function(data) {
                //console.log("populating data table...");
                // clear the table before populating it with more data
                $("#dt_historial").DataTable().clear();
                var length = Object.keys(data).length;
                for (var i = 0; i < length; i++) {
                    var res = data[i];
            
                    // Se puede cargar la info desde la inicializacion de la tabla
                    $('#dt_historial').dataTable().fnAddData([
                        res.voluntario,
                        res.tipo,
                        res.fecha
                     ]);
            
                }
            };

        </script>
    </body>
</html>