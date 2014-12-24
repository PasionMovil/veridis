<?php
$output = $color = $el_class = $css_animation = $url_value = $url_target = $icon_color = $icon_bgcolor = $icon_css = $iconwrapper_css = $title_color = $title_hovercolor = $title_css = '';
extract(shortcode_atts(array(
    'number_of_post' => '',
    'css_animation' => '',
    'el_class' => ''
), $atts));


$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_counter wpb_content_element '.$el_class, $this->settings['base']);
$css_class .= $this->getCSSAnimation($css_animation);



$output = '<div class="'.$css_class.'">';
$postNumber = 10;
if ($number_of_post != '') {
	$postNumber = $number_of_post;
}
$argsPosts= array('post_type'=> 'post', 'posts_per_page' => $number_of_post, 'order'=> 'DESC', 'orderby' => 'post_date'  );
$allPosts = get_posts($argsPosts);
		
if($allPosts) {
	$output .= '<ul class="news-ticker">';
	foreach($allPosts as $singlePost)
	{ 	
		$output .= '<li><span>'.get_the_time('Y/m/d g:i A', $singlePost->ID ).'</span><a title="'.esc_attr($singlePost->post_title).'" href="'.get_permalink( $singlePost->ID ).'">'.esc_html($singlePost->post_title).'</a></li>';
	}
	$output .= '</ul>';
}
$output .= '</div>';

echo $output;
?>