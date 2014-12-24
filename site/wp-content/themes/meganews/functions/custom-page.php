<?php
//page editing
   add_action( 'admin_menu', 'hybrid_create_meta_box_page' );
   add_action( 'save_post', 'hybrid_save_meta_data_page' );


function hybrid_create_meta_box_page() {
	global $theme_name;

	add_meta_box( 'page-meta-boxes', __('Page settings', "meganews"), 'page_meta_boxes', 'page', 'normal', 'default' );
}


function hybrid_page_meta_boxes() {

	/* Array of the meta box options. */
	$meta_boxes = array(
			'pego_page_show_breadcrumbs' => array( 
							'name' => 'pego_page_show_breadcrumbs', 
							'title' => __('Show breadcrumbs?', 'meganews'), 
							'description' => __('Select Yes to make breadcrumbs visible on the current page.', 'meganews'), 
							'type' => "select", 
							'std' => __('Yes','meganews'),
							'options' => array(__('Yes','meganews'),__('No','meganews')))
	);

	return apply_filters( 'hybrid_page_meta_boxes', $meta_boxes );
}

function page_meta_boxes() {
	global $post;
	$meta_boxes = hybrid_page_meta_boxes(); ?>

	<table class="form-table pego-page-settings">
	<?php foreach ( $meta_boxes as $meta ) :

		$value = stripslashes( get_post_meta( $post->ID, $meta['name'], true ) );

		if ( $meta['type'] == 'text' )
			get_meta_text_input_page( $meta, $value );
		elseif ( $meta['type'] == 'heading' )
			get_meta_heading( $meta, $value );
		elseif ( $meta['type'] == 'textarea' )
			get_meta_textarea_page( $meta, $value );
		elseif ( $meta['type'] == 'select' )
			get_meta_select_page( $meta, $value );
		elseif ( $meta['type'] == 'radioimg' )
			get_meta_radioimg_page( $meta, $value );
			elseif ( $meta['type'] == 'upload' )
			get_meta_upload_page( $meta, $value );

	endforeach; ?>
	</table>
<?php
}

function get_meta_text_input_page( $args = array(), $value = false ) {

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

function get_meta_upload_page( $args = array(), $value = false ) {

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




function get_meta_select_page( $args = array(), $value = false ) {

	extract( $args ); ?>

	<tr>
		<th style="width:30%;">
			<label for="<?php echo $name; ?>"><b><?php echo $title; ?></b><br/><span style="color:#777777;" ><?php echo $description; ?></span></label>
		</th>
		<td>
			<select style="width:90px;" name="<?php echo $name; ?>" id="<?php echo $name; ?>">
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


function get_meta_radioimg_page( $args = array(), $value = false ) {

	extract( $args ); ?>

	<tr>
		<th style="width:30%;">
			<label for="<?php echo $name; ?>"><b><?php echo $title; ?></b><br/><span style="color:#777777;" ><?php echo $description; ?></span></label>
		</th>
		<td>
			
			<?php 
			$st=0;
			foreach ( $options as $key => $valueradioimg ) : 
				$st++;
			?>
				  <label class="radioImgClass <?php echo $name; ?>" for="<?php echo $key; ?>">
   					 <input id="<?php echo $key; ?>" type="radio" name="<?php echo $name; ?>" value="<?php echo $key; ?>" <?php if (( htmlentities( $value, ENT_QUOTES ) == $key ) || (($value != true) && ($st==1))) echo ' checked'; ?> />
    					<img src="<?php echo $valueradioimg['img'] ?>">
  					</label>
  					
			<?php endforeach; ?>
			
			<input type="hidden" name="<?php echo $name; ?>_noncename" id="<?php echo $name; ?>_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
		</td>
	</tr>
	<?php
}


function get_meta_color_page( $args = array(), $value = false ) {

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



function get_meta_textarea_page( $args = array(), $value = false ) {

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

function get_meta_heading( $args = array(), $value = false ) {

	extract( $args ); ?>

	<tr>
		<th style="width:30%;">
			<label class="settings_heading" for="<?php echo $name; ?>"><h1><?php echo $title; ?></h1><span style="color:#777777;"><?php echo $description; ?></span></label>
		</th>		
	</tr>
	<?php
}


function hybrid_save_meta_data_page( $post_id ) {
	global $post;
	
	$meta_boxes = array_merge( hybrid_page_meta_boxes() );
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