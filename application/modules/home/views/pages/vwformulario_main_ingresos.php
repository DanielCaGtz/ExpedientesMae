	<?php
		$data=$controller->get_data("*","campos_expedientes","expedientes_id='14' AND is_secretcode='0' AND (id=170 OR id=188)","campos_bloques_id, orden");
		$data_campos=$controller->get_data("*","expedientes_datos","expedientes_id='14' AND id IN (SELECT DISTINCT expedientes_datos_id FROM campos_datos) ");
	?>
	<style>
		.btn-app{border: 1px solid #333;}
	</style>
	<link rel="stylesheet" href="<?php echo base_url(); ?>plugins/iCheck/all.css">
	<div class="content-wrapper">
		<section class="content-header">
			<h1>Ingresos <small>Registros</small></h1>
			<ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i> Ingresos</a></li>
				<li class="active">Registros</li>
			</ol>
		</section>

		<section class="content" id="main_content">
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label>Fecha de inicio</label>
						<input type="date" class="form-control" id="fecha_inicio">
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label>Fecha de fin</label>
						<input type="date" class="form-control" id="fecha_fin">
					</div>
				</div>
			</div>
			<?php if($controller->check_user_permission("allow_ingresos")){ ?>
			<div class="row">
				<div class="form-group">
					<a class="btn btn-app" id="download_reportes" href="javascript:;"><i class="fa fa-download"></i> Descargar Reporte</a>
					<img id="loader_img" style="display:none;" src="<?php echo base_url(); ?>img/loader.GIF">
				</div>
			</div>
			<?php } ?>
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
										<?php if($data!==FALSE) foreach($data AS $key){ if(intval($key["id"])===188) $w="style='display:none;'"; else $w=""; ?>
										<th <?php echo $w; ?>><?php echo $key["nombre"]; ?></th>
										<?php } ?>
										<th>Costo total</th>
										<th>Método de pago</th>
										<th>Terminal</th>
										<th>¿Facturado?</th>
										<th>Seguro</th>
										<th>Fecha del ingreso</th>
										<th>Estatus</th>
									</tr>
								</thead>
								<tbody>
									<?php if($data_campos!==FALSE) foreach($data_campos AS $campo){ ?>
									<tr>
										<?php
											$costo_total=0;
											$dato_int=0;
											if($data!==FALSE) foreach($data AS $key){
												$query="SELECT ppal.dato_int, COALESCE(COALESCE((SELECT opcion_nombre FROM cat_opciones WHERE id=ppal.dato_int), ppal.dato_var, ppal.dato_text, ppal.dato_date, ppal.dato_time),(SELECT COALESCE(det.dato_int, det.dato_var, det.dato_text, det.dato_date, det.dato_time) FROM campos_datos AS det WHERE det.id=ppal.dato_int)) AS campo_dato FROM campos_datos AS ppal WHERE ppal.expedientes_datos_id='".$campo["id"]."' AND ppal.campos_expedientes_id='".$key["id"]."';";
												#echo $query;
												$data_temp=$controller->get_data_from_query($query);
												if($data_temp!==FALSE){ if(intval($key["id"])===188) $w="style='display:none;'"; else $w="";
										?>
										<td <?php echo $w; ?>><?php
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
														if(intval($key["is_inventario"])===0){
															$dato_int=$data_temp[0]["dato_int"];
															echo $data_temp[0]["campo_dato"];
														}else{
															$query_invent="SELECT COUNT(*) AS num FROM campos_datos WHERE dato_var LIKE (SELECT CONCAT('%',id,'%') FROM campos_datos WHERE expedientes_datos_id='".$campo["id"]."' AND campos_expedientes_id='".$key["is_inventario"]."') AND cat_campos_id=8 GROUP BY id; ";
															#echo $query_invent;
															$data_exist=$controller->get_data_from_query($query_invent);
															if($data_exist!==FALSE) echo $data_temp[0]["campo_dato"]-$data_exist[0]["num"];
															else echo $data_temp[0]["campo_dato"];
														}
													}
										?></td>
										<?php
												}else{
										?>
										<td></td>
										<?php
												}
											}
										?>
										<td>$<?php echo number_format($costo_total,2); ?></td>
										<td>
											<?php //METODO DE PAGO
												if($dato_int>0){
													$query="SELECT COALESCE(
																COALESCE((SELECT opcion_nombre FROM cat_opciones WHERE id=ppal.dato_int), ppal.dato_var, ppal.dato_text, ppal.dato_date, ppal.dato_time),
																(SELECT COALESCE(det.dato_int, det.dato_var, det.dato_text, det.dato_date, det.dato_time) FROM campos_datos AS det WHERE det.id=ppal.dato_int)) AS campo_dato
															FROM campos_datos AS ppal
															WHERE ppal.expedientes_datos_id=(SELECT expedientes_datos_id FROM campos_datos WHERE expedientes_id='13' AND campos_expedientes_id='168' AND dato_int='$dato_int' LIMIT 1) AND ppal.campos_expedientes_id='169';";
													#echo $query;
													$data_metodo=$controller->get_data_from_query($query);
													if($data_metodo!==FALSE) echo $data_metodo[0]["campo_dato"];
												}
											?>
										</td>
										<td>
											<?php //TERMINAL
												if($dato_int>0){
													$query="SELECT COALESCE(
																COALESCE((SELECT opcion_nombre FROM cat_opciones WHERE id=ppal.dato_int), ppal.dato_var, ppal.dato_text, ppal.dato_date, ppal.dato_time),
																(SELECT COALESCE(det.dato_int, det.dato_var, det.dato_text, det.dato_date, det.dato_time) FROM campos_datos AS det WHERE det.id=ppal.dato_int)) AS campo_dato
															FROM campos_datos AS ppal
															WHERE ppal.expedientes_datos_id=(SELECT expedientes_datos_id FROM campos_datos WHERE expedientes_id='13' AND campos_expedientes_id='168' AND dato_int='$dato_int' LIMIT 1) AND ppal.campos_expedientes_id='194';";
													#echo $query;
													$data_metodo=$controller->get_data_from_query($query);
													if($data_metodo!==FALSE) echo $data_metodo[0]["campo_dato"];
												}
											?>
										</td>
										<td>
											<?php //FACTURADO
												if($dato_int>0){
													$query="SELECT COALESCE(
																COALESCE((SELECT opcion_nombre FROM cat_opciones WHERE id=ppal.dato_int), ppal.dato_var, ppal.dato_text, ppal.dato_date, ppal.dato_time),
																(SELECT COALESCE(det.dato_int, det.dato_var, det.dato_text, det.dato_date, det.dato_time) FROM campos_datos AS det WHERE det.id=ppal.dato_int)) AS campo_dato
															FROM campos_datos AS ppal
															WHERE ppal.expedientes_datos_id=(SELECT expedientes_datos_id FROM campos_datos WHERE expedientes_id='14' AND campos_expedientes_id='170' AND dato_int='$dato_int' LIMIT 1) AND ppal.campos_expedientes_id='196';";
													#echo $query;
													$data_metodo=$controller->get_data_from_query($query);
													if($data_metodo!==FALSE) echo $data_metodo[0]["campo_dato"];
												}
											?>
										</td>
										<td>
											<?php //SEGURO
												if($dato_int>0){
													$query="SELECT COALESCE(
																COALESCE((SELECT opcion_nombre FROM cat_opciones WHERE id=ppal.dato_int), ppal.dato_var, ppal.dato_text, ppal.dato_date, ppal.dato_time),
																(SELECT COALESCE(det.dato_int, det.dato_var, det.dato_text, det.dato_date, det.dato_time) FROM campos_datos AS det WHERE det.id=ppal.dato_int)) AS campo_dato
															FROM campos_datos AS ppal
															WHERE ppal.expedientes_datos_id=(SELECT expedientes_datos_id FROM campos_datos WHERE expedientes_id='14' AND campos_expedientes_id='170' AND dato_int='$dato_int' LIMIT 1) AND ppal.campos_expedientes_id='193';";
													#echo $query;
													$data_metodo=$controller->get_data_from_query($query);
													if($data_metodo!==FALSE) echo $data_metodo[0]["campo_dato"];
												}
											?>
										</td>
										<td>
											<div class="form-group">
												<label>
													<?php $val_temp=""; $ingreso=$controller->get_data("*","ingresos","expedientes_datos_id='".$campo["id"]."'"); if($ingreso!==FALSE){ if(strlen($ingreso[0]["date_start"])) $val_temp=$ingreso[0]["date_start"]; } ?>
													<input type="date" class="form-control fecha_ingreso" data-expedientes_datos_id="<?php echo $campo["id"]; ?>" value="<?php echo $val_temp!=='' ? $val_temp : ''; ?>" >
													<img style="width:15px;display:none;float: right;" src="<?php echo base_url(); ?>img/loader.GIF" class="loader_img">
												</label>
											</div>
										</td>
										<td>
											<div class="form-group">
												<label data-expedientes_datos_id="<?php echo $campo["id"]; ?>" data-costo="<?php echo $costo_total; ?>">
													<?php $pagado=0; if($ingreso!==FALSE){ $pagado=intval($ingreso[0]["pagado"]); } if($pagado>0){ ?>
													<input type="checkbox" class="minimal" checked> <span>Pagado</span>
													<?php }else{ ?>
													<input type="checkbox" class="minimal"> <span>No Pagado</span>
													<?php } ?>
													<img style="width:15px;display:none;" src="<?php echo base_url(); ?>img/loader.GIF" class="loader_img">
												</label>
											</div>
										</td>
									</tr>
									<?php } ?>
								</tbody>
								<tfoot>
									<tr>
										<?php if($data!==FALSE) foreach($data AS $key){ if(intval($key["id"])===188) $w="style='display:none;'"; else $w="";  ?>
										<th <?php echo $w; ?>><?php echo $key["nombre"]; ?></th>
										<?php } ?>
										<th>Costo total</th>
										<th>Fecha del ingreso</th>
										<th>Estatus</th>
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
