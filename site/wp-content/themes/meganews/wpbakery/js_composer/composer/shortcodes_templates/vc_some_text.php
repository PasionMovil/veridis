<?php
$output = $color = $el_class = $css_animation = $url_value = $url_target = $icon_color = $icon_bgcolor = $icon_css = $iconwrapper_css = $title_color = $title_hovercolor = $title_css = '';
extract(shortcode_atts(array(
    'title' => '',
    'button_caption' => '',
    'url' => '',
    'url_target' => '',
    'css_animation' => '',
    'el_class' => ''
), $atts));


$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_some_text wpb_content_element '.$el_class, $this->settings['base']);
$css_class .= $this->getCSSAnimation($css_animation);


$output = '<div class="'.$css_class.'">';
	$output .= '<div class="some-text">';
		$output .= '<h1>'.$title.'</h1>';
		$output .= wpb_js_remove_wpautop($content);
		if (($button_caption != '')&&($url != '')) {
			$output .= '<a href="'.$url.'" target="'.$url_target.'" title="'.$button_caption.'">'.$button_caption.'</a>';
		}
	$output .= '</div>';
$output .= '</div>';

echo $output;
?>