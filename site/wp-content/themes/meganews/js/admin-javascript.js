 jQuery(document).ready(function($){
 
 
    var custom_uploader;

      $('.upload_image_button_custom_field').click(function(e) {
 
        e.preventDefault();
       
 	  //If the uploader object has already been created, reopen the dialog
      //  if (custom_uploader) {
       //     custom_uploader.open();
      //      return;
       // }
 	
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 		 var currentID = this.id;
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $( '#' + currentID + '_input').val(attachment.url);
            $( '#' + currentID + '_preview_img').attr("src", attachment.url);
            //$( '#' + currentID + '_preview_img').css("display", "block");
        });
        //Open the uploader dialog
        custom_uploader.open();
    });
    
    
    var custom_uploader1;

      $('.upload_mp3_button_custom_field').click(function(e) {
 
        e.preventDefault();
       
 	  //If the uploader object has already been created, reopen the dialog
      //  if (custom_uploader) {
       //     custom_uploader.open();
      //      return;
       // }
 	
        //Extend the wp.media object
        custom_uploader1 = wp.media.frames.file_frame = wp.media({
            title: 'Choose MP3',
            button: {
                text: 'Choose MP3'
            },
            multiple: false
        });
 		 var currentID = this.id;
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader1.on('select', function() {
            attachment = custom_uploader1.state().get('selection').first().toJSON();
            $( '#' + currentID + '_input').val(attachment.url);
            //$( '#' + currentID + '_preview_img').css("display", "block");
        });
        //Open the uploader dialog
        custom_uploader1.open();
    });
 
 
 
});