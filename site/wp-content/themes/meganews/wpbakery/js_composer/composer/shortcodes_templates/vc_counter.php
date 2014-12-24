<?php
$output = $color = $el_class = $css_animation = $url_value = $url_target = $icon_color = $icon_bgcolor = $icon_css = $iconwrapper_css = $title_color = $title_hovercolor = $title_css = '';
extract(shortcode_atts(array(
    'title' => '',
    'icon' => '',
    'counter_value' => '',
    'icon_color' => '',
    'bg_color' => '',
    'title_color' => '',
    'value_color' => '',
    'css_animation' => '',
    'el_class' => ''
), $atts));
wp_enqueue_script( 'waypoints' );

$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_counter wpb_content_element '.$el_class, $this->settings['base']);
$css_class .= $this->getCSSAnimation($css_animation);

$id = rand(1, 10000);
$output = '<script>
 // start all the timers
 jQuery(document).ready(function($){
  

    function count(options) {
        var $this = $(this);
        options = $.extend({}, options || {}, $this.data("countToOptions") || {});
        $this.countTo(options);
      }
	  	
 
      
  
    if (typeof jQuery.fn.waypoint !== "undefined") {
    	jQuery("#counter_'.$id.'").waypoint(function($) {
	  	
	  	   jQuery("#counter_'.$id.'").each(count);
	  	
		}, { offset: "85%" });
    }
  
    
      
      
      
       });
</script>';


if (($icon_color != '')||($value_color != '')||($bg_color != '')||($title_color != '')) {
	$output .= '<style> ';
	if ($icon_color != '') {
		$output .= '  .counter-specific-wrapper-'.$id.' .counter-icon-wrapper .counter-icon { color: '.$icon_color.';  } ';
	}
	if ($value_color != '') {
		$output .= '  .counter-specific-wrapper-'.$id.' .counter_execute { color: '.$value_color.';  } ';
	}
	if ($bg_color != '') {
		$output .= '  .counter-specific-wrapper-'.$id.'  { background-color: '.$bg_color.';  } ';
	}
	if ($title_color != '') {
		$output .= '  .counter-specific-wrapper-'.$id.' .counter-title { color: '.$title_color.';  } ';
	}
	$output .= '</style>';
}



$output .= '<div class="'.$css_class.'">';
	$output .='<div class="counter-wrapper counter-specific-wrapper-'.$id.'">';
		if (($icon != '')&&($icon != 'no-icon')) {
			$output .= '<div class="counter-icon-wrapper"><span class="counter-icon icon-'.$icon.'"></span></div>';
		}
		$output .= '<b class="counter_execute" id="counter_'.$id.'" data-from="0" data-to="'.$counter_value.'" data-speed="1500"></b>';
		if ($title != '') {
			$output .= '<h1 class="counter-title">'.$title.'</h1>';
		}
	$output .= '</div>';
$output .= '</div>';


echo $output;
?>