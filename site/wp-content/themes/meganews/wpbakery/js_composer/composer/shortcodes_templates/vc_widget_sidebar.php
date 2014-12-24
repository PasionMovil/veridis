<?php
$output = $el_position = $title = $width = $el_class = $sidebar_id = '';
extract(shortcode_atts(array(
    'el_position' => '',
    'title' => '',
    'sidebar_border' => '',
    'width' => '1/1',
    'el_class' => '',
    'sidebar_id' => ''
), $atts));
if ( $sidebar_id == '' ) return null;

$el_class = $this->getExtraClass($el_class);

ob_start();
dynamic_sidebar($sidebar_id);
$sidebar_value = ob_get_contents();
ob_end_clean();

$sidebar_value = trim($sidebar_value);
$sidebar_value = (substr($sidebar_value, 0, 3) == '<li' ) ? '<ul>'.$sidebar_value.'</ul>' : $sidebar_value;
//

$extraClass = '';
if ($sidebar_border == 'left') {
	$extraClass = ' sidebar-left-border ';
}

if ($sidebar_border == 'right') {
	$extraClass = ' sidebar-right-border ';
}



$css_class =  apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_widgetised_column wpb_content_element'.$extraClass.$el_class, $this->settings['base']);
$output .= "\n\t".'<div class="'.$css_class.'">';
$output .= "\n\t\t".'<div class="wpb_wrapper">';
$output .= wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_widgetised_column_heading'));
$output .= "\n\t\t\t".$sidebar_value;
$output .= "\n\t\t".'</div> '.$this->endBlockComment('.wpb_wrapper');
$output .= "\n\t".'</div> '.$this->endBlockComment('.wpb_widgetised_column');

echo $output;