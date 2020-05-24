<?php

add_action('wp', 'redirect_3Dconfigurator_single_product_page');

function redirect_3Dconfigurator_single_product_page()
{
    
    if (is_product())
    { // if the page is a single product page
        
        global $product, $post;
        $id_configurator = get_post_meta($post->ID, 'path_3Dconfigurator', true);
        
        if ($id_configurator != NULL)
        { // if this product has a 3D configurator target
            
            $uid = get_post_meta($post->ID, 'product_UID', true);
            $target = get_post_permalink($id_configurator) . '?uid=' . $uid;
            wp_redirect($target, 301); // redirect to configurator target url
            exit();

        }
    }
}

?>