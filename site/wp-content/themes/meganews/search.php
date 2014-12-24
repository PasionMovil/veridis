<?php get_header(); ?>			
		<div id="container"> <!-- start container -->	
			<?php pego_display_breadcrumbs(); ?>	
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/wpbakery/js_composer/assets/js/js_composer_front.js"></script>	
	<link rel="stylesheet" id="composer-css" href="<?php echo get_template_directory_uri(); ?>/wpbakery/js_composer/assets/css/js_composer_front.css" type="text/css" media="screen">
	<?php
	$cat_id = get_query_var('cat');
	$pego_cat_color = get_tax_meta($cat_id,'pego_category_color');
	$pego_category_show_type= get_tax_meta($cat_id,'pego_category_show_type');
	$category_featured_post_section= get_tax_meta($cat_id,'pego_category_featured_post_section');
	$category_show_latest = get_tax_meta($cat_id,'pego_category_show_latest');
	$category_sidebar = get_tax_meta($cat_id,'pego_category_sidebar');
	$allSidebarss = pego_get_all_sidebars();
	$sidebar_selected =  $allSidebarss[$category_sidebar];
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
				echo '<li><span>'.get_the_time('Y/m/d g:i A', $singlePost->ID ).'</span><a title="'.esc_attr($singlePost->post_title).'" href="'.get_permalink( $singlePost->ID ).'">'.esc_html($singlePost->post_title).'</a></li>';
			}
			echo '</ul>';
		}
	}
	?>	
	
	<?php printf( __( '<h1 class="main-titles">Search Results for: <span class="category-color">%s</span></h1>', 'meganews' ),  get_search_query() ); ?>
	
	
			<div class="main-no-sidebar">
				<div class="inside-section">
		  			<div class="vc_span8" style="min-height: 1px;">
		<?php
		
			echo '<div class="single-post-thumb-wrapper-type-two" >';
			$post_counter = 0;
		
		
			while ( have_posts() ) : the_post();  ?>					
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php
					if (get_post_type( $post ) == 'post') {
						$imagethumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'Post1' );
					
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
										
							
						?>						
							<div class="single-post-thumb">
								<div class="post-thumb">
									<a href="<?php the_permalink(); ?>" target="_self" title="<?php echo esc_attr(the_title()); ?>">
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
									<div class="post-date"><?php echo get_the_date('M d, Y'); ?></div>
									<div class="post-comments-number" style="line-height: 1.9;"><span class="icons-comments icon-comment"></span><a href="<?php comments_link(); ?>" ><?php echo get_comments_number($post->ID); ?></a></div>
									<h1 class="post-title">
										<a class="category-hover-color-<?php echo $cat_id; ?>" href="<?php the_permalink(); ?>" target="_self" title="<?php echo esc_attr(get_the_title()); ?>">
											<?php echo esc_html(get_the_title()); ?>
										</a>
									</h1>									
									<div class="excerpt"><?php the_excerpt(); ?></div>
								</div>
								<div class="clear"></div>
							</div>
							<?php
							if (($post_counter % 2) == 0) {
								echo '<div class="clear"></div>';
							}
							
						}
							?>
								
			</div>
							
			<?php
			endwhile; 
			
			if ($post_counter == 0 ) {
			?>
				<div class="search-empty"><h2><?php _e('Nothing found! ','meganews'); ?></h2></div>
			<?php
			}
			?>
			<div class="pagination-wrapper">
  				  <?php pego_kriesi_pagination();?>
  			</div>			
		</div>
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
		<div class="clear"></div>
	</div>	
	</div>
	<div class="clear"></div>
</div><!-- end container -->		
<?php get_footer(); ?>