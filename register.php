<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    if ($_SESSION['dni'] != '') {
        header("Location: login.php");
        exit();
    }
    
    ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Formulario Registro</title>
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
    <body>
        <section class="h-100 bg-dark">
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
                                            <h3 class="mb-5 text-uppercase">Formulario de registro</h3>
                                            <div class="row">
                                                <div class="col-md-12 mb-4">
                                                    <div class="form-outline">
                                                        <input type="text" id="nombreCompleto" name="nombreCompleto" class="form-control form-control-lg" required />
                                                        <label class="form-label" for="nombreCompleto">Nombre completo</label>
                                                        <div class="valid-feedback">Nombre correcto</div>
                                                        <div class="invalid-feedback">Introduzca el nombre.</div>
                                                        <div class="form-notch">
                                                            <div class="form-notch-leading" style="width: 9px;"></div>
                                                            <div class="form-notch-middle" style="width: 110.2px;"></div>
                                                            <div class="form-notch-trailing"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-4">
                                                    <div class="form-outline">
                                                        <input type="text" id="apellido1" name="apellido1" class="form-control form-control-lg" required />
                                                        <label class="form-label" for="apellido1">Primer apellido</label>
                                                        <div class="valid-feedback">Apellido correcto</div>
                                                        <div class="invalid-feedback">Introduzca el apellido.</div>
                                                        <div class="form-notch">
                                                            <div class="form-notch-leading" style="width: 9px;"></div>
                                                            <div class="form-notch-middle" style="width: 100.2px;"></div>
                                                            <div class="form-notch-trailing"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <div class="form-outline">
                                                        <input type="text" id="apellido2" name="apellido2" class="form-control form-control-lg" required />
                                                        <label class="form-label" for="apellido2">Segundo apellido</label>
                                                        <div class="valid-feedback">Apellido correcto</div>
                                                        <div class="invalid-feedback">Introduzca el apellido.</div>
                                                        <div class="form-notch">
                                                            <div class="form-notch-leading" style="width: 9px;"></div>
                                                            <div class="form-notch-middle" style="width: 110.2px;"></div>
                                                            <div class="form-notch-trailing"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-4">
                                                    <div class="form-outline">
                                                        <input type="text" id="dni" name="dni" class="form-control form-control-lg" required/>
                                                        <label class="form-label" for="dni">DNI</label>
                                                        <div class="valid-feedback">DNI correcto</div>
                                                        <div id="labelDni" class="invalid-feedback">Formato incorrecto del dni.</div>
                                                        <div class="form-notch">
                                                            <div class="form-notch-leading" style="width: 9px;"></div>
                                                            <div class="form-notch-middle" style="width: 30.2px;"></div>
                                                            <div class="form-notch-trailing"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <div class="form-outline">
                                                        <input type="date" id="fechaNacimiento" name="fechaNacimiento" class="form-control form-control-lg active" required/>
                                                        <label class="form-label" for="fechaNacimiento">Fecha de nacimiento</label>
                                                        <div class="valid-feedback">Fecha correcta</div>
                                                        <div class="invalid-feedback">Introduzca la fecha.</div>
                                                        <div class="form-notch">
                                                            <div class="form-notch-leading" style="width: 9px;"></div>
                                                            <div class="form-notch-middle" style="width: 130.2px;"></div>
                                                            <div class="form-notch-trailing"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-outline mb-4">
                                                <input type="text" id="email" name="email" class="form-control form-control-lg" required/>
                                                <label class="form-label" for="email">Email</label>
                                                <div class="valid-feedback">Email correcto</div>
                                                <div class="invalid-feedback">Introduzca el email.</div>
                                                <div class="form-notch">
                                                            <div class="form-notch-leading" style="width: 9px;"></div>
                                                            <div class="form-notch-middle" style="width: 40.2px;"></div>
                                                            <div class="form-notch-trailing"></div>
                                                        </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 mb-4">
                                                    <div class="form-outline">
                                                        <input type="password" id="contrasena" name="contrasena" class="form-control form-control-lg" required/>
                                                        <label class="form-label" for="contrasena">Contraseña</label>
                                                        <div class="valid-feedback">Contraseña correcta</div>
                                                        <div class="invalid-feedback">Introduzca la contraseña.</div>
                                                        <div class="form-notch">
                                                            <div class="form-notch-leading" style="width: 9px;"></div>
                                                            <div class="form-notch-middle" style="width: 75.2px;"></div>
                                                            <div class="form-notch-trailing"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-outline mb-4">
                                                <input type="text" id="direccion" name="direccion" class="form-control form-control-lg" required/>
                                                <label class="form-label" for="direccion">Dirección</label>
                                                <div class="valid-feedback">Dirección correcta</div>
                                                <div class="invalid-feedback">Introduzca la dirección.</div>
                                                <div class="form-notch">
                                                            <div class="form-notch-leading" style="width: 9px;"></div>
                                                            <div class="form-notch-middle" style="width: 70.2px;"></div>
                                                            <div class="form-notch-trailing"></div>
                                                        </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-4">
                                                    <div class="form-outline">
                                                        <input type="text" id="cpostal" name="cpostal" class="form-control form-control-lg" required/>
                                                        <label class="form-label" for="cpostal">Código postal</label>
                                                        <div class="valid-feedback">Código postal correcto</div>
                                                        <div class="invalid-feedback">Introduzca el código postal.</div>
                                                        <div class="form-notch">
                                                            <div class="form-notch-leading" style="width: 9px;"></div>
                                                            <div class="form-notch-middle" style="width: 90.2px;"></div>
                                                            <div class="form-notch-trailing"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <div class="form-outline">
                                                        <input type="text" id="movil" name="movil" class="form-control form-control-lg" minlength="9" maxlength="12" required/>
                                                        <label class="form-label" for="movil">Teléfono Móvil</label>
                                                        <div class="valid-feedback">Móvil correcto</div>
                                                        <div class="invalid-feedback">Introduzca el teléfono móvil.</div>
                                                        <div class="form-notch">
                                                            <div class="form-notch-leading" style="width: 9px;"></div>
                                                            <div class="form-notch-middle" style="width: 95.2px;"></div>
                                                            <div class="form-notch-trailing"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end pt-3">
                                                <a style = "margin-right: 5%; margin-top: 1.9%;" href ="login.php">Ya estás registrado? Iniciar sesión</a>
                                                <button id="btnRegister" type="submit" class="btn btn-warning btn-lg ms-2" disabled>Registro</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
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
        <script type="text/javascript" src="./js/registro.js"></script>
    </body>