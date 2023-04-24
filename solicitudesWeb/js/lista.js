$(document).ready(function () {
    definirDataTable();
    loadData('%');

    // VALIDAR ICC
    var ok = true;
    $('#envioIcc').on('input', function () {
        if (validarIcc($('#envioIcc').val())) { // FUNCION VALIDAR ICC
            $('#btnEnviarSim').prop("disabled", false);
            $('#labelIcc').hide();
        } else {
            $('#btnEnviarSim').prop("disabled", true);
            $('#labelIcc').show();
        }
    });

    $('#solicitudes tbody').on('click', 'tr', function () {
        arrayData = $('#solicitudes').DataTable().row(this).data();
        $('#idTransporte').prop('value', arrayData[0]);
        $('#direccion').prop('value', arrayData[19]);
        $('#telefono').prop('value', arrayData[12]);
        $('#email').prop('value', arrayData[13]);
        $('#nombre').prop('value', (arrayData[9] + " " + arrayData[10]));
        $('#dni').prop('value', arrayData[7]);
        $('#municipio').prop('value', arrayData[21]);
        $('#codPostal').prop('value', arrayData[20]);
        $('#provincia').prop('value', arrayData[22]);
        $('#selectAgencia').selectpicker('val', '');
    });

    $('#selectAgencia').on('change', function () {
        if ($('#selectAgencia').selectpicker().val() != "") {
            $('#agencia').prop('value', $('#selectAgencia').selectpicker().val());
        } else {
            $('#agencia').prop('value', "");
        }
    });


    // POST
    $('#enviarSim').on('submit', function (e) {
        // ACTUALIZAR ICC EN BD
        var data = $("#enviarSim").serialize();
        console.log(data);
        cambiarIcc();
        e.preventDefault();
        $.ajax({
            url: 'envioSim.php',
            type: 'post',
            dataType: 'application/json',
            data: data,
            success: function (data) {
                // MODAL OK
                location.reload();
            },
            error: function (e) {
                //location.reload();
            }
        });
    });
});

