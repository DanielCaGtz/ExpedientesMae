	<style>
		form{background: #eaeaea;color: black;}
		.float_right{float:right;}
		.float_left{float:left;}
		.align_right{text-align:right;}
		.align_left{text-align:left;}
		.w_20{width:20%;}.w_40{width:40%;}
	</style>
	<div class="content-wrapper">
		<section class="content-header">
			<h1>Cargar Reportes <small>Carga de Excel</small></h1>
			<ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i> Carga de archivos</a></li>
				<li class="active">Carga de reportes</li>
			</ol>
		</section>

		<section class="content" id="main_content">
			<div class="row">
				<div class="col-md-2">
					<div id="wait" style="display:none;" class="box box-warning box-solid">
						<div class="box-header">
							<h3 class="box-title title">Guardando</h3>
							<div class="box-tools pull-right"><button type="button" class="btn btn-box-tool close_wait"><i class="fa fa-close"></i></button></div>
						</div>
						<div class="box-body body">Por favor espere</div>
						<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-4 col-xs-4">
					<div class="box box-primary box-solid">
						<div class="box-header with-border">
							<h3 class="box-title">Cargar Excel para reporte</h3>
							<div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
						</div>
						<form role="form" class="form-contact" action="" id="send_form" name="send_form" method="post" enctype="multipart/form-data">
							<div class="box-body">
								<div class="form-group">
							  		<label>Cargar Excel</label>
							  		<input type="file" class="form-control" id="ruta_file" name="ruta_file" required>
								</div>
								<div class="form-group">
									<label>Expediente a reportar</label>
									<select class="form-control get_this_data" id="expedientes_id" required>
										<?php foreach($controller->get_data("*","expedientes","","titulo") AS $e => $key){ ?>
										<option value="<?php echo $key['id']; ?>"><?php echo $key['titulo']; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="form-group">
									<label>Nombre del archivo (sin espacios)</label>
									<input type="text" class="form-control get_this_data" id="nombre" required>
								</div>
								<div class="form-group">
									<label>Título del reporte</label>
									<input type="text" class="form-control get_this_data" id="titulo" required>
								</div>
								<div class="form-group align_right">
									<button type="button" class="btn btn-warning" id="add_new_field">Agregar</button>
								</div><br clear="all">
								<fieldset id="main_container"></fieldset>
						  	</div>
						  	<div class="box-footer"><button type="submit" class="btn btn-primary enviar_formulario">Enviar</button></div>
						</form>
			  		</div>
				</div>
			</div>
		</section>
	</div>