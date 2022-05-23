
<div class="products-box product-create-page">
    <div class="caption">
        <h1>Create your own product</h1>
    </div>
    <div class="form-wrapper">
        <form action="/" method="POST" class="product-create-form">
            <div class="loading-overlay">
                <span class="loading-spinner"></span>
            </div>
            <div class="form-panel">
                <div class="form-input">
                    <label for="product_title">Product Title&nbsp;<abbr class="required" title="required">*</abbr></label>
                    <input type="text" name="product_title" id="product_title" class="input-text" required>
                </div>
                <div class="form-input">
                    <label for="product_price">Product Price&nbsp;<abbr class="required" title="required">*</abbr></label>
                    <input type="number" name="product_price" min="1" step="1" id="product_price" class="input-text" required>
                </div>
                <div class="form-input">
                    <label for="product_type">Product Type</label>
                    <select id="product_type" name="product_type" class="input-select">
                        <?php foreach( resto_get_product_type_list() as $type => $type_title ){ ?>
                        <option value="<?php echo $type; ?>"><?php echo $type_title; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-input">
                    <label for="product_date">Product Date</label>
                    <input type="date" name="product_date" id="product_date" class="input-text">
                </div>
            </div>
            <div class="form-panel">
                <div class="form-input">
                    <div class="form-file-input">
                        <label for="product_image_id">Product image</label>
                        <div class="product-image-field">
                            <div class="image-container">
                                <input type="file" accept=".jpg, .jpeg, .png" name="img_file" class="input-file-hidden">
                                <?php $wc_image_default = wc_placeholder_img_src( 'woocommerce_single' ); ?>
                                <img src="<?php echo $wc_image_default; ?>" class="image-preview" data-img-default="<?php echo $wc_image_default; ?>"/>
                                <div class="overlay-image-select">Select image</div>
                            </div>
                            <div class="form-file-actions">
                                <input type="hidden" name="product_image_id" value="" />
                                <button class="btn btn-grey btn-product-image-clear">Clear image</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-action">
                <div class="form-errors"></div>
                <button class="btn btn-product-create">Create product</button>
            </div>
        </form>
    </div>
</div>
