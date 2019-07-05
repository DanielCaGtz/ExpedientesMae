		<?php
			function consulta_uri($txt){
				$url=explode("/",$_SERVER["REQUEST_URI"]);
				foreach($txt AS $item){
					foreach($url AS $path){
						if($item===$path) return "active";
					}
				}
			}
		?>
		<aside class="main-sidebar">
			<section class="sidebar">
				<div class="user-panel">
					<div class="pull-left image">
						<img src="<?php echo base_url(); ?>img/user.jpg" class="img-circle" alt="User Image">
					</div>
					<div class="pull-left info">
						<p>Usuario</p>
						<a href="<?php echo base_url(); ?>"><i class="fa fa-circle text-success"></i> Online</a>
					</div>
				</div>
				<ul class="sidebar-menu">
					<li class="header">MENÚ PRINCIPAL</li>
					<li class="treeview <?php echo consulta_uri(array('home')); ?>">
						<a href="<?php echo base_url(); ?>">
							<i class="fa fa-dashboard"></i> <span>Home</span>
							<span class="pull-right-container"></span>
						</a>
					</li>
					<li class="treeview <?php echo consulta_uri(array('datos_formulario','formulario','editar_formulario')); ?>">
						<a href="#">
							<i class="fa fa-bar-chart"></i> <span>Expedientes</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<?php $w=""; if($this->session->userdata("username")=="visitor") $w="AND is_admin='0'";
							$data=$controller->get_data("*","expedientes","active='1' $w","orden, titulo"); if($data!==FALSE) foreach($data AS $e=>$key){
								if(intval($key['id'])===15) $s=$controller->check_user_permission("allow_inventario");
								else $s=TRUE;
								if($s){
							?>
							<li class="<?php echo consulta_uri(array($key['id'])); ?>"><a href="<?php echo base_url('datos_formulario/'.$key['id']); ?>"><i class="fa fa-circle-o"></i> <?php echo $key["titulo"]; ?></a></li>
							<?php }} ?>
						</ul>
					</li>
					<?php if($this->session->userdata("username")!="visitor"){ ?>
					<li class="treeview <?php echo consulta_uri(array('editor','editar_bloques','editar_opciones','editar_paquetes','editar_reportes')); ?>">
						<a href="#">
							<i class="fa fa-pencil"></i> <span>Editor</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li class="<?php echo consulta_uri(array('editor')); ?>"><a href="<?php echo base_url('editor'); ?>"><i class="fa fa-circle-o"></i> Editor de expedientes</a></li>
							<li class="<?php echo consulta_uri(array('editar_bloques')); ?>"><a href="<?php echo base_url('editar_bloques'); ?>"><i class="fa fa-circle-o"></i> Editor de bloques</a></li>
							<li class="<?php echo consulta_uri(array('editar_opciones')); ?>"><a href="<?php echo base_url('editar_opciones'); ?>"><i class="fa fa-circle-o"></i> Editor de opciones</a></li>
							<?php if($controller->check_user_permission("allow_inventario")){ ?>
							<li class="<?php echo consulta_uri(array('editar_paquetes')); ?>"><a href="<?php echo base_url('editar_paquetes'); ?>"><i class="fa fa-circle-o"></i> Editor de paquetes</a></li>
							<?php } ?>
							<li class="<?php echo consulta_uri(array('editar_reportes')); ?>"><a href="<?php echo base_url('editar_reportes'); ?>"><i class="fa fa-circle-o"></i> Editor de reportes</a></li>
						</ul>
					</li>
					<li class="treeview <?php echo consulta_uri(array('test')); ?>">
						<a href="#">
							<i class="fa fa-edit"></i> <span>Usuarios</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li class="<?php echo consulta_uri(array('test')); ?>"><a href="<?php echo base_url(); ?>"><i class="fa fa-circle-o"></i> Alta usuario</a></li>
							<li class="<?php echo consulta_uri(array('test')); ?>"><a href="<?php echo base_url(); ?>"><i class="fa fa-circle-o"></i> Consultar usuario</a></li>
							<li class="<?php echo consulta_uri(array('test')); ?>"><a href="<?php echo base_url(); ?>"><i class="fa fa-circle-o"></i> Modificar usuario</a></li>
							<li class="<?php echo consulta_uri(array('test')); ?>"><a href="<?php echo base_url(); ?>"><i class="fa fa-circle-o"></i> Baja usuario</a></li>
						</ul>
					</li>
					<li class="<?php echo consulta_uri(array()); ?>">
						<a href="<?php echo base_url(); ?>ingresos">
							<i class="fa fa-th"></i> <span>Ingresos</span>
						</a>
					</li>
					<?php if($controller->check_user_permission("allow_facturacion")){ ?>
					<li class="treeview <?php echo consulta_uri(array('test')); ?>">
						<a href="#">
							<i class="fa fa-edit"></i> <span>Facturación</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li class="<?php echo consulta_uri(array('test')); ?>"><a href="<?php echo base_url(); ?>"><i class="fa fa-circle-o"></i> Digifact</a></li>
							<li class="<?php echo consulta_uri(array('test')); ?>"><a href="<?php echo base_url(); ?>"><i class="fa fa-circle-o"></i> Historial Facturación</a></li>
						</ul>
					</li>
					<?php } if($controller->check_user_permission("allow_egresos")){ ?>
					<li class="treeview <?php echo consulta_uri(array('test')); ?>">
						<a href="#">
							<i class="fa fa-edit"></i> <span>Egresos</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li class="<?php echo consulta_uri(array('test')); ?>"><a href="<?php echo base_url(); ?>"><i class="fa fa-circle-o"></i> Egresos</a></li>
							<li class="<?php echo consulta_uri(array('test')); ?>"><a href="<?php echo base_url(); ?>"><i class="fa fa-circle-o"></i> Catálogo de egresos</a></li>
							<li class="<?php echo consulta_uri(array('test')); ?>"><a href="<?php echo base_url(); ?>"><i class="fa fa-circle-o"></i> Autorización electrónica</a></li>
						</ul>
					</li>
					<?php } if($controller->check_user_permission("allow_inventario")){ ?>
					<li class="<?php echo consulta_uri(array()); ?>">
						<a href="<?php echo base_url(); ?>datos_formulario/15">
							<i class="fa fa-th"></i> <span>Inventario</span>
						</a>
					</li>
					<?php } ?>
					<li class="treeview <?php echo consulta_uri(array('test')); ?>" style="display:none;">
						<a href="#">
							<i class="fa fa-edit"></i> <span>Reportes</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li class="<?php echo consulta_uri(array('test')); ?>"><a href="<?php echo base_url(); ?>"><i class="fa fa-circle-o"></i> Egresos</a></li>
							<li class="<?php echo consulta_uri(array('test')); ?>"><a href="<?php echo base_url(); ?>"><i class="fa fa-circle-o"></i> Ingresos</a></li>
							<li class="<?php echo consulta_uri(array('test')); ?>"><a href="<?php echo base_url(); ?>"><i class="fa fa-circle-o"></i> Inventario</a></li>
							<li class="<?php echo consulta_uri(array('test')); ?>"><a href="<?php echo base_url(); ?>"><i class="fa fa-circle-o"></i> Pacientes</a></li>
							<li class="<?php echo consulta_uri(array('test')); ?>"><a href="<?php echo base_url(); ?>"><i class="fa fa-circle-o"></i> Desempeño Médicos</a></li>
							<li class="<?php echo consulta_uri(array('test')); ?>"><a href="<?php echo base_url(); ?>"><i class="fa fa-circle-o"></i> Cartera vencida</a></li>
						</ul>
					</li>
					<?php } ?>
				</ul>
			</section>
		</aside>
