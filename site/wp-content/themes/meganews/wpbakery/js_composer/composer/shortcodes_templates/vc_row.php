<?php
$output = $el_class = $row_fullwidth =  $extra_row_width_class = $row_section = $image = '';
extract(shortcode_atts(array(
    'el_class' => '',
    'bg_container_remove' => false,
    'row_section' => '',
    'bg_pattern' => '',
    'margin_top_row' => '',
    'margin_bottom_row' => '',
    'padding_top_row' => '',
    'padding_bottom_row' => '',
    'bgcolor' => '',
    'image' => $image
), $atts));

wp_enqueue_style( 'js_composer_front' );
wp_enqueue_script( 'wpb_composer_front_js' );
wp_enqueue_style('js_composer_custom_css');

$id = rand(1, 10000);

$el_class = $this->getExtraClass($el_class);

$css_class =  apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_row '.get_row_css_class().$el_class, $this->settings['base']);

$extra_row_width_class = "";

if ($row_fullwidth == false) { 
	$extra_row_width_class .= " container_width";
}


$addCss = '';
$addCssClass = '';
if (($margin_top_row != '')||($margin_bottom_row != '')) {
	$addCssClass = ' row_special_'.$id;
	$addCss .= ' <style> .row_special_'.$id.' { ';
	if ($margin_top_row != '') {
		$addCss .= ' margin-top: '.$margin_top_row.'px !important;';
	}
	if ($margin_bottom_row != '') {
		$addCss .= ' margin-bottom: '.$margin_bottom_row.'px !important;';
	}
	$addCss .= ' } </style> ';
}
$output = $addCss;


$addCss1 = '';
$addCssClass1 = '';
if (($padding_top_row != '')||($padding_bottom_row != '')) {
	$addCssClass1 = ' row_padding_special_'.$id;
	$addCss1 .= ' <style> .row_padding_special_'.$id.' { ';
	if ($padding_top_row != '') {
		$addCss1 .= ' padding-top: '.$padding_top_row.'px !important;';
	}
	if ($padding_bottom_row != '') {
		$addCss1 .= ' padding-bottom: '.$padding_bottom_row.'px !important;';
	}
	$addCss1 .= ' } </style> ';
}
$output .= $addCss1;

$extra_row_width_class1 = '';
$extra_row_width_class2 = '';
$extracss = '';
$img_id = preg_replace('/[^\d]/', '', $image);
//$img1 = wpb_getImageBySizeSRC(array( 'attach_id' => $img_id, 'thumb_size' => 'full' ));
$img = wp_get_attachment_image_src($img_id, 'full');
if (( $img != NULL )||($bgcolor != '')) {
	//$output .= $img['thumbnail'];
	$extra_row_width_class1 = ' parallax  parallax-section  ';
	$extra_row_width_class2 = ' parallax-section-inside '.$addCssClass1.' ';
	$extracss = ' style = "';
	if ( $bgcolor != '' ){
		$extracss .= ' background-color: '.$bgcolor.'; ';
	}
	if ( $img != NULL ){
		$extracss .= ' background-image: url(\''.$img[0].'\'); ';
	}
	$extracss .= ' " ';
}




if (($bg_container_remove == 'yes')||($extra_row_width_class1 != '')) { 
	$output .= '</div>';
}

if ($extra_row_width_class1 != '') {
	$output .= '<div class="'.$css_class.''.$extra_row_width_class1.' '.$addCssClass.' " '.$extracss.' >';
	$addCssClass = '';
}

$output .= '<div class="inside-section'.$extra_row_width_class2.''.$bg_pattern.'">';

$output .= '<div class="'.$css_class.''.$extra_row_width_class.' '.$addCssClass.' ">';
$output .= wpb_js_remove_wpautop($content);
if ($extra_row_width_class1 != '') {
	$output .= '</div>';
}
$output .= '</div>';
$output .= '</div>';


if (($bg_container_remove == 'yes')||($extra_row_width_class1 != '')) { 
	$output .= '<div class="main-no-sidebar">';
}

echo $output;