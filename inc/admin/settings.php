<?php

/**
 * Menu page slugs
 */
$resto_general = 'resto_settings_general.php';
$resto_frontpage = 'resto_settings_frontpage.php';

include(dirname(__FILE__) . '/settings_general.php');
include(dirname(__FILE__) . '/settings_frontpage.php');

/**
 * Register options
 */
add_action( 'admin_init', 'theme_settings_init' );
function theme_settings_init(){
    
    // General options
    register_setting( 'resto_general', 'resto_phone' );
    register_setting( 'resto_general', 'resto_email' );
    register_setting( 'resto_general', 'resto_logo' );
    register_setting( 'resto_general', 'resto_social' );
    register_setting( 'resto_general', 'gmap_delivery_region' );
    register_setting( 'resto_general', 'resto_site_menu_type' );
    register_setting( 'resto_general', 'resto_foodmenu_file' );
    register_setting( 'resto_general', 'resto_foodmenu_file_title' );
    register_setting( 'resto_general', 'resto_infofile' );
    register_setting( 'resto_general', 'resto_infofile_title' );
    
    // FrontPage options
    register_setting( 'resto_frontpage', 'resto_frontpage_title' );
    register_setting( 'resto_frontpage', 'resto_frontpage_subtitle' );
    register_setting( 'resto_frontpage', 'resto_main_image' );
    register_setting( 'resto_frontpage', 'resto_infoblock' );
    register_setting( 'resto_frontpage', 'resto_frontpage_content' );
}

/**
 * Setup theme settings pages in admin menu
 */
add_action( 'admin_menu', 'add_settings_page' );
function add_settings_page() {
    global $resto_general;
    global $resto_frontpage;
    
    add_menu_page('Resto Theme Settings', 'Resto Theme', 'manage_options', $resto_general, 'resto_general_opt');
    add_submenu_page( $resto_general, 'General options', 'General', 'manage_options', $resto_general, 'resto_general_opt');
    add_submenu_page( $resto_general, 'Front Page Options', 'Front Page', 'manage_options', $resto_frontpage, 'resto_frontpage_opt');
}

/**
 * Action. Enqueue 'admin.js' and 'admin.css'
 * 
 * @global type $post
 * @param type $hook
 */
function resto_options_enqueue_adminscript($hook) {
    
    $screen = get_current_screen();
    
    // include scripts and styles only on pages, which associated with 'resto' theme or product 'edit' screens
    if($screen && (strpos($screen->id, 'resto_settings') !== false || $screen->id == 'product') ){
    
        // WordPress media library
        if ( ! did_action( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        }

        wp_enqueue_style( 'resto_theme_options_css', get_template_directory_uri() . '/inc/admin/assets/admin.css', array(), '0.1');
        
        // wp_enqueue_script('jquery');
        
        // Load Image Box Selector control script
        wp_enqueue_script( 'resto_control_image_select', get_template_directory_uri() . '/inc/admin/assets/control_image_select.js', array( 'jquery' ), '0.1', false );
        
        // Load File Selector control script
        wp_enqueue_script( 'resto_control_file_select', get_template_directory_uri() . '/inc/admin/assets/control_file_select.js', array( 'jquery' ), '0.1', false );
        
        wp_enqueue_script( 'resto_theme_options_js', get_template_directory_uri() . '/inc/admin/assets/admin.js', array('jquery'), null, false );
    }
}
add_action( 'admin_enqueue_scripts', 'resto_options_enqueue_adminscript' );