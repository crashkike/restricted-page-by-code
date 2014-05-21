<?php
/*
 * Plugin Name: Restricted Page by Code
 * Plugin URI: http://www.enriqueacevedo.mx/plugins/abc
 * Description: Lorem ipsum
 * Version: 1.0
 * Author: Enrique Acevedo
 * Author URI: http://www.enriqueacevedo.mx
 * Requires at least: 3.9.1
 * Tested up to: 3.9
 */

/**
 * Plugin para wordpress
 *
 * Descripcion larga bla bla bla
 */
namespace PageByCode;

/** 
 * @package crashkike
 * @category Core
 * @author Enrique Acevedo
 */

/**
 * Clase WP_Code es la encargada de administrar los codigos
 *
 * The Bootstrapper is responsible for setting up the phpDocumentor application.
 */
class WP_Code {
	
	/**
	 * Inicializa funciones indispensables para el tema
	 * 
	 * Llamadas a Funciones a destacar: <br>
	 * <code>
	 * add_action( 'init', array( $this, 'code_post_type' ) );<br>
	 * add_action( 'wp_footer', 'popup_login' );<br>
	 * add_action('wp_ajax_buscar_posts', 'buscar_posts_callback');<br>
	 * add_action('wp_ajax_nopriv_buscar_posts', 'buscar_posts_callback');
	 * </code>
	 * 
	 */
	public function __construct(){
		add_action( 'init', array( $this, 'myStartSession' ), 1 );
    	add_action( 'init', array( $this, 'code_post_type' ) );
		//add_action( 'wp_footer', array( $this, 'popup_login' ) );
		add_action( 'wp_ajax_buscar_posts', array( $this, 'buscar_posts_callback' ) );
		add_action( 'wp_ajax_nopriv_buscar_posts', array( $this, 'buscar_posts_callback' ) );
		add_shortcode( 'page_private', array( $this, 'popup_login' ) );
    }
	
	
	
	/**
	 * Verifica si han sido creadas las sesiones
	 * 
	 * @since 1.0
	 * @return void
	 */ 
	public function myStartSession() {
	    if( !session_id() ) {
	        session_start();
	    }
	}
	
	
	/**
	 * Crea el custom post type de Codigos
	 * 
	 * El fin de crear el custom post type es para tener una mejor administracion
	 * de los codigos usando la interfaz de wordpress. Solo se activa el titulo
	 * ya que los demas campos no son necesarios. 
	 * <br><br>
	 * Esta funcion es invocada desde la funcion __construcc, no recibe parametros
	 * 
	 * @uses WP_Code::__construct()
	 * @since 1.0
	 * @return void
	 */
	public function code_post_type(){
		$labels =  array (
	        'name'                  => 'Codigo',
	        'singular_name'         => 'Codigo',
	        'menu_name'             => 'Codigo',
	        'add_new'               => 'Agregar Codigo',
	        'add_new_item'          => 'Agregar nuevo Codigo',
	        'edit'                  => 'Editar',
	        'edit_item'             => 'Editar Codigo',
	        'new_item'              => 'Nuevo Codigo',
	        'view'                  => 'Ver',
	        'view_item'             => 'Ver Codigo',
	        'search_items'          => 'Buscar Codigo',
	        'not_found'             => 'No se encontraron Codigo',
	        'not_found_in_trash'    => 'No se encontraron Codigo en la papelera',
	    );
	    $args = array(
	    	'description'           => 'Listado de Codigo',
	    	'public'                => true,
	    	'labels'                => $labels,
	    	'publicly_queryable'    => true,
	        'show_ui'               => true,
	        'show_in_menu'          => true,
	        'rewrite'               => array('slug' => 'codigo'),
	        'capability_type'       => 'post',
	        'hierarchical'          => true,
	        'has_archive'           => true,
	        'rewrite'               => array('slug' => 'codigo', 'with_front' => false ),
	        'query_var'             => 'codigo',
	        'supports'              => array( 'title'),        
	    );    
	    register_post_type('codigo', $args);
		require 'include/code-meta-box.php';
		flush_rewrite_rules( true );
	}

	/**
	 * Muestra la ventana emergente en la pagina seleccionada
	 * 
	 * El popup solo contiene un texto e input el cual envia el codigo para validar
	 * si existe en el registro de codigos, de ser TRUE deja visible la pagina
	 * 
	 * @author Enrique Acevedo
	 * @uses WP_Code::__construct()
	 * @since 1.0
	 * @return view
	 */
	public function popup_login(){
		ob_start();
		include( plugin_dir_path( __FILE__ ) . '/include/index.php' );
		$file_content = ob_get_contents();
		ob_end_clean ();
		echo $file_content;
		ob_start();
		include( plugin_dir_path( __FILE__ ) . '/templete/popup.php' );
		$file_content = ob_get_contents();
		ob_end_clean ();
		echo $file_content;
	}
	
	
	/**
	 * Respuesta Ajax
	 * 
	 * Realiza la peticion para buscar el codigo ingresado, de ser correcto el 
	 * codigo se deja ver el contenido de la pagina.<br> 
	 * 
	 * @author Enrique Acevedo
	 * @uses WP_Code::__construct()
	 * @uses Templete/popup.php
	 * @since 1.0
	 * @return view
	 */
	public function buscar_posts_callback(){
		global $wpdb; 
	
		$datos;
		$nonce = $_REQUEST['nonce'];
   		if( ! wp_verify_nonce( $nonce, 'myajax-post-comment-nonce' ) )
   			die( 'Te atrapamos maldito!');
		//el resto del código
		if( $_REQUEST['code'] != '' ){
				
			$table_name = $wpdb->prefix . "postmeta";
			$result = $wpdb->get_results("SELECT
				". $wpdb->prefix ."postmeta.post_id,
				". $wpdb->prefix ."posts.post_title as name
				FROM
				". $wpdb->prefix ."postmeta
				INNER JOIN ". $wpdb->prefix ."posts ON ". $wpdb->prefix ."posts.ID = ". $wpdb->prefix ."postmeta.post_id
				INNER JOIN ". $wpdb->prefix ."postmeta AS meta2 ON ". $wpdb->prefix ."posts.ID = meta2.post_id AND meta2.meta_key = 'active'
				WHERE
				". $wpdb->prefix ."postmeta.meta_key = 'code' AND
				". $wpdb->prefix ."postmeta.meta_value = '". $_REQUEST['code'] ."'
			"); 
		
			if( count($result) ){
				$_SESSION['code_name'] = $result[0]->name;
				$datos = array('res'		=>'1', "name" => $result[0]->name);
			}else{
				unset( $_SESSION['code_name'] );
				$datos = array('res'		=>'0', "name" => '', "fallo" => 'dos' );
			}		
		}else{
			unset( $_SESSION['code_name'] );
			$datos = array('res'		=>'0', "name" => '');
		}

		die( json_encode( $datos ) );
	}
}
 
$WP_Code = new WP_Code();
?>