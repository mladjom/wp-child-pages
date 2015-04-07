<?php
/**
 * Plugin Name: Child Pages
 * Plugin URI: http://milentijevic.com/wordpress-plugins/wp-child-pages/
 * Description: This Plugin displays child pages of the parent page it is placed on.
 * Version: 1.0
 * Author: Mladjo
 * Author URI: http://milentijevic.com
 * Requires at least: 4.0
 * Tested up to: 4.2
 *
 * Text Domain: childpages
 * Domain Path: /languages/

 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('ChildPages')) :

    /**
     * Main Class
     */
    final class ChildPages {

        /**
         * @var string
         */
        public $version = '1.0';

        /**
         * @var string
         */
        public $slug = 'childpages';

        /**
         * @var ChildPages The single instance of the class
         * @since 1.0
         */
        protected static $_instance = null;

        /**
         * Main Class Instance
         *
         * Ensures only one instance of Class is loaded or can be loaded.
         *
         * @since 1.0
         * @static
         * @return ChildPages - Main instance
         */
        public static function instance() {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Cloning is forbidden.
         * @since 1.0
         */
        public function __clone() {
            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'childpages'), '1.0');
        }

        /**
         * Unserializing instances of this class is forbidden.
         * @since 1.0
         */
        public function __wakeup() {
            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'childpages'), '1.0');
        }

        /**
         * Class Constructor.
         */
        public function __construct() {
            $this->init_hooks();
            $this->define_constants();
            $this->includes();
            $this->get_thumbnail_sizes();
        }

        /**
         * Hook into actions and filters
         * @since  1.0
         */
        private function init_hooks() {
            add_action('init', array($this, 'init'), 0);
        }

        /**
         * Define Plugin Constants
         */
        private function define_constants() {
            $this->define('CP_VERSION', $this->version);
            $this->define('CHILDPAGES_VERSION', $this->version);
            $this->define('CP_SLUG', $this->slug);
            $this->define('CP_PLUGIN_DIR', plugin_dir_path(__FILE__));
        }

        /**
         * Define constant if not already set
         * @param  string $name
         * @param  string|bool $value
         */
        private function define($name, $value) {
            if (!defined($name)) {
                define($name, $value);
            }
        }

        /**
         * What type of request is this?
         * string $type frontend or admin
         * @return bool
         */
        private function is_request($type) {
            switch ($type) {
                case 'admin' :
                    return is_admin();
                case 'frontend' :
                    return (!is_admin());
            }
        }

        /**
         * Include required core files used in admin and on the frontend.
         */
        public function includes() {
            include_once( 'includes/widget/class.widget.php' );
            if ($this->is_request('admin')) {
                include_once( 'includes/shortcodes/shortcodes.php' );
            }

            if ($this->is_request('frontend')) {
                include_once( 'includes/shortcodes.php' );
                //add_action('wp_enqueue_scripts', array(__CLASS__, 'load_scripts'));
            }
        }

        /**
         * Init Plugin when WordPress Initialises.
         */
        public function init() {
            // Set up localisation
            $this->load_plugin_textdomain();
            add_post_type_support('page', 'excerpt');
        }

        /**
         * Load Localisation files.
         */
        public function load_plugin_textdomain() {

            load_plugin_textdomain('childpages', false, plugin_basename(dirname(__FILE__)) . '/languages');
        }

        /**
         * Register/queue frontend scripts.
         */
//        public static function load_scripts() {
//            wp_enqueue_style('childpages', plugins_url('assets/css/childpages.css', __FILE__), array(), CP_VERSION);
//            wp_enqueue_script('childpages', plugins_url('/assets/js/childpages.js', __FILE__), array('jquery'), CP_VERSION, true);
//        }
        /**
         * Get the available image sizes.
         */
        public function get_thumbnail_sizes() {

            global $_wp_additional_image_sizes;

            foreach (get_intermediate_image_sizes() as $s) {

                $sizes[$s] = $s;
            }

            return $sizes;
        }

        /**
         * Get the plugin url.
         * @return string
         */
        public function plugin_url() {
            return untrailingslashit(plugins_url('/', __FILE__));
        }

        /**
         * Get the plugin path.
         * @return string
         */
        public function plugin_path() {
            return untrailingslashit(plugin_dir_path(__FILE__));
        }

    }

    endif;

// The excerpt based on character
function string_limit_characters($excerpt, $substr = 0) {

    $string = strip_tags(str_replace('...', '...', $excerpt));
    if ($substr > 0) {
        $string = substr($string, 0, $substr);
    }
    return $string;
}

/**
 * Returns the main instance of Class to prevent the need to use globals.
 *
 * @since  1.0
 * @return ChildPages
 */
function CP() {
    return ChildPages::instance();
}

// Get Class Running
CP();
