<?php
	if (!isset($_SESSION)) {
	    session_start();
	}
	
	
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
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
		<!-- Google Fonts -->
		<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
		<!-- MDB -->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.2.0/mdb.min.css" rel="stylesheet"/>
	</head>
	<body class="bg-black">
	<div class="container py-5 h-100 d-flex justify-content-center align-items-center">
		<form id="formData" class="needs-validation" novalidate>
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
											<a style = "margin-right: 5%; margin-top: 1.9%;" href ="register.php">No estás registrado?  Registrarme</a>
											<button id="btnLogin" type="submit" class="btn btn-warning btn-lg ms-2">Iniciar Sesión</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Modal Respuesta -->
		<div id="modal" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 id="tituloModal" class="modal-title"></h5>
						<button type="button" class="close" data-mdb-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p id="textoModal"></p>
					</div>
					<div id="footerModal" class="modal-footer">
						<button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>
			
		</form>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.1/mdb.min.js"></script>
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