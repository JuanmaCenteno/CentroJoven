<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    if ($_SESSION['dni'] != '') {
      header("Location: fichar.php");
      exit();
    }
    
    //include_once $_SERVER['DOCUMENT_ROOT'] . "/ProyectoEmpresa/conexDB.php";
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Formulario de Acceso</title>
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
        <form id="formData" class="needs-validation" novalidate>
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col">
                        <div class="card card-registration my-4">
                            <div class="row g-0">
                                <div class="col-xl-6 d-none d-xl-block">
                                    <img src="./resources/estudiantes.jpg"
                                        alt="Sample photo" class="imagen"
                                        style="border-top-left-radius: .25rem; border-bottom-left-radius: .25rem;" />
                                </div>
                                <div class="col-xl-6">
                                    <div class="card-body p-md-5 text-black">
                                        <h3 class="mb-5 text-uppercase">Formulario de acceso</h3>
                                        <div class="form-outline mb-4">
                                            <input type="text" id="email" name="email" class="form-control form-control-lg" required/>
                                            <label class="form-label" for="email">Email</label>
                                            <div class="form-notch">
                                                <div class="form-notch-leading" style="width: 9px;"></div>
                                                <div class="form-notch-middle" style="width: 40.2px;"></div>
                                                <div class="form-notch-trailing"></div>
                                            </div>
                                        </div>                                        
                                        <div class="form-outline mb-4">
                                            <input type="password" id="contrasena" name="contrasena" class="form-control form-control-lg" required/>
                                            <label class="form-label" for="contrasena">Contraseña</label>
                                            <div class="form-notch">
                                                <div class="form-notch-leading" style="width: 9px;"></div>
                                                <div class="form-notch-middle" style="width: 70.2px;"></div>
                                                <div class="form-notch-trailing"></div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end pt-3">
                                            <button id="btnLogin" type="submit" class="btn btn-warning btn-lg ms-2">Iniciar Sesión</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <script>
            (() => {
              'use strict';
            
              // Fetch all the forms we want to apply custom Bootstrap validation styles to
              const forms = document.querySelectorAll('.needs-validation');
            
              // Loop over them and prevent submission
              Array.prototype.slice.call(forms).forEach((form) => {
                form.addEventListener('submit', (event) => {
                  if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                  }
                  form.classList.add('was-validated');
                }, false);
              });
            })();
        </script>
        <script type="text/javascript" src="./js/login.js"></script>
    </body>