<?php
/*
* Plugin Name: Diet recipe
 * Description: test project
 * Version: 1.0
 * Author: Ali Shojaei
 * Author URI: https://alishojaei.ir
 * Text Domain: diet
 * Domain Path: /languages/
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

load_plugin_textdomain('diet', false, basename(dirname(__FILE__)) . '/languages');

define('DIET_VERSION', '1.0.0');
define('DIET_PATH', plugin_dir_path(__FILE__));
define('DIET_TEMP', DIET_PATH.'/templates');
define('DIET_URL', plugin_dir_url(__FILE__));

require_once DIET_PATH . 'includes/functions.php';
require_once DIET_PATH . 'includes/RecipeFactory.php';
require_once DIET_PATH . 'includes/DietEndpoint.php';

add_filter( 'template_include', 'template_loader' );


