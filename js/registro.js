$(document).ready(function () {
    // POST
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
                url: './funciones/formRegistro.php',
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                enctype: 'multipart/form-data',
                processData: false,
                success: function (response) {
                    //console.log(response.respuesta);
                    const myModal = new mdb.Modal(document.getElementById('modal'));
                    if(response.respuesta === "Duplicado"){
                        $('#tituloModal').text("ATENCIÓN");
                        $('#textoModal').text("Ya existe un usuario con ese DNI o email.");
                        //location.reload();
                    }else{
                        //alert("HOLA");
                        $('#tituloModal').text("ÉXITO");
                        $('#textoModal').text("Usuario creado correctamente.");
                        $('#footerModal').append('<button type="button" class="btn btn-secondary" onclick="window.location.href = \'./login.php\';">Iniciar Sesión</button>');
                        $('input').val('');
                    }
                    myModal.show();
                    //finForm();
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

    // Comportamiento de botones
    $('input').on('input', function () {
        if(this.value.length > 0){
            $(this).addClass("active");  
        }else{
            $(this).removeClass("active");
        }        
    });
    
    // Validación del DNI
    $('#dni').on('input', function () {
        if(this.value.length == 9){
            if (validarDni(this.value)) {
                $('#labelDni').hide();
                $(':input[type="submit"]').prop('disabled', false);
            } else {
                $('#labelDni').show();
                $(':input[type="submit"]').prop('disabled', true);
            }
        }        
    });    
});

// Función que valida DNI
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