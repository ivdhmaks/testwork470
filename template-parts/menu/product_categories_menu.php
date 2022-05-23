<?php

$categories_terms = RESTO_ProductCategories::getTree();
if( !empty($categories_terms) ){
    ?>
    <ul class="menu__list drop-menu__list">
        <?php  foreach( $categories_terms as $cat_term) { ?>
        <li class="menu__item">
            <a href="<?php echo get_term_link( $cat_term['term_id'], 'product_cat' ); ?>" class="menu__link drop-menu__link "><?php echo $cat_term['name']; ?></a>
            <?php if( !empty($cat_term['childs']) ) { ?>
            <span class="submenu_toggle"></span>
            <ul class="menu__list submenu" >
                <?php foreach( $cat_term['childs'] as $child ) { ?>
                <li class="menu__item">
                    <a href="<?php echo get_term_link( $child['term_id'], 'product_cat' ); ?>" class="menu__link drop-menu__link "><?php echo $child['name']; ?></a>
                </li>
                <?php } ?>
            </ul>
            <?php } ?>
        </li>
        <?php } ?>
    </ul>
<?php } ?>
