<?php

/*

	Plugin Name: Latest Post Widget
	Description: Plugin is used for latest posts.
	Version: 1.0
 
*/

class post_widget extends WP_Widget {


/*-----------------------------------------------------------------------------------*/
/*	Widget Setup
/*-----------------------------------------------------------------------------------*/
	
function post_Widget() {

	$widget_options = array(
		'classname' => 'post_widget',
		'description' => __('Custom latest post widget.','meganews')
	);

	$control_options = array(    //dodama svoje incializirane mere
		'width' => 200,
		'height' => 400,
		'id_base' => 'post_widget'
	);

	$this->WP_Widget( 'post_widget', __('Pego - Latest Post Widget','meganews'), $widget_options, $control_options );
	
}



function widget( $args, $instance ) {
	
	extract( $args );
	global $post;
	
	$title = apply_filters('widget_title', $instance['title'] );
	$posts_number = $instance['posts_number'];
	$category_select = $instance['category_select'];
	$format_select = $instance['format_select'];
	$type_select = $instance['type_select'];
	
	$allCategories = pego_get_all_categories();
	$key = array_search($category_select, $allCategories); 


	global $post;
	
	echo $before_widget;
	
	if ( $title )
	{
		echo $before_title;
		echo $title;
		echo $after_title;
	}
	
	if ($key != '') {
		if ($format_select != '') {
			$args = array('posts_per_page' => $posts_number, 'order'=> 'DESC', 'orderby' => 'post_date', 'cat' => $key, 'tax_query' => array(
       	 	array(
           	 	'taxonomy' => 'post_format',
           	 	'field' => 'slug',
           	 	'terms' => array( 'post-format-'.$format_select )
           	 	)
       	 	));
		} else {
			$args = array('posts_per_page' => $posts_number, 'order'=> 'DESC', 'orderby' => 'post_date', 'cat' => $key );
		}
	
	
		
	} else {
		if ($format_select != '') {
			$args = array('posts_per_page' => $posts_number, 'order'=> 'DESC', 'orderby' => 'post_date', 'tax_query' => array(
       	 	array(
           	 	'taxonomy' => 'post_format',
           	 	'field' => 'slug',
           	 	'terms' => array( 'post-format-'.$format_select )
           	 	)
       	 	));
		} else {
			$args = array('posts_per_page' => $posts_number, 'order'=> 'DESC', 'orderby' => 'post_date');
		}
	} 
	
	$port_query = new WP_Query( $args );
	if ($type_select == 'Type#2') {
		echo '<div class="latest-post-type2">';
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
		$post_alternative_cat_tag = get_post_meta($post->ID,'post_alternative_cat_tag', true);
		
		if ($type_select == 'Type#2') {
						?>
						 		<div class="post-widget-single-type2"> 
									<a title="<?php esc_attr(the_title()); ?>" href="<?php the_permalink();?>" >				
									<?php echo get_the_post_thumbnail($post->ID,'PostSection22', array('alt' => '', 'title' => '')); ?>
									</a>
								</div>
				<?php
				} else {
				?>
					<div class="post-widget-single" style="width:100%;">
							
							<div class="mypost_widget_img"> 
								<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
								<a title="<?php esc_attr(the_title()); ?>" href="<?php the_permalink();?>" >						
									<?php echo get_the_post_thumbnail($post->ID,'thumbnail', array('alt' => '', 'title' => '')); ?>
								</a>
							</div>
							
							<div class="mypost_widget_text"> 
								<?php if ($post_alternative_cat_tag != '') { ?>
									
									<div class="category"><a href="#" title="" class="cat category-bg-color-<?php echo $cats[0]['id']; ?>"><?php echo esc_html($post_alternative_cat_tag); ?></a></div>
								<?php } 
								else { ?>
								<div class="category"><a href="<?php echo get_category_link($cats[0]['id']) ?>" title="<?php echo esc_attr($cats[0]['name']); ?>" class="cat category-bg-color-<?php echo $cats[0]['id']; ?>"><?php echo esc_html($cats[0]['name']); ?></a></div>
								
								<?php
								}
								?>
							   	<div class="title"><a title="<?php echo esc_attr(get_the_title($post->ID)); ?>"  href="<?php the_permalink();?>"><?php echo esc_html(get_the_title($post->ID)); ?></a></div>
							</div>	
						</div>
						<div class="clear"></div>	
			
				<?php
				}
				?>			
				
	<?php endwhile; endif; wp_reset_postdata();  
	if ($type_select == 'Type#2') {
		echo '</div>';
	}
	?>
	<div class="clear"></div>		
	<?php
     
 	
	echo $after_widget;
	
}


function form( $instance ) {  

	/* Set the default values  */
		$defaults = array(
		'title' => 'Post Widget',
		'posts_number' => '3',
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		$valuesCategories = pego_get_all_categories();
		$valuesFormat = array('image', 'gallery', 'video', 'audio', 'standard');
		$valuesType = array('Type#1', 'Type#2');

	 ?>

		<label for="<?php echo $this->get_field_id( 'title' ); ?>">
		<?php _e('Title:','meganews'); ?>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" 
				 name="<?php echo $this->get_field_name( 'title' ); ?>" 
				 value="<?php echo $instance['title']; ?>" />
		</label>
																													
		<label for="<?php echo $this->get_field_id( 'posts_number' ); ?>">
		<?php _e('Number of posts:','meganews'); ?>
		<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'posts_number' ); ?>" 
				 name="<?php echo $this->get_field_name( 'posts_number' ); ?>" 
				 value="<?php echo $instance['posts_number']; ?>" />
		</label>

	<?php if ($valuesCategories) { ?>
		<label for="<?php echo $this->get_field_id('category_select'); ?>">
        <?php _e('Category (if none is choosen, all will be taken):','meganews'); ?>
          <select name="<?php echo $this->get_field_name('category_select'); ?>" 
                  id="<?php echo $this->get_field_id('category_select'); ?>"
                class="widefat">
                <option value="">Select Category</option>
          <?php 
            foreach ($valuesCategories as $value)
              {     
              ?>
                <option <?php if ($instance['category_select'] == $value) echo 'selected="selected"' ?>   value="<?php echo $value; ?>"><?php echo $value; ?></option>
              <?php
              }
              ?>
          </select> 
        </label>
		<?php 
		} 
		?>
		<?php if ($valuesFormat) { ?>
		<label for="<?php echo $this->get_field_id('format_select'); ?>">
        <?php _e('Format (if none is choosen, all will be taken):','meganews'); ?>
          <select name="<?php echo $this->get_field_name('format_select'); ?>" 
                  id="<?php echo $this->get_field_id('format_select'); ?>"
                class="widefat">
                <option value="">Select Format</option>
          <?php 
            foreach ($valuesFormat as $value)
              {     
              ?>
                <option <?php if ($instance['format_select'] == $value) echo 'selected="selected"' ?>   value="<?php echo $value; ?>"><?php echo $value; ?></option>
              <?php
              }
              ?>
          </select> 
        </label>
		<?php 
		} 
		?>
		<?php if ($valuesType) { ?>
		<label for="<?php echo $this->get_field_id('type_select'); ?>">
        <?php _e('Showing Type (if none is choosen, Type#1 will be taken):','meganews'); ?>
          <select name="<?php echo $this->get_field_name('type_select'); ?>" 
                  id="<?php echo $this->get_field_id('type_select'); ?>"
                class="widefat">
                <option value="">Select Type</option>
          <?php 
            foreach ($valuesType as $value)
              {     
              ?>
                <option <?php if ($instance['type_select'] == $value) echo 'selected="selected"' ?>   value="<?php echo $value; ?>"><?php echo $value; ?></option>
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
add_action( 'widgets_init', 'post_widgets' );

function post_widgets() {
	register_widget( 'post_Widget' );
}
?>