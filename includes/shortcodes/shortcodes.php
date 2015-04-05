<?php

class CPShortcodes {

    function __construct() {
        add_action('admin_init', array(&$this, 'admin_init'));
    }

    /**
     * Enqueue Admin Scripts and Styles
     *
     * @return	void
     */
    function admin_init() {

        include_once( plugin_dir_path(__FILE__) . 'includes/class-shortcode-admin-insert.php' );

        // css
        wp_enqueue_style('chilpages-popup', plugin_dir_url( __FILE__ ) . 'assets/css/admin.css', false, '1.0', 'all');

        // js
        wp_enqueue_script('jquery-ui-sortable');
        wp_localize_script('jquery', 'CPPShortcodes', array('plugin_folder' => WP_PLUGIN_URL .'/includes/shortcodes') );
    }

}

new CPShortcodes();

