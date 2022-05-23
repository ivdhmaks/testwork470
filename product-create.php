<?php
/*
 * Template Name: Create Product
 */
?>

<?php get_header(); ?>

<section class="products-section">
    <div class="wrapper">
        <div class="products-row">
            
            <?php get_template_part( 'template-parts/product-create-form' ); ?>
            
        </div>
    </div>
</section>

<?php

get_footer();