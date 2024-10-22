$(document).ready(function () {
    // POST
    // Control de validación
    $('input').on('input', function () {
        if(this.value.length > 0){
            $(this).addClass("active");  
        }else{
            $(this).removeClass("active");
        }        
    });

    // Submit del formulario
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
                        const myModal = new mdb.Modal(document.getElementById('modal'));
                        $('#tituloModal').text("ATENCIÓN");
                        $('#textoModal').text("Email o contraseña erróneos.");
                        $('#contrasena').val('');
                        myModal.show();
                    }else{
                        //alert("Usuario encontrado");
                        window.location.href = "fichar.php";
                    }
                }
            });
            return false;
        } else{
            const myModal = new mdb.Modal(document.getElementById('modal'));
            $('#tituloModal').text("ATENCIÓN");
            $('#textoModal').text("Tiene campos sin rellenar.");
            myModal.show();
        }
    }); 
});
