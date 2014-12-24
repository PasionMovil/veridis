<?php
global $pego_prefix;

$output = '';
extract(shortcode_atts(array(
    'color' => '',
    'track_color' => '',
    'value' => '',
    'type' => '',
    'icon' => '',
    'icon_color' => '',
    'icon_size' => '',
    'chart_width' => '',
    'line_width' => '',
    'description_color' => '',
    'description_size' => '',
    'description' => '',
	'percent_color' => '',
    'percent_size' => '',
    'css_animation' => '',
    'el_class' => ''
), $atts));


$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_circle_chart wpb_content_element '.$el_class, $this->settings['base']);
$css_class .= $this->getCSSAnimation($css_animation);

$id = rand(1, 10000);




if ($color == '') {
	$color = get_option($pego_prefix.'main_template_color');   
 } 

 $output = '<div class="'.$css_class.'">';	
	$output .= '<style> .chart'.$id.' { width:'.$chart_width.'px; height:'.$chart_width.'px; line-height:'.$chart_width.'px; } .chart'.$id.' .chart-percent, .chart'.$id.' .chart-description { line-height:'.$chart_width.'px; }   </style>';	
	$output .= '<div data-percent="'.$value.'" data-barsize="'.$chart_width.'" data-linewidth="'.$line_width.'" data-trackcolor="'.$track_color.'" data-barcolor="'.$color.'" class="easyPieChart chart'.$id.'">';
	if($type == 'percent' ) {
		$percent_style = '';
		if (($percent_color != '')||($percent_size != '')) {
			$percent_style .= ' style= " ';
			if ($percent_color != '') {
				$percent_style .= ' color: '.$percent_color.'; ';
			}
			if ($percent_size != '') {
				$percent_style .= ' font-size: '.$percent_size.'px; ';
			}
			$percent_style .= ' " ';
		}
		$output .= '<div class="chart-percent"'.$percent_style.'><span'.$percent_style.'>'.$value.'</span>%</div>';
	}	
	if($type == 'icon' ) {
		$icon_style = '';
		if (($icon_color != '')||($icon_size != '')) {
			$icon_style .= ' style= " ';
			if ($icon_color != '') {
				$icon_style .= ' color: '.$icon_color.'; ';
			}
			if ($icon_size != '') {
				$icon_style .= ' font-size: '.$icon_size.'px; ';
			}
			$icon_style .= ' " ';
		}
		$output .= '<div class="chart-icon chart'.$id.' icon-'.$icon.'"'.$icon_style.'></div>';
	}
	if($type == 'description' ) {
		$description_style = '';
		if (($description_color != '')||($description_size != '')) {
			$description_style .= ' style= " ';
			if ($description_color != '') {
				$description_style .= ' color: '.$description_color.'; ';
			}
			if ($description_size != '') {
				$description_style .= ' font-size: '.$description_size.'px; ';
			}
			$description_style .= ' " ';
		}
		$output .= '<div class="chart-description"'.$description_style.'>'.$description.'</div>';
	}
	$output .= '</div>';
	$output .= wpb_js_remove_wpautop($content);	
$output .= '</div>';
echo $output;
?>