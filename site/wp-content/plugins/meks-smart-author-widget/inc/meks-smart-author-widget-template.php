<?php
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
	
	if(	$instance['link_url'] && $instance['display_all_posts'] ){
		$author_link = $instance['link_url'];
	} else {
		$author_link = $instance['display_all_posts'] ? get_author_posts_url(get_the_author_meta('ID',$user_id)) : false;
	}


	
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