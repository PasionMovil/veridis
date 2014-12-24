<?php
$output = $title = $link = $size = $zoom = $type = $bubble = $el_class = '';
extract(shortcode_atts(array(
    'title' => ''
), $atts));

	global $post;
	$folio_id = $post->ID;
	
	$post_type = get_post_type($folio_id);


$el_class = $this->getExtraClass($el_class);

$css_class =  apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_single_item wpb_content_element'.$el_class, $this->settings['base']);
$output .= "\n\t".'<div class="'.$css_class.'">';
	
	
	if ($post_type == 'portfolio') { 		
		$portfolioType = get_post_meta($folio_id, 'portfolio_type_selected' , true); 
		$single_portfolio_type = get_post_meta($folio_id, 'single_portfolio_type' , true); 
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $folio_id ), 'single-post-thumbnail' );	
		$permalink = get_permalink( $folio_id );
		$title = get_the_title( $folio_id); 
		$portf_slug = $post->post_name;		
		if ($portfolioType == 'Image') {
			$output .= '<img  src="'.$image[0].'" alt="" />';
		}
		
		if ($portfolioType == 'Slideshow') {
			$attachments = get_children(array('post_parent' => $folio_id,
						'post_status' => 'inherit',
						'post_type' => 'attachment',
						'post_mime_type' => 'image',
						'order' => 'ASC',
						'orderby' => 'menu_order ID'));	
			$output .= '<div class="flexsliderFolio">';
				$output .= '<ul class="slides">';
					foreach($attachments as $att_id => $attachment) {
						$full_img_url = wp_get_attachment_url($attachment->ID); 
						$output .= '<li><img class="folio-featured" src="'.$full_img_url.'" alt=""></li>';
					}
				$output .= '</ul>';
			$output .= '</div>';
		}
		if ($portfolioType == 'Video') {			
			global $wp_embed;
			$linkVideo = get_post_meta($folio_id , 'portfolio_video_url_gal' , true);
			$embed = $wp_embed->run_shortcode('[embed width="1080"]'.$linkVideo.'[/embed]');
			$output .= '<div class="video-wrapper"><div class="video-container">'.$embed.'</div></div>';	
			
		}	
	}
	if ($post_type == 'post') { 		
		$post_id = $folio_id;
		$format = get_post_format( $post_id );	
		if ($format == 'image') {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'single-post-thumbnail' );
			$output .= '<img src="'.$image[0].'" alt="" />';
		} 
		if ($format == 'quote') { 	
			$quote = get_post_meta($post_id , 'post_quote' , true);
			$output .= '<div class="post-quote">'.$quote.'</div>';	
		}  
		if ($format == 'gallery') { 			
			$quote = get_post_meta($post_id , 'post_quote' , true);	
			$output .= '<div class="flexsliderFolio">';	
				$output .= '<ul class="slides">';
				$attachments = get_children(array('post_parent' => $post_id,
												'post_status' => 'inherit',
												'post_type' => 'attachment',
												'post_mime_type' => 'image',
												'order' => 'ASC',
												'orderby' => 'menu_order ID'));	
				foreach($attachments as $att_id => $attachment) {
					$full_img_url = wp_get_attachment_image_src($attachment->ID,'full', true);
					$output .= '<li><img src="'.$full_img_url[0].'" alt=""></li>';
				}
			
				$output .= '</ul>';	
			$output .= '</div>';	
		} 
		if ($format == 'video') { 				
				global $wp_embed;
				$linkVideo = get_post_meta($post_id , 'post_video_url' , true);
				$embed = $wp_embed->run_shortcode('[embed width="1080"]'.$linkVideo.'[/embed]');
				$output .= '<div class="video-wrapper"><div class="video-container">'.$embed.'</div></div>';	
			} else			
			if ($format == 'audio') { 			
				$post_audio = get_post_meta($post_id , 'post_audio' , true);									
				$output .= do_shortcode($post_audio);	
			}
	}
$output .= "\n\t".'</div> '.$this->endBlockComment('.wpb_gmaps_widget');

echo $output;