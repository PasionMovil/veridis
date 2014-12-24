<?php
$output = $el_class = $title_css = $content_css = $title_font =  $content_font = $backoground_css =  $border_css = '';
extract(shortcode_atts(array(
    'icon' => '',
    'icon_color' => '',
    'css_animation' => '',
    'el_class' => ''
), $atts));


$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_list wpb_content_element '.$el_class, $this->settings['base']);
$css_class .= $this->getCSSAnimation($css_animation);

$id = rand(1, 10000);

$allIcons = pego_get_all_icons();
$allIconsCodes = pego_get_all_icons_codes();
$key = array_search($icon, $allIcons);
$code_value = $allIconsCodes[$key];
$output = '<div class="'.$css_class.'">';
if ($icon != 'no-icon') {
	$output .= '<style> .list'.$id.' ul li:before { content: \''.$code_value.'\';  } .list'.$id.' ul { list-style: none;  } ';
	if ($icon_color != '') {
		$output .= ' .list'.$id.' ul li:before { color: '.$icon_color.' !important;  } ';
	}
	$output .= '</style>';
}
	$output .= '<div class="list_sh list'.$id.'">';	
		$output .= wpb_js_remove_wpautop($content);		
	$output .= '</div>';
$output .= '</div>';
echo $output;
?>