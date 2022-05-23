<?php

/**
 * Output a custom image select box
 */
function resto_woo_image_select_input( $params ){
    global $post;
    
    // image placeholder
    $default_img = get_template_directory_uri() . '/inc/admin/assets/placeholder-image.png';
    
    $params['name']         = !empty( $params['name'] ) ? $params['name'] : $params['id'];
    $params['value']        = !empty( $params['value'] ) ? $params['value'] : get_post_meta( $post->ID, $params['id'], true );
    $params['img_url']      = !empty( $params['img_url'] ) ? $params['img_url'] : $default_img;
    $params['img_default']  = !empty( $params['value_default'] ) ? $params['value_default'] : $default_img;
    $params['desc_tip']     = !empty( $params['desc_tip'] ) ? $params['desc_tip'] : false;
    
    ?>
    <div class="form-field <?php echo $params['id']; ?>_field">
        <label for="<?php echo $params['id']; ?>"><?php echo $params['label']; ?></label>
        <div class="control_image_browse">
            <img src="<?php echo ( !empty($params['img_url']) ) ? $params['img_url'] : $params['img_default']; ?>" width="120px" height="120px" data-img-default="<?php echo $params['img_default']; ?>"/>
            <div>
                <input type="hidden" id="<?php echo $params['id']; ?>" name="<?php echo $params['name']; ?>" value="<?php echo $params['value']; ?>" />
                <input type="button" class="image_browse_button button" data-value-type="attachment_id" value="Set Image"/>
                <input type="button" class="image_clear_button button" value="X">
            </div>
        </div>
        <?php if( !empty($params['desc_tip']) ){ ?>
        <p class="description" id="tagline-description_logo"><?php echo $params['desc_tip']; ?></p>
        <?php } ?>
    </div>
    
    <?php
    
}