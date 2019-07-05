		<script src="<?php echo base_url(); ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
		<script src="<?php echo base_url(); ?>plugins/iCheck/icheck.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.js"></script>
		<script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
		<script src="<?php echo base_url(); ?>plugins/fastclick/fastclick.js"></script>
		<script src="<?php echo base_url(); ?>dist/js/app.min.js"></script>
		<script src="<?php echo base_url(); ?>dist/js/demo.js"></script>
		<script src="<?php echo base_url(); ?>js/classie.js"></script>
		<script>
			$(document).ready(function(){
				function show_wait(show,title,title2,over){
					$("#wait").fadeOut(1600,function(){
						$(this).removeClass("box-danger").removeClass("box-success").removeClass("box-warning").addClass(show);
						$(this).find(".title").html(title);
						$(this).find(".body").html(title2);
						$(this).show();
						$('html, body').animate({scrollTop: $("#wait").offset().top}, 1000);
						if(parseInt(over)>0) $(this).find(".overlay").show(); else $(this).find(".overlay").hide();
						$(this).fadeIn(1600);
					});
				}
				$("#download_reportes").on("click",function(){
					var id=$(this).attr("data-id");
					$("#loader_img").show();
					var fecha_inicio = $("#fecha_inicio").val();
					var fecha_fin = $("#fecha_fin").val();
					$.post(window.url.base_url+"home/ctrpages/get_reporte_ingresos",{id:id, fecha_inicio:fecha_inicio, fecha_fin:fecha_fin},function(resp){
						resp=JSON.parse(resp);
						$("#loader_img").hide();
						var link = "<a id='download_xlsx' href='"+window.url.base_url+"files/"+resp.ruta+"' download style='display:none'></a>";
						$("#create_here").html(link);
						jQuery("#download_xlsx")[0].click();
					});
				});
				$('.minimal').iCheck({
					checkboxClass: 'icheckbox_minimal-blue'
				});
				$(".fecha_ingreso").on("change",function(){
					$(this).parent().find(".loader_img").show();
					var expedientes_datos_id = $(this).attr("data-expedientes_datos_id");
					var fecha = $(this).val();
					var elem = $(this);
					$.post(window.url.base_url+"home/ctrpages/change_fecha_ingresos",{expedientes_datos_id:expedientes_datos_id, fecha:fecha},function(resp){
						resp=JSON.parse(resp);
						if(resp.success!==false) elem.parent().find(".loader_img").hide();
					});
				});
				$("#main_content .minimal").on("ifChanged",function(){
					$(this).parent().parent().find(".loader_img").show();
					var expedientes_datos_id = $(this).parent().parent().attr("data-expedientes_datos_id");
					var costo = $(this).parent().parent().attr("data-costo");
					if($(this).is(":checked")){
						$(this).parent().parent().find("span").html("Pagado");
						var estatus=1;
					}else{
						$(this).parent().parent().find("span").html("No Pagado");
						var estatus=0;
					}
					var elem = $(this);
					$.post(window.url.base_url+"home/ctrpages/change_ingresos",{expedientes_datos_id:expedientes_datos_id, estatus:estatus, costo:costo},function(resp){
						resp=JSON.parse(resp);
						if(resp.success!==false) elem.parent().parent().find(".loader_img").hide();
					});
				});
				$('#example1').DataTable({
					"paging": true,
					"lengthChange": false,
					"searching": true,
					"ordering": true,
					"info": true,
					"autoWidth": false
				});
			});
		</script>
	</body>
</html>
