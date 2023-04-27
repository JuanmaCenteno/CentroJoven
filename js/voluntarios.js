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
                url: './funciones/formLogin.php',
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


var loadCombos = function (plataforma) {
    $.ajax({
        type: 'GET',
        url: 'api_datos.php',
        contentType: "text/plain",
        dataType: 'json',
        data: {
            accion: "tiposConexion",
            plataforma: plataforma
        },
        success: function (data) {
            myJsonData = data;
            //console.log(myJsonData);
            cargarComboVoluntarios(myJsonData);
        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });
}

var cargarComboVoluntarios = function (datos) {
    var combo2 = document.getElementById("selectCodigoCliente");
    for (var i = 0; i < datos.length; i++) {
        var opt = datos[i];
        var el = document.createElement("option");
        el.textContent = opt.apellidos;
        el.value = opt.id;
        combo2.appendChild(el);
    }
    $('#selectCodigoCliente').selectpicker('refresh');
};