var cambiarIcc = function () {
    var plataforma = $("#plataforma").val();
    var id = $("#idTransporte").val();
    var icc = $("#envioIcc").val();
    $.ajax({
        type: 'GET',
        url: 'api_solicitudes.php',
        contentType: "text/plain",
        dataType: 'json',
        data: {
            accion: 'cambiarIcc',
            id: id,
            icc: icc,
            plataforma: plataforma
        },
        success: function (data) {
            myJsonData = data;
            //console.log(myJsonData);
        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });
}

var definirDataTable = function () {
    $('#solicitudes').removeAttr('width').DataTable({
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
                text: 'Todos los Estados',
                action: function () {
                    loadData('%');
                }
            },
            {
                text: 'Solicitado',
                action: function () {
                    loadData('Solicitado');
                }
            },
            {
                text: 'En Espera Transporte',
                action: function () {
                    loadData('En Espera Transporte');
                }
            },
            {
                text: 'Recogido Transporte',
                action: function () {
                    loadData('Recogido Transporte');
                }
            },
            {
                text: 'Recibido Transporte',
                action: function () {
                    loadData('Recibido Transporte');
                }
            },
            {
                text: 'Error Transporte',
                action: function () {
                    loadData('Error Transporte');
                }
            },
            {
                text: 'En Proceso Alta/Portabilidad',
                action: function () {
                    loadData('En Proceso Alta/Portabilidad');
                }
            },
            {
                text: 'Alta/Portabilidad Rechazada',
                action: function () {
                    loadData('Alta/Portabilidad Rechazada');
                }
            },
            {
                text: 'Completado',
                action: function () {
                    loadData('Completado');
                }
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
        scrollX: true,
        order: [[1, "desc"]]
    });
}

var loadData = function (estado) {
    var plataforma = $("#plataforma").val();
    $.ajax({
        type: 'GET',
        url: 'api_solicitudes.php',
        contentType: "text/plain",
        dataType: 'json',
        data: {
            accion: 'listaSolicitudes',
            estado: estado,
            plataforma: plataforma
        },
        success: function (data) {
            myJsonData = data;
            console.log(myJsonData);
            $("#solicitudes").dataTable().fnDestroy();
            definirDataTable();
            populateDataTable(myJsonData);
        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });
};

var populateDataTable = function (data) {
    var plataforma = $("#plataforma").val();
    // clear the table before populating it with more data
    $("#solicitudes").DataTable().clear();
    var length = Object.keys(data).length;
    for (var i = 0; i < length; i++) {
        var res = data[i];

        // Se puede cargar la info desde la inicializacion de la tabla
        $('#solicitudes').dataTable().fnAddData([
            res.ID_TRANSPORTE,
            res.FECHA_SOLICITUD,
            res.ESTADO,
            res.ESTADO_SIM,
            res.ID_TARIFA,
            res.NOMBRE_TARIFA,
            res.PRECIO_TARIFA,
            res.NUM_DOCUMENTO,
            res.RAZON_SOCIAL,
            res.NOMBRE_TITULAR,
            res.APELLIDOS_TITULAR,
            res.FECHA_NACIMIENTO,
            res.NUM_TLF_TITULAR,
            res.EMAIL,
            res.ALTA,
            res.NUM_TLF_PORTABILIDAD,
            res.NUM_ICC_PORTABILIDAD,
            res.TITULAR_CUENTA_BANCARIA,
            res.IBAN,
            res.DIRECCION_ENVIO,
            res.CODIGO_POSTAL_ENVIO,
            res.MUNICIPIO_ENVIO,
            res.PROVINCIA_ENVIO,
            res.DIRECCION_FACTURACION,
            res.CODIGO_POSTAL_FACTURACION,
            res.MUNICIPIO_FACTURACION,
            res.PROVINCIA_FACTURACION,
            "<button id='btnSim' type='button' class='btn btn-success' onclick='muestraModal()'><span class='glyphicon glyphicon-send' aria-hidden='true'></span></button>" +
            "<button id='btnImprimirEtiqueta' type='button' class='btn btn-warning' value='Etiqueta' onclick='imprimirEtiqueta(\"" + res.ESTADO + "\",\"" + res.ID_TRANSPORTE + "\")'><span class='glyphicon glyphicon-barcode' aria-hidden='true'></span></button>" +
            "<button id='btnSeguimiento' type='button' class='btn btn-info' value='Seguimiento Pedido' onclick='seguimientoPedido(\"" + res.CODIGO_POSTAL_ENVIO + "\",\"" + res.ID_TRANSPORTE + "\")'><span class='glyphicon glyphicon-map-marker' aria-hidden='true'></span></button>"
        ]);
        if (plataforma == "FTTH") {
            $('#solicitudes').dataTable().fnSetColumnVis(26, false);
        }
    }
};

var muestraModal = function () {
    $('#modalIcc').modal('show');
};

var imprimirEtiqueta = function (estado, idTransporte) {
    var plataforma = $("#plataforma").val();
    if (estado === "En Espera Transporte" || estado === "Solicitado") {
        $.ajax({
            type: 'GET',
            url: 'api_solicitudes.php',
            contentType: "text/plain",
            dataType: 'json',
            data: {
                accion: 'imprimirEtiqueta',
                idTransporte: idTransporte,
                plataforma: plataforma,
            },
            success: function (data) {
                if (data !== "") {
                    myJsonData = JSON.parse(data);
                    //console.log("ETIQUETA JS", myJsonData.url_etiqueta);
                    var win = window.open('https://www.genei.es/recursos/etiquetas/' + myJsonData.url_etiqueta, '_blank');
                    if (win) {
                        //Browser has allowed it to be opened
                        win.focus();
                    } else {
                        //Browser has blocked it
                        alert('Please allow popups for this website');
                    }
                }
            },
            error: function (e) {
                console.log("There was an error with your request...");
                console.log("error: " + JSON.stringify(e));
            }
        });
    }
};

var seguimientoPedido = function (codPostal, idTransporte) {
    console.log(codPostal, idTransporte);
    $.ajax({
        type: 'GET',
        url: 'api_solicitudes.php',
        contentType: "text/plain",
        dataType: 'json',
        data: {
            accion: 'seguimientoPedido',
            idTransporte: idTransporte
        },
        success: function (data) {
            if (data !== "") {
                myJsonData = JSON.parse(data);
                console.log("SEGUIMIENTO JS", myJsonData.seguimiento);
                var win = window.open('https://m.gls-spain.es/e/' + myJsonData.seguimiento + "/" + codPostal + "/es", '_blank');
                if (win) {
                    //Browser has allowed it to be opened
                    win.focus();
                } else {
                    //Browser has blocked it
                    alert('Please allow popups for this website');
                }
            }
        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });
    return false;
};

// FUNCIONES PARA VALIDAR ICC

var calcularIcc = function (Luhn) {
    var sum = 0;
    for (i = 0; i < Luhn.length; i++) {
        sum += parseInt(Luhn.substring(i, i + 1));
    }
    var delta = new Array(0, 1, 2, 3, 4, -4, -3, -2, -1, 0);
    for (i = Luhn.length - 1; i > 0; i -= 2) {
        var deltaIndex = parseInt(Luhn.substring(i, i + 1));
        var deltaValue = delta[deltaIndex];
        sum += deltaValue;
    }
    var mod10 = sum % 10;
    mod10 = 10 - mod10;
    if (mod10 == 10) {
        mod10 = 0;
    }
    return mod10;
}

var validarIcc = function (Luhn) {
    var LuhnDigit = parseInt(Luhn.substring(Luhn.length - 1, Luhn.length));
    var LuhnLess = Luhn.substring(0, Luhn.length - 1);
    if (calcularIcc(LuhnLess) == parseInt(LuhnDigit)) {
        return true;
    }
    return false;
}