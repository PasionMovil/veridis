<?php
/*-----------------------------------------------------------------------------------*/
/*	Author Widget Class
/*-----------------------------------------------------------------------------------*/

class MKS_Author_Widget extends WP_Widget { 
  
  private $users_split_at = 200; //Do not run get_users() if there are more than 200 users on the website
  var $defaults;
	
	function MKS_Author_Widget() {
		$widget_ops = array( 'classname' => 'mks_author_widget', 'description' => __('Use this widget to display author/user profile info', 'meks') );
		$control_ops = array( 'id_base' => 'mks_author_widget' );
		$this->WP_Widget( 'mks_author_widget', __('Meks Smart Author', 'meks'), $widget_ops, $control_ops );
		
		if(!is_admin()){
		  add_action( 'wp_enqueue_scripts', array($this,'enqueue_styles'));
		}

		$this->defaults = array( 
				'title' => __('About Author', 'meks'),
				'author' => 0,
				'auto_detect' => 0,
				'display_name' => 1,
				'display_avatar' => 1,
				'display_desc' => 1,
				'display_all_posts' => 1,
				'avatar_size' => 64,
				'name_to_title' => 0,
				'link_to_name' => 0,
				'link_to_avatar' => 0,
				'link_text' => __('View all posts', 'meks'),
			);

		//Allow themes or plugins to modify default parameters
		$this->defaults = apply_filters('mks_author_widget_modify_defaults', $this->defaults);

	}
	

	function enqueue_styles(){
 		wp_register_style( 'meks-author-widget', MKS_AUTHOR_WIDGET_URL.'css/style.css', false, MKS_AUTHOR_WIDGET_VER );
    	wp_enqueue_style( 'meks-author-widget' );
 	}

	
	function widget( $args, $instance ) {
		
		extract( $args );

		$instance = wp_parse_args( (array) $instance, $this->defaults );
		
		//Check for user_id
		$user_id = $instance['author'];
		if($instance['auto_detect']){
			if(is_author()){
 				$obj = get_queried_object();
 				$user_id = $obj->data->ID;
 			} elseif(is_single()){
 				$obj = get_queried_object();
 				$user_id = $obj->post_author;
 			}
 		}
 			
 		$author_link = $instance['display_all_posts'] ? get_author_posts_url(get_the_author_meta('ID',$user_id)) : false;
 		
		$title =  $instance['name_to_title'] ? get_the_author_meta('display_name', $user_id) : apply_filters('widget_title', $instance['title'] );
				
		echo $before_widget;

		if ( !empty($title) ) {
			echo $before_title . $title . $after_title;
		}
		?>
		
		<?php if($instance['display_avatar']) : ?>
			<?php
			 	if($author_link && $instance['link_to_avatar']){
			 		$pre_avatar = '<a href="'.$author_link.'">';
			 		$post_avatar = '</a>';
			 	} else {
			 		$pre_avatar = '';
			 		$post_avatar = '';
			 	}
 				echo $pre_avatar. get_avatar( get_the_author_meta('ID', $user_id), $instance['avatar_size'] ) . $post_avatar;
 			?>
 		<?php endif; ?>
		
		<?php if($instance['display_name'] && !($instance['name_to_title'])) : ?>
		  <?php
		  	if($author_link && $instance['link_to_name']){
			 		$pre_name = '<a href="'.$author_link.'">';
			 		$post_name = '</a>';
			 	} else {
			 		$pre_name = '';
			 		$post_name = '';
			 	}
				echo '<h3>' . $pre_name . get_the_author_meta('display_name', $user_id) . $post_name. '</h3>';
			?>
		<?php endif; ?>
		
		<?php if($instance['display_desc']) : ?>
			<?php echo wpautop(get_the_author_meta('description',$user_id)); ?>
		<?php endif; ?>
			
		<?php if($author_link && $instance['link_text']) : ?>
			<div class="mks_autor_link_wrap"><a href="<?php echo $author_link; ?>" class="mks_author_link"><?php echo $instance['link_text']; ?></a></div>
		<?php endif; ?>

		<?php
		echo $after_widget;
	}

	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['author'] = absint( $new_instance['author'] );
		$instance['auto_detect'] = isset($new_instance['auto_detect']) ? 1 : 0;
		$instance['display_name'] = isset($new_instance['display_name']) ? 1 : 0;
		$instance['display_avatar'] = isset($new_instance['display_avatar']) ? 1 : 0;
		$instance['display_desc'] = isset($new_instance['display_desc']) ? 1 : 0;
		$instance['display_all_posts'] = isset($new_instance['display_all_posts']) ? 1 : 0;
		$instance['name_to_title'] = isset($new_instance['name_to_title']) ? 1 : 0;
		$instance['link_to_name'] = isset($new_instance['link_to_name']) ? 1 : 0;
		$instance['link_to_avatar'] = isset($new_instance['link_to_avatar']) ? 1 : 0;
		$instance['link_text'] = strip_tags( $new_instance['link_text'] );
		$instance['avatar_size'] = !empty($new_instance['avatar_size']) ? absint($new_instance['avatar_size']) : 64;

