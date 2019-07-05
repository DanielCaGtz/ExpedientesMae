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
				function open_new_tab(url_){
					var win = window.open(url_, '_self');
  					win.focus();
				}
				function show_wait(timeout,main_class,main_title,main_text){
					$("#wait").fadeOut(timeout,function(){
						$(this).removeClass("box-warning").removeClass("box-success").removeClass('box-danger').addClass(main_class);
						$(this).find(".title").html(main_title);
						$(this).find(".body").html(main_text);
						$(this).show();
						$('html, body').animate({scrollTop: $("#wait").offset().top}, 1000);
						$(this).find(".overlay").hide();
						$(this).fadeIn(timeout);
					});
				}
				function get_li(nombre="", id=0){
					var id_inv=$("#inventario").val();
					nombre=$("#inventario option:selected").text();
					var add="<li class='ui-state-default ui-sortable-handle'>";
						add+="<div class='box box-default collapsed-box'>";
							add+="<div class='box-header with-border'>";
								add+="<div data-id='"+id+"' data-inventario='"+id_inv+"' class='input' style='border:none;width: 70%;font-size: 14px;'>"+nombre+"</div>";
								add+="<div class='box-tools pull-right'>";
									add+="<button type='button' style='margin-left: 5px;' class='btn btn-box-tool del'><i class='fa fa-minus'></i></button>";
								add+="</div>";
							add+="</div>";
						add+="</div>";
					add+="</li>";
					return add;
				}

				$("#add_new_record").on("click",function(){
					$("#new_name").show();
					$("#new_catalogo").show();
				});
				$("#add").on("click",function(){
					$("#save").attr("data-unsaved",1);
					$("#sortable").append(get_li());
				});
				$("#eliminar").on("click",function(){
					var id = $("#paquetes").val();
					if(parseInt(id)>0 && confirm("¿Realmente desea eliminar?")){
						$.post(window.url.base_url+"home/ctrhome/delete_paquete",{id:id},function(resp){
							location.reload();
						});
					}
				});
				$("#save").on("click",function(){
					if(!$("#save").prop('disabled')){
						var lis=new Array();
						$.each($("#sortable li"),function(i,item){
							var child=new Array();
							var s=0;
							child[s++]=$(item).find(".input").html();
							child[s++]=$(item).find(".input").attr("data-inventario");
							child[s++]=$(item).find(".input").attr("data-id");
							lis[i]=child;
						});
						show_wait(1000,"box-warning","Guardando","Por favor espere.");
						$.post(window.url.base_url+"home/ctrhome/save_paquetes",{data:lis, nombre:$("#nombre_paquete").val()},function(resp){
							resp=JSON.parse(resp);
							if(resp.success!==false){
								setTimeout(function(){
									show_wait(1600,"box-success","Éxito","Elementos guardados exitosamente.");
									setTimeout(function(){
										location.reload();
									},2500);
								},1200);
							}else{
								setTimeout(function(){
									show_wait(1600,"box-danger","Error","Hubo un problema. Intente más tarde.");
								},1200);
							}
						});
					}
				});
				$("#sortable").on("click",".del",function(){
					var parent=$(this).parent().parent();
					var elem=parent.parent().parent();
					var id_main=parent.find("input").attr("data-id");
					elem.remove();
				});
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
