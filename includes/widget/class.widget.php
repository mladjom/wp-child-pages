<?php

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

if (!class_exists('ChildPagesWidget')) {

    class ChildPagesWidget extends WP_Widget {

        protected $widget_slug = 'childpages';

        /* -------------------------------------------------- */
        /* Constructor
          /*-------------------------------------------------- */

        /**
         * Specifies the classname and description, instantiates the widget,
         * loads localization files, and includes necessary stylesheets and JavaScript.
         */
        public function __construct() {
            // widget actual processes
            parent::__construct(
                    $this->get_widget_slug(), __('Child Pages', $this->get_widget_slug()), array(
                'classname' => $this->get_widget_slug() . '-class',
                'description' => __('Child Pages.', $this->get_widget_slug())
                    ), array(
                'width' => 250,
                'height' => 350,
            ));

        }

        /**
         * Return the widget slug.
         *
         * @since 1.0.0
         *
         * @return Plugin slug variable.
         */
        public function get_widget_slug() {
            return $this->widget_slug;
        }

        /* -------------------------------------------------- */
        /* Form
          /*-------------------------------------------------- */

        /**
         * Generates the administration form for the widget.
         *
         * @param array instance The array of keys and values for the widget.
         */
        public function form($instance) {
            // outputs the options form on admin

            /* Set up some default widget settings. */
            /* Make sure all keys are added here, even with empty string values. */
            $defaults = array(
                'title' => '',
                'orderby' => 'menu_order',
                'order' => 'DESC',
                'thumb_size' => 50,
                'show_title' => '',
                'show_excerpt' => '',
                'excerpt_lenght' => 20
            );

            $instance = wp_parse_args((array) $instance, $defaults);

            // Display the admin form
            include( plugin_dir_path(__FILE__) . '/views/admin.php' );
        }

        /**
         * Get an array of the available orderby options.
         * @return array
         */
        protected function get_orderby_options() {
            return array(
                'none' => __('No Order', 'childpages'),
                'ID' => __('Entry ID', 'childpages'),
                'title' => __('Title', 'childpages'),
                'date' => __('Date Added', 'childpages'),
                'menu_order' => __('Specified Order Setting', 'childpages'),
                'rand' => __('Random Order', 'childpages')
            );
        }

        /**
         * Get an array of the available order options.
         * @return array
         */
        protected function get_order_options() {
            return array(
                'ASC' => __('Ascending', 'childpages'),
                'DESC' => __('Descending', 'childpages')
            );
        }

        /* -------------------------------------------------- */
        /* Update
          /*-------------------------------------------------- */

        /**
         * Processes the widget's options to be saved.
         *
         * @param array new_instance The previous instance of values before the update.
         * @param array old_instance The new instance of values to be generated via the update.
         */
        public function update($new_instance, $old_instance) {

            $instance = $old_instance;

            // TODO: Here is where you update your widget's old values with the new, incoming values
            /* Strip tags for title and name to remove HTML (important for text inputs). */
            $instance['title'] = strip_tags($new_instance['title']);

            /* Make sure the integer values are definitely integers. */
            $instance['excerpt_lenght'] = intval($new_instance['excerpt_lenght']);

            /* The select box is returning a text value, so we escape it. */
            $instance['orderby'] = esc_attr($new_instance['orderby']);
            $instance['order'] = esc_attr($new_instance['order']);
            $instance['thumb_size'] = esc_attr($new_instance['thumb_size']);

            $instance['show_excerpt'] = strip_tags($new_instance['show_excerpt']);
            $instance['show_title'] = strip_tags($new_instance['show_title']);

            return $instance;
        }

        /* -------------------------------------------------- */
        /* Display
          /*-------------------------------------------------- */

        /**
         * Outputs the content of the widget.
         *
         * @param array args The array of form elements
         * @param array instance The current instance of the widget
         */
        public function widget($args, $instance) {

            extract($args, EXTR_SKIP);

            // Only run on pages
            if (!is_page())
                return;

            global $post;
            $parents = $post->ID;

            $args = array(
                'child_of' => $parents,
            );
            $subpages = get_pages($args);

            // Only run on if page has children
            if (empty($subpages))
                return;

            /* Before widget (defined by themes). */
            echo $before_widget;


            // Here is where you manipulate your widget's values based on their input fields
            include( plugin_dir_path(__FILE__) . '/views/widget.php' );

            // After widget (defined by themes). */
            echo $after_widget;
        }

    }

    // end class


    add_action('widgets_init', create_function('', 'register_widget("ChildPagesWidget");'));
}