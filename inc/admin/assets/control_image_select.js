jQuery(function($){
    
    /**
    * Callback function for 'onclick' event
    * Used for all buttons with class 'image_browse_button'
    * 
    * @param {event} e - event object
    * @returns {Boolean}
    */
    var onclick_browse_image = function(e){
       e.preventDefault();

       var button = $(this);
       var custom_uploader = wp.media({
           title: 'Browse image',
           button: {
               text: 'Select image' // button label text
           },
           library: {
               type: 'image'
           },
           multiple: false // for multiple image selection set to true
       });

       custom_uploader.on('select', function() { // it also has "open" and "close" events 
           var value_type = $(button).data('value-type');
           var attachment = custom_uploader.state().get('selection').first().toJSON();

           $(button).parent().prev().attr('src', attachment.url);
           
           if( value_type == 'attachment_id' ){
               $(button).prev().val(attachment.id);
           } else {
               $(button).prev().val(attachment.url);
           }

       });

       custom_uploader.open();

       return false;
    };


    /**
     * Callback function for 'onclick' event
     * Used for all buttons with class 'image_clear_button' 
     * 
     * @param {event} e - event object
     * @returns {Boolean}
     */
    var onclick_clear_image = function(e){
       e.preventDefault();

       var button = $(this);
       var r = confirm("Clear image?");
       if (r == true) {
           var default_img = $(button).parent().prev().attr('data-img-default');
           $(button).parent().prev().attr('src', default_img);
           $(button).prev().prev().val('');
       }

       return false;
    };
    
    // binding callback 'onclick_browse_image' to event 'click' for buttons with class 'image_browse_button'
    $(document.body).on('click', '.control_image_browse .image_browse_button', onclick_browse_image);

    // binding callback 'onclick_clear_image' to event 'click' for buttons with class 'image_clear_button'
    $(document.body).on('click', '.control_image_browse .image_clear_button', onclick_clear_image);
    
});

