	<body>
		<div class="container">
			<div class="fs-form-wrap" id="fs-form-wrap">
				<div class="fs-title">
					<h1>REGISTRO Cuestionarios Telcel</h1>
					<div class="codrops-top">
						<a class="codrops-icon codrops-icon-prev" id="login_button" href="javascript:;"><span>Iniciar sesión</span></a>
					</div>
				</div>
				<form id="myform" class="fs-form fs-form-full" autocomplete="off">
					<ol class="fs-fields">
						<li>
							<label class="fs-field-label fs-anim-upper" for="q1">Nombre completo</label>
							<input class="fs-anim-lower" id="q1" name="q1" type="text" placeholder="Nombre" required/>
						</li>
						<li>
							<label class="fs-field-label fs-anim-upper" for="q2" data-info="Email">Email</label>
							<input class="fs-anim-lower" id="q2" name="q2" type="email" placeholder="Email" required/>
						</li>
						<li>
							<label class="fs-field-label fs-anim-upper" for="q2" data-info="Email">Contraseña</label>
							<input class="fs-anim-lower" id="q2" name="q2" type="password" placeholder="Contraseña" required/>
						</li>
					</ol>
					<button class="fs-submit" type="submit">Enviar respuestas</button>
				</form>
			</div>
		</div>
		<script>
			$(document).ready(function(){
				$("#login_button").on("click",function(){
					window.location.replace(window.url.base_url+"login");
				});
			});
		</script>
		