$(document).ready(function () {
    // POST

    $('button').on('click',function (e) {
        
        e.preventDefault();        
        datos = JSON.stringify({ 'tipo': $(this).text()});
        console.log(datos);
        
        $.ajax({
            url: 'entradaSalida.php',
            type: 'POST',
            data: datos,
            async: false,
            cache: false,
            contentType: "application/json",
            processData: false,
            success: function (response) {
                
            }
        });        
    }); 


});
