<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Expedientes | MAE</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<script type="text/javascript">window.url = {base_url:"<?php echo nombre_ruta_host(); ?>"};</script>
		<link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>css/font-awesome.min.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>css/ionicons.min.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>css/AdminLTE.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>plugins/iCheck/square/blue.css">
		<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.11.3.js"></script>
		<link rel="icon" href="<?php echo base_url(); ?>img/icon_school.png" type="image/png">
		<link rel="shortcut icon" href="<?php echo base_url(); ?>img/icon_school.png">
		<style>
			body{background-image:url("img/back.jpg") !important;}
			.button_login{background-color:#b4b7b9;margin-bottom:10px;border-color:#b4b7b9;font-weight: 500;font-size:14px;border-radius:10px !important;}
			.button_login_paciente{background-color:#3a98f9;margin-bottom:5px;border-color:#3a98f9;font-weight: 900;font-size:28px;border-radius:10px !important;}
		</style>
	</head>
	<body class="hold-transition login-page">
		<div id="msg_receptor" class="callout login-box" style="display:none;margin-bottom: -120px;">
			<h4 id="msg1_callout"></h4>
			<span id="msg2_callout"></span>
		</div>
		<div class="login-box" id="login_first">
			<div class="login-logo"><a href="<?php echo base_url(); ?>"><b style="color:#3a98f9">Expedientes médicos</b></a> <?php /*<img style="width: 165px;" src="<?php echo base_url(); ?>img/new/globo.png">*/ ?></div>
			<div class="login-box-body login_container">
				<form>
					<div class="row">
						<div class="col-xs-12">
							<button type="button" id="login_pwd" class="btn btn-primary btn-block btn-flat button_login">Entrar con contraseña</button>
							<button type="button" id="login_paciente" class="btn btn-primary btn-block btn-flat button_login_paciente">Ingreso con código único</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="login-box" style="display:none;" id="login_second">
			<div class="login-logo"><a href="<?php echo base_url(); ?>"><b style="color:#3a98f9">Expedientes médicos</b></a> <?php /*<img style="width: 165px;" src="<?php echo base_url(); ?>img/new/globo.png">*/ ?></div>
			<div class="login-box-body login_container">
				<p class="login-box-msg">Iniciar sesión</p>
				<form id="guprapHAmeMusTuStadraswef" method="post" enctype="multipart/form-data">
					<div class="form-group has-feedback">
						<?php $d="email"; ?>
						<input type="text" class="form-control" placeholder="Email" name="<?php echo $d; ?>" id="<?php echo $d; ?>">
						<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
					</div>
					<div class="form-group has-feedback">
						<?php $d="pwd"; ?>
						<input type="password" class="form-control" placeholder="Contraseña" name="<?php echo $d; ?>" id="<?php echo $d; ?>">
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>
					<div class="row">
						<div class="col-xs-8">
							<a href="javascript:;" class="back_login">Regresar</a>
							<div class="checkbox icheck">
								<?php $d="remember"; ?>
								<label><input name="<?php echo $d; ?>" id="<?php echo $d; ?>" type="checkbox"> Recordar usuario</label>
							</div>
						</div>
						<div class="col-xs-4">
							<button type="submit" class="btn btn-primary btn-block btn-flat" style="background-color:#3a98f9;border-color:#3a98f9;font-weight: 900;">Entrar</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="login-box" style="display:none;" id="login_code">
			<div class="login-logo"><a href="<?php echo base_url(); ?>"><b style="color:#3a98f9">Expedientes médicos</b></a> <?php /*<img style="width: 165px;" src="<?php echo base_url(); ?>img/new/globo.png">*/ ?></div>
			<div class="login-box-body login_container">
				<p class="login-box-msg">Iniciar sesión</p>
				<form id="login_with_code" method="post" enctype="multipart/form-data">
					<div class="form-group has-feedback">
						<?php $d="pwd_second"; ?>
						<input type="password" class="form-control" placeholder="Código único" name="<?php echo $d; ?>" id="<?php echo $d; ?>">
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>
					<div class="row">
						<div class="col-xs-8">
							<a href="javascript:;" class="back_login">Regresar</a>
							<div class="checkbox icheck">
								<?php $d="remember_second"; ?>
								<label><input name="<?php echo $d; ?>" id="<?php echo $d; ?>" type="checkbox"> Recordar usuario</label>
							</div>
						</div>
						<div class="col-xs-4">
							<button type="submit" class="btn btn-primary btn-block btn-flat" style="background-color:#3a98f9;border-color:#3a98f9;font-weight: 900;">Entrar</button>
						</div>
					</div>
				</form>
				<a href="#" id="show_forgot_password" style="color:#3a98f9">Obtener mi código</a><br>
			</div>
		</div>
		<div class="login-box" style="display:none;" id="forgot_pwd">
			<div class="login-logo"><a href="<?php echo base_url(); ?>"><b style="color:#3a98f9">Expedientes médicos</b></a> <?php /*<img style="width: 165px;" src="<?php echo base_url(); ?>img/new/globo.png">*/ ?></div>
			<div class="login-box-body login_container">
				<p class="login-box-msg">Obtener código único</p>
				<form id="forgot_pwd_form" method="post" enctype="multipart/form-data">
					<div class="form-group has-feedback">
						<?php $d="name"; ?>
						<input type="text" class="form-control" required placeholder="Nombre completo" name="<?php echo $d; ?>" id="<?php echo $d; ?>">
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>
					<div class="form-group has-feedback">
						<?php $d="email"; ?>
						<input type="email" class="form-control" required placeholder="Correo electrónico" name="<?php echo $d; ?>" id="<?php echo $d; ?>">
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>
					<div class="row">
						<div class="col-xs-8">
							<a href="javascript:;" class="back_login">Regresar</a>
						</div>
						<div class="col-xs-4">
							<button type="submit" class="btn btn-primary btn-block btn-flat" style="background-color:#3a98f9;border-color:#3a98f9;font-weight: 900;">Enviar</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<script src="<?php echo base_url(); ?>plugins/jQuery/jQuery-2.2.0.min.js"></script>
		<script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>plugins/iCheck/icheck.min.js"></script>
		<script>
			$(function () {
				$('input').iCheck({
					checkboxClass: 'icheckbox_square-blue',
					radioClass: 'iradio_square-blue',
					increaseArea: '20%' // optional
				});
			});
			$(document).ready(function(){
				$("#show_forgot_password").on("click",function(){
					$("#login_second").hide();
					$("#login_code").hide();
					$("#login_first").hide();
					$("#forgot_pwd").show();
				});
				$("#login_pwd").on("click",function(){
					$("#login_first").hide();
					$("#login_code").hide();
					$("#forgot_pwd").hide();
					$("#login_second").show();
				});
				$("#login_paciente").on("click",function(){
					$("#login_second").hide();
					$("#login_first").hide();
					$("#forgot_pwd").hide();
					$("#login_code").show();
				});
				$(".back_login").on("click",function(){
					$("#login_second").hide();
					$("#login_code").hide();
					$("#forgot_pwd").hide();
					$("#login_first").show();
				});
				/*
				$("#login_paciente").on("click",function(){
					$.post(window.url.base_url+"login/ctrlogin/login_guest",{},function(resp){
						resp=JSON.parse(resp);
						$("#msg_receptor").fadeOut(1400,function(){
							$("#msg_receptor").removeClass("callout-danger").removeClass("callout-success").removeClass("callout-warning").addClass("callout-"+resp.type_msg);
							$("#msg1_callout").html(resp.title);
							$(this).html(resp.msg);			
							$(this).show();
							$(this).fadeIn(1600);
						});
						if(resp.success!==false){
							setTimeout(function(){
								location.reload();
							},1400);
						}
					});
				});
				/* */
				$("#forgot_pwd_form").on("submit",function(evt){
					evt.preventDefault();
					$.post(window.url.base_url+"login/ctrlogin/check_usr_for_code",{data:$(this).serialize()},function(resp){
						resp=JSON.parse(resp);
						$("#msg_receptor").fadeOut(1400,function(){
							$("#msg_receptor").removeClass("callout-danger").removeClass("callout-success").removeClass("callout-warning").addClass("callout-"+resp.type_msg);
							$("#msg1_callout").html(resp.title);
							$(this).html(resp.msg);			
							$(this).show();
							$(this).fadeIn(1600);
						});
						/*if(resp.success!==false){
							setTimeout(function(){
								location.reload();
							},1500);
						}*/
					});
				});
				$("#guprapHAmeMusTuStadraswef").on("submit",function(evt){
					evt.preventDefault();
					$.post(window.url.base_url+"login/ctrlogin/login",{data:$(this).serialize()},function(resp){
						resp=JSON.parse(resp);
						$("#msg_receptor").fadeOut(1400,function(){
							$("#msg_receptor").removeClass("callout-danger").removeClass("callout-success").removeClass("callout-warning").addClass("callout-"+resp.type_msg);
							$("#msg1_callout").html(resp.title);
							$(this).html(resp.msg);			
							$(this).show();
							$(this).fadeIn(1600);
						});
						if(resp.success!==false){
							setTimeout(function(){
								location.reload();
							},1500);
						}
					});
				});
				$("#login_with_code").on("submit",function(evt){
					evt.preventDefault();
					$.post(window.url.base_url+"login/ctrlogin/login_with_code",{data:$(this).serialize()},function(resp){
						resp=JSON.parse(resp);
						$("#msg_receptor").fadeOut(1400,function(){
							$("#msg_receptor").removeClass("callout-danger").removeClass("callout-success").removeClass("callout-warning").addClass("callout-"+resp.type_msg);
							$("#msg1_callout").html(resp.title);
							$(this).html(resp.msg);			
							$(this).show();
							$(this).fadeIn(1600);
						});
						if(resp.success!==false){
							setTimeout(function(){
								location.reload();
							},1500);
						}
					});
				});
			});
		</script>
	</body>
</html>