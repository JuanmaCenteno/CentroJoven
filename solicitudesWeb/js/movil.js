// Patrones de validación
var patronDate = /^(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d$/;
var patronTlfMovil = new RegExp("(\\+34|0034|34)?[ -]*(6|7)[ -]*([0-9][ -]*){8}");
var patronTlfFijo = new RegExp("(\\+34|0034|34)?[ -]*(8|9)[ -]*([0-9][ -]*){8}");
var patronEmail = new RegExp("^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$");

// Variables de validación
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
var checkFijo = true;
var tarjetaOk = false;
var fechTarjOk = false;
var tarj = false;

// Validación Sms
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
        loadCombo($('#idEmpresa').val(), "MOVIL");
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

    // Tab Datos
    datos();

    // Tab Configuracion
    configuracion();

    // Tab Pagos
    pagos();

    // POST
    $('#btnConfirmarCompra').on('click', function (e) {
        e.preventDefault();
        var formData = new FormData($('#formData')[0]);
        $.ajax({
            url: 'solicitudesWeb/formMovil.php',
            type: 'POST',
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            success: function () {
                //alert('Formulario enviado');
                $('#rowForm').hide();
                $('#formFinalText').show();
                $('#formFinalLink').show();
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
            cargarCombo(myJsonData);
        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });
};

var cargarCombo = function (datos) {
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

// NAVEGACIÓN
var navegacion = function () {
    $('#btnContinuarConfiguracion').on('click', function () {
        cambiarValoresDatosColumna();
        $('#myTabs li:eq(1) a').tab('show');
    });
    $('#btnContinuarPagos').on('click', function () {
        cambiarValoresConfiguracionColumna();
        $('#myTabs li:eq(2) a').tab('show');
    });
    $('#btnAtrasDatos').on('click', function () {
        $('#myTabs li:eq(0) a').tab('show');
    });
    $('#btnAtrasConfigurar').on('click', function () {
        $('#myTabs li:eq(1) a').tab('show');
    });
}



// -------------------------------------- TAB DATOS --------------------------------------

var datos = function () {
    // COMPORTAMIENTO
    $('#selectTipoDocumento').on('change', function () {
        if ($('#selectTipoDocumento').val() == 1) {
            selectDni();
        } else {
            selectCif();
        }
    });


    // VALIDACIONES
    // Validaciones tabDatos
    $('#numeroDocumento').on('input', function () {
        if ($('#selectTipoDocumento').val() == 1) { // ES PERSONA FISICA --> VALIDAMOS DNI
            console.log("ES PERSONA FISICA --> ", this.value);
            if (validarDni(this.value)) {
                $('#labelDocumento').hide();
                dniValido = true;
            } else {
                $('#labelDocumento').show();
                dniValido = false;
            }
        } else {                                    // ES PERSONA JURIDICA --> VALIDAMOS CIF
            console.log("ES PERSONA JURIDICA --> ", this.value);
            if (validateCIF(this.value)) {
                $('#labelDocumento').hide();
                dniValido = true;
            } else {
                $('#labelDocumento').show();
                dniValido = false;
            }
        }
        checkTabDatos();
    });
    $('#dniAdministrador').on('input', function () {
            console.log("ES PERSONA FISICA --> ", this.value);
            if (validarDni(this.value)) {
                $('#labelDniAdministrador').hide();
                dniAdminValido = true;
            } else {
                $('#labelDniAdministrador').show();
                dniAdminValido = false;
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
    // Número Fijo Mininet
    $('#numeroFijoMininet').on('input', function () {
        if (patronTlfFijo.test(this.value)) {
            $('#labelFijo').hide();
            comprobarFijo($('#numeroFijoMininet').val());
        } else {
            $('#labelFijo').show();
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

};

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

// VALIDACIONES

// Funcion Validar dni

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

/*Fuente de la informacion:
http://bulma.net/impresion.phtml?nIdNoticia=2248
http://www.trucomania.org/trucomania/truco.cgi?337&esp
http://es.wikipedia.org/wiki/N%C3%BAmero_de_identificaci%C3%B3n_fiscal#C.C3.A1lculo_de_la_letra_del_NIF

A - Sociedades Anónimas
B - Sociedades de responsabilidad limitada
C - Sociedades colectivas
D - Sociedades comanditarias
E - Comunidades de bienes
F - Sociedades cooperativas
G - Asociaciones y otros tipos no definidos
H - Comunidades de propietarios
J - Sociedades civiles, con o sin personalidad jurídica
K - Españoles menores de 14 años
L - Españoles residentes en el extranjero sin DNI
M - NIF que otorga la Agencia Tributaria a extranjeros que no tienen NIE
N - Entidades extranjeras
P - Corporaciones locales
Q - Organismos autónomos
R - Congregaciones e instituciones religiosas
S - Organos de la administración
U - Uniones Temporales de Empresas
V - Otros tipos no definidos en el resto de claves
W - Establecimientos permanentes de entidades no residentes en España
X - Extranjeros identificados por la Policía con un número de identidad de extranjero, NIE, asignado hasta el 15 de julio de 2008
Y - Extranjeros identificados por la Policía con un NIE, asignado desde el 16 de julio de 2008 (Orden INT/2058/2008, BOE del 15 de julio )
Z - Letra reservada para cuando se agoten los 'Y' para Extranjeros identificados por la Policía con un NIE

La ultima cifra es el dígito de control, que puede ser o bien un número o bien
una letra, en función del tipo de sociedad.
A las categorias P (Ayuntamientos) y X (Extranjeros) les corresponde una letra
en lugar de un número.

El dígito de control se calcula con las 7 cifras restantes del CIF (quitando la
primera y la ultima), con el siguiente algoritmo:

- CIF: A58818501
- Quitamos la primera y la ultima cifra:
	5881850
- Sumamos las cifras pares:
	Suma = 8 + 1 + 5 = 14
- Ahora sumamos cada cifra impar multiplicada por dos, y sumamos las cifras del
  resultado:
	5 * 2 = 10 ==> 1 + 0 = 1
	8 * 2 = 16 ==> 1 + 6 = 7
	8 * 2 = 16 ==> 1 + 6 = 7
	0 * 2 = 0 ==> 0
- y volvemos a sumar esos resultados a la suma anterior:
	Suma=Suma+1+7+7+0;
- Al final de este proceso, tenemos que Suma=29, pues bien, nos quedamos con la
  cifra de las unidades (9)
- Restamos esta cifra de las unidades de 10, dándonos un 1, que es el código de
  control para todos los tipos de sociedades exceptuando la X que se verifica
  como un DNI.
- Para las sociedades K, P, Q y S habria que sumar un 64 al digito de control que
  hemos calculado para hallar el ASCII de la letra de control:
	Chr(64+(10-(Suma mod 10)))*/


/*
 * Tiene que recibir el cif sin espacios ni guiones
 */
function validateCIF(cif)
{
    console.log("CIF RECIBIDO", cif);
    cif = cif.replace('-', '');
    cif = cif.replace(' ', '');
    console.log("CIF LIMPIO", cif);
    //Quitamos el primer caracter y el ultimo digito
    var valueCif=cif.substr(1,cif.length-2);

    var suma=0;

    //Sumamos las cifras pares de la cadena
    for(i=1;i<valueCif.length;i=i+2)
    {
        suma=suma+parseInt(valueCif.substr(i,1));
    }

    var suma2=0;

    //Sumamos las cifras impares de la cadena
    for(i=0;i<valueCif.length;i=i+2)
    {
        result=parseInt(valueCif.substr(i,1))*2;
        if(String(result).length==1)
        {
            // Un solo caracter
            suma2=suma2+parseInt(result);
        }else{
            // Dos caracteres. Los sumamos...
            suma2=suma2+parseInt(String(result).substr(0,1))+parseInt(String(result).substr(1,1));
        }
    }

    // Sumamos las dos sumas que hemos realizado
    suma=suma+suma2;

    var unidad=String(suma).substr(1,1)
    unidad=10-parseInt(unidad);

    var primerCaracter=cif.substr(0,1).toUpperCase();

    if(primerCaracter.match(/^[FJKNPQRSUVW]$/))
    {
        //Empieza por .... Comparamos la ultima letra
        if(String.fromCharCode(64+unidad).toUpperCase()==cif.substr(cif.length-1,1).toUpperCase())
            return true;
    }else if(primerCaracter.match(/^[XYZ]$/)){
        //Se valida como un dni
        var newcif;
        if(primerCaracter=="X")
            newcif=cif.substr(1);
        else if(primerCaracter=="Y")
            newcif="1"+cif.substr(1);
        else if(primerCaracter=="Z")
            newcif="2"+cif.substr(1);
        return validateDNI(newcif);
    }else if(primerCaracter.match(/^[ABCDEFGHLM]$/)){
        //Se revisa que el ultimo valor coincida con el calculo
        if(unidad==10)
            unidad=0;
        if(cif.substr(cif.length-1,1)==String(unidad))
            return true;
    }else{
        //Se valida como un dni
        return validateDNI(cif);
    }
    return false;
}

/*
 * Tiene que recibir el dni sin espacios ni guiones
 * Esta funcion es llamada
 */
function validateDNI(dni)
{
    var lockup = 'TRWAGMYFPDXBNJZSQVHLCKE';
    var valueDni=dni.substr(0,dni.length-1);
    var letra=dni.substr(dni.length-1,1).toUpperCase();

    if(lockup.charAt(valueDni % 23)==letra)
        return true;
    return false;
}

// COMPROBAR FIJO MININET

var comprobarFijo = function (numero) {
    $.ajax({
        type: 'GET',
        url: 'http://aiongest.internetinfraestructuras.es/solicitudesWeb/api_solicitudes.php',
        contentType: "text/plain",
        dataType: 'json',
        data: {
            accion: 'comprobarFijo',
            fijo: numero,
            plataforma: "FTTH"
        },
        success: function (data) {
            myJsonData = data;
            var lenght = Object.keys(myJsonData).length;
            if (lenght > 0) {
                checkFijo = true;
                $('#labelMininet').fadeOut();
            } else {
                checkFijo = false;
                $('#labelMininet').fadeIn();
            }
            checkTabDatos();
        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });
}

// Validar inputs tabDatos
var checkTabDatos = function () {
    var ok = false;
    if ($('#selectTipoDocumento').val() == 1) {
        if (dniValido && fechaValido) {
            ok = true;
        }
    } else {
        if (dniValido && razonSocialValido && dniAdminValido) {
            ok = true;
        }
    }
    if (ok && movilValido && emailValido && checkDatos && checkFijo) {
        $('#btnContinuarConfiguracion').prop('disabled', false);
    } else {
        $('#btnContinuarConfiguracion').prop('disabled', true);
    }
};

// CAMBIO VALORES COLUMNA RESUMEN

// Limpiar Valores Columna

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

// -------------------------------------- TAB CONFIGURACIÓN --------------------------------------

var configuracion = function () {
    // Seleccionar Numero nuevo o antiguo
    $('#btnNumeroAntiguo').on('click', function () {
        checkNumeroAntiguo = true;
        checkNumeroNuevo = false;
        $('#btnContinuarPagos').prop('disabled', true);
        $('#btnNumeroNuevo').prop('checked', false);
        selectNumeroAntiguo();
        checkTabConfiguracion();
        panelTarjeta(false);
    });
    $('#btnNumeroNuevo').on('click', function () {
        checkNumeroAntiguo = false;
        checkNumeroNuevo = true;
        $('#btnNumeroAntiguo').prop('checked', false);
        $('#btnContinuarPagos').prop('disabled', false);
        selectNumeroNuevo();
        panelTarjeta(true);
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
        titularSi();
    });
    $('#comprobarTitularNo').on('click', function () {
        checkTitular = false;
        checkTabConfiguracion();
    });
    // Dirección Línea Ppal
    $('#codigoPostal').on('input', function () {
        enableDireccion();
    });
    $('#numeroCalle').on('input', function () {
        if ($('#numeroCalle').val() == "") {
            $('#btnValidarDireccion').prop("disabled", true);
        } else {
            $('#btnValidarDireccion').prop("disabled", false);
        }
    });
    $('#checkSinNumero').on('click', function () {
        $("#numeroCalle").prop("disabled", this.checked);
        $('#btnValidarDireccion').prop("disabled", !this.checked);
    });

    // BOTON VALIDAR DIRECCION PORTABILIDAD
    $('#btnValidarDireccion').on('click', function () {
        direccionValida = true;
        checkTabPagos();
        hideDireccionPpal();
    });
    $('#btnElegirOtraDireccion').on('click', function () {
        direccionValida = false;
        showDireccionPpal();
    });

    // VALIDACIONES

    $('#numeroTelefonoNuevo').on('input', function () {
        if (patronTlfMovil.test(this.value)) {
            $('#labelMovilNuevo').hide();
            $('#comprobarTitularSi').prop("disabled", false);
            $('#comprobarTitularNo').prop("disabled", false);
            movilNuevoValido = true;
        } else {
            $('#labelMovilNuevo').show();
            movilNuevoValido = false;
        }
        checkTabConfiguracion();
    });

}

// COMPORTAMIENTO Dirección Linea Ppal

var enableDireccion = function () {
    if ($('#codigoPostal').val() == "") {
        $('#nombreCalle').prop("disabled", true);
        $('#numeroCalle').prop("disabled", true);
        $('#municipio').prop("disabled", true);
        $('#provincia').prop("disabled", true);
        $('#checkSinNumero').prop("disabled", true);
    } else {
        $('#nombreCalle').prop("disabled", false);
        $('#numeroCalle').prop("disabled", false);
        $('#municipio').prop("disabled", false);
        $('#provincia').prop("disabled", false);
        $('#checkSinNumero').prop("disabled", false);
    }
};

var hideDireccionPpal = function () {
    $('#divDatosDireccion').fadeOut();
    $('#divCompletarDireccion').fadeIn();
};

var showDireccionPpal = function () {
    clearDireccion();
    $('#divDatosDireccion').fadeIn();
    $('#divCompletarDireccion').fadeOut();
};

//Limpiar Direccion
var clearDireccion = function () {
    $('#codigoPostal').prop("value", "");
    $('#nombreCalle').prop("value", "");
    $('#municipio').prop("value", "");
    $('#provincia').prop("value", "");
    $('#numeroCalle').prop("value", "");
    $('#checkSinNumero').prop("checked", false);

};

// Comprobar Titular

var titularSi = function () {
    var num = $('#numeroTelefonoNuevo').val();
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
        url: 'solicitudesWeb/formMovil.php',
        data: data,
        success: function (response) {
            console.log(response);
            console.log("SMS enviado correctamente");
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
};

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
    $('#numeroTelefonoNuevo').prop('value', $('#numeroTelefonoTitular').val());
    if (patronTlfMovil.test($('#numeroTelefonoNuevo').val())) {
        $('#labelMovilNuevo').hide();
        $('#comprobarTitularSi').prop("disabled", false);
        $('#comprobarTitularNo').prop("disabled", false);
        movilNuevoValido = true;
    } else {
        $('#labelMovilNuevo').show();
        movilNuevoValido = false;
    }
    checkTabConfiguracion();
    $('#divMovil').fadeIn();
    $('#divModalidad').fadeIn();
};


var selectNumeroNuevo = function () {
    $('#divMovil').fadeOut();
    $('#divModalidad').fadeOut();
    $('#divSelectTitular').fadeOut();
    $('#divIcc').fadeOut();

};


// VALIDACIONES

// Validar inputs TabConfiguracion
var checkTabConfiguracion = function () {
    if (movilNuevoValido && checkTitular) {
        $('#btnContinuarPagos').prop('disabled', false);
    } else {
        $('#btnContinuarPagos').prop('disabled', true);
    }
};

// CAMBIO VALORES COLUMNA RESUMEN

// Limpiar Valores Columna

var cambiarValoresConfiguracionColumna = function () {
    limpiarValoresColumnaPortabilidad();
    if (checkNumeroAntiguo) {
        $('#labelMovilPortabilidad').text("Móvil: " + $('#numeroTelefonoNuevo').val());
        $('#divDatosPortabilidad').show();
        $('#labelMovilPortabilidad').show();
    }
};

var limpiarValoresColumnaPortabilidad = function () {
    $('#labelMovilPortabilidad').text('');
    $('#divDatosPortabilidad').hide();
    $('#labelMovilPortabilidad').hide();
};

// -------------------------------------- TAB PAGOS --------------------------------------

var pagos = function () {
    // COMPORTAMIENTO
    // Botónes Dirección Facturación
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
    // HIDE/SHOW
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
    // BOTON VALIDAR DIRECCION FACTURACION
    $('#btnValidarDireccionFacturacion').on('click', function () {
        direccionFacturacionValida = true;
        checkTabPagos();
        hideDireccionFacturacion();
    });
    $('#btnElegirOtraDireccionFacturacion').on('click', function () {
        direccionFacturacionValida = false;
        checkTabPagos();
        showDireccionFacturacion();
    });

    // VALIDACIONES
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


    // VALIDACIONES INPUT TARJETA CREDITO

    $('#numTarjeta').on('input', function () {
        var numT = $('#numTarjeta').val();

        if (numT.length >= 14) {
            console.log("TARJETA CREDITO >= 14 --> ", numT);
            if (validateCardNo(numT)) {
                $('#labelTarjeta').hide();
                tarjetaOk = true;
            } else {
                $('#labelTarjeta').show();
                tarjetaOk = false;
            }
        } else {
            $('#labelTarjeta').show();
            console.log("TARJETA CREDITO  < 14--> ", numT);
        }
        checkTarjeta();
    });

    $('#caducidad1').on('input', function () {
        var cad = $('#caducidad1').val();
        var cad2 = $('#caducidad2').val();
        if (cad.length == 2 && cad2.length == 2) {
            var fech = new Date('20' + cad2 + '-' + cad + "-28");
            var fSis = new Date();


            if (fSis.getTime() >= fech.getTime()) {
                $('#labelFech').show();
                fechTarjOk = false;
            } else {
                $('#labelFech').hide();
                fechTarjOk = true;
                $('#fechaCaducidad').prop('value', fech);
            }
        }
        checkTarjeta();
    });

    $('#caducidad2').on('input', function () {
        var cad = $('#caducidad1').val();
        var cad2 = $('#caducidad2').val();
        if (cad.length == 2 && cad2.length == 2) {
            var fech = new Date('20' + cad2 + '-' + cad + "-28");
            var fSis = new Date();
            if (fSis.getTime() >= fech.getTime()) {
                $('#labelFech').show();
                fechTarjOk = false;
            } else {
                $('#labelFech').hide();
                fechTarjOk = true;
                $('#fechaCaducidad').prop('value', fech);
            }
        }
        checkTarjeta();
    });

    $('#numCcv').on('input', function () {
        var ccv = $('#numCcv').val();
        if (ccv.length == 3 || ccv.length == 4) {
            checkTarjeta();
        } else {

        }
    });
};

// COMPORTAMIENTO Direccion Facturacion

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

var hideDireccionFacturacion = function () {
    $('#divDatosDireccionFacturacion').fadeOut();
    $('#divCompletarDireccionFacturacion').fadeIn();
};

var showDireccionFacturacion = function () {
    clearDireccionFacturacion();
    $('#divDatosDireccionFacturacion').fadeIn();
    $('#divCompletarDireccionFacturacion').fadeOut();
};

var panelTarjeta = function (ok) {
    if (ok) {
        $('#panelTarjeta').show();
        $('#hTarjeta').show();
        $('#numTarjeta').prop("required", true);
        $('#caducidad1').prop("required", true);
        $('#caducidad2').prop("required", true);
        $('#numCcv').prop("required", true);
    } else {
        $('#panelTarjeta').hide();
        $('#hTarjeta').hide();
        $('#numTarjeta').prop("required", false);
        $('#caducidad1').prop("required", false);
        $('#caducidad2').prop("required", false);
        $('#numCcv').prop("required", false);
    }
};

// Limpiar Direccion Facturacion
var clearDireccionFacturacion = function () {
    $('#codigoPostalDireccionFacturacion').prop("value", "");
    $('#nombreCalleDireccionFacturacion').prop("value", "");
    $('#numeroCalleDireccionFacturacion').prop("value", "");
    $('#municipioDF').prop("value", "");
    $('#provinciaDF').prop("value", "");
    $('#checkSinNumeroDireccionFacturacion').prop("checked", false);

};

// VALIDACIONES

// Funcion valida IBAN

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

// CHECK TARJETA CRÉDITO

var validateCardNo = function (no) {
    return (no && checkLuhn(no) &&
        no.length == 16 && (no[0] == 4 || no[0] == 5 && no[1] >= 1 && no[1] <= 5 ||
            (no.indexOf("6011") == 0 || no.indexOf("65") == 0)) ||
        no.length == 15 && (no.indexOf("34") == 0 || no.indexOf("37") == 0) ||
        no.length == 13 && no[0] == 4)
};

var checkLuhn = function (cardNo) {
    var s = 0;
    var doubleDigit = false;
    for (var i = cardNo.length - 1; i >= 0; i--) {
        var digit = +cardNo[i];
        if (doubleDigit) {
            digit *= 2;
            if (digit > 9)
                digit -= 9;
        }
        s += digit;
        doubleDigit = !doubleDigit;
    }
    return s % 10 == 0;
};

var checkTarjeta = function () {
    var ccv = $('#numCcv').val();
    if ((ccv.length == 3 || ccv.length == 4) && tarjetaOk && fechTarjOk) {
        tarj = true;
    } else {
        tarj = false;
    }
};

// Validar inputs tabPagos

var checkTabPagos = function () {
    console.log(
                "btnNumeroAntiguo --> ", $('#btnNumeroAntiguo').prop("checked"),
                "tarj --> ", tarj,
                "*ibanValido --> ", ibanValido,
                "*direccionValida --> ",  direccionValida,
                "checkNuevaDireccion --> ", checkNuevaDireccion,
                "direccionFacturacionValida --> ", direccionFacturacionValida,
                "*checkPrestacion --> ", checkPrestacion,
                ($('#btnNumeroAntiguo').prop("checked") == false && tarj),
                (checkNuevaDireccion && direccionFacturacionValida)
    );

    if (checkPrestacion && ibanValido && direccionValida) {
        if ($('#btnNumeroAntiguo').prop("checked") == false && tarj) {  // ALTA
            if (checkNuevaDireccion) {
                if (direccionFacturacionValida) {
                    $('#btnConfirmarCompra').prop('disabled', false);
                } else {
                    $('#btnConfirmarCompra').prop('disabled', true);
                }
            } else {
                $('#btnConfirmarCompra').prop('disabled', false);
            }
        } else {                                                        // PORTA
            if (checkNuevaDireccion) {
                if (direccionFacturacionValida) {
                    $('#btnConfirmarCompra').prop('disabled', false);
                } else {
                    $('#btnConfirmarCompra').prop('disabled', true);
                }
            } else {
                $('#btnConfirmarCompra').prop('disabled', false);
            }
        }

    } else {
        $('#btnConfirmarCompra').prop('disabled', true);
    }

};