<?php
//post editing
add_action( 'admin_menu', 'hybrid_create_meta_box' );
  add_action( 'save_post', 'hybrid_save_meta_data' );



function hybrid_create_meta_box() {
	global $theme_name;
	add_meta_box( 'post-meta-boxes', __('General post options','meganews'), 'post_meta_boxes', 'post', 'normal', 'default' );
}


function hybrid_post_meta_boxes() {

	/* Array of the meta box options. */
	$meta_boxes = array(			
			'post_video_url' => array( 
							'name' => 'post_video_url',
							'title' => __('Video url:', 'meganews'), 
							'description' => __('Used for video post format.','meganews'),
							'type' => 'text' ),
			'post_audio_upload' => array( 
							'name' => 'post_audio_upload', 
							'title' => __('Post audio upload', 'meganews'), 
							'description' => __('Upload mp3 for audio post type.', 'meganews'), 
							'type' => "upload"),
			'post_alternative_cat_tag' => array( 
							'name' => 'post_alternative_cat_tag',
							'title' => __('Alternative tag:', 'meganews'), 
							'description' => __('If alternative tag is inserted, it will replace the category tag.','meganews'),
							'type' => 'text' ),	
			'post_tag_new' => array( 
							'name' => 'post_tag_new', 
							'title' => __('Add additional tag to post?', 'meganews'), 
							'description' => __('If inserted the tag will be added to the post.', 'meganews'), 
							'type' => 'text' ),
			'post_review_heading' => array( 
							'name' => 'post_review_heading', 
							'title' => __('Post review', 'meganews'), 
							'description' => __('If inserted the tag will be added to the post.', 'meganews'), 
							'type' => 'heading' ),
			'post_review_summary' => array( 
							'name' => 'post_review_summary', 
							'title' => __('Post review summary', 'meganews'), 
							'description' => __('Write a summary for the post review.', 'meganews'), 
							'type' => 'textarea' ),
			'post_review_title1' => array( 
							'name' => 'post_review_title1',
							'title' => __('Post review title #1:', 'meganews'), 
							'description' => __('Insert review title #1.','meganews'),
							'type' => 'text' ),	
			'post_review_value1' => array( 
							'name' => 'post_review_value1',
							'title' => __('Post review value #1:', 'meganews'), 
							'description' => __('Insert review value #1 (number between 1-100).','meganews'),
							'type' => 'text' ),	
			'post_review_title2' => array( 
							'name' => 'post_review_title2',
							'title' => __('Post review title #2:', 'meganews'), 
							'description' => __('Insert review title #2.','meganews'),
							'type' => 'text' ),	
			'post_review_value2' => array( 
							'name' => 'post_review_value2',
							'title' => __('Post review value #2:', 'meganews'), 
							'description' => __('Insert review value #2 (number between 1-100).','meganews'),
							'type' => 'text' ),	
			'post_review_title3' => array( 
							'name' => 'post_review_title3',
							'title' => __('Post review title #3:', 'meganews'), 
							'description' => __('Insert review title #3.','meganews'),
							'type' => 'text' ),	
			'post_review_value3' => array( 
							'name' => 'post_review_value3',
							'title' => __('Post review value #3:', 'meganews'), 
							'description' => __('Insert review value #3 (number between 1-100).','meganews'),
							'type' => 'text' ),
			'post_review_title4' => array( 
							'name' => 'post_review_title4',
							'title' => __('Post review title #4:', 'meganews'), 
							'description' => __('Insert review title #4.','meganews'),
							'type' => 'text' ),	
			'post_review_value4' => array( 
							'name' => 'post_review_value4',
							'title' => __('Post review value #4:', 'meganews'), 
							'description' => __('Insert review value #4 (number between 1-100).','meganews'),
							'type' => 'text' ),	
			'post_review_title5' => array( 
							'name' => 'post_review_title5',
							'title' => __('Post review title #5:', 'meganews'), 
							'description' => __('Insert review title #5.','meganews'),
							'type' => 'text' ),	
			'post_review_value5' => array( 
							'name' => 'post_review_value5',
							'title' => __('Post review value #5:', 'meganews'), 
							'description' => __('Insert review value #5 (number between 1-100).','meganews'),
							'type' => 'text' )
							
);
	return apply_filters( 'hybrid_post_meta_boxes', $meta_boxes );
}

function post_meta_boxes() {
	global $post;
	$meta_boxes = hybrid_post_meta_boxes(); ?>

	<table class="form-table">
	<?php foreach ( $meta_boxes as $meta ) :

		$value = get_post_meta( $post->ID, $meta['name'], true );

		if ( $meta['type'] == 'text' )
			get_meta_text_input( $meta, $value );
		elseif ( $meta['type'] == 'heading' )
			get_meta_heading_post( $meta, $value );
		elseif ( $meta['type'] == 'textarea' )
			get_meta_textarea( $meta, $value );
		elseif ( $meta['type'] == 'select' )
			get_meta_select( $meta, $value );
		elseif ( $meta['type'] == 'upload' )
			get_meta_upload( $meta, $value );

	endforeach; ?>
	</table>
<?php
}



/**
 * Outputs a text input box with arguments from the
 * parameters.  Used for both the post/page meta boxes.
 *
 * @since 0.3
 * @param array $args
 * @param array string|bool $value
 */
