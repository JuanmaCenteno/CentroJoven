$(document).ready(function () {
    window.plataforma = $("#plataforma").val();
    window.userId = $("#userId").val();
    //console.log("USUARIO: " + window.userId + "\n");
    var url = "resources/codigosDevolucion.json";
    window.json = "";
    window.asunto = "";
    window.estado = "2";
    definirDataTable('lineasActivas');
    definirDataTable('solicitudes');
    $('#documentosAdicionales').hide();

    // JSON CONCEPTO DEVOLUCIÓN
    $.getJSON(url, function (json) {
        window.json = json;
    });

    // EJECUTAR CONSULTA
    $('#btnExec').on('click', function () {
        recargaBotones();
        window.visReseller = false;
        $("#panelTabla").fadeOut();
        $("#panelDevoluciones").fadeOut();
        $("#labelDevoluciones").fadeOut();
        $("#panelTicket").fadeOut();
        var dni = $('#dniCliente').val();
        //console.log("DNI: " + dni);
        if (dni == "") {
            alert("Introduce un DNI");
        } else {
            dni = $('#dniCliente').val();
            loadData(dni);
        }
    });

    // COMPORTAMIENTO TOCAR DATATABLE
    $('#devoluciones tbody').on('click', 'tr', function () {
        arrayData = $('#devoluciones').DataTable().row(this).data();
        $('#idFactura').prop('value', arrayData[0]);
        $('#importe').prop('value', arrayData[7]);
        $('#numTlf').prop('value', arrayData[4]);
        //console.log(arrayData[4]);
    });

    // COMPORTAMIENTO GUION

    $('#comboBoxPreguntas').on('change', function () {
        var preg = $('#comboBoxPreguntas').val();
        //console.log(preg);
        loadRespuesta(preg);
    });

    // CREAR TÍCKET

    $('#btnTicket').on('click', function () {
        var dni = $('#dniCliente').val();
        window.infoExtra = $('#infoExtra').val();
        //console.log(window.plataforma);
        $.ajax({
            type: 'GET',
            url: 'api_datos.php',
            contentType: "text/plain",
            dataType: 'json',
            data: {
                accion: 'infoCliente',
                plataforma: window.plataforma,
                dniCliente: dni
            },
            success: function (data) {
                myJsonData = data;
                //console.log(myJsonData);
                //enviarTicket(myJsonData);
                postTicket(myJsonData);
            },
            error: function (e) {
                //console.log("There was an error with your request...");
                //console.log("error: " + JSON.stringify(e));
            }
        });
    });
    // TICKET DEVOLUCION
    $('#btnTicket1').on('click', function () {
        var dni = $('#dniCliente').val();
        window.infoExtra = $('#infoExtra1').val();
        //console.log(window.plataforma);
        $.ajax({
            type: 'GET',
            url: 'api_datos.php',
            contentType: "text/plain",
            dataType: 'json',
            data: {
                accion: 'infoCliente',
                plataforma: window.plataforma,
                dniCliente: dni
            },
            success: function (data) {
                myJsonData = data;
                //console.log(myJsonData);
                //enviarTicket(myJsonData);
                postTicket(myJsonData);
            },
            error: function (e) {
                //console.log("There was an error with your request...");
                //console.log("error: " + JSON.stringify(e));
            }
        });
    });
});

// CARGA PREGUNTAS
var loadGuion = function (accion, tipo) {
    $.ajax({
        type: 'GET',
        url: 'api_datos.php',
        contentType: "text/plain",
        dataType: 'json',
        data: {
            accion: accion,
            tipo: tipo,
            plataforma: window.plataforma
        },
        success: function (data) {
            myJsonData = data;
            window.arrayPreguntas = myJsonData;
            //console.log("LOAD GUION", myJsonData);
            loadUsuarios();
        },
        error: function (e) {
            //console.log("There was an error with your request...");
            //console.log("error: " + JSON.stringify(e));
        }
    });
};

var loadUsuarios = function () {
    $.ajax({
        type: 'GET',
        url: 'api_datos.php',
        contentType: "text/plain",
        dataType: 'json',
        data: {
            accion: 'listaUsuariosGuion',
            plataforma: window.plataforma
        },
        success: function (data) {
            myJsonData = data;
            //console.log("LOADUSUARIOS", myJsonData);
            cargarPreguntas(myJsonData);
        },
        error: function (e) {
            //console.log("There was an error with your request...");
            //console.log("error: " + JSON.stringify(e));
        }
    });
};

