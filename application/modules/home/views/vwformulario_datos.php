	<?php
		$data=$controller->get_data("*","campos_expedientes","expedientes_id='".$expediente["id"]."' AND is_secretcode='0'","campos_bloques_id, orden","","8");
		$w="";
		if($this->session->userdata("username")=="visitor"){
			switch($expediente["id"]){
				case 1: $w=" AND id='".$this->session->userdata("expedientes_datos_id")."'; "; break;
				case 7: case 12: $w=" AND id IN (SELECT expedientes_datos_id FROM campos_datos WHERE dato_int IN (SELECT id FROM campos_datos WHERE expedientes_datos_id='".$this->session->userdata("expedientes_datos_id")."')) ; "; break;
			}
		}
		$data_campos=$controller->get_data("*","expedientes_datos","expedientes_id='".$expediente["id"]."' AND id IN (SELECT DISTINCT expedientes_datos_id FROM campos_datos) $w ");
		$data_formatos=$controller->get_data("*","formatos","expedientes_id='".$expediente["id"]."'");
	?>
	<style>
		.btn-app{border: 1px solid #333;}
		.descargar_formato_adicional{width: 150px;}
	</style>
	<div class="content-wrapper">
		<section class="content-header">
			<h1><?php echo $expediente["titulo"]; ?> <small>Registros</small></h1>
			<ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i> <?php echo $expediente["titulo"]; ?></a></li>
				<li class="active">Registros</li>
			</ol>
		</section>

		<section class="content" id="main_content">
			<div class="row">
				<div class="form-group">
					<a class="btn btn-app" id="add_li" href="<?php echo base_url('formulario/'.$id_expediente); ?>"><i class="fa fa-plus"></i> Agregar</a>
					<a class="btn btn-app" id="download_reportes" data-id="<?php echo $expediente["id"]; ?>" href="javascript:;"><i class="fa fa-download"></i> Descargar Reporte</a>
					<img id="loader_img" style="display:none;" src="<?php echo base_url(); ?>img/loader.GIF">
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
			<div class="row">
				<div class="col-md-12">
					<div class="box">
						<div class="box-header"><h3 class="box-title">Registros</h3></div>
						<div class="box-body">
							<table id="example1" class="table table-bordered table-striped">
								<thead>
									<tr>
										<?php if($data!==FALSE) foreach($data AS $key){ ?>
										<th><?php echo $key["nombre"]; ?></th>
										<?php }if($expediente["id"]==14){ ?>
										<th>Costo total</th>
										<?php } ?>
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody>
									<?php if($data_campos!==FALSE) foreach($data_campos AS $campo){ ?>
									<tr>
										<?php
											$costo_total=0;
											if($data!==FALSE) foreach($data AS $key){
												$data_temp=$controller->get_data("COALESCE(COALESCE((SELECT opcion_nombre FROM cat_opciones WHERE id=ppal.dato_int), ppal.dato_var, ppal.dato_text, ppal.dato_date, ppal.dato_time),(SELECT COALESCE(det.dato_int, det.dato_var, det.dato_text, det.dato_date, det.dato_time) FROM campos_datos AS det WHERE det.id=ppal.dato_int)) AS campo_dato","campos_datos AS ppal","ppal.expedientes_datos_id='".$campo["id"]."' AND ppal.campos_expedientes_id='".$key["id"]."'");
												if($data_temp!==FALSE){
										?>
										<td><?php
													if(intval($key["cat_campos_id"])===8){
														$data_bitacora=$controller->get_data_from_query("SELECT ppal.*, det.dato_var, (SELECT dato_var FROM campos_datos WHERE expedientes_datos_id=det.expedientes_datos_id AND campos_expedientes_id=180) AS precio FROM bitacora_detalles AS ppal INNER JOIN campos_datos AS det ON ppal.id_inventario=det.id WHERE ppal.bitacora_id='".$data_temp[0]["campo_dato"]."';");
														if($data_bitacora!==FALSE){
															$r="";
															foreach($data_bitacora AS $b => $bit){
																if($r!="")$r.=" || ";
																$r.=trim($bit["cantidad"]."x".$bit["dato_var"]);
																$costo_total+=(floatval($bit["precio"])*intval($bit["cantidad"]));
															}
															echo $r;
														}
													}else{
														if(intval($key["is_inventario"])===0) echo $data_temp[0]["campo_dato"];
														else{
															$query_invent="SELECT COALESCE(cantidad,0) AS num FROM bitacora_detalles WHERE id_inventario = (SELECT id FROM campos_datos WHERE expedientes_datos_id='".$campo["id"]."' AND campos_expedientes_id='".$key["is_inventario"]."'); ";
															//echo $query_invent;
															$data_exist=$controller->get_data_from_query($query_invent);
															if($data_exist!==FALSE){
																if(intval($data_exist[0]["num"])<=intval($data_temp[0]["campo_dato"]))
																	echo $data_temp[0]["campo_dato"]-$data_exist[0]["num"];
																else
																	echo $data_temp[0]["campo_dato"];
															}else echo $data_temp[0]["campo_dato"];
														}
													}
										?></td>
										<?php
												}else{
										?>
										<td></td>
										<?php
												}
											} if($expediente["id"]==14){
										?>
										<td>$<?php echo number_format($costo_total,2); ?></td>
										<?php } ?>
										<td>
											<button type="button" class="btn btn-box-tool editar_registro" data-id="<?php echo $campo['id']; ?>"><i class="fa fa-pencil"></i></button>
											<button type="button" class="btn btn-box-tool eliminar_registro" data-id="<?php echo $campo['id']; ?>"><i class="fa fa-trash"></i></button>
											<?php if($data_formatos!==FALSE){ ?>
											<button type="button" class="btn btn-box-tool descargar_formato" data-campo="<?php echo $campo['id']; ?>" data-id="<?php echo $expediente['id']; ?>"><i class="fa fa-download"></i></button>
											<?php }if($expediente["id"]==14){ ?>
											<button type="button" class="btn btn-box-tool descargar_formato_bitacora" data-campo="<?php echo $campo['id']; ?>" data-id="<?php echo $expediente['id']; ?>"><i class="fa fa-download"></i></button>
											<?php
											} $data_adic=$controller->get_data_from_query("SELECT formatos_id, ruta, titulo FROM formatos_adicionales WHERE expedientes_id='".$expediente["id"]."' ORDER BY orden ;");
											if($data_adic!==FALSE){
											?>
											<select class="descargar_formato_adicional">
												<option value="">Descargar formatos</option>
											<?php
												foreach($data_adic AS $a=>$adic){
													?>
													<option data-campo="<?php echo $campo['id']; ?>" data-idexp="<?php echo $expediente['id']; ?>" value="<?php echo $adic['formatos_id']; ?>"><?php echo $adic['titulo']; ?></option>
													<?php
												}
											?>
											</select>
											<?php
											}
											?>
										</td>
									</tr>
									<?php } ?>
								</tbody>
								<tfoot>
									<tr>
										<?php if($data!==FALSE) foreach($data AS $key){ ?>
										<th><?php echo $key["nombre"]; ?></th>
										<?php } ?>
										<th>Acciones</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<div id="create_here"></div>
