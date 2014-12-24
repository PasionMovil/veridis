<?php

/*

	Plugin Name: Posts in Tab widget
	Description: Plugin is used for latest posts.
	Version: 1.0
 
*/

class post_tab_widget extends WP_Widget {


/*-----------------------------------------------------------------------------------*/
/*	Widget Setup
/*-----------------------------------------------------------------------------------*/
	
function post_tab_Widget() {

	$widget_options = array(
		'classname' => 'post_tab_widget',
		'description' => __('Custom latest post widget.','meganews')
	);

	$control_options = array(    //dodama svoje incializirane mere
		'width' => 200,
		'height' => 400,
		'id_base' => 'post_tab_widget'
	);

	$this->WP_Widget( 'post_tab_widget', __('Pego - Posts in Tabs Widget','meganews'), $widget_options, $control_options );
	
}



function widget( $args, $instance ) {
	
	extract( $args );
	global $post;
	
	$output = '[vc_tabs]';
	
/* Tab #1 */	
	$title1 = apply_filters('widget_title', $instance['title1'] );
	$posts_number1 = $instance['posts_number1'];
	$category_select1 = $instance['category_select1'];
	$format_select1 = $instance['format_select1'];
	$type_select1 = $instance['type_select1'];
	
	$allCategories = pego_get_all_categories();
	$key1 = array_search($category_select1, $allCategories); 


	global $post;
	
	$id = rand(1, 10000);
if ($title1 != '') {
	$output .= '[vc_tab title="'.$title1.'" tab_id="'.$id.'"]';
	
	if ($key1 != '') {
		if ($format_select1 != '') {
			$args = array('posts_per_page' => $posts_number1, 'order'=> 'DESC', 'orderby' => 'post_date', 'cat' => $key1, 'tax_query' => array(
       	 	array(
           	 	'taxonomy' => 'post_format',
           	 	'field' => 'slug',
           	 	'terms' => array( 'post-format-'.$format_select1 )
           	 	)
       	 	));
		} else {
			$args = array('posts_per_page' => $posts_number1, 'order'=> 'DESC', 'orderby' => 'post_date', 'cat' => $key1 );
		}
	
	
		
	} else {
		if ($format_select1 != '') {
			$args = array('posts_per_page' => $posts_number1, 'order'=> 'DESC', 'orderby' => 'post_date', 'tax_query' => array(
       	 	array(
           	 	'taxonomy' => 'post_format',
           	 	'field' => 'slug',
           	 	'terms' => array( 'post-format-'.$format_select1)
           	 	)
       	 	));
		} else {
			$args = array('posts_per_page' => $posts_number1, 'order'=> 'DESC', 'orderby' => 'post_date');
		}
	} 
	
	$port_query = new WP_Query( $args );
	
	if ($type_select1 == 'Type#2') {
		$output .= '<div class="latest-post-type2">';
	}       
   if( $port_query->have_posts() ) : while( $port_query->have_posts() ) : $port_query->the_post(); 
		$post_categories = wp_get_post_categories( $post->ID );
		if ($post_categories) {
			$cats = array();
			foreach($post_categories as $c){
				$cat = get_category( $c );
				$cats[] = array( 'id' => $cat->cat_ID, 'name' => $cat->name );
			}
		}
		
		if ($type_select1 == 'Type#2') {
						
							
						 		$output .= '<div class="post-widget-single-type2">'; 
									$output .= '<a title="'.esc_attr(get_the_title()).'" href="'.get_permalink().'" >';				
									$output .= get_the_post_thumbnail($post->ID,"PostSection22", array("alt" => "", "title" => ""));
									$output .= '</a>';
								$output .= '</div>';
							
			
				} else {
					$cat_link = get_category_link($cats[0]['id']);
					$cat_id = $cats[0]['id'];
					$cat_name = $cats[0]['name'];
					$output .= '<div class="post-widget-single  post_widget" style="width:100%;">';
					
					
							$output .= '<div class="mypost_widget_img"> ';
								$output .= '<a title="'.esc_attr(get_the_title()).'" href="'.get_permalink().'" >';						
									$output .= get_the_post_thumbnail($post->ID,"thumbnail", array("alt" => "", "title" => ""));
								$output .= '</a>';
							$output .= '</div>';
							
							$output .= '<div class="mypost_widget_text">';
								$output .= '<div class="category"><a href="'.$cat_link.'" title="'.esc_attr($cat_name).'" class="cat category-bg-color-'.$cat_id.'">'.esc_html($cat_name).'</a></div>';
							   	$output .= '<div class="title"><a title="'.esc_attr(get_the_title()).'"  href="'.get_permalink().'">'.esc_html(get_the_title()).'</a></div>';
							$output .= '</div>';
						
						$output .= '</div>';
						$output .= '<div class="clear"></div>';	
			
				
				}
				?>			
				
	<?php endwhile; endif; wp_reset_postdata();   
	if ($type_select1 == 'Type#2') {
		$output .= '</div>';
	}
	$output .= '<div class="clear"></div>';		

     
 	
 	$output .= '[/vc_tab]';
 }
	
/* Tab #2 */	
	$title2 = apply_filters('widget_title', $instance['title2'] );
	$posts_number2 = $instance['posts_number2'];
	$category_select2 = $instance['category_select2'];
	$format_select2 = $instance['format_select2'];
	$type_select2 = $instance['type_select2'];
	
	$allCategories = pego_get_all_categories();
	$key2 = array_search($category_select2, $allCategories); 


	global $post;
	
	$id = rand(2, 20000);
if ($title2 != '') {
	$output .= '[vc_tab title="'.$title2.'" tab_id="'.$id.'"]';
	
	if ($key2 != '') {
		if ($format_select2 != '') {
			$args = array('posts_per_page' => $posts_number2, 'order'=> 'DESC', 'orderby' => 'post_date', 'cat' => $key2, 'tax_query' => array(
       	 	array(
           	 	'taxonomy' => 'post_format',
           	 	'field' => 'slug',
           	 	'terms' => array( 'post-format-'.$format_select2 )
           	 	)
       	 	));
		} else {
			$args = array('posts_per_page' => $posts_number2, 'order'=> 'DESC', 'orderby' => 'post_date', 'cat' => $key2 );
		}
	
	
		
	} else {
		if ($format_select2 != '') {
			$args = array('posts_per_page' => $posts_number2, 'order'=> 'DESC', 'orderby' => 'post_date', 'tax_query' => array(
       	 	array(
           	 	'taxonomy' => 'post_format',
           	 	'field' => 'slug',
           	 	'terms' => array( 'post-format-'.$format_select2)
           	 	)
       	 	));
		} else {
			$args = array('posts_per_page' => $posts_number2, 'order'=> 'DESC', 'orderby' => 'post_date');
		}
	} 
	
	$port_query = new WP_Query( $args );
	if ($type_select2 == 'Type#2') {
		$output .= '<div class="latest-post-type2">';
	}       
	       
   if( $port_query->have_posts() ) : while( $port_query->have_posts() ) : $port_query->the_post(); 
		$post_categories = wp_get_post_categories( $post->ID );
		if ($post_categories) {
			$cats = array();
			foreach($post_categories as $c){
				$cat = get_category( $c );
				$cats[] = array( 'id' => $cat->cat_ID, 'name' => $cat->name );
			}
		}
		if ($type_select2 == 'Type#2') {
						
						
						 		$output .= '<div class="post-widget-single-type2">'; 
									$output .= '<a title="'.esc_attr(get_the_title()).'" href="'.get_permalink().'" >';				
									$output .= get_the_post_thumbnail($post->ID,"PostSection22", array("alt" => "", "title" => ""));
									$output .= '</a>';
								$output .= '</div>';
							
			
				} else {
					$cat_link = get_category_link($cats[0]['id']);
					$cat_id = $cats[0]['id'];
					$cat_name = $cats[0]['name'];
					$output .= '<div class="post-widget-single post_widget" style="width:100%;">';
							
						
							$output .= '<div class="mypost_widget_img"> ';
								$output .= '<a title="'.esc_attr(get_the_title()).'" href="'.get_permalink().'" >';						
									$output .= get_the_post_thumbnail($post->ID,"thumbnail", array("alt" => "", "title" => ""));
								$output .= '</a>';
							$output .= '</div>';
							
							$output .= '<div class="mypost_widget_text">';
								$output .= '<div class="category"><a href="'.$cat_link.'" title="'.esc_attr($cat_name).'" class="cat category-bg-color-'.$cat_id.'">'.esc_html($cat_name).'</a></div>';
							   	$output .= '<div class="title"><a title="'.esc_attr(get_the_title()).'" href="'.get_permalink().'">'.esc_html(get_the_title()).'</a></div>';
							$output .= '</div>';
						$output .= '</div>';
						$output .= '<div class="clear"></div>';	
			
				
				}
				?>			
				
	<?php endwhile; endif; wp_reset_postdata();   
	if ($type_select2 == 'Type#2') {
		$output .= '</div">';
	}       
	$output .= '<div class="clear"></div>';		

     
 	
 	$output .= '[/vc_tab]';
 }
	
	
	/* Tab #3 */	
	$title3 = apply_filters('widget_title', $instance['title3'] );
	$posts_number3 = $instance['posts_number3'];
	$category_select3 = $instance['category_select3'];
	$format_select3 = $instance['format_select3'];
	$type_select3 = $instance['type_select3'];
	
	$allCategories = pego_get_all_categories();
	$key3 = array_search($category_select3, $allCategories); 


	global $post;
	
	$id = rand(3, 30000);
if ($title3 != '') {
	$output .=  '[vc_tab title="'.$title3.'" tab_id="'.$id.'"]';
	
	if ($key3 != '') {
		if ($format_select3 != '') {
			$args = array('posts_per_page' => $posts_number3, 'order'=> 'DESC', 'orderby' => 'post_date', 'cat' => $key3, 'tax_query' => array(
       	 	array(
           	 	'taxonomy' => 'post_format',
           	 	'field' => 'slug',
           	 	'terms' => array( 'post-format-'.$format_select3 )
           	 	)
       	 	));
		} else {
			$args = array('posts_per_page' => $posts_number3, 'order'=> 'DESC', 'orderby' => 'post_date', 'cat' => $key3 );
		}
	
	
		
	} else {
		if ($format_select3 != '') {
			$args = array('posts_per_page' => $posts_number3, 'order'=> 'DESC', 'orderby' => 'post_date', 'tax_query' => array(
       	 	array(
           	 	'taxonomy' => 'post_format',
           	 	'field' => 'slug',
           	 	'terms' => array( 'post-format-'.$format_select3)
           	 	)
       	 	));
		} else {
			$args = array('posts_per_page' => $posts_number3, 'order'=> 'DESC', 'orderby' => 'post_date');
		}
	} 
	
	$port_query = new WP_Query( $args );
	
	if ($type_select3 == 'Type#2') {
		$output .= '<div class="latest-post-type2">';
	}        
   if( $port_query->have_posts() ) : while( $port_query->have_posts() ) : $port_query->the_post(); 
		$post_categories = wp_get_post_categories( $post->ID );
		if ($post_categories) {
			$cats = array();
			foreach($post_categories as $c){
				$cat = get_category( $c );
				$cats[] = array( 'id' => $cat->cat_ID, 'name' => $cat->name );
			}
		}
		
		if ($type_select3 == 'Type#2') {
						
							
						 		$output .= '<div class="post-widget-single-type2">'; 
									$output .= '<a title="'.esc_attr(get_the_title()).'" href="'.get_permalink().'" >';				
									$output .= get_the_post_thumbnail($post->ID,"PostSection22", array("alt" => "", "title" => ""));
									$output .= '</a>';
								$output .= '</div>';
							
			
				} else {
					$cat_link = get_category_link($cats[0]['id']);
					$cat_id = $cats[0]['id'];
					$cat_name = $cats[0]['name'];
					$output .= '<div class="post-widget-single post_widget" style="width:100%;">';
							
						
							$output .= '<div class="mypost_widget_img"> ';
								$output .= '<a title="'.esc_attr(get_the_title()).'" href="'.get_permalink().'" >';						
									$output .= get_the_post_thumbnail($post->ID,"thumbnail", array("alt" => "", "title" => ""));
								$output .= '</a>';
							$output .= '</div>';
							
							$output .= '<div class="mypost_widget_text">';
								$output .= '<div class="category"><a href="'.$cat_link.'" title="'.esc_attr($cat_name).'" class="cat category-bg-color-'.$cat_id.'">'.esc_html($cat_name).'</a></div>';
							   	$output .= '<div class="title"><a title="'.esc_attr(get_the_title()).'"  href="'.get_permalink().'">'.esc_html(get_the_title()).'</a></div>';
							$output .= '</div>';
						$output .= '</div>';
						$output .= '<div class="clear"></div>';	
			
				
				}
				?>			
				
	<?php endwhile; endif; wp_reset_postdata(); 
	if ($type_select3 == 'Type#2') {
		$output .= '</div>';
	}         
	$output .= '<div class="clear"></div>';		

     
 	
 	$output .= '[/vc_tab]';
}

	
	$output .=  '[/vc_tabs]';
	echo $before_widget;
	echo do_shortcode($output);
	echo $after_widget;
}


function form( $instance ) {  

	/* Set the default values  */
		$defaults = array(
		'title' => '',
		'posts_number1' => '3',
		'posts_number2' => '3',
		'posts_number3' => '3'
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		$valuesCategories = pego_get_all_categories();
		$valuesFormat = array('image', 'gallery', 'video', 'audio', 'standard');
		$valuesType = array('Type#1', 'Type#2');

	 ?>

		<label for="<?php echo $this->get_field_id( 'title1' ); ?>">
		<?php _e('Tab#1 - Title:','meganews'); ?>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title1' ); ?>" 
				 name="<?php echo $this->get_field_name( 'title1' ); ?>" 
				 value="<?php echo $instance['title1']; ?>" />
		</label>
																													
		<label for="<?php echo $this->get_field_id( 'posts_number1' ); ?>">
		<?php _e('Tab#1 - Number of posts:','meganews'); ?>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'posts_number1' ); ?>" 
				 name="<?php echo $this->get_field_name( 'posts_number1' ); ?>" 
				 value="<?php echo $instance['posts_number1']; ?>" />
		</label>

	<?php if ($valuesCategories) { ?>
		<label for="<?php echo $this->get_field_id('category_select1'); ?>">
        <?php _e('Tab #1 - Category (if none is choosen, all will be taken):','meganews'); ?>
          <select name="<?php echo $this->get_field_name('category_select1'); ?>" 
                  id="<?php echo $this->get_field_id('category_select1'); ?>"
                class="widefat">
                <option value="">Select Category</option>
          <?php 
            foreach ($valuesCategories as $value)
              {     
              ?>
                <option <?php if ($instance['category_select1'] == $value) echo 'selected="selected"' ?>   value="<?php echo $value; ?>"><?php echo $value; ?></option>
              <?php
              }
              ?>
          </select> 
        </label>
		<?php 
		} 
		?>
		<?php if ($valuesFormat) { ?>
		<label for="<?php echo $this->get_field_id('format_select1'); ?>">
        <?php _e('Tab #1 - Format (if none is choosen, all will be taken):','meganews'); ?>
          <select name="<?php echo $this->get_field_name('format_select1'); ?>" 
                  id="<?php echo $this->get_field_id('format_select1'); ?>"
                class="widefat">
                <option value="">Select Format</option>
          <?php 
            foreach ($valuesFormat as $value)
              {     
              ?>
                <option <?php if ($instance['format_select1'] == $value) echo 'selected="selected"' ?>   value="<?php echo $value; ?>"><?php echo $value; ?></option>
              <?php
              }
              ?>
          </select> 
        </label>
		<?php 
		} 
		?>
		<?php if ($valuesType) { ?>
		<label for="<?php echo $this->get_field_id('type_select1'); ?>">
        <?php _e('Tab #1 - Showing Type (if none is choosen, Type#1 will be taken):','meganews'); ?>
          <select name="<?php echo $this->get_field_name('type_select1'); ?>" 
                  id="<?php echo $this->get_field_id('type_select1'); ?>"
                class="widefat">
                <option value="">Select Type</option>
          <?php 
            foreach ($valuesType as $value)
              {     
              ?>
                <option <?php if ($instance['type_select1'] == $value) echo 'selected="selected"' ?>   value="<?php echo $value; ?>"><?php echo $value; ?></option>
              <?php
              }
              ?>
          </select> 
        </label>
		<?php 
		} 
		?>
		
		
		<label for="<?php echo $this->get_field_id( 'title2' ); ?>">
		<?php _e('Tab#2 - Title:','meganews'); ?>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title2' ); ?>" 
				 name="<?php echo $this->get_field_name( 'title2' ); ?>" 
				 value="<?php echo $instance['title2']; ?>" />
		</label>
																													
		<label for="<?php echo $this->get_field_id( 'posts_number2' ); ?>">
		<?php _e('Tab#2 - Number of posts:','meganews'); ?>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'posts_number2' ); ?>" 
				 name="<?php echo $this->get_field_name( 'posts_number2' ); ?>" 
				 value="<?php echo $instance['posts_number2']; ?>" />
		</label>

