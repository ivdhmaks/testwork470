$(window).on('load', function () {
	if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
		$('body').addClass('ios');
	} else {
		$('body').addClass('web');
	};
	$('body').removeClass('loaded');
});
/* viewport width */
function viewport() {
	var e = window,
		a = 'inner';
	if (!('innerWidth' in window)) {
		a = 'client';
		e = document.documentElement || document.body;
	}
	return {
		width: e[a + 'Width'],
		height: e[a + 'Height']
	}
};


/* viewport width */
$(function () {
	/* placeholder*/
	// $('input, textarea').each(function () {
	// 	var placeholder = $(this).attr('placeholder');
	// 	$(this).focus(function () {
	// 		$(this).attr('placeholder', '');
	// 	});
	// 	$(this).focusout(function () {
	// 		$(this).attr('placeholder', placeholder);
	// 	});
	// });

	var y_offsetWhenScrollDisabled = 0,
		offset = 0;

	$(window).scroll(function () {
		y_offsetWhenScrollDisabled = $(window).scrollTop();
	});

	function lockScroll() {
		offset = y_offsetWhenScrollDisabled;
		$('html').addClass('scrollDisabled');
		$('html').css('margin-top', -y_offsetWhenScrollDisabled);
	}

	function unlockScroll() {
		$('html').removeClass('scrollDisabled');
		$('html').css('margin-top', '');
		$('html, body').animate({
			scrollTop: offset
		}, 0);
	}

	
	
	function scrollNav(el) {

		let offsetLastPos = 0;

		function fixedNavigation(lastOffset) {
			let curr_pos = window.pageYOffset || document.documentElement.scrollTop;

			if (curr_pos > header.offsetHeight) {
				header.classList.add("sticky");
			} else {
				header.classList.remove("sticky");
			}
		}


		let header = document.querySelector(el),
			body = document.querySelector("body");

		let sticky = header.offsetTop,
			sticky_height = header.offsetHeight;


		function setTopPadding() {
			header_height = header.offsetHeight;
			body.style.paddingTop = `${header_height}px`;
		}

		// setTopPadding();

		window.onscroll = function (e) {
			fixedNavigation(offsetLastPos);
		};

		window.onresize = function () {
			fixedNavigation(offsetLastPos);
		}

		fixedNavigation(offsetLastPos);
	}

	scrollNav('.header');




	function navigation(btn, menu) {
		let $burger = jQuery(btn),
			$nav = jQuery(menu);

		function checkNavShown() {
			if ($nav.hasClass('active')) {
				return true;
			} else {
				return false
			}
		}


		function showNav() {
			$nav.addClass('active');
			lockScroll();
			return false;
		}


		function hideNav() {
			$nav.removeClass('active');
			unlockScroll();
			return false;
		}

		$burger.on('click', () => {
			showNav();
			
		})

		$nav.find('.close').on('click', () => {
			hideNav();
		})

		jQuery(document).mouseup((e) => {
			if (!$nav.is(e.target) && $nav.has(e.target).length === 0 && $nav.hasClass('active')) {
				hideNav()
			}
		});
	}
	navigation('.js-burger', '.js-burger-menu');
	navigation('.js-open-menu', '.js-drop-menu');



	function getOffsetTop(el) {
		let $pos = jQuery(el).scrollTop();

		return $pos;
	}

	function showOnScroll($btn, $offset) {
		let $window_offset = getOffsetTop(window);

		if ($window_offset > $offset) {
			$btn.fadeIn(400);
		} else {
			$btn.fadeOut(200);
		}
	}

	if (jQuery('.js-scroll-top').length && $(window).width() > 991) {
		let $btn = jQuery('.js-scroll-top')
		showOnScroll($btn, 900);

		jQuery(window).scroll(() => {
			showOnScroll($btn, 900);
		})

		$btn.on('click', () => {
			jQuery("html, body").animate({
				scrollTop: "0"
			}, 600);
		})
	}

	function quantityCalc() {
		let $btn = jQuery('.js-quantity-btn');
		

        function activeReload(e) {
            let $reload_btn = e.closest('.quantity-box').siblings('.js-quantity-reload');
            if ($reload_btn.length && !$reload_btn.attr('data-active')) {
                $reload_btn.attr('data-active', 'active');
            } else {
                return false;
            }
        }


        jQuery(document.body).on('click', '.js-quantity-btn', function () {
            let $input = jQuery(this).siblings('.js-quantity'),
                $val = +$input.val(),
                $min, $step, res;


            if (!($val >= 0)) {
                $val = 1;
            } else {
                $val = $val;
            }

            if (!$input.attr('min')) {
                $min = 1;
            } else {
                $min = $input.attr('min');
            }

            if (!$input.attr('step')) {
                $step = 1;
            } else {
                $step = $input.attr('step');
            }

            if (jQuery(this).hasClass('minus') && $val > $min) {
                res = $val -= +$step;
                // activeReload(jQuery(this));

                if (Number.isInteger(res)) {
                    $input.val(res.toFixed(0));
                } else {
                    $input.val(res.toFixed(1));
                }
                
                $input.trigger('change');

            } else if (jQuery(this).hasClass('plus')) {
                res = $val += +$step;
                // activeReload(jQuery(this));

                if (Number.isInteger(res)) {
                    $input.val(res.toFixed(0));
                } else {
                    $input.val(res.toFixed(1));
                }
                
                $input.trigger('change');
            }

            // reloadPrices();

        })

        // jQuery('.js-quantity').on('change', function () {
        //     activeReload(jQuery(this));
        // })

        // jQuery('.js-quantity-reload .btn').on('click', function () {
        //     if (jQuery(this).closest('.js-quantity-reload').attr('data-active') == 'active') {
        //         document.updateCart.submit();
        //     }
        // })
    }

    quantityCalc();

});


