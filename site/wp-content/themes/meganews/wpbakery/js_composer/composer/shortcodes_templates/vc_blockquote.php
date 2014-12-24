<?php
$output = $first_letter = $el_class = $first_letter_bg = $first_letter_color = $first_letter_css = $first_letter_size = $background_color = $icon = $border_color = $background_color = $border_size = $border_css =  $icon_background_color = '';
extract(shortcode_atts(array(
    'type' => 'type1',
    'border_color' => '',
    'border_size' => '',
    'icon_image' => '',
    'background_color' => '',
    'icon_background_color' => '',
    'css_animation' => '',
    'el_class' => ''
), $atts));


$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_blockquote wpb_content_element '.$el_class, $this->settings['base']);
$css_class .= $this->getCSSAnimation($css_animation);


if ($type == 'type1') {
	if (($border_size != '')||($border_color != '')) {
		$border_css .= ' style=  " ';
		if ($border_size != '') {
			$border_css .= ' border-width: '.$border_size.'px;  ';
		}
		if ($border_color != '') {
			$border_css .= ' border-color: '.$border_color.';  ';
		}
		$border_css .= ' " ';	
	}
}
if ($type == 'type2') {
	if (($background_color != '')||($icon_image != '')) {
		$border_css .= ' style=  " ';		
		if ($icon_image != '') {
			$img_id = preg_replace('/[^\d]/', '', $icon_image);
			$link_to = wp_get_attachment_image_src( $img_id, 'thumbnail');
			$border_css .= ' background: url('.$link_to[0].') no-repeat scroll 20px center; ';
		}
		if ($background_color != '') {
			$border_css .= ' background-color: '.$background_color.';  ';
		}
		$border_css .= ' " ';	
	}
}

if ($type == 'type3') {
	if (($icon_background_color != '')||($icon_image != '')) {
		$border_css .= ' style=  " ';		
		if ($icon_image != '') {
			$img_id = preg_replace('/[^\d]/', '', $icon_image);
			$link_to = wp_get_attachment_image_src( $img_id, 'thumbnail');
			$border_css .= ' background: url('.$link_to[0].') no-repeat scroll center center; ';
		}
		if ($icon_background_color != '') {
			$border_css .= ' background-color: '.$icon_background_color.';  ';
		}
		$border_css .= ' " ';	
	}
}

if ($type == 'type3') {
	$output .= '<div class="'.$css_class.'"><div class="blockquote '.$type.'"><div class="icon_holder"'.$border_css.'></div><p>'.wpb_js_remove_wpautop($content).'</p></div></div>';
} else {
	$output .= '<div class="'.$css_class.'"><div class="blockquote '.$type.'"'.$border_css.'><p>'.wpb_js_remove_wpautop($content).'</p></div></div>';
}

echo $output;