<?php
$output = $separator_type = '';
extract(shortcode_atts(array(
    'separator_type' => 'type1',
    'el_class' => ''
), $atts));
$el_class = $this->getExtraClass($el_class);

$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_separator wpb_content_element ', $this->settings['base']);

$output .= '<div class="'.$css_class.' '.$separator_type.'"></div>'.$this->endBlockComment('separator')."\n";

echo $output;