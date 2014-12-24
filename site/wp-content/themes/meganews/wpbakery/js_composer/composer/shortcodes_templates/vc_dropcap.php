<?php
$output = $first_letter = $el_class = $first_letter_bg = $first_letter_color = $first_letter_css = $first_letter_size  = '';
extract(shortcode_atts(array(
    'first_letter' => '',
    'first_letter_bg' => '',
    'first_letter_color' => '',
    'first_letter_size' => '',
    'type' => 'type1',
    'css_animation' => '',
    'el_class' => ''
), $atts));


$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_dropcap wpb_content_element '.$el_class, $this->settings['base']);
$css_class .= $this->getCSSAnimation($css_animation);

if ($type == 'type1') {
	if (($first_letter_color != '')||($first_letter_size != '')) {
		$first_letter_css .= ' style= " ';
		if ($first_letter_color != '') {
			$first_letter_css .= ' color: '.$first_letter_color.';  ';
		}
		if ($first_letter_size != '') {
			$first_letter_css .= ' font-size: '.$first_letter_size.'px !important;  ';
		}
		$first_letter_css .= ' " ';	
	}
}

if ($type == 'type2') {
	if (($first_letter_bg != '')||($first_letter_color != '')||($first_letter_size != '')) {
		$first_letter_css .= ' style= " ';
		if ($first_letter_bg != '') {
			$first_letter_css .= ' background-color: '.$first_letter_bg.';  ';
		}
		if ($first_letter_color != '') {
			$first_letter_css .= ' color: '.$first_letter_color.';  ';
		}
		if ($first_letter_size != '') {
			$first_letter_css .= ' font-size: '.$first_letter_size.'px !important;  ';
		}
		$first_letter_css .= ' " ';	
	}
}
$output .= '<div class="'.$css_class.'"><div class="dropcap '.$type.'"><span class="first_letter" '.$first_letter_css.'>'.$first_letter.'</span>'.wpb_js_remove_wpautop($content).'</div></div>';
echo $output;