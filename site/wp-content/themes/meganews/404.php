<?php get_header(); ?>			
		<div id="container"> <!-- start container -->	
			<?php pego_display_breadcrumbs(); ?>	
							<div class="main-no-sidebar">
								<?php 
										
									if ( function_exists( 'ot_get_option' ) ) {
											$error_page_id = ot_get_option('meganews_404_page'); 
											echo apply_filters('the_content', get_post_field('post_content', $error_page_id));	
									}
								 ?>
							</div>				
		</div><!-- end container -->		
<?php get_footer(); ?>