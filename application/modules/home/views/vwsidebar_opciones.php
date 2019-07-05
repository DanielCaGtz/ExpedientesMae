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
				function show_wait(main_class,main_title,main_text){
					$("#wait").fadeOut(1400,function(){
						$(this).removeClass("box-warning").removeClass("box-success").removeClass('box-danger').addClass(main_class);
						$(this).find(".title").html(main_title);
						$(this).find(".body").html(main_text);
						$(this).show();
						//$('html, body').animate({scrollTop: $("#wait").offset().top}, 1000);
						$(this).find(".overlay").hide();
						$(this).fadeIn(1400);
					});
				}
				function deactivate_all(num){
					if(parseInt(num)!==1 && parseInt(num)!==2){
						$(".opciones").attr("checked", false);
						$(".definir_opciones_parent_div").addClass("invisible");
					}
					if(parseInt(num)!==2){
						$("#expedientes").val("0");
						$(".campo_opcion_div").addClass("invisible");
						$(".expediente_catalogo_div").addClass("invisible");
					}
					$.each($("#campo_texto option"),function(i,item){
						if($(item).attr("data-type")!="0") $(item).remove();
					});
					$("#campo_texto").val("0");
					$("#campo_texto").attr("data-id",0);
					$.each($("#sortable .row_childs"),function(i,item){
						$(item).remove();
					});
					$(".button_final").addClass("invisible");
					$("#save").attr("data-final",0);
					$(".agregar_opcion_div").addClass("invisible");
					$(".definir_opciones_child_div").addClass("invisible");
				}
				function change_expedientes(num){
					var id=$("#expedientes").val();
					if(parseInt(id)>0){
						deactivate_all(2);
						$(".campo_opcion_div").removeClass("invisible");
						$.post(window.url.base_url+"home/ctrhome/consulta_campos_por_expediente",{id:id},function(resp){
							resp=JSON.parse(resp);
							if(resp.success!==false){
								$.each(resp.result,function(i,item){
									$("#campo_texto").append("<option data-type='1' value='"+item.id+"'>"+item.nombre+"</option>");
								});
							}
							if(parseInt(num)>0){
								$("#campo_texto").val(num);
								$("#campo_texto").attr("data-id",num);
								$(".button_final").removeClass("invisible");
								$("#save").attr("data-final",2);
							}
						});
					}
				}
				
				$("#campos").on("change",function(){
					var id=$(this).val();
					if(parseInt(id)>0){
						deactivate_all(0);
						$.post(window.url.base_url+"home/ctrhome/consultar_campo_multiple",{id:id},function(resp){
							resp=JSON.parse(resp);
							$(".definir_opciones_parent_div").removeClass("invisible");
							if(resp.success!==false){
								deactivate_all(1);
								if(resp.result[0].campos_expedientes_opcional_id!==null){
									$(".opciones").attr("checked", false);
									$(".opciones[value=1]").prop("checked",true);
									$(".expediente_catalogo_div").removeClass("invisible");
									$("#expedientes").val(resp.result[0].expedientes_id);
									change_expedientes(resp.result[0].campos_expedientes_opcional_id);
								}else{
									$(".opciones").attr("checked", false);
									$(".opciones[value=0]").prop("checked",true);
									$(".agregar_opcion_div").removeClass("invisible");
									$(".definir_opciones_child_div").removeClass("invisible");
									$(".button_final").removeClass("invisible");
									$("#save").attr("data-final",1);
									$.each(resp.result,function(i,item){
										$("#sortable").append('<div class="row row_childs"><div class="col-lg-11"><div class="input-group"><span class="input-group-addon">Opción</span><input type="text" data-id="'+item.id+'" class="form-control text_opcion" value="'+item.opcion_nombre+'"></div></div><div class="col-lg-1"><a class="eliminar_opcion btn"><i class="fa fa-remove"></i></a></div></div>');
									});
								}
							}
						});
					}
				});
				$(".opciones").on("change",function(){
					deactivate_all(1);
					if(parseInt($(".opciones:checked").val())>0){
						$(".expediente_catalogo_div").removeClass("invisible");
					}else{
						$(".agregar_opcion_div").removeClass("invisible");
						$(".definir_opciones_child_div").removeClass("invisible");
					}
				});
				$("#add_new_opc").on("click",function(){
					$(".button_final").removeClass("invisible");
					$("#save").attr("data-final",1);
					$("#sortable").append('<div class="row row_childs"><div class="col-lg-11"><div class="input-group"><span class="input-group-addon">Opción</span><input type="text" data-id="0" class="form-control text_opcion"></div></div><div class="col-lg-1"><a class="eliminar_opcion btn"><i class="fa fa-remove"></i></a></div></div>');
				});
				$("#sortable").on("click",".eliminar_opcion",function(){
					var id=$(this).parent().parent().find(".text_opcion").attr("data-id");
					var element=$(this).parent().parent();
					if(confirm("¿Realmente desea eliminar la opción?")){
						$.post(window.url.base_url+"home/ctrhome/delete_opcion_multiple",{id:id},function(resp){
							resp=JSON.parse(resp);
							if(resp.success!==false){
								element.remove();
								var x=0;
								$.each($("#sortable .row_childs"),function(i,item){x++;});
								if(parseInt(x)==0){
									$(".button_final").addClass("invisible");
									$("#save").attr("data-final",0);
								}
							}
						});
					}
				});
				$("#expedientes").on("change",function(){
					change_expedientes(0);
				});
				$("#campo_texto").on("change",function(){
					var id=$(this).val();
					if(parseInt(id)>0){
						$(".button_final").removeClass("invisible");
						$("#save").attr("data-final",2);
					}else{
						$(".button_final").addClass("invisible");
						$("#save").attr("data-final",0);
					}
				});
				$("#save").on("click",function(){
					if(parseInt($(this).attr("data-final"))>0){
						if(parseInt($(this).attr("data-final"))==1){
							var lis=new Array();
							$.each($("#sortable .row_childs"),function(i,item){
								var temp=new Array();
								var x = 0;
								temp[x++] = $(item).find(".text_opcion").val();
								temp[x++] = $(item).find(".text_opcion").attr("data-id");
								lis[i]=temp;
							});
							var id=$("#campos").val();
							$.post(window.url.base_url+"home/ctrhome/save_campos_multiples",{data:lis, id:id},function(resp){
								resp=JSON.parse(resp);
								if(resp.success!==false){
									show_wait("box-success","Éxito","Elemento guardado exitosamente.");
									$.each($("#sortable .row_childs"),function(i,item){
										$(item).find(".text_opcion").attr("data-id",resp.result[i]);
									});
								}else{
									show_wait("box-danger","Error","Hubo un problema. Intente más tarde.");
								}
							});
						}else if(parseInt($(this).attr("data-final"))==2){
							var id_opc=$("#campo_texto").val();
							var id_campos=$("#campo_texto").attr("data-id");
							var id=$("#campos").val();
							show_wait("box-warning","Guardando","Por favor espere.");
							$.post(window.url.base_url+"home/ctrhome/save_campos_multiples_opc",{id_opc:id_opc, id:id, id_campos:id_campos},function(resp){
								resp=JSON.parse(resp);
								if(resp.success!==false){
									$("#campo_texto").attr("data-id",resp.result);
									show_wait("box-success","Éxito","Elemento guardado exitosamente.");
								}else{
									show_wait("box-danger","Error","Hubo un problema. Intente más tarde.");
								}
							});
						}
					}
				});

				$("#sortable").sortable().disableSelection();
				
				$(".content .callout .close").on("click",function(){
					$(this).parent().remove();
				});
				$(".close_wait").on("click",function(){
					$(this).parent().parent().parent().hide();
				});
			});
		</script>
	</body>
</html>
