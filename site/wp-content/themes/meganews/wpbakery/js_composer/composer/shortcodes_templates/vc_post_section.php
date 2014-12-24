<?php
$output = '';
extract(shortcode_atts(array(
    'post_section_type' => '',
	'postposition1' => '',
	'postposition2' => '',
	'postposition3' => '',
	'postposition4' => '',
	'postposition5' => '',
	'postposition6' => '',
	'postposition7' => '',
	'postposition8' => '',
	'postfill' => 'latest',
	'grid_teasers_offset' => '',
	'cat' => '',
	'css_animation' => '',
	'type6_index' => ''
), $atts));

$el_class= '';

$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_post_section wpb_content_element '.$el_class, $this->settings['base']);
$css_class .= $this->getCSSAnimation($css_animation);


$postsNeeded = array();
$offset_number = 0;
$numberOfItemsToRead = 8;
if ($grid_teasers_offset != '') {
	$offset_number = $grid_teasers_offset - 1;
	$numberOfItemsToRead = $numberOfItemsToRead + $grid_teasers_offset + 1;
}


if ($postfill == 'latest') {
	if ($cat != '') {
		$argsPosts = array('post_type'=> 'post', 'posts_per_page' => 8, 'order'=> 'DESC', 'orderby' => 'post_date',  'cat' => $cat );
	} else  {
		$argsPosts = array('post_type'=> 'post', 'offset' => $offset_number,  'posts_per_page' => $numberOfItemsToRead, 'order'=> 'DESC', 'orderby' => 'post_date'  );
	}
	$postsPosts= get_posts($argsPosts);	
	$counter= 0;
	foreach($postsPosts as $postsPost)
	{ 
			
			$postsNeeded[$counter]['id'] = $postsPost->ID;
			$postsNeeded[$counter]['format'] = get_post_format( $postsPost->ID );	
			$postsNeeded[$counter]['title'] = $postsPost->post_title;
			$postsNeeded[$counter]['permalink'] = get_permalink($postsPost->ID);
			$postsNeeded[$counter]['average_rate'] = get_post_average_review($postsPost->ID);
			$postsNeeded[$counter]['PostSection41'] = wp_get_attachment_image( get_post_thumbnail_id( $postsPost->ID ), 'PostSection41' ); 
			$postsNeeded[$counter]['PostSection31'] = wp_get_attachment_image( get_post_thumbnail_id( $postsPost->ID ), 'PostSection31' ); 
			$postsNeeded[$counter]['PostSection21'] = wp_get_attachment_image( get_post_thumbnail_id( $postsPost->ID ), 'PostSection21' ); 
			$postsNeeded[$counter]['PostSection22'] = wp_get_attachment_image( get_post_thumbnail_id( $postsPost->ID ), 'PostSection22' ); 
			$postsNeeded[$counter]['PostSection11'] = wp_get_attachment_image( get_post_thumbnail_id( $postsPost->ID ), 'PostSection11' ); 
			$postsNeeded[$counter]['PostSection12'] = wp_get_attachment_image( get_post_thumbnail_id( $postsPost->ID ), 'PostSection12' ); 
			
			$postsNeeded[$counter]['post_alternative_cat_tag'] = get_post_meta($postsPost->ID,'post_alternative_cat_tag', true);
			$postsNeeded[$counter]['post_tag_new'] = get_post_meta($postsPost->ID,'post_tag_new', true);
			$post_categories = wp_get_post_categories( $postsPost->ID );
			if ($post_categories) {
				$cats = array();
				foreach($post_categories as $c){
					$cat = get_category( $c );
					$cats[] = array( 'id' => $cat->cat_ID, 'name' => $cat->name );
						if ($cat->category_parent == 0) { 
 						} 
 						else {
							$cats[0] = array( 'id' => $cat->cat_ID, 'name' => $cat->name );
						} 
				}
				$postsNeeded[$counter]['cat_id'] = $cats[0]['id'];		
				$postsNeeded[$counter]['cat_name'] = $cats[0]['name'];	
				$postsNeeded[$counter]['cat_link'] =  get_category_link($cats[0]['id']);
			}
			else {
				$postsNeeded[$counter]['cat_id'] = '';		
				$postsNeeded[$counter]['cat_name'] = '';	
				$postsNeeded[$counter]['cat_link'] =  '';
			}			
			
			$counter++;
	}
} else {
	$counter= 0;
	$post_selected =  pego_get_post_by_title($postposition1, $output1 = OBJECT);
	$postsNeeded[$counter]['id'] = $post_selected->ID;
	$postsNeeded[$counter]['title'] = $post_selected->post_title;
	$postsNeeded[$counter]['format'] = get_post_format( $post_selected->ID );
	$postsNeeded[$counter]['permalink'] = get_permalink($post_selected->ID);
	$postsNeeded[$counter]['average_rate'] = get_post_average_review($post_selected->ID);
	$postsNeeded[$counter]['PostSection41'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection41' ); 
	$postsNeeded[$counter]['PostSection31'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection31' ); 
	$postsNeeded[$counter]['PostSection21'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection21' ); 
	$postsNeeded[$counter]['PostSection22'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection22' ); 
	$postsNeeded[$counter]['PostSection11'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection11' ); 
	$postsNeeded[$counter]['PostSection12'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection12' ); 
	$postsNeeded[$counter]['post_alternative_cat_tag'] = get_post_meta($post_selected->ID,'post_alternative_cat_tag', true);
	$postsNeeded[$counter]['post_tag_new'] = get_post_meta($post_selected->ID,'post_tag_new', true);
	$post_categories = wp_get_post_categories( $post_selected->ID );
	if ($post_categories) {
		$cats = array();
		foreach($post_categories as $c){
			$cat = get_category( $c );
			$cats[] = array( 'id' => $cat->cat_ID, 'name' => $cat->name );
			if ($cat->category_parent == 0) { 
 			} 
 			else {
				$cats[0] = array( 'id' => $cat->cat_ID, 'name' => $cat->name );
			}
		}
		$postsNeeded[$counter]['cat_id'] = $cats[0]['id'];		
		$postsNeeded[$counter]['cat_name'] = $cats[0]['name'];	
		$postsNeeded[$counter]['cat_link'] =  get_category_link($cats[0]['id']);
	}
	else {
		$postsNeeded[$counter]['cat_id'] = '';		
		$postsNeeded[$counter]['cat_name'] = '';	
		$postsNeeded[$counter]['cat_link'] =  '';
	}		
	$counter++;
	
	$post_selected =  pego_get_post_by_title($postposition2, $output1 = OBJECT);
	$postsNeeded[$counter]['id'] = $post_selected->ID;
	$postsNeeded[$counter]['title'] = $post_selected->post_title;
	$postsNeeded[$counter]['format'] = get_post_format( $post_selected->ID );
	$postsNeeded[$counter]['permalink'] = get_permalink($post_selected->ID);
	$postsNeeded[$counter]['average_rate'] = get_post_average_review($post_selected->ID);
	$postsNeeded[$counter]['PostSection41'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection41' ); 
	$postsNeeded[$counter]['PostSection31'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection31' ); 
	$postsNeeded[$counter]['PostSection21'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection21' ); 
	$postsNeeded[$counter]['PostSection22'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection22' ); 
	$postsNeeded[$counter]['PostSection11'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection11' ); 
	$postsNeeded[$counter]['PostSection12'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection12' ); 
	
	$postsNeeded[$counter]['post_alternative_cat_tag'] = get_post_meta($post_selected->ID,'post_alternative_cat_tag', true);
	$postsNeeded[$counter]['post_tag_new'] = get_post_meta($post_selected->ID,'post_tag_new', true);
	
	$post_categories = wp_get_post_categories( $post_selected->ID );
	if ($post_categories) {
		$cats = array();
		foreach($post_categories as $c){
			$cat = get_category( $c );
			$cats[] = array( 'id' => $cat->cat_ID, 'name' => $cat->name );
			if ($cat->category_parent == 0) { 
 			} 
 			else {
				$cats[0] = array( 'id' => $cat->cat_ID, 'name' => $cat->name );
			}
		}
		$postsNeeded[$counter]['cat_id'] = $cats[0]['id'];		
		$postsNeeded[$counter]['cat_name'] = $cats[0]['name'];	
		$postsNeeded[$counter]['cat_link'] =  get_category_link($cats[0]['id']);
	}
	else {
		$postsNeeded[$counter]['cat_id'] = '';		
		$postsNeeded[$counter]['cat_name'] = '';	
		$postsNeeded[$counter]['cat_link'] =  '';
	}			
	$counter++;
	
	$post_selected =  pego_get_post_by_title($postposition3, $output1 = OBJECT);
	$postsNeeded[$counter]['id'] = $post_selected->ID;
	$postsNeeded[$counter]['title'] = $post_selected->post_title;
	$postsNeeded[$counter]['format'] = get_post_format( $post_selected->ID );
	$postsNeeded[$counter]['permalink'] = get_permalink($post_selected->ID);
	$postsNeeded[$counter]['average_rate'] = get_post_average_review($post_selected->ID);
	$postsNeeded[$counter]['PostSection41'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection41' ); 
	$postsNeeded[$counter]['PostSection31'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection31' ); 
	$postsNeeded[$counter]['PostSection21'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection21' ); 
	$postsNeeded[$counter]['PostSection22'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection22' ); 
	$postsNeeded[$counter]['PostSection11'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection11' ); 
	$postsNeeded[$counter]['PostSection12'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection12' ); 	
	
	$postsNeeded[$counter]['post_alternative_cat_tag'] = get_post_meta($post_selected->ID,'post_alternative_cat_tag', true);
	$postsNeeded[$counter]['post_tag_new'] = get_post_meta($post_selected->ID,'post_tag_new', true);	
	$post_categories = wp_get_post_categories( $post_selected->ID );
	if ($post_categories) {
		$cats = array();
		foreach($post_categories as $c){
			$cat = get_category( $c );
			$cats[] = array( 'id' => $cat->cat_ID, 'name' => $cat->name );
			if ($cat->category_parent == 0) { 
 			} 
 			else {
				$cats[0] = array( 'id' => $cat->cat_ID, 'name' => $cat->name );
			}
		}
		$postsNeeded[$counter]['cat_id'] = $cats[0]['id'];		
		$postsNeeded[$counter]['cat_name'] = $cats[0]['name'];	
		$postsNeeded[$counter]['cat_link'] =  get_category_link($cats[0]['id']);
	}
	else {
		$postsNeeded[$counter]['cat_id'] = '';		
		$postsNeeded[$counter]['cat_name'] = '';	
		$postsNeeded[$counter]['cat_link'] =  '';
	}		
	$counter++;
	
	$post_selected =  pego_get_post_by_title($postposition4, $output1 = OBJECT);
	$postsNeeded[$counter]['id'] = $post_selected->ID;
	$postsNeeded[$counter]['title'] = $post_selected->post_title;
	$postsNeeded[$counter]['format'] = get_post_format( $post_selected->ID );
	$postsNeeded[$counter]['permalink'] = get_permalink($post_selected->ID);
	$postsNeeded[$counter]['average_rate'] = get_post_average_review($post_selected->ID);
	$postsNeeded[$counter]['PostSection41'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection41' ); 
	$postsNeeded[$counter]['PostSection31'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection31' ); 
	$postsNeeded[$counter]['PostSection21'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection21' ); 
	$postsNeeded[$counter]['PostSection22'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection22' ); 
	$postsNeeded[$counter]['PostSection11'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection11' ); 
	$postsNeeded[$counter]['PostSection12'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection12' ); 	
	
	$postsNeeded[$counter]['post_alternative_cat_tag'] = get_post_meta($post_selected->ID,'post_alternative_cat_tag', true);
	$postsNeeded[$counter]['post_tag_new'] = get_post_meta($post_selected->ID,'post_tag_new', true);
	$post_categories = wp_get_post_categories( $post_selected->ID );
	if ($post_categories) {
		$cats = array();
		foreach($post_categories as $c){
			$cat = get_category( $c );
			$cats[] = array( 'id' => $cat->cat_ID, 'name' => $cat->name );
			if ($cat->category_parent == 0) { 
 			} 
 			else {
				$cats[0] = array( 'id' => $cat->cat_ID, 'name' => $cat->name );
			}
		}
		$postsNeeded[$counter]['cat_id'] = $cats[0]['id'];		
		$postsNeeded[$counter]['cat_name'] = $cats[0]['name'];	
		$postsNeeded[$counter]['cat_link'] =  get_category_link($cats[0]['id']);
	}
	else {
		$postsNeeded[$counter]['cat_id'] = '';		
		$postsNeeded[$counter]['cat_name'] = '';	
		$postsNeeded[$counter]['cat_link'] =  '';
	}		
	$counter++;
	
	$post_selected =  pego_get_post_by_title($postposition5, $output1 = OBJECT);
	$postsNeeded[$counter]['id'] = $post_selected->ID;
	$postsNeeded[$counter]['title'] = $post_selected->post_title;
	$postsNeeded[$counter]['format'] = get_post_format( $post_selected->ID );
	$postsNeeded[$counter]['permalink'] = get_permalink($post_selected->ID);
	$postsNeeded[$counter]['average_rate'] = get_post_average_review($post_selected->ID);
	$postsNeeded[$counter]['PostSection41'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection41' ); 
	$postsNeeded[$counter]['PostSection31'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection31' ); 
	$postsNeeded[$counter]['PostSection21'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection21' ); 
	$postsNeeded[$counter]['PostSection22'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection22' ); 
	$postsNeeded[$counter]['PostSection11'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection11' ); 
	$postsNeeded[$counter]['PostSection12'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection12' ); 
	
	$postsNeeded[$counter]['post_alternative_cat_tag'] = get_post_meta($post_selected->ID,'post_alternative_cat_tag', true);
	$postsNeeded[$counter]['post_tag_new'] = get_post_meta($post_selected->ID,'post_tag_new', true);
	$post_categories = wp_get_post_categories( $post_selected->ID );
	if ($post_categories) {
		$cats = array();
		foreach($post_categories as $c){
			$cat = get_category( $c );
			$cats[] = array( 'id' => $cat->cat_ID, 'name' => $cat->name );
			if ($cat->category_parent == 0) { 
 			} 
 			else {
				$cats[0] = array( 'id' => $cat->cat_ID, 'name' => $cat->name );
			}
		}
		$postsNeeded[$counter]['cat_id'] = $cats[0]['id'];		
		$postsNeeded[$counter]['cat_name'] = $cats[0]['name'];	
		$postsNeeded[$counter]['cat_link'] =  get_category_link($cats[0]['id']);
	}
	else {
		$postsNeeded[$counter]['cat_id'] = '';		
		$postsNeeded[$counter]['cat_name'] = '';	
		$postsNeeded[$counter]['cat_link'] =  '';
	}	
	$counter++;
	
	$post_selected =  pego_get_post_by_title($postposition6, $output1 = OBJECT);
	$postsNeeded[$counter]['id'] = $post_selected->ID;
	$postsNeeded[$counter]['title'] = $post_selected->post_title;
	$postsNeeded[$counter]['format'] = get_post_format( $post_selected->ID );
	$postsNeeded[$counter]['permalink'] = get_permalink($post_selected->ID);
	$postsNeeded[$counter]['average_rate'] = get_post_average_review($post_selected->ID);
	$postsNeeded[$counter]['PostSection41'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection41' ); 
	$postsNeeded[$counter]['PostSection31'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection31' ); 
	$postsNeeded[$counter]['PostSection21'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection21' ); 
	$postsNeeded[$counter]['PostSection22'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection22' ); 
	$postsNeeded[$counter]['PostSection11'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection11' ); 
	$postsNeeded[$counter]['PostSection12'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection12' ); 
	
	$postsNeeded[$counter]['post_alternative_cat_tag'] = get_post_meta($post_selected->ID,'post_alternative_cat_tag', true);
	$postsNeeded[$counter]['post_tag_new'] = get_post_meta($post_selected->ID,'post_tag_new', true);
	
	$post_categories = wp_get_post_categories( $post_selected->ID );
	if ($post_categories) {
		$cats = array();
		foreach($post_categories as $c){
			$cat = get_category( $c );
			$cats[] = array( 'id' => $cat->cat_ID, 'name' => $cat->name );
			if ($cat->category_parent == 0) { 
 			} 
 			else {
				$cats[0] = array( 'id' => $cat->cat_ID, 'name' => $cat->name );
			}
		}
		$postsNeeded[$counter]['cat_id'] = $cats[0]['id'];		
		$postsNeeded[$counter]['cat_name'] = $cats[0]['name'];	
		$postsNeeded[$counter]['cat_link'] =  get_category_link($cats[0]['id']);
	}
	else {
		$postsNeeded[$counter]['cat_id'] = '';		
		$postsNeeded[$counter]['cat_name'] = '';	
		$postsNeeded[$counter]['cat_link'] =  '';
	}		
	$counter++;
	
	$post_selected =  pego_get_post_by_title($postposition7, $output1 = OBJECT);
	$postsNeeded[$counter]['id'] = $post_selected->ID;
	$postsNeeded[$counter]['title'] = $post_selected->post_title;
	$postsNeeded[$counter]['format'] = get_post_format( $post_selected->ID );
	$postsNeeded[$counter]['permalink'] = get_permalink($post_selected->ID);
	$postsNeeded[$counter]['average_rate'] = get_post_average_review($post_selected->ID);
	$postsNeeded[$counter]['PostSection41'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection41' ); 
	$postsNeeded[$counter]['PostSection31'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection31' ); 
	$postsNeeded[$counter]['PostSection21'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection21' ); 
	$postsNeeded[$counter]['PostSection22'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection22' ); 
	$postsNeeded[$counter]['PostSection11'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection11' ); 
	$postsNeeded[$counter]['PostSection12'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection12' ); 
	
	$postsNeeded[$counter]['post_alternative_cat_tag'] = get_post_meta($post_selected->ID,'post_alternative_cat_tag', true);
	$postsNeeded[$counter]['post_tag_new'] = get_post_meta($post_selected->ID,'post_tag_new', true);
	
	$post_categories = wp_get_post_categories( $post_selected->ID );
	if ($post_categories) {
		$cats = array();
		foreach($post_categories as $c){
			$cat = get_category( $c );
			$cats[] = array( 'id' => $cat->cat_ID, 'name' => $cat->name );
			if ($cat->category_parent == 0) { 
 			} 
 			else {
				$cats[0] = array( 'id' => $cat->cat_ID, 'name' => $cat->name );
			}
		}
		$postsNeeded[$counter]['cat_id'] = $cats[0]['id'];		
		$postsNeeded[$counter]['cat_name'] = $cats[0]['name'];	
		$postsNeeded[$counter]['cat_link'] =  get_category_link($cats[0]['id']);
	}
	else {
		$postsNeeded[$counter]['cat_id'] = '';		
		$postsNeeded[$counter]['cat_name'] = '';	
		$postsNeeded[$counter]['cat_link'] =  '';
	}	
	$counter++;
	
	$post_selected =  pego_get_post_by_title($postposition8, $output1 = OBJECT);
	$postsNeeded[$counter]['id'] = $post_selected->ID;
	$postsNeeded[$counter]['title'] = $post_selected->post_title;
	$postsNeeded[$counter]['format'] = get_post_format( $post_selected->ID );
	$postsNeeded[$counter]['permalink'] = get_permalink($post_selected->ID);
	$postsNeeded[$counter]['average_rate'] = get_post_average_review($post_selected->ID);
	$postsNeeded[$counter]['PostSection41'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection41' ); 
	$postsNeeded[$counter]['PostSection31'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection31' ); 
	$postsNeeded[$counter]['PostSection21'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection21' ); 
	$postsNeeded[$counter]['PostSection22'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection22' ); 
	$postsNeeded[$counter]['PostSection11'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection11' ); 
	$postsNeeded[$counter]['PostSection12'] = wp_get_attachment_image( get_post_thumbnail_id( $post_selected->ID ), 'PostSection12' ); 
	
	$postsNeeded[$counter]['post_alternative_cat_tag'] = get_post_meta($post_selected->ID,'post_alternative_cat_tag', true);
	$postsNeeded[$counter]['post_tag_new'] = get_post_meta($post_selected->ID,'post_tag_new', true);
	
	$post_categories = wp_get_post_categories( $post_selected->ID );
	if ($post_categories) {
		$cats = array();
		foreach($post_categories as $c){
			$cat = get_category( $c );
			$cats[] = array( 'id' => $cat->cat_ID, 'name' => $cat->name );
			if ($cat->category_parent == 0) { 
 			} 
 			else {
				$cats[0] = array( 'id' => $cat->cat_ID, 'name' => $cat->name );
			}
		}
		$postsNeeded[$counter]['cat_id'] = $cats[0]['id'];		
		$postsNeeded[$counter]['cat_name'] = $cats[0]['name'];	
		$postsNeeded[$counter]['cat_link'] =  get_category_link($cats[0]['id']);
	}
	else {
		$postsNeeded[$counter]['cat_id'] = '';		
		$postsNeeded[$counter]['cat_name'] = '';	
		$postsNeeded[$counter]['cat_link'] =  '';
	}							
}


$output .= "\n\t".'<div class="'.$css_class.'">';

if ($post_section_type == 'type1') {

	$output .= '<div class="large_post_wrapper post_wrapp_width3 first">';

		
		$output .= '<a style="display: block; line-height: 0;" href="'.$postsNeeded[0]['permalink'].'" title="'.esc_attr($postsNeeded[0]['title']).'" >';
			$output .= $postsNeeded[0]['PostSection31'];
		$output .= '</a>';
		
		$output .= '<div class="post-tags">';
			if ($postsNeeded[0]['post_alternative_cat_tag'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[0]['cat_id'].'">';
				$output .= esc_html($postsNeeded[0]['post_alternative_cat_tag']);
				$output .= '</span>';
			}
			else {
				if ($postsNeeded[0]['cat_name'] != '') {
					$output .= '<a href="'.$postsNeeded[0]['cat_link'].'" title="'.esc_attr($postsNeeded[0]['cat_name']).'" class="post_cat_tag category-bg-color-'.$postsNeeded[0]['cat_id'].'">';
					$output .= esc_html($postsNeeded[0]['cat_name']);
					$output .= '</a>';
				}				
			}
			if ($postsNeeded[0]['post_tag_new'] != '') {
				$output .= '<span class="post_cat_tag">';
					$output .= esc_html($postsNeeded[0]['post_tag_new']);
				$output .= '</span>';
			}
			if ($postsNeeded[0]['format'] != '') {
				if ($postsNeeded[0]['format'] == 'image') { $postsNeeded[0]['format'] = 'picture'; }
				if ($postsNeeded[0]['format'] == 'gallery') { $postsNeeded[0]['format'] = 'camera'; }
				if ($postsNeeded[0]['format'] == 'audio') { $postsNeeded[0]['format'] = 'note'; }
				$output .= '<span class="icon-for-post-format icon-'.$postsNeeded[0]['format'].'"></span>';
			}
			if ($postsNeeded[0]['average_rate'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[0]['cat_id'].'">'.$postsNeeded[0]['average_rate'].'</span>';
			}
		$output .= '</div>';
		$output .= '<a href="'.$postsNeeded[0]['permalink'].'" title="'.esc_attr($postsNeeded[0]['title']).'" ><h1 class="post_cat_title_large">';
			$output .= esc_html($postsNeeded[0]['title']);
		$output .= '</h1></a>';
		
		
	$output .= '</div>';
	
	
	
	$output .= '<div class="small_outher_wrapper post_wrapp_width2">';
	$output .= '<div class="small_post_wrapper post_wrapp_width2">';
		$output .= '<a style="display: block; line-height: 0;" href="'.$postsNeeded[1]['permalink'].'" title="'.esc_attr($postsNeeded[1]['title']).'" >';
			$output .= $postsNeeded[1]['PostSection22'];
		$output .= '</a>';
		$output .= '<div class="post-tags">';
			if ($postsNeeded[1]['post_alternative_cat_tag'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[1]['cat_id'].'">';
				$output .= esc_html($postsNeeded[1]['post_alternative_cat_tag']);
				$output .= '</span>';
			}
			else {
				if ($postsNeeded[1]['cat_name'] != '') {
					$output .= '<a href="'.$postsNeeded[1]['cat_link'].'" title="'.esc_attr($postsNeeded[1]['cat_name']).'" class="post_cat_tag category-bg-color-'.$postsNeeded[1]['cat_id'].'">';
					$output .= esc_html($postsNeeded[1]['cat_name']);
					$output .= '</a>';
				}	
			}
			if ($postsNeeded[1]['post_tag_new'] != '') {
				$output .= '<span class="post_cat_tag newtag">';
					$output .= esc_html($postsNeeded[1]['post_tag_new']);
				$output .= '</span>';
			}
			if ($postsNeeded[1]['format'] != '') {
				if ($postsNeeded[1]['format'] == 'image') { $postsNeeded[1]['format'] = 'picture'; }
				if ($postsNeeded[1]['format'] == 'gallery') { $postsNeeded[1]['format'] = 'camera'; }
				if ($postsNeeded[1]['format'] == 'audio') { $postsNeeded[1]['format'] = 'note'; }
				$output .= '<span class="icon-for-post-format icon-'.$postsNeeded[1]['format'].'"></span>';
			}
			if ($postsNeeded[1]['average_rate'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[1]['cat_id'].'">'.$postsNeeded[1]['average_rate'].'</span>';
			}
		$output .= '</div>';
		$output .= '<a href="'.$postsNeeded[1]['permalink'].'" title="'.esc_attr($postsNeeded[1]['title']).'" ><h1 class="post_cat_title_small">';
			$output .= esc_html($postsNeeded[1]['title']);
		$output .= '</h1></a>';
	$output .= '</div>';
	
	$output .= '<div class="small_post_wrapper post_wrapp_width2">';
		$output .= '<a style="display: block; line-height: 0;" href="'.$postsNeeded[2]['permalink'].'" title="'.esc_attr($postsNeeded[2]['title']).'" >';
			$output .= $postsNeeded[2]['PostSection22'];
		$output .= '</a>';
		$output .= '<div class="post-tags">';
			if ($postsNeeded[2]['post_alternative_cat_tag'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[2]['cat_id'].'">';
				$output .= esc_html($postsNeeded[2]['post_alternative_cat_tag']);
				$output .= '</span>';
			}
			else {
				if ($postsNeeded[2]['cat_name'] != '') {
					$output .= '<a href="'.$postsNeeded[2]['cat_link'].'" title="'.esc_attr($postsNeeded[2]['cat_name']).'" class="post_cat_tag category-bg-color-'.$postsNeeded[2]['cat_id'].'">';
					$output .= esc_html($postsNeeded[2]['cat_name']);
					$output .= '</a>';
				}	
			}
			if ($postsNeeded[2]['post_tag_new'] != '') {
				$output .= '<span class="post_cat_tag newtag">';
					$output .= esc_html($postsNeeded[2]['post_tag_new']);
				$output .= '</span>';
			}
			if ($postsNeeded[2]['format'] != '') {
				if ($postsNeeded[2]['format'] == 'image') { $postsNeeded[2]['format'] = 'picture'; }
				if ($postsNeeded[2]['format'] == 'gallery') { $postsNeeded[2]['format'] = 'camera'; }
				if ($postsNeeded[2]['format'] == 'audio') { $postsNeeded[2]['format'] = 'note'; }
				$output .= '<span class="icon-for-post-format icon-'.$postsNeeded[2]['format'].'"></span>';
			}
			if ($postsNeeded[2]['average_rate'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[2]['cat_id'].'">'.$postsNeeded[2]['average_rate'].'</span>';
			}
		$output .= '</div>';
		$output .= '<a href="'.$postsNeeded[2]['permalink'].'" title="'.esc_attr($postsNeeded[2]['title']).'" ><h1 class="post_cat_title_small">';
			$output .= esc_html($postsNeeded[2]['title']);
		$output .= '</h1></a>';
	$output .= '</div>';
	$output .= '</div>';


	$output .= '<div class="upright_post_wrapper post_wrapp_width1">';
		$output .= '<a style="display: block; line-height: 0;" href="'.$postsNeeded[3]['permalink'].'" title="'.esc_attr($postsNeeded[3]['title']).'" >';
			$output .= $postsNeeded[3]['PostSection11'];
		$output .= '</a>';
		$output .= '<div class="post-tags">';
			if ($postsNeeded[3]['post_alternative_cat_tag'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[3]['cat_id'].'">';
				$output .= esc_html($postsNeeded[3]['post_alternative_cat_tag']);
				$output .= '</span>';
			}
			else {
				if ($postsNeeded[3]['cat_name'] != '') {
					$output .= '<a href="'.$postsNeeded[3]['cat_link'].'" title="'.esc_attr($postsNeeded[3]['cat_name']).'" class="post_cat_tag category-bg-color-'.$postsNeeded[3]['cat_id'].'">';
					$output .= esc_html($postsNeeded[3]['cat_name']);
					$output .= '</a>';
				}	
			}
			if ($postsNeeded[3]['post_tag_new'] != '') {
				$output .= '<span class="post_cat_tag newtag">';
					$output .= esc_html($postsNeeded[3]['post_tag_new']);
				$output .= '</span>';
			}
			if ($postsNeeded[3]['format'] != '') {
				if ($postsNeeded[3]['format'] == 'image') { $postsNeeded[3]['format'] = 'picture'; }
				if ($postsNeeded[3]['format'] == 'gallery') { $postsNeeded[3]['format'] = 'camera'; }
				if ($postsNeeded[3]['format'] == 'audio') { $postsNeeded[3]['format'] = 'note'; }
				$output .= '<span class="icon-for-post-format icon-'.$postsNeeded[3]['format'].'"></span>';
			}
			if ($postsNeeded[3]['average_rate'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[3]['cat_id'].'">'.$postsNeeded[3]['average_rate'].'</span>';
			}
		$output .= '</div>';
		$output .= '<a href="'.$postsNeeded[3]['permalink'].'" title="'.esc_attr($postsNeeded[3]['title']).'" ><h1 class="post_cat_title_small">';
			$output .= esc_html($postsNeeded[3]['title']);
		$output .= '</h1></a>';
	$output .= '</div>';
}
	
if ($post_section_type == 'type2') {
		$output .= '<div class="large_post_wrapper post_wrapp_width3 first ">';
		$output .= '<a style="display: block; line-height: 0;" href="'.$postsNeeded[0]['permalink'].'" title="'.esc_attr($postsNeeded[0]['title']).'" >';
			$output .= $postsNeeded[0]['PostSection31'];
		$output .= '</a>';
		$output .= '<div class="post-tags">';
			if ($postsNeeded[0]['post_alternative_cat_tag'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[0]['cat_id'].'">';
				$output .= esc_html($postsNeeded[0]['post_alternative_cat_tag']);
				$output .= '</span>';
			}
			else {
				if ($postsNeeded[0]['cat_name'] != '') {
					$output .= '<a href="'.$postsNeeded[0]['cat_link'].'" title="'.esc_attr($postsNeeded[0]['cat_name']).'" class="post_cat_tag category-bg-color-'.$postsNeeded[0]['cat_id'].'">';
					$output .= esc_html($postsNeeded[0]['cat_name']);
					$output .= '</a>';
				}				
			}
			if ($postsNeeded[0]['post_tag_new'] != '') {
				$output .= '<span class="post_cat_tag">';
					$output .= esc_html($postsNeeded[0]['post_tag_new']);
				$output .= '</span>';
			}
			if ($postsNeeded[0]['format'] != '') {
				if ($postsNeeded[0]['format'] == 'image') { $postsNeeded[0]['format'] = 'picture'; }
				if ($postsNeeded[0]['format'] == 'gallery') { $postsNeeded[0]['format'] = 'camera'; }
				if ($postsNeeded[0]['format'] == 'audio') { $postsNeeded[0]['format'] = 'note'; }
				$output .= '<span class="icon-for-post-format icon-'.$postsNeeded[0]['format'].'"></span>';
			}
			if ($postsNeeded[0]['average_rate'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[0]['cat_id'].'">'.$postsNeeded[0]['average_rate'].'</span>';
			}
		$output .= '</div>';
		$output .= '<a href="'.$postsNeeded[0]['permalink'].'" title="'.esc_attr($postsNeeded[0]['title']).'" ><h1 class="post_cat_title_large">';
			$output .= esc_html($postsNeeded[0]['title']);
		$output .= '</h1></a>';
	$output .= '</div>';
	
	
	$output .= '<div class="large_post_wrapper post_wrapp_width2">';
		$output .= '<a style="display: block; line-height: 0;" href="'.$postsNeeded[1]['permalink'].'" title="'.esc_attr($postsNeeded[1]['title']).'" >';
			$output .= $postsNeeded[1]['PostSection21'];
		$output .= '</a>';
		$output .= '<div class="post-tags">';
			if ($postsNeeded[1]['post_alternative_cat_tag'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[1]['cat_id'].'">';
				$output .= esc_html($postsNeeded[1]['post_alternative_cat_tag']);
				$output .= '</span>';
			}
			else {
				if ($postsNeeded[1]['cat_name'] != '') {
					$output .= '<a href="'.$postsNeeded[1]['cat_link'].'" title="'.esc_attr($postsNeeded[1]['cat_name']).'" class="post_cat_tag category-bg-color-'.$postsNeeded[1]['cat_id'].'">';
					$output .= esc_html($postsNeeded[1]['cat_name']);
					$output .= '</a>';
				}	
			}
			if ($postsNeeded[1]['post_tag_new'] != '') {
				$output .= '<span class="post_cat_tag newtag">';
					$output .= esc_html($postsNeeded[1]['post_tag_new']);
				$output .= '</span>';
			}
			if ($postsNeeded[1]['format'] != '') {
				if ($postsNeeded[1]['format'] == 'image') { $postsNeeded[1]['format'] = 'picture'; }
				if ($postsNeeded[1]['format'] == 'gallery') { $postsNeeded[1]['format'] = 'camera'; }
				if ($postsNeeded[1]['format'] == 'audio') { $postsNeeded[1]['format'] = 'note'; }
				$output .= '<span class="icon-for-post-format icon-'.$postsNeeded[1]['format'].'"></span>';
			}
			if ($postsNeeded[1]['average_rate'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[1]['cat_id'].'">'.$postsNeeded[1]['average_rate'].'</span>';
			}
		$output .= '</div>';
		$output .= '<a href="'.$postsNeeded[1]['permalink'].'" title="'.esc_attr($postsNeeded[1]['title']).'" ><h1 class="post_cat_title_small">';
			$output .= esc_html($postsNeeded[1]['title']);
		$output .= '</h1></a>';
	$output .= '</div>';
	


	$output .= '<div class="upright_post_wrapper post_wrapp_width1">';
		$output .= '<a style="display: block; line-height: 0;" href="'.$postsNeeded[2]['permalink'].'" title="'.esc_attr($postsNeeded[2]['title']).'" >';
			$output .= $postsNeeded[2]['PostSection11'];
		$output .= '</a>';
		$output .= '<div class="post-tags">';
			if ($postsNeeded[2]['post_alternative_cat_tag'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[2]['cat_id'].'">';
				$output .= esc_html($postsNeeded[2]['post_alternative_cat_tag']);
				$output .= '</span>';
			}
			else {
				if ($postsNeeded[2]['cat_name'] != '') {
					$output .= '<a href="'.$postsNeeded[2]['cat_link'].'" title="'.esc_attr($postsNeeded[2]['cat_name']).'" class="post_cat_tag category-bg-color-'.$postsNeeded[2]['cat_id'].'">';
					$output .= esc_html($postsNeeded[2]['cat_name']);
					$output .= '</a>';
				}	
			}
			if ($postsNeeded[2]['post_tag_new'] != '') {
				$output .= '<span class="post_cat_tag newtag">';
					$output .= esc_html($postsNeeded[2]['post_tag_new']);
				$output .= '</span>';
			}
			if ($postsNeeded[2]['format'] != '') {
				if ($postsNeeded[2]['format'] == 'image') { $postsNeeded[2]['format'] = 'picture'; }
				if ($postsNeeded[2]['format'] == 'gallery') { $postsNeeded[2]['format'] = 'camera'; }
				if ($postsNeeded[2]['format'] == 'audio') { $postsNeeded[2]['format'] = 'note'; }
				$output .= '<span class="icon-for-post-format icon-'.$postsNeeded[2]['format'].'"></span>';
			}
			if ($postsNeeded[2]['average_rate'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[2]['cat_id'].'">'.$postsNeeded[2]['average_rate'].'</span>';
			}
		$output .= '</div>';
		$output .= '<a href="'.$postsNeeded[2]['permalink'].'" title="'.esc_attr($postsNeeded[2]['title']).'" ><h1 class="post_cat_title_small">';
			$output .= esc_html($postsNeeded[2]['title']);
		$output .= '</h1></a>';
	$output .= '</div>';
	}
  
if ($post_section_type == 'type3') {

	$output .= '<div class="large_post_wrapper post_wrapp_width3 first">';
		$output .= '<a style="display: block; line-height: 0;" href="'.$postsNeeded[0]['permalink'].'" title="'.$postsNeeded[0]['title'].'" >';
			$output .= $postsNeeded[0]['PostSection31'];
		$output .= '</a>';
		$output .= '<div class="post-tags">';
			if ($postsNeeded[0]['post_alternative_cat_tag'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[0]['cat_id'].'">';
				$output .= esc_html($postsNeeded[0]['post_alternative_cat_tag']);
				$output .= '</span>';
			}
			else {
				if ($postsNeeded[0]['cat_name'] != '') {
					$output .= '<a href="'.$postsNeeded[0]['cat_link'].'" title="'.esc_attr($postsNeeded[0]['cat_name']).'" class="post_cat_tag category-bg-color-'.$postsNeeded[0]['cat_id'].'">';
					$output .= esc_html($postsNeeded[0]['cat_name']);
					$output .= '</a>';
				}				
			}
			if ($postsNeeded[0]['post_tag_new'] != '') {
				$output .= '<span class="post_cat_tag">';
					$output .= esc_html($postsNeeded[0]['post_tag_new']);
				$output .= '</span>';
			}
			if ($postsNeeded[0]['format'] != '') {
				if ($postsNeeded[0]['format'] == 'image') { $postsNeeded[0]['format'] = 'picture'; }
				if ($postsNeeded[0]['format'] == 'gallery') { $postsNeeded[0]['format'] = 'camera'; }
				if ($postsNeeded[0]['format'] == 'audio') { $postsNeeded[0]['format'] = 'note'; }
				$output .= '<span class="icon-for-post-format icon-'.$postsNeeded[0]['format'].'"></span>';
			}
			if ($postsNeeded[0]['average_rate'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[0]['cat_id'].'">'.$postsNeeded[0]['average_rate'].'</span>';
			}
		$output .= '</div>';
		$output .= '<a href="'.$postsNeeded[0]['permalink'].'" title="'.esc_attr($postsNeeded[0]['title']).'" ><h1 class="post_cat_title_large">';
			$output .= esc_html($postsNeeded[0]['title']);
		$output .= '</h1></a>';
	$output .= '</div>';
	
	
	$output .= '<div class="upright_post_wrapper post_wrapp_width1">';
		$output .= '<a style="display: block; line-height: 0;" href="'.$postsNeeded[1]['permalink'].'" title="'.esc_attr($postsNeeded[1]['title']).'" >';
			$output .= $postsNeeded[1]['PostSection11'];
		$output .= '</a>';
		$output .= '<div class="post-tags">';
			if ($postsNeeded[1]['post_alternative_cat_tag'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[1]['cat_id'].'">';
				$output .= esc_html($postsNeeded[1]['post_alternative_cat_tag']);
				$output .= '</span>';
			}
			else {
				if ($postsNeeded[1]['cat_name'] != '') {
					$output .= '<a href="'.$postsNeeded[1]['cat_link'].'" title="'.esc_attr($postsNeeded[1]['cat_name']).'" class="post_cat_tag category-bg-color-'.$postsNeeded[1]['cat_id'].'">';
					$output .= esc_html($postsNeeded[1]['cat_name']);
					$output .= '</a>';
				}	
			}
			if ($postsNeeded[1]['post_tag_new'] != '') {
				$output .= '<span class="post_cat_tag newtag">';
					$output .= esc_html($postsNeeded[1]['post_tag_new']);
				$output .= '</span>';
			}
			if ($postsNeeded[1]['format'] != '') {
				if ($postsNeeded[1]['format'] == 'image') { $postsNeeded[1]['format'] = 'picture'; }
				if ($postsNeeded[1]['format'] == 'gallery') { $postsNeeded[1]['format'] = 'camera'; }
				if ($postsNeeded[1]['format'] == 'audio') { $postsNeeded[1]['format'] = 'note'; }
				$output .= '<span class="icon-for-post-format icon-'.$postsNeeded[1]['format'].'"></span>';
			}
			if ($postsNeeded[1]['average_rate'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[1]['cat_id'].'">'.$postsNeeded[1]['average_rate'].'</span>';
			}
		$output .= '</div>';
		$output .= '<a href="'.$postsNeeded[1]['permalink'].'" title="'.esc_attr($postsNeeded[1]['title']).'" ><h1 class="post_cat_title_small">';
			$output .= esc_html($postsNeeded[1]['title']);
		$output .= '</h1></a>';
	$output .= '</div>';

	$output .= '<div class="upright_post_wrapper post_wrapp_width1">';
		$output .= '<a style="display: block; line-height: 0;" href="'.$postsNeeded[2]['permalink'].'" title="'.esc_attr($postsNeeded[2]['title']).'" >';
			$output .= $postsNeeded[2]['PostSection11'];
		$output .= '</a>';
		$output .= '<div class="post-tags">';
			if ($postsNeeded[2]['post_alternative_cat_tag'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[2]['cat_id'].'">';
				$output .= esc_html($postsNeeded[2]['post_alternative_cat_tag']);
				$output .= '</span>';
			}
			else {
				if ($postsNeeded[2]['cat_name'] != '') {
					$output .= '<a href="'.$postsNeeded[2]['cat_link'].'" title="'.esc_attr($postsNeeded[2]['cat_name']).'" class="post_cat_tag category-bg-color-'.$postsNeeded[2]['cat_id'].'">';
					$output .= esc_html($postsNeeded[2]['cat_name']);
					$output .= '</a>';
				}	
			}
			if ($postsNeeded[2]['post_tag_new'] != '') {
				$output .= '<span class="post_cat_tag newtag">';
					$output .= esc_html($postsNeeded[2]['post_tag_new']);
				$output .= '</span>';
			}
			if ($postsNeeded[2]['format'] != '') {
				if ($postsNeeded[2]['format'] == 'image') { $postsNeeded[2]['format'] = 'picture'; }
				if ($postsNeeded[2]['format'] == 'gallery') { $postsNeeded[2]['format'] = 'camera'; }
				if ($postsNeeded[2]['format'] == 'audio') { $postsNeeded[2]['format'] = 'note'; }
				$output .= '<span class="icon-for-post-format icon-'.$postsNeeded[2]['format'].'"></span>';
			}
			if ($postsNeeded[2]['average_rate'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[2]['cat_id'].'">'.$postsNeeded[2]['average_rate'].'</span>';
			}
		$output .= '</div>';
		$output .= '<a href="'.$postsNeeded[2]['permalink'].'"  title="'.esc_attr($postsNeeded[2]['title']).'" ><h1 class="post_cat_title_small">';
			$output .= esc_html($postsNeeded[2]['title']);
		$output .= '</h1></a>';
	$output .= '</div>';
	
	$output .= '<div class="upright_post_wrapper post_wrapp_width1">';
		$output .= '<a style="display: block; line-height: 0;" href="'.$postsNeeded[3]['permalink'].'" title="'.esc_attr($postsNeeded[3]['title']).'" >';
			$output .= $postsNeeded[3]['PostSection11'];
		$output .= '</a>';
		$output .= '<div class="post-tags">';
			if ($postsNeeded[3]['post_alternative_cat_tag'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[3]['cat_id'].'">';
				$output .= esc_html($postsNeeded[3]['post_alternative_cat_tag']);
				$output .= '</span>';
			}
			else {
				if ($postsNeeded[3]['cat_name'] != '') {
					$output .= '<a href="'.$postsNeeded[3]['cat_link'].'" title="'.esc_attr($postsNeeded[3]['cat_name']).'" class="post_cat_tag category-bg-color-'.$postsNeeded[3]['cat_id'].'">';
					$output .= esc_html($postsNeeded[3]['cat_name']);
					$output .= '</a>';
				}	
			}
			if ($postsNeeded[3]['post_tag_new'] != '') {
				$output .= '<span class="post_cat_tag newtag">';
					$output .= esc_html($postsNeeded[3]['post_tag_new']);
				$output .= '</span>';
			}
			if ($postsNeeded[3]['format'] != '') {
				if ($postsNeeded[3]['format'] == 'image') { $postsNeeded[3]['format'] = 'picture'; }
				if ($postsNeeded[3]['format'] == 'gallery') { $postsNeeded[3]['format'] = 'camera'; }
				if ($postsNeeded[3]['format'] == 'audio') { $postsNeeded[3]['format'] = 'note'; }
				$output .= '<span class="icon-for-post-format icon-'.$postsNeeded[3]['format'].'"></span>';
			}
			if ($postsNeeded[3]['average_rate'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[3]['cat_id'].'">'.$postsNeeded[3]['average_rate'].'</span>';
			}
		$output .= '</div>';
		$output .= '<a href="'.$postsNeeded[3]['permalink'].'" title="'.esc_attr($postsNeeded[3]['title']).'" ><h1 class="post_cat_title_small">';
			$output .= esc_html($postsNeeded[3]['title']);
		$output .= '</h1></a>';
	$output .= '</div>';
}
 
if ($post_section_type == 'type4') {

	$output .= '<div class="large_post_wrapper post_wrapp_width4 first">';
		$output .= '<a style="display: block; line-height: 0;" href="'.$postsNeeded[0]['permalink'].'" title="'.esc_attr($postsNeeded[0]['title']).'" >';
			$output .= $postsNeeded[0]['PostSection41'];
		$output .= '</a>';
		$output .= '<div class="post-tags">';
			if ($postsNeeded[0]['post_alternative_cat_tag'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[0]['cat_id'].'">';
				$output .= esc_html($postsNeeded[0]['post_alternative_cat_tag']);
				$output .= '</span>';
			}
			else {
				if ($postsNeeded[0]['cat_name'] != '') {
					$output .= '<a href="'.$postsNeeded[0]['cat_link'].'" title="'.esc_attr($postsNeeded[0]['cat_name']).'" class="post_cat_tag category-bg-color-'.$postsNeeded[0]['cat_id'].'">';
					$output .= esc_html($postsNeeded[0]['cat_name']);
					$output .= '</a>';
				}				
			}
			if ($postsNeeded[0]['post_tag_new'] != '') {
				$output .= '<span class="post_cat_tag">';
					$output .= esc_html($postsNeeded[0]['post_tag_new']);
				$output .= '</span>';
			}
			if ($postsNeeded[0]['format'] != '') {
				if ($postsNeeded[0]['format'] == 'image') { $postsNeeded[0]['format'] = 'picture'; }
				if ($postsNeeded[0]['format'] == 'gallery') { $postsNeeded[0]['format'] = 'camera'; }
				if ($postsNeeded[0]['format'] == 'audio') { $postsNeeded[0]['format'] = 'note'; }
				$output .= '<span class="icon-for-post-format icon-'.$postsNeeded[0]['format'].'"></span>';
			}
			if ($postsNeeded[0]['average_rate'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[0]['cat_id'].'">'.$postsNeeded[0]['average_rate'].'</span>';
			}
		$output .= '</div>';
		$output .= '<a href="'.$postsNeeded[0]['permalink'].'" title="'.esc_attr($postsNeeded[0]['title']).'" ><h1 class="post_cat_title_large">';
			$output .= esc_html($postsNeeded[0]['title']);
		$output .= '</h1></a>';
	$output .= '</div>';
	
	
	$output .= '<div class="upright_post_wrapper post_wrapp_width1">';
		$output .= '<a style="display: block; line-height: 0;" href="'.$postsNeeded[1]['permalink'].'" title="'.esc_attr($postsNeeded[1]['title']).'" >';
			$output .= $postsNeeded[1]['PostSection11'];
		$output .= '</a>';
		$output .= '<div class="post-tags">';
			if ($postsNeeded[1]['post_alternative_cat_tag'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[1]['cat_id'].'">';
				$output .= esc_html($postsNeeded[1]['post_alternative_cat_tag']);
				$output .= '</span>';
			}
			else {
				if ($postsNeeded[1]['cat_name'] != '') {
					$output .= '<a href="'.$postsNeeded[1]['cat_link'].'" title="'.esc_attr($postsNeeded[1]['cat_name']).'" class="post_cat_tag category-bg-color-'.$postsNeeded[1]['cat_id'].'">';
					$output .= esc_html($postsNeeded[1]['cat_name']);
					$output .= '</a>';
				}	
			}
			if ($postsNeeded[1]['post_tag_new'] != '') {
				$output .= '<span class="post_cat_tag newtag">';
					$output .= esc_html($postsNeeded[1]['post_tag_new']);
				$output .= '</span>';
			}
			if ($postsNeeded[1]['format'] != '') {
				if ($postsNeeded[1]['format'] == 'image') { $postsNeeded[1]['format'] = 'picture'; }
				if ($postsNeeded[1]['format'] == 'gallery') { $postsNeeded[1]['format'] = 'camera'; }
				if ($postsNeeded[1]['format'] == 'audio') { $postsNeeded[1]['format'] = 'note'; }
				$output .= '<span class="icon-for-post-format icon-'.$postsNeeded[1]['format'].'"></span>';
			}
			if ($postsNeeded[1]['average_rate'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[1]['cat_id'].'">'.$postsNeeded[1]['average_rate'].'</span>';
			}
		$output .= '</div>';
		$output .= '<a href="'.$postsNeeded[1]['permalink'].'" title="'.esc_attr($postsNeeded[1]['title']).'" ><h1 class="post_cat_title_small">';
			$output .= esc_html($postsNeeded[1]['title']);
		$output .= '</h1></a>';
	$output .= '</div>';

	$output .= '<div class="upright_post_wrapper post_wrapp_width1">';
		$output .= '<a style="display: block; line-height: 0;" href="'.$postsNeeded[2]['permalink'].'" title="'.esc_attr($postsNeeded[2]['title']).'" >';
			$output .= $postsNeeded[2]['PostSection11'];
		$output .= '</a>';
		$output .= '<div class="post-tags">';
			if ($postsNeeded[2]['post_alternative_cat_tag'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[2]['cat_id'].'">';
				$output .= esc_html($postsNeeded[2]['post_alternative_cat_tag']);
				$output .= '</span>';
			}
			else {
				if ($postsNeeded[2]['cat_name'] != '') {
					$output .= '<a href="'.$postsNeeded[2]['cat_link'].'" title="'.esc_attr($postsNeeded[2]['cat_name']).'" class="post_cat_tag category-bg-color-'.$postsNeeded[2]['cat_id'].'">';
					$output .= esc_html($postsNeeded[2]['cat_name']);
					$output .= '</a>';
				}	
			}
			if ($postsNeeded[2]['post_tag_new'] != '') {
				$output .= '<span class="post_cat_tag newtag">';
					$output .= esc_html($postsNeeded[2]['post_tag_new']);
				$output .= '</span>';
			}
			if ($postsNeeded[2]['format'] != '') {
				if ($postsNeeded[2]['format'] == 'image') { $postsNeeded[2]['format'] = 'picture'; }
				if ($postsNeeded[2]['format'] == 'gallery') { $postsNeeded[2]['format'] = 'camera'; }
				if ($postsNeeded[2]['format'] == 'audio') { $postsNeeded[2]['format'] = 'note'; }
				$output .= '<span class="icon-for-post-format icon-'.$postsNeeded[2]['format'].'"></span>';
			}
			if ($postsNeeded[2]['average_rate'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[2]['cat_id'].'">'.$postsNeeded[2]['average_rate'].'</span>';
			}
		$output .= '</div>';
		$output .= '<a href="'.$postsNeeded[2]['permalink'].'" title="'.esc_attr($postsNeeded[2]['title']).'" ><h1 class="post_cat_title_small">';
			$output .= esc_html($postsNeeded[2]['title']);
		$output .= '</h1></a>';
	$output .= '</div>';
	
	
}

if ($post_section_type == 'type5') {

	$output .= '<div class="large_post_wrapper post_wrapp_width2 first">';
		$output .= '<a style="display: block; line-height: 0;" href="'.$postsNeeded[0]['permalink'].'" title="'.esc_attr($postsNeeded[0]['title']).'" >';
			$output .= $postsNeeded[0]['PostSection21'];
		$output .= '</a>';
		$output .= '<div class="post-tags">';
			if ($postsNeeded[0]['post_alternative_cat_tag'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[0]['cat_id'].'">';
				$output .= esc_html($postsNeeded[0]['post_alternative_cat_tag']);
				$output .= '</span>';
			}
			else {
				if ($postsNeeded[0]['cat_name'] != '') {
					$output .= '<a href="'.$postsNeeded[0]['cat_link'].'" title="'.esc_attr($postsNeeded[0]['cat_name']).'" class="post_cat_tag category-bg-color-'.$postsNeeded[0]['cat_id'].'">';
					$output .= esc_html($postsNeeded[0]['cat_name']);
					$output .= '</a>';
				}				
			}
			if ($postsNeeded[0]['post_tag_new'] != '') {
				$output .= '<span class="post_cat_tag">';
					$output .= esc_html($postsNeeded[0]['post_tag_new']);
				$output .= '</span>';
			}
			if ($postsNeeded[0]['format'] != '') {
				if ($postsNeeded[0]['format'] == 'image') { $postsNeeded[0]['format'] = 'picture'; }
				if ($postsNeeded[0]['format'] == 'gallery') { $postsNeeded[0]['format'] = 'camera'; }
				if ($postsNeeded[0]['format'] == 'audio') { $postsNeeded[0]['format'] = 'note'; }
				$output .= '<span class="icon-for-post-format icon-'.$postsNeeded[0]['format'].'"></span>';
			}
			if ($postsNeeded[0]['average_rate'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[0]['cat_id'].'">'.$postsNeeded[0]['average_rate'].'</span>';
			}
		$output .= '</div>';
		$output .= '<a href="'.$postsNeeded[0]['permalink'].'" title="'.esc_attr($postsNeeded[0]['title']).'"><h1 class="post_cat_title_large">';
			$output .= esc_html($postsNeeded[0]['title']);
		$output .= '</h1></a>';
	$output .= '</div>';
	
	$output .= '<div class="large_post_wrapper post_wrapp_width2">';
		$output .= '<a style="display: block; line-height: 0;" href="'.$postsNeeded[1]['permalink'].'" title="'.esc_attr($postsNeeded[1]['title']).'" >';
			$output .= $postsNeeded[1]['PostSection21'];
		$output .= '</a>';
		$output .= '<div class="post-tags">';
			if ($postsNeeded[1]['post_alternative_cat_tag'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[1]['cat_id'].'">';
				$output .= esc_html($postsNeeded[1]['post_alternative_cat_tag']);
				$output .= '</span>';
			}
			else {
				if ($postsNeeded[1]['cat_name'] != '') {
					$output .= '<a href="'.$postsNeeded[1]['cat_link'].'" title="'.esc_attr($postsNeeded[1]['cat_name']).'"  class="post_cat_tag category-bg-color-'.$postsNeeded[1]['cat_id'].'">';
					$output .= esc_html($postsNeeded[1]['cat_name']);
					$output .= '</a>';
				}				
			}
			if ($postsNeeded[1]['post_tag_new'] != '') {
				$output .= '<span class="post_cat_tag">';
					$output .= esc_html($postsNeeded[1]['post_tag_new']);
				$output .= '</span>';
			}
			if ($postsNeeded[1]['format'] != '') {
				if ($postsNeeded[1]['format'] == 'image') { $postsNeeded[1]['format'] = 'picture'; }
				if ($postsNeeded[1]['format'] == 'gallery') { $postsNeeded[1]['format'] = 'camera'; }
				if ($postsNeeded[1]['format'] == 'audio') { $postsNeeded[1]['format'] = 'note'; }
				$output .= '<span class="icon-for-post-format icon-'.$postsNeeded[1]['format'].'"></span>';
			}
			if ($postsNeeded[1]['average_rate'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[1]['cat_id'].'">'.$postsNeeded[1]['average_rate'].'</span>';
			}
		$output .= '</div>';
		$output .= '<a href="'.$postsNeeded[1]['permalink'].'" title="'.esc_attr($postsNeeded[1]['title']).'" ><h1 class="post_cat_title_large">';
			$output .= esc_html($postsNeeded[1]['title']);
		$output .= '</h1></a>';
	$output .= '</div>';
	
	$output .= '<div class="small_outher_wrapper post_wrapp_width2">';
	$output .= '<div class="small_post_wrapper post_wrapp_width2">';
		$output .= '<a style="display: block; line-height: 0;" href="'.$postsNeeded[2]['permalink'].'" title="'.esc_attr($postsNeeded[2]['title']).'" >';
			$output .= $postsNeeded[2]['PostSection22'];
		$output .= '</a>';
		$output .= '<div class="post-tags">';
			if ($postsNeeded[2]['post_alternative_cat_tag'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[2]['cat_id'].'">';
				$output .= esc_html($postsNeeded[2]['post_alternative_cat_tag']);
				$output .= '</span>';
			}
			else {
				if ($postsNeeded[2]['cat_name'] != '') {
					$output .= '<a href="'.$postsNeeded[2]['cat_link'].'" title="'.esc_attr($postsNeeded[2]['cat_name']).'" class="post_cat_tag category-bg-color-'.$postsNeeded[2]['cat_id'].'">';
					$output .= esc_html($postsNeeded[2]['cat_name']);
					$output .= '</a>';
				}	
			}
			if ($postsNeeded[2]['post_tag_new'] != '') {
				$output .= '<span class="post_cat_tag newtag">';
					$output .= esc_html($postsNeeded[2]['post_tag_new']);
				$output .= '</span>';
			}
			if ($postsNeeded[2]['format'] != '') {
				if ($postsNeeded[2]['format'] == 'image') { $postsNeeded[2]['format'] = 'picture'; }
				if ($postsNeeded[2]['format'] == 'gallery') { $postsNeeded[2]['format'] = 'camera'; }
				if ($postsNeeded[2]['format'] == 'audio') { $postsNeeded[2]['format'] = 'note'; }
				$output .= '<span class="icon-for-post-format icon-'.$postsNeeded[2]['format'].'"></span>';
			}
			if ($postsNeeded[2]['average_rate'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[2]['cat_id'].'">'.$postsNeeded[2]['average_rate'].'</span>';
			}
		$output .= '</div>';
		$output .= '<a href="'.$postsNeeded[2]['permalink'].'" title="'.esc_attr($postsNeeded[2]['title']).'" ><h1 class="post_cat_title_small">';
			$output .= esc_html($postsNeeded[2]['title']);
		$output .= '</h1></a>';
	$output .= '</div>';
	
		$output .= '<div class="small_post_wrapper post_wrapp_width2">';
		$output .= '<a style="display: block; line-height: 0;" href="'.$postsNeeded[3]['permalink'].'" title="'.esc_attr($postsNeeded[3]['title']).'" >';
			$output .= $postsNeeded[3]['PostSection22'];
		$output .= '</a>';
		$output .= '<div class="post-tags">';
			if ($postsNeeded[3]['post_alternative_cat_tag'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[3]['cat_id'].'">';
				$output .= esc_html($postsNeeded[3]['post_alternative_cat_tag']);
				$output .= '</span>';
			}
			else {
				if ($postsNeeded[3]['cat_name'] != '') {
					$output .= '<a href="'.$postsNeeded[3]['cat_link'].'" title="'.esc_attr($postsNeeded[3]['cat_name']).'" class="post_cat_tag category-bg-color-'.$postsNeeded[3]['cat_id'].'">';
					$output .= esc_html($postsNeeded[3]['cat_name']);
					$output .= '</a>';
				}	
			}
			if ($postsNeeded[3]['post_tag_new'] != '') {
				$output .= '<span class="post_cat_tag newtag">';
					$output .= esc_html($postsNeeded[3]['post_tag_new']);
				$output .= '</span>';
			}
			if ($postsNeeded[3]['format'] != '') {
				if ($postsNeeded[3]['format'] == 'image') { $postsNeeded[3]['format'] = 'picture'; }
				if ($postsNeeded[3]['format'] == 'gallery') { $postsNeeded[3]['format'] = 'camera'; }
				if ($postsNeeded[3]['format'] == 'audio') { $postsNeeded[3]['format'] = 'note'; }
				$output .= '<span class="icon-for-post-format icon-'.$postsNeeded[3]['format'].'"></span>';
			}
			if ($postsNeeded[3]['average_rate'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[3]['cat_id'].'">'.$postsNeeded[3]['average_rate'].'</span>';
			}
		$output .= '</div>';
		$output .= '<a href="'.$postsNeeded[3]['permalink'].'" title="'.esc_attr($postsNeeded[3]['title']).'" ><h1 class="post_cat_title_small">';
			$output .= esc_html($postsNeeded[3]['title']);
		$output .= '</h1></a>';
	$output .= '</div>';
	$output .= '</div>';
	
}

if ($post_section_type == 'type6') {

	$output .= '<div class="large_post_wrapper post_wrapp_width3 first">';
		$output .= '<div class="flexsliderPostSection"><ul class="slides">';

	for ($i = 0; $i < $type6_index; $i++) {
    	$output .='<li>';
		
		$output .= '<a style="display: block; line-height: 0;" href="'.$postsNeeded[$i]['permalink'].'" title="'.esc_attr($postsNeeded[$i]['title']).'" >';
			$output .= $postsNeeded[$i]['PostSection31'];
		$output .= '</a>';
		$output .= '<div class="post-tags">';
			if ($postsNeeded[$i]['post_alternative_cat_tag'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[$i]['cat_id'].'">';
				$output .= esc_html($postsNeeded[$i]['post_alternative_cat_tag']);
				$output .= '</span>';
			}
			else {
				if ($postsNeeded[$i]['cat_name'] != '') {
					$output .= '<a href="'.$postsNeeded[$i]['cat_link'].'" title="'.esc_attr($postsNeeded[$i]['cat_name']).'" class="post_cat_tag category-bg-color-'.$postsNeeded[$i]['cat_id'].'">';
					$output .= esc_html($postsNeeded[$i]['cat_name']);
					$output .= '</a>';
				}				
			}
			if ($postsNeeded[$i]['post_tag_new'] != '') {
				$output .= '<span class="post_cat_tag">';
					$output .= esc_html($postsNeeded[$i]['post_tag_new']);
				$output .= '</span>';
			}
			if ($postsNeeded[$i]['format'] != '') {
				if ($postsNeeded[$i]['format'] == 'image') { $postsNeeded[$i]['format'] = 'picture'; }
				if ($postsNeeded[$i]['format'] == 'gallery') { $postsNeeded[$i]['format'] = 'camera'; }
				if ($postsNeeded[$i]['format'] == 'audio') { $postsNeeded[$i]['format'] = 'note'; }
				$output .= '<span class="icon-for-post-format icon-'.$postsNeeded[$i]['format'].'"></span>';
			}
			if ($postsNeeded[$i]['average_rate'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[$i]['cat_id'].'">'.$postsNeeded[$i]['average_rate'].'</span>';
			}
		$output .= '</div>';
		$output .= '<a href="'.$postsNeeded[$i]['permalink'].'" title="'.esc_attr($postsNeeded[$i]['title']).'" ><h1 class="post_cat_title_large">';
			$output .= esc_html($postsNeeded[$i]['title']);
		$output .= '</h1></a>';
		
		$output .='</li>';
	}
	$output .='</ul></div>';
		
		
	$output .= '</div>';
	
	
	
	$output .= '<div class="small_outher_wrapper post_wrapp_width2">';
	$output .= '<div class="small_post_wrapper post_wrapp_width2">';
		$output .= '<a style="display: block; line-height: 0;" href="'.$postsNeeded[$type6_index]['permalink'].'" title="'.esc_attr($postsNeeded[$type6_index]['title']).'" >';
			$output .= $postsNeeded[$type6_index]['PostSection22'];
		$output .= '</a>';
		$output .= '<div class="post-tags">';
			if ($postsNeeded[$type6_index]['post_alternative_cat_tag'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[$type6_index]['cat_id'].'">';
				$output .= esc_html($postsNeeded[$type6_index]['post_alternative_cat_tag']);
				$output .= '</span>';
			}
			else {
				if ($postsNeeded[$type6_index]['cat_name'] != '') {
					$output .= '<a href="'.$postsNeeded[$type6_index]['cat_link'].'" title="'.esc_attr($postsNeeded[$type6_index]['cat_name']).'" class="post_cat_tag category-bg-color-'.$postsNeeded[$type6_index]['cat_id'].'">';
					$output .= esc_html($postsNeeded[$type6_index]['cat_name']);
					$output .= '</a>';
				}	
			}
			if ($postsNeeded[1]['post_tag_new'] != '') {
				$output .= '<span class="post_cat_tag newtag">';
					$output .= esc_html($postsNeeded[$type6_index]['post_tag_new']);
				$output .= '</span>';
			}
			if ($postsNeeded[$type6_index]['format'] != '') {
				if ($postsNeeded[$type6_index]['format'] == 'image') { $postsNeeded[$type6_index]['format'] = 'picture'; }
				if ($postsNeeded[$type6_index]['format'] == 'gallery') { $postsNeeded[$type6_index]['format'] = 'camera'; }
				if ($postsNeeded[$type6_index]['format'] == 'audio') { $postsNeeded[$type6_index]['format'] = 'note'; }
				$output .= '<span class="icon-for-post-format icon-'.$postsNeeded[$type6_index]['format'].'"></span>';
			}
			if ($postsNeeded[$type6_index]['average_rate'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[$type6_index]['cat_id'].'">'.$postsNeeded[$type6_index]['average_rate'].'</span>';
			}
		$output .= '</div>';
		$output .= '<a href="'.$postsNeeded[$type6_index]['permalink'].'" title="'.esc_attr($postsNeeded[$type6_index]['title']).'" ><h1 class="post_cat_title_small">';
			$output .= esc_html($postsNeeded[$type6_index]['title']);
		$output .= '</h1></a>';
	$output .= '</div>';
	
	$output .= '<div class="small_post_wrapper post_wrapp_width2">';
		$output .= '<a style="display: block; line-height: 0;" href="'.$postsNeeded[$type6_index+1]['permalink'].'" title="'.esc_attr($postsNeeded[$type6_index+1]['title']).'" >';
			$output .= $postsNeeded[$type6_index+1]['PostSection22'];
		$output .= '</a>';
		$output .= '<div class="post-tags">';
			if ($postsNeeded[$type6_index+1]['post_alternative_cat_tag'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[$type6_index+1]['cat_id'].'">';
				$output .= esc_html($postsNeeded[$type6_index+1]['post_alternative_cat_tag']);
				$output .= '</span>';
			}
			else {
				if ($postsNeeded[$type6_index+1]['cat_name'] != '') {
					$output .= '<a href="'.$postsNeeded[$type6_index+1]['cat_link'].'" title="'.esc_attr($postsNeeded[$type6_index+1]['cat_name']).'" class="post_cat_tag category-bg-color-'.$postsNeeded[$type6_index+1]['cat_id'].'">';
					$output .= esc_html($postsNeeded[$type6_index+1]['cat_name']);
					$output .= '</a>';
				}	
			}
			if ($postsNeeded[$type6_index+1]['post_tag_new'] != '') {
				$output .= '<span class="post_cat_tag newtag">';
					$output .= esc_html($postsNeeded[$type6_index+1]['post_tag_new']);
				$output .= '</span>';
			}
			if ($postsNeeded[$type6_index+1]['format'] != '') {
				if ($postsNeeded[$type6_index+1]['format'] == 'image') { $postsNeeded[$type6_index+1]['format'] = 'picture'; }
				if ($postsNeeded[$type6_index+1]['format'] == 'gallery') { $postsNeeded[$type6_index+1]['format'] = 'camera'; }
				if ($postsNeeded[$type6_index+1]['format'] == 'audio') { $postsNeeded[$type6_index+1]['format'] = 'note'; }
				$output .= '<span class="icon-for-post-format icon-'.$postsNeeded[$type6_index+1]['format'].'"></span>';
			}
			if ($postsNeeded[$type6_index+1]['average_rate'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[$type6_index+1]['cat_id'].'">'.$postsNeeded[$type6_index+1]['average_rate'].'</span>';
			}
		$output .= '</div>';
		$output .= '<a href="'.$postsNeeded[$type6_index+1]['permalink'].'" title="'.esc_attr($postsNeeded[$type6_index+1]['title']).'" ><h1 class="post_cat_title_small">';
			$output .= esc_html($postsNeeded[$type6_index+1]['title']);
		$output .= '</h1></a>';
	$output .= '</div>';
	$output .= '</div>';


	$output .= '<div class="upright_post_wrapper post_wrapp_width1">';
		$output .= '<a style="display: block; line-height: 0;" href="'.$postsNeeded[$type6_index+2]['permalink'].'" title="'.esc_attr($postsNeeded[$type6_index+2]['title']).'" >';
			$output .= $postsNeeded[$type6_index+2]['PostSection11'];
		$output .= '</a>';
		$output .= '<div class="post-tags">';
			if ($postsNeeded[$type6_index+2]['post_alternative_cat_tag'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[$type6_index+2]['cat_id'].'">';
				$output .= esc_html($postsNeeded[$type6_index+2]['post_alternative_cat_tag']);
				$output .= '</span>';
			}
			else {
				if ($postsNeeded[$type6_index+2]['cat_name'] != '') {
					$output .= '<a href="'.$postsNeeded[$type6_index+2]['cat_link'].'" title="'.esc_attr($postsNeeded[$type6_index+2]['cat_name']).'" class="post_cat_tag category-bg-color-'.$postsNeeded[$type6_index+2]['cat_id'].'">';
					$output .= esc_html($postsNeeded[$type6_index+2]['cat_name']);
					$output .= '</a>';
				}	
			}
			if ($postsNeeded[$type6_index+2]['post_tag_new'] != '') {
				$output .= '<span class="post_cat_tag newtag">';
					$output .= esc_html($postsNeeded[$type6_index+2]['post_tag_new']);
				$output .= '</span>';
			}
			if ($postsNeeded[$type6_index+2]['format'] != '') {
				if ($postsNeeded[$type6_index+2]['format'] == 'image') { $postsNeeded[$type6_index+2]['format'] = 'picture'; }
				if ($postsNeeded[$type6_index+2]['format'] == 'gallery') { $postsNeeded[$type6_index+2]['format'] = 'camera'; }
				if ($postsNeeded[$type6_index+2]['format'] == 'audio') { $postsNeeded[$type6_index+2]['format'] = 'note'; }
				$output .= '<span class="icon-for-post-format icon-'.$postsNeeded[$type6_index+2]['format'].'"></span>';
			}
			if ($postsNeeded[$type6_index+2]['average_rate'] != '') {
				$output .= '<span class="post_cat_tag category-bg-color-'.$postsNeeded[$type6_index+2]['cat_id'].'">'.$postsNeeded[$type6_index+2]['average_rate'].'</span>';
			}
		$output .= '</div>';
		$output .= '<a href="'.$postsNeeded[$type6_index+2]['permalink'].'" title="'.esc_attr($postsNeeded[$type6_index+2]['title']).'" ><h1 class="post_cat_title_small">';
			$output .= esc_html($postsNeeded[$type6_index+2]['title']);
		$output .= '</h1></a>';
	$output .= '</div>';
}


		


$output .= "\n\t".'</div> ';

echo $output;