<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!class_exists('Somc_Mladjom_Widget')) {
	
	class Somc_Mladjom_Widget extends WP_Widget {
 		
	protected $version = '1.0.0';

	protected $plugin_slug = 'somc-mladjom'; 
		/*--------------------------------------------------*/
		/* Constructor
		/*--------------------------------------------------*/
	
		/**
		* Specifies the classname and description, instantiates the widget,
		* loads localization files, and includes necessary stylesheets and JavaScript.
		*/
		public function __construct() {
		// widget actual processes
	
			/* Widget settings. */
			$widget_ops = array( 'classname' => $this->plugin_slug, 'description' =>__('Somc Mladjom.', $this->plugin_slug ));
		
			/* Widget control settings. */
			$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => $this->plugin_slug );
		
			/* Create the widget. */
			$this->WP_Widget( $this->plugin_slug, __('Somc Mladjom', $this->plugin_slug), $widget_ops, $control_ops );
			
			if ( is_active_widget(false, false, $this->id_base) ){
			// Load public-facing style sheet and JavaScript.
			//add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			//add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );				
			}
		
			// Add support for translations
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		
		}// end constructor
 
		/*--------------------------------------------------*/
		/* Form
		/*--------------------------------------------------*/ 
		/**
		* Generates the administration form for the widget.
		*
		* @param array instance The array of keys and values for the widget.
		*/
		public function form( $instance ) {
	 	// outputs the options form on admin

		/* Set up some default widget settings. */
		/* Make sure all keys are added here, even with empty string values. */
		$defaults = array(
			'title' => '',
			'orderby' => 'menu_order',
			'order' => 'DESC',
			'thumb_size' => 50,
			'show_title'=>'',					
			'show_excerpt'=>'',					
			'excerpt_lenght'=>20					
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

			// Display the admin form
			include( plugin_dir_path(__FILE__) . '/views/admin.php' );

		} // end form

		/**
		 * Get an array of the available orderby options.
		 * @return array
		 */
		protected function get_orderby_options () {
			return array(
						'none' => __( 'No Order', $plugin_slug ),
						'ID' => __( 'Entry ID', $plugin_slug ),
						'title' => __( 'Title', $plugin_slug ),
						'date' => __( 'Date Added', $plugin_slug ),
						'menu_order' => __( 'Specified Order Setting', $plugin_slug ),
						'rand' => __( 'Random Order', $plugin_slug )
						);
		} // End get_orderby_options()

		/**
		 * Get an array of the available order options.
		 * @return array
		 */
		protected function get_order_options () {
			return array(
						'ASC' => __( 'Ascending', $plugin_slug ),
						'DESC' => __( 'Descending', $plugin_slug )
						);
		} // End get_order_options()

		/*--------------------------------------------------*/
		/* Update
		/*--------------------------------------------------*/ 
		/**
		* Processes the widget's options to be saved.
		*
		* @param array new_instance The previous instance of values before the update.
		* @param array old_instance The new instance of values to be generated via the update.
		*/
		public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		// TODO: Here is where you update your widget's old values with the new, incoming values
		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );

		/* Make sure the integer values are definitely integers. */
  	$instance['excerpt_lenght'] = intval($new_instance['excerpt_lenght']);

		/* The select box is returning a text value, so we escape it. */
		$instance['orderby'] = esc_attr( $new_instance['orderby'] );
		$instance['order'] = esc_attr( $new_instance['order'] );
		$instance['thumb_size'] = esc_attr( $new_instance['thumb_size'] );

		$instance['show_excerpt'] = strip_tags($new_instance['show_excerpt']);
		$instance['show_title'] = strip_tags($new_instance['show_title']);

		return $instance;
		} // End update()


 
		/*--------------------------------------------------*/
		/* Display
		/*--------------------------------------------------*/

		/**
		* Outputs the content of the widget.
		*
		* @param array args The array of form elements
		* @param array instance The current instance of the widget
		*/
		public function widget( $args, $instance ) {

			extract( $args, EXTR_SKIP );
			
			// Only run on pages
			if ( !is_page() )
			return;
	
			global $post;
			$parents = $post->ID;
	
			$args = array(
				'child_of' => $parents,
			);
			$subpages = get_pages( $args);
			
			// Only run on if page has children
			if ( empty( $subpages ) ) 
				return;			
			
			/* Before widget (defined by themes). */			
			echo $before_widget;	


			// Here is where you manipulate your widget's values based on their input fields
			include( plugin_dir_path( __FILE__ ) . '/views/widget.php' );
			
			// After widget (defined by themes). */
			echo $after_widget;
			} // end widget


	/**
	* Load the plugin text domain for translation.
	*
	* @since 1.1.0
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
	
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-widget-styles', plugins_url( 'widget.css', __FILE__ ), array(), $this->version );
	}
	*/

	/**
	* Register and enqueues public-facing JavaScript files.
	*
	* @since 1.0.0
	
	public function enqueue_scripts() {
		if(wp_script_is('jquery')) {
    	// do nothing
			} else {
    // insert jQuery
			wp_enqueue_script( 'jquery' );
		}
		wp_enqueue_script( $this->plugin_slug . '-widget-scripts', plugins_url( 'widget.js', __FILE__ ), array( 'jquery' ), $this->version );
	}
	*/
 
} // end class


add_action( 'widgets_init', create_function( '', 'register_widget("Somc_Mladjom_Widget");' ) );

}