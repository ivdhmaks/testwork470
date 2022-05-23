<?php

include dirname(__FILE__) . '/woo-admin_helpers.php';

/*
 * Add custom Data Tab to the WC_Product edit screen
 */
add_filter('woocommerce_product_data_tabs', 'resto_product_custom_tabs' );
function resto_product_custom_tabs( $tabs ){
 
    $tabs['resto_custom_tab'] = array(
        'label'    => 'Resto Custom Data',
        'target'   => 'resto_product_data',
        'class'    => array(),
        'priority' => 90,
    );
    return $tabs;
 
}
 
/*
 * Render custom Data Tab fields
 */
add_action( 'woocommerce_product_data_panels', 'resto_custom_tab_fields' );
function resto_custom_tab_fields(){
 
    $product_id = get_the_ID();
    
    echo '<div id="resto_product_data" class="panel woocommerce_options_panel hidden">';
    echo '<div class="options_group">';

    $attachment_id = get_post_meta( $product_id, 'htr_product_image_id', true );
    $image_url = false;
    if( !empty($attachment_id) ){
        $image_url = wp_get_attachment_image_url($attachment_id);
        //$image_url = wp_get_attachment_url($attachment_id);
    }
    resto_woo_image_select_input( array(
        'id'        => 'htr_product_image_id',
        'value'     => $attachment_id,
        'label'     => 'Product image',
        'img_url'   => $image_url,
        'desc_tip'  => 'Image select demo',
    ) );
    
    woocommerce_wp_text_input( array(
        'id'    => 'htr_product_date',
        'value' => get_post_meta( $product_id, 'htr_product_date', true ),
        'label' => 'Product date',
        'type'  => 'date',
    ) );

    woocommerce_wp_select( array(
        'id'      => 'htr_product_type',
        'label'   => __( 'Product type', 'ht_resto' ),
        'value'   => get_post_meta( $product_id, 'htr_product_type', true ),
        'options' => resto_get_product_type_list(),
    ) );
    
    echo '</div>';
    ?>
    
    <div class="options_group htr-actions">
        <button class="button button-large btn-htr-reset">Reset all values</button>
        <button class="button button-primary button-large btn-htr-update">Update</button>
    </div>

    <?php
    echo '</div>';
 
}

/**
 * Product custom fileds save
 */
add_action( 'woocommerce_process_product_meta', 'resto_save_product_custom_fields', 10, 2 );
function resto_save_product_custom_fields( $id, $post ){
 
    $fields = array(
        'htr_product_image_id',
        'htr_product_date',
        'htr_product_type'
    );
    
    foreach( $fields as $filed_name ){
        if( !empty( $_POST[$filed_name] ) ) {
            update_post_meta( $id, $filed_name, strip_tags(trim($_POST[$filed_name])) );
        } else {
            delete_post_meta( $id, $filed_name );
        }
    }
    
}