// SELECCIONA DEL ARRAY DE PREGUNTAS LAS QUE SON VISIBLES POR EL USUARIO
var cargarPreguntas = function (data) {
    var resultado = window.arrayPreguntas;
    var length = Object.keys(resultado).length;
    var length1 = Object.keys(data).length;
    for (var i = 0; i < length; i++) {
        var res = resultado[i];
        res.vis = false;
        for (var j = 0; j < length1; j++) {
            var resu = data[j];

            if (res.id === parseInt(resu.ID_GUION, 10)) {
                //console.log("CARGAR PREGUNTAS --> RES ID:  ", res.id, " RES ID GUION: ", parseInt(resu.ID_GUION,10), "USUARIO LOGIN : ", parseInt(window.userId,10), "USUARIO GUION", parseInt(resu.ID_USUARIO,10));
                if (resu.ID_USUARIO === window.userId) {
                    res.vis = true;
                }
                break;
            }
        }
    }
    //console.log("ARRAY FINAL: ", resultado);
    cargarComboPreguntas(resultado);
};

var loadRespuesta = function (tipo) {
    console.log("LOAD RESPUESTA TIPO", tipo);
    $.ajax({
        type: 'GET',
        url: 'api_datos.php',
        contentType: "text/plain",
        dataType: 'json',
        data: {
            accion: 'infoRespuesta',
            tipo: tipo,
            plataforma: window.plataforma
        },
        success: function (data) {
            myJsonData = data;
            console.log(myJsonData);
            $('#infoRespuesta').prop('value', myJsonData.respuesta);
        },
        error: function (e) {
            //console.log("There was an error with your request...");
            //console.log("error: " + JSON.stringify(e));
        }
    });
};

var cargarComboPreguntas = function (datos) {
    var combo = document.getElementById('comboBoxPreguntas');
    $('#comboBoxPreguntas').children().remove().end();
    for (var i = 0; i < datos.length; i++) {
        var opt = datos[i];
        //console.log("comboBoxPreguntas", opt);
        if (opt.vis == true) {
            var el = document.createElement("option");
            el.textContent = opt.caso;
            el.value = opt.id;
            combo.appendChild(el);
        }
    }
    $('#comboBoxPreguntas').selectpicker('refresh');
};

// ENVIO DE TICKET
var enviarTicket = function (datos) {
    //console.log("INFO EXTRA: " + window.infoExtra);
    var prioridad = $('#selectPrioridad').val();
    if ($('#infoExtra1').val() != "") {
        prioridad = $('#selectPrioridad1').val();
    }
    var archivo = $('#adjunto').val();
    //console.log(archivo);
    //var mensaje = "<h2 style='color:#cc3366'>Información del Cliente</h2><ul><li>ID Cliente: <strong>" + datos.id + "</strong></li><li> Nombre y Apellidos: <strong>" + datos.cliente + "</strong></li><li> Documento de identidad: <strong>" + datos.dni + "</strong></li><li>Reseller: <strong>" + datos.reseller + "</strong></li><li>Nº Fijo: <strong>" + datos.fijo + "</strong></li><li>Nº Móvil: <strong>" + datos.movil + "</strong></li><li>Email: <strong>" + datos.email + "</strong></li></ul></r></hr></br><h2 style='color:#cc3366'>Información Adicional</h2><p>" + window.infoExtra + "</p>";    
    //var mens = "data:text/html,MESSAGE <b>" + mensaje + "</b>";
    //window.asunto += " " + datos.cliente;
    //console.log(window.asunto);
    $.ajax({
        type: 'GET',
        url: "api_datos.php",
        contentType: "text/plain",
        dataType: 'json',
        data: {
            plataforma: "OSTICKET",
            accion: "insertTicket",
            asunto: (window.asunto + " " + datos.cliente),
            nombreCliente: datos.cliente,
            numTlfCliente: datos.movil,
            prioridad: prioridad,
            imagen: archivo
            //mensaje: mens
        },
        success: function (data) {
            myJsonData = data;
            alert("TICKET ENVIADO");
            $('#infoExtra').prop('value', '');
            $('#infoExtra1').prop('value', '');
            $('#adjunto').prop('value', '');
        },
        error: function (e) {
            //console.log("There was an error with your request...");
            //console.log("error: " + JSON.stringify(e));
        }
    });
};

