<?php
/*
* Customize plugin shortcodes before inserting them. 
* This TinyMCE Shortcode Integration adds the plugin button to the Visual Editor. 
* Click on the button and pick one of the many shortcodes from the list. 
* You can set shortcode attribues before inserting the shortcode into the post. 
*/

class TinyMCE_Shortcodes {

	public $plugin_path;

	
	public function __construct() {

		$this->plugin_path = plugin_dir_path( __FILE__ );
		
		add_action( 'admin_init', array( &$this, 'action_admin_init' ) );
		
		// wp_ajax_... is only run for logged usrs
		add_action( 'wp_ajax_scn_check_url_action', array( &$this, 'ajax_action_check_url' ) );
		require_once( 'shortcodes/shortcodes.php' );

	}
	
	function action_admin_init() {
		
		global $pagenow;
		
		if ( ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) && get_user_option( 'rich_editing' ) == 'true' && ( in_array( $pagenow, array( 'post.php', 'post-new.php', 'page-new.php', 'page.php' ) ) ) )  {
		  	
			add_filter( 'mce_buttons',          array( &$this, 'filter_mce_buttons'          ) );
			add_filter( 'mce_external_plugins', array( &$this, 'filter_mce_external_plugins' ) );
			
			wp_register_style('scnStyles', $this->plugin_url() . 'shortcodes/css/styles.css');
			wp_enqueue_style('scnStyles');
		}
	}

	/**
	 * Register the shortcodes.
	 */
	public function register_shortcodes () {
		require_once( $this->plugin_path . 'shortcodesss/shortcodes.php' );

	} // End register_widgets()
	
	function filter_mce_buttons( $buttons ) {
		
		array_push( $buttons, '|', 'scn_button');
		return $buttons;
	}
	
	function filter_mce_external_plugins( $plugins ) {
		
        $plugins['TinymceShortcodes'] = $this->plugin_url() . 'shortcodes/tinymce/editor_plugin.php';
        return $plugins;
	}
	
	/**
	 * Returns the full URL of this plugin including trailing slash.
	 */
	function plugin_url() {
		
		return WP_PLUGIN_URL . '/' . str_replace( basename( __FILE__ ), "", plugin_basename( __FILE__ ) );
	}
	
	
	// AJAX ACTION ///////////////
	
	/**
	 * Checks if a given url (via GET or POST) exists.
	 * Returns JSON
	 * 
	 * NOTE: for users that are not logged in this is not called.
	 *       The client recieves <code>-1</code> in that case.
	 */
	function ajax_action_check_url() {

		$hadError = true;

		$url = isset( $_REQUEST['url'] ) ? $_REQUEST['url'] : '';

		if ( strlen( $url ) > 0  && function_exists( 'get_headers' ) ) {
				
			$file_headers = @get_headers( $url );
			$exists       = $file_headers && $file_headers[0] != 'HTTP/1.1 404 Not Found';
			$hadError     = false;
		}

		echo '{ "exists": '. ($exists ? '1' : '0') . ($hadError ? ', "error" : 1 ' : '') . ' }';

		die();
	}

}

new TinyMCE_Shortcodes();
?>
