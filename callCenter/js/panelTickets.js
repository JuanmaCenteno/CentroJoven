$(document).ready(function () {
    window.plataforma = $("#plataforma").val();
    definirDataTable('dt_tickets');
    definirDataTable('solicitudes');
    loadTickets("%");

    // TBODY
    $('#dt_tickets tbody').on('click', 'tr', function () {
        arrayData = $('#dt_tickets').DataTable().row(this).data();
        $('#numTicket').prop('value', arrayData[0]);
    });

    // POST
    $('#cerrarTicket').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData($('#cerrarTicket')[0]);
        $.ajax({

            url: 'http://89.140.17.5/tkt/api_corta.php',
            type: 'POST',
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            success: function () {
                // MODAL OK
                location.reload();
            },
            error: function (e) {
                $('#modalErrorConsulta').modal('show');
            }
        });
        return false;
    });
});

var definirDataTable = function (id) {
    $('#' + id).DataTable({

        dom: 'lBfrtip',
        buttons: [
            {
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
            },
            {
                text: 'Incidencias',
                action: function () {
                    loadTickets('Incidencia');
                }
            },
            {
                text: 'Averías',
                action: function () {
                    loadTickets('Avería');
                }
            },
            {
                text: 'Bajas',
                action: function () {
                    loadTickets('Baja');
                }
            },
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
};

var loadTickets = function (tipo) {
    // CARGAMOS TICKETS
    $.ajax({
        type: 'GET',
        url: 'http://89.140.17.5/tkt/api_corta.php',
        contentType: "text/plain",
        dataType: 'json',
        data: {
            accion: 'listaTickets',
            plataforma: "OSTICKET",
        },
        success: function (data) {
            myJsonData = data;
            console.log(myJsonData);
            $("#dt_tickets").dataTable().fnDestroy();
            definirDataTable("dt_tickets");
            populateDataTable(myJsonData);
        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });
};

var populateDataTable = function (data) {
    // clear the table before populating it with more data
    $("#dt_tickets").DataTable().clear();
    var length = Object.keys(data).length;
    for (var i = 0; i < length; i++) {
        var res = data[i];
        // Se puede cargar la info desde la inicializacion de la tabla
        $('#dt_tickets').dataTable().fnAddData([
            res.id,
            res.fecha,
            res.asunto,
            res.contenido,
            res.nombre,
            res.tlf,
            res.prioridad,
            "<a href='#cerrarTicketModal' class='delete' data-toggle='modal'><i class='material-icons' data-toggle='tooltip' title='Cerrar'>&#xE872;</i></a>"
        ]);
    }
};