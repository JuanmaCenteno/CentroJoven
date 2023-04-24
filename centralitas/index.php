<?php if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['REVENDEDOR'])) {
    $_SESSION['REVENDEDOR'] = 1;
} ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario Registro Centralitas</title>
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
</head>

<body style="background-color: white;">
    <!-- WRAPPER -->
    <div id="wrapper">

        <section id="middle">
            <!-- page title -->
            <!-- /page title -->
            <div class="container full" style="background-color: white; width: 80%; max-width: 5000px;margin-bottom: 2%; border-color: black; border-width: 10%;">
                <h3 style="text-align: center; padding-top: 1%;">Registro de Voluntario</h3>
                <hr />
                <form method="POST" id="formData">
                    <div class="form-group">
                        <h5>ID</h5>
                        <label for="selectCodigoCliente">Cliente</label>
                        <select class="selectpicker ml-3 w-75" id="selectCodigoCliente" name="codigoCliente" title="Nada seleccionado" required data-live-search="true">
                        </select>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="inputNumeroTelefono">Número de Teléfono</label>
                            <input type="number" class="form-control" id="inputNumeroTelefono" name="numeroTelefono" placeholder="Introduce el número de teléfono" required>
                        </div>
                        <div class="col">
                            <label for="inputNumeroTelefono">Número de Teléfono 2</label>
                            <input type="number" class="form-control" id="inputNumeroTelefono2" name="numeroTelefono2" placeholder="Introduce el número de teléfono">
                        </div>
                        <div class="col">
                            <label for="inputNumeroTelefono">Número de Teléfono 3</label>
                            <input type="number" class="form-control" id="inputNumeroTelefono3" name="numeroTelefono3" placeholder="Introduce el número de teléfono">
                        </div>
                    </div>
                    <hr />
                    <h5>IDC</h5>
                    <div class="row">
                        <div class="col">
                            <label for="inputNombreSede">Nombre Sede</label>
                            <input type="text" class="form-control" id="inputNombreSede" name="nombreSede" placeholder="Introduce el nombre de la sede" required>
                        </div>
                        <div class="col">
                            <label for="inputLocucionIDC">Locución</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="locucionIDC" id="inputLocucionIDC">
                                <label class="custom-file-label" for="inputLocucionIDC">Selecciona un fichero de locución</label>
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-6">
                            <label>Tipo Conexión</label>
                            <select class="selectpicker ml-3" id="selectTipoConexion" name="tipoConexion" title="Nada seleccionado" required>
                            </select>
                        </div>
                        <div class="col-6">
                            <label>Tipo Electrónica</label>
                            <select class="selectpicker ml-3" id="selectTipoElectronica" name="tipoElectronica" title="Nada seleccionado" required>
                            </select>
                        </div>
                    </div>
                    <hr />
                    <h5>IDCD</h5>
                    <div class="row mt-4">
                        <div class="col-2">
                            <label for="selectNumDepartamentos">Nº Departamentos</label>
                        </div>
                        <div class="col-4">
                            <select id="selectNumDepartamentos" title="" class="selectpicker w-25" data-live-search="true" data-dropup-auto="false">
                                <option>-</option>
                            </select>
                        </div>
                    </div>
                    <div id="departamentos"></div>
                    <hr />
                    <h5>Dialplans</h5>
                    <div class="row mt-4">
                        <div class="col-1">
                            <label for="selectNumLineas">Nº Líneas</label>
                        </div>
                        <div class="col-4">
                            <select id="selectNumLineas" title="" class="selectpicker w-25" data-live-search="true" data-dropup-auto="false">
                                <option>-</option>
                            </select>
                        </div>
                    </div>
                    <div id="lineas">
                    </div>
                    <div class="row mt-5">
                        <button type="submit" class="btn btn-block" style="background-color: #007bff; color:white">Registrar</button>
                    </div>
                    <input type="text" id="action" name="action" style="display: none;" value="insert">
                    <input type="text" id="inputCif" name="cifCliente" style="display: none;" value="">
                    <!--
                    <input type="text" id="inputPlataforma" name="plataforma" style="display: none;" value="<?php echo $_SESSION['PLATAFORMA'] ?>">
                    <input type="text" id="inputRevendedor" name="revendedor" style="display: none;" value="<?php echo $_SESSION['REVENDEDOR'] ?>">
                    -->
                </form>
            </div>
        </section>
        <!-- /MIDDLE -->
    </div>

    <!-- SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="js/formVoluntarios.js"></script>
</body>

</html>