var postTicket = function (datos) {
    //console.log("INFO EXTRA: " + window.infoExtra);
    var prioridad = $('#selectPrioridad').val();
    if ($('#infoExtra1').val() != "") {
        prioridad = $('#selectPrioridad1').val();
    }

    var guion = $('#infoRespuesta').val();
    //console.log(archivo);
    //var mensaje = "<h2 style='color:#cc3366'>Información del Cliente</h2><ul><li>ID Cliente: <strong>" + datos.id + "</strong></li><li> Nombre y Apellidos: <strong>" + datos.cliente + "</strong></li><li> Documento de identidad: <strong>" + datos.dni + "</strong></li><li>Reseller: <strong>" + datos.reseller + "</strong></li><li>Nº Fijo: <strong>" + datos.fijo + "</strong></li><li>Nº Móvil: <strong>" + datos.movil + "</strong></li><li>Email: <strong>" + datos.email + "</strong></li></ul></r></hr></br><h2 style='color:#cc3366'>Información Adicional</h2><p>" + window.infoExtra + "</p>";
    //var mens = "data:text/html,MESSAGE <b>" + mensaje + "</b>";
    var mens = "Informacion del Cliente:\n ID Cliente: " + datos.id + "\nNombre y Apellidos: " + datos.cliente + "\nDocumento de identidad: " + datos.dni + "\nReseller: " + datos.reseller + "\nNum Fijo: " + datos.fijo + "\nNum Movil: " + datos.movil + "\nEmail: " + datos.email + "\nPreguntas \n" + guion + "\nInformacion Adicional: \n" + window.infoExtra;

    var fd = new FormData();
    var files = $('#adjunto')[0].files[0];
    fd.append('file', files);
    fd.append("asunto", (window.asunto + " " + datos.cliente));
    fd.append("mensaje", mens);
    $.ajax({
        type: 'POST',
        url: "enviar_ticket.php",
        processData: false,
        contentType: false,
        dataType: 'json',
        data: fd,
        success: function (data) {
            //myJsonData = data;
            clean();
        },
        error: function (e) {
            clean();
        }
    });
    return false;
};

// CAMBIAR ASUNTO Y MOSTRAR PANEL DE ENVÍO DE TICKET
var mostrarTicket = function name(asu) {
    $('#documentosAdicionales').hide();
    if (asu == 'Baja') {
        if (window.plataforma == "MOVIL") {
            $('#documentosAdicionales').attr("href", "https://aiongest.internetinfraestructuras.es/documentos/MOVIL/BajaMovil.pdf");
            $('#documentosAdicionales').html("<i className='fa fa-download'></i> Documento de baja Movil");
            $('#documentosAdicionales').show();
        } else {
            $('#documentosAdicionales').attr("href", "https://aiongest.internetinfraestructuras.es/documentos/MOVIL/BajaFTTH.pdf");
            $('#documentosAdicionales').html("<i className='fa fa-download'></i> Documento de baja FTTH");
            $('#documentosAdicionales').show();
        }
    }
    if (asu == 'Gestión de Líneas' && window.plataforma == "MOVIL") {
        $('#documentosAdicionales').attr("href", "https://aiongest.internetinfraestructuras.es/documentos/MOVIL/CambioTitularMovil.pdf");
        $('#documentosAdicionales').html("<i className='fa fa-download'></i> Documento cambio de titular");
        $('#documentosAdicionales').show();
    }

    if (asu == "Incidencia") {
        if (window.plataforma != "MOVIL") {
            loadSolicitudes($('#dniCliente').val());
        }
    } else {
        $("#tablaSolicitudes").fadeOut();
    }
    window.asunto = "";
    window.asunto = window.plataforma + " Nueva " + asu;
    window.tipo = asu;
    $('#panelEnviarTicket').fadeIn();
    if (asu == "Avería") {
        asu = "Averia";
    }
    if (window.visReseller == true) {
        //console.log("LISTA PREGUNTAS RESELLER --> ", asu);
        loadGuion('listaPreguntasReseller', asu);
    } else {
        //console.log("LISTA PREGUNTAS --> ", asu);
        loadGuion('listaPreguntas', asu);
    }
};

var loadCombo = function (id, plataforma) {
    $.ajax({
        type: 'GET',
        url: 'api_datos.php',
        contentType: "text/plain",
        dataType: 'json',
        data: {
            accion: 'listaNombres',
            plataforma: plataforma
        },
        success: function (data) {
            myJsonData = data;
            //console.log(myJsonData);
            cargarCombo(id, myJsonData);
        },
        error: function (e) {
            //console.log("There was an error with your request...");
            //console.log("error: " + JSON.stringify(e));
        }
    });
};

