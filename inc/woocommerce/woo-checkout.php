<?php

/**
 * Customize default address fields
 */
add_filter( 'woocommerce_default_address_fields', 'resto_customize_default_address_fields' );
function resto_customize_default_address_fields( $address_fields ) {
    
    // make postcode field not required
    //$address_fields['postcode']['required'] = false;
    
    // remove postcode field
    if( isset($address_fields['postcode']) ){
        unset($address_fields['postcode']);
    }
    
    // fields reorder
    if( isset($address_fields['state']) ){
        $address_fields['state']['priority'] = 43;
    }
    if( isset($address_fields['city']) ){
        $address_fields['city']['priority'] = 45;
    }
    
    return $address_fields;
}

/**
 * Customize checkout fields
 */
add_filter( 'woocommerce_checkout_fields' , 'resto_customize_checkout_fields' );
function resto_customize_checkout_fields( $fields ) {
    
    // fields reorder
    if( isset($fields['billing']['billing_state']) ){
        $fields['billing']['billing_state']['priority'] = 43;
    }
    if( isset($fields['billing']['billing_city']) ){
        $fields['billing']['billing_city']['priority'] = 45;
        // change label
        //$fields['billing']['billing_city']['label'] = 'City';
    }
    
    // remove billing company field
    if( isset($fields['billing']['billing_company']) ){
        unset($fields['billing']['billing_company']);
    }
    
    // remove billing country field
    if( isset($fields['billing']['billing_country']) ){
        unset($fields['billing']['billing_country']);
    }
    
    // remove billing state field
    if( isset($fields['billing']['billing_state']) ){
        unset($fields['billing']['billing_state']);
    }
    
    // remove billing email field
    if( isset($fields['billing']['billing_email']) ){
        unset($fields['billing']['billing_email']);
    }
    
    /**
     * Add custom field 'billing_person_num' to the checkout billing section
     */
    $fields['billing']['billing_person_num'] = array(
        'type'        => 'number', 
        'label'       => 'Number of persons',
        'placeholder' => 'Specify the number of people', 
        // attributes for input type="number"
        'custom_attributes' => array(
            'min' => '1',
            'max' => '999',
            'step' => '1',
        ),
        'required'    => false, 
        'class'       => array('billing_person_num', 'form-row', 'form-row-wide'),
        'priority'    => 105,
    );
    
    return $fields;
}

/**
 * Default value for checkout custom field 'billing_person_num'
 * apply_filters( 'default_checkout_' . $input, $value, $input );
 */
add_filter( 'default_checkout_billing_person_num' , 'resto_default_billing_person_num_value', 10, 2 );
function resto_default_billing_person_num_value( $value, $input ) {
    
    if( $input == 'billing_person_num' && empty($value) ){
        return '1'; // default value for checkout custom field 'billing_person_num'
    }
    
}

/**
 * Update the order meta with 'billing_person_num' custom field value
 */
add_action( 'woocommerce_checkout_update_order_meta', 'resto_custom_checkout_field_update_order_meta' );
function resto_custom_checkout_field_update_order_meta( $order_id ) {
    
    if ( ! empty( $_POST['billing_person_num'] ) ) {
        update_post_meta( $order_id, 'billing_person_num', sanitize_text_field( $_POST['billing_person_num'] ) );
    }
   
}


/**
 * Display 'billing_person_num' custom field value on the order edit page
 */
add_action( 'woocommerce_admin_order_data_after_billing_address', 'resto_admin_display_custom_checkout_field_order_meta', 10, 1 );
function resto_admin_display_custom_checkout_field_order_meta($order){
    
    echo '<p><strong>Number of persons:</strong> ' . get_post_meta( $order->get_id(), 'billing_person_num', true ) . '</p>';
    
}