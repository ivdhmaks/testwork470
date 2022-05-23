<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<div class="product <?php echo esc_attr( implode( ' ', wc_get_product_class( '', $product ) ) ); ?>" data-product_id="<?php echo $product->get_id(); ?>">
    <div class="product__content">
        <div class="loading-overlay">
            <span class="loading-spinner"></span>
        </div>
	<?php
	/**
	 * Hook: woocommerce_before_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
        remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
	do_action( 'woocommerce_before_shop_loop_item' );

	/**
	 * Hook: woocommerce_before_shop_loop_item_title.
	 *
	 * @hooked woocommerce_show_product_loop_sale_flash - 10
	 * @hooked woocommerce_template_loop_product_thumbnail - 10
	 */
        remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
        remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
	do_action( 'woocommerce_before_shop_loop_item_title' );
        ?>
        
        <?php
        
        $primary_image = $product->get_image('woocommerce_thumbnail', array('class' => 'product__img product-primary_img'));
        $secondary_image = '';
        
        $attachment_ids = $product->get_gallery_image_ids();
        if( isset($attachment_ids[0]) ){
            $secondary_image = wp_get_attachment_image( $attachment_ids[0], 'woocommerce_thumbnail', false, array('class' => 'product__img product-secondary_img') );
        }
        
        ?>
        
        <span class="product__img-box" data-gallery_id="<?php echo $product->get_id() ;?>">
            <?php echo $primary_image; ?>
            <?php 
            if( !empty($secondary_image) ){
                echo $secondary_image;
            }
            ?>
        </span>
        
        <div id="product__img-gallery_<?php echo $product->get_id() ;?>" class="product__img-gallery" style="display:none;" >
            <?php
            $main_img = '';
            if ( $product->get_image_id() ) {
                $main_img = wp_get_attachment_image_url( $product->get_image_id(), 'full' );
            }
            
            if( !empty($main_img) ){
                echo '<a href="' . $main_img . '"></a>';
            }
            
            if( !empty($attachment_ids) ){
                foreach( $attachment_ids as $attach_id ){
                    $attach_src = wp_get_attachment_image_url( $attach_id, 'full' );
                    if( !empty($attach_src) ){
                        echo '<a href="' . $attach_src . '"></a>';
                    }
                }
            }
            ?>
        </div>
        
        <div class="product__info">
            <?php
            /**
             * Hook: woocommerce_shop_loop_item_title.
             *
             * @hooked woocommerce_template_loop_product_title - 10
             */
            remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
            do_action( 'woocommerce_shop_loop_item_title' );
            ?>
            
            <span data-href="<?php echo get_permalink( $product->get_id() ); ?>" class="product__title"><?php echo $product->get_title(); ?></span>
            
            <div class="product__description">
                <?php echo nl2br( $product->get_short_description() ); ?>
            </div>
            
            <?php
            /**
             * Hook: woocommerce_after_shop_loop_item_title.
             *
             * @hooked woocommerce_template_loop_rating - 5
             * @hooked woocommerce_template_loop_price - 10
             */
            remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
            do_action( 'woocommerce_after_shop_loop_item_title' );
            ?>
            
            <div class="product__bottom">
            <?php
            /**
             * Hook: woocommerce_after_shop_loop_item.
             *
             * @hooked woocommerce_template_loop_product_link_close - 5
             * @hooked woocommerce_template_loop_add_to_cart - 10
             */
            remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
            do_action( 'woocommerce_after_shop_loop_item' );
            ?>
            </div>
            
        </div>
    </div>
</div>
