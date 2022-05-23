
<?php

/**
 * Home page template
 */

?>

<?php get_header(); ?>

<?php
/*
$resto_frontpage_title_opt = get_option( 'resto_frontpage_title' );
$resto_frontpage_subtitle_opt = get_option( 'resto_frontpage_subtitle' );

$gmap_delivery_region = get_option( 'gmap_delivery_region' );

// FoodMenu file option
$foodmenu_file_opt = get_option( 'resto_foodmenu_file' );
$foodmenu_file_title_opt = get_option( 'resto_foodmenu_file_title' );

// InfoFile option
$infofile_opt = get_option( 'resto_infofile' );
$infofile_title_opt = get_option( 'resto_infofile_title' );

$resto_infoblock = get_option( 'resto_infoblock' );

$resto_main_image_opt = get_option( 'resto_main_image' );
$backgrount_image_style = '';
if( $resto_main_image_opt ){
    $backgrount_image_style = 'background: url(' . $resto_main_image_opt . ') no-repeat center/cover;';
}
*/
?>

<!--
<section class="intro-section">
    <div class="intro">
        <div class="wrapper">
            <div class="intro__content">
                
            </div>
        </div>
    </div>
</section>
-->


<section id="homepage_products" class="products-section">
    <div class="wrapper">
        <div class="products-row">

            <?php get_sidebar('resto_widgets'); ?>

            <div class="products-box">
                <div class="create-product-action">
                    <a href="/create-product/" class="btn btn-product-create">Create your own product</a>
                </div>
                <div class="caption"><?php _e('All products', 'ht_resto'); ?></div>
                <div class="products">

                    <?php
                    /**
                     * Custom product loop
                     */
                    $post_per_page = 12;
                    $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
                    $args = array(
                        'post_type'      => 'product',
                        'posts_per_page' => $post_per_page,
                        'paged'          => $paged,
                        'post_status'    => 'publish',
                        'orderby'        => 'date',
                        'order'          => 'DESC',
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
                    $loop = new WP_Query( $args );

                    $max_num_pages = $loop->max_num_pages;
                    
                    if ( $loop->have_posts() ) {
                        while ( $loop->have_posts() ) {
                            $loop->the_post();
                            wc_get_template_part( 'content', 'product' );
                        }
                    } else {
                        echo __( 'No products found' );
                    }

                    wp_reset_postdata();

                    ?>

                </div>
                
                <?php if( $max_num_pages > 0 ) { ?>
                <div class="ajax-loader_block">
                    <span class="loading-spinner"></span>
                    <button class="btn btn-default btn-load_more products-load-more-button" data-post_per_page="<?php echo $post_per_page; ?>" data-max_num_pages="<?php echo $max_num_pages; ?>" data-current_page="1" ><?php _e('Show more', 'ht_resto'); ?></button>
                </div>
                <?php } ?>
                
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>