function blockVH(el, dif) {
	var block = $(el),
		vh = $(window).height();

	if (dif) {
		block.css({
			'min-height': vh,
			'padding-top': dif,
		});
	} else {
		block.css({
			'min-height': vh,
		});
	}
}

var handler = function () {

	var height_footer = $('footer').height();
	var height_header = $('header').height();
	//$('.content').css({'padding-bottom':height_footer+40, 'padding-top':height_header+40});


	var viewport_wid = viewport().width;
	var viewport_height = viewport().height;

	if (viewport_wid <= 991) {
		$('.intro-section').css('padding-top', $('.header').innerHeight());

	} else {
		blockVH('.intro-section', $('.header').innerHeight());
	}

}
$(window).bind('load', handler);
$(window).bind('resize', handler);

var header_fix_handler = function () {

	var height_footer = $('footer').height();
	var height_header = $('header').height();
	//$('.content').css({'padding-bottom':height_footer+40, 'padding-top':height_header+40});


	var viewport_wid = viewport().width;
	var viewport_height = viewport().height;

	if (viewport_wid <= 991) {
		$('.header-fix').css('padding-top', $('.header').innerHeight());

	} else {
		blockVH('.header-fix', $('.header').innerHeight());
	}

}
$(window).bind('load', header_fix_handler);
$(window).bind('resize', header_fix_handler);

