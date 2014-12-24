<?php get_header(); ?>			
		<div id="container"> <!-- start container -->	
			<?php pego_display_breadcrumbs(); ?>				
			<div class="main-no-sidebar">
				<div class="inside-section">
		  			<div class="vc_span8">
					<?php while ( have_posts() ) : the_post(); 
							$permalink = get_permalink( $post->ID );
							$title = get_the_title( $post->ID); 	
							$format = get_post_format( $post->ID );	
							$cats= get_the_category_list(', ','',$post->ID);
							$post_categories = wp_get_post_categories( $post->ID );
							$allCats = array(); 
							if ($post_categories) {
								$cats = array();
								foreach($post_categories as $c){
									$cat = get_category( $c );
									$cats[] = array( 'id' => $cat->cat_ID, 'name' => $cat->name );
									$allCats[] = $cat->name;
								}
								$cat_id = $cats[0]['id'];		
								$cat_name = $cats[0]['name'];	
								$cat_link =  get_category_link($cats[0]['id']);
							}
							$allCatsString = implode(",", $allCats);
							?>
							<div class="single-post-cat"><a class="category-bg-color-<?php echo $cat_id; ?>" href="<?php echo $cat_link; ?>"><?php echo $cat_name; ?></a></div>
							<div class="single-post-comments-number"><span class="icons-comments icon-comment"></span><a href="<?php comments_link(); ?>" ><?php echo get_comments_number($post->ID); ?></a></div>
							<div class="clear"></div>
							<h1 class="single-post-title"><?php echo esc_html(get_the_title()); ?></h1>
							<div class="single-post-author">Written by: <?php the_author_posts_link(); ?></div>
							<div class="single-post-date"><?php echo get_the_time('Y/m/d g:i A', $post->ID ); ?></div>
							<div class="clear"></div>
							<div class="single-post-type-content">
							<?php
								echo do_shortcode('[vc_row][vc_column width="1/1"][/vc_column][/vc_row]'); 
							 if ($format == 'image') {
								  $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
								  echo '<img src="'.$image[0].'" alt="'.get_the_title($post->ID).'" />';
							 }
							 if ($format == 'gallery') { 	
								       $attachments = get_children(array('post_parent' => $post->ID,
																  'post_status' => 'inherit',
																  'post_type' => 'attachment',
																  'post_mime_type' => 'image',
																  'order' => 'DESC',
																  'orderby' => 'menu_order ID'));	
																  
										 $idGallery = rand(1, 10000);	
										 				    
    							  		  ?>
    							  		  <div class="single-post-gallery">
								          <div class="slider-single-post flexslider">
          									<ul class="slides">
         									 <?php
         									 foreach($attachments as $att_id => $attachment) {
													$preview_img_url = wp_get_attachment_image_src($attachment->ID,'Post1', true);
													$full_img_url = wp_get_attachment_image_src($attachment->ID,'full', true);
													?>
													<li>
														<a title="<?php echo $attachment->post_excerpt; ?>"  rel="prettyPhoto[gallery<?php echo $idGallery; ?>]"  href="<?php echo $full_img_url[0]; ?>">
															<img  src="<?php echo $preview_img_url[0]; ?>" alt="" />
														</a>
														<?php
														if ($attachment->post_excerpt != '') {
														 ?>
														 <p class="flex-caption"><?php echo $attachment->post_excerpt; ?></p>
														 <?php
														}
														?>
													</li>
													<?php
									  		}
									  		?>
									  	   </ul>
       									 </div>
       									   <div class="carousel-single-post flexslider">
          									<ul class="slides">
         									 <?php
         									 foreach($attachments as $att_id => $attachment) {
													$full_img_url = wp_get_attachment_image_src($attachment->ID,'PostSection31', true);
													?>
														<li><img src="<?php echo $full_img_url[0]; ?>" alt="" /></li>
													<?php
									  		}
									  		?>
									  	   </ul>
       									 </div>
       									</div>
								  
								  <?php
							  } 
							  if ($format == 'audio') { 
							 	 $audioFile = get_post_meta($post->ID , 'post_audio_upload' , true);
							  ?>
							  	<audio id="player2" src="<?php echo $audioFile; ?>" type="audio/mp3" controls="controls"></audio>	
							 <?php 
							  }
							  if ($format == 'video') { 				
									global $wp_embed;
									$linkVideo = get_post_meta($post->ID , 'post_video_url' , true);
									$embed = $wp_embed->run_shortcode('[embed width="1280"]'.$linkVideo.'[/embed]');
									echo '<div class="video-wrapper"><div class="video-container">'.$embed.'</div></div>';	
								} 
							 ?>
							 </div>
							 <div class="clear"></div>
							<?php the_content(); ?>
							<div class="clear"></div>
							
							<?php 
							//review
							
							$title1 = get_post_meta($post->ID , 'post_review_title1' , true);
							$review1 = get_post_meta($post->ID , 'post_review_value1' , true);
							$title2 = get_post_meta($post->ID , 'post_review_title2' , true);
							$review2 = get_post_meta($post->ID , 'post_review_value2' , true);
							$title3 = get_post_meta($post->ID , 'post_review_title3' , true);
							$review3 = get_post_meta($post->ID , 'post_review_value3' , true);
							$title4 = get_post_meta($post->ID , 'post_review_title4' , true);
							$review4 = get_post_meta($post->ID , 'post_review_value4' , true);
							$title5 = get_post_meta($post->ID , 'post_review_title5' , true);
							$review5 = get_post_meta($post->ID , 'post_review_value5' , true);
							if ((($title1 != '')&&($review1 != ''))||(($title2 != '')&&($review2 != ''))||(($title3 != '')&&($review3 != ''))||(($title4 != '')&&($review4 != ''))||(($title5 != '')&&($review5 != ''))  ) {
								$review_array = array();
								$value_sum = 0;
								$number_reviews = 0;
								$review_sh = '[vc_progress_bar values="';
								if (($title1 != '')&&($review1 != '')) { 
									$review_array[] = $review1.'|'.$title1;
									$number_reviews++;
									$value_sum += $review1;
								}
								if (($title2 != '')&&($review2 != '')) { 
									$review_array[] = $review2.'|'.$title2;
									$number_reviews++;
									$value_sum += $review2;
								}
								if (($title3 != '')&&($review3 != '')) { 
									$review_array[] = $review3.'|'.$title3;
									$number_reviews++;
									$value_sum += $review3;
								}
								if (($title4 != '')&&($review4 != '')) { 
									$review_array[] = $review4.'|'.$title4;
									$number_reviews++;
									$value_sum += $review4;
								}
								if (($title5 != '')&&($review5 != '')) { 
									$review_array[] = $review5.'|'.$title5;
									$number_reviews++;
									$value_sum += $review5;
								}
								$review_sh .= implode(",", $review_array);
							
							
								$review_sh .= '" bgcolor="bar_blue" units="%" height="30"]';
								$average_review = round(($value_sum/$number_reviews), 0); 
								echo '<div class="post-review">';
									echo '<h3>The review</h3>';
									echo '<div class="title-stripes-left"></div>';
									echo '<div class="clear"></div>';
									echo '<div class="review-average">';
										echo $average_review.'<span>%</span>';
									echo '</div>';
									echo '<div class="review-summary">';
										echo get_post_meta($post->ID , 'post_review_summary' , true);
									echo '</div>';
									echo '<div class="clear"></div>'; 
									echo do_shortcode($review_sh);
								echo '</div>';
								
							}
							
							
							
							$post_tags = wp_get_post_tags($post->ID);
							if(!empty($post_tags)) {
								 echo '<div class="post-tagged">';
								 $tagsCaption = '';
								 if ( function_exists( 'ot_get_option' ) ) {
									if (ot_get_option('meganews_tags_caption') != '') {
										$tagsCaption = ot_get_option('meganews_tags_caption').' ';
									}
								}
								the_tags($tagsCaption, ', ', '<br />'); 
								echo '</div>';
							}
							?>	
							<div class="clear"></div>
							<?php
							$shareArray = array();
							if ( function_exists( 'ot_get_option' ) ) {
								if (ot_get_option('meganews_single_post_share') != '') {
									$shareArray = ot_get_option('meganews_single_post_share');
								}
							}
							
							pego_get_blog_socials($permalink, $title, $shareArray);
							
							//pego_get_author_data($post); ?>
                            <div class="clear"></div>
							

					<?php
					endwhile; 
					?>
					<?php 
					echo '<div class="related-posts">';
					 if ( function_exists( 'ot_get_option' ) ) {
						if (ot_get_option('meganews_related_posts_caption') != '') {
							echo do_shortcode('[vc_text_titles title="'.ot_get_option('meganews_related_posts_caption').'" title_type="h2" page_title_type="v1" title_align="left"]');
						}
					}
					echo do_shortcode('[vc_teaser_grid  showtype="type1" grid_columns_count="3" summary_length="115" grid_categories="'.$allCatsString.'" grid_teasers_count="3" grid_thumb_size="600x400"]');
					echo '</div>';
					?>

                    <!-- start Fuzzy SEO stuff -->
                    <div class="clear"></div>
                    <div class="vc_span8" style="margin-bottom: 40px;">
                        <?php
                            seoqueries_get_page_terms($plain_text = false)
                        ?>
                    </div>
                    <!-- end Fuzzy Seo -->

					<div class="clear"></div>
					<!-- start comments -->												
					<div id="comments">
						<?php //comments_template(); ?>
					</div>				
					<!-- end comments -->						
				</div>
				<div class="vc_span4 sidebar">
					<div class="wpb_widgetised_column sidebar-left-border">
				<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Blog Sidebar')) : else : ?>

				<?php endif; ?>
					</div>
				</div>
				</div>
				<div class="clear"></div>				
			</div>	
</div><!-- end container -->		
<?php get_footer(); ?>
