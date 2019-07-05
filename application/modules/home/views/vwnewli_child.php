<?php
$cat_campos_data=$controller->get_data("*","cat_campos","active='1'","id");
$data_bloques=$controller->get_data("*","campos_bloques","expedientes_id='$id_main'","orden");
?>
<div class="box box-primary box-solid collapsed-box second_child" data-id="0">
	<div class="box-header with-border">
		<h3 class="box-title"><div contenteditable="true" class="editable">Nuevo campo</div></h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool edit_editable"><i class="fa fa-pencil"></i></button>
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
			<button type="button" class="btn btn-box-tool delete_second" data-widget="remove"><i class="fa fa-remove"></i></button>
		</div>
	</div>
	<form role="form">
		<div class="box-body">
			<div class="form-group">
				<label for="campo_obligatorio">¿Obligatorio?</label>
				<label><input type="checkbox" class="minimal required_editable"></label>
			</div>
			<div class="form-group">
				<label>Tipo de campo</label>
				<select class="form-control campos_editable">
					<?php if($cat_campos_data!==FALSE) foreach($cat_campos_data AS $c=>$campo){ ?>
					<option value="<?php echo $campo['id']; ?>"><?php echo $campo["descripcion"]; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group">
				<label>Bloque perteneciente</label>
				<select class="form-control bloques_editable">
					<?php if($data_bloques!==FALSE) foreach($data_bloques AS $b=>$bloque){ ?>
					<option value="<?php echo $bloque['id']; ?>"><?php echo $bloque["nombre"]; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	</form>
</div>