$(document).ready(function($){
    
    /**
     * Blocking product buttons on AJAX adding to cart
     * 
     * Handle WooCommerce event 'adding_to_cart' which are triggered during adding item to cart
     * event 'adding_to_cart' is triggered in /wp-content/plugins/woocommerce/assets/js/frontend/add-to-cart.js
     */
    $(document.body).on('adding_to_cart', function(event, $thisbutton, data){
        var product_id = data.product_id;
        $('[data-product_id="' + product_id + '"] .product__content').addClass('ajax_loading');
        $thisbutton.attr('disabled', 'disabled');
    });
    
    /**
     * Unblock product buttons
     * Update mini-cart content in header
     * 
     * Handle WooCommerce event 'added_to_cart' which are triggered after ajax request winished
     * event 'added_to_cart' is triggered in /wp-content/plugins/woocommerce/assets/js/frontend/add-to-cart.js
     */
    $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $thisbutton){
        var product_id = $thisbutton.attr('data-product_id');
        $('[data-product_id="' + product_id + '"] .product__content').removeClass('ajax_loading');
        $thisbutton.removeAttr('disabled');
        
        updateMiniCart(fragments);
    });
    
    /**
     * Update mini-cart content in header
     * 
     * Handle WooCommerce event 'removed_from_cart' which are triggered after removed item from cart
     * event 'removed_from_cart' is triggered in /wp-content/plugins/woocommerce/assets/js/frontend/add-to-cart.js
     */
    $(document.body).on('removed_from_cart', function( event, fragments, cart_hash, $button ){
        updateMiniCart(fragments);
    });
    
    /**
     * Update mini cart in header
     * 
     * @param {type} cart_fragments
     * @returns {undefined}
     */
    function updateMiniCart(cart_fragments){
        var $mini_cart = $('#header_mini_cart');
        $mini_cart.find('.mini-cart-total').text(cart_fragments.cart_total_sum);
        $mini_cart.find('.mini-cart-count').text(cart_fragments.cart_items_count);
        $mini_cart.find('.mini-cart-btn').attr('data-cart_items_count', cart_fragments.cart_items_count);
    }
    
    /**
     * Update 'data-quantity' attribute on quantity input change
     */
    $(document.body).on('change input', 'input.js-quantity', function () {
        var $thisInput = $(this);
        var $qty_val = $thisInput.val();
        $thisInput.parent().parent().find('.add_to_cart_button').attr('data-quantity', $qty_val);
    });
    
    /**
     * Show popup if cart is empty
     */
    $('.mini-cart-btn').on('click', function(event){
        var cart_item_count = parseInt($(this).attr('data-cart_items_count'));
        if( !cart_item_count > 0 ){
            event.preventDefault();
            popup.show('Your cart is empty');
            return false;
        }
    });
    
    /**
     * AJAX load more 
     */
    $('.products-load-more-button').on('click', function () {
        
        var $thisBtn = $(this);
        var $loaderBlock = $thisBtn.parent();
        
        var post_per_page = $thisBtn.attr('data-post_per_page') || 6;
        var current_page = $thisBtn.attr('data-current_page') || 1;
        var max_num_pages = $thisBtn.attr('data-max_num_pages') || 3;
        
        // next page
        var next_page = parseInt(current_page) + 1;
        
        var data = {
            post_per_page: post_per_page,
            page: next_page,
            action: 'products_load_more'
        };
        
        $.ajax({
            url: RESTO.ajaxurl,
            data: data,
            type: 'POST',
            dataType: 'json',
            beforeSend: function (response) {
                $loaderBlock.addClass('ajax_loading');
            },
            complete: function (response) {
                $loaderBlock.removeClass('ajax_loading');
            },
            success:function(response){
                if( response.error ){
                    console.log(response.error);
                    return;
                } 
                
                if( response.html.length == 0 || next_page >= max_num_pages ){
                    $thisBtn.attr('disabled', 'disabled');
                    //$thisBtn.text('No more products');
                }
                
                if( response.html.length ){
                    $('.products-box .products').append(response.html);
                    $thisBtn.attr('data-current_page', next_page);
                }
            }
        });
    });
    
    /**
     * Popup
     */
    var popup = {
        'show': function(message_text){
            $('.popup_messagebar .msg_text').html(message_text);
            $('.popup_messagebar').animate({
                opacity: 1,
                top: '8em'
            }, 500, function(){
                setTimeout(function(){ 
                    popup.close();
                }, 4000);
            });
        },
        'close': function(){
            $('.popup_messagebar').animate({
                opacity: 0,
                top: '-1em'
            }, 500, function(){
                $('.popup_messagebar .msg_text').html('');
            }); 
        }
    };
    
    /**
     * Product Gallery popup
     */
    $(document.body).on('click', '.product__img-box', function(e){
        e.preventDefault();
        
        var gallery_id = $(this).attr('data-gallery_id');
        var $links_el = $('#product__img-gallery_' + gallery_id + ' > a');
        
        if( $links_el.length > 0 ){
            var img_items = [];
            
            $links_el.each(function(index){
                img_items.push({src: $(this).attr('href')});
            });
            
            $.magnificPopup.open({
                type: 'image',
                items: img_items,
                gallery: {
                    enabled: true
                },
		closeBtnInside: false,
		fixedContentPos: true,
		mainClass: 'mfp-no-margins mfp-with-zoom',
            });
        }
        
        return false;
    });
    
    /**
     * GoogleMaps Popup
     */
    $('.gmaps-popup').magnificPopup({
        type: 'iframe',
        mainClass: 'mfp-fade',
        removalDelay: 160,
        preloader: true,
        fixedContentPos: false
    });
    
    /**
     * Resto MainMenu submenu toggle
     */
    $('.resto-main-menu span.submenu_toggle').on('click', function(){
        var $this_btn = $(this);
        var $parent = $this_btn.parent();
        var $submenu = $parent.find('.submenu');
        
        // close all other submenus, not this submenu
        $('.resto-main-menu .submenu').not($submenu).slideUp(400, function(){
            $('.resto-main-menu .menu__item').not($parent).removeClass('open');
        });
        
        // open or close this submenu
        if( $parent.hasClass('open') ){
            $submenu.slideUp(400, function(){
                $parent.removeClass('open');
            });
        } else {
            $submenu.slideDown(400, function(){
                $parent.addClass('open');
            });
        }
    });
    
    /**
     * Category Menu widget submenu toggle
     */
    $('.category-widget-menu span.submenu_toggle').on('click', function(){
        var $this_btn = $(this);
        var $parent = $this_btn.parent();
        var $submenu = $parent.find('.cat-submenu');
        
        // close all other submenus, not this submenu
        $('.category-widget-menu .cat-submenu').not($submenu).slideUp(400, function(){
            $('.category-widget-menu .cat-menu__item').not($parent).removeClass('open');
        });
        
        // open or close this submenu
        if( $parent.hasClass('open') ){
            $submenu.slideUp(400, function(){
                $parent.removeClass('open');
            });
        } else {
            $submenu.slideDown(400, function(){
                $parent.addClass('open');
            });
        }
    });
    
});

