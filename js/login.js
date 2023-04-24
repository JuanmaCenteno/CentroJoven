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
var tarjetaOk = false;
var fechTarjOk = false;
var tarj = false;



$(document).ready(function () {
    // POST
    
    $('input').on('input', function () {
        if(this.value.length > 0){
            $(this).addClass("active");  
        }else{
            $(this).removeClass("active");
        }        
    });

    $('#formData').submit(function (e) {
        // Obtener los campos requeridos
        var camposRequeridos = document.querySelectorAll('[required]');
        
        // Validar cada campo requerido
        var valido = true;
        for (var i = 0; i < camposRequeridos.length; i++) {
            if (!camposRequeridos[i].value) {
            valido = false;
            break;
            }
        }
        
        // Si todos los campos requeridos están completos, enviar el formulario
        if (valido) {
            e.preventDefault();
            var formData = new FormData($('#formData')[0]);        
            $.ajax({
                url: 'formLogin.php',
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                enctype: 'multipart/form-data',
                processData: false,
                success: function (response) {
                    //console.log(response);
                    if(response.length == 0 ){
                        alert("Email o contraseña erróneos");
                    }else{
                        //alert("Usuario encontrado");
                        window.location.href = "fichar.php";
                    }
                }
            });
            return false;
        } else{
            alert("Tiene campos sin rellenar");
        }
    }); 
});
