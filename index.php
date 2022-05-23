<?php
/**
 * The main template file
 */

get_header(); 

?>

<section class="products-section">
    <div class="wrapper">
        <div class="products-row">
            
            <?php
            if ( have_posts() ) :

                /* Start the Loop */
                while ( have_posts() ) : the_post();

                    get_template_part( 'template-parts/content', 'page' );

                endwhile;

            else :

                get_template_part( 'template-parts/content', 'none' );

            endif;
            ?>
            
        </div>
    </div>
</section>

<?php

get_footer();
