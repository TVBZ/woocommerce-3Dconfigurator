<?php
/**
 * Plugin Name: WooCommerce 3D Configurator
 * Description: Custom WooCommerce plugin for treespoke.com 3D configurator products
 * Plugin URI: https://github.com/TVBZ/woocommerce-3Dconfigurator
 * Author: Tom F. Vanbrabant
 * Version: 0.2
 */

 
defined( 'ABSPATH' ) || exit;


$dir = plugin_dir_path( __FILE__ );


// Create custom post type
require_once($dir.'custom_post_type.php');

// Add custom fields to WooCommerce products
require_once($dir.'custom_fields.php');

// Redirect single product page
require_once($dir.'product_redirect.php');

?>