var mks_shortcode;
var mks_shortcode_content;
(function() {
    tinymce.create('tinymce.plugins.mks_shortcodes', {
        init : function(ed, url) {
            ed.addButton('mks_shortcodes_button', {
                title : 'Meks Shortcodes',
                image : '../wp-content/plugins/meks-flexible-shortcodes/img/shortcodes-button.png',
                onclick : function() {
                    
                    mks_shortcode = ed.selection;
                    mks_shortcode_content = ed.selection.getContent();
                    
										var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
                    	W = W - 80;
                    	H = H - 84;
										
										
										var shortcodes_loaded = jQuery("#mks_shortcodes_holder").length;
										
										if (shortcodes_loaded){
										
												tb_show( 'Meks Shortcodes', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=mks_shortcodes' );
										
										} else {
												
												jQuery("body").append('<div id="mks_shortcodes_holder" style="display: none;"><div id="mks_shortcodes"></div></div>');
												
												jQuery.get('admin-ajax.php?action=mks_generate_shortcodes_ui', function(data) {
  													jQuery('#mks_shortcodes').html(data);
  													tb_show( 'Meks Shortcodes', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=mks_shortcodes' );
												});
										}
										
										
                    //tb_show( 'Meks Shortcodes', 'admin-ajax.php?action=mks_generate_shortcodes_ui&width=' + W + '&height=' + H);
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('mks_shortcodes', tinymce.plugins.mks_shortcodes);
})();