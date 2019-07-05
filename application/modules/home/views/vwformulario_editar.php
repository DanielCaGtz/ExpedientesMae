	<div class="content-wrapper">
		<section class="content-header">
			<h1><?php echo $expediente["titulo"]; ?> <small><?php echo $expediente["subtitulo"]; ?></small></h1>
			<ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i> <?php echo $expediente["titulo"]; ?></a></li>
				<li class="active"><?php echo $expediente["subtitulo"]; ?></li>
			</ol>
		</section>

		<section class="content" id="main_content" data-expediente="<?php echo $expediente['id']; ?>">
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
				<?php $data_bloques=$controller->get_data("*","campos_bloques","expedientes_id='$id_expediente'","orden"); if($data_bloques!==FALSE) foreach($data_bloques AS $b=>$bloque){ ?>
				<div class="col-lg-6 col-xs-6">
					<div class="box <?php echo $expediente['color']; ?> box-solid">
						<div class="box-header with-border">
							<h3 class="box-title"><?php echo $bloque["nombre"]; ?></h3>
							<div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
						</div>
						<form role="form" class="form-contact formulario" style="background: #eaeaea;color: black;">
							<div class="box-body">
								<?php $data_campos=$controller->get_data("*","campos_expedientes","campos_bloques_id='".$bloque["id"]."' AND is_secretcode='0'","orden"); if($data_campos!==FALSE) foreach($data_campos AS $e=>$key){ ?>
								<div class="form-group">
							  		<label for="field_<?php echo $key['id']; ?>"><?php echo $key["nombre"]; ?></label>
							  		<?php echo $controller->get_field($key["id"],$id_expedientes_datos); ?>
								</div>
								<?php } ?>
						  	</div>
						  	<div class="box-footer"><button type="submit" class="btn <?php echo $expediente['color']; ?> enviar_formulario">Enviar</button></div>
						</form>
			  		</div>
				</div>
				<?php } ?>
			</div>
		</section>
	</div>