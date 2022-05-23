<?php

function resto_frontpage_opt(){
    global $resto_frontpage;?>
    
    <div class="wrap">
        <h1>Front Page Options</h1>
        <form method="post" enctype="multipart/form-data" action="options.php">
            <?php 
            settings_fields('resto_frontpage'); 
            do_settings_sections($resto_frontpage);
            
            // Frontpage H1 title option
            $resto_frontpage_title_opt = get_option( 'resto_frontpage_title' );
            $resto_frontpage_subtitle_opt = get_option( 'resto_frontpage_subtitle' );
            
            // Main image option
            $resto_main_image_opt = get_option( 'resto_main_image' );
            
            // Front page SEO content
            $resto_frontpage_content = get_option( 'resto_frontpage_content' );
            
            // Info block option
            // option value structure:
            // $infoblock = array(
            //     'caption' => '',
            //     'blocks' => array(
            //         [
            //             'image' => '',
            //             'text'  => '',
            //         ],
            //         [
            //             'image' => '',
            //             'text'  => '',
            //         ]
            //     )
            // );
            $resto_infoblock = get_option( 'resto_infoblock' );
            $infoblock_defaults = array(
                'caption'   => '',
                'blocks'    => array()
            );
            // merge '$resto_infoblock' with defaults '$infoblock_defaults'
            $resto_infoblock = wp_parse_args( $resto_infoblock, $infoblock_defaults );
            
            // image placeholder
            $default_img = get_template_directory_uri() . '/inc/admin/assets/placeholder-image.png';
            
            ?>
            
            <hr>
            <h2 class="title">FrontPage Title</h2>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th><label for="resto_frontpage_title">H1 Title</label></th>
                        <td>
                            <input name="resto_frontpage_title" id="resto_frontpage_title" type="text" style="width: 50em;" value="<?php echo $resto_frontpage_title_opt; ?>" class="regular-text code">
                        </td>
                    </tr>
                    <tr>
                        <th><label for="resto_frontpage_subtitle">H1 SubTitle</label></th>
                        <td>
                            <input name="resto_frontpage_subtitle" id="resto_frontpage_subtitle" type="text" style="width: 50em;" value="<?php echo $resto_frontpage_subtitle_opt; ?>" class="regular-text code">
                        </td>
                    </tr>
                </tbody>
            </table>
            
            
            <hr>
            <h2 class="title">Main Image</h2>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th><label for="resto_main_image">Main Image</label></th>
                        <td>
                            <div class="control_image_browse">
                                <img src="<?php echo ( !empty($resto_main_image_opt) ) ? $resto_main_image_opt : $default_img; ?>" width="110px" height="90px" data-img-default='<?php echo $default_img; ?>'/>
                                <div>
                                    <input type="hidden" name="resto_main_image" value="<?php echo $resto_main_image_opt; ?>" />
                                    <input type="button" class="image_browse_button button" value="Set Image"/>
                                    <input type="button" class="image_clear_button button" value="X">
                                </div>
                            </div>
                            <p class="description" id="tagline-description_logo">Front page main image</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            
            <hr>
            <h2 class="title">Info Blocks</h2>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th><label for="infoblock_caption">Caption</label></th>
                        <td>
                            <input name="resto_infoblock[caption]" id="infoblock_caption" type="text" style="width: 50em;" value="<?php echo $resto_infoblock['caption']; ?>" class="regular-text code">
                            <p class="description" id="tagline-description_phone">Info block caption text</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="infoblock_blocks">Blocks</label></th>
                        <td>
                            <div class="info-blocks-section">
                                <table class="form-table info-blocks-table">
                                    <tbody>
                                    <?php 
                                    $block_index = 0;
                                    if( !empty($resto_infoblock['blocks']) ) :   
                                        foreach( $resto_infoblock['blocks'] as $block ):
                                            $block_index++;
                                    ?>
                                        <tr id="block-row-<?php echo $block_index; ?>" >
                                            <td>
                                                <p class="description">Icon</p>
                                                <div class="control_image_browse">
                                                    <img src="<?php echo (!empty($block['image'])) ? $block['image'] : $default_img; ?>" width="110px" height="90px" data-img-default='<?php echo $default_img; ?>'/>
                                                    <div>
                                                        <input type="hidden" name="resto_infoblock[blocks][<?php echo $block_index; ?>][image]" value="<?php echo $block['image']; ?>" />
                                                        <input type="button" class="image_browse_button button" value="Set Image"/>
                                                        <input type="button" class="image_clear_button button" value="X">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="description">Text</span>
                                                <input name="resto_infoblock[blocks][<?php echo $block_index; ?>][text]" type="text" value="<?php echo $block['text']; ?>" style="width: 30em;" class="regular-text code">
                                            </td>
                                            <td>
                                                <input type="button" class="button delete-infoblock-button" value="Remove"/>
                                            </td>
                                        </tr>
                                    <?php 
                                        endforeach;
                                    endif;
                                    ?>

                                    </tbody>
                                </table>
                                <div class="section_footer_buttons"> 
                                    <input class="infoblock_max_id" type="hidden" value="<?php echo $block_index; ?>" /> 
                                    <input type="button" class="add-infoblock-button button-primary button-large" value=" + Add Block" />  
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <hr>
            <h2 class="title">Content</h2>
            <table class="form-table">
                <tr>
                    <th><label for="resto_frontpage_content">SEO Text</label></th>
                    <td>
                        <?php
                            // we use built-in WP Editor for editing this meta field
                            $editor_settings = array(
                                'textarea_name' =>'resto_frontpage_content',
                                'textarea_rows' => 10,
                                'media_buttons' => true // enable mediafiles features
                            );
                            wp_editor( htmlspecialchars_decode($resto_frontpage_content), 'resto_frontpage_content', $editor_settings );
                        ?>
                        <p class="description" id="tagline-description">Front page SEO text</p>
                    </td>
                </tr>
            </table>
            
            <hr>
            <p class="submit">  
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />  
            </p>
        </form>        
    </div>

    <!-- wp.template for InfoBlock table row -->
    <script type="text/html" id="tmpl-infoblock-table-row">
        <tr id="block-row-{{data.row_id}}" >
            <td>
                <p class="description">Icon</p>
                <div class="control_image_browse">
                    <img src="<?php echo $default_img; ?>" width="110px" height="90px" data-img-default='<?php echo $default_img; ?>'/>
                    <div>
                        <input type="hidden" name="resto_infoblock[blocks][{{data.row_id}}][image]" value="" />
                        <input type="button" class="image_browse_button button" value="Set Image"/>
                        <input type="button" class="image_clear_button button" value="X">
                    </div>
                </div>
            </td>
            <td>
                <span class="description">Text</span>
                <input name="resto_infoblock[blocks][{{data.row_id}}][text]" type="text" value="" style="width: 30em;" class="regular-text code">
            </td>
            <td>
                <input type="button" class="button delete-infoblock-button" value="Remove"/>
            </td>
        </tr>
    </script>
    <!-- /wp.template for Slider table row -->

    <?php
}
