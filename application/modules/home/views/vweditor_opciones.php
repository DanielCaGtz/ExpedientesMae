		<?php
			$data_expedientes=$controller->get_data("*","expedientes","active='1'","orden");
		?>
		<style>
			#sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
			#sortable .row { cursor:move; margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 50px; }
			#sortable li span { position: absolute; margin-left: -1.3em; }
			.row_childs{background:#fff;}
			.example-modal .modal {position: relative;top: auto;bottom: auto;right: auto;left: auto;display: block;z-index: 1;}
			.z9999{z-index:99999 !important;}
			.example-modal .modal {background: transparent !important;}
			.lateral_icon{margin-top: 2px;margin-right: 5px;}
			.f15{font-size: 15px;}
			.layout_border{border: 3px solid #00a65a;border-radius: 4px;}
			.modal-header .close{margin-left: 10px;}
			.invisible{display:none;}
		</style>
		<script src="<?php echo base_url(); ?>js/modernizr.custom.js"></script>
		<script src="<?php echo base_url(); ?>js/jquery-1.10.2.min.js"></script>
		<script src="<?php echo base_url(); ?>js/jquery-ui.min.js"></script>
		<script src="<?php echo base_url(); ?>js/jquery.ui.touch-punch.min.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/component.css" />
		<div class="content-wrapper">
			<section class="content-header"><section class="content-header"><h1>Editor <small>Opciones</small></h1></section></section>
			<section class="content">
				<div class="row">
					<div class="col-md-4">
						<div class="box box-primary box-solid">
							<div class="box-header with-border"><h3 class="box-title">Campos múltiples</h3></div>
							<div class="box-body">
								<div class="form-group">
									<label>Campos con múltiples opciones</label>
									<?php $data=$controller->get_data_from_query("SELECT ppal.id, ppal.nombre, exp.titulo, exp.subtitulo FROM campos_expedientes AS ppal INNER JOIN cat_campos AS cat ON ppal.cat_campos_id=cat.id INNER JOIN expedientes AS exp ON ppal.expedientes_id=exp.id WHERE cat.is_multiple='1' AND exp.active='1' ORDER BY ppal.campos_bloques_id, ppal.orden ;"); ?>
									<select class="form-control" id="campos">
										<option selected value="0">Seleccione un campo</option>
										<?php if($data!==FALSE){ foreach($data AS $e=>$key){ ?>
										<option value="<?php echo $key['id']; ?>"><?php echo $key["nombre"]." - ".$key["titulo"]; ?></option>
										<?php }} ?>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 invisible definir_opciones_parent_div">
						<div class="box box-primary box-solid">
							<div class="box-header with-border"><h3 class="box-title">Definir opciones</h3></div>
							<div class="box-body">
								<div class="form-group">
									<div class="radio"><label><input type="radio" name="opciones" class="opciones" value="0">Editar opciones manualmente</label></div>
									<div class="radio"><label><input type="radio" name="opciones" class="opciones" value="1">Elegir opciones de catálogo</label></div>
								</div>
								<div class="form-group agregar_opcion_div invisible">
									<button type="button" class="btn btn-block btn-primary" id="add_new_opc">Agregar opción</button>
								</div>
								<div class="form-group expediente_catalogo_div invisible">
									<label>Elija el expediente que servirá como catálogo</label>
									<select class="form-control" id="expedientes">
										<option selected value="0">Seleccione un expediente</option>
										<?php if($data_expedientes!==FALSE){ foreach($data_expedientes AS $e=>$key){ ?>
										<option value="<?php echo $key['id']; ?>"><?php echo $key["titulo"]; ?></option>
										<?php }} ?>
									</select>
								</div>
								<div class="form-group campo_opcion_div invisible">
									<label>Elija el campo que servirá como OPCIÓN</label>
									<select class="form-control" id="campo_texto" data-id="0">
										<option data-type="0" selected value="0">Seleccione un campo</option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4 invisible definir_opciones_child_div">
						<div class="box box-primary box-solid">
							<div class="box-header with-border"><h3 class="box-title">Definir opciones</h3></div>
							<div class="box-body" id="sortable">
								<div class="row row_childs">
									<div class="col-lg-11">
										<div class="input-group">
											<span class="input-group-addon">Opción</span><input type="text" data-id="0" class="form-control text_opcion">
										</div>
									</div>
									<div class="col-lg-1"><a class="eliminar_opcion btn"><i class="fa fa-remove"></i></a></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row invisible button_final">
					<div class="col-md-4">
						<div class="form-group"><a class="btn btn-app" id="save"><i class="fa fa-save"></i> Guardar</a></div>
					</div>
				</div>
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
			</section>
		</div>
