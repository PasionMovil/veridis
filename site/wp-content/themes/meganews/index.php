<?php get_header(); ?>			
		<div id="container"> <!-- start container -->	
			<div class="main-no-sidebar">
				<div class="inside-section">
		 		    <div class="vc_span8">
						<?php while ( have_posts() ) : the_post();  ?>					
							<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								<div class="entry-content-posts">
									<h1><a href="<?php the_permalink(); ?>" target="_self" title="<?php echo esc_attr(get_the_title()); ?>"><?php echo esc_html(get_the_title()); ?></a></h1>
									<?php the_content(); ?>
									<?php the_tags(); ?>
									<?php wp_link_pages(); ?>
								</div>
							</div>
						<?php
						endwhile; 
						?>	
						<div class="clear"></div>
						<div class="pagination-wrapper">
  				  			<div class="alignleft" style="margin-left: 10px; margin-bottom: 20px;"><?php previous_posts_link('&laquo; Previous Entries') ?></div>
							<div class="alignright" style="margin-right: 10px;  margin-bottom: 20px;"><?php next_posts_link('Next Entries &raquo;','') ?></div>
						</div>
					</div>
					<div class="vc_span4 sidebar">
						<div class="wpb_widgetised_column sidebar-left-border">		
							<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Blog Sidebar')) : else : ?>

							<?php endif; 	?>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>	
		</div><!-- end container -->	
		<div class="clear"></div>	
<?php get_footer(); ?>