var cargarCombo = function (id, datos) {
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
};

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
};

var loadData = function (dniCliente) {
    //console.log("DNI: " + dniCliente);
    // CARGAMOS INFO CLIENTE
    $.ajax({
        type: 'GET',
        url: 'api_datos.php',
        contentType: "text/plain",
        dataType: 'json',
        data: {
            accion: 'infoCliente',
            plataforma: plataforma,
            dniCliente: dniCliente
        },
        success: function (data) {
            myJsonData = data;
            //console.log(myJsonData);
            $("#dt_cliente").dataTable().fnDestroy();
            definirDataTable("dt_cliente");
            populateDataTableCliente(myJsonData);
            if (myJsonData.cc == "0") {
                //console.log("DISTRIBUCION --> ", myJsonData);
                loadDataAfter(dniCliente);
            } else if (myJsonData.cc == "1") {
                //console.log("RESELLER --> ", myJsonData);
                //console.log("ID EMP: " + myJsonData.idReseller);
                loadDevolucionesReseller(myJsonData.idReseller, dniCliente);
            }
            //console.log("MASTER EMP: " + myJsonData.master);
        },
        error: function (e) {
            //console.log("There was an error with your request...");
            //console.log("error: " + JSON.stringify(e));
        }
    });
    $.ajax({
        type: 'GET',
        url: 'api_datos.php',
        contentType: "text/plain",
        dataType: 'json',
        data: {
            accion: 'lineasActivas',
            plataforma: plataforma,
            dniCliente: dniCliente
        },
        success: function (data) {
            myJsonData = data;
            //console.log(myJsonData);
            $("#lineasActivas").dataTable().fnDestroy();
            definirDataTable("lineasActivas");
            $("#panelTabla").fadeIn();
            $("#panelDevoluciones").hide();
            $("#labelDevoluciones").hide();
            populateDataTableLineasActivas(myJsonData);
        },
        error: function (e) {
            //console.log("There was an error with your request...");
            //console.log("error: " + JSON.stringify(e));
        }
    });
};

var loadDataAfter = function name(dniCliente) {
    // CARGAMOS DEVOLUCIONES
    $.ajax({
        type: 'GET',
        url: 'api_datos.php',
        contentType: "text/plain",
        dataType: 'json',
        data: {
            accion: 'listaDevoluciones',
            plataforma: plataforma,
            estado: window.estado,
            dniCliente: dniCliente
        },
        success: function (data) {
            myJsonData = JSON.parse(JSON.stringify(data));
            //console.log(myJsonData);
            //console.log("Longitud: " + myJsonData.length);
            if (myJsonData.length > 0) {
                $("#devoluciones").dataTable().fnDestroy();
                definirDataTable("devoluciones");
                populateDataTableDevoluciones(myJsonData);
                $("#panelDevoluciones").fadeIn();
                $("#labelDevoluciones").fadeOut();
                window.asunto = "Nueva Devolución";
            } else {
                $("#panelTicket").fadeIn();
            }
        },
        error: function (e) {
            //console.log("There was an error with your request...");
            //console.log("error: " + JSON.stringify(e));
        }
    });
};

var loadDevolucionesReseller = function (idReseller, dniCliente) {
    $.ajax({
        type: 'GET',
        url: 'api_datos.php',
        contentType: "text/plain",
        dataType: 'json',
        data: {
            accion: 'listaDevolucionesReseller',
            plataforma: plataforma,
            estado: window.estado,
            dniCliente: idReseller
        },
        success: function (data) {
            myJsonData = JSON.parse(JSON.stringify(data));
            //console.log(myJsonData);
            //console.log("Longitud: " + myJsonData.length);
            if (myJsonData.length > 0) {
                $("#labelDevoluciones").fadeIn();
                window.asunto = "Nueva Devolución";
            } else {
                window.visReseller = true;
                soloGestion();
                loadDataAfter(dniCliente);
            }
        },
        error: function (e) {
            //console.log("There was an error with your request...");
            //console.log("error: " + JSON.stringify(e));
        }
    });
};

var recargaBotones = function () {
    $("#btnConsulta").fadeIn();
    $("#btnBaja").fadeIn();
    $("#btnBonos").fadeIn();
    $("#btnGestion").fadeIn();
};

