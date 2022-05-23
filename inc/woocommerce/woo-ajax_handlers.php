<?php

/**
 * AJAX handler for Products Load More button
 */
add_action('wp_ajax_products_load_more', 'products_load_more_ajax_handler');
add_action('wp_ajax_nopriv_products_load_more', 'products_load_more_ajax_handler');
function products_load_more_ajax_handler() {

    $resp_data = array(
        'html'  => '',
        'error' => '',
    );
    
    if( empty($_POST['post_per_page']) ){
        $resp_data['error'] = 'Param \'post_per_page\' not specified!';
        echo wp_send_json($resp_data);
        wp_die();
    }
    
    if( empty($_POST['page']) ){
        $resp_data['error'] = 'Param \'page\' not specified!';
        echo wp_send_json($resp_data);
        wp_die();
    }
    
    
    $post_per_page = $_POST['post_per_page'];
    $page = $_POST['page'];
    
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $post_per_page,
        'paged'          => $page,
        'post_status'    => 'publish',
        //'orderby'        => 'date',
        'orderby'        => 'menu_order', // order by specified sort order. Admin -> WooCommerce -> All Products -> Tab 'Sorting' (Ordering) -> use Drag'n'Drop
        'order'          => 'ASC',
        // get only products that are visible in the catalog
        'tax_query' => array( 
            array(
                'taxonomy'  => 'product_visibility',
                'terms'     => array( 'exclude-from-catalog' ),
                'field'     => 'name',
                'operator'  => 'NOT IN',
            ) 
        )
    );
    
    $products = get_posts($args);
    
    if ( !empty($products) ) {
        
        global $product;
        
        ob_start();
        foreach( $products as $product_post ){
            
            $product = wc_get_product($product_post->ID);
            
            get_template_part('woocommerce/content-product');
            
        }
        $products_content = ob_get_clean();

        $resp_data['html'] = $products_content;
        $resp_data['error'] = '';
    } 
    
    wp_send_json($resp_data);
    wp_die();
}

/**
 * AJAX handler for the product image upload
 */
add_action('wp_ajax_product_create_image_upload', 'product_create_image_upload_handler');
add_action('wp_ajax_nopriv_product_create_image_upload', 'product_create_image_upload_handler');
function product_create_image_upload_handler() {

    $resp_data = array(
        'attachment_id' => false,
        'image_url'     => false,
        'error'         => false,
    );
    
    $image_file_key = 'product_image';
    
    if( empty($_FILES[$image_file_key]) ){
        $resp_data['error'] = 'No file data received';
        wp_send_json($resp_data);
        wp_die();
    }
    
    $file_checker = new RestoFileChecker();
    $check_result = $file_checker->check($image_file_key);
    
    if( !$check_result ){
        $resp_data['error'] = $file_checker->getError();
        wp_send_json($resp_data);
        wp_die();
    }
    
    $attachment_id = media_handle_upload($image_file_key, 0);
    
    if( is_wp_error($attachment_id) ){
        $resp_data['error'] = $attachment_id->get_error_message();
        wp_send_json($resp_data);
        wp_die();
    }
    
    $resp_data['attachment_id'] = $attachment_id;
    $resp_data['image_url'] = wp_get_attachment_image_url($attachment_id, 'medium');
    
    wp_send_json($resp_data);
    wp_die();
}

/**
 * AJAX handler for the 'create new product' action
 */
add_action('wp_ajax_product_create_new', 'product_create_new_handler');
add_action('wp_ajax_nopriv_product_create_new', 'product_create_new_handler');
function product_create_new_handler() {

    $resp_data = array(
        'message'   => false,
        'errors'     => false,
    );
    
    $product_title    = ( !empty($_POST['product_title']) ) ? strip_tags(trim($_POST['product_title'])) : false;
    $product_price    = ( !empty($_POST['product_price']) ) ? strip_tags(trim($_POST['product_price'])) : false;
    $product_type     = ( !empty($_POST['product_type']) ) ? strip_tags(trim($_POST['product_type'])) : false;
    $product_date     = ( !empty($_POST['product_date']) ) ? strip_tags(trim($_POST['product_date'])) : false;
    $product_image_id = ( !empty($_POST['product_image_id']) ) ? strip_tags(trim($_POST['product_image_id'])) : false;
    
    if( !$product_title ){
        $resp_data['errors'][] = 'Product name not specified';
    }
    
    if( !$product_price ){
        $resp_data['errors'][] = 'Product price not specified';
    }
    
    // if errors
    if( !empty($resp_data['errors']) ){
        $resp_data['errors'] = implode('<br>', $resp_data['errors']);
        wp_send_json($resp_data);
        wp_die();
    }
    
    // make unique slug for this product
    $product_slug = sanitize_title($product_title) . '-' . substr((string)time(), -5);
    
    // prepage price value
    $product_price = str_replace(',', '.', $product_price);
    
    /**
     * Creating new product
     */
    $product = new WC_Product_Simple();
    $product->set_name( $product_title );
    $product->set_slug( $product_slug );
    $product->set_regular_price( number_format((float)$product_price, 2, '.', '') );
    $product->set_catalog_visibility('visible');
    $product->set_stock_status('instock');

    if( !empty($product_image_id) ){
        $product->set_image_id($product_image_id);
    }

    $product_id = $product->save();
    
    if( !$product_id ){
        $resp_data['errors'][] = 'Something went wrong. Try again later.';
        wp_send_json($resp_data);
        wp_die();
    }
    
    /**
     * Update product custom metadata
     */
    if( !empty($product_type) ){
        update_post_meta( $product_id, 'htr_product_type', $product_type );
    }
    
    if( !empty($product_date) ){
        update_post_meta( $product_id, 'htr_product_date', $product_date );
    }
    
    if( !empty($product_image_id) ){
        update_post_meta( $product_id, 'htr_product_image_id', $product_image_id );
    }
    
    $new_product_url = get_permalink($product_id);
    
    /**
     * Success message HTML
     */
    
    ob_start();
    ?>

    <div class="create-product-success">
        <h2>Product successfully created!</h2>
        <p>Next steps:</p>
        <p><a href="<?php echo $new_product_url; ?>">View your product</a> or <a href="/create-product/">Create new product</a></p>
    </div>

    <?php 
    $resp_data['message'] = ob_get_clean();
    
    wp_send_json($resp_data);
    wp_die();
}
