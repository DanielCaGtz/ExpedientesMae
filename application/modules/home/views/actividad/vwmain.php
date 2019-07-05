<style>
	.old {color: #d60c30}
	.new {color: #20d60c}
</style>
<div class="content-wrapper">
  <section class="content-header"><section class="content-header"><h1>Registro de actividad <small>Histórico</small></h1></section></section>
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
				<div class="col-md-12">
					<div class="box">
						<div class="box-header"><h3 class="box-title">Actividad</h3></div>
						<div class="box-body">
							<table id="example1" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>Usuario</th>
										<th>Acción</th>
                    <th>Sección</th>
                    <th>Campo</th>
                    <th>Cambios realizados</th>
										<th>Fecha y hora</th>
										<th>Enlace</th>
									</tr>
								</thead>
                <tbody>
									<?php
										$data = $controller->get_data_from_query('SELECT ppal.*, user.username FROM log AS ppal INNER JOIN users AS user ON ppal.idUsuario = user.id');
										if ($data !== FALSE) {
											foreach ($data AS $e => $key) { ?>
												<tr>
													<td><?= $key['username'] ?></td>
													<td><?= $key['accion'] ?></td>
													<td>
													<?php
														switch ($key['tabla']) {
															case 'campos_datos':
																$data_campos = $controller->get_data('expedientes_id', $key['tabla'], 'id="'.$key['idTabla'].'"');
																if ($data_campos !== FALSE) {
																	$data_campos = $data_campos[0];
																	$data_inventario = $controller->get_data('titulo', 'expedientes', 'id="'.$data_campos['expedientes_id'].'"');
																	if ($data_inventario !== FALSE) {
																		$data_inventario = $data_inventario[0];
																		echo $data_inventario['titulo'];
																	}
																}
															break;
															default:
																echo $key['tabla'];
														}
													?>
													</td>
													<td>
													<?php
														switch ($key['tabla']) {
															case 'campos_datos':
																$data_campos = $controller->get_data('campos_expedientes_id', $key['tabla'], 'id="'.$key['idTabla'].'"');
																if ($data_campos !== FALSE) {
																	$data_campos = $data_campos[0];
																	$data_expediente = $controller->get_data('nombre', 'campos_expedientes', 'id="'.$data_campos['campos_expedientes_id'].'"');
																	if ($data_expediente !== FALSE) {
																		$data_expediente = $data_expediente[0];
																		echo $data_expediente['nombre'];
																	}
																}
															break;
															default:
																echo $key['columna'];
														}
													?>
													</td>
													<td>
														<span class='old'><?= $key['viejo_valor'] ?></span>&nbsp;
														<i class="fa fa-arrow-right"></i>&nbsp;
														<span class='new'><?= $key['nuevo_valor'] ?></span>
													</td>
													<td><?= $key['date_start'] ?></td>
													<td>
													<?php
														switch ($key['tabla']) {
															case 'campos_datos':
																$data_campos = $controller->get_data('expedientes_datos_id', $key['tabla'], 'id="'.$key['idTabla'].'"');
																if ($data_campos !== FALSE) {
																	$data_campos = $data_campos[0]; ?>
																	<button type="button" class="btn btn-box-tool"><a target='_blank' href='<?= base_url() ?>editar_formulario/<?= $data_campos['expedientes_datos_id'] ?>'><i class="fa fa-eye"></i></a></button>
																<?php
																}
															break;
														}
													?>
													</td>
												</tr>
											<?php
											}
										}
									?>
								</tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
		</section>
</div>
