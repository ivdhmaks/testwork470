jQuery(function($){
    
    /**
    * Callback function for 'onclick' event
    * Used for all buttons with class 'file_browse_button'
    * 
    * @param {event} e - event object
    * @returns {Boolean}
    */
    var onclick_browse_file = function(e){
       e.preventDefault();

       var button = $(this);
       var custom_uploader = wp.media({
           title: 'Browse file',
           button: {
               text: 'Select file' // button label text
           },
           library: {
                order: 'ASC',
                orderby: 'title', // [ 'name', 'author', 'date', 'title', 'modified', 'uploadedTo', 'id', 'post__in', 'menuOrder' ]
                type: 'application/pdf' // mime type. e.g. 'image', 'image/jpeg', 'application/pdf' etc... see  get_allowed_mime_types() function
                // for multiple mime types use JS array e.g. [ 'video', 'image' ]
           },
           multiple: false // for multiple files selection set to true
       });

       custom_uploader.on('select', function() { // it also has "open" and "close" events 
           var attachment = custom_uploader.state().get('selection').first().toJSON();

           $(button).parent().find('input.control_file_input').val(attachment.url);
           //$(button).prev().val(attachment.id); // we don't need attachment id in this case

       });

       custom_uploader.open();

       return false;
    };


    /**
     * Callback function for 'onclick' event
     * Used for all buttons with class 'file_clear_button' 
     * 
     * @param {event} e - event object
     * @returns {Boolean}
     */
    var onclick_clear_file = function(e){
       e.preventDefault();

       var button = $(this);
       var r = confirm("Clear file url?");
       if (r == true) {
           $(button).parent().find('input.control_file_input').val('');
       }

       return false;
    };
    
    // binding callback 'onclick_browse_file' to event 'click' for buttons with class 'file_browse_button'
    $(document.body).on('click', '.control_file_browse .file_browse_button', onclick_browse_file);

    // binding callback 'onclick_clear_file' to event 'click' for buttons with class 'file_clear_button'
    $(document.body).on('click', '.control_file_browse .file_clear_button', onclick_clear_file);
    
});

