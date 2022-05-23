<?php

/** Basic setup **/
add_theme_support( 'menus' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'title-tag' );

// hide admin bar on frontend
show_admin_bar(false);


/** Includes **/
require get_template_directory() . '/inc/admin/settings.php';

/* Load WooCommerce compatibility files. */
if ( class_exists( 'WooCommerce' ) ) {
    include get_template_directory() . '/inc/woocommerce/woo-functions.php';
    include get_template_directory() . '/inc/admin/woo-product_custom_fields.php';
}


/** Load scripts and styles **/
function resto_load_resources(){
    
    // CSS
    wp_enqueue_style( 'resto_main_css', get_template_directory_uri() . '/assets/css/style.css', array(), '0.1');
    wp_enqueue_style( 'magnific_popup_css', get_template_directory_uri() . '/assets/js/magnific-popup/magnific-popup.css', array(), '0.1');
    
    // JS
    // remove default Wordpress jQuery library
    wp_deregister_script('jquery'); 
    
    wp_enqueue_script( 'jquery', get_template_directory_uri() . '/assets/js/jquery-3.5.1.min.js', array(), '0.1', false );
    wp_enqueue_script( 'resto_jquery_migrate', get_template_directory_uri() . '/assets/js/jquery-migrate-3.3.0.min.js', array('jquery'), '0.1', false );
    wp_enqueue_script( 'magnific_popup_js', get_template_directory_uri() . '/assets/js/magnific-popup/jquery.magnific-popup.min.js', array('jquery'), '0.1', false );
    wp_enqueue_script( 'resto_main_js', get_template_directory_uri() . '/assets/js/custom.js', array('jquery'), '0.1', true );
    
    $localize_args = array(
        'ajaxurl' => admin_url('admin-ajax.php')
    );
    wp_localize_script( 'resto_main_js', 'RESTO', $localize_args);
    
}
add_action( 'wp_enqueue_scripts', 'resto_load_resources' );

/*
 * Theme setup
 */
function resto_setup_theme(){
    register_nav_menu('restaurant_menu', 'Restaurant Main menu');
    register_nav_menu('network_menu', 'Network menu');
    
    add_theme_support( 'woocommerce' );
}
add_action('after_setup_theme', 'resto_setup_theme');

/**
 * Get list of nav menu items
 * 
 * @param string $menu_location_name - nav menu location name (slug)
 * @return array
 */
function resto_get_menu_items_list($menu_location_name){
    
    $menu_list = array();
    
    $locations = get_nav_menu_locations();

    if( $locations && isset($locations[ $menu_location_name ]) ){
        $menu = wp_get_nav_menu_object( $locations[ $menu_location_name ] ); // get menu object

        $menu_items = wp_get_nav_menu_items( $menu ); // get menu elements
        
        $child_items = array();
        
        if($menu_items){
            
            // nav menu items : top level
            foreach ( $menu_items as $key => $menu_item ){
                // if no parent in this item, then it's a top level item
                if($menu_item->menu_item_parent == 0){
                    // top level nav menu items
                    $menu_list[$menu_item->ID] = array(
                        'link'      => $menu_item->url,
                        'title'     => $menu_item->title,
                        'parent_id' => $menu_item->menu_item_parent,
                        'current'   => ( is_current_menu_item($menu_item->url) ) ? true : false,
                        'sub_items' => array(),
                    );
                } else {
                    // childs
                    $child_items[$menu_item->ID] = $menu_item;
                }
            }
            
            // nav menu items : level 1 of childs
            foreach($child_items as $c_key => $child_item){
                if( !empty($menu_list[$child_item->menu_item_parent]) ){
                    $is_parent_active = $is_current_subitem = ( is_current_menu_item($child_item->url) ) ? true : false;
                    $child_1L = array(
                        'link'      => $child_item->url,
                        'title'     => $child_item->title,
                        'parent_id' => $child_item->menu_item_parent,
                        'current'   => $is_current_subitem,
                        'sub_items' => array(),
                    );
                    
                    // set parent active, if current submenu item is active
                    if( $is_parent_active ){
                        $menu_list[$child_item->menu_item_parent]['current'] = true;
                    }
                    
                    // nav menu items : level 2 of childs
                    foreach($child_items as $c2L_key => $child_2L){
                        if( $child_2L->menu_item_parent == $child_item->ID){
                            $child_1L['sub_items'][$child_2L->ID] = array(
                                'link'      => $child_2L->url,
                                'title'     => $child_2L->title,
                                'parent_id' => $child_2L->menu_item_parent,
                                'current'   => ( is_current_menu_item($child_2L->url) ) ? true : false,
                            );
                        }
                    }
                    
                    $menu_list[$child_item->menu_item_parent]['sub_items'][$child_item->ID] = $child_1L;
                    
                    unset($child_items[$c_key]);
                }
            }
        }
    }
    
    return $menu_list;
}

/**
 * Helper. 
 * Determine that the current REQUEST_URI coincides with passed menu item url
 *  
 * @param type $menu_item_url
 * @return boolean
 */
function is_current_menu_item( $menu_item_url ){
    $front_page_url         = home_url();
    $_root_relative_current = untrailingslashit( $_SERVER['REQUEST_URI'] );

    //if it is the customize page then it will strips the query var off the url before entering the comparison block.
    if ( is_customize_preview() ) {
        $_root_relative_current = strtok( untrailingslashit( $_SERVER['REQUEST_URI'] ), '?' );
    }

    $current_url    = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_root_relative_current );
    $raw_item_url   = strpos( $menu_item_url, '#' ) ? substr( $menu_item_url, 0, strpos( $menu_item_url, '#' ) ) : $menu_item_url;
    $item_url       = set_url_scheme( untrailingslashit( $raw_item_url ) );

    $matches = array(
        $current_url,
        urldecode( $current_url ),
        $_root_relative_current,
        urldecode( $_root_relative_current ),
    );

    if ( $raw_item_url && in_array( $item_url, $matches ) ) {
        return true;
    } elseif ( $item_url == $front_page_url && is_front_page() ) {
        return true;
    }

    return false;
}

/*
 *  Helper. Get clean phone string
 */
function get_phone_clean($phone){
    return str_replace(array("(", ")", " ", "-"), "", $phone);
}

/**
 * Helper. Get WooCommerce currency symbol
 * 
 * @return string|boolean
 */
function resto_get_currency_symbol(){
    if( class_exists('WooCommerce') ){
        return get_woocommerce_currency_symbol();
    }
    
    return false;
}