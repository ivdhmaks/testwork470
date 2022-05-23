jQuery(document).ready(function($){
    
    /**
     * Add new InfoBlock
     */
    $('.add-infoblock-button').on('click', function(e){
        
        // table row id
        var infoblock_max_id = parseInt( $(this).parent().find('input.infoblock_max_id').val() );
        infoblock_max_id = infoblock_max_id + 1;
        
        // wp.template
        var tpl_data = {
            row_id: infoblock_max_id
        };
        var template = wp.template( 'infoblock-table-row' );
        var rendered_html = template(tpl_data);

        // update table
        $('.info-blocks-section table.info-blocks-table tbody').append( rendered_html );
        
        // update max row id
        $(this).parent().find('input.infoblock_max_id').val(infoblock_max_id);
    });
    
    /**
     * Remove InfoBlock
     */
    $(document.body).on('click', '.delete-infoblock-button', function(e){
        var answer = confirm('Remove this InfoBlock?');
        if(answer == true){
            $(this).parent().parent().remove();
        }
    });
    
    /**
     * Reset custom fileds in the WC_Product data tab
     */
    $('#resto_product_data .btn-htr-reset').on('click', function(e){
        e.preventDefault();
        
        var r = confirm("Clear all custom fields?");
        if( r === true ){
            
            var data_tab_id = '#resto_product_data';
            
            // reset date input
            $(data_tab_id + ' input[name="htr_product_date"]').val('');
            
            // reset image select box
            $(data_tab_id + ' input[name="htr_product_image_id"]').val('');
            // reset image src
            var default_img = $(data_tab_id + ' .control_image_browse > img').attr('data-img-default');
            $(data_tab_id + ' .control_image_browse > img').attr('src', default_img);
            
            // reset product type select
            $(data_tab_id + ' select[name="htr_product_type"]').prop('selectedIndex', 0);
        }
        
    });
    
    /**
     * Publish/Update product button
     */
    $('#resto_product_data .btn-htr-update').on('click', function(e){
        e.preventDefault();
        $('#publish').trigger('click');
    });
    
});