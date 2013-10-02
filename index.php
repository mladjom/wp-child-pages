<?php
/*
Plugin Name: Somc Mladjom
Plugin URI: http://www.divinedeveloper.com.com/plugins/
Description: This Plugin displays all subpages of the page it is placed on 
Version: 1.0
Author: Mladjo
Author URI: http://www.divinedeveloper.com/
License: GPLv3
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once dirname( __FILE__ ) . '/class.plugin.php' ;
require_once dirname( __FILE__ ) . '/inc/class.shortcodes.php';
require_once dirname( __FILE__ ) . '/inc/widget/class.widget.php';
Somc_Mladjom::get_instance();