		return $instance;
	}

	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults ); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', 'meks'); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>

		<p>
			
			<?php if( $this->count_users() <= $this->users_split_at ) : ?>
			
			<?php $authors = get_users(); ?>
			<label for="<?php echo $this->get_field_id( 'author' ); ?>"><?php _e('Choose author/user', 'meks'); ?>:</label>
			<select name="<?php echo $this->get_field_name( 'author' ); ?>" id="<?php echo $this->get_field_id( 'author' ); ?>" class="widefat">
			<?php foreach($authors as $author) : ?>
				<option value="<?php echo $author->ID; ?>" <?php selected($author->ID, $instance['author']); ?>><?php echo $author->data->user_login; ?></option>
			<?php endforeach; ?>
			</select>
			
			<?php else: ?>
			
			<label for="<?php echo $this->get_field_id( 'author' ); ?>"><?php _e('Enter author/user ID', 'meks'); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'author' ); ?>" type="text" name="<?php echo $this->get_field_name( 'author' ); ?>" value="<?php echo $instance['author']; ?>" class="small-text" />
	  	
			<?php endif; ?>
			
		</p>
		
		<p>
	  	<input id="<?php echo $this->get_field_id( 'auto_detect' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'auto_detect' ); ?>" value="1" <?php checked(1, $instance['auto_detect']); ?>/>
	  	<label for="<?php echo $this->get_field_id( 'auto_detect' ); ?>"><?php _e('Automatically detect author', 'meks'); ?></label>
	  	<small class="howto"><?php _e('Use this option to automatically detect author if this sidebar is used on single post template or author template', 'meks'); ?></small>
	  </p>
	  <h4><?php _e('Display Options', 'meks'); ?></h4>
	  <ul>
	  	<li>
	  		<input id="<?php echo $this->get_field_id( 'display_avatar' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'display_avatar' ); ?>" value="1" <?php checked(1, $instance['display_avatar']); ?>/>
	  		<label for="<?php echo $this->get_field_id( 'display_avatar' ); ?>"><?php _e('Display author avatar', 'meks'); ?></label>
	  	</li>
	  	<li>
	  		<label for="<?php echo $this->get_field_id( 'avatar_size' ); ?>"><?php _e('Avatar size:', 'meks'); ?></label>
	  		<input id="<?php echo $this->get_field_id( 'avatar_size' ); ?>" type="text" name="<?php echo $this->get_field_name( 'avatar_size' ); ?>" value="<?php echo $instance['avatar_size']; ?>" class="small-text"/> px
	  	</li>
	  </ul>
	  <hr/>
	  <ul>
	  	<li>
	  		<input id="<?php echo $this->get_field_id( 'display_name' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'display_name' ); ?>" value="1" <?php checked(1, $instance['display_name']); ?>/>
	  		<label for="<?php echo $this->get_field_id( 'display_name' ); ?>"><?php _e('Display author name', 'meks'); ?></label>
	  	</li>
	  	<li>
	  		<input id="<?php echo $this->get_field_id( 'name_to_title' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'name_to_title' ); ?>" value="1" <?php checked(1, $instance['name_to_title']); ?>/>
	  		<label for="<?php echo $this->get_field_id( 'name_to_title' ); ?>"><?php _e('Overwrite widget title with author name', 'meks'); ?></label>
	  	</li>
	  </ul>
	  <hr/>
	  <ul>
	  	<li>
	  		<input id="<?php echo $this->get_field_id( 'display_desc' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'display_desc' ); ?>" value="1" <?php checked(1, $instance['display_desc']); ?>/>
	  		<label for="<?php echo $this->get_field_id( 'display_desc' ); ?>"><?php _e('Display author description', 'meks'); ?></label>
	  	</li>
	  </ul>
	  <hr/>
	  <ul>
	  	<li>
	  		<input id="<?php echo $this->get_field_id( 'display_all_posts' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'display_all_posts' ); ?>" value="1" <?php checked(1, $instance['display_all_posts']); ?>/>
	  		<label for="<?php echo $this->get_field_id( 'display_all_posts' ); ?>"><?php _e('Display author "all posts" archive link', 'meks'); ?></label>
	  	</li>
	  	<li>
	  		<input id="<?php echo $this->get_field_id( 'link_to_name' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'link_to_name' ); ?>" value="1" <?php checked(1, $instance['link_to_name']); ?>/>
	  		<label for="<?php echo $this->get_field_id( 'link_to_name' ); ?>"><?php _e('Link author name', 'meks'); ?></label>
	  	</li>
	  	<li>
	  		<input id="<?php echo $this->get_field_id( 'link_to_avatar' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'link_to_avatar' ); ?>" value="1" <?php checked(1, $instance['link_to_avatar']); ?>/>
	  		<label for="<?php echo $this->get_field_id( 'link_to_avatar' ); ?>"><?php _e('Link author avatar', 'meks'); ?></label>
	  	</li>
	  	<li>
	  		<label for="<?php echo $this->get_field_id( 'link_text' ); ?>"><?php _e('Link text:', 'meks'); ?></label>
	  		<input id="<?php echo $this->get_field_id( 'link_text' ); ?>" type="text" name="<?php echo $this->get_field_name( 'link_text' ); ?>" value="<?php echo $instance['link_text']; ?>" class="widefat"/>
	  		<small class="howto"><?php _e('Specify text for "all posts" link if you want to show separate link', 'meks'); ?></small>
	  	</li>
	  </ul>
		
		
	<?php
	}
	
	/* Check total number of users on the website */
	function count_users(){
		$user_count = count_users();
		if(isset($user_count['total_users']) && !empty($user_count['total_users'])){
			return $user_count['total_users'];
		}
		return 0;
	}
}

?>