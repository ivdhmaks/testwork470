
            </main>
            <!-- CONTENT EOF   -->

            <?php
            $resto_logo_opt = get_option( 'resto_logo' );
            if( empty($resto_logo_opt) ){
                $resto_logo_opt = get_template_directory_uri() . '/assets/img/logo.png';
            }

            $resto_phone_opt = get_option( 'resto_phone' );
            $resto_social_opts = get_option( 'resto_social', array() );
            ?>

            <!-- BEGIN FOOTER -->
            <footer class="footer">
                <div class="wrapper">
                    <div class="footer__content">

                        <div class="logo footer__logo">
                            <a href="<?php echo esc_url( home_url()); ?>">
                                <img src="<?php echo $resto_logo_opt; ?>" alt="logo" class="logo__pic footer__logo-pic">
                            </a>
                        </div>
                        <div class="social footer__social">
                            <?php foreach( $resto_social_opts as $social => $link ) { ?>
                            <a href="<?php echo $link; ?>" class="social__item"><i class="icon-<?php echo $social; ?>"></i></a>
                            <?php } ?>
                        </div>
                        <?php if ( !empty($resto_phone_opt) ) { ?>
                        <div class="contacts footer__contacts">
                            <a href="tel:<?php echo get_phone_clean($resto_phone_opt); ?>" class="contact footer__contact">
                                <i class="icon-mobile-phone"></i><span><?php echo $resto_phone_opt; ?></span>
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </footer>
            <!-- FOOTER EOF   -->

        </div>

        <div class="menu drop-menu js-drop-menu">
            <div class="drop-menu__close close">
                <i class="icon-close"></i>
            </div>
            <div class="wrapper">
                <div class="drop-menu__content resto-main-menu">
                    <div class="menu__title drop-menu__title"><?php _e('Menu', 'ht_resto'); ?></div>
                    <?php 
                    if( get_option( 'resto_site_menu_type' ) == 'product_categories' ){
                        get_template_part( 'template-parts/menu/product_categories_menu' );
                    } else {
                        get_template_part( 'template-parts/menu/restaurant_menu' );
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="burger-menu js-burger-menu">
            <div class="burger-menu__close close">
                <i class="icon-close"></i>
            </div>
            <div class="wrapper">
                <?php
                $network_menu = resto_get_menu_items_list('network_menu');
                if( $network_menu ){
                ?>
                <ul class="burger-menu__list">
                    <?php foreach ( $network_menu as $nt_menu_item ) { ?>
                    <li class="burger-menu__item">
                        <a href="<?php echo $nt_menu_item['link']; ?>" class="burger-menu__link"><?php echo $nt_menu_item['title']; ?></a>
                    </li>
                    <?php } ?>
                </ul>
                <?php } ?>
            </div>
        </div>
        
        <div class="popup_messagebar">
            <div class="msg_text"></div>
        </div>
        
        <div class="scroll-top js-scroll-top"><?php _e('Top', 'ht_resto'); ?></div>

        <div class="icon-load"></div>

        <!-- BODY EOF   -->

        <?php wp_footer(); ?>
        
    </body>

</html>

