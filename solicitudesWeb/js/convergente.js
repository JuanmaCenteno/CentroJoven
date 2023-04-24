// Patrones de validación
var patronDate = /^(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d$/;
var patronTlfMovil = new RegExp("(\\+34|0034|34)?[ -]*(6|7)[ -]*([0-9][ -]*){8}");
var patronTlfFijo = new RegExp("(\\+34|0034|34)?[ -]*(9)[ -]*([0-9][ -]*){8}");
var patronEmail = new RegExp("^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$");

// Variables de validación
var direccionInstalacionValida = false;
var dniValido = false;
var razonSocialValido = false;
var nombreApellidosValido = false;
var fechaValido = false;
var movilValido = false;
var emailValido = false;
var checkDatos = false;
var date = "";
var checkNumeroNuevo = false;
var checkNumeroAntiguo = false;
var movilNuevoValido = false;
var checkTitular = false;
var iban = "";
var ibanValido = false;
var direccionValida = false;
var checkNuevaDireccion = true;
var direccionFacturacionValida = false;
var checkPrestacion = false;
var municipioSeleccionado = false;
var calleSeleccionada = false;

//Validación SMS
var codigo = 0;
var start = 0;
var elapsed = 0;
var enviado = false;


$(document).ready(function () {
    // Cambiar Focus Maxlenght
    $('input').on("input", function () {
        if ($(this).val().length == $(this).attr("maxlength")) {
            $(this).next().focus();
        }
    });

    // Cargar Combo Id tarifa
    if ($('#idEmpresa').val() != "") {
        loadCombo($('#idEmpresa').val(), "FTTH");
    }

    $('#selectTarifa').on('change', function () {
        var idTarifa = $('#selectTarifa').selectpicker().val();
        var nombreTarifa = $("#selectTarifa>option:selected").text();
        nombreTarifa = nombreTarifa.substr(0, nombreTarifa.lastIndexOf('|') - 1);
        var precioTarifa = $("#selectTarifa>option:selected").text().toString();
        var euro = parseInt(precioTarifa.lastIndexOf('€')) - 1;
        var raya = precioTarifa.lastIndexOf('|') + 1;
        precioTarifa = precioTarifa.substr(raya, euro);
        precioTarifa = precioTarifa.replace('€', '');

        // Cambio texto en span de nombre y precio
        document.getElementById("spanPrecio").innerHTML = precioTarifa + "€";
        document.getElementById("spanNombreTarifa").innerHTML = nombreTarifa;
        // Cambio valores en inputs del formulario
        $('#precioTarifa').prop('value', precioTarifa);
        $('#nombreTarifa').prop('value', nombreTarifa);
        $('#idTarifa').prop('value', idTarifa);
    });

    // Botones de Navegación
    navegacion();

    // Pestaña Cobertura
    tabCobertura();

    // Pestaña Datos Titular
    tabDatos();

    // Pestaña Configuración
    tabConfiguracion();

    // Pestaña Pago y Envío
    tabPagos();

    // POST
    $('#btnConfirmarCompra').on('click', function (e) {
        e.preventDefault();
        var formData = new FormData($('#formData')[0]);
        $.ajax({
            url: 'solicitudesWeb/formFtth.php',
            type: 'POST',
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            success: function () {
                //alert('Formulario enviado');
                finForm();
            }
        });
        return false;
    });
});

// COMBO TARIFAS
var loadCombo = function (idEmp, plataforma) {
    $.ajax({
        type: 'GET',
        url: 'solicitudesWeb/api_solicitudes.php',
        contentType: "text/plain",
        dataType: 'json',
        data: {
            accion: 'listaTarifas',
            idEmp: idEmp,
            plataforma: plataforma
        },
        success: function (data) {
            myJsonData = data;
            //console.log(myJsonData);
            cargarComboReseller(myJsonData);
        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });
};

var cargarComboReseller = function (datos) {
    var combo = document.getElementById('selectTarifa');
    $('#selectTarifa').children().remove().end();
    for (var i = 0; i < datos.length; i++) {
        var opt = datos[i];
        //console.log(opt);
        var el = document.createElement("option");
        el.textContent = opt.apellidos;
        el.value = opt.id;
        combo.appendChild(el);
    }
    $('#selectTarifa').selectpicker('refresh');
};

var navegacion = function () {

    $('#btnContinuarDatos').on('click', function () {
        cambiarValoresInstalacionColumna();
        $('#myTabs li:eq(1) a').tab('show');
    });
    $('#btnAtrasCobertura').on('click', function () {
        $('#myTabs li:eq(0) a').tab('show');
    });

    $('#btnContinuarConfiguracion').on('click', function () {
        cambiarValoresDatosColumna();
        $('#myTabs li:eq(2) a').tab('show');
    });

    $('#btnContinuarPagos').on('click', function () {
        cambiarValoresConfiguracionColumna();
        $('#myTabs li:eq(3) a').tab('show');
    });
    $('#btnAtrasDatos').on('click', function () {
        $('#myTabs li:eq(1) a').tab('show');
    });

    $('#btnAtrasConfigurar').on('click', function () {
        $('#myTabs li:eq(2) a').tab('show');
    });
}

// -------------------------------------- TAB COBERTURA --------------------------------------

var tabCobertura = function () {
    // DIRECCION INSTALACIÓN

    $('#codigoPostalInstalacion').on('input', function () {
        enableDireccionInstalacion();
    });

    $('#numeroCalleInstalacion').on('input', function () {
        if ($('#numeroCalleInstalacion').val() == "") {
            $('#btnValidarDireccionInstalacion').prop("disabled", true);
        } else {
            $('#btnValidarDireccionInstalacion').prop("disabled", false);
        }
    });

    $('#checkSinNumeroInstalacion').on('click', function () {
        $("#numeroCalleInstalacion").prop("disabled", this.checked);
        $('#btnValidarDireccionInstalacion').prop("disabled", !this.checked);
    });

    // VALIDACIÓN DIRECCIÓN INSTALACIÓN
    $('#btnValidarDireccionInstalacion').on('click', function () {
        // if Validar Dir fun
        validarDireccionInstalacion();
    });

    $('#btnElegirOtraDireccionInstalacion').on('click', function () {
        direccionInstalacionValida = false;
        checkTabCobertura();
        showDireccionInstalacion();
        $('#selectMunicipio').selectpicker('val', '');
    });

    $('#selectProvincia').on('change', function () {
        cargarMunicipios($('#selectProvincia').selectpicker().val());
        $('#provincia').prop('value', $('#selectProvincia').selectpicker().val());
    })

    $('#selectMunicipio').on('change', function () {
        var prov = $('#selectProvincia').selectpicker().val();
        var mun = $('#selectMunicipio').selectpicker().val();
        if (mun != "") {
            $('#municipio').prop('value', mun);
            municipioSeleccionado = true;
            cargarCalles(prov, mun);
        } else {
            municipioSeleccionado = false;
        }
        checkTabCobertura();
    });

    $('#selectCalle').on('change', function () {
        var calle = $('#selectCalle option:selected').selectpicker().text();
        var val = $('#selectCalle').selectpicker().val();
        if (calle != "") {
            calleSeleccionada = true;
            $('#nombreCalleInstalacion').prop('value', (val + " " + calle));
            //console.log($('#nombreCalleInstalacion').val());
        } else {
            calleSeleccionada = false;
        }
        checkTabCobertura();
    });

    cargarProvincias();
};


// COMPORTAMIENTO Dirección Instalación

var hideDireccionInstalacion = function () {
    $('#divDatosDireccionInstalacion').fadeOut();
    $('#divCompletarDireccionInstalacion').fadeIn();
};

var showDireccionInstalacion = function () {
    clearDireccionInstalacion();
    $('#divDatosDireccionInstalacion').fadeIn();
    $('#divCompletarDireccionInstalacion').fadeOut();
};

// COMPROBAR DIRECCION DE FIBRA

var cargarProvincias = function () {
    var provincias = ['ALAVA', 'ALBACETE', 'ALICANTE', 'ALMERIA', 'ASTURIAS', 'AVILA', 'BADAJOZ', 'BALEARES', 'BARCELONA', 'BURGOS', 'CACERES',
        'CADIZ', 'CANTABRIA', 'CASTELLON', 'CEUTA', 'CIUDAD REAL', 'CORDOBA', 'CORUNA', 'CUENCA', 'GERONA', 'GRANADA', 'GUADALAJARA',
        'GUIPUZCOA', 'HUELVA', 'HUESCA', 'JAEN', 'LA RIOJA', 'LAS PALMAS', 'LEON', 'LUGO', 'MADRID', 'MALAGA', 'MELILLA', 'MURCIA', 'NAVARRA',
        'ORENSE', 'PALENCIA', 'PONTEVEDRA', 'SALAMANCA', 'SEGOVIA', 'SEVILLA', 'SORIA', 'TARRAGONA',
        'TENERIFE', 'TERUEL', 'TOLEDO', 'VALENCIA', 'VALLADOLID', 'VIZCAYA', 'ZAMORA', 'ZARAGOZA'];
    cargarCombo('selectProvincia', provincias);
}

var cargarMunicipios = function (prov) {
    $.ajax({
        type: 'GET',
        url: 'http://aiongest.internetinfraestructuras.es/apiuuii.php',
        contentType: "text/plain",
        dataType: 'json',
        data: {
            accion: 'getMunicipios',
            provincia: prov
        },
        success: function (data) {
            myJsonData = data;
            //console.log(myJsonData.data);
            cargarComboMunicipio('selectMunicipio', myJsonData.data);
        },
        error: function (e) {
            //console.log("There was an error with your request...");
            //console.log("error: " + JSON.stringify(e));
        }
    });
}

var cargarCalles = function (prov, municipio) {
    $.ajax({
        type: 'GET',
        url: 'http://aiongest.internetinfraestructuras.es/apiuuii.php',
        contentType: "text/plain",
        dataType: 'json',
        data: {
            accion: 'getCalles',
            provincia: prov,
            municipio: municipio
        },
        success: function (data) {
            myJsonData = data;
            //console.log(myJsonData.data);
            cargarComboCalles('selectCalle', myJsonData.data);
        },
        error: function (e) {
            //console.log("There was an error with your request...");
            //console.log("error: " + JSON.stringify(e));
        }
    });
}

var cargarComboMunicipio = function (id, datos) {
    var combo = document.getElementById(id);
    $('#' + id).children().remove().end();
    for (var i = 0; i < datos.length; i++) {
        var opt = datos[i];
        //console.log(opt);
        var el = document.createElement("option");
        el.textContent = opt.municipio;
        el.value = opt.municipio;
        combo.appendChild(el);
    }
    $('#' + id).selectpicker('refresh');
};

var cargarComboCalles = function (id, datos) {
    var combo = document.getElementById(id);
    $('#' + id).children().remove().end();
    for (var i = 0; i < datos.length; i++) {
        var opt = datos[i];
        //console.log(opt);
        var el = document.createElement("option");
        el.textContent = opt.calle;
        el.value = opt.tipovia;
        combo.appendChild(el);
    }
    $('#' + id).selectpicker('refresh');
};

var cargarCombo = function (id, datos) {
    var combo = document.getElementById(id);
    $('#' + id).children().remove().end();
    for (var i = 0; i < datos.length; i++) {
        var opt = datos[i];
        //console.log(opt);
        var el = document.createElement("option");
        el.textContent = opt;
        el.value = opt;
        combo.appendChild(el);
    }
    $('#' + id).selectpicker('refresh');
};

var validarDireccionInstalacion = function () {
    var prov = $('#selectProvincia').selectpicker().val();
    var mun = $('#selectMunicipio').selectpicker().val();
    var calle = $('#selectCalle option:selected').selectpicker().text();
    //console.log(calle);
    var numero = $('#numeroCalleInstalacion').val();
    ajaxDireccionInstalacion(prov, mun, calle, numero);
}

var ajaxDireccionInstalacion = function (prov, municipio, calle, numero) {
    $.ajax({
        type: 'GET',
        url: 'http://aiongest.internetinfraestructuras.es/apiuuii.php',
        contentType: "text/plain",
        dataType: 'json',
        data: {
            accion: 'getUUII',
            provincia: prov,
            municipio: municipio,
            calle: calle,
            numero: numero
        },
        success: function (data) {
            myJsonData = data;
            //console.log(myJsonData.data.resultados);
            if (myJsonData.data.resultados.length > 0) {
                direccionInstalacionValida = true;
                checkTabCobertura();
                hideDireccionInstalacion();
                $('#labelFibraSi').show();
                $('#labelFibraNo').hide();
            } else {
                direccionInstalacionValida = false;
                checkTabCobertura();
                $('#labelFibraNo').show();
                $('#labelFibraSi').hide();
            }
        },
        error: function (e) {
            //console.log("There was an error with your request...");
            //console.log("error: " + JSON.stringify(e));
        }
    });
}

// CHEQUEO TODO VALIDADO

var checkTabCobertura = function () {
    if (direccionInstalacionValida && municipioSeleccionado && calleSeleccionada) {
        $('#btnContinuarDatos').prop('disabled', false);
    } else {
        $('#btnContinuarDatos').prop('disabled', true);
    }
};


// CAMBIO VALORES RESUMEN

var cambiarValoresInstalacionColumna = function () {
    $('#direccionInstalacion').text($('#nombreCalleInstalacion').val() + " , " + $('#numeroCalleInstalacion').val() + " , " + $('#codigoPostalInstalacion').val());
    $('#direccionInstalacion').show();
    $('#divInfoInstalacion').show();
};



// -------------------------------------- TAB DATOS --------------------------------------

var tabDatos = function () {
    // DNI o CIF
    $('#selectTipoDocumento').on('change', function () {
        if ($('#selectTipoDocumento').val() == 1) {
            selectDni();
        } else {
            selectCif();
        }
    });

    // VALIDACIONES TAB DATOS

    $('#numeroDocumento').on('input', function () {
        if (validarDni(this.value)) {
            $('#labelDocumento').hide();
            dniValido = true;
        } else {
            $('#labelDocumento').show();
            dniValido = false;
        }
        checkTabDatos();
    });

    $('#razonSocial').on('input', function () {
        if (this.value != "") {
            $('#labelRazonSocial').hide();
            razonSocialValido = true;
        } else {
            $('#labelRazonSocial').show();
            razonSocialValido = false;
        }
        checkTabDatos();
    });

    $('#apellido2Titular').on('input', function () {
        if (this.value != "" && $('#apellido2Titular').val() != "" && $('#nombreTitular').val() != "") {
            $('#labelApellidos').hide();
            nombreApellidosValido = true;
        } else {
            $('#labelApellidos').show();
            nombreApellidosValido = false;
        }
        checkTabDatos();
    });

    $('#diaFechaNacimientoTitular').on('input', function () {
        date = ($('#diaFechaNacimientoTitular').val() + "-" + $('#mesFechaNacimientoTitular').val() + "-" + this.value).toString();
        if (date.match(patronDate) != null) {
            $('#labelFecha').hide();
            fechaValido = true;
        } else {
            $('#labelFecha').show();
            fechaValido = false;
        }
        checkTabDatos();
    });

    $('#mesFechaNacimientoTitular').on('input', function () {
        date = ($('#diaFechaNacimientoTitular').val() + "-" + $('#mesFechaNacimientoTitular').val() + "-" + this.value).toString();
        if (date.match(patronDate) != null) {
            $('#labelFecha').hide();
            fechaValido = true;
        } else {
            $('#labelFecha').show();
            fechaValido = false;
        }
        checkTabDatos();
    });

    $('#anioFechaNacimientoTitular').on('input', function () {
        date = ($('#diaFechaNacimientoTitular').val() + "-" + $('#mesFechaNacimientoTitular').val() + "-" + this.value).toString();
        if (date.match(patronDate) != null) {
            $('#labelFecha').hide();
            fechaValido = true;
        } else {
            $('#labelFecha').show();
            fechaValido = false;
        }
        checkTabDatos();
    });

    $('#numeroTelefonoTitular').on('input', function () {
        if (patronTlfMovil.test(this.value)) {
            $('#labelMovil').hide();
            movilValido = true;
        } else {
            $('#labelMovil').show();
            movilValido = false;
        }
        checkTabDatos();
    });

    $('#emailTitular').on('input', function () {
        if (patronEmail.test(this.value)) {
            $('#labelEmail').hide();
        } else {
            $('#labelEmail').show();
            emailValido = false;
        }
    });

    $('#emailTitularRepetido').on('paste', function (e) {
        e.preventDefault();
    });

    $('#emailTitularRepetido').on('input', function () {
        if (patronEmail.test(this.value)) {
            if (this.value == $('#emailTitular').val()) {
                $('#labelEmailRep').hide();
                emailValido = true;
            }
        } else {
            $('#labelEmailRep').show();
            emailValido = false;
        }
        checkTabDatos();
    });

    $('#checkoutConditions').on('change', function () {
        if (this.checked) {
            checkDatos = true;
        } else {
            checkDatos = false;
        }
        checkTabDatos();
    });
}

// DNI y CIF

var selectDni = function () {
    $('#contenedorCif').fadeOut();
    $('#labelCif').fadeOut();
    $('#labelDni').fadeIn();
    $('#contenedorFecha').fadeIn();
};

var selectCif = function () {
    $('#contenedorCif').fadeIn();
    $('#labelCif').fadeIn();
    $('#labelDni').fadeOut();
    $('#contenedorFecha').fadeOut();
};

var clearDireccionInstalacion = function () {
    $('#selectProvincia').selectpicker('val', '');
    $('#selectMunicipio').selectpicker('val', '');
    $('#selectCalle').selectpicker('val', '');
    $('#municipio').prop("value", "");
    $('#provincia').prop("value", "");
    $('#codigoPostalInstalacion').prop("value", "");
    $('#nombreCalleInstalacion').prop("value", "");
    $('#numeroCalleInstalacion').prop("value", "");
    $('#checkSinNumeroInstalacion').prop("checked", false);

};

function validarDni(value) {

    var validChars = 'TRWAGMYFPDXBNJZSQVHLCKET';
    var nifRexp = /^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKET]$/i;
    var nieRexp = /^[XYZ][0-9]{7}[TRWAGMYFPDXBNJZSQVHLCKET]$/i;
    var str = value.toString().toUpperCase();

    if (!nifRexp.test(str) && !nieRexp.test(str)) return false;

    var nie = str
        .replace(/^[X]/, '0')
        .replace(/^[Y]/, '1')
        .replace(/^[Z]/, '2');

    var letter = str.substr(-1);
    var charIndex = parseInt(nie.substr(0, 8)) % 23;

    if (validChars.charAt(charIndex) === letter) return true;

    return false;
}

// CHECK TODO VALIDADO

var checkTabDatos = function () {
    var ok = false;
    if ($('#selectTipoDocumento').val() == 1) {
        if (dniValido && fechaValido) {
            ok = true;
        }
    } else {
        if (dniValido && razonSocialValido) {
            ok = true;
        }
    }
    if (ok && movilValido && emailValido && checkDatos) {
        $('#btnContinuarConfiguracion').prop('disabled', false);
    } else {
        $('#btnContinuarConfiguracion').prop('disabled', true);
    }
};

// CAMBIO VALORES RESUMEN

var limpiarValoresColumnaTitular = function () {
    $('#nombreTitularColumna').text('');
    $('#razonSocialTitularColumna').text('');
    $('#dniTitularColumna').text('');
    $('#fechaNacimientoTitularColumna').text('');
    $('#emailTitularColumna').hide();
    $('#nombreTitularColumna').hide();
    $('#razonSocialTitularColumna').hide();
    $('#dniTitularColumna').hide();
    $('#fechaNacimientoTitularColumna').hide();
    $('#emailTitularColumna').hide();
}

var mostrarDivTitularColumna = function () {
    if ($('#selectTipoDocumento').val() == 2) {
        $('#razonSocialTitularColumna').show();
        $('#fechaNacimientoTitularColumna').hide();
    } else {
        $('#fechaNacimientoTitularColumna').show();
        $('#razonSocialTitularColumna').hide();
    }
    $('#nombreTitularColumna').show();
    $('#dniTitularColumna').show();
    $('#emailTitularColumna').show();
    $('#divInfoTitular').show();
}

var cambiarValoresDatosColumna = function () {
    limpiarValoresColumnaTitular();
    if ($('#selectTipoDocumento').val() == 2) {
        $('#razonSocialTitularColumna').text($('#razonSocial').val());
    } else {
        $('#fechaNacimientoTitularColumna').text(date.replace(/\-/g, '\/'));
    }
    $('#nombreTitularColumna').text($('#nombreTitular').val() + " " + $('#apellido1Titular').val() + " " + $('#apellido2Titular').val());
    $('#dniTitularColumna').text($('#numeroDocumento').val());
    $('#emailTitularColumna').text($('#emailTitular').val());
    mostrarDivTitularColumna();
};


// -------------------------------------- TAB CONFIGURAR --------------------------------------


var tabConfiguracion = function () {
    // Seleccionar Numero nuevo o antiguo
    $('#btnNumeroAntiguo').on('click', function () {
        checkNumeroAntiguo = true;
        checkNumeroNuevo = false;
        $('#btnContinuarPagos').prop('disabled', true);
        $('#btnNumeroNuevo').prop('checked', false);
        selectNumeroAntiguo();
        checkTabConfiguracion();
    });

    $('#btnNumeroNuevo').on('click', function () {
        checkNumeroAntiguo = false;
        checkNumeroNuevo = true;
        $('#btnNumeroAntiguo').prop('checked', false);
        $('#btnContinuarPagos').prop('disabled', false);
        selectNumeroNuevo();
    });

    // Seleccionar contrato o prepago
    $('#btnModalidadPrepago').on('click', function () {
        selectPrepago();
    });

    $('#btnModalidadContrato').on('click', function () {
        selectContrato();
    });

    // Seleccionar si es titular
    $('#comprobarTitularSi').on('click', function () {
        checkTabConfiguracion();
        titularSi();
    });

    $('#comprobarTitularNo').on('click', function () {
        checkTitular = false;
        checkTabConfiguracion();
    });
}

// COMPORTAMIENTO Número Teléfono

// Comprobar Titular

var titularSi = function () {
    var num = $('#numeroTelefonoTitular').val();
    $('#smsValidation').fadeIn();
    $('#inputCodigoSms').on('input', function () {
        if ($('#inputCodigoSms').val() != "") {
            if ($('#inputCodigoSms').val().length == 6) {
                $('#btnCheckCodigoSms').prop("disabled", false);
            }
        } else {
            $('#btnCheckCodigoSms').prop("disabled", true);
        }
    });
    $('#btnCheckCodigoSms').on('click', function () {
        comprobarCodigo($('#inputCodigoSms').val());
    });
    crearCodigo(num);
};

var crearCodigo = function (num) {
    if (!enviado) {
        start = new Date().getTime();
        codigo = Math.floor(Math.random() * (1000000 - 111111)) + 111111;
        //console.log(codigo);
        enviarSms(codigo, num);
    } else {
        elapsed = (new Date().getTime() - start) / 1000;
        if (elapsed > 120) {
            start = new Date().getTime();
            codigo = Math.floor(Math.random() * (1000000 - 111111)) + 111111;
            enviarSms(codigo, num);
        }
    }
}

var enviarSms = function (code, numTlf) {
    var data = { codigo: code, numTlf: numTlf };
    $.ajax({
        type: "POST",
        url: 'solicitudesWeb/formFtth.php',
        data: data,
        success: function (response) {
            //console.log(response);
            //console.log("SMS enviado correctamente");
            enviado = true;
        }
    });
}

var comprobarCodigo = function name(code) {
    if (codigo == code) {
        $('#smsValidation').fadeOut();
        $('#labelSmsNoValidado').fadeOut();
        $('#labelSmsValidado').fadeIn();
        checkTitular = true;
    } else {
        $('#labelSmsNoValidado').fadeIn();
    }
    checkTabConfiguracion();
}

// Tipo Modalidad

var selectPrepago = function () {
    $('#divIcc').fadeIn();
    $('#divSelectTitular').fadeIn();
};

var selectContrato = function () {
    $('#divIcc').fadeOut();
    $('#divSelectTitular').fadeIn();
};

var selectNumeroAntiguo = function () {
    $('#divMovil').fadeIn();
    $('#divModalidad').fadeIn();
};


var selectNumeroNuevo = function () {
    $('#divMovil').fadeOut();
    $('#divModalidad').fadeOut();
    $('#divSelectTitular').fadeOut();
    $('#smsValidation').fadeOut();
};


// VALIDACIONES TAB CONFIGURACION

$('#numeroTelefonoNuevo').on('input', function () {
    if (patronTlfFijo.test(this.value)) {
        $('#labelMovilNuevo').hide();
        movilNuevoValido = true;
    } else {
        $('#labelMovilNuevo').show();
        movilNuevoValido = false;
    }
    checkTabConfiguracion();
});


// CHECK TODO VALIDADO

var checkTabConfiguracion = function () {
    if (movilNuevoValido && checkTitular) {
        $('#btnContinuarPagos').prop('disabled', false);
    } else {
        $('#btnContinuarPagos').prop('disabled', true);
    }
};


// CAMBIO VALORES RESUMEN

var cambiarValoresConfiguracionColumna = function () {
    limpiarValoresColumnaPortabilidad();
    if (checkNumeroAntiguo) {
        $('#labelMovilPortabilidad').text("Fijo: " + $('#numeroTelefonoNuevo').val());
        $('#divDatosPortabilidad').show();
        $('#labelMovilPortabilidad').show();
    }
};

var limpiarValoresColumnaPortabilidad = function () {
    $('#labelMovilPortabilidad').text('');
    $('#divDatosPortabilidad').hide();
    $('#labelMovilPortabilidad').hide();
}


// -------------------------------------- TAB PAGOS --------------------------------------


var tabPagos = function () {
    // COMPORTAMIENTO Direccion Facturacion

    $('#btnDireccionSim').on('click', function () {
        checkNuevaDireccion = false;
        checkTabPagos();
        $('#labelInfoDireccion').show();
        $('#divDatosDireccionFacturacion').fadeOut();
    });

    $('#btnDireccionNueva').on('click', function () {
        checkNuevaDireccion = true;
        checkTabPagos();
        $('#labelInfoDireccion').hide();
        $('#divDatosDireccionFacturacion').fadeIn();
    });

    $('#codigoPostalDireccionFacturacion').on('input', function () {
        enableDireccionFacturacion();
    });

    $('#numeroCalleDireccionFacturacion').on('input', function () {
        if ($('#numeroCalleDireccionFacturacion').val() == "") {
            $('#btnValidarDireccionFacturacion').prop("disabled", true);
        } else {
            $('#btnValidarDireccionFacturacion').prop("disabled", false);
        }
    });

    $('#checkSinNumeroDireccionFacturacion').on('click', function () {
        $("#numeroCalleDireccionFacturacion").prop("disabled", this.checked);
        $('#btnValidarDireccionFacturacion').prop("disabled", !this.checked);
    });


    // VALIDACIONES TAB PAGOS

    // Validación Inputs de IBAN

    $('#accountCode').on('input', function () {
        iban = $('#accountCode').val() + $('#accountEntity').val() + $('#accountOffice').val() + $('#numCuenta1').val() + $('#numCuenta2').val() + $('#numCuenta3').val(); if (validarIban(iban)) {
            $('#labelIban').hide();
            ibanValido = true;
        } else {
            $('#labelIban').show();
            ibanValido = false;
        }
        checkTabPagos();
    });

    $('#accountEntity').on('input', function () {
        iban = $('#accountCode').val() + $('#accountEntity').val() + $('#accountOffice').val() + $('#numCuenta1').val() + $('#numCuenta2').val() + $('#numCuenta3').val(); if (validarIban(iban)) {
            $('#labelIban').hide();
            ibanValido = true;
        } else {
            $('#labelIban').show();
            ibanValido = false;
        }
        checkTabPagos();
    });

    $('#accountOffice').on('input', function () {
        iban = $('#accountCode').val() + $('#accountEntity').val() + $('#accountOffice').val() + $('#numCuenta1').val() + $('#numCuenta2').val() + $('#numCuenta3').val(); if (validarIban(iban)) {
            $('#labelIban').hide();
            ibanValido = true;
        } else {
            $('#labelIban').show();
            ibanValido = false;
        }
        checkTabPagos();
    });

    $('#numCuenta1').on('input', function () {
        iban = $('#accountCode').val() + $('#accountEntity').val() + $('#accountOffice').val() + $('#numCuenta1').val() + $('#numCuenta2').val() + $('#numCuenta3').val(); if (validarIban(iban)) {
            $('#labelIban').hide();
            ibanValido = true;
        } else {
            $('#labelIban').show();
            ibanValido = false;
        }
        checkTabPagos();
    });

    $('#numCuenta2').on('input', function () {
        iban = $('#accountCode').val() + $('#accountEntity').val() + $('#accountOffice').val() + $('#numCuenta1').val() + $('#numCuenta2').val() + $('#numCuenta3').val(); if (validarIban(iban)) {
            $('#labelIban').hide();
            ibanValido = true;
        } else {
            $('#labelIban').show();
            ibanValido = false;
        }
        checkTabPagos();
    });

    $('#numCuenta3').on('input', function () {
        iban = $('#accountCode').val() + $('#accountEntity').val() + $('#accountOffice').val() + $('#numCuenta1').val() + $('#numCuenta2').val() + $('#numCuenta3').val(); if (validarIban(iban)) {
            $('#labelIban').hide();
            ibanValido = true;
        } else {
            $('#labelIban').show();
            ibanValido = false;
        }
        checkTabPagos();
    });

    $('#btnCheckPrestacion').on('change', function () {
        if (this.checked) {
            checkPrestacion = true;
        } else {
            checkPrestacion = false;
        }
        checkTabPagos();
    });

    $('#btnValidarDireccionFacturacion').on('click', function () {
        // if Validar Dir fun
        direccionFacturacionValida = true;
        checkTabPagos();
        hideDireccionFacturacion();
    });

    $('#btnElegirOtraDireccionFacturacion').on('click', function () {
        direccionFacturacionValida = false;
        checkTabPagos();
        showDireccionFacturacion();
    });
};

var enableDireccionFacturacion = function () {
    if ($('#codigoPostalDireccionFacturacion').val() == "") {
        $('#nombreCalleDireccionFacturacion').prop("disabled", true);
        $('#numeroCalleDireccionFacturacion').prop("disabled", true);
        $('#municipioDF').prop("disabled", true);
        $('#provinciaDF').prop("disabled", true);
        $('#checkSinNumeroDireccionFacturacion').prop("disabled", true);
    } else {
        $('#nombreCalleDireccionFacturacion').prop("disabled", false);
        $('#numeroCalleDireccionFacturacion').prop("disabled", false);
        $('#municipioDF').prop("disabled", false);
        $('#provinciaDF').prop("disabled", false);
        $('#checkSinNumeroDireccionFacturacion').prop("disabled", false);
    }
};

var enableDireccionInstalacion = function () {
    if ($('#codigoPostalInstalacion').val() == "") {
        $('#nombreCalleInstalacion').prop("disabled", true);
        $('#numeroCalleInstalacion').prop("disabled", true);
        $('#municipio').prop("disabled", true);
        $('#provincia').prop("disabled", true);
        $('#checkSinNumeroInstalacion').prop("disabled", true);
    } else {
        $('#nombreCalleInstalacion').prop("disabled", false);
        $('#numeroCalleInstalacion').prop("disabled", false);
        $('#municipio').prop("disabled", false);
        $('#provincia').prop("disabled", false);
        $('#checkSinNumeroInstalacion').prop("disabled", false);
    }
};

var hideDireccionFacturacion = function () {
    $('#divDatosDireccionFacturacion').fadeOut();
    $('#divCompletarDireccionFacturacion').fadeIn();
};

var showDireccionFacturacion = function () {
    clearDireccionFacturacion();
    $('#divDatosDireccionFacturacion').fadeIn();
    $('#divCompletarDireccionFacturacion').fadeOut();
};

var clearDireccionFacturacion = function () {
    $('#codigoPostalDireccionFacturacion').prop("value", "");
    $('#nombreCalleDireccionFacturacion').prop("value", "");
    $('#numeroCalleDireccionFacturacion').prop("value", "");
    $('#municipioDF').prop("value", "");
    $('#provinciaDF').prop("value", "");
    $('#checkSinNumeroDireccionFacturacion').prop("checked", false);
};

// CHECK IBAN

function validarIban(IBAN) {

    //Se pasa a Mayusculas
    IBAN = IBAN.toUpperCase();
    //Se quita los blancos de principio y final.
    IBAN = IBAN.trim();
    IBAN = IBAN.replace(/\s/g, ""); //Y se quita los espacios en blanco dentro de la cadena

    var letra1, letra2, num1, num2;
    var isbanaux;
    var numeroSustitucion;
    //La longitud debe ser siempre de 24 caracteres
    if (IBAN.length != 24) {
        return false;
    }

    // Se coge las primeras dos letras y se pasan a números
    letra1 = IBAN.substring(0, 1);
    letra2 = IBAN.substring(1, 2);
    num1 = getnumIBAN(letra1);
    num2 = getnumIBAN(letra2);
    //Se sustituye las letras por números.
    isbanaux = String(num1) + String(num2) + IBAN.substring(2);
    // Se mueve los 6 primeros caracteres al final de la cadena.
    isbanaux = isbanaux.substring(6) + isbanaux.substring(0, 6);

    //Se calcula el resto, llamando a la función modulo97, definida más abajo
    resto = modulo97(isbanaux);
    if (resto == 1) {
        return true;
    } else {
        return false;
    }
}

function modulo97(iban) {
    var parts = Math.ceil(iban.length / 7);
    var remainer = "";

    for (var i = 1; i <= parts; i++) {
        remainer = String(parseFloat(remainer + iban.substr((i - 1) * 7, 7)) % 97);
    }

    return remainer;
}

function getnumIBAN(letra) {
    ls_letras = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return ls_letras.search(letra) + 10;
}

// CHECK TODO VALIDADO

var checkTabPagos = function () {
    if (ibanValido && direccionInstalacionValida) {
        if (checkNuevaDireccion) {
            if (direccionFacturacionValida) {
                if (checkPrestacion) {
                    $('#btnConfirmarCompra').prop('disabled', false);
                } else {
                    $('#btnConfirmarCompra').prop('disabled', true);
                }
            } else {
                $('#btnConfirmarCompra').prop('disabled', true);
            }
        } else {
            if (checkPrestacion) {
                $('#btnConfirmarCompra').prop('disabled', false);
            } else {
                $('#btnConfirmarCompra').prop('disabled', true);
            }
        }
    } else {
        $('#btnConfirmarCompra').prop('disabled', true);
    }
};


// -------------------------------------- FIN FORMULARIO --------------------------------------

var finForm = function () {
    $('#rowForm').hide();
    $('#formFinalText').show();
    $('#formFinalLink').show();
}