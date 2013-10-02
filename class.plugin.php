<?php
/*
Plugin class
*/


class Somc_Mladjom {
	
	protected $version = '1.0.0';

	protected $plugin_slug = 'somc-mladjom';

	protected static $instance = null;
		
	public function __construct() {

	// Add support for translations
	add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
	
	
	// Load admin style sheet and JavaScript.
	//add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
	//add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

	// Load public-facing style sheet and JavaScript.
	add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

	register_activation_hook( __FILE__, array( $this, 'activate' ) );
	//register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
	}


	/**
	* Return an instance of this class.
	*
	* @since 1.0.0
	*
	* @return object A single instance of this class.
	*/
	public static function get_instance() {
	
	// If the single instance hasn't been set, set it now.
	if ( null == self::$instance ) {
	self::$instance = new self;
	}
	
	return self::$instance;
	}

	/**
	* Fired when the plugin is activated.
	*
	* @since 1.0.0
	*
	* @param boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	*/
	public static function activate( $network_wide ) {
			flush_rewrite_rules();
	}
	
	/**
	* Load the plugin text domain for translation.
	*
	* @since 1.0.0
	*/
	public function load_plugin_textdomain() {
	
	$domain = $this->plugin_slug;
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
	
	load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	/**
	* Register and enqueue public-facing style sheet.
	*
	* @since 1.0.0
	*/
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/app.css', __FILE__ ), array(), $this->version );
	}
	
	/**
	* Register and enqueues public-facing JavaScript files.
	*
	* @since 1.0.0
	*/
	public function enqueue_scripts() {
		if(wp_script_is('jquery')) {
    	// do nothing
			} else {
    // insert jQuery
			wp_enqueue_script( 'jquery' );
			}
			wp_enqueue_script( $this->plugin_slug . '-plugins', plugins_url( 'assets/js/plugins.js', __FILE__ ), array( 'jquery' ), $this->version );
			wp_enqueue_script( $this->plugin_slug . '-main', plugins_url( 'assets/js/main.js', __FILE__ ), array( 'jquery' ), $this->version );
		}

	}
	// The excerpt based on character
	function string_limit_characters($excerpt, $substr=0)
	{
	
		$string = strip_tags(str_replace('...', '...', $excerpt));
		if ($substr>0) {
			$string = substr($string, 0, $substr);
		}
		return $string;
	}
	// Catching ID and echoing content
	function get_this_post() {
		$page_id = $_REQUEST["post_id"]; 
		$page_data = get_page( $page_id );	
		echo  $page_data->post_content ;// echo the content
		die(); // this is required to return a proper result
}
// creating Ajax call for WordPress
add_action( 'wp_ajax_nopriv_get_this_post', 'get_this_post' );
add_action( 'wp_ajax_get_this_post', 'get_this_post' );


new Somc_Mladjom;
