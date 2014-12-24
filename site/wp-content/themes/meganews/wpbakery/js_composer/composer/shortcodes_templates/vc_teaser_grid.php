<?php

$title = $grid_columns_count = $grid_teasers_count = $grid_layout = $grid_link = $grid_link_target = $postsAverageReview = '';
$grid_template = $grid_thumb_size = $grid_posttypes = $grid_layout_mode = $grid_taxomonies = $grid_categories = $posts_in = $posts_not_in = '';
$grid_content = $el_class = $width = $orderby = $orderby1  = $order = $el_position = $isotope_item = $grid_button = $grid_teasers_count_pagination = '';

extract(shortcode_atts(array(
    'title' => '',
    'grid_columns_count' => 4,
    'grid_teasers_count' => 8,
    'grid_layout' => 'title_thumbnail_text', // title_thumbnail_text, thumbnail_title_text, thumbnail_text, thumbnail_title, thumbnail, title_text
    'grid_layout1' => 'thumbnail', // title_thumbnail_text, thumbnail_title_text, thumbnail_text, thumbnail_title, thumbnail, title_text
    'grid_link' => 'link_post', // link_post, link_image, link_image_post, link_no
    'grid_link_target' => '_self',
    'grid_template' => 'grid', //grid, carousel
    'grid_thumb_size' => 'full',
    'thumbnail_hover_icons' => 'linkpopup',
    'grid_posttypes' => '',
    'grid_taxomonies' => '',
    'grid_categories' => '',
    'grid_layout_mode' => 'fitRows',
    'posts_in' => '',
    'posts_not_in' => '',
    'grid_content' => 'teaser', // teaser, content
    'grid_button' => '', 
    'el_class' => '',
    'width' => '1/1',
    'orderby' => NULL,
    'orderby1' => NULL,
    'order' => 'DESC',
    'show_thumbnail' => '',
    'folio_related' => '',
    'grid_teasers_count_pagination' => '',
    'el_position' => '',
    'circle_date_show' => '',
	'title_on_thumb_show' => '',
	'comments_number_show' => '',
	'uner_thumb_date_show' => '',
	'post_title_under_thumb_show' => '',
	'excerpt_show' => '',
	'showtype' => '',
	'summary_length' => '',
	'grid_teasers_offset_number' => ''
), $atts));


	global $post;
	$post_idd = $post->ID;
	$page_url = get_permalink( $post_idd );
	
	$unique_id = rand(1, 10000);



	if ( $grid_template == 'grid' || $grid_template == 'filtered_grid') {
		wp_enqueue_style('isotope-css');
		wp_enqueue_script( 'isotope' );
		$isotope_item = 'isotope-item ';
	} else if ( $grid_template == 'carousel' ) {
		wp_enqueue_script( 'jcarousellite' );
		$isotope_item = '';
	}

	if ( $grid_link == 'link_image' || $grid_link == 'link_image_post' ) {
		wp_enqueue_script( 'prettyphoto' );
		wp_enqueue_style( 'prettyphoto' );
	}

	$output = '';

	$el_class = $this->getExtraClass( $el_class );
	$width = '';//wpb_translateColumnWidthToSpan( $width );
	$li_span_class = wpb_translateColumnsCountToSpanClass( $grid_columns_count );


	$query_args = array();

	$not_in = array();
	if ( $posts_not_in != '' ) {
		$posts_not_in = str_ireplace(" ", "", $posts_not_in);
		$not_in = explode(",", $posts_not_in);
	}

	$link_target = $grid_link_target=='_blank' ? ' target="_blank"' : '';


	//exclude current post/page from query
	if ( $posts_in == '' ) {
		global $post;
		array_push($not_in, $post->ID);
	}
	else if ( $posts_in != '' ) {
		$posts_in = str_ireplace(" ", "", $posts_in);
		$query_args['post__in'] = explode(",", $posts_in);
	}
	if ( $posts_in == '' || $posts_not_in != '' ) {
		$query_args['post__not_in'] = $not_in;
	}

	// Post teasers count
	if ( $grid_teasers_count != '' && !is_numeric($grid_teasers_count) ) $grid_teasers_count = -1;
	if ( $grid_teasers_count != '' && is_numeric($grid_teasers_count) ) $query_args['posts_per_page'] = $grid_teasers_count;

	// Post types
	$pt = array();
	if ( $grid_posttypes != '' ) {
		$grid_posttypes = explode(",", $grid_posttypes);
		foreach ( $grid_posttypes as $post_type ) {
			array_push($pt, $post_type);
		}
		$query_args['post_type'] = $pt;
	}
	
	if ($grid_teasers_count_pagination != '') {
		$paged = get_query_var('paged') ? get_query_var('paged') : 1;  
		$query_args['paged'] = $paged;
		$query_args['posts_per_page'] = $grid_teasers_count_pagination;
	}

	// Taxonomies

	$taxonomies = array();
	if ( $grid_taxomonies != '' ) {
		$grid_taxomonies = explode(",", $grid_taxomonies);
		foreach ( $grid_taxomonies as $taxom ) {
			array_push($taxonomies, $taxom);
		}
	}

	// Narrow by categories
	if ( $grid_categories != '' ) {
		$grid_categories = explode(",", $grid_categories);
		$gc = array();
		foreach ( $grid_categories as $grid_cat ) {
			array_push($gc, $grid_cat);
		}
		$gc = implode(",", $gc);
		////http://snipplr.com/view/17434/wordpress-get-category-slug/
		$query_args['category_name'] = $gc;

		$taxonomies = get_taxonomies('', 'object');
		$query_args['tax_query'] = array('relation' => 'OR');
		foreach ( $taxonomies as $t ) {
			if ( in_array($t->object_type[0], $pt) ) {
				$query_args['tax_query'][] = array(
					'taxonomy' => $t->name,//$t->name,//'portfolio_category',
					'terms' => $grid_categories,
					'field' => 'slug',
				);
			}
		}
	}

	// Order posts
	if ( $orderby1 != NULL ) {
		$query_args['orderby'] = $orderby1;
	}
	
	//	$query_args['offset'] = 3;
	if ($grid_teasers_offset_number != '') {
		if ($grid_teasers_count == '-1') {
			$my_query1 = new WP_Query($query_args);
			$count = $my_query1->post_count;
			$count = $count - $grid_teasers_offset_number + 1;
			$query_args['posts_per_page'] = $count;
			$query_args['offset'] = $grid_teasers_offset_number - 1;
		}	
		else {
			$query_args['offset'] = $grid_teasers_offset_number - 1;
		}
		
	}
		
		
		
	
	$query_args['order'] = $order;
	if ($showtype == 'type4') {
		
	//	$query_args['day'] = '10';
	}

	
	
	
	
	// Run query
	$my_query = new WP_Query($query_args);
	//global $_wp_additional_image_sizes;

	$teasers = '';
	$teasers2 = '';
	$teaser_categories = Array();
	if($grid_template == 'filtered_grid' && empty($grid_taxomonies)) {
		$taxonomies = get_object_taxonomies(!empty($query_args['post_type']) ? $query_args['post_type'] : get_post_types(array('public' => false, 'name' => 'attachment'), 'names', 'NOT'));
	}

	$posts_Ids = array();
	
	$post_count = 0;

	while ( $my_query->have_posts() ) {
		$link_title_start = $link_image_start = $p_link = $link_image_end = $p_img_large = '';

		$my_query->the_post();

		$posts_Ids[] = $my_query->post->ID;


		$categories_css = '';
		if( $grid_template == 'filtered_grid' ) {
			/** @var $post_cate``gories get list of categories */
			// $post_categories = get_the_category($my_query->post->ID);
			$post_categories = wp_get_object_terms($my_query->post->ID, 'category');
			if(!is_wp_error($post_categories)) {
				foreach($post_categories as $cat) {
					if(!in_array($cat->term_id, $teaser_categories)) {
						$teaser_categories[] = $cat->term_id;
					}
					$categories_css .= ' grid-cat-'.$cat->term_id;
				}
			}

		}
		$post_title = the_title("", "", false);
		$post_id = $my_query->post->ID;
		
		
		

		$teaser_post_type = 'posts_grid_teaser_'.$my_query->post->post_type . ' ';
		if($grid_content == 'teaser') {
			$content = apply_filters('the_excerpt', get_the_excerpt());
		} else {
			$content = get_the_content();
			$content = apply_filters('the_content', $content);
			$content = str_replace(']]>', ']]&gt;', $content);
		}

		// $content = ( $grid_content == 'teaser' ) ? apply_filters('the_excerpt', get_the_excerpt()) : get_the_content(); //TODO: get_the_content() rewrite more WP native way.
		$content = wpautop($content);
		$link = '';
		$button = '';
		$thumbnail = '';

		
		

		
		//get categorties and formt type
		$cats= get_the_category_list(', ','',$post_id);
		$format = get_post_format( $post_id );
		$icon = 'pencil';
		if ($format == 'image') { $icon = 'picture'; }
		if ($format == 'video') { $icon = 'video'; }
		if ($format == 'gallery') { $icon = 'docs'; }
		if ($format == 'quote') { $icon = 'quote'; }
		if ($format == 'link') { $icon = 'link'; }
		if ($format == 'audio') { $icon = 'note'; }
		//$data = get_option(OPTIONS);							
		//$dateFormat = $data['date_format'];	
		//$datum = get_the_date($dateFormat);
		$comments = get_comments_number( $post_id ); 
		
		$permalink = get_permalink($post_id);
		
		if ($format == 'link') {
			$permalink = get_post_meta($post_id , 'post_link' , true);
		}
		// Read more link
		if ( $grid_link != 'link_no' ) {
			$link = '<a class="more-link" href="'. $permalink .'"'.$link_target.' title="'. sprintf( esc_attr__( 'Permalink to %s', 'meganews' ), the_title_attribute( 'echo=0' ) ).'">'. __("Read more", "meganews") .'</a>';
			if ($grid_button != '') {
				$button = '<a class="post-button btn wpb_button wpb_btn_turquoise wpb_regularsize" href="'. $permalink .'"'.$link_target.' title="'. sprintf( esc_attr__( 'Permalink to %s', 'meganews' ), the_title_attribute( 'echo=0' ) ).'">'. $grid_button.'</a>';
			}
	   }
		
		
		
		$linkPostLink = get_post_meta($post_id , 'post_link' , true);
		

		// Thumbnail logic
		if ( in_array($grid_layout, array('title_thumbnail_text', 'thumbnail_title_text', 'thumbnail_text', 'thumbnail_title', 'thumbnail', 'title_text', 'thumbnail_title_text_details', 'title_thumbnail_text_details', 'title_thumbnail_details_text', 'extra_type') ) ) {
			$post_thumbnail = $p_img_large = '';
			//$attach_id = get_post_thumbnail_id($post_id);

			$post_thumbnail = wpb_getImageBySize(array( 'post_id' => $post_id, 'thumb_size' => $grid_thumb_size ));
			$thumbnail = $post_thumbnail['thumbnail'];
		
			
			$p_img_large = $post_thumbnail['p_img_large'];
		}

		// Link logic
		if ( $grid_link != 'link_no' ) {
			$p_video = '';
			if ( $grid_link == 'link_image' || $grid_link == 'link_image_post' ) {
				$p_video = get_post_meta($post_id, "_p_video", true);
			}

			if ( $grid_link == 'link_post' ) {
				$link_image_start = '<a class="link_image" href="'.$permalink.'"'.$link_target.' title="'.sprintf( esc_attr__( 'Permalink to %s', 'meganews' ), the_title_attribute( 'echo=0' ) ).'">';
				$link_title_start = '<a class="link_title" href="'.$permalink.'"'.$link_target.' title="'.sprintf( esc_attr__( 'Permalink to %s', 'meganews' ), the_title_attribute( 'echo=0' ) ).'">';
			}
			else if ( $grid_link == 'link_image' ) {
				if ( $p_video != "" ) {
					$p_link = $p_video;
				} else {
					$p_link = $p_img_large[0];
				}
				$link_image_start = '<a class="link_image prettyphoto" href="'.$p_link.'"'.$link_target.' title="'.the_title_attribute('echo=0').'">';
				$link_title_start = '<a class="link_title prettyphoto" href="'.$p_link.'"'.$link_target.' title="'.the_title_attribute('echo=0').'">';
			}
			else if ( $grid_link == 'link_image_post' ) {
				if ( $p_video != "" ) {
					$p_link = $p_video;
				} else {
					$p_link = $p_img_large[0];
				}
				$link_image_start = '<a class="link_image prettyphoto" href="'.$p_link.'"'.$link_target.' title="'.the_title_attribute('echo=0').'">';
				$link_title_start = '<a class="link_title" href="'.$permalink.'"'.$link_target.' title="'.sprintf( esc_attr__( 'Permalink to %s', 'meganews' ), the_title_attribute( 'echo=0' ) ).'">';
			}
			$link_title_end = '</a>';
			$link_image_end = '</a>';
		} else {
			$link_image_start = '';
			$link_title_start = '';
			$link_title_end = $link_image_end = '';
		}
		
			if (($show_thumbnail == 'yes')&&(($grid_template == 'grid')||($grid_template == 'filtered_grid'))) {
				if ($format == 'video') { 
					$link_image_start = '';
					$link_image_end = '';
					global $wp_embed;
					$linkVideo = get_post_meta($post_id , 'post_video_url' , true);
					$embed = $wp_embed->run_shortcode('[embed width="1080"]'.$linkVideo.'[/embed]');
					$thumbnail = '<div class="video-wrapper"><div class="video-container">'.$embed.'</div></div>';	
				} else			
				if ($format == 'audio') { 
					$link_image_start = '';
					$link_image_end = '';
					$post_audio = get_post_meta($post_id , 'post_audio' , true);									
					$thumbnail = $post_audio;	
				} else
				if ($format == 'image') { 			
					//$thumbnail = $post_thumbnail['thumbnail'];					
					$thumbnail = do_shortcode($post_thumbnail['thumbnail']).'<div class="post-overlay"></div><a class="post-details-link" href="'.$permalink.'"><span class="icons-post-thumb icon-plus"></span></a>';
					
					
				} else
				if ($format == 'quote') { 			
					$link_image_start = '';
					$link_image_end = '';
					$quote = get_post_meta($post_id , 'post_quote' , true);
					$thumbnail = '<div class="post-quote">'.$quote.'</div>';	
				}  
				else
				if ($format == 'gallery') { 			
					$link_image_start = '';
					$link_image_end = '';
					$quote = get_post_meta($post_id , 'post_quote' , true);	
					$thumbnail = '<div class="flexsliderFolio">';	
						$thumbnail .= '<ul class="slides">';
						$attachments = get_children(array('post_parent' => $post_id,
														'post_status' => 'inherit',
														'post_type' => 'attachment',
														'post_mime_type' => 'image',
														'order' => 'ASC',
														'orderby' => 'menu_order ID'));	
						foreach($attachments as $att_id => $attachment) {
							$full_img_url = wp_get_attachment_image_src($attachment->ID,'full', true);
							$thumbnail .= '<li><img src="'.$full_img_url[0].'" alt=""></li>';
						}
					
						$thumbnail .= '</ul>';	
					$thumbnail .= '</div>';	
				} 
				else 
				if ($format == 'link') {
				
					$thumbnail = do_shortcode($post_thumbnail['thumbnail']).'<div class="post-overlay"></div><a class="post-details-link" href="'.$linkPostLink.'"><span class="icons-post-thumb icon-plus"></span></a>';
					
				}
				
				else {
					$thumbnail = '';
				}
					
				
			}			
		if ($showtype == 'type3') { 
			$li_span_class = 'vc_span12 mb20';
		}
		if ($showtype == 'type4') { 
			$li_span_class = 'vc_span12 pb35';
		}
		
		$teasers .= '<li class="'.$isotope_item.apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $li_span_class, 'vc_teaser_grid_li').$categories_css.'">';
		if ($showtype == 'type1') {
			
			
			
			
			
						$post_alternative_cat_tag = get_post_meta($post->ID,'post_alternative_cat_tag', true);
						$post_tag_new= get_post_meta($post->ID,'post_tag_new', true);
						$postsAverageReview = get_post_average_review($post->ID);
						$post_categories = wp_get_post_categories( $post->ID );
						$format = get_post_format( $post->ID );	
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
							$cat_id = $cats[0]['id'];		
							$cat_name = $cats[0]['name'];	
							$cat_link =  get_category_link($cats[0]['id']);
						}

										
							$to_filter = '<div class="post-grid-thumb-type1-wrapper">';
								$to_filter .= '<div class="post-grid-thumb-type1">';
									$to_filter .= '<a href="'.get_permalink().'" target="_self" title="'.esc_attr(get_the_title()).'">';
										$to_filter .= do_shortcode($thumbnail);
									$to_filter .= '</a>';
									$to_filter .= '<div class="post-tags">';
										if ($post_alternative_cat_tag != '') {
											$to_filter .= '<span class="post_cat_tag category-bg-color-'.$cat_id.'">';
												$to_filter .=  esc_html($post_alternative_cat_tag);
											$to_filter .= '</span>';
										} else {
												if ($cat_name != '') {
													$to_filter .= '<a href="'.$cat_link.'" title="'.esc_attr($cat_name).'" class="post_cat_tag category-bg-color-'.$cat_id.'">';
													$to_filter .= esc_html($cat_name);
													$to_filter .= '</a>';
												}
											}	
										if ($post_tag_new != '') {
											$to_filter .= '<span class="post_cat_tag">';
												$to_filter .= esc_html($post_tag_new);
											$to_filter .= '</span>';
										}
										if ($format != '') {
												if ($format == 'image') { $format = 'picture'; }
												if ($format == 'gallery') { $format = 'camera'; }
												if ($format == 'audio') { $format = 'note'; }
												$to_filter .= '<span class="icon-for-post-format icon-'.$format.'"></span>';
										}
										if ($postsAverageReview != '') {
											$to_filter .= '<span class="post_cat_tag category-bg-color-'.$cat_id.'">'.$postsAverageReview.'</span>';
										}
									$to_filter .= '</div>';
								$to_filter .= '</div>';
								$to_filter .= '<div class="post-grid-type1-details" style="margin-left: 0;">';
									$to_filter .= '<div class="post-grid-type1-date-comment">';
										$to_filter .= '<div class="post-date">'.get_the_date('M d, Y').'</div>';
										$to_filter .= '<div class="post-comments-number"><span class="icons-comments icon-comment"></span><a href="'.get_comments_link().'" >'.get_comments_number($post->ID).'</a></div>';
									$to_filter .= '</div>';
									$to_filter .= '<h1 class="post-title">';
										$to_filter .= '<a class="category-hover-color-'.$cat_id.'" href="'.get_permalink().'" target="_self" title="'.esc_attr(get_the_title()).'">';
											$to_filter .= esc_html(get_the_title());
										$to_filter .= '</a>';
									$to_filter .= '</h1>';
									if ($summary_length != '') {
										$postSummary = get_the_excerpt();
										if (strlen($postSummary) > $summary_length)
										{
										$postSummary = substr($postSummary,0,$summary_length).'...';
										}
										$to_filter .= '<div class="excerpt">'.$postSummary.'</div>';
									}
									else {
										$to_filter .= '<div class="excerpt">'.get_the_excerpt().'</div>';
									}
								$to_filter .= '</div>';
							$to_filter .= '</div>';
						
							
					$teasers .= apply_filters('vc_teaser_grid_content', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "content" => $content, "media_link" => $p_link) );
				}
				
			if ($showtype == 'type2') {
						$post_alternative_cat_tag = get_post_meta($post->ID,'post_alternative_cat_tag', true);
						$post_tag_new= get_post_meta($post->ID,'post_tag_new', true);
						$post_categories = wp_get_post_categories( $post->ID );
						$format = get_post_format( $post->ID );	
						$postsAverageReview = get_post_average_review($post->ID);
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
							$cat_id = $cats[0]['id'];		
							$cat_name = $cats[0]['name'];	
							$cat_link =  get_category_link($cats[0]['id']);
						}
							
						$post_count++;
						$to_filter= '';
						if ($post_count == 1) {			
							$to_filter = '<div class="post-grid-thumb-type1-wrapper">';
								$to_filter .= '<div class="post-grid-thumb-type1">';
									$to_filter .= '<a href="'.get_permalink().'" target="_self" title="'.esc_attr(get_the_title()).'">';
										$to_filter .= do_shortcode($thumbnail);
									$to_filter .= '</a>';
									$to_filter .= '<div class="post-tags">';
										if ($post_alternative_cat_tag != '') {
											$to_filter .= '<span class="post_cat_tag category-bg-color-'.$cat_id.'">';
												$to_filter .=  esc_html($post_alternative_cat_tag);
											$to_filter .= '</span>';
										} else {
												if ($cat_name != '') {
													$to_filter .= '<a href="'.$cat_link.'" title="'.esc_attr($cat_name).'" class="post_cat_tag category-bg-color-'.$cat_id.'">';
													$to_filter .= esc_html($cat_name);
													$to_filter .= '</a>';
												}
											}	
										if ($post_tag_new != '') {
											$to_filter .= '<span class="post_cat_tag">';
												$to_filter .= esc_html($post_tag_new);
											$to_filter .= '</span>';
										}
										if ($format != '') {
												if ($format == 'image') { $format = 'picture'; }
												if ($format == 'gallery') { $format = 'camera'; }
												if ($format == 'audio') { $format = 'note'; }
												$to_filter .= '<span class="icon-for-post-format icon-'.$format.'"></span>';
										}
										if ($postsAverageReview != '') {
											$to_filter .= '<span class="post_cat_tag category-bg-color-'.$cat_id.'">'.$postsAverageReview.'</span>';
										}
									$to_filter .= '</div>';
								$to_filter .= '</div>';
								$to_filter .= '<div class="post-grid-type1-details" style="margin-left: 0;">';
									$to_filter .= '<div class="post-details-type3-first-line">';
										$to_filter .= '<div class="post-date">'.get_the_date('M d, Y').'</div>';
										$to_filter .= '<div class="post-comments-number"><span class="icons-comments icon-comment"></span><a href="'.get_comments_link().'" >'.get_comments_number($post->ID).'</a></div>';
									$to_filter .= '</div>';
									$to_filter .= '<h1 class="post-title">';
										$to_filter .= '<a class="category-hover-color-'.$cat_id.'" href="'.get_permalink().'" target="_self" title="'.esc_attr(get_the_title()).'">';
											$to_filter .= esc_html(get_the_title());
										$to_filter .= '</a>';
									$to_filter .= '</h1>';
									if ($summary_length != '') {
										$postSummary = get_the_excerpt();
										if (strlen($postSummary) > $summary_length)
										{
										$postSummary = substr($postSummary,0,$summary_length).'...';
										}
										$to_filter .= '<div class="excerpt">'.$postSummary.'</div>';
									}
									else {
										$to_filter .= '<div class="excerpt">'.get_the_excerpt().'</div>';
									}
								$to_filter .= '</div>';
							$to_filter .= '</div>';
							$to_filter .= '<div class="border-div"></div>';
							$to_filter .= '<div class="clear"></div>';
							
						}
						else {
							if ($post_count == 2) {
								$to_filter .= '<ul class="post-grid-thumb-list">';
									$to_filter .= '<li>';
										$post_thumbnail = wpb_getImageBySize(array( 'post_id' => $post_id, 'thumb_size' => '65x50' ));
										$thumbnail = $post_thumbnail['thumbnail'];
										$to_filter .= '<a href="'.get_permalink().'" target="_self" title="'.esc_attr(get_the_title()).'">';
											$to_filter .= do_shortcode($thumbnail);
										$to_filter .= '</a>';
										$to_filter .= '<div class="post-grid-details">';
											$to_filter .= '<div class="post-date">'.get_the_date('M d, Y').'</div>';
											$to_filter .= '<h1 class="post-title">';
												$to_filter .= '<a class="category-hover-color-'.$cat_id.'" href="'.get_permalink().'" target="_self" title="'.esc_attr(get_the_title()).'">';
													$to_filter .= esc_html(get_the_title());
												$to_filter .= '</a>';
											$to_filter .= '</h1>';
										$to_filter .= '</div>';
									$to_filter .= '</li>';
							} else {
									$to_filter .= '<li>';
										$post_thumbnail = wpb_getImageBySize(array( 'post_id' => $post_id, 'thumb_size' => '65x50' ));
										$thumbnail = $post_thumbnail['thumbnail'];
										$to_filter .= '<a href="'.get_permalink().'" target="_self" title="'.esc_attr(get_the_title()).'">';
											$to_filter .= do_shortcode($thumbnail);
										$to_filter .= '</a>';
										$to_filter .= '<div class="post-grid-details">';
											$to_filter .= '<div class="post-date">'.get_the_date('M d, Y').'</div>';
											$to_filter .= '<h1 class="post-title">';
												$to_filter .= '<a class="category-hover-color-'.$cat_id.'" href="'.get_permalink().'" target="_self" title="'.esc_attr(get_the_title()).'">';
													$to_filter .= esc_html(get_the_title());
												$to_filter .= '</a>';
											$to_filter .= '</h1>';
										$to_filter .= '</div>';
									$to_filter .= '</li>';
							}
							if ($post_count == $grid_teasers_count) {
								$to_filter .= '</ul>';
							}
							
						}
							
					$teasers2 .= apply_filters('vc_teaser_grid_content', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "content" => $content, "media_link" => $p_link) );
				}
				
			if ($showtype == 'type3') {
			
			
			
			
			
						$post_alternative_cat_tag = get_post_meta($post->ID,'post_alternative_cat_tag', true);
						$post_tag_new= get_post_meta($post->ID,'post_tag_new', true);
						$post_categories = wp_get_post_categories( $post->ID );
						$format = get_post_format( $post->ID );	
						$postsAverageReview = get_post_average_review($post->ID);
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
							$cat_id = $cats[0]['id'];		
							$cat_name = $cats[0]['name'];	
							$cat_link =  get_category_link($cats[0]['id']);
						}

						$to_filter = '<div class="post-grid-thumb-type3-wrapper">';
								$to_filter .= '<div class="post-grid-thumb-type3">';
									$to_filter .= '<a href="'.get_permalink().'" target="_self" title="'.esc_attr(get_the_title()).'">';
										$to_filter .= do_shortcode($thumbnail);
									$to_filter .= '</a>';
									
									
									
									$to_filter .= '<div class="post-tags">';
										if ($post_alternative_cat_tag != '') {
											$to_filter .= '<span class="post_cat_tag category-bg-color-'.$cat_id.'">';
												$to_filter .=  esc_html($post_alternative_cat_tag);
											$to_filter .= '</span>';
										} else {
												if ($cat_name != '') {
													$to_filter .= '<a href="'.$cat_link.'" title="'.esc_attr($cat_name).'" class="post_cat_tag category-bg-color-'.$cat_id.'">';
													$to_filter .= esc_html($cat_name);
													$to_filter .= '</a>';
												}
											}	
										if ($post_tag_new != '') {
											$to_filter .= '<span class="post_cat_tag">';
												$to_filter .= esc_html($post_tag_new);
											$to_filter .= '</span>';
										}
										if ($format != '') {
												if ($format == 'image') { $format = 'picture'; }
												if ($format == 'gallery') { $format = 'camera'; }
												if ($format == 'audio') { $format = 'note'; }
												$to_filter .= '<span class="icon-for-post-format icon-'.$format.'"></span>';
										}
										if ($postsAverageReview != '') {
											$to_filter .= '<span class="post_cat_tag category-bg-color-'.$cat_id.'">'.$postsAverageReview.'</span>';
										}
									$to_filter .= '</div>';
									
									
								$to_filter .= '</div>';
								$to_filter .= '<div class="post-details-type3">';
									$to_filter .= '<div class="post-details-type3-date-comment">';
										$to_filter .= '<div class="post-date">'.get_the_date('M d, Y').'</div>';
										$to_filter .= '<div class="post-comments-number"><span class="icons-comments icon-comment"></span><a href="'.get_comments_link().'" >'.get_comments_number($post->ID).'</a></div>';
									$to_filter .= '</div>';
									$to_filter .= '<br />';
									$to_filter .= '<h1 class="post-title">';
										$to_filter .= '<a class="category-hover-color-'.$cat_id.'" href="'.get_permalink().'" target="_self" title="'.esc_attr(get_the_title()).'">';
											$to_filter .= esc_html(get_the_title());
										$to_filter .= '</a>';
									$to_filter .= '</h1>';
									if ($summary_length != '') {
										$postSummary = get_the_excerpt();
										if (strlen($postSummary) > $summary_length)
										{
										$postSummary = substr($postSummary,0,$summary_length).'...';
										}
										$to_filter .= '<div class="excerpt">'.$postSummary.'</div>';
									}
									else {
										$to_filter .= '<div class="excerpt">'.get_the_excerpt().'</div>';
									}
								$to_filter .= '</div>';
						$to_filter .= '</div>';
						$to_filter .= '<div class="clear"></div>';
							
					$teasers .= apply_filters('vc_teaser_grid_content', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "content" => $content, "media_link" => $p_link) );
				}
									
	
	
				if ($showtype == 'type4') {
						$grid_template = 'filtered_grid';
						$postsAverageReview = get_post_average_review($post->ID);
						$post_alternative_cat_tag = get_post_meta($post->ID,'post_alternative_cat_tag', true);
						$post_tag_new= get_post_meta($post->ID,'post_tag_new', true);
						$post_categories = wp_get_post_categories( $post->ID );
						$format = get_post_format( $post->ID );	
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
							$cat_id = $cats[0]['id'];		
							$cat_name = $cats[0]['name'];	
							$cat_link =  get_category_link($cats[0]['id']);
						}

						$to_filter = '<div class="post-grid-thumb-type4-wrapper">';
							$to_filter .= '<div class="vertical-border"></div>';
							$to_filter .= '<div class="post-grid-thumb-type4-time">';
								$to_filter .= '<div class="post-grid-thumb-type4-time-inside">'.get_the_date('G:i').'</div>';
							$to_filter .= '</div>';
								$to_filter .= '<div class="post-grid-thumb-type4">';
									$to_filter .= '<a href="'.get_permalink().'" target="_self" title="'.esc_attr(get_the_title()).'">';
										$to_filter .= do_shortcode($thumbnail);
									$to_filter .= '</a>';
									
									
									
									$to_filter .= '<div class="post-tags">';
										if ($post_alternative_cat_tag != '') {
											$to_filter .= '<span class="post_cat_tag category-bg-color-'.$cat_id.'">';
												$to_filter .=  esc_html($post_alternative_cat_tag);
											$to_filter .= '</span>';
										} else {
												if ($cat_name != '') {
													$to_filter .= '<a href="'.$cat_link.'" title="'.esc_attr($cat_name).'" class="post_cat_tag category-bg-color-'.$cat_id.'">';
													$to_filter .= esc_html($cat_name);
													$to_filter .= '</a>';
												}
											}	
										if ($post_tag_new != '') {
											$to_filter .= '<span class="post_cat_tag">';
												$to_filter .= esc_html($post_tag_new);
											$to_filter .= '</span>';
										}
										if ($format != '') {
												if ($format == 'image') { $format = 'picture'; }
												if ($format == 'gallery') { $format = 'camera'; }
												if ($format == 'audio') { $format = 'note'; }
												$to_filter .= '<span class="icon-for-post-format icon-'.$format.'"></span>';
										}
										if ($postsAverageReview != '') {
											$to_filter .= '<span class="post_cat_tag category-bg-color-'.$cat_id.'">'.$postsAverageReview.'</span>';
										}
									$to_filter .= '</div>';
									
									
								$to_filter .= '</div>';
								$to_filter .= '<div class="post-details-type4">';
										$to_filter .= '<div class="post-date">'.get_the_date('M d, Y').'</div>';
										$to_filter .= '<div class="post-comments-number"><span class="icons-comments icon-comment"></span><a href="'.get_comments_link().'" >'.get_comments_number($post->ID).'</a></div>';
									
									$to_filter .= '<br />';
									$to_filter .= '<h1 class="post-title">';
										$to_filter .= '<a class="category-hover-color-'.$cat_id.'" href="'.get_permalink().'" target="_self" title="'.esc_attr(get_the_title()).'">';
											$to_filter .= esc_html(get_the_title());
										$to_filter .= '</a>';
									$to_filter .= '</h1>';
									if ($summary_length != '') {
										$postSummary = get_the_excerpt();
										if (strlen($postSummary) > $summary_length)
										{
										$postSummary = substr($postSummary,0,$summary_length).'...';
										}
										$to_filter .= '<div class="excerpt">'.$postSummary.'</div>';
									}
									else {
										$to_filter .= '<div class="excerpt">'.get_the_excerpt().'</div>';
									}
								$to_filter .= '</div>';
						$to_filter .= '</div>';
						$to_filter .= '<div class="clear"></div>';
							
					$teasers .= apply_filters('vc_teaser_grid_content', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "content" => $content, "media_link" => $p_link) );
				}
							
	
	

	
		
		
					
				

	
	
	
	
	
	
		
		$teasers .= '</li> ' . $this->endBlockComment('single teaser');
	} // endwhile loop
	
	
	if ($grid_teasers_count_pagination != '') {
		$allPostPages = $my_query->max_num_pages;
		$currentPostPage = $paged;	
		$output .= '<div class="nav_posts">';					
			if ($currentPostPage < $allPostPages ) {
				$output .= '<div class="nav-previous"><a href="'.$page_url.'page/'.($currentPostPage + 1).'" target="_self">Next page <span class="meta-nav">&rarr;</span></a></div>';
			}	
			$output .='<div class="nav-next">' . get_previous_posts_link( __( '<span class="meta-nav">&larr;</span> Previous page ' ) ) . '</div>';	
			$output .= '<div class="clear"></div>';	
		$output .= '</div>';	
	}	
	
	wp_reset_query();

	if( $grid_template == 'filtered_grid' && $teasers && !empty($teaser_categories)) {
		/*
		$categories_list = wp_list_categories(array(
			'orderby' => 'name',
			'walker' => new Teaser_Grid_Category_Walker(),
			'include' => implode(',', $teaser_categories),
			'show_option_none'   => __('No categories', 'meganews'),
			'echo' => false
		));
		*/
		$categories_array = get_terms('category', array(
			'orderby' => 'name',
			'include' => implode(',', $teaser_categories)
		));

		$categories_list_output = '<ul class="categories_filter clearfix">';
		$categories_list_output .= '<li class="active"><a href="#" data-filter="*">' . __('All', 'meganews') . '</a></li>';
		if(!is_wp_error($categories_array)) {
			foreach($categories_array as $cat) {
			
			$postsInCat = get_term_by('name',$cat->name,'category');
			$postsInCat = $postsInCat->count;
			
			
				$categories_list_output .= '<li class="filter-separator">/</li><li><a href="#" data-filter=".grid-cat-'.$cat->term_id.'">' . esc_attr($cat->name).'</a></li>';
			}
		}
		$categories_list_output.= '</ul><div class="filter-underline"></div><div class="clearfix"></div>';
	} else {
		$categories_list_output = '';
	}
	
	$extraClassWidth = '';
	if ($showtype == 'type4') {
		$extraClassWidth = ' trype4width';
	}
	if ( $teasers ) { $teasers = '<div class="teaser_grid_container">'.$categories_list_output.'<ul class="wpb_thumbnails wpb_thumbnails-fluid clearfix'.$extraClassWidth.'" data-layout-mode="'.$grid_layout_mode.'">'. $teasers .'</ul></div>'; }
	else { $teasers = __("Nothing found." , "meganews"); }

	$posttypes_teasers = '';
	
	
	
	
	
	
	



	if ( is_array($grid_posttypes) ) {
		//$posttypes_teasers_ar = explode(",", $grid_posttypes);
		$posttypes_teasers_ar = $grid_posttypes;
		foreach ( $posttypes_teasers_ar as $post_type ) {
			$posttypes_teasers .= 'wpb_teaser_grid_'.$post_type . ' ';
		}
	}

	$grid_class = 'wpb_'.$grid_template . ' columns_count_'.$grid_columns_count . ' grid_layout-'.$grid_layout . ' '  . $grid_layout.'_'.$li_span_class . ' ' . 'columns_count_'.$grid_columns_count.'_'.$grid_layout . ' ' . $posttypes_teasers;
	$css_class =  apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_teaser_grid wpb_content_element '.$grid_class.$width.$el_class, $this->settings['base']);

	$output .= "\n\t".'<div class="'.$css_class.'">';
	$output .= "\n\t\t".'<div class="wpb_wrapper">';
	//$output .= ($title != '' ) ? "\n\t\t\t".'<h2 class="wpb_heading wpb_teaser_grid_heading">'.$title.'</h2>' : '';
	$output .= wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_teaser_grid_heading'));
	if ( $grid_template == 'carousel' ) {
		$output .= apply_filters( 'vc_teaser_grid_carousel_arrows', '<div class="carousel-nav"><a href="#" class="prev">&larr;</a> <a href="#" class="next">&rarr;</a></div>' );
	}
	
	
	if ($showtype == 'type2') {
		$output .= $teasers2;
	}
	else {
		$output .= $teasers;
	}

	
	$output .= "\n\t\t".'</div> '.$this->endBlockComment('.wpb_wrapper');
	$output .= "\n\t".'</div> '.$this->endBlockComment('.wpb_teaser_grid');



	echo $output;
