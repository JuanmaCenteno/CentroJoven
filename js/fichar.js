$(document).ready(function () {
    // POST

    // Comportamiento botón o de Entrada/Salida o cambio a panel de administración
    $('.btnPost').on('click',function (e) {
        if($(this).text() == "Panel de Administración"){
            window.location.href = "crudVoluntarios.php";
        }else {
            e.preventDefault();        
            datos = JSON.stringify({ 'tipo': $(this).text()});
            //console.log(datos);
            // Post que manda a la BD entrada o salida
            $.ajax({
                url: './funciones/entradaSalida.php',
                type: 'POST',
                data: datos,
                async: false,
                cache: false,
                contentType: "application/json",
                processData: false,
                success: function (response) {
                    //console.log(response);
                    $('#tituloModal').text("ATENCIÓN");
                    $('#textoModal').text(response.mensaje);
                    $('#modal').modal('show');
                    //location.reload();
                }
            });  
        }              
    });
});
