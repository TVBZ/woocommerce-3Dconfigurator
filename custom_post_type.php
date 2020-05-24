<?php

add_action('init', 'create_3Dconfigurator_custom_post_type');

function create_3Dconfigurator_custom_post_type()
{

    // Set labels for custom post type
    $labels = array(
        'name' => __('3D Configurators') ,
        'singular_name' => __('3D Configurator') ,
        'add_new_item' => __('Add New 3D Configurator') ,
        'edit_item' => __('Edit 3D Configurator') ,
        'new_item' => __('New 3D Configurator') ,
        'all_items' => __('All 3D Configurators') ,
        'view_item' => __('View 3D Configurator') ,
        'menu_name' => __('3D Configurators') ,
        'update_item' => __('Update 3D Configurator') ,
    );

    // Add custom post type
    register_post_type('3D-configurator', array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'has_archive' => true,
        'rewrite' => array(
            'slug' => '3D-configurator'
        ) ,
        'hierarchical' => false,        
    ));
    
}

?>