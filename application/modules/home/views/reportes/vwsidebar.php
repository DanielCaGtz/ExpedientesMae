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
		<script src="<?php echo base_url(); ?>plugins/iCheck/icheck.min.js"></script>
		<script>
			$(document).ready(function(){
				function show_wait(show,title,title2,over){
					$("#wait").fadeOut(800,function(){
						$(this).removeClass("box-danger").removeClass("box-success").removeClass("box-warning").addClass(show);
						$(this).find(".title").html(title);
						$(this).find(".body").html(title2);
						$(this).show();
						$('html, body').animate({scrollTop: $("#wait").offset().top}, 1000);
						if(parseInt(over)>0) $(this).find(".overlay").show(); else $(this).find(".overlay").hide();
						$(this).fadeIn(900);
					});
				}
				$("#add_new_field").on("click",function(){
					var expediente=$("#expedientes_id").val();
					$.post(window.url.base_url+"home/ctrreportes/get_rows_fields",{expediente:expediente},function(resp){
						$("#main_container").append(resp);
					});
				});
				$("#send_form").on("submit",function(e){
					e.preventDefault();
					uploadFiles();
				});
				function uploadFiles(){
					var formData = new FormData(document.getElementById("send_form"));
					show_wait("box-warning","Guardando","Por favor espere.",1);
					$.ajax({
						url: window.url.base_url+'tools/ctrtools/doupload/ruta_file',
						type: 'POST',
						data:  formData,
						mimeType:"multipart/form-data",
						contentType: false,
						cache: false,
						processData:false,
						success : function(data){
							data=JSON.parse(data);
							if(data.success!==false){
								var ruta=data.result.name;
								var expedientes_id=$("#expedientes_id").val();
								var nombre=$("#nombre").val();
								var titulo=$("#titulo").val();
								var lis = [];
								$.each($("#main_container .rows_container"),function(i,item){
									var temp = [];
									var x=0;
									temp[x++] = $(item).find(".get_this_cell").val();
									temp[x++] = $(item).find(".get_this_field").val();
									temp[x++] = $(item).find(".if_is_id").is(':checked');
									temp[x++] = $(item).find(".if_is_first_letter").is(':checked');
									lis[i] = temp;
								});
								$.post(window.url.base_url+"home/ctrreportes/save_new_reporte",{ruta:ruta, expedientes_id:expedientes_id, nombre:nombre, titulo:titulo, lis:lis},function(resp){
									resp=JSON.parse(resp);
									if(resp.success!==false){
										show_wait("box-success","Éxito","El reporte se ha guardado exitosamente.",0);
										setTimeout(function(){ location.reload(); }, 2000);
									}else show_wait("box-danger","Error","Hubo un problema con la Base de datos. Intente más tarde.",0);
								});
							}else show_wait("box-danger","Error","Hubo un problema. Intente más tarde.",0);
						},
						error: function(data){
							return false;
						}
					});
				}
			});
		</script>
	</body>
</html>
