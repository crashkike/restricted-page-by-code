<?php
/**
 * Meta box de seccion area
 *
 * Incrustacion de los meta box en area, de los cuales estaran. titular, mision,
 * vision, objetivo, telefono de contacto, direccion fisica. 
 *
 * @author Enrique Acevedo
 * @uses WP_Code::code_post_type()
 * @since 1.0
 * @return view
 *
 */
namespace PageByCode;

class code_meta{
	public function __construct(){
		// Hook para declarar la accionacion de mas campos
		add_action( 'add_meta_boxes', array( $this, 'add_code_metaboxes' ) );
		// Hook para guardar los metabox personalizados
		add_action( 'save_post', array( $this, 'save_code_meta' ), 1, 2 );
    }
	
	public function add_code_metaboxes(){
		// Se agrega un metabox personalizado solo para el uso del apartado de area (creado en post_type)
		//add_meta_box( $id, $title, $callback, $page, $context, $priority, $callback_args );
		add_meta_box( 'code_datos', 'Link', array( $this, 'code_datos' ) , 'codigo', 'side', 'default' );
	}
	
	public function code_datos(){
		global $post;
	    // Noncename needed to verify where the data originated
	    echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' .
	    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
		?>
		<style type="text/css">
		table.area-input h4 { margin-bottom: 0px; }
		</style>
		<table width="100%" class="area-input">
			<tbody>
				<tr>
					<td>
						<h4>Code</h4>
						<input type="text" class="widefat" value="<?php echo get_post_meta($post->ID, 'code', true); ?>" name="code">
				</tr>	
				
				<tr>
					<td>
						<h4>Active</h4>
						<?php $ban = get_post_meta($post->ID, 'active', true); 
						if( $ban == '' ){
							$ban = 0;
						}
						?>
						<select id="active" name="active">
							<option value="0" <?php echo ($ban == 0)? 'selected="selected"' : '' ; ?> >Inactive</option>
							<option value="1" <?php echo ($ban == 1)? 'selected="selected"' : '' ; ?>>Active</option>
						</select>
				</tr>		
			</tbody>
		</table>
		<?php
	}
	
	public function save_code_meta( $post_id, $post ){
		
		// verify this came from the our screen and with proper authorization,
	    // because save_post can be triggered at other times
	    if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) {
	   		return $post->ID;
	    }
		
	    // Is the user allowed to edit the post or page?
	    if ( !current_user_can( 'edit_post', $post->ID ))
	        return $post->ID;
	    // OK, we're authenticated: we need to find and save the data
	    // We'll put it into an array to make it easier to loop though.
	    $events_meta['code'] 		= $_POST['code'];
		$events_meta['active'] 	= $_POST['active'];
		 

	    // Add values of $events_meta as custom fields
	    foreach ($events_meta as $key => $value) { // Cycle through the $events_meta array!
	        if( $post->post_type == 'revision' ) return; // Don't store custom data twice
	        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
	        if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
	            update_post_meta( $post->ID, $key, $value );
	        } else { // If the custom field doesn't have a value
	            add_post_meta( $post->ID, $key, $value );
	        }
	        if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	    }
	}
}

$clase = new code_meta(); 
?>