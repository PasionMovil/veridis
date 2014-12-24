<?php get_header(); ?>			
		<div id="container"> <!-- start container -->	
			<?php
			$breadcrumbsShow = get_post_meta($post->ID , 'pego_page_show_breadcrumbs' , true);
			if ($breadcrumbsShow != 'No') {
			 	pego_display_breadcrumbs(); 
			}
			?>
					<?php while ( have_posts() ) : the_post();  ?>
							<div class="main-no-sidebar">
								<?php the_content(); ?>
							</div>
					<?php
					endwhile; 
					?>						
		</div><!-- end container -->		
<?php get_footer(); ?>