<?php

// creating testimonials 

add_action('init', 'testimonials_register');
 
function testimonials_register() {
 
	$labels = array(
		'name' => __('Testimonials', "meganews"),
		'singular_name' => __('Testimonials Item',  "meganews"),
		'add_new' => __('Add New',  "meganews"),
		'add_new_item' => __('Add New Testimonials Item', "meganews"),
		'edit_item' => __('Edit Testimonials Item', "meganews"),
		'new_item' => __('New Testimonials Item', "meganews"),
		'view_item' => __('View Testimonials Item', "meganews"),
		'search_items' => __('Search Testimonials', "meganews"),
		'not_found' =>  __('Nothing found', "meganews"),
		'not_found_in_trash' => __('Nothing found in Trash', "meganews"),
		'parent_item_colon' => ''
	);
 
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','thumbnail', 'custom-fields')
	  ); 
 
	register_post_type( 'testimonials' , $args );
}

//testimonials editing

	add_action( 'admin_menu', 'hybrid_create_meta_box_testimonials' );
	add_action( 'save_post', 'hybrid_save_meta_data_testimonials' );

function hybrid_create_meta_box_testimonials() {
	global $theme_name;
	add_meta_box( 'post-meta-boxes_testimonials', __('Testimonials options', "meganews"), 'post_meta_boxes_testimonials', 'testimonials', 'normal', 'default' );
}


function hybrid_post_meta_boxes_testimonials() {
	
	/* Array of the meta box options. */
	$meta_boxes = array(				
			'test_name' => array( 
							'name' => 'test_name', 
							'title' => __(' Author name', 'meganews'), 
							'description' => __('Enter testimonial author name.', 'meganews'), 
							'type' => 'text' ),
			'test_image' => array( 
							'name' => 'test_image', 
							'title' => __(' Author image', 'meganews'), 
							'description' => __('Upload image for author.', 'meganews'), 
							'type' => "upload")	
	);

	return apply_filters( 'hybrid_post_meta_boxes_testimonials', $meta_boxes );
}




function post_meta_boxes_testimonials() {
	global $post;
	$meta_boxes = hybrid_post_meta_boxes_testimonials(); ?>

	<table class="form-table">
	<?php foreach ( $meta_boxes as $meta ) :

		$value = get_post_meta( $post->ID, $meta['name'], true );

		if ( $meta['type'] == 'text' )
			get_meta_text_input_testimonials( $meta, $value );
		elseif ( $meta['type'] == 'heading' )
			get_meta_heading_testimonials( $meta, $value );
		elseif ( $meta['type'] == 'textarea' )
			get_meta_textarea_testimonials( $meta, $value );
		elseif ( $meta['type'] == 'select' )
			get_meta_select_testimonials( $meta, $value );		
		elseif ( $meta['type'] == 'color' )
			get_meta_color_testimonials( $meta, $value );
		elseif ( $meta['type'] == 'upload' )
			get_meta_upload_testimonials( $meta, $value );

	endforeach; ?>
	</table>
<?php
}


function get_meta_upload_testimonials( $args = array(), $value = false ) {

	extract( $args ); ?>

	<tr>
		<th style="width:30%;">
			<label for="<?php echo $name; ?>"><b><?php echo $title; ?></b><br/><span  style="color:#777777;"><?php echo $description; ?></span></label>
		</th>
		<td>
			<label for="<?php echo $name; ?>">
    			 <input id="<?php echo $name; ?>_input" type="text" size="36" name="<?php echo $name; ?>" value="<?php echo esc_html( $value );  ?>" /> 
   				 <input id="<?php echo $name; ?>" class="upload_image_button_custom_field button" type="button" value="Upload Image" />
   				 		<div class="clear"></div><img  id="<?php echo $name; ?>_preview_img" class="uploader_image_preview_custom_field" alt="" src="<?php echo $value; ?>" /> 
    			 <input type="hidden" name="<?php echo $name; ?>_noncename" id="<?php echo $name; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
			</label>
		</td>
	</tr>
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
function get_meta_text_input_testimonials( $args = array(), $value = false ) {

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

/**
 * Outputs a select box with arguments from the
 * parameters.  Used for__(' Type:', 'meganews'),  both the post/page meta boxes.
 *
 * @since 0.3
 * @param array $args
 * @param array string|bool $value
 */
function get_meta_select_testimonials( $args = array(), $value = false ) {

	extract( $args ); ?>

	<tr>
		<th style="width:30%;">
			<label for="<?php echo $name; ?>"><b><?php echo $title; ?></b><br/><span style="color:#777777;" ><?php echo $description; ?></span></label>
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
function get_meta_textarea_testimonials( $args = array(), $value = false ) {

	extract( $args ); ?>

	<tr>
		<th style="width:30%;">
			<label for="<?php echo $name; ?>"><b><?php echo $title; ?></b><br/><span style="color:#777777;"><?php echo $description; ?></span></label>
		</th>
		<td>
			<textarea name="<?php echo $name; ?>" id="<?php echo $name; ?>" cols="60" rows="4" tabindex="30" style="width: 97%;"><?php echo esc_html( $value );  ?></textarea>
			<input type="hidden" name="<?php echo $name; ?>_noncename" id="<?php echo $name; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
		</td>
	</tr>
	<?php
}

function get_meta_color_testimonials( $args = array(), $value = false ) {

	extract( $args ); ?>

	<tr>
		<th style="width:30%;">
			<label for="<?php echo $name; ?>"><b><?php echo $title; ?></b><br/><span style="color:#777777;" ><?php echo $description; ?></span></label>
		</th>
		<td>
			<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/admin/css/colorpicker.css" type="text/css" media="screen" />
			<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/admin/js/colorpicker.js"></script>	
		   #<input type="text" maxlength="6" size="6" name="<?php echo $name; ?>"  id="colorpickerField1" value="<?php echo esc_html( $value );  ?>"  />
			<input type="hidden" name="<?php echo $name; ?>_noncename" id="<?php echo $name; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
		</td>
	</tr>
	<?php
}


function get_meta_heading_testimonials( $args = array(), $value = false ) {

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
function hybrid_save_meta_data_testimonials( $post_id ) {
	global $post;

		$meta_boxes = array_merge( hybrid_post_meta_boxes_testimonials() );

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