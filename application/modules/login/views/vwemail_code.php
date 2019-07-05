<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Codigo de acceso</title>
  <meta name="keywords" content="free website templates, free bootstrap themes, free template, free bootstrap, free website template">
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Satisfy|Bree+Serif|Candal|PT+Sans">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/style.css">
  <style>
  	address,h4,p, h5, h2, .credits{color: #333 !important;}
  </style>
</head>

<body>
  <!-- footer -->
  <footer class="footer text-center" style="background:#79a5e6 !important;">
	<div class="footer-top">
	  <div class="row">
		<div class="col-md-offset-3 col-md-6 text-center">
		  <div class="widget">
			<h4 class="widget-title"><img src="<?php echo base_url(); ?>img/logo.png" style="width: 300px;"></h4>
			<address><h4>Codigo de acceso:</h4></address>
			<h2><?php echo $code; ?></h2>
			<h5>Con este codigo podras entrar al sistema y consultar tus datos</h5><br><br>
			<p><a target="_blank" href="<?php echo base_url(); ?>" style="color:#bd3a3a;">Regresar al sistema</a></p>
			<div class="social-list">
			  <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
			  <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
			</div>
			<p class="copyright clear-float"></p>
		  </div>
		</div>
	  </div>
	</div>
  </footer>

</body>

</html>
