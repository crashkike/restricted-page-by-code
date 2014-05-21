
<!-- start: Muestra POPUP -->
<div style='display:none'>
	<div id='inline_content' style='padding:10px; background:#fff;'>
		<div id="posts_before">
			<form id="ajax-form" method="post" action="">
				<input type="text" name="code" id="code"/>
				<input type="hidden" name="nonce" id="nonce" value="<?php echo  wp_create_nonce( 'myajax-post-comment-nonce' ); ?>"/>
				<input type="submit" name="enviar" value="enviar"/>				
			</form>
		</div>
		<div id="posts_after" style='display:none'>
			<h1>BIENVENIDO</h1>
			<p class="nombre_codigo"></p>
			<button class="close_popup">Aceptar</button>
		</div>
	</div>
</div>
<!-- end: Muestra POPUP -->