<?php
/**
 * Template part for displaying page content in page.php
 */

?>

<div class="products-box">
    <div class="caption">
        <h1><?php the_title(); ?></h1>
    </div>
    <div class="products">
        <?php 
        
        the_content(); 
        
        ?>
    </div>
</div>
