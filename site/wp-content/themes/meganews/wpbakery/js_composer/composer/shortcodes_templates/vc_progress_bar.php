<?php
$output = $title = $values = $units = $bgcolor = $custombgcolor = $options = $el_class = '';
extract( shortcode_atts( array(
    'title' => '',
    'values' => '',
    'units' => '',
    'height' => '',
    'font_size' => '',
    'bgcolor' => 'bar_grey',
    'custombgcolor' => '',
    'options' => '',
    'hide_track' => '',
    'css_animation' => '',
    'el_class' => ''
), $atts));


$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_progress_bar wpb_content_element '.$el_class, $this->settings['base']);
$css_class .= $this->getCSSAnimation($css_animation);

wp_enqueue_script( 'waypoints' );

$trackClass = '';
if ($hide_track == "hide") {
	$trackClass = ' vc_single_bar_no_track'; 
}
$bar_options = '';
$options = explode(",", $options);
if (in_array("animated", $options)) $bar_options .= " animated";
if (in_array("striped", $options)) $bar_options .= " striped";

if ($bgcolor=="custom" && $custombgcolor!='') { $custombgcolor = ' style="background-color: '.$custombgcolor.';"'; $bgcolor=""; }
if ($bgcolor!="") $bgcolor = " ".$bgcolor;

$output = '<div class="'.$css_class.'">';
//$output .= wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_progress_bar_heading'));

$graph_lines = explode(",", $values);

$id = rand(1, 10000);

if (($height != '')||($font_size != '')) {
	$output .= '<style> ';
	if ($height != '') {
		$output .= ' .progressbar'.$id.', .progressbar'.$id.' .vc_label_units { height: '.$height.'px !important;  } ';
		$output .= ' .progressbar'.$id.' .vc_label, .progressbar'.$id.' .vc_label_units { line-height: '.$height.'px !important;  } ';
	}
	if ($font_size != '') {
		$output .= ' .progressbar'.$id.' .vc_label, .progressbar'.$id.' span.vc_label_units { font-size: '.$font_size.'px !important;  } ';
	}	
	$output .= '</style>';
}

foreach ($graph_lines as $line) {
    $single_val = explode("|", $line);

    $unit = ($units!='') ? ' <span class="vc_label_units">' . $single_val[0] . $units . '</span>' : '';
    $custom_single_bgcolor = (isset($single_val[2])) ? ' style="background-color: '.$single_val[2].';"' : $custombgcolor;

    $output .= '<div class="vc_single_bar'.$bgcolor.' progressbar'.$id.' '.$trackClass.'">';
    $output .= '<small class="vc_label">'. $single_val[1] .'</small>';
    $output .= '<span class="vc_bar'.$bar_options.'" data-value="'.$single_val[0].'"'.$custom_single_bgcolor.'>'. $unit .'</span>';
    $output .= '</div>';
}

$output .= '</div>';

echo $output . $this->endBlockComment('progress_bar') . "\n";