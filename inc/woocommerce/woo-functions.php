<?php

require dirname(__FILE__) . '/widgets/resto_widget_product_cat.php';
require dirname(__FILE__) . '/woo-ajax_handlers.php';
require dirname(__FILE__) . '/woo-checkout.php';
require dirname(__FILE__) . '/helpers/RestoFileChecker.php';

/**
 * RESTO_ProductCategories helper class
 */
class RESTO_ProductCategories{
    
    /**
     * Get product categories hierarchical tree array
     * 
     * @return array 
     */
    public static function getTree(){
        
        $categories_tree = array();
        
        $category_args = array(
            'taxonomy'      => 'product_cat', // WooCommerce product category taxonomy
            'hide_empty'    => true, // don't show empty categories
            'hierarchical'  => true, // show hierarchy
            // using sort ordering, specified by Drag'n'Drop in Admin menu for Product Category taxonomy
            'orderby'       => 'meta_value_num',
            'meta_key'      => 'order',
            'order'         => 'ASC',
        );
        $term_list = get_terms( $category_args );

        $prod_categories = array();
        // convert WP_Term object to array
        foreach( $term_list as $cat_term ){
            $prod_categories[] = (array)$cat_term;
        }

        if( !empty($prod_categories) ){
            // build tree of categories
            $categories_tree = self::_buildTree($prod_categories, 0);
        }
        
        return $categories_tree;
    }
    
    /**
    * Build category tree
    * Convert plain array to array tree
    * Helper method
    * 
    * @param array $elements
    * @param int $parent_id
    * @return array
    */
    private static function _buildTree($elements, $parent_id = 0){
        $branch = array();
 
        foreach ($elements as $element) {
            if ($element['parent'] == $parent_id) {
                // childs
                $childs = self::_buildTree($elements, $element['term_id']);
                if ($childs) {
                    $element['childs'] = $childs;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }
    
}

/**
 * Register Shop sidebar widget area.
 */
function resto_shop_sidebars_init() {
    
    register_sidebar( array(
        'name'          => 'Resto sidebar',
        'id'            => 'resto-widgets',
        'description'   => 'Resto theme sidebar. Add widgets here.',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
    
}
add_action( 'widgets_init', 'resto_shop_sidebars_init' );


/**
 * Change a currency symbol
 */
add_filter('woocommerce_currency_symbol', 'resto_change_currency_symbol', 10, 2);
function resto_change_currency_symbol( $currency_symbol, $currency ) {
    if( $currency == 'UAH' ){
        $currency_symbol = ' грн';
    }
    return $currency_symbol;
}

/**
 * Change number of products that are displayed per page (shop page)
 */
add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );
function new_loop_shop_per_page( $cols ) {
  // $cols contains the current number of products per page based on the value stored on Options –> Reading
  // Return the number of products you wanna show per page.
  $cols = 12;
  return $cols;
}

/**
 * Custom fragments data for Cart AJAX actions 
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'resto_custom_cart_fragments');
function resto_custom_cart_fragments($fragments){
    
    $fragments['cart_items_count'] = WC()->cart->get_cart_contents_count();
    
    if( wc_prices_include_tax() ){
        $fragments['cart_total_sum'] = WC()->cart->get_cart_contents_total() + WC()->cart->get_cart_contents_tax();
    } else {
        $fragments['cart_total_sum'] = WC()->cart->get_cart_contents_total();
    }
    
    return $fragments;
}

/**
 * WC_Product custom type list
 * 
 * @return array
 */
function resto_get_product_type_list(){
    return array(
        'rare'      => 'Rare', 
        'frequent'  => 'Frequent', 
        'unusual'   => 'Unusual'
    );
}