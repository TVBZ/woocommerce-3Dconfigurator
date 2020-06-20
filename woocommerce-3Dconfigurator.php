<?php

/**
 * Plugin Name: WooCommerce 3D Configurator
 * Description: Custom WooCommerce plugin for treespoke.com 3D configurator products
 * Plugin URI: https://github.com/TVBZ/woocommerce-3Dconfigurator
 * Author: Tom F. Vanbrabant
 * Version: 0.4
 */


// Restrict direct access
defined('ABSPATH') || exit;


/*********************************
 ***  Create custom post type  ***
 *********************************/

add_action('init', 'create_3Dconfigurator_custom_post_type');

function create_3Dconfigurator_custom_post_type()
{

	// Set labels for custom post type
	$labels = array(
		'name' => __('3D Configurators'),
		'singular_name' => __('3D Configurator'),
		'add_new_item' => __('Add New 3D Configurator'),
		'edit_item' => __('Edit 3D Configurator'),
		'new_item' => __('New 3D Configurator'),
		'all_items' => __('All 3D Configurators'),
		'view_item' => __('View 3D Configurator'),
		'menu_name' => __('3D Configurators'),
		'update_item' => __('Update 3D Configurator'),
	);

	// Add custom post type
	register_post_type('3D-configurator', array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'has_archive' => false,
		'rewrite' => array(
			'slug' => 'configureer',
			//'with_front' => false
		),
		'hierarchical' => false,
	));
}



/****************************
 ***  Build custom fields ***
 ****************************/

add_action('woocommerce_product_options_general_product_data', 'woocommerce_create_custom_treespoke_fields');

function woocommerce_create_custom_treespoke_fields()
{

	global $woocommerce, $post;

	// Get posts from 3D-configurator post type
	$posts_type_3Dconfigurators = get_posts(array(
		'post_type' => '3D-configurator',
		'post_status' => 'publish',
		'posts_per_page' => -1
	));

	// Set active (selected) configurator option
	$current_configurator = NULL;
	$excisting_configurator = get_post_meta($post->ID, 'path_3Dconfigurator', true);
	if ($excisting_configurator != NULL) $current_configurator = $excisting_configurator;

	// Initialize array for dropdown options
	$dropdown_options = array(
		NULL => __('None', 'woocommerce')
	);

	// Add excisting posts to array
	foreach ($posts_type_3Dconfigurators as $post) {
		$option_value = __($post->post_title, 'woocommerce');
		$dropdown_options[$post->ID] = $option_value;
	};

	// Open fields container
	echo '<div>';

	// Add configurator targets dropdown field
	woocommerce_wp_select(array(
		'id' => 'path_3Dconfigurator',
		'label' => __('3D configurator', 'woocommerce'),
		'selected' => true,
		'value' => $current_configurator,
		'options' => $dropdown_options,
		'desc_tip' => 'false'
	));

	// Add product UID field
	woocommerce_wp_text_input(array(
		'id' => 'product_UID',
		'placeholder' => '',
		'label' => __('3D product UID', 'woocommerce'),
		'desc_tip' => 'false'
	));

	// Close fields container
	echo '</div>';
}



/*********************************
 ***  Save fields to database  ***
 *********************************/

add_action('woocommerce_process_product_meta', 'woocommerce_save_custom_treespoke_fields');

function woocommerce_save_custom_treespoke_fields($post_id)
{

	// Save configurator path
	$configurator_path = $_POST['path_3Dconfigurator'];
	if (!empty($configurator_path)) update_post_meta($post_id, 'path_3Dconfigurator', esc_attr($configurator_path));
	else update_post_meta($post_id, 'path_3Dconfigurator', '');

	// Save UID field
	$product_uid = $_POST['product_UID'];
	if (!empty($product_uid)) update_post_meta($post_id, 'product_UID', esc_attr($product_uid));
	else update_post_meta($post_id, 'product_UID', '');
}



/*****************************************
 ***  Redirect single 3D product page  ***
 *****************************************/

add_action('wp', 'redirect_3Dconfigurator_single_product_page');

function redirect_3Dconfigurator_single_product_page()
{

	if (is_product()) { // if the page is a single product page

		global $product, $post;
		$id_configurator = get_post_meta($post->ID, 'path_3Dconfigurator', true);

		if ($id_configurator != NULL) { // if this product has a 3D configurator target

			$uid = get_post_meta($post->ID, 'product_UID', true);
			$target = get_post_permalink($id_configurator) . '?uid=' . $uid;
			wp_redirect($target, 301); // redirect to configurator target url
			exit();
		}
	}
}