/**
 * Product Create Form behaviors
 */
$(document).ready(function($){
    
    var $product_create_form = $('form.product-create-form');
    
    if( $product_create_form ){
        
        var $input_product_title = $product_create_form.find('input[name="product_title"]');
        var $input_product_price = $product_create_form.find('input[name="product_price"]');
        var $select_product_type = $product_create_form.find('select[name="product_type"]');
        var $input_product_date = $product_create_form.find('input[name="product_date"]');
        var $input_image_id = $product_create_form.find('input[name="product_image_id"]');
        
        var $input_file_hidden = $product_create_form.find('input.input-file-hidden');
        
        var $btn_image_clear = $product_create_form.find('.btn-product-image-clear');
        var $btn_product_create = $product_create_form.find('.btn-product-create');
        
        var $image_preview = $product_create_form.find('img.image-preview');
        var $form_errors_el = $product_create_form.find('.form-errors');
        
        /**
         * Image select box
         */
        $('.product-image-field img, .overlay-image-select').on('click', function(e){
            e.preventDefault();
            $input_file_hidden.trigger('click');
        })
        
        /**
         * Reset Image select box
         */
        $btn_image_clear.on('click', function(e){
            e.preventDefault();
            
            var default_img = $image_preview.attr('data-img-default');
            $image_preview.attr('src', default_img);
            
            $input_image_id.val('');
            $input_file_hidden.val('');
        });
        
        /**
         * AJAX Image upload
         */
        $input_file_hidden.on('change', function(e){
            var file_data = $(this).prop('files')[0];
            if( !file_data ){
                return false;
            }
            
            // reset form errors
            $form_errors_el.html('');
            
            // collect form data
            var form_data = new FormData();                  
            form_data.append('product_image', file_data);
            form_data.append('action', 'product_create_image_upload');
            
            $.ajax({
                url: RESTO.ajaxurl,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'POST',
                beforeSend: function() {
                    $product_create_form.addClass('ajax_loading');
                },
                complete: function() {
                    $product_create_form.removeClass('ajax_loading');
                },
                success:function(response){
                    if( response.error ){
                        console.log(response.error);
                        $form_errors_el.html('<p>' + response.error + '</p>');
                        return;
                    } 

                    if( response.attachment_id && response.image_url ){
                        $input_image_id.val(response.attachment_id);
                        $image_preview.attr('src', response.image_url);
                    }
                }
            });
            
        });
        
        /**
         * AJAX Product create
         */
        $btn_product_create.on('click', function(e){
            e.preventDefault();
            
            var data = {
                product_title: $input_product_title.val(),
                product_price: $input_product_price.val(),
                product_type: $select_product_type.val(),
                product_date: $input_product_date.val(),
                product_image_id: $input_image_id.val(),
                action: 'product_create_new'
            };

            $.ajax({
                url: RESTO.ajaxurl,
                data: data,
                type: 'POST',
                dataType: 'json',
                beforeSend: function (response) {
                    $product_create_form.addClass('ajax_loading');
                },
                complete: function (response) {
                    $product_create_form.removeClass('ajax_loading');
                },
                success:function(response){
                    if( response.error ){
                        console.log(response.error);
                        $form_errors_el.html('<p>' + response.error + '</p>');
                        return;
                    } 

                    if( response.message ){
                        $('.form-wrapper').html(response.message);
                    }
                }
            });
            
        });
        
    }
    
});