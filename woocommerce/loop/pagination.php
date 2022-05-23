<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$base    = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
$format  = isset( $format ) ? $format : '';

if ( $total <= 1 ) {
	return;
}

$page_links = paginate_links(
    apply_filters(
        'woocommerce_pagination_args',
        array( // WPCS: XSS ok.
            'base'      => $base,
            'format'    => $format,
            'add_args'  => false,
            'current'   => max( 1, $current ),
            'total'     => $total,
            'prev_text' => '',
            'next_text' => '',
            'type'      => 'array',
            'end_size'  => 1,
            'mid_size'  => 4,
        )
    )
);

?>
<div class="pagination">
    <ul class="pagination__list">
        <?php 
        foreach( $page_links as $p_link ) { 
            $current = ( strpos($p_link, 'page-numbers current') ) ? 'current' : '';
            $p_link = preg_replace('/class=\"page-numbers/', 'class="page-numbers pagination__link', $p_link);
        ?>
            <li class="pagination__item <?php echo $current; ?>"><?php echo $p_link; ?></li>
        <?php } ?>
    </ul>
</div>
