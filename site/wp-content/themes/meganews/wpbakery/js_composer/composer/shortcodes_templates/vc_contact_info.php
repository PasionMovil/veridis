<?php
$output = $url = '';
extract(shortcode_atts(array(   
    'icon' => '',  
    'css_animation' => '',
    'el_class' => ''
), $atts));


$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_contact_info wpb_content_element '.$el_class, $this->settings['base']);
$css_class .= $this->getCSSAnimation($css_animation);

$output = '<div class="'.$css_class.'">';
	$output .= '<div class="contact-info">';	
		$output .= '<div class="contact-info-icon">';	
			$output .= '<span class="iconClass-contact-info icon-'.$icon.'"></span>';
		$output .= '</div>';
		$output .= '<div class="contact-info-content">';	
			$output .= '<p>'.$content.'</p>';
		$output .= '<div class="clear"></div></div>';
		$output .= '<div class="clear"></div>';
	$output .= '</div>';
$output .= '</div>';
	

echo $output;
?>