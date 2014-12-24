(function($){
  jQuery('.my_param1_field').change(function(){
    jQuery(this).parent().parent().find('.wpb_vc_param_value').val(jQuery(this).val());
  });
})(window.jQuery);