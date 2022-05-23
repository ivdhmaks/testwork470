<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="format-detection" content="telephone=no">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="icon" type="image/x-icon" href="favicon.ico" />
        
        <?php wp_head(); ?>
        
    </head>

    <?php
    $resto_logo_opt = get_option( 'resto_logo' );
    if( empty($resto_logo_opt) ){
        $resto_logo_opt = get_template_directory_uri() . '/assets/img/logo.png';
    }

    $resto_phone_opt = get_option( 'resto_phone' );
    ?>
    
    <body class="loaded <?php echo esc_attr( implode( ' ', get_body_class() ) ); ?>">

        <!-- BEGIN BODY -->
        <div class="main-wrapper">

            <!-- BEGIN HEADER -->
            <header class="header">
                <div class="wrapper">
                    <div class="header__content">
                        <?php if( !empty($resto_phone_opt) ) { ?>
                        <div class="contacts header__contacts">
                            <a href="tel:<?php echo get_phone_clean($resto_phone_opt);?>" class="contact header__contact">
                                <i class="icon-mobile-phone"></i><span><?php echo $resto_phone_opt; ?></span>
                            </a>
                        </div>
                        <?php } ?>
                        <div class="logo header__logo">
                            <a href="<?php echo esc_url( home_url()); ?>">
                                <img src="<?php echo $resto_logo_opt; ?>" alt="logo" class="logo__pic header__logo-pic">
                            </a>
                        </div>
                        
                        <?php if( class_exists('WooCommerce') ) { ?>
                        <div id="header_mini_cart" class="cart header__cart">
                            <div class="cart__info">
                                <div class="cart__caption"><?php _e('Your order', 'ht_resto'); ?></div>
                                <div class="cart__info-data">
                                    <div class="cart__info-cost"><?php _e('Cost:', 'ht_resto'); ?> <span class="mini-cart-total"><?php echo WC()->cart->get_cart_contents_total(); ?></span><?php echo resto_get_currency_symbol(); ?> </div>
                                    <div class="cart__info-count"> <?php _e('Items:', 'ht_resto'); ?> <span class="mini-cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span></div>
                                </div>
                            </div>
                            <a href="<?php echo wc_get_cart_url(); ?>" class="mini-cart-btn" data-cart_items_count="<?php echo WC()->cart->get_cart_contents_count(); ?>">
                                <i class="icon-cart cart__icon"></i>
                            </a>
                        </div>
                        <?php } ?>
                        
                        <div class="header__nav-box">
                            <div class="burger-wrapper">
                                <div class="burger-caption"><?php _e('Menu', 'ht_resto'); ?></div>
                                <div class="burger js-burger">
                                    <div class="burger__line"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- HEADER EOF   -->
            
            <!-- BEGIN CONTENT -->
            <main class="content">