<?php
$output = $title = $title_align = $el_class = $title_heading = $separator_type = '';
extract(shortcode_atts(array(
    'title' => __("Title", "meganews"),
    'title_align' => 'separator_align_center',
    'title_heading' => '',
    'separator_type' => 'none',
    'margin_top' => '',
    'el_class' => ''
), $atts));
$el_class = $this->getExtraClass($el_class);

$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_text_separator wpb_content_element '.$title_align.$el_class, $this->settings['base']);

$css='';
if ($title_heading == 'sidebar_title') {
	$output .= '<h3 class="sidebar-title">'.$title.'</h3><div class="title_stripes_sidebar"></div><div class="clear"></div>';
}
else {
	if ($margin_top != '') {
		$css = ' style = "margin-top:'.$margin_top.'px;" ';
	}
	if ($title_heading != 'none') { 
		$output .= '<div class="'.$css_class.' '.$separator_type.'"'.$css.'><div><'.$title_heading.' class="element_titles">'.$title.'</'.$title_heading.'><div class="title-stripes-'.$title_align.'"></div></div></div>'.$this->endBlockComment('separator')."\n";
	} else
	{
		$output .= '<div class="'.$css_class.' '.$separator_type.'"'.$css.'><div><div class="no_heading element_titles">'.$title.'</div></div></div>'.$this->endBlockComment('separator')."\n";
	}
}


echo $output;