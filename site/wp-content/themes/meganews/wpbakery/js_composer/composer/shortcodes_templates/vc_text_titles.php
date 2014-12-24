<?php
$output = $title = $title_align = $el_class = $title_heading = $separator_type = '';
extract(shortcode_atts(array(
    'title' => __("Title", "meganews"),
    'title_type' => 'h1',
    'page_title_type' => '',
    'page_title_text_heading' => '',
    'title_align' => 'left',
    'css_animation' => '',
    'el_class' => ''
), $atts));

$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_text_titles wpb_content_element title_align_'.$title_align.' '.$el_class, $this->settings['base']);
$css_class .= $this->getCSSAnimation($css_animation);

$output = '<div class="'.$css_class.'">';
	$output .= '<'.$title_type.'>'.$title.'</'.$title_type.'><div class="title-stripes-'.$title_align.'"></div>';
$output .= '</div>';

echo $output;