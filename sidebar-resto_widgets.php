<?php
/**
 * The sidebar containing the main widget area
 */
 
if ( is_active_sidebar( 'resto-widgets' ) ):
?>

<div class="sidebar">
    
    <?php dynamic_sidebar( 'resto-widgets' ); ?>
    
</div>
    
<?php endif; ?>


