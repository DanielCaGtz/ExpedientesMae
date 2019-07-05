		<style>
			#sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
			#sortable li { cursor:move; margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 50px; }
			#sortable li span { position: absolute; margin-left: -1.3em; }
		</style>
		<div class="content-wrapper">
			<section class="content-header"><section class="content-header"><h1>Editor <small>Bloques</small></h1></section></section>
			<section class="content">
				<div class="row">
					<div class="col-md-5" data-role="content" data-theme="c">
						<div class="form-group">
							<label>Bloques por expediente</label>
							<?php $data=$controller->get_data("*","expedientes","active='1'","orden"); ?>
							<select class="form-control" id="expedientes">
								<option selected value="0">Seleccione un expediente</option>
								<?php if($data!==FALSE){ foreach($data AS $e=>$key){ ?>
								<option value="<?php echo $key['id']; ?>"><?php echo $key["titulo"]; ?></option>
								<?php }} ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-5" data-role="content" data-theme="c"><ul id="sortable" data-role="listview" data-inset="true" data-theme="d"></ul></div>
					<div class="col-md-2">
						<div class="box-body">
							<a class="btn btn-app" id="add"><i class="fa fa-plus"></i> Agregar</a>
							<a class="btn btn-app" id="save" data-unsaved="0"><i class="fa fa-save"></i> Guardar</a>
							<img style="display:none;" id="loader" src="<?php echo base_url(); ?>img/loader.GIF">
						</div>
					</div>
					<div class="col-md-3">
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
