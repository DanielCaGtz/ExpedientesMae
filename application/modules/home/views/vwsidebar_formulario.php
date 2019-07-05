		<script src="<?php echo base_url(); ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="<?php echo base_url(); ?>plugins/select2/dist/js/select2.js"></script>
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
				$(".multicombo").select2({tags:true});;
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

				$("#agregar_inventario").on("click",function(){
					var id=$(".multicombo").val();
					var name=$(".multicombo option:selected").text();
					var temp="<p><label>"+name+" <input type='text' style='width:40px;' data-id='"+id+"' class='cantidad_producto' placeholder='Cantidad' value='1'><button type='button' class='btn btn-box-tool eliminar_inventario_elemento'><i class='fa fa-remove'></i></button></label></p>";
					$("#contenedor_inventario").append(temp);
				});
				$("#paquetes_selector").on("change",function(){
					var id=$(this).val();
					if(parseInt(id)>0){
						$.post(window.url.base_url+"home/ctrhome/get_paquete_detalle",{id:id},function(resp){
							resp=JSON.parse(resp);
							if(resp.success!==false){
								$("#contenedor_inventario").empty();
								$.each(resp.result,function(i,item){
									var temp="<p><label>"+item.nombre+" <input type='text' style='width:40px;' data-id='"+item.id_inventario+"' class='cantidad_producto' placeholder='Cantidad' value='1'><button type='button' class='btn btn-box-tool eliminar_inventario_elemento'><i class='fa fa-remove'></i></button></label></p>";
									$("#contenedor_inventario").append(temp);
								});
							}
						});
					}
				});
				$("#main_content").on("click",".eliminar_inventario_elemento",function(){
					$(this).parent().parent().remove();
				});
				$("#main_content .formulario").submit(function(evt){
					evt.preventDefault();
					var lis = new Array();
					$.each($(".campo_editable"),function(i,item){
						var temp_array = new Array();
						var x = 0;
						if($(item).attr("data-type")=="radio"){
							var temp=$(item).find("input[name='field_"+$(item).attr("data-id")+"']:checked").val();
							if(temp===undefined) temp_array[x++]=0;
							else temp_array[x++]=temp;
						}else if($(item).attr("data-type")=="multicombo"){
							var temp = new Array();
							var s = 0;
							$.each($("#contenedor_inventario .cantidad_producto"),function(i,item){
								var temp_temp = new Array();
								temp_temp[0] = $(item).attr("data-id");
								temp_temp[1] = $(item).val();
								temp[s++]=temp_temp;
							});
							if(s==0)temp_array[x++]=0;
							else temp_array[x++]=temp;
						}else temp_array[x++]=$(item).val();
						temp_array[x++]=$(item).attr("data-type");
						temp_array[x++]=$(item).attr("data-bloque");
						temp_array[x++]=$(item).attr("data-id");
						temp_array[x++]=$(item).attr("data-campos");
						lis[i]=temp_array;
					});
					var id_exp=$("#main_content").attr("data-expediente");
					show_wait("box-warning","Guardando","Por favor espere.",1);
					$.post(window.url.base_url+"home/ctrhome/save_formulario",{id_exp:id_exp, data:lis},function(resp){
						resp=JSON.parse(resp);
						if(resp.success!==false){
							$.each($("#main_content .formulario"),function(e,key){
								$(key)[0].reset();
							});
							setTimeout(function(){show_wait("box-success","Éxito","Elementos guardados exitosamente.",0);},1200);
						}else setTimeout(function(){show_wait("box-danger","Error","Hubo un problema. Intente más tarde.",0);},1200);
					});
				});
			});
		</script>
	</body>
</html>
