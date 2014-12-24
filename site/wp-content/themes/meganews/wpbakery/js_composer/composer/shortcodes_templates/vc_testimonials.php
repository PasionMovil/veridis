<?php
$output = '';
extract(shortcode_atts(array(
    'values' => '',
    'testimonials_type' => '',
    'css_animation' => '',
    'el_class' => ''
), $atts));


$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_testimonials wpb_content_element '.$el_class, $this->settings['base']);
$css_class .= $this->getCSSAnimation($css_animation);
$id = rand(1, 10000);
$columns = explode(",", $values);


$output = '<div class="'.$css_class.'">';

$allTestss = pego_get_all_test();
$addClass='';
if ($columns) {
	$count = count($columns);

	$output .= '<div style="width: 80%; margin: 0 auto;">';
		$output .= '<div class="cbp-qtrotator">';
		foreach ($columns as $single_column) { 
			$id = array_search($single_column, $allTestss);
			$test_name = get_post_meta($id,'test_name', true);	
			$test_image = get_post_meta($id,'test_image', true);	
			$test_content =  get_post_field('post_content', $id); 			
			
					
			$output .= '<div class="cbp-qtcontent">';	
				if(!empty($test_image)) {
					$output .=' <img src="'.$test_image.'" alt="'.$test_name.'" />';				
				}		
				$output .= '<blockquote>';
					$output .= '<p>'. $test_content .'</p>';
					if(!empty($test_name)) {
							$output .=' <div class="testimonailsauthor">- '.$test_name.'</div>';	
					}
				$output .= '</blockquote>';
			$output .= '</div>';	
		}	
		$output .= '</div>';		
	$output .= '</div>';	
	}
	

$output .= '</div>';
echo $output;
?>