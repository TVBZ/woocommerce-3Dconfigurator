<?php

/**
* Plugin Name: WooCommerce 3D Configurator
* Description: Create 3D configurator products for TreeSpoke using WooCommerce
* Author: Tom F. Vanbrabant
* Version: 0.1
*/



/*
 *  Create fields in admin products form
 */

add_action('woocommerce_product_options_general_product_data', 'woocommerce_create_3Dconfig_fields');

function woocommerce_create_3Dconfig_fields()
{
    global $woocommerce, $post;
    echo '<div>';

    // Field for configurator Path
    woocommerce_wp_select(array(
        'id' => 'path_3Dconfigurator',
        'label' => __('3D configurator', 'woocommerce') ,
        'options' => array(
            NULL => __('None', 'woocommerce') ,
            '/sideboard' => __('Sideboard', 'woocommerce') ,
            '/boekenkast' => __('Boekenkast', 'woocommerce') ,
            '/tv-kast' => __('TV kast', 'woocommerce')
        ) ,
        'desc_tip' => 'false'
    ));

    // Field for product UID
    woocommerce_wp_text_input(array(
        'id' => 'product_UID',
        'placeholder' => '',
        'label' => __('3D product UID', 'woocommerce') ,
        'desc_tip' => 'false'
    ));

    echo '</div>';
}



/*
 *  Save fields to database
 */

add_action('woocommerce_process_product_meta', 'woocommerce_save_3Dconfig_fields');

function woocommerce_save_3Dconfig_fields($post_id)
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



/*
 * redirect 3D configurator products to target configurator
 */

add_action( 'wp', 'redirect_3Dconfigurator_products' );

function redirect_3Dconfigurator_products() {
	if (is_product()) { // if the page is a single product page
		global $product, $post;
		$path_3Dconfigurator = get_post_meta($post->ID, 'path_3Dconfigurator', true);
		if ($path_3Dconfigurator != NULL) { // if the product has a 3D configurator target
			ob_start();
			$product_UID = get_post_meta($post->ID, 'product_UID', true);
    		$target_url = get_site_url(null, $path_3Dconfigurator, 'https') . '?uid=' . $product_UID;
			wp_redirect($target_url, 301); // redirect to configurator target url
    		exit();
		}
	}
}