<?php get_header(); ?>			
		<div id="container"> <!-- start container -->	
			<?php pego_display_breadcrumbs(); ?>	
		<?php

	$cat_id = get_query_var('cat');
	$pego_cat_color = get_tax_meta($cat_id,'pego_category_color');
	$pego_category_show_type= get_tax_meta($cat_id,'pego_category_show_type');
	$category_featured_post_section= get_tax_meta($cat_id,'pego_category_featured_post_section');
	$category_show_latest = get_tax_meta($cat_id,'pego_category_show_latest');
	$category_sidebar = get_tax_meta($cat_id,'pego_category_sidebar');
	$allSidebarss = pego_get_all_sidebars();
	$sidebar_selected = '';
	if ($category_sidebar != '') {
		$sidebar_selected =  $allSidebarss[$category_sidebar];
	}
	?> 
<style> 
.pagination a:hover {
    color: <?php echo $pego_cat_color; ?>;
}
.pagination .current {
    border: 1px solid #222222;
    color: #222222;
    margin-top: -1px;
}
 </style> 
 	<?php
 	$post_counter = 0;
 	echo do_shortcode('[vc_row][vc_column width="1/1"][/vc_column][/vc_row]'); 
	if (($category_featured_post_section != '')&&($category_featured_post_section != 'none')) {
		echo do_shortcode('[vc_row][vc_column width="1/1"][vc_post_section post_section_type="'.$category_featured_post_section.'"  postfill="latest" cat='.$cat_id.'][/vc_column][/vc_row]'); 
	}
	if ($category_show_latest == 'on') {
		$argsPosts= array('post_type'=> 'post', 'posts_per_page' => 10, 'order'=> 'DESC', 'orderby' => 'post_date'  );
		$allPosts = get_posts($argsPosts);
		
		if($allPosts) {
			echo '<ul class="news-ticker">';
			foreach($allPosts as $singlePost)
			{ 	
				echo '<li><span>'.get_the_time('Y/m/d g:i A', $singlePost->ID ).'</span><a title="'.$singlePost->post_title.'" href="'.get_permalink( $singlePost->ID ).'">'.$singlePost->post_title.'</a></li>';
			}
			echo '</ul>';
		}
	}
	?>	
	
			<?php
			if(( is_category())||(is_tax('portfolio_categories'))){  ?> 
  	 		<h1 class="main-titles"><?php _e('Category: ','meganews'); ?><span class="category-color-<?php echo $cat_id; ?>"><?php single_cat_title(); ?></span></h1>
  	 		<?php
			/* If this is a tag archive */ } elseif( is_tag() ) { ?>					
			<h1 class="main-titles"><?php _e('Posts Tagged: ','meganews'); ?><span class="category-color"><?php single_tag_title(); ?></span></h1>
  	 		<?php
		    /* If this is a daily archive */ } elseif (is_day()) { ?>				
			<h1 class="main-titles"><?php _e('Archive for: ','meganews'); ?><span class="category-color"><?php the_time('F jS, Y'); ?></span></h1>
  	 		<?php
			 /* If this is a monthly archive */ } elseif (is_month()) { ?>	
			<h1 class="main-titles"><?php _e('Archive for: ','meganews'); ?><span class="category-color"><?php the_time('F Y'); ?></span></h1>
  	 		<?php
		    /* If this is a yearly archive */ } elseif (is_year()) { ?>			
			<h1 class="main-titles"><?php _e('Archive for: ','meganews'); ?><span class="category-color"><?php the_time('Y'); ?></span></h1>
  	 		<?php
			 /* If this is an author archive */ } elseif (is_author()) { ?>	
			<h1 class="main-titles"><?php _e('Author Archive: ','meganews'); ?></h1>
  	 		<?php
			/* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>							
			<h1 class="main-titles"><?php _e('Blog Archive: ','meganews'); ?></h1>
			<?php
			}
			?>
	
	
	<div class="main-no-sidebar">
		<div class="inside-section">
		  <div class="vc_span8 <?php echo $pego_category_show_type; ?> ">
		 <?php if ($pego_category_show_type == 'type2') { echo '<div class="single-post-thumb-wrapper-type-two">'; } ?>
			<?php while ( have_posts() ) : the_post();  ?>					
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php
						$imagethumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'Post1' );
						if ($pego_category_show_type == 'type2') {
							$post_counter++;
							
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
							}
							$cat_id = $cats[0]['id'];		
							$cat_name = $cats[0]['name'];	
							$cat_link =  get_category_link($cats[0]['id']);
						}
						$summary_length = 115;				
							
					?>						
							<div class="single-post-thumb">
								<div class="post-thumb">
									<a href="<?php the_permalink(); ?>" target="_self" title="<?php echo esc_attr(get_the_title()); ?>">
										<img src="<?php echo $imagethumb[0]; ?>" />
									</a>
									<div class="post-tags">
									<?php
										if ($post_alternative_cat_tag != '') {
										?>
											<span class="post_cat_tag category-bg-color-<?php echo $cat_id; ?>">
											<?php echo $post_alternative_cat_tag; ?>
											</span>
										<?php
										}
										else {
											if ($cat_name != '') {
											?>
												<a href="<?php echo $cat_link; ?>" class="post_cat_tag category-bg-color-<?php echo $cat_id; ?>">
												<?php echo $cat_name; ?>
												</a>
											<?php
											}				
										}
										if ($post_tag_new != '') {
										?>
											<span class="post_cat_tag">
												<?php echo $post_tag_new; ?>
											</span>
										<?php
										}
										if ($format != '') {
											if ($format == 'image') { $format = 'picture'; }
											if ($format == 'gallery') { $format = 'camera'; }
											if ($format == 'audio') { $format = 'note'; }
											?>
											<span class="icon-for-post-format icon-<?php echo $format; ?>"></span>
											<?php
										}
										
										if ($postsAverageReview != '') {
											?>
											<span class="post_cat_tag category-bg-color-<?php echo $cat_id; ?>"><?php echo $postsAverageReview; ?></span>
											<?php
										}
										?>
									</div>	
								</div>
								<div class="post-details">
									<div class="post-grid-type1-date-comment-archive">
										<div class="post-date"><?php echo get_the_date('M d, Y'); ?></div>
										<div class="post-comments-number"><span class="icons-comments icon-comment"></span><a href="<?php comments_link(); ?>" ><?php echo get_comments_number($post->ID); ?></a></div>
									</div>
									<h1 class="post-title">
										<a class="category-hover-color-<?php echo $cat_id; ?>" href="<?php the_permalink(); ?>" target="_self" title="<?php echo esc_attr(get_the_title()); ?>">
											<?php the_title(); ?>
										</a>
									</h1>
									<?php									
									if ($summary_length != '') {
										$postSummary = get_the_excerpt();
										if (strlen($postSummary) > $summary_length)
										{
										$postSummary = substr($postSummary,0,$summary_length).'...';
										}
										echo'<div class="excerpt">'.$postSummary.'</div>';
									}
									else {
										echo '<div class="excerpt">'.get_the_excerpt().'</div>';
									}
									?>
								</div>
								<div class="clear"></div>
							</div>
							<?php
							if (($post_counter % 2) == 0) {
								echo '<div class="clear"></div>';
							}
							?>
						
					<?php
						}
						else {
							$summary_length = 150;
							$post_alternative_cat_tag = get_post_meta($post->ID,'post_alternative_cat_tag', true);
							$post_tag_new= get_post_meta($post->ID,'post_tag_new', true);
							$post_categories = wp_get_post_categories( $post->ID );
							$postsAverageReview = get_post_average_review($post->ID);
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

							$to_filter = '<div class="post-grid-thumb-type3-wrapper" style="margin-bottom: 20px;">';
								$to_filter .= '<div class="post-grid-thumb-type3">';
									$to_filter .= '<a href="'.get_permalink().'" target="_self" title="'.esc_attr(get_the_title()).'">';
									$to_filter .= '<img src="'.$imagethumb[0].'" />';
									$to_filter .= '</a>';
									
									
									
									$to_filter .= '<div class="post-tags">';
										if ($post_alternative_cat_tag != '') {
											$to_filter .= '<span class="post_cat_tag category-bg-color-'.$cat_id.'">';
												$to_filter .=  $post_alternative_cat_tag;
											$to_filter .= '</span>';
										} else {
												if ($cat_name != '') {
													$to_filter .= '<a href="'.$cat_link.'" class="post_cat_tag category-bg-color-'.$cat_id.'">';
													$to_filter .= $cat_name;
													$to_filter .= '</a>';
												}
											}	
										if ($post_tag_new != '') {
											$to_filter .= '<span class="post_cat_tag">';
												$to_filter .= $post_tag_new;
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
								$to_filter .= '<div class="clear"></div>';
						$to_filter .= '</div>';
							$post_counter++;
							if (($post_counter % 2) == 0) {
								$to_filter .= '<div class="clear-later"></div>';
							}
							echo $to_filter;
						}
					?>						
								
			</div>
							
			<?php
			endwhile; 
			?>
			<div class="pagination-wrapper">
  				  <?php pego_kriesi_pagination();?>
  			</div>
  		<?php if ($pego_category_show_type == 'type2') {echo '</div>';} ?>			
		</div>
		
		
		<div class="vc_span4 sidebar">
			<div class="wpb_widgetised_column sidebar-left-border">
		<?php
		if ($sidebar_selected != '') {
			if (function_exists('dynamic_sidebar') && dynamic_sidebar($sidebar_selected)) : else : ?>

			<?php endif; 

		} else 
		{
			if (function_exists('dynamic_sidebar') && dynamic_sidebar('Blog Sidebar')) : else : ?>

			<?php endif; 
			
		}
		?>
		</div>
		</div>
		</div>
		<div class="clear"></div>
	
</div><!-- end container -->		
<?php get_footer(); ?>