	<?php if ($valuesCategories) { ?>
		<label for="<?php echo $this->get_field_id('category_select2'); ?>">
        <?php _e('Tab #2 - Category (if none is choosen, all will be taken):','meganews'); ?>
          <select name="<?php echo $this->get_field_name('category_select2'); ?>" 
                  id="<?php echo $this->get_field_id('category_select2'); ?>"
                class="widefat">
                <option value="">Select Category</option>
          <?php 
            foreach ($valuesCategories as $value)
              {     
              ?>
                <option <?php if ($instance['category_select2'] == $value) echo 'selected="selected"' ?>   value="<?php echo $value; ?>"><?php echo $value; ?></option>
              <?php
              }
              ?>
          </select> 
        </label>
		<?php 
		} 
		?>
		<?php if ($valuesFormat) { ?>
		<label for="<?php echo $this->get_field_id('format_select2'); ?>">
        <?php _e('Tab #2 - Format (if none is choosen, all will be taken):','meganews'); ?>
          <select name="<?php echo $this->get_field_name('format_select2'); ?>" 
                  id="<?php echo $this->get_field_id('format_select2'); ?>"
                class="widefat">
                <option value="">Select Format</option>
          <?php 
            foreach ($valuesFormat as $value)
              {     
              ?>
                <option <?php if ($instance['format_select2'] == $value) echo 'selected="selected"' ?>   value="<?php echo $value; ?>"><?php echo $value; ?></option>
              <?php
              }
              ?>
          </select> 
        </label>
		<?php 
		} 
		?>
		<?php if ($valuesType) { ?>
		<label for="<?php echo $this->get_field_id('type_select2'); ?>">
        <?php _e('Tab #2 - Showing Type (if none is choosen, Type#2 will be taken):','meganews'); ?>
          <select name="<?php echo $this->get_field_name('type_select2'); ?>" 
                  id="<?php echo $this->get_field_id('type_select2'); ?>"
                class="widefat">
                <option value="">Select Type</option>
          <?php 
            foreach ($valuesType as $value)
              {     
              ?>
                <option <?php if ($instance['type_select2'] == $value) echo 'selected="selected"' ?>   value="<?php echo $value; ?>"><?php echo $value; ?></option>
              <?php
              }
              ?>
          </select> 
        </label>
		<?php 
		} 
		?>
		
