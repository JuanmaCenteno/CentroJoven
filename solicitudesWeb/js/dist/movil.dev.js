"use strict";

// Patrones de validación
var patronDate = /^(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d$/;
var patronTlfMovil = new RegExp("(\\+34|0034|34)?[ -]*(6|7)[ -]*([0-9][ -]*){8}");
var patronEmail = new RegExp("^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$"); // Variables de validación

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
var checkPrestacion = false; // Validación Sms

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
  }); // Botones de Navegación

  navegacion(); // Tab Datos

  datos(); // Tab Configuracion

  configuracion(); // Tab Pagos

  pagos(); // POST

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
      success: function success() {
        /*
        //alert('Formulario enviado');
        $('#rowForm').hide();
        $('#formFinalText').show();
        $('#formFinalLink').show();
        */
      }
    });
    return false;
  });
}); // NAVEGACIÓN

var navegacion = function navegacion() {
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
}; // -------------------------------------- TAB DATOS --------------------------------------


var datos = function datos() {
  // COMPORTAMIENTO
  $('#selectTipoDocumento').on('change', function () {
    if ($('#selectTipoDocumento').val() == 1) {
      selectDni();
    } else {
      selectCif();
    }
  }); // VALIDACIONES
  // Validaciones tabDatos

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
};

var selectDni = function selectDni() {
  $('#contenedorCif').fadeOut();
  $('#labelCif').fadeOut();
  $('#labelDni').fadeIn();
  $('#contenedorFecha').fadeIn();
};

var selectCif = function selectCif() {
  $('#contenedorCif').fadeIn();
  $('#labelCif').fadeIn();
  $('#labelDni').fadeOut();
  $('#contenedorFecha').fadeOut();
}; // VALIDACIONES
// Funcion Validar dni


function validarDni(value) {
  var validChars = 'TRWAGMYFPDXBNJZSQVHLCKET';
  var nifRexp = /^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKET]$/i;
  var nieRexp = /^[XYZ][0-9]{7}[TRWAGMYFPDXBNJZSQVHLCKET]$/i;
  var str = value.toString().toUpperCase();
  if (!nifRexp.test(str) && !nieRexp.test(str)) return false;
  var nie = str.replace(/^[X]/, '0').replace(/^[Y]/, '1').replace(/^[Z]/, '2');
  var letter = str.substr(-1);
  var charIndex = parseInt(nie.substr(0, 8)) % 23;
  if (validChars.charAt(charIndex) === letter) return true;
  return false;
} // Validar inputs tabDatos


var checkTabDatos = function checkTabDatos() {
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
}; // CAMBIO VALORES COLUMNA RESUMEN
// Limpiar Valores Columna


var limpiarValoresColumnaTitular = function limpiarValoresColumnaTitular() {
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
};

var mostrarDivTitularColumna = function mostrarDivTitularColumna() {
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
};

var cambiarValoresDatosColumna = function cambiarValoresDatosColumna() {
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
}; // -------------------------------------- TAB CONFIGURACIÓN --------------------------------------


var configuracion = function configuracion() {
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
    checkTabConfiguracion();
  }); // Seleccionar contrato o prepago

  $('#btnModalidadPrepago').on('click', function () {
    selectPrepago();
  });
  $('#btnModalidadContrato').on('click', function () {
    selectContrato();
  }); // Seleccionar si es titular

  $('#comprobarTitularSi').on('click', function () {
    titularSi();
  });
  $('#comprobarTitularNo').on('click', function () {
    checkTitular = false;
    checkTabConfiguracion();
  }); // Dirección Línea Ppal

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
  }); // BOTON VALIDAR DIRECCION PORTABILIDAD

  $('#btnValidarDireccion').on('click', function () {
    direccionValida = true;
    checkTabPagos();
    hideDireccionPpal();
  });
  $('#btnElegirOtraDireccion').on('click', function () {
    direccionValida = false;
    showDireccionPpal();
  }); // VALIDACIONES

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
}; // COMPORTAMIENTO Dirección Linea Ppal


var enableDireccion = function enableDireccion() {
  if ($('#codigoPostal').val() == "") {
    $('#nombreCalle').prop("disabled", true);
    $('#numeroCalle').prop("disabled", true);
    $('#checkSinNumero').prop("disabled", true);
  } else {
    $('#nombreCalle').prop("disabled", false);
    $('#numeroCalle').prop("disabled", false);
    $('#checkSinNumero').prop("disabled", false);
  }
};

var hideDireccionPpal = function hideDireccionPpal() {
  $('#divDatosDireccion').fadeOut();
  $('#divCompletarDireccion').fadeIn();
};

var showDireccionPpal = function showDireccionPpal() {
  clearDireccion();
  $('#divDatosDireccion').fadeIn();
  $('#divCompletarDireccion').fadeOut();
}; //Limpiar Direccion


var clearDireccion = function clearDireccion() {
  $('#codigoPostal').prop("value", "");
  $('#nombreCalle').prop("value", "");
  $('#numeroCalle').prop("value", "");
  $('#checkSinNumero').prop("checked", false);
}; // Comprobar Titular


var titularSi = function titularSi() {
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

var crearCodigo = function crearCodigo(num) {
  if (!enviado) {
    start = new Date().getTime();
    codigo = Math.floor(Math.random() * (1000000 - 111111)) + 111111;
    console.log(codigo); //enviarSms(codigo, num);
  } else {
    elapsed = (new Date().getTime() - start) / 1000;

    if (elapsed > 120) {
      start = new Date().getTime();
      codigo = Math.floor(Math.random() * (1000000 - 111111)) + 111111;
      enviarSms(codigo, num);
    }
  }
};

var enviarSms = function enviarSms(code, numTlf) {
  var data = {
    codigo: code,
    numTlf: numTlf
  };
  $.ajax({
    type: "POST",
    url: 'solicitudesWeb/formMovil.php',
    data: data,
    success: function success(response) {
      console.log(response);
      console.log("SMS enviado correctamente");
      enviado = true;
    },
    error: function error(e) {
      console.log("There was an error with your request...");
      console.log("error: " + JSON.stringify(e));
    }
  });
};

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
}; // Tipo Modalidad


var selectPrepago = function selectPrepago() {
  $('#divIcc').fadeIn();
  $('#divSelectTitular').fadeIn();
};

var selectContrato = function selectContrato() {
  $('#divIcc').fadeOut();
  $('#divSelectTitular').fadeIn();
};

var selectNumeroAntiguo = function selectNumeroAntiguo() {
  $('#numeroTelefonoNuevo').prop('value', $('#numeroTelefonoTitular').val());
  checkNumeroAntiguo = true;
  $('#divMovil').fadeIn();
  $('#divModalidad').fadeIn();
};

var selectNumeroNuevo = function selectNumeroNuevo() {
  $('#divMovil').fadeOut();
  $('#divModalidad').fadeOut();
  $('#divSelectTitular').fadeOut();
  $('#divIcc').fadeOut();
}; // VALIDACIONES
// Validar inputs TabConfiguracion


var checkTabConfiguracion = function checkTabConfiguracion() {
  if (movilNuevoValido && checkTitular) {
    $('#btnContinuarPagos').prop('disabled', false);
  } else {
    $('#btnContinuarPagos').prop('disabled', true);
  }
}; // CAMBIO VALORES COLUMNA RESUMEN
// Limpiar Valores Columna


var cambiarValoresConfiguracionColumna = function cambiarValoresConfiguracionColumna() {
  limpiarValoresColumnaPortabilidad();

  if (checkNumeroAntiguo) {
    $('#labelMovilPortabilidad').text("Móvil: " + $('#numeroTelefonoNuevo').val());
    $('#divDatosPortabilidad').show();
    $('#labelMovilPortabilidad').show();
  }
};

var limpiarValoresColumnaPortabilidad = function limpiarValoresColumnaPortabilidad() {
  $('#labelMovilPortabilidad').text('');
  $('#divDatosPortabilidad').hide();
  $('#labelMovilPortabilidad').hide();
}; // -------------------------------------- TAB PAGOS --------------------------------------


