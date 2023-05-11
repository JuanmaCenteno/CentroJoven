<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    
    //$_SESSION['id'] = "1";
    //$_SESSION['dni'] = "79165345N";

    include_once "./funciones/conexDB.php";

    $db = DB::getInstance();
    $dni = $_SESSION['dni'];
    $fechaComprobar = date("Y-m-d", time()) . "%";
    $disableEntrada = false;
    $disableSalida = false; 

    $query = "SELECT * FROM centrojoven.historial WHERE dniVoluntario like ? AND fechayhora like ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('ss',$dni, $fechaComprobar);
    $stmt->execute();
    $res = $stmt->get_result();
    $resultado = [];
    while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
        array_push($resultado, $row);
    }

    //print_r($resultado);

    if(empty($resultado)){
        $disableSalida = true;
    }else{
        if ($resultado[0]['tipo'] == "entrada") {
           $disableEntrada = true;
           $disableSalida = false;
        }
    }
    
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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body class="bg-black">
        <div class="contenedor container py-5 h-100" style="position:relative; margin: 0 auto;">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="card-body p-md-5 text-black bg-white">
                    <h3 class="mb-5 text-uppercase" style="text-align: center">Centro Joven</h3>
                    <div class="row">
                        <button id="btnEntrada" type="submit" class="btnPost btn btn-success btn-lg ms-2" <?php if ($disableEntrada) { ?> disabled  <?php } ?>>Entrada</button>
                    </div>
                    <div class="row mt-3">
                        <button id="btnSalida" type="submit" class="btnPost btn btn-danger btn-lg ms-2" <?php if ($disableSalida) { ?> disabled  <?php } ?>>Salida</button>
                    </div>
                    <?php if ($_SESSION['id'] == "1" || $_SESSION['id'] == 1) { ?>
                       <div class="row mt-3">
                            <button id="btnAdmin" type="submit" class="btnPost btn btn-primary btn-lg ms-2">Panel de Administración</button>
                        </div>
                    <?php } ?>
                    
                </div>
            </div>
        </div>
        </form>

        <!-- Modal Respuesta -->
        <div id="modal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 id="tituloModal" class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onClick="window.location.reload();">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="textoModal"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onClick="window.location.reload();">Cerrar</button>
                </div>
                </div>
            </div>
        </div>

        <!-- Fin Modal -->
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script type="text/javascript" src="./js/fichar.js"></script>
    </body>