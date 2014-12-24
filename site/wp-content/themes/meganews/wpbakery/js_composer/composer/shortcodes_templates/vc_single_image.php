<?php

$output = $el_class = $image = $img_size = $img_link = $img_link_target = $img_link_large = $title = $css_animation = '';

extract(shortcode_atts(array(
    'title' => '',
    'image' => $image,
    'img_size'  => 'full',
    'img_link_large' => false,
    'img_link' => '',
    'img_link_target' => '_self',
    'el_class' => '',
    'margin_top' => '',
    'margin_bottom' => '',
    'css_animation' => '',
    'el_class' => ''
), $atts));

$id = rand(1, 10000);

$img_id = preg_replace('/[^\d]/', '', $image);
$img = wpb_getImageBySize(array( 'attach_id' => $img_id, 'thumb_size' => $img_size ));
if ( $img == NULL ) $img['thumbnail'] = '<img src="http://placekitten.com/g/400/300" /> <small>'.__('This is image placeholder, edit your page to replace it.', 'meganews').'</small>';

$el_class = $this->getExtraClass($el_class);

$el_class = $title;

$a_class = '';
/*
if ( $el_class != '' ) {
    $tmp_class = explode(" ", strtolower($el_class));
    $tmp_class = str_replace(".", "", $tmp_class);
    if ( in_array("prettyphoto", $tmp_class) ) {
        wp_enqueue_script( 'prettyphoto' );
        wp_enqueue_style( 'prettyphoto' );
        $a_class = ' class="prettyphoto"';
        $el_class = str_ireplace(" prettyphoto", "", $el_class);
    }
}
*/

$link_to = '';
if ($img_link_large==true) {
    $link_to = wp_get_attachment_image_src( $img_id, 'large');
    $link_to = $link_to[0];
	   wp_enqueue_script( 'prettyphoto' );
        wp_enqueue_style( 'prettyphoto' );
        $a_class = ' class="prettyphoto"';
        $el_class = str_ireplace(" prettyphoto", "", $el_class);
}
else if (!empty($img_link)) {
    $link_to = $img_link;
}
$image_string = !empty($link_to) ? '<a'.$a_class.' href="'.$link_to.'"'.($img_link_target!='_self' ? ' target="'.$img_link_target.'"' : '').'>'.$img['thumbnail'].'</a>' : $img['thumbnail'];

if (($margin_bottom != '')||($margin_top != '')) {
	$output .= '<style>';
		$output .= '.image'.$id.' img { ';
		if ($margin_bottom != '') {
			$output .= 'margin-bottom: '.$margin_bottom.'px; ';
		}
		if ($margin_top != '') {
			$output .= 'margin-top '.$margin_top.'px; ';
		}
		$output .= ' } ';
	$output .= '</style>';
}

$css_class =  apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_single_image wpb_content_element image'.$id.''.$el_class, $this->settings['base']);
$css_class .= $this->getCSSAnimation($css_animation);

$output .= "\n\t".'<div class="'.$css_class.'">';
$output .= "\n\t\t".'<div class="wpb_wrapper">';
$output .= "\n\t\t\t".wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_singleimage_heading'));
$output .= "\n\t\t\t".$image_string;
$output .= "\n\t\t".'</div> '.$this->endBlockComment('.wpb_wrapper');
$output .= "\n\t".'</div> '.$this->endBlockComment('.wpb_single_image');

echo $output;