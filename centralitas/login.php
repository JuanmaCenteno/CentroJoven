<?php if (!isset($_SESSION)) {
    session_start();
}
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Centro Joven</title>
    <!-- mobile settings -->
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />
    <!-- WEB FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&amp;subset=latin,latin-ext,cyrillic,cyrillic-ext" rel="stylesheet" type="text/css" />
    <!-- CORE CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!--Bootstrap 4 y JQuery-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- Selectpicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <!-- CSS mio -->
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="container full" style="background-color: white; width: 80%; max-width: 5000px;margin-bottom: 2%; border-color: black; border-width: 10%;">        
    <form method="POST" id="formLogin">
        <div class="row">
            <div class="col">
                <label for="inputUser">Usuario</label>
                <input type="number" class="form-control" id="inputUser" name="user" placeholder="Introduce el usuario" required>
            </div>            
        </div>
        <div class="row">
        <div class="col">
                <label for="inputPassword">Contraseña</label>
                <input type="number" class="form-control" id="inputPassword" name="password" placeholder="Introduce la contraseña" required>
        </div>
        </div>
        <div class="row mt-5">
            <button type="submit" class="btn btn-block" style="background-color: #007bff; color:white">Iniciar Sesión</button>
        </div>

      <button class="botoncito btn btn-block" type="button">
        No eres miembro todavía? Regístrate!
      </button>
    </form>
  </div>

     <!-- SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="js/formVoluntarios.js"></script>
<body>
