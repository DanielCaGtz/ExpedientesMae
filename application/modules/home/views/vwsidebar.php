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
					$("#wait").fadeOut(1600,function(){
						$(this).removeClass("box-danger").removeClass("box-success").removeClass("box-warning").addClass(show);
						$(this).find(".title").html(title);
						$(this).find(".body").html(title2);
						$(this).show();
						//$('html, body').animate({scrollTop: $("#wait").offset().top}, 1000);
						if(parseInt(over)>0) $(this).find(".overlay").show(); else $(this).find(".overlay").hide();
						$(this).fadeIn(1600);
					});
				}
				$('input[type="checkbox"].minimal').iCheck({checkboxClass: 'icheckbox_minimal-blue'});

				$("#sortable").sortable().disableSelection();
				$(".connectedSortable_child").sortable().disableSelection();
				$("#add_li").on("click",function(){
					$.post(window.url.base_url+"home/ctrhome/get_new_li",{},function(resp){
						$("#sortable").append(resp);
					});
				});
				$("#menus").on("click",".add_field",function(){
					var id = $(this).parent().parent().parent().parent().parent().parent().attr("data-id");
					var elem = $(this);
					$.post(window.url.base_url+"home/ctrhome/get_new_li_child",{id:id},function(resp){
						$(elem).parent().parent().find(".connectedSortable_child").append(resp);
					});
				});

				$(".a_colores").on("click",function(){
					$.each($(".a_colores"),function(i,item){
						$(item).removeClass("a_colores_active");
					});
					$(this).addClass("a_colores_active");
				});
				$(".a_icons").on("click",function(){
					$.each($(".a_icons"),function(i,item){
						$(item).removeClass("a_icons_active");
					});
					$(this).addClass("a_icons_active");
				});
				$("#menus").on("click",".collapse_modal",function(){
					var par = $(this).parent().parent().find(".modal-body");
					if(!par.hasClass("collapsed")){
						$(this).find(".fa").removeClass("fa-minus").addClass("fa-plus");
						par.addClass("collapsed");
					}else{
						$(this).find(".fa").removeClass("fa-plus").addClass("fa-minus");
						par.removeClass("collapsed");
					}
					par.slideToggle('slow');
				});
				$(".content .callout .close").on("click",function(){$(this).parent().hide();});
				$(".close_wait").on("click",function(){$(this).parent().parent().parent().hide();});
				$("#menus").on("click",".edit_editable",function(){
					var text=$(this).parent().parent().find(".editable").html();
					$(this).parent().parent().find(".editable").html("").focus().html(text);
				});
				$("#menus").on("click",".edit_text_modal",function(){
					var text=$(this).parent().find(".editable").html();
					$(this).parent().find(".editable").html("").focus().html(text);
				});
				$("#menus").on("click",".edit_text_modal_subtitle",function(){
					var text=$(this).parent().find(".editable_subtitle").html();
					$(this).parent().find(".editable_subtitle").html("").focus().html(text);
				});
				$("#menus").on("click",".delete_expediente",function(){
					var id = $(this).parent().parent().parent().parent().parent().parent().attr("data-id");
					var elem = $(this).parent().parent().parent().parent().parent().parent();
					$.post(window.url.base_url+"home/ctrhome/consult_expediente_to_delete",{id:parseInt(id)},function(resp){
						resp=JSON.parse(resp);
						var s=false;
						if(resp.result===false){
							if(confirm("¿Realmente desea eliminar este expediente? Todos los campos relacionados se eliminarán.")) s=true;
						}else var s=true;
						if(s!==false){
							show_wait("box-warning","Guardando","Por favor espere.");
							$.post(window.url.base_url+"home/ctrhome/delete_expediente",{id:parseInt(id)},function(result){
								result=JSON.parse(result);
								if(result.success!==false){
									elem.remove();
									show_wait("box-success","Éxito","Elemento eliminado exitosamente.");
								}else{
									show_wait("box-danger","Error","Hubo un problema. Intente más tarde.");
								}
							});
						}
					});
				});
				$("#menus").on("click",".delete_second",function(){
					var id = $(this).parent().parent().parent().attr("data-id");
					var elem = $(this).parent().parent().parent();
					$.post(window.url.base_url+"home/ctrhome/consult_campo_to_delete",{id:parseInt(id)},function(resp){
						resp=JSON.parse(resp);
						var s=false;
						if(resp.result===false){
							if(confirm("¿Realmente desea eliminar este campo? Todos los datos relacionados se eliminarán.")) s=true;
						}else var s=true;
						if(s!==false){
							show_wait("box-warning","Guardando","Por favor espere.");
							$.post(window.url.base_url+"home/ctrhome/delete_campo",{id:parseInt(id)},function(result){
								result=JSON.parse(result);
								if(result.success!==false){
									elem.remove();
									show_wait("box-success","Éxito","Elemento eliminado exitosamente.");
								}else{
									show_wait("box-danger","Error","Hubo un problema. Intente más tarde.");
								}
							});
						}
					});
				});
				$("#save").on("click",function(){
					var lis=new Array();
					$.each($("#sortable .first_child"),function(i,item){
						var temp=new Array();
						var x = 0;
						temp[x++] = $(item).find(".modal-title .editable").html();
						temp[x++] = $(item).attr("data-id");
						temp[x++] = $(item).find(".modal-title .editable_subtitle").html();
						temp[x++] = $(item).find(".change_color").attr("data-color");
						temp[x++] = $(item).find(".change_icon").attr("data-icon");
						lis[i]=temp;
					});
					show_wait("box-warning","Guardando","Por favor espere.",1);
					$.post(window.url.base_url+"home/ctrhome/save_expedientes",{data:lis},function(resp){
						resp=JSON.parse(resp);
						if(resp.success!==false){
							$.each($("#sortable .first_child"),function(i,item){
								$(item).attr("data-id",resp.result[i]);
							});

							var lis_second=new Array();
							$.each($("#sortable .second_child"),function(i,item){
								var temp=new Array();
								var x = 0;
								temp[x++] = $(item).find(".box-title .editable").html();
								temp[x++] = $(item).attr("data-id");
								temp[x++] = $(item).find(".campos_editable").val();
								temp[x++] = $(item).parent().parent().parent().parent().parent().parent().parent().attr("data-id");
								temp[x++] = $(item).find(".bloques_editable").val();
								temp[x++] = $(item).find(".required_editable").is(':checked') ? 1 : 0;
								lis_second[i]=temp;
							});
							$.post(window.url.base_url+"home/ctrhome/save_expedientes_campos",{data:lis_second},function(resp){
								resp=JSON.parse(resp);
								if(resp.success!==false){
									$.each($("#sortable .second_child"),function(i,item){
										$(item).attr("data-id",resp.result[i]);
									});
									setTimeout(function(){show_wait("box-success","Éxito","Elementos guardados exitosamente.",0);},1200);
								}else setTimeout(function(){show_wait("box-danger","Error","Hubo un problema con los campos a guardar. Intente más tarde.",0);},1200);
							});
						}else setTimeout(function(){show_wait("box-danger","Error","Hubo un problema. Intente más tarde.",0);},1200);
					});
				});
			});
			var ModalEffects = (function() {
				function init() {
					var overlay = document.querySelector( '#menus .md-overlay' );
					[].slice.call( document.querySelectorAll( '.md-trigger' ) ).forEach( function( el, i ) {
						var modal = document.querySelector( '#' + el.getAttribute( 'data-modal' ) ), close = modal.querySelector( '.md-close' );
						function removeModal( hasPerspective ) {
							classie.remove( modal, 'md-show' );
							if(hasPerspective) classie.remove( document.documentElement, 'md-perspective' );
						}

						function removeModalHandler() {
							removeModal( classie.has( el, 'md-setperspective' ) );
						}

						el.addEventListener( 'click', function( ev ) {
							classie.add( modal, 'md-show' );
							$.each($(".change_color"),function(i,item){
								$(item).removeClass("active_modal");
							});
							$.each($(".change_icon"),function(i,item){
								$(item).removeClass("active_modal");
							});
							if($(el).attr("data-color")!==undefined){
								var color=$(el).attr("data-color");
								$(el).addClass("active_modal");
								$.each($("#modal-1 .a_colores"),function(i,item){
									if(color==$(item).attr("data-clase")) $(item).addClass("a_colores_active");
									else $(item).removeClass("a_colores_active");
								});
							}else if($(el).attr("data-icon")!==undefined){
								var icon=$(el).attr("data-icon");
								$(el).addClass("active_modal");
								$.each($("#modal-2 .a_icons"),function(i,item){
									if(icon==$(item).attr("data-icon")) $(item).addClass("a_icons_active");
									else $(item).removeClass("a_icons_active");
								});
							}
						});

						close.addEventListener( 'click', function( ev ) {
							ev.stopPropagation();
							if($(el).hasClass("active_modal")){
								if($(el).attr("data-color")!==undefined){
									var color="bg-gray";
									$.each($("#modal-1 .a_colores"),function(i,item){
										if($(item).hasClass("a_colores_active")) color=$(item).attr("data-clase");
									});
									$(el).attr("data-color",color);
								}else{
									var icon="ion-folder";
									$.each($("#modal-2 .a_icons"),function(i,item){
										if($(item).hasClass("a_icons_active")) icon=$(item).attr("data-icon");
									});
									$(el).attr("data-icon",icon);
								}
							}
							removeModalHandler();
						});
					});
				}
				init();
			})();
		</script>
	</body>
</html>