var pagos = function pagos() {
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
  }); // HIDE/SHOW

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
  }); // BOTON VALIDAR DIRECCION FACTURACION

  $('#btnValidarDireccionFacturacion').on('click', function () {
    direccionFacturacionValida = true;
    checkTabPagos();
    hideDireccionFacturacion();
  });
  $('#btnElegirOtraDireccionFacturacion').on('click', function () {
    direccionFacturacionValida = false;
    checkTabPagos();
    showDireccionFacturacion();
  }); // VALIDACIONES
  // Validación Inputs de IBAN

  $('#accountOwner').on('input', function () {
    iban = $('#accountCode').val() + $('#accountEntity').val() + $('#accountOffice').val() + $('#accountDC').val() + $('#accountAccountNumber').val();

    if (validarIban(iban)) {
      $('#labelIban').hide();
      ibanValido = true;
    } else {
      $('#labelIban').show();
      ibanValido = false;
    }

    checkTabPagos();
  });
  $('#accountCode').on('input', function () {
    iban = $('#accountCode').val() + $('#accountEntity').val() + $('#accountOffice').val() + $('#accountDC').val() + $('#accountAccountNumber').val();

    if (validarIban(iban)) {
      $('#labelIban').hide();
      ibanValido = true;
    } else {
      $('#labelIban').show();
      ibanValido = false;
    }

    checkTabPagos();
  });
  $('#accountEntity').on('input', function () {
    iban = $('#accountCode').val() + $('#accountEntity').val() + $('#accountOffice').val() + $('#accountDC').val() + $('#accountAccountNumber').val();

    if (validarIban(iban)) {
      $('#labelIban').hide();
      ibanValido = true;
    } else {
      $('#labelIban').show();
      ibanValido = false;
    }

    checkTabPagos();
  });
  $('#accountOffice').on('input', function () {
    iban = $('#accountCode').val() + $('#accountEntity').val() + $('#accountOffice').val() + $('#accountDC').val() + $('#accountAccountNumber').val();

    if (validarIban(iban)) {
      $('#labelIban').hide();
      ibanValido = true;
    } else {
      $('#labelIban').show();
      ibanValido = false;
    }

    checkTabPagos();
  });
  $('#accountDC').on('input', function () {
    iban = $('#accountCode').val() + $('#accountEntity').val() + $('#accountOffice').val() + $('#accountDC').val() + $('#accountAccountNumber').val();

    if (validarIban(iban)) {
      $('#labelIban').hide();
      ibanValido = true;
    } else {
      $('#labelIban').show();
      ibanValido = false;
    }

    checkTabPagos();
  });
  $('#accountAccountNumber').on('input', function () {
    iban = $('#accountCode').val() + $('#accountEntity').val() + $('#accountOffice').val() + $('#accountDC').val() + $('#accountAccountNumber').val();

    if (validarIban(iban)) {
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
}; // COMPORTAMIENTO Direccion Facturacion


var enableDireccionFacturacion = function enableDireccionFacturacion() {
  if ($('#codigoPostalDireccionFacturacion').val() == "") {
    $('#nombreCalleDireccionFacturacion').prop("disabled", true);
    $('#numeroCalleDireccionFacturacion').prop("disabled", true);
    $('#checkSinNumeroDireccionFacturacion').prop("disabled", true);
  } else {
    $('#nombreCalleDireccionFacturacion').prop("disabled", false);
    $('#numeroCalleDireccionFacturacion').prop("disabled", false);
    $('#checkSinNumeroDireccionFacturacion').prop("disabled", false);
  }
};

var hideDireccionFacturacion = function hideDireccionFacturacion() {
  $('#divDatosDireccionFacturacion').fadeOut();
  $('#divCompletarDireccionFacturacion').fadeIn();
};

var showDireccionFacturacion = function showDireccionFacturacion() {
  clearDireccionFacturacion();
  $('#divDatosDireccionFacturacion').fadeIn();
  $('#divCompletarDireccionFacturacion').fadeOut();
}; // Limpiar Direccion Facturacion


var clearDireccionFacturacion = function clearDireccionFacturacion() {
  $('#codigoPostalDireccionFacturacion').prop("value", "");
  $('#nombreCalleDireccionFacturacion').prop("value", "");
  $('#numeroCalleDireccionFacturacion').prop("value", "");
  $('#checkSinNumeroDireccionFacturacion').prop("checked", false);
}; // VALIDACIONES
// Funcion valida IBAN


function validarIban(IBAN) {
  //Se pasa a Mayusculas
  IBAN = IBAN.toUpperCase(); //Se quita los blancos de principio y final.

  IBAN = IBAN.trim();
  IBAN = IBAN.replace(/\s/g, ""); //Y se quita los espacios en blanco dentro de la cadena

  var letra1, letra2, num1, num2;
  var isbanaux;
  var numeroSustitucion; //La longitud debe ser siempre de 24 caracteres

  if (IBAN.length != 24) {
    return false;
  } // Se coge las primeras dos letras y se pasan a números


  letra1 = IBAN.substring(0, 1);
  letra2 = IBAN.substring(1, 2);
  num1 = getnumIBAN(letra1);
  num2 = getnumIBAN(letra2); //Se sustituye las letras por números.

  isbanaux = String(num1) + String(num2) + IBAN.substring(2); // Se mueve los 6 primeros caracteres al final de la cadena.

  isbanaux = isbanaux.substring(6) + isbanaux.substring(0, 6); //Se calcula el resto, llamando a la función modulo97, definida más abajo

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
} // Validar inputs tabPagos


var checkTabPagos = function checkTabPagos() {
  if (ibanValido && direccionValida) {
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