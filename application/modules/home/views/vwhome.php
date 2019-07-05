	<div class="content-wrapper">
		<section class="content-header">
			<h1>Dashboard <small>Control panel</small></h1>
	  <ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	  </ol>
	</section>

	<section class="content">
		<div class="row">
			<?php $w=""; if($this->session->userdata("username")=="visitor") $w="AND is_admin='0'";
			$data=$controller->get_data("*","expedientes","active='1' $w","orden"); if($data!==FALSE) foreach($data AS $e=>$key){
				if(intval($key['id'])===15) $s=$controller->check_user_permission("allow_inventario");
				else $s=TRUE;
				if($s){
			?>
			<div class="col-lg-3 col-xs-6">
				<div class="small-box <?php echo $key['color']; ?>">
					<div class="inner">
						<h3><?php echo $key['titulo']; ?></h3>
						<p><?php echo $key['subtitulo']; ?></p>
					</div>
					<div class="icon"><i class="ion <?php echo $key['icon']; ?>"></i></div>
					<a href="<?php echo base_url('datos_formulario/'.$key['id']); ?>" class="small-box-footer">Consultar <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<?php }} ?>
		</div>
		<?php if($this->session->userdata("username")!="visitor"){ ?>
		<div class="row">
			<div class="col-lg-3 col-xs-6">
				<div class="small-box bg-olive">
					<div class="inner">
						<h3>Administración</h3>
						<p>Editor de expedientes</p>
					</div>
					<div class="icon"><i class="ion ion-hammer"></i></div>
					<a href="<?php echo base_url('editor'); ?>" class="small-box-footer">Editar <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<div class="col-lg-3 col-xs-6">
				<div class="small-box bg-olive">
					<div class="inner">
						<h3>Administración</h3>
						<p>Editor de bloques</p>
					</div>
					<div class="icon"><i class="ion ion-wrench"></i></div>
					<a href="<?php echo base_url('editar_bloques'); ?>" class="small-box-footer">Editar <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<div class="col-lg-3 col-xs-6">
				<div class="small-box bg-olive">
					<div class="inner">
						<h3>Administración</h3>
						<p>Editor de opciones</p>
					</div>
					<div class="icon"><i class="ion ion-wrench"></i></div>
					<a href="<?php echo base_url('editar_opciones'); ?>" class="small-box-footer">Editar <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<div class="col-lg-3 col-xs-6">
				<div class="small-box bg-olive">
					<div class="inner">
						<h3>Administración</h3>
						<p>Editor de paquetes de inventario</p>
					</div>
					<div class="icon"><i class="ion ion-wrench"></i></div>
					<a href="<?php echo base_url('editar_paquetes'); ?>" class="small-box-footer">Editar <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<div class="col-lg-3 col-xs-6">
				<div class="small-box bg-aqua">
					<div class="inner">
						<h3>Ingresos</h3>
						<p>Ver ingresos</p>
					</div>
					<div class="icon"><i class="ion ion-document"></i></div>
					<a href="<?php echo base_url('ingresos'); ?>" class="small-box-footer">Consultar <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
		</div>
		<?php } ?>
	</section>
</div>