var soloGestion = function () {
    $("#btnConsulta").fadeOut();
    $("#btnBaja").fadeOut();
    $("#btnBonos").fadeOut();
};

var loadSolicitudes = function (dniCliente) {
    $.ajax({
        type: 'GET',
        url: 'api_datos.php',
        contentType: "text/plain",
        dataType: 'json',
        data: {
            accion: 'listaSolicitudes',
            plataforma: plataforma,
            dniCliente: dniCliente
        },
        success: function (data) {
            myJsonData = JSON.parse(JSON.stringify(data));
            //console.log(myJsonData);
            $("#solicitudes").dataTable().fnDestroy();
            definirDataTable("solicitudes");
            populateDataTableSolicitudes(myJsonData);
            $("#tablaSolicitudes").fadeIn();
        },
        error: function (e) {
            //console.log("There was an error with your request...");
            //console.log("error: " + JSON.stringify(e));
        }
    });
};

var populateDataTableLineasActivas = function (data) {
    // clear the table before populating it with more data
    $("#lineasActivas").DataTable().clear();
    var length = Object.keys(data).length;
    var puk = "";
    var voz = 0.00;
    var dat = 0.00;
    var sms = 0.00;
    console.log("LINEASACTIVAS --> ", data);
    for (var i = 0; i < length; i++) {
        var res = data[i];
        if (res.puk) {
            puk = res.puk;
        }
        if (res.voz) {
            voz = parseFloat(res.voz).toFixed(2);
        }
        if (res.dat) {
            dat = parseFloat(res.dat).toFixed(2);
        }
        if (res.sms) {
            sms = parseFloat(res.sms).toFixed(2);
        }
        // Se puede cargar la info desde la inicializacion de la tabla
        $('#lineasActivas').dataTable().fnAddData([
            res.numero,
            res.dni,
            res.nombre,
            res.direccionInstalacion,
            res.nombreTarifa,
            res.nombreEmpresa,
            res.fechaAltaAiongest,
            res.fechaBajaAiongest,
            res.estado,
            puk,
            voz,
            dat,
            sms
        ]);
    }
    if (plataforma == "FTTH") {
        $('#lineasActivas').dataTable().fnSetColumnVis(9, false);
        $('#lineasActivas').dataTable().fnSetColumnVis(10, false);
        $('#lineasActivas').dataTable().fnSetColumnVis(11, false);
        $('#lineasActivas').dataTable().fnSetColumnVis(12, false);
    }
};

var populateDataTableDevoluciones = function (data) {
    // clear the table before populating it with more data
    $("#devoluciones").DataTable().clear();
    var length = Object.keys(data).length;
    for (var i = 0; i < length; i++) {
        var res = data[i];
        var descripcion = getDescripcion(res.concepto);
        var customer = res.id_customer;
        if (plataforma == "MOVIL") {
            customer = res.CUSTOMER_ID;
        }
        var reseller = res.NOMBRE + " | " + res.APELLIDOS;
        var importe = (parseFloat(res.importe_final_devol) + parseFloat(res.gastos_devolucion)).toFixed(2);
        var idFactura = res.id_factu;
        var numTlf = res.numero_telef;
        // Se puede cargar la info desde la inicializacion de la tabla
        $('#devoluciones').dataTable().fnAddData([
            idFactura,
            res.fecha_impago,
            res.dni_cliente,
            res.nombre_cliente,
            numTlf,
            reseller,
            res.nombreTarifa,
            importe,
            res.concepto,
            descripcion,
            `<a onclick="recobroDevolucion('` + idFactura + `', '` + importe + `', '` + numTlf + `');" style="font-size: 1.5em; color:black;">
                <button type="button" rel="tooltip" data-toggle="tooltip" title="Recobro Devolución">
                    <i class="fa fa-credit-card"></i>
                </button>
            </a>`
            /*
            <a onclick="recobroDevolucionManual('` + idFactura + `', '` + importe + `', '` + numTlf + `');" style="font-size: 1.5em; color:black;">
                <button type="button" rel="tooltip" data-toggle="tooltip" title="Recobro Devolución">
                    <i class="fa fa-credit-card"></i>
                </button>
            </a>`
            */
        ]);
    }
};

