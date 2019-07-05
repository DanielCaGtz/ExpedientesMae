		<script src="<?php echo base_url(); ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
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
					$.post(window.url.base_url+"home/ctrhome/get_reporte_completo",{id:id},function(resp){
						resp=JSON.parse(resp);
						$("#loader_img").hide();
						var link = "<a id='download_xlsx' href='"+window.url.base_url+"files/"+resp.ruta+"' download style='display:none'></a>";
						$("#create_here").html(link);
						jQuery("#download_xlsx")[0].click();
					});
				});
				$("#main_content").on("change",".descargar_formato_adicional",function(){
					var id=$(this).val();
					if(parseInt(id)>0){
						var campo=$(this).find("option:selected").attr("data-campo");
						var exp=$(this).find("option:selected").attr("data-idexp");
						$("#loader_img").show();
						$.post(window.url.base_url+"home/ctrhome/get_formato_adicional_by_expediente",{id:id, campo:campo, exp:exp},function(resp){
							resp=JSON.parse(resp);
							if(resp.success!==false){
								$("#loader_img").hide();
								var link = "<a id='download_xlsx' href='"+window.url.base_url+"files/"+resp.ruta+"' download style='display:none'></a>";
								$("#create_here").html(link);
								jQuery("#download_xlsx")[0].click();
							}
						});
					}
				});
				$("#main_content").on("click",".descargar_formato_bitacora",function(){
					var id=$(this).attr("data-id");
					var campo=$(this).attr("data-campo");
					$("#loader_img").show();
					$.post(window.url.base_url+"home/ctrhome/get_formato_bitacora",{id:id, campo:campo},function(resp){
						resp=JSON.parse(resp);
						if(resp.success!==false){
							$("#loader_img").hide();
							var link = "<a id='download_xlsx' href='"+window.url.base_url+"files/"+resp.ruta+"' download style='display:none'></a>";
							$("#create_here").html(link);
							jQuery("#download_xlsx")[0].click();
						}
					});
				});
				$("#main_content").on("click",".descargar_formato",function(){
					var id=$(this).attr("data-id");
					var campo=$(this).attr("data-campo");
					$("#loader_img").show();
					$.post(window.url.base_url+"home/ctrhome/get_formato_by_expediente",{id:id, campo:campo},function(resp){
						resp=JSON.parse(resp);
						if(resp.success!==false){
							$("#loader_img").hide();
							var link = "<a id='download_xlsx' href='"+window.url.base_url+"files/"+resp.ruta+"' download style='display:none'></a>";
							$("#create_here").html(link);
							jQuery("#download_xlsx")[0].click();
						}
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
				$("#example1").on("click",".eliminar_registro",function(){
					var id=$(this).attr("data-id");
					var elem=$(this).parent().parent();
					if(confirm("¿Realmente desea eliminar el registro?")){
						$.post(window.url.base_url+"home/ctrhome/eliminar_expedientes_datos",{id:id},function(resp){
							resp=JSON.parse(resp);
							if(resp.success!==false){
								elem.remove();
								$('#example1').DataTable().reload();
							}
						});
					}
				});
				$("#example1").on("click",".editar_registro",function(){
					var id=$(this).attr("data-id");
					window.location.href = window.url.base_url+"editar_formulario/"+id;
				});
			});
		</script>
	</body>
</html>
