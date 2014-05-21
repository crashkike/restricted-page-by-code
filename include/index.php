<link rel="stylesheet" href="<?php echo plugins_url(); ?>/restricted-page-by-code/include/colorbox.css" />
<script src="<?php echo plugins_url(); ?>/restricted-page-by-code/include/jquery.colorbox.js"></script>
<script>
jQuery(document).ready(function(){
	<?php
	if(!isset($_SESSION['code_name'])) {
	?>
		jQuery.colorbox({
			inline:			true, 
		 	href:			"#inline_content",
		 	opacity: 		0.55,
		 	overlayClose: 	false,
		 	escKey:			false,
		 	closeButton:	false,
		 	width:			"80%", 
		 	height:			"80%"
		});
		jQuery("input[name='code']").focus();
	<?php
	}else{
		echo "";
	}
	?>
	
	
	
	// Funcion para detectar el envio del FORM		
	jQuery(document).ready(function($) {
		var cadena='';
		$('#ajax-form').submit(function(e){
			e.preventDefault();				
			jQuery.ajax({
				url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
			 	data:{
			   	'action': 	'buscar_posts',
			   	'code': 		$('#code').val(),
			   	'nonce': 	$('#nonce').val()
				},
			 	dataType: 'JSON',
			 	success:function(data){
			 		if( data.res == 1 ){
			 			console.log(data.name);
			 			jQuery(".nombre_codigo").html( data.name );
			 			jQuery("#posts_before").hide();
			 			jQuery("#posts_after").show();
			 			//jQuery.colorbox.close()
			 		}				   	
			   },
			 	error: function(errorThrown){
					// alert('error');
			      console.log(errorThrown);
				}
			});
		});
		
		$(".close_popup").click(function(){
			jQuery.colorbox.close();
		});
	});
});
</script>