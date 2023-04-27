$(document).ready(function () {
    // POST

    $('button').on('click',function (e) {

        if($(this).text() == "Panel de Administración"){
            window.location.href = "crudVoluntarios.php";
        }else {
            e.preventDefault();        
            datos = JSON.stringify({ 'tipo': $(this).text()});
            console.log(datos);
            
            $.ajax({
                url: './funciones/entradaSalida.php',
                type: 'POST',
                data: datos,
                async: false,
                cache: false,
                contentType: "application/json",
                processData: false,
                success: function (response) {
                    
                }
            });  
        }              
    });
});
