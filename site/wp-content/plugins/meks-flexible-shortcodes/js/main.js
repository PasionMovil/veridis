(function($) {
    $(document).ready(function ($) {
    	
if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
      /* Accordion Shortcode Handler */
       $("body").on("touchstart", ".mks_accordion_heading", function(e){
          var toggle = $(this).parent('.mks_accordion_item');
          if(toggle.hasClass('mks_accordion_active') == false){
              toggle.parent('div').find('.mks_accordion_item').find('.mks_accordion_content:visible').slideUp("fast");
              toggle.parent('div').find('.mks_accordion_active').removeClass('mks_accordion_active');
              toggle.find('.mks_accordion_content').slideToggle("fast", function(){
              toggle.addClass('mks_accordion_active');
          });
          } else {
            toggle.parent('div').find('.mks_accordion_item').find('.mks_accordion_content:visible').slideUp("fast");
            toggle.parent('div').find('.mks_accordion_active').removeClass('mks_accordion_active');
          }
       });

      /* Toggle Shortcode Handler */
       $("body").on("touchstart", ".mks_toggle_heading", function(e){
          var toggle = $(this).parent('.mks_toggle');
          toggle.find('.mks_toggle_content').slideToggle("fast", function(){
              toggle.toggleClass('mks_toggle_active');
            });
       });
    
      /* Tabs Shortcode Handler */
      $('.mks_tabs').each(function() {
        
        var tabs_nav = $(this).find('.mks_tabs_nav');

        $(this).find('.mks_tab_item').each(function() {        

          tabs_nav.append('<div class="mks_tab_nav_item">'+$(this).find('.nav').html()+'</div>');
          $(this).find('.nav').remove();          
          
        });
        
        $(this).find('.mks_tabs_nav').find('.mks_tab_nav_item:first').addClass('active');
        $(this).find('.mks_tab_item').hide();
        $(this).find('.mks_tab_item:first').show();
        $(this).show();
        
      });

       $("body").on("touchstart", ".mks_tabs_nav .mks_tab_nav_item", function(e){

        if($(this).hasClass('active') == false){
          
          tab_to_show = $(this).parent('.mks_tabs_nav').find('.mks_tab_nav_item').index($(this));

          $(this).parent('.mks_tabs_nav').parent('.mks_tabs').find('.mks_tab_item').hide();
          $(this).parent('.mks_tabs_nav').parent('.mks_tabs').find('.mks_tab_item').eq(tab_to_show).show();

          $(this).parent('.mks_tabs_nav').find('.mks_tab_nav_item').removeClass('active');
          $(this).addClass('active');
          
        }
      }); 
}
else{
      /* Accordion Shortcode Handler */
       $("body").on("click", ".mks_accordion_heading", function(e){
          var toggle = $(this).parent('.mks_accordion_item');
          if(toggle.hasClass('mks_accordion_active') == false){
              toggle.parent('div').find('.mks_accordion_item').find('.mks_accordion_content:visible').slideUp("fast");
              toggle.parent('div').find('.mks_accordion_active').removeClass('mks_accordion_active');
              toggle.find('.mks_accordion_content').slideToggle("fast", function(){
              toggle.addClass('mks_accordion_active');
          });
          } else {
            toggle.parent('div').find('.mks_accordion_item').find('.mks_accordion_content:visible').slideUp("fast");
            toggle.parent('div').find('.mks_accordion_active').removeClass('mks_accordion_active');
          }
       });

      /* Toggle Shortcode Handler */
       $("body").on("click", ".mks_toggle_heading", function(e){
          var toggle = $(this).parent('.mks_toggle');
          toggle.find('.mks_toggle_content').slideToggle("fast", function(){
              toggle.toggleClass('mks_toggle_active');
            });
       });
    
      /* Tabs Shortcode Handler */
      $('.mks_tabs').each(function() {
        
        var tabs_nav = $(this).find('.mks_tabs_nav');

        $(this).find('.mks_tab_item').each(function() {        

          tabs_nav.append('<div class="mks_tab_nav_item">'+$(this).find('.nav').html()+'</div>');
          $(this).find('.nav').remove();          
          
        });
        
        $(this).find('.mks_tabs_nav').find('.mks_tab_nav_item:first').addClass('active');
        $(this).find('.mks_tab_item').hide();
        $(this).find('.mks_tab_item:first').show();
        $(this).show();
        
      });

       $("body").on("click", ".mks_tabs_nav .mks_tab_nav_item", function(e){

        if($(this).hasClass('active') == false){
          
          tab_to_show = $(this).parent('.mks_tabs_nav').find('.mks_tab_nav_item').index($(this));

          $(this).parent('.mks_tabs_nav').parent('.mks_tabs').find('.mks_tab_item').hide();
          $(this).parent('.mks_tabs_nav').parent('.mks_tabs').find('.mks_tab_item').eq(tab_to_show).show();

          $(this).parent('.mks_tabs_nav').find('.mks_tab_nav_item').removeClass('active');
          $(this).addClass('active');
          
        }
      }); 
}		


});	
})(jQuery);