function get_meta_text_input( $args = array(), $value = false ) {

	extract( $args ); ?>

	<tr>
		<th style="width:30%;">
			<label for="<?php echo $name; ?>"><b><?php echo $title; ?></b><br/><span  style="color:#777777;"><?php echo $description; ?></span></label>
		</th>
		<td>
			<input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo esc_html( $value );  ?>" size="30" tabindex="30" style="width: 97%;" />
			<input type="hidden" name="<?php echo $name; ?>_noncename" id="<?php echo $name; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
		</td>
	</tr>
	<?php
}


function get_meta_upload( $args = array(), $value = false ) {

	extract( $args ); ?>

	<tr>
		<th style="width:30%;">
			<label for="<?php echo $name; ?>"><b><?php echo $title; ?></b><br/><span  style="color:#777777;"><?php echo $description; ?></span></label>
		</th>
		<td>
			<label for="<?php echo $name; ?>">
    			 <input id="<?php echo $name; ?>_input" type="text" size="36" name="<?php echo $name; ?>" value="<?php echo esc_html( $value );  ?>" /> 
   				 <input id="<?php echo $name; ?>" class="upload_mp3_button_custom_field button" type="button" value="Upload MP3" />
   				 		
    			 <input type="hidden" name="<?php echo $name; ?>_noncename" id="<?php echo $name; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
			</label>
		</td>
	</tr>
	<?php
}

 
 
function get_meta_select( $args = array(), $value = false ) {

	extract( $args ); ?>

	<tr>
		<th style="width:30%;">
			<label for="<?php echo $name; ?>"><b><?php echo $title; ?></b><br/><span  style="color:#777777;"><?php echo $description; ?></span></label>
	
		</th>
		<td>
			<select style="width:100px;" name="<?php echo $name; ?>" id="<?php echo $name; ?>">
			<?php foreach ( $options as $option ) : ?>
				<option <?php if ( htmlentities( $value, ENT_QUOTES ) == $option ) echo ' selected="selected"'; ?>>
					<?php echo $option; ?>
				</option>
			<?php endforeach; ?>
			</select>
			<input type="hidden" name="<?php echo $name; ?>_noncename" id="<?php echo $name; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
		</td>
	</tr>
	<?php
}

/**
 * Outputs a textarea with arguments from the
 * parameters.  Used for both the post/page meta boxes.
 *
 * @since 0.3
 * @param array $args
 * @param array string|bool $value
 */
function get_meta_textarea( $args = array(), $value = false ) {

	extract( $args ); ?>

	<tr>
		<th style="width:30%;">
			<label for="<?php echo $name; ?>"><b><?php echo $title; ?></b><br/><span  style="color:#777777;"><?php echo $description; ?></span></label>
		</th>
		<td>
			<textarea name="<?php echo $name; ?>" id="<?php echo $name; ?>" cols="60" rows="4" tabindex="30" style="width: 97%;"><?php echo esc_html( $value );  ?></textarea>
			<input type="hidden" name="<?php echo $name; ?>_noncename" id="<?php echo $name; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
		</td>
	</tr>
	<?php
}

function get_meta_color( $args = array(), $value = false ) {

	extract( $args ); ?>

	<tr>
		<th style="width:30%;">
			<label for="<?php echo $name; ?>"><b><?php echo $title; ?></b><br/><span style="color:#777777;" ><?php echo $description; ?></span></label>
		</th>
		<td>
			<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/functions/css/colorpicker.css" type="text/css" media="screen" />
			<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/functions/js/jquery.js"></script>
			<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/functions/js/colorpicker.js"></script>	
			<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/functions/js/eye.js"></script>
			<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/functions/js/layout.js?ver=1.0.2"></script>
		   #<input type="text" maxlength="6" size="6" name="<?php echo $name; ?>"  id="colorpickerField1" value="<?php echo esc_html( $value );  ?>"  />
			<input type="hidden" name="<?php echo $name; ?>_noncename" id="<?php echo $name; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
		</td>
	</tr>
	<?php
}


function get_meta_heading_post( $args = array(), $value = false ) {

	extract( $args ); ?>
	<tr>
		<th style="width:30%;">
			<label for="<?php echo $name; ?>"><h1 style="font-size:14px;"><?php echo $title; ?></h1><span style="color:#777777;"><?php echo $description; ?></span></label>
		</th>		
	</tr>
	<?php
}




/**
 * Loops through each meta box's set of variables.
 * Saves them to the database as custom fields.
 *
 * @since 0.3
 * @param int $post_id
 */
function hybrid_save_meta_data( $post_id ) {
	global $post;


		$meta_boxes = array_merge( hybrid_post_meta_boxes() );

	foreach ( $meta_boxes as $meta_box ) :
		if ( $meta_box['type'] != 'heading' ) {
				
			if ((!isset($_POST[$meta_box['name'] . '_noncename']))  || ( !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], plugin_basename( __FILE__ ) ) ))
				return $post_id;

			if ( 'page' == $_POST['post_type'] && !current_user_can( 'edit_page', $post_id ) )
				return $post_id;

			elseif ( 'post' == $_POST['post_type'] && !current_user_can( 'edit_post', $post_id ) )
				return $post_id;

			$data = stripslashes( $_POST[$meta_box['name']] );

			if ( get_post_meta( $post_id, $meta_box['name'] ) == '' )
				add_post_meta( $post_id, $meta_box['name'], $data, true );

			elseif ( $data != get_post_meta( $post_id, $meta_box['name'], true ) )
				update_post_meta( $post_id, $meta_box['name'], $data );

			elseif ( $data == '' )
				delete_post_meta( $post_id, $meta_box['name'], get_post_meta( $post_id, $meta_box['name'], true ) );
		}
	endforeach;
	
}
?>