var populateDataTableSolicitudes = function (data) {
    // clear the table before populating it with more data
    $("#solicitudes").DataTable().clear();
    var length = Object.keys(data).length;
    for (var i = 0; i < length; i++) {
        var res = data[i];
        // Se puede cargar la info desde la inicializacion de la tabla
        $('#solicitudes').dataTable().fnAddData([
            res.Estado_Solicitud_ftthmb,
            res.Direccion_Completa_ftthmb,
            res.Nombre_Completo_ftthmb,
            res.DNI_Cliente_ftthmb,
            res.Email_Cliente_ftthmb,
            res.Telefono_Instalacion_ftthmb,
            res.Observaciones,
            res.EstadoIncidencia,
            res.MotivoIncidencia,
            res.DescripcionIncidencia
        ]);
    }
};

var populateDataTableCliente = function (data) {
    // clear the table before populating it with more data
    $("#dt_cliente").DataTable().clear();
    var res = data;
    //console.log("Nombre: " + res.cliente);
    //console.log(res);
    // Se puede cargar la info desde la inicializacion de la tabla
    $('#dt_cliente').dataTable().fnAddData([
        res.cliente,
        res.dni,
        res.direccion,
        res.reseller,
        res.email,
        res.fijo,
        res.movil,
        res.fecha
    ]);
};

var recobroDevolucion = function (idFactura, importe, numTlf) {
    $.ajax(
        {
            url: 'formRedsys.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                accion: "enviarSms",
                idFactura: idFactura,
                importe: importe,
                numTlf: numTlf,
                plataforma: plataforma
            },
            success: function (data) {
                //console.log(data);
                alertaOk("ÉXITO", "El SMS ha sido enviado correctamente", "success", "Aceptar", "");
            },
            error: function () {
                alertaOk("ATENCION", "Ocurrió un error y NO hemos podido desbloquear la línea, realice el desbloqueo desde Gestión Líneas", "warning", "Aceptar", "");
            }
        });
};

var recobroDevolucionManual = function (idFactura, importe, numTlf) {
    $.ajax(
        {
            url: 'formRedsys.php',
            type: 'POST',
            cache: false,
            async: true,
            data: {
                accion: "formPagoBoton",
                idFactura: idFactura,
                importe: importe,
                numTlf: numTlf,
                plataforma: plataforma
            },
            success: function (data) {
                myJsonData = JSON.parse(data);
                //console.log(myJsonData);
                formRedsys(myJsonData);
            },
            error: function () {
                alertaOk("ATENCION", "Ocurrió un error y NO hemos podido desbloquear la línea, realice el desbloqueo desde Gestión Líneas", "warning", "Aceptar", "");
            }
        });
};

var formRedsys = function (data) {
    document.getElementById("version").value = data.version;
    document.getElementById("params").value = data.params;
    document.getElementById("firma").value = data.signature;
    document.forms["frm"].submit();
};

var getDescripcion = function (codigo) {
    var length = Object.keys(window.json).length;
    var descripcion = "";
    for (var i = 0; i < length; i++) {
        var res = window.json[i];
        if (res.codigo == codigo) {
            descripcion = res.descripcion;
        }
    }
    return descripcion;
};

var clean = function () {
    alertaOk("ÉXITO", "El Ticket ha sido creado correctamente", "success", "Aceptar", "");
    $('#infoExtra').prop('value', '');
    $('#infoExtra1').prop('value', '');
    $('#adjunto').prop('value', '');
    $('#infoRespuesta').prop('value', '');
    $('#comboBoxPreguntas').selectpicker('val', '');
};

// Función para adjuntar imagen y pasarla a base64
function toDataUrl(src, callback, outputFormat) {
    // Create an Image object
    var img = new Image();
    // Add CORS approval to prevent a tainted canvas
    img.crossOrigin = 'Anonymous';
    img.onload = function () {
        // Create an html canvas element
        var canvas = document.createElement('CANVAS');
        // Create a 2d context
        var ctx = canvas.getContext('2d');
        var dataURL;
        // Resize the canavas to the original image dimensions
        canvas.height = this.naturalHeight;
        canvas.width = this.naturalWidth;
        // Draw the image to a canvas
        ctx.drawImage(this, 0, 0);
        // Convert the canvas to a data url
        dataURL = canvas.toDataURL(outputFormat);
        // Return the data url via callback
        callback(dataURL);
        // Mark the canvas to be ready for garbage 
        // collection
        canvas = null;
    };
    // Load the image
    img.src = src;
    // make sure the load event fires for cached images too
    if (img.complete || img.complete === undefined) {
        // Flush cache
        img.src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
        // Try again
        img.src = src;
    }
}