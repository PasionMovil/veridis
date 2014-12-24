<?php
$output = '';
extract(shortcode_atts(array(
    'icon' => '',
    'icon_size' => '',
    'icon_color' => '',
    'icon_hovercolor' => '',
    'icon_link' => '',
    'css_animation' => '',
    'el_class' => ''
), $atts));


$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_icon wpb_content_element '.$el_class, $this->settings['base']);
$css_class .= $this->getCSSAnimation($css_animation);

$id = rand(1, 10000);

$output .= "\n\t".'<div class="'.$css_class.'">';

if (($icon_size != '')||($icon_color != '')||($icon_hovercolor != '')) {
	$output .= '<style>';
	if (($icon_size != '')||($icon_color != '')) {
			$output .= ' .fonticons.iconid'.$id.' { ';
			if ($icon_size != '') {
				$output .= ' font-size: '.$icon_size.'px; ';
			}
			if ($icon_color != '') {
				$output .= ' color: '.$icon_color.'; ';
			}
			$output .= ' } ';
	}
	if ($icon_hovercolor != '') {
		$output .=  ' .fonticons.iconid'.$id.':hover { color: '.$icon_hovercolor.'; } ';
	}
	$output .= '</style>';
}
	
	if ($icon != 'no-icon') {
		if ($icon_link != '') {
			$output .= '<a href="'.$icon_link.'" target="_blank"><div class="fonticons iconid'.$id.' icon-'.$icon.'"></div></a>';
		}
		else {
			$output .= '<div class="fonticons iconid'.$id.' icon-'.$icon.'"></div>';
		}
	}		
$output .= "\n\t".'</div> ';

echo $output;