				<label for="<?php echo $this->get_field_id( 'title3' ); ?>">
		<?php _e('Tab#3 - Title:','meganews'); ?>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title3' ); ?>" 
				 name="<?php echo $this->get_field_name( 'title3' ); ?>" 
				 value="<?php echo $instance['title3']; ?>" />
		</label>
																													
		<label for="<?php echo $this->get_field_id( 'posts_number3' ); ?>">
		<?php _e('Tab#3 - Number of posts:','meganews'); ?>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'posts_number3' ); ?>" 
				 name="<?php echo $this->get_field_name( 'posts_number3' ); ?>" 
				 value="<?php echo $instance['posts_number3']; ?>" />
		</label>

	<?php if ($valuesCategories) { ?>
		<label for="<?php echo $this->get_field_id('category_select3'); ?>">
        <?php _e('Tab #3 - Category (if none is choosen, all will be taken):','meganews'); ?>
          <select name="<?php echo $this->get_field_name('category_select3'); ?>" 
                  id="<?php echo $this->get_field_id('category_select3'); ?>"
                class="widefat">
                <option value="">Select Category</option>
          <?php 
            foreach ($valuesCategories as $value)
              {     
              ?>
                <option <?php if ($instance['category_select3'] == $value) echo 'selected="selected"' ?>   value="<?php echo $value; ?>"><?php echo $value; ?></option>
              <?php
              }
              ?>
          </select> 
        </label>
		<?php 
		} 
		?>
		<?php if ($valuesFormat) { ?>
		<label for="<?php echo $this->get_field_id('format_select3'); ?>">
        <?php _e('Tab #3 - Format (if none is choosen, all will be taken):','meganews'); ?>
          <select name="<?php echo $this->get_field_name('format_select3'); ?>" 
                  id="<?php echo $this->get_field_id('format_select3'); ?>"
                class="widefat">
                <option value="">Select Format</option>
          <?php 
            foreach ($valuesFormat as $value)
              {     
              ?>
                <option <?php if ($instance['format_select3'] == $value) echo 'selected="selected"' ?>   value="<?php echo $value; ?>"><?php echo $value; ?></option>
              <?php
              }
              ?>
          </select> 
        </label>
		<?php 
		} 
		?>
		<?php if ($valuesType) { ?>
		<label for="<?php echo $this->get_field_id('type_select3'); ?>">
        <?php _e('Tab #3 - Showing Type (if none is choosen, Type#3 will be taken):','meganews'); ?>
          <select name="<?php echo $this->get_field_name('type_select3'); ?>" 
                  id="<?php echo $this->get_field_id('type_select3'); ?>"
                class="widefat">
                <option value="">Select Type</option>
          <?php 
            foreach ($valuesType as $value)
              {     
              ?>
                <option <?php if ($instance['type_select3'] == $value) echo 'selected="selected"' ?>   value="<?php echo $value; ?>"><?php echo $value; ?></option>
              <?php
              }
              ?>
          </select> 
        </label>
		<?php 
		} 
		?>
			
	<?php
	}
}


/*     Adding widget to widgets_init and registering flickr widget    */
add_action( 'widgets_init', 'post_tab_widgets' );

function post_tab_widgets() {
	register_widget( 'post_tab_Widget' );
}
?>