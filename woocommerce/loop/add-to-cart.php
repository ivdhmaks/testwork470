<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

?>

<div class="product__count-form">
    <div class="quantity-box">
        <div class="quantity-btn js-quantity-btn minus"></div>
        <input type="number" name="product_qty_input" id="product_qty_input-<?php echo $product->get_id(); ?>" class="product__count quantity js-quantity" min="1" max="99" value="1" maxlength="2">
        <div class="quantity-btn js-quantity-btn plus"></div>
    </div>
    <?php
    echo apply_filters(
        'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
        sprintf(
            '<button data-href="%s" data-quantity="%s" class="btn product__btn %s" %s>Order now</button>',
            esc_url( $product->add_to_cart_url() ),
            esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
            esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
            isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : ''
        ),
        $product,
        $args
    );
    ?>
</div>


