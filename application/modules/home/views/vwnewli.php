<li class="ui-state-default first_child" data-id="0">
	<div class="example-modal">
		<div class="modal modal-info">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close collapse_modal" data-dismiss="collapse" aria-label="Cerrar"><i class="fa fa-minus"></i></button>
						<button type="button" class="close edit_text_modal" data-dismiss="collapse" aria-label="Editar"><i class="fa fa-pencil"></i></button>
						<h4 class="modal-title"><div contenteditable="true" class="editable" style="width: 90%;">Nuevo expediente</div></h4>
						<button type="button" class="close edit_text_modal_subtitle" data-dismiss="collapse" aria-label="Editar"><i class="fa fa-pencil"></i></button>
						<h5 class="modal-title"><div contenteditable="true" class="editable_subtitle" style="width: 90%;">Subtítulo</div></h5>
					</div>
					<div class="modal-body"><ul class="connectedSortable_child"></ul></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-left add_field" data-dismiss="modal">Agregar campo</button>
						<button type="button" class="btn btn-success pull-left change_color md-trigger" data-modal="modal-1" data-color="bg-aqua" data-dismiss="modal">Elegir color</button>
						<button type="button" class="btn btn-warning pull-left change_icon md-trigger" data-modal="modal-2" data-icon="ion-document" data-dismiss="modal">Elegir ícono</button>
						<button type="button" class="btn btn-danger delete_expediente">Eliminar expediente</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</li>
<script>
	$(document).ready(function(){
		$(".connectedSortable_child").sortable().disableSelection();
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
	});
</script>