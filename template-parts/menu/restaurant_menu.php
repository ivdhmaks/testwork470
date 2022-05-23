<?php 
    $restaurant_menu = resto_get_menu_items_list('restaurant_menu'); 
    if( $restaurant_menu ){
    ?>    
    <ul class="menu__list drop-menu__list">
        <?php 
        foreach( $restaurant_menu as $rs_menu_item) { 
            $menu_item_class = ( $rs_menu_item['current'] ) ? 'current-menu-item' : '';
        ?>
        <li class="menu__item <?php echo $menu_item_class; ?>">
            <a href="<?php echo $rs_menu_item['link']; ?>" class="menu__link drop-menu__link "><?php echo $rs_menu_item['title']; ?></a>
            <?php if( !empty($rs_menu_item['sub_items']) ) { ?>
            <span class="submenu_toggle"></span>
            <ul class="menu__list submenu" >
                <?php foreach( $rs_menu_item['sub_items'] as $sub_item ) { ?>
                <li class="menu__item">
                    <a href="<?php echo $sub_item['link']; ?>" class="menu__link drop-menu__link "><?php echo $sub_item['title']; ?></a>
                </li>
                <?php } ?>
            </ul>
            <?php } ?>
        </li>
        <?php } ?>
    </ul>
<?php } ?>