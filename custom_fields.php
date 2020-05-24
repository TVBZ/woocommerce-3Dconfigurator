<?php

/*
 *  Build custom fields
*/

add_action('woocommerce_product_options_general_product_data', 'woocommerce_create_custom_treespoke_fields');

function woocommerce_create_custom_treespoke_fields()
{

    global $woocommerce, $post;

    // Get posts from 3D-configurator post type
    $posts_type_3Dconfigurators = get_posts(array(
        'post_type' => '3D-configurator',
        'post_status' => 'publish',
        'posts_per_page' => - 1
    ));

    // Initialize array for dropdown options
    $dropdown_options = array(
        NULL => __('None', 'woocommerce')
    );

    // Set active (selected) configurator option
    $current_configurator = NULL;
    $excisting_configurator = get_post_meta($post->ID, 'path_3Dconfigurator', true);
    if ($excisting_configurator != NULL) $current_configurator = $excisting_configurator;
    // Add excisting posts to array
    foreach ($posts_type_3Dconfigurators as $post)
    {
        $option_value = __($post->post_title, 'woocommerce');
        $dropdown_options[$post->ID] = $option_value;
    };

    

    // Open fields container
    echo '<div>';

    // Create configurator targets dropdown field
    woocommerce_wp_select(array(
        'id' => 'path_3Dconfigurator',
        'label' => __('3D configurator', 'woocommerce') ,
        'selected' => true,
        'value' => $current_configurator,
        'options' => $dropdown_options,
        'desc_tip' => 'false'
    ));

    // Create product UID field
    woocommerce_wp_text_input(array(
        'id' => 'product_UID',
        'placeholder' => '',
        'label' => __('3D product UID', 'woocommerce') ,
        'desc_tip' => 'false'
    ));

    // Close fields container
    echo '</div>';
    
}



/*
 *  Save fields to database
*/

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

?>