<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['REVENDEDOR'])) {
    $_SESSION['REVENDEDOR'] = 1;
}
if (!isset($_SESSION['PLATAFORMA'])) {
    $_SESSION['PLATAFORMA'] = 'MOVIL';
}
$alta = filter_input(INPUT_GET, 'a', FILTER_SANITIZE_STRING);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Felicidades</title>
    <!--Bootstrap 4 y JQuery-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/style-valida.css">
    <!-- Dispositivos móviles -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
</head>

<body>
    <div class="container">
        <div class="row">
            <img class="imagen" src="resources/netvoz.png">
        </div>
        <div class="row">
            <div class="col-12">
                <h2>Felicidades</h2>
                <?php if ($alta == "SI") { ?>
                    <p>Su ICC ha sido validado correctamente</p>
                    <p>Se ha procedido al Alta de su línea en Netvoz</p>
                    <p>Le enviaremos un SMS cuando el proceso haya concluido y pueda insertar su SIM en su dispositivo.</p>
                <?php } else { ?>
                    <p>Su ICC ha sido validado correctamente</p>
                    <p>Hemos procedido a la solicitud de portabilidad de su línea a Netvoz.</p>
                    <p>Le envíaremos un SMS con la fecha de cambio, para que proceda a introducir su SIM en su dispositivo.</p>
                <?php } ?>
            </div>
        </div>
        <div class="row mt-3">
            <input id="btnValidar" type="button" class="btn btn-lg btn-success btn-block bot" value="Validar">
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('body').bind('touchmove', function(e) {
                e.preventDefault()
            });

            $('#btnValidar').on('click', function() {
                location.href = "https://www.netvoz.eu";
            });
        });
    </script>
</body>

</html>