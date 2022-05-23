<?php

function resto_general_opt(){
    global $resto_general;?>
    
    <div class="wrap">
        <h1>General options</h1>
        <form method="post" enctype="multipart/form-data" action="options.php">
            <?php 
            settings_fields('resto_general'); 
            do_settings_sections($resto_general);
            
            // phone option
            $resto_phone_opt = get_option( 'resto_phone' );
            
            // email option
            $resto_email_opt = get_option( 'resto_email' );
            
            // Logo option
            $resto_logo_opt = get_option( 'resto_logo' );
            
            // social buttons options
            $resto_social_opts = get_option( 'resto_social' );
            $social_defaults = array(
                'facebook'  => '',
                'instagram' => '',
                'youtube'   => '',
            );
            // merge '$resto_social_opts' with defaults '$social_defaults'
            $resto_social_opts = wp_parse_args( $resto_social_opts, $social_defaults );
            
            // GoogleMaps option
            $gmap_delivery_region = get_option( 'gmap_delivery_region' );
            
            // Site Main Menu type (Product Categories or 'restaurant_menu' from WP NavMenus)
            $resto_site_menu_type = get_option( 'resto_site_menu_type' );
            
            // FoodMenu file option
            $foodmenu_file_opt = get_option( 'resto_foodmenu_file' );
            $foodmenu_file_title_opt = get_option( 'resto_foodmenu_file_title' );
            
            // InfoFile option
            $infofile_opt = get_option( 'resto_infofile' );
            $infofile_title_opt = get_option( 'resto_infofile_title' );
            
            // image placeholder
            $default_img = get_template_directory_uri() . '/inc/admin/assets/placeholder-image.png';
            
            ?>
            
            <hr>
            <h2 class="title">Company info</h2>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th><label for="resto_phone">Phone</label></th>
                        <td>
                            <input name="resto_phone" id="resto_phone" type="text" style="width: 50em;" value="<?php echo $resto_phone_opt; ?>" class="regular-text code">
                            <p class="description" id="tagline-description_phone">Company Phone</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="resto_email">Email</label></th>
                        <td>
                            <input name="resto_email" id="resto_email" type="text" style="width: 50em;" value="<?php echo $resto_email_opt; ?>" class="regular-text code">
                            <p class="description" id="tagline-description_email">Company Email</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="resto_logo">Site Logo</label></th>
                        <td>
                            <div class="control_image_browse">
                                <img src="<?php echo ( !empty($resto_logo_opt) ) ? $resto_logo_opt : $default_img; ?>" width="110px" height="90px" data-img-default='<?php echo $default_img; ?>'/>
                                <div>
                                    <input type="hidden" name="resto_logo" value="<?php echo $resto_logo_opt; ?>" />
                                    <input type="button" class="image_browse_button button" value="Set Logo"/>
                                    <input type="button" class="image_clear_button button" value="X">
                                </div>
                            </div>
                            <p class="description" id="tagline-description_logo">Provide your logo</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <hr>
            <h2 class="title">Social buttons</h2>
            <table class="form-table">
                <tbody>
                    <?php foreach( $resto_social_opts as $social => $link ) { ?>
                    <tr>
                        <th><label for="resto_<?php echo $social; ?>"><?php echo ucfirst($social); ?></label></th>
                        <td>
                            <input name="resto_social[<?php echo $social; ?>]" id="resto_<?php echo $social; ?>" type="text" style="width: 50em;" value="<?php echo $link; ?>" class="regular-text code">
                            <p class="description" id="tagline-description">Link for <?php echo ucfirst($social); ?></p>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            
            <hr>
            <h2 class="title">Google maps</h2>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th><label for="gmap_delivery_region">Delivery region map</label></th>
                        <td>
                            <input name="gmap_delivery_region" id="gmap_delivery_region" type="text" style="width: 50em;" value="<?php echo $gmap_delivery_region; ?>" class="regular-text code">
                            <p class="description" id="tagline-description_phone">Specify GoogleMaps link for Delivery region</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <hr>
            <h2 class="title">Site Menu</h2>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th><label for="resto_site_menu_type">Main Menu type</label></th>
                        <td>
                            <select name="resto_site_menu_type" id="resto_site_menu_type" style="width: 50em;" class="regular-text code">
                                <?php if( empty($resto_site_menu_type) ) { ?>
                                <option selected value="restaurant_menu">Show Restaurant Menu (Nav Menu)</option>
                                <option value="product_categories">Show Product Categories</option>
                                <?php } else { ?>
                                <option <?php echo ($resto_site_menu_type == 'restaurant_menu') ? 'selected' : ''; ?> value="restaurant_menu">Show Restaurant Menu (Nav Menu)</option>
                                <option <?php echo ($resto_site_menu_type == 'product_categories') ? 'selected' : ''; ?> value="product_categories">Show Product Categories</option>
                                <?php } ?>
                            </select>
                            <p class="description" id="tagline-description_menu_type">Select Main Menu type (WooCommerce Product Categories or 'Restaurant Menu' from Wordpress NavMenus)</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <hr>
            <h2 class="title">Downloads</h2>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th><label for="resto_foodmenu_file">Food Menu file</label></th>
                        <td>
                            <div class="foodmenu-title-input">
                                <p class="description" id="tagline-description_foodmenu">Download button title:</p>
                                <input name="resto_foodmenu_file_title" id="resto_foodmenu_file_title" type="text" value="<?php echo $foodmenu_file_title_opt; ?>" class="regular-text code">
                            </div>
                            <div class="control_file_browse">
                                <button type="button" class="button file_browse_button">Edit/Upload</button>
                                <input type="text" class="control_file_input regular-text code" name="resto_foodmenu_file" id="resto_foodmenu_file" value="<?php echo $foodmenu_file_opt; ?>" readonly style="width: 50em;" >
                                <button type="button" class="button file_clear_button">X</button>
                            </div>
                            <p class="description" id="tagline-description_foodmenu">Specify your food menu file (PDF files only)</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th><label for="resto_foodmenu_file">Info file</label></th>
                        <td>
                            <div class="foodmenu-title-input">
                                <p class="description" id="tagline-description_foodmenu">Download button title:</p>
                                <input name="resto_infofile_title" id="resto_infofile_title" type="text" value="<?php echo $infofile_title_opt; ?>" class="regular-text code">
                            </div>
                            <div class="control_file_browse">
                                <button type="button" class="button file_browse_button">Edit/Upload</button>
                                <input type="text" class="control_file_input regular-text code" name="resto_infofile" id="resto_infofile" value="<?php echo $infofile_opt; ?>" readonly style="width: 50em;" >
                                <button type="button" class="button file_clear_button">X</button>
                            </div>
                            <p class="description" id="tagline-description_foodmenu">Specify your information file (PDF files only)</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <hr>
            <p class="submit">  
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />  
            </p>
        </form>
    </div> 
<?php
}
