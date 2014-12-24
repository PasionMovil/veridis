<?php
$output = '';
extract(shortcode_atts(array(
    'values' => '',
    'css_animation' => '',
    'el_class' => ''
), $atts));


$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_services wpb_content_element '.$el_class, $this->settings['base']);
$css_class .= $this->getCSSAnimation($css_animation);

$unique_id = rand(1, 10000);
$single_services_input = explode(",", $values);




$output = '<div id="multiple-services-'.$unique_id.'" class="'.$css_class.'">';
$output .= '<div class="services-loading"></div>';
$output .= '<div id="single-services-'.$unique_id.'" class="single-services-item-box"></div>';
$output .= '<div class="close-service"><a href="#" class="close-single-service single-close-button-'.$unique_id.'" onclick="return backToServices(\'single-services-'.$unique_id.'\', \'single-close-button-'.$unique_id.'\');" >Close</a></div>';

$output .= '<div class="'.$css_class.'">';

$allServices = pego_get_all_services();
$addClass='';
if ($single_services_input) {
	$count = count($single_services_input);

	$output .= '<ul class="pego_services">';
	foreach ($single_services_input as $single_service) { 

			$id = array_search($single_service, $allServices);
			$service_content =  get_post_field('post_content', $id); 
			$service_title =  get_the_title($id); 
			$link = get_permalink( $id );
			
			$output .= '<li class="services_single"><a onclick="return showServicesItem(\''.$link.'\', \'multiple-services-'.$unique_id.'\', \'single-services-'.$unique_id.'\', \'single-close-button-'.$unique_id.'\');" href="#" title="'.$service_title.'">'.$service_title.'</a></li>';
	}
	$output .= '</ul>';
	$output .= '<div class="clear"></div>';
	
}
$output .= '</div>';
$output .= '</div>';


echo $output;
?>