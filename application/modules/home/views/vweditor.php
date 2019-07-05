		<?php
			$s=FALSE; $data=$controller->get_data("*","expedientes","active='1'","orden","","");
			$cat_campos_data=$controller->get_data("*","cat_campos","active='1'","id");
		?>
		<script src="<?php echo base_url(); ?>js/modernizr.custom.js"></script>
		<script src="<?php echo base_url(); ?>js/jquery-1.10.2.min.js"></script>
		<script src="<?php echo base_url(); ?>js/jquery-ui.min.js"></script>
		<script src="<?php echo base_url(); ?>js/jquery.ui.touch-punch.min.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/component.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>plugins/iCheck/all.css">
		<style>
			.example-modal .modal {position: relative;top: auto;bottom: auto;right: auto;left: auto;display: block;z-index: 1;}
			.z9999{z-index:99999 !important;}
			.example-modal .modal {background: transparent !important;}
			.lateral_icon{margin-top: 2px;margin-right: 5px;}
			.f15{font-size: 15px;}
			.layout_border{border: 3px solid #00a65a;border-radius: 4px;}
			.modal-header .close{margin-left: 10px;}
			#sortable, .connectedSortable_child{list-style-type: none;}
			#sortable li, .connectedSortable_child li{cursor: move;}
			label{color: #333;}
			.md-close{border: none;padding: 0.6em 1.2em;background: #c0392b;color: #fff;font-family: 'Lato', Calibri, Arial, sans-serif;font-size: 1em;letter-spacing: 1px;text-transform: uppercase;cursor: pointer;display: inline-block;margin: 3px 2px;border-radius: 2px;}
			.md-close:hover{background: #A5281B;}
			.a_colores_active, .a_icons_active{border: 4px solid #ff0000;border-radius: 4px;}
			.icon{color: white;text-align: center;font-size: x-large;}
		</style>
		<div class="md-modal md-effect-1" id="modal-1">
			<div class="md-content">
				<h3>Elegir color</h3>
				<div style="max-height: 500px;overflow-y: scroll;">
					<ul class="list-unstyled clearfix" id="colores_ul">
						<?php foreach($controller->get_data("*","colores","active='1'") AS $e => $key){ ?>
						<li class="li_layout" style="float:left; width: 33.33333%; padding: 5px;height: 90px;">
							<a href="javascript:void(0);" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4);" class="clearfix a_colores full-opacity-hover" data-clase="<?php echo $key['clase']; ?>">
								<div><span style="display:block; width: 100%; float: left; height: 30px; background: #<?php echo $key["color"]; ?>;"></span></div>
							</a>
							<p class="text-center no-margin"><?php echo $key["nombre"]; ?></p>
						</li>
						<?php } ?>
					</ul>
					<button class="md-close">Guardar</button>
				</div>
			</div>
		</div>
		<div class="md-modal md-effect-1" id="modal-2">
			<div class="md-content">
				<h3>Elegir icono</h3>
				<div style="max-height: 500px;overflow-y: scroll;">
					<ul class="list-unstyled clearfix" id="colores_ul">
						<?php foreach($controller->get_data("*","iconos","active='1'") AS $e => $key){ ?>
						<li class="li_layout" style="float:left; width: 15%; padding: 5px;height: 50px;">
							<a href="javascript:void(0);" style="display: block;" class="clearfix a_icons full-opacity-hover" data-icon="<?php echo $key['icono']; ?>">
								<div><div class="icon"><i class="ion <?php echo $key["icono"]; ?>"></i></div></div>
							</a>
						</li>
						<?php } ?>
					</ul>
					<button class="md-close">Guardar</button>
				</div>
			</div>
		</div>
		<div class="md-overlay"></div>
		<div class="content-wrapper">
			<section class="content-header"><h1>Editor <small>Expedientes</small></h1></section>
			<section class="content">
				<div id="menus">
					<ul id="sortable" class="connectedSortable">
						<?php if($data!==FALSE){ foreach($data AS $e=>$key){
							$data_child=$controller->get_data("*","campos_expedientes","expedientes_id='".$key["id"]."'","campos_bloques_id, orden");
							$data_bloques=$controller->get_data("*","campos_bloques","expedientes_id='".$key["id"]."'","orden");
						?>
						<li class="ui-state-default first_child" data-id="<?php echo $key['id']; ?>">
							<div class="example-modal">
								<div class="modal modal-info">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close collapse_modal" data-dismiss="collapse" aria-label="Cerrar"><i class="fa fa-minus"></i></button>
												<button type="button" class="close edit_text_modal" data-dismiss="collapse" aria-label="Editar"><i class="fa fa-pencil"></i></button>
												<h4 class="modal-title"><div contenteditable="true" class="editable" style="width: 90%;"><?php echo $key["titulo"]; ?></div></h4>
												<button type="button" class="close edit_text_modal_subtitle" data-dismiss="collapse" aria-label="Editar"><i class="fa fa-pencil"></i></button>
												<h5 class="modal-title"><div contenteditable="true" class="editable_subtitle" style="width: 90%;"><?php echo $key["subtitulo"]; ?></div></h5>
											</div>
											<div class="modal-body">
												<ul class="connectedSortable_child">
												<?php if($data_child!==FALSE) foreach($data_child AS $i=>$child){ ?>
													<div class="box box-primary box-solid collapsed-box second_child" data-id="<?php echo $child['id']; ?>">
														<div class="box-header with-border">
															<h3 class="box-title"><div contenteditable="true" class="editable"><?php echo $child["nombre"]; ?></div></h3>
															<div class="box-tools pull-right">
																<button type="button" class="btn btn-box-tool edit_editable"><i class="fa fa-pencil"></i></button>
																<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
																<button type="button" class="btn btn-box-tool delete_second"><i class="fa fa-remove"></i></button>
															</div>
														</div>
														<form role="form">
															<div class="box-body">
																<div class="form-group">
																	<label for="campo_obligatorio">¿Obligatorio?</label>
																	<label><input type="checkbox" class="minimal required_editable" <?php echo intval($child['required'])>0 ? "checked" : ""; ?>></label>
																</div>
																<div class="form-group">
																	<label for="cat_campos_id">Tipo de campo</label>
																	<select class="form-control campos_editable">
																		<?php if($cat_campos_data!==FALSE) foreach($cat_campos_data AS $c=>$campo){ ?>
																		<option <?php echo intval($campo['id'])===intval($child['cat_campos_id']) ? 'selected' : ''; ?> value="<?php echo $campo['id']; ?>"><?php echo $campo["descripcion"]; ?></option>
																		<?php } ?>
																	</select>
																</div>
																<div class="form-group">
																	<label for="campos_bloques_id">Bloque perteneciente</label>
																	<select class="form-control bloques_editable">
																		<?php if($data_bloques!==FALSE) foreach($data_bloques AS $b=>$bloque){ ?>
																		<option <?php echo intval($bloque['id'])===intval($child['campos_bloques_id']) ? 'selected' : ''; ?> value="<?php echo $bloque['id']; ?>"><?php echo $bloque["nombre"]; ?></option>
																		<?php } ?>
																	</select>
																</div>
															</div>
														</form>
													</div>
												<?php } ?>
												</ul>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default pull-left add_field" data-dismiss="modal">Agregar campo</button>
												<button type="button" class="btn btn-success pull-left change_color md-trigger" data-modal="modal-1" data-color="<?php echo $key['color']; ?>" data-dismiss="modal">Elegir color</button>
												<button type="button" class="btn btn-warning pull-left change_icon md-trigger" data-modal="modal-2" data-icon="<?php echo $key['icon']; ?>" data-dismiss="modal">Elegir ícono</button>
												<button type="button" class="btn btn-danger delete_expediente">Eliminar expediente</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</li>
  						<?php } } ?>
					</ul>
					<div class="example-modal" style="text-align: center;">
						<div class="modal modal-info">
							<div class="modal-dialog">
								<div class="col-md-3" style="width:100%;">
									<div class="box-body">
										<a class="btn btn-app" id="add_li"><i class="fa fa-plus"></i> Agregar</a>
										<a class="btn btn-app" id="save"><i class="fa fa-save"></i> Guardar</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="wait" style="width: 300px;margin: auto;display:none;" class="box box-warning box-solid">
						<div class="box-header">
							<h3 class="box-title title">Guardando</h3>
							<div class="box-tools pull-right"><button type="button" class="btn btn-box-tool close_wait"><i class="fa fa-close"></i></button></div>
						</div>
						<div class="box-body body">Por favor espere</div>
						<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
					</div>
				</div>
			</section>
		</div>
		<script src="<?php echo base_url(); ?>js/classie.js"></script>
