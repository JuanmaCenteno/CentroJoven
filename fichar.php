<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    
    //echo $_SESSION['dni'];
    
    //include_once $_SERVER['DOCUMENT_ROOT'] . "/ProyectoEmpresa/conexDB.php";
    ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Fichar</title>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" />
        <!-- Google Fonts Roboto -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
        <!-- Custom styles -->
        <link rel="stylesheet" href="styles.css" />
        <!-- Font Awesome -->
        <link
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
            rel="stylesheet"
            />
        <!-- Google Fonts -->
        <link
            href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
            rel="stylesheet"
            />
        <!-- MDB -->
        <link
            href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.css"
            rel="stylesheet"
            />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    </head>
    <body class="bg-black">
        <div class="contenedor container py-5 h-100" style="position:relative; margin: 0 auto;">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="card-body p-md-5 text-black bg-white">
                    <h3 class="mb-5 text-uppercase" style="text-align: center">Centro Joven</h3>
                    <div class="row">
                        <button id="btnEntrada" type="submit" class="btn btn-success btn-lg ms-2">Entrada</button>
                    </div>
                    <div class="row mt-3">
                        <button id="btnSalida" type="submit" class="btn btn-danger btn-lg ms-2">Salida</button>
                    </div>
                </div>
                <div class="col">
                </div>
            </div>
        </div>
        </form>
        <script type="text/javascript" src="./js/fichar.js"></script>
    </body>