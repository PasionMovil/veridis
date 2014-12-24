<?php
/**
 * WPBakery Visual Composer Shortcodes settings
 *
 * @package VPBakeryVisualComposer
 *
 */
$vc_is_wp_version_3_6_more = version_compare(preg_replace('/^([\d\.]+)(\-.*$)/', '$1', get_bloginfo('version')), '3.6') >= 0;
// Used in "Button" and "Call to Action" blocks
//$colors_arr = array(__("Grey", "meganews") => "wpb_button", __("Blue", "meganews") => "btn-primary", __("Turquoise", "meganews") => "btn-info", __("Green", "meganews") => "btn-success", __("Orange", "meganews") => "btn-warning", __("Red", "meganews") => "btn-danger", __("Black", "meganews") => "btn-inverse");

$colors_arr = array(__("Main color", "meganews") => "btn_red", __("Grey", "meganews") => "btn_grey", __("Blue", "meganews") => "btn_blue", __("Turquoise", "meganews") => "btn_turquoise", __("Green", "meganews") => "btn_green", __("Orange", "meganews") => "btn_orange", __("Black", "meganews") => "btn_black");

// Used in "Button" and "Call to Action" blocks
$size_arr = array(__("Regular size", "meganews") => "wpb_regularsize", __("Large", "meganews") => "btn-large", __("Small", "meganews") => "btn-small", __("Mini", "meganews") => "btn-mini");

$target_arr = array(__("Same window", "meganews") => "_self", __("New window", "meganews") => "_blank");


$allIcons = pego_get_all_icons();

// get all testimonials
$allTests = pego_get_all_test();
$list_testimonials = '';
$number_of_testimonials_item = count($allTests);
$current=0;
if ($allTests) {
	foreach($allTests as $value) {
		$current++;
		if ($current == $number_of_testimonials_item) {
			$list_testimonials  .= " and ".$value;	
		}
		else
		{
			$list_testimonials  .= $value.", ";
		}
		
	}	
}

$allCategoriesList = pego_get_all_categories();

$list_cats= '';
$number_of_cats_item = count($allCategoriesList);
$current1=0;
if ($allCategoriesList) {
	foreach($allCategoriesList as $value) {
		$current1++;
		if ($current1 == $number_of_cats_item) {
			$list_cats  .= $value;	
		}
		else
		{
			$list_cats  .= $value.", ";
		}
		
	}	
}

$allIcons4 = array(
    __("None", "meganews") => "none",
	  "" => "icon-plus",
    __("Alarm clock icon", "meganews") => "wpb_alarm_clock",
    __("Anchor icon", "meganews") => "wpb_anchor",
    __("Application Image icon", "meganews") => "wpb_application_image",
    __("Arrow icon", "meganews") => "wpb_arrow",
    __("Asterisk icon", "meganews") => "wpb_asterisk",
    __("Hammer icon", "meganews") => "wpb_hammer",
    __("Balloon icon", "meganews") => "wpb_balloon",
    __("Balloon Buzz icon", "meganews") => "wpb_balloon_buzz",
    __("Balloon Facebook icon", "meganews") => "wpb_balloon_facebook",
    __("Balloon Twitter icon", "meganews") => "wpb_balloon_twitter",
    __("Battery icon", "meganews") => "wpb_battery",
    __("Binocular icon", "meganews") => "wpb_binocular",
    __("Document Excel icon", "meganews") => "wpb_document_excel",
    __("Document Image icon", "meganews") => "wpb_document_image",
    __("Document Music icon", "meganews") => "wpb_document_music",
    __("Document Office icon", "meganews") => "wpb_document_office",
    __("Document PDF icon", "meganews") => "wpb_document_pdf",
    __("Document Powerpoint icon", "meganews") => "wpb_document_powerpoint",
    __("Document Word icon", "meganews") => "wpb_document_word",
    __("Bookmark icon", "meganews") => "wpb_bookmark",
    __("Camcorder icon", "meganews") => "wpb_camcorder",
    __("Camera icon", "meganews") => "wpb_camera",
    __("Chart icon", "meganews") => "wpb_chart",
    __("Chart pie icon", "meganews") => "wpb_chart_pie",
    __("Clock icon", "meganews") => "wpb_clock",
    __("Fire icon", "meganews") => "wpb_fire",
    __("Heart icon", "meganews") => "wpb_heart",
    __("Mail icon", "meganews") => "wpb_mail",
    __("Play icon", "meganews") => "wpb_play",
    __("Shield icon", "meganews") => "wpb_shield",
    __("Video icon", "meganews") => "wpb_video"
);



$argsPosts = array('post_type'=> 'post', 'posts_per_page' => -1, 'order'=> 'DESC', 'orderby' => 'post_date'  );
$postsPosts= get_posts($argsPosts);	
$allPosts= array();
$allPosts[0] = '';
if($postsPosts) {
	foreach($postsPosts as $postsPost)
	{ 
		$allPosts[$postsPost->ID] = $postsPost->post_title;	
	}
}

$add_css_animation = array(
  "type" => "dropdown",
  "heading" => __("CSS Animation", "meganews"),
  "param_name" => "css_animation",
  "admin_label" => true,
  "value" => array(__("No", "meganews") => '', __("Top to bottom", "meganews") => "top-to-bottom", __("Bottom to top", "meganews") => "bottom-to-top", __("Left to right", "meganews") => "left-to-right", __("Right to left", "meganews") => "right-to-left", __("Appear from center", "meganews") => "appear"),
  "description" => __("Select animation type if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.", "meganews")
);

vc_map( array(
  "name" => __("Row", "meganews"),
  "base" => "vc_row",
  "is_container" => true,
  "icon" => "icon-wpb-row",
  "show_settings_on_create" => false,
  "category" => __('Content', 'meganews'),
  "params" => array(
    array(
      "type" => 'checkbox',
      "heading" => __("Remove background container?", "meganews"),
      "param_name" => "bg_container_remove",
      "description" => __("Check to remove the background container.", "meganews"),
      "value" => Array(__("Yes", "meganews") => 'yes')
    )
  ),
  "js_view" => 'VcRowView'
) );
vc_map( array(
  "name" => __("Row", "meganews"), //Inner Row
  "base" => "vc_row_inner",
  "content_element" => false,
  "is_container" => true,
  "icon" => "icon-wpb-row",
  "show_settings_on_create" => false,
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  ),
  "js_view" => 'VcRowView'
) );
vc_map( array(
  "name" => __("Column", "meganews"),
  "base" => "vc_column",
  "is_container" => true,
  "content_element" => false,
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  ),
  "js_view" => 'VcColumnView'
) );

/* Text Block
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Text Block", "meganews"),
  "base" => "vc_column_text",
  "icon" => "icon-wpb-layer-shape-text",
  "wrapper_class" => "clearfix",
  "category" => __('Content', 'meganews'),
  "params" => array(
    array(
      "type" => "textarea_html",
      "holder" => "div",
      "heading" => __("Text", "meganews"),
      "param_name" => "content",
      "value" => __("<p>I am text block. Click edit button to change this text.</p>", "meganews")
    ),
    $add_css_animation,
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
) );


  vc_map( array(
  "name" => __("Post section", "meganews"),
  "base" => "vc_post_section",
  "icon" => "icon-wpb-film-youtube",
  "category" => __('Content', 'meganews'),
  "params" => array(
   	array(
      "type" => "my_param",
      "heading" => __("Post section type", "meganews"),
      "param_name" => "post_section_type",
      "value" => array(__('Type1', "meganews") => "type1", __('Type2', "meganews") => "type2", __('Type3', "meganews") => "type3", __('Type4', "meganews") => "type4", __('Type5', "meganews") => "type5", __('Type6', "meganews") => "type6"),
      "description" => __("Select post section type.", "meganews")
	  ),
	array(
      "type" => "dropdown",
      "heading" => __("Number of posts in slider", "meganews"),
      "param_name" => "type6_index",
      "value" => array(__('2', "meganews") => 2, __('3', "meganews") => 3, __('4', "meganews") => 4, __('5', "meganews") => 5),
      "description" => __("Select number of posts that will display in the slider. Setting is used only for the last post section type(set above).", "meganews"),
      "dependency" => Array('element' => "post_section_type", 'value' => array('type6'))
	  ),
	 array(
      "type" => "dropdown",
      "heading" => __("Fill posts with:", "meganews"),
      "param_name" => "postfill",
      "value" => array( __("Latest", "meganews") => 'latest', __("Random", "meganews") => 'random' ),
      "description" => __("Select how to fill the posts.", "meganews")
	),
	array(
      "type" => "textfield",
      "heading" => __("Start at post on position: ", "meganews"),
      "param_name" => "grid_teasers_offset",
      "description" => __('If post fill is set to Latest, you may define with which item (enter number of the post position) it should start.', "meganews"),
      "dependency" => Array('element' => "postfill", 'value' => array('latest'))
    ),
	array(
      "type" => "dropdown",
      "heading" => __("Post on position #1", "meganews"),
      "param_name" => "postposition1",
      "value" => $allPosts,
      "description" => __("Select post.", "meganews"),
	  "dependency" => Array('element' => "postfill", 'value' => array('random'))
	),
	array(
      "type" => "dropdown",
      "heading" => __("Post on position #2", "meganews"),
      "param_name" => "postposition2",
      "value" => $allPosts,
      "description" => __("Select post.", "meganews"),
	  "dependency" => Array('element' => "postfill", 'value' => array('random'))
	),
	array(
      "type" => "dropdown",
      "heading" => __("Post on position #3", "meganews"),
      "param_name" => "postposition3",
      "value" => $allPosts,
      "description" => __("Select post.", "meganews"),
	  "dependency" => Array('element' => "postfill", 'value' => array('random'))
	),
	array(
      "type" => "dropdown",
      "heading" => __("Post on position #4", "meganews"),
      "param_name" => "postposition4",
      "value" => $allPosts,
      "description" => __("Select post.", "meganews"),
	  "dependency" => Array('element' => "postfill", 'value' => array('random'))
	),
	array(
      "type" => "dropdown",
      "heading" => __("Post on position #5", "meganews"),
      "param_name" => "postposition5",
      "value" => $allPosts,
      "description" => __("Select post.", "meganews"),
	  "dependency" => Array('element' => "postfill", 'value' => array('random'))
	),
	array(
      "type" => "dropdown",
      "heading" => __("Post on position #6", "meganews"),
      "param_name" => "postposition6",
      "value" => $allPosts,
      "description" => __("Select post.", "meganews"),
	  "dependency" => Array('element' => "postfill", 'value' => array('random'))
	),
	array(
      "type" => "dropdown",
      "heading" => __("Post on position #7", "meganews"),
      "param_name" => "postposition7",
      "value" => $allPosts,
      "description" => __("Select post.", "meganews"),
	  "dependency" => Array('element' => "postfill", 'value' => array('random'))
	),
	array(
      "type" => "dropdown",
      "heading" => __("Post on position #8", "meganews"),
      "param_name" => "postposition8",
      "value" => $allPosts,
      "description" => __("Select post.", "meganews"),
	  "dependency" => Array('element' => "postfill", 'value' => array('random'))
	)
  )
 // ,  "js_view" => 'VcColLeftIconView'
) );



 vc_map( array(
  "name" => __("Post ticker", "meganews"),
  "base" => "vc_post_ticker",
  "icon" => "icon-wpb-film-youtube",
  "category" => __('Content', 'meganews'),
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Number of posts", "meganews"),
      "param_name" => "number_of_post",
      "description" => __("Set number of posts. By default 10 will be taken.", "meganews")
    )
  )
 // ,  "js_view" => 'VcColLeftIconView'
) );
 
 
 
/* Teaser grid
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Post Grid", "meganews"),
  "base" => "vc_teaser_grid",
  "icon" => "icon-wpb-application-icon-large",
  "category" => __('Content', 'meganews'),
  "params" => array(
    array(
      "type" => "my_param2",
      "heading" => __("Show Type", "meganews"),
      "param_name" => "showtype",
      "value" => array( __("Type1", "meganews") => "type1", __("Type2", "meganews") => "type2", __("Type3", "meganews") => "type3", __("Type4", "meganews") => "type4"),
      "description" => __('Select show type.', 'meganews')
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Columns count", "meganews"),
      "param_name" => "grid_columns_count",
      "value" => array(5, 4, 3, 2, 1),
      "admin_label" => true,
      "description" => __("Select columns count.", "meganews"),
      "dependency" => Array('element' => 'showtype', 'value' => array('type1'))
    ),
    array(
      "type" => "textfield",
      "heading" => __("Summary length", "meganews"),
      "param_name" => "summary_length",
      "description" => __('Enter summary length. Enter number only, this will present number of chars before it gets cut. If left empty, the entire post summay will be displayed.', "meganews"),
	  "dependency" => Array('element' => 'grid_posttypes', 'value' => array('post'))
    ), 
    array(
      "type" => "textfield",
      "heading" => __("Items count", "meganews"),
      "param_name" => "grid_teasers_count",
      "description" => __('How many items to show? Enter number or word "All".', "meganews")
    ),
  	array(
      "type" => "textfield",
      "heading" => __("Start at post on position: ", "meganews"),
      "param_name" => "grid_teasers_offset_number",
      "description" => __('Define with which post item (enter number of the post position) to start. If empty it will start with the latest one. ', "meganews"),
      "dependency" => Array('element' => "postfill", 'value' => array('latest'))
    ),
    array(
      "type" => "exploded_textarea",
      "heading" => __("Categories", "meganews"),
      "param_name" => "grid_categories",
      "description" => __("If you want to narrow output, enter category names here. You may choose from: <strong>".$list_cats."</strong>. Note: Only listed categories will be included. Divide categories with linebreaks (Enter).", "meganews")
    ),
    array(
      "type" => "textfield",
      "heading" => __("Thumbnail size", "meganews"),
      "param_name" => "grid_thumb_size",
      "description" => __('Enter thumbnail size. Example: You may enter image size in pixels: 200x100 (Width x Height) to crop images to same size or enter full for original images dimensions.', "meganews")
    )
  )
) );




/* Textual block
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Titles", "meganews"),
  "base" => "vc_text_titles",
  "icon" => "icon-wpb-ui-separator-label",
  "category" => __('Content', 'meganews'),
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Title", "meganews"),
      "param_name" => "title",
      "holder" => "div",
      "value" => __("Title", "meganews"),
      "description" => __("Title content.", "meganews")
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Title type", "meganews"),
      "param_name" => "title_type",
      "value" => array(__('h1', "meganews") => "h1", __('h2', "meganews") => "h2", __('h3', "meganews") => "h3", __('h4', "meganews") => "h4", __('h5', "meganews") => "h5") ,
      "description" => __("Select title type.", "meganews")
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Page title type", "meganews"),
      "param_name" => "page_title_type",
      "value" => array(__('Version #1', "meganews") => "v1", __('Version #2', "meganews") => "v2", __('Version #3', "meganews") => "v3", __('Version #4', "meganews") => "v4") ,
      "description" => __("Select page title type.", "meganews"),
      "dependency" => Array('element' => "title_type", 'value' => array('heading_page_title_type'))
    ),
	array(
      "type" => "textfield",
      "heading" => __("Page title text", "meganews"),
      "param_name" => "page_title_text_heading",
      "value" => __("", "meganews"),
      "description" => __("Page title text [optional]", "meganews"),
      "dependency" => Array('element' => "title_type", 'value' => array('heading_page_title_type'))
    ),
   array(
      "type" => "dropdown",
      "heading" => __("Title alignment", "meganews"),
      "param_name" => "title_align",
      "value" => array(__('Left', "meganews") => "left", __('Center', "meganews") => "center", __('Right', "meganews") => "right") ,
      "description" => __("Select title alignment. ", "meganews")
    ),
    $add_css_animation,
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews"),
	  "dependency" => Array('element' => "title_heading", 'value' => array('none', 'h1', 'h2', 'h3', 'h4', 'h5'))
    )
  ),
  "js_view" => 'VcTextSeparatorView'
) );





 /* Contact info
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Icon + text", "meganews"),
  "base" => "vc_contact_info",
  "icon" => "icon-wpb-film-youtube",
  "category" => __('Content', 'meganews'),
  "params" => array(  
   array(
      "type" => "my_param1",
      "heading" => __("Icon", "meganews"),
      "param_name" => "icon",
      "value" => $allIcons,
      "description" => __("Select icon.", "meganews")
    ),
   array(
      "type" => "textarea_html",    
      "heading" => __("Content under chart", "meganews"),
      "param_name" => "content",
      "value" => __("<p></p>", "meganews")
    ),
    $add_css_animation,
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )	
  
  )  
) );



/* Support for 3rd Party plugins
---------------------------------------------------------- */
// Contact form 7 plugin
include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); // Require plugin.php to use is_plugin_active() below
if (is_plugin_active('contact-form-7/wp-contact-form-7.php')) {
  global $wpdb;
  $cf7 = $wpdb->get_results( 
  	"
  	SELECT ID, post_title 
  	FROM $wpdb->posts
  	WHERE post_type = 'wpcf7_contact_form' 
  	"
  );
  $contact_forms = array();
  if ($cf7) {
    foreach ( $cf7 as $cform ) {
      $contact_forms[$cform->post_title] = $cform->ID;
    }
  } else {
    $contact_forms["No contact forms found"] = 0;
  }
  vc_map( array(
    "base" => "contact-form-7",
    "name" => __("Contact Form 7", "meganews"),
    "icon" => "icon-wpb-contactform7",
    "category" => __('Content', 'meganews'),
    "params" => array(
      array(
        "type" => "textfield",
        "heading" => __("Form title", "meganews"),
        "param_name" => "title",
        "admin_label" => true,
        "description" => __("What text use as form title. Leave blank if no title is needed.", "meganews")
      ),
      array(
        "type" => "dropdown",
        "heading" => __("Select contact form", "meganews"),
        "param_name" => "id",
        "value" => $contact_forms,
        "description" => __("Choose previously created contact form from the drop down list.", "meganews")
      )
    )
  ) );
} // if contact form7 plugin active


/* Message box
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Message Box", "meganews"),
  "base" => "vc_message",
  "icon" => "icon-wpb-information-white",
  "wrapper_class" => "alert",
  "category" => __('Content', 'meganews'),
  "params" => array(
    array(
      "type" => "dropdown",
      "heading" => __("Message box type", "meganews"),
      "param_name" => "color",
      "value" => array(__('Informational', "meganews") => "alert-info", __('Warning', "meganews") => "alert-block", __('Success', "meganews") => "alert-success", __('Error', "meganews") => "alert-error"),
      "description" => __("Select message type.", "meganews")
    ),
    array(
      "type" => "textarea_html",
      "holder" => "div",
      "class" => "messagebox_text",
      "heading" => __("Message text", "meganews"),
      "param_name" => "content",
      "value" => __("<p>I am message box. Click edit button to change this text.</p>", "meganews")
    ),
    $add_css_animation,
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  ),
  "js_view" => 'VcMessageView'
) );



/* Toggle (FAQ)
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Toggle", "meganews"),
  "base" => "vc_toggle",
  "icon" => "icon-wpb-toggle-small-expand",
  "category" => __('Content', 'meganews'),
  "params" => array(
    array(
      "type" => "textfield",
      "holder" => "h4",
      "class" => "toggle_title",
      "heading" => __("Toggle title", "meganews"),
      "param_name" => "title",
      "value" => __("Toggle title", "meganews"),
      "description" => __("Toggle block title.", "meganews")
    ),
    array(
      "type" => "textarea_html",
      "holder" => "div",
      "class" => "toggle_content",
      "heading" => __("Toggle content", "meganews"),
      "param_name" => "content",
      "value" => __("<p>Toggle content goes here, click edit button to change this text.</p>", "meganews"),
      "description" => __("Toggle block content.", "meganews")
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Default state", "meganews"),
      "param_name" => "open",
      "value" => array(__("Closed", "meganews") => "false", __("Open", "meganews") => "true"),
      "description" => __('Select "Open" if you want toggle to be open by default.', "meganews")
    ),
    $add_css_animation,
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  ),
  "js_view" => 'VcToggleView'
) );

/* Single image */
vc_map( array(
  "name" => __("Single Image", "meganews"),
  "base" => "vc_single_image",
  "icon" => "icon-wpb-single-image",
  "category" => __('Content', 'meganews'),
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Widget title", "meganews"),
      "param_name" => "title",
      "description" => __("What text use as a widget title. Leave blank if no title is needed.", "meganews")
    ),
    array(
      "type" => "attach_image",
      "heading" => __("Image", "meganews"),
      "param_name" => "image",
      "value" => "",
      "description" => __("Select image from media library.", "meganews")
    ),
    array(
      "type" => "textfield",
      "heading" => __("Image size", "meganews"),
      "param_name" => "img_size",
      "description" => __("Enter thumbnail size. Example: You may enter image size in pixels: 200x100 (Width x Height) to crop images to same size or enter full for original images dimensions. If emtpy full dimensions will be taken.", "meganews")
    ),
    array(
      "type" => 'checkbox',
      "heading" => __("Link to large image?", "meganews"),
      "param_name" => "img_link_large",
      "description" => __("If selected, image will be linked to the bigger image.", "meganews"),
      "value" => Array(__("Yes, please", "meganews") => 'yes')
    ),
    array(
      "type" => "textfield",
      "heading" => __("Image link", "meganews"),
      "param_name" => "img_link",
      "description" => __("Enter url if you want this image to have link.", "meganews"),
      "dependency" => Array('element' => "img_link_large", 'is_empty' => true, 'callback' => 'wpb_single_image_img_link_dependency_callback')
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Link Target", "meganews"),
      "param_name" => "img_link_target",
      "value" => $target_arr,
      "dependency" => Array('element' => "img_link_large", 'is_empty' => true)
    ),
    array(
      "type" => "textfield",
      "heading" => __("Margin top", "meganews"),
      "param_name" => "margin_top",
      "description" => __("Enter margin top for the image. Enter number only. [optional]", "meganews")
    ),
    array(
      "type" => "textfield",
      "heading" => __("Margin bottom", "meganews"),
      "param_name" => "margin_bottom",
      "description" => __("Enter margin bottom for the image. Enter number only. [optional]", "meganews")
    ),
     $add_css_animation,
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
));

/* Gallery/Slideshow
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Image Gallery", "meganews"),
  "base" => "vc_gallery",
  "icon" => "icon-wpb-images-stack",
  "category" => __('Content', 'meganews'),
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Widget title", "meganews"),
      "param_name" => "title",
      "description" => __("What text use as a widget title. Leave blank if no title is needed.", "meganews")
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Gallery type", "meganews"),
      "param_name" => "type",
      "value" => array(__("Flex slider fade", "meganews") => "flexslider_fade", __("Flex slider slide", "meganews") => "flexslider_slide", __("Nivo slider", "meganews") => "nivo", __("Image grid", "meganews") => "image_grid"),
      "description" => __("Select gallery type.", "meganews")
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Auto rotate slides", "meganews"),
      "param_name" => "interval",
      "value" => array(3, 5, 10, 15, __("Disable", "meganews") => 0),
      "description" => __("Auto rotate slides each X seconds.", "meganews"),
      "dependency" => Array('element' => "type", 'value' => array('flexslider_fade', 'flexslider_slide', 'nivo'))
    ),
    array(
      "type" => "attach_images",
      "heading" => __("Images", "meganews"),
      "param_name" => "images",
      "value" => "",
      "description" => __("Select images from media library.", "meganews")
    ),
    array(
      "type" => "textfield",
      "heading" => __("Image size", "meganews"),
      "param_name" => "img_size",
      "description" => __("Enter image size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use 'thumbnail' size.", "meganews")
    ),
    array(
      "type" => "dropdown",
      "heading" => __("On click", "meganews"),
      "param_name" => "onclick",
      "value" => array(__("Open prettyPhoto", "meganews") => "link_image", __("Do nothing", "meganews") => "link_no", __("Open custom link", "meganews") => "custom_link"),
      "description" => __("What to do when slide is clicked?", "meganews")
    ),
    array(
      "type" => "exploded_textarea",
      "heading" => __("Custom links", "meganews"),
      "param_name" => "custom_links",
      "description" => __('Enter links for each slide here. Divide links with linebreaks (Enter).', 'meganews'),
      "dependency" => Array('element' => "onclick", 'value' => array('custom_link'))
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Custom link target", "meganews"),
      "param_name" => "custom_links_target",
      "description" => __('Select where to open  custom links.', 'meganews'),
      "dependency" => Array('element' => "onclick", 'value' => array('custom_link')),
      'value' => $target_arr
    ),
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
) );


/* Tabs
---------------------------------------------------------- */
$tab_id_1 = time().'-1-'.rand(0, 100);
$tab_id_2 = time().'-2-'.rand(0, 100);
vc_map( array(
  "name"  => __("Tabs", "meganews"),
  "base" => "vc_tabs",
  "show_settings_on_create" => false,
  "is_container" => true,
  "icon" => "icon-wpb-ui-tab-content",
  "category" => __('Content', 'meganews'),
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Widget title", "meganews"),
      "param_name" => "title",
      "description" => __("What text use as a widget title. Leave blank if no title is needed.", "meganews")
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Auto rotate tabs", "meganews"),
      "param_name" => "interval",
      "value" => array(__("Disable", "meganews") => 0, 3, 5, 10, 15),
      "description" => __("Auto rotate tabs each X seconds.", "meganews")
    ),
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  ),
  "custom_markup" => '
  <div class="wpb_tabs_holder wpb_holder vc_container_for_children">
  <ul class="tabs_controls">
  </ul>
  %content%
  </div>'
  ,
  'default_content' => '
  [vc_tab title="'.__('Tab 1','meganews').'" tab_id="'.$tab_id_1.'"][/vc_tab]
  [vc_tab title="'.__('Tab 2','meganews').'" tab_id="'.$tab_id_2.'"][/vc_tab]
  ',
  "js_view" => ($vc_is_wp_version_3_6_more ? 'VcTabsView' : 'VcTabsView35')
) );

/* Tour section
---------------------------------------------------------- */
$tab_id_1 = time().'-1-'.rand(0, 100);
$tab_id_2 = time().'-2-'.rand(0, 100);
WPBMap::map( 'vc_tour', array(
  "name" => __("Tour Section", "meganews"),
  "base" => "vc_tour",
  "show_settings_on_create" => false,
  "is_container" => true,
  "container_not_allowed" => true,
  "icon" => "icon-wpb-ui-tab-content-vertical",
  "category" => __('Content', 'meganews'),
  "wrapper_class" => "clearfix",
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Widget title", "meganews"),
      "param_name" => "title",
      "description" => __("What text use as a widget title. Leave blank if no title is needed.", "meganews")
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Auto rotate slides", "meganews"),
      "param_name" => "interval",
      "value" => array(__("Disable", "meganews") => 0, 3, 5, 10, 15),
      "description" => __("Auto rotate slides each X seconds.", "meganews")
    ),
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  ),
  "custom_markup" => '  
  <div class="wpb_tabs_holder wpb_holder clearfix vc_container_for_children">
  <ul class="tabs_controls">
  </ul>
  %content%
  </div>'
  ,
  'default_content' => '
  [vc_tab title="'.__('Slide 1','meganews').'" tab_id="'.$tab_id_1.'"][/vc_tab]
  [vc_tab title="'.__('Slide 2','meganews').'" tab_id="'.$tab_id_2.'"][/vc_tab]
  ',
  "js_view" => ($vc_is_wp_version_3_6_more ? 'VcTabsView' : 'VcTabsView35')
) );

vc_map( array(
  "name" => __("Tab", "meganews"),
  "base" => "vc_tab",
  "allowed_container_element" => 'vc_row',
  "is_container" => true,
  "content_element" => false,
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Title", "meganews"),
      "param_name" => "title",
      "description" => __("Tab title.", "meganews")
    ),
    array(
      "type" => "tab_id",
      "heading" => __("Tab ID", "meganews"),
      "param_name" => "tab_id"
    )
  ),
  'js_view' => ($vc_is_wp_version_3_6_more ? 'VcTabView' : 'VcTabView35')
) );

/* Accordion block
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Accordion", "meganews"),
  "base" => "vc_accordion",
  "show_settings_on_create" => false,
  "is_container" => true,
  "icon" => "icon-wpb-ui-accordion",
  "category" => __('Content', 'meganews'),
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Widget title", "meganews"),
      "param_name" => "title",
      "description" => __("What text use as a widget title. Leave blank if no title is needed.", "meganews")
    ),
    array(
      "type" => "textfield",
      "heading" => __("Active tab", "meganews"),
      "param_name" => "active_tab",
      "description" => __("Enter tab number to be active on load or enter false to collapse all tabs.", "meganews")
    ),
    array(
      "type" => 'checkbox',
      "heading" => __("Allow collapsible all", "meganews"),
      "param_name" => "collapsible",
      "description" => __("Select checkbox to allow for all sections to be be collapsible.", "meganews"),
      "value" => Array(__("Allow", "meganews") => 'yes')
    ),
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  ),
  "custom_markup" => '
  <div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
  %content%
  </div>
  <div class="tab_controls">
  <button class="add_tab" title="'.__("Add accordion section", "meganews").'">'.__("Add accordion section", "meganews").'</button>
  </div>
  ',
  'default_content' => '
  [vc_accordion_tab title="'.__('Section 1', "meganews").'"][/vc_accordion_tab]
  [vc_accordion_tab title="'.__('Section 2', "meganews").'"][/vc_accordion_tab]
  ',
  'js_view' => 'VcAccordionView'
) );
vc_map( array(
  "name" => __("Accordion Section", "meganews"),
  "base" => "vc_accordion_tab",
  "allowed_container_element" => 'vc_row',
  "is_container" => true,
  "content_element" => false,
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Title", "meganews"),
      "param_name" => "title",
      "description" => __("Accordion section title.", "meganews")
    ),
  ),
  'js_view' => 'VcAccordionTabView'
) );


/* Widgetised sidebar
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Widgetised Sidebar", "meganews"),
  "base" => "vc_widget_sidebar",
  "class" => "wpb_widget_sidebar_widget",
  "icon" => "icon-wpb-layout_sidebar",
  "category" => __('Structure', 'meganews'),
  "params" => array(
    array(
      "type" => "widgetised_sidebars",
      "heading" => __("Sidebar", "meganews"),
      "param_name" => "sidebar_id",
      "description" => __("Select which widget area output.", "meganews")
    ),
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    ),
	array(
      "type" => "dropdown",
      "heading" => __("Sidebar border?", "meganews"),
      "param_name" => "sidebar_border",
      "value" => array(__("None", "meganews") => "none", __("Left", "meganews") => "left", __("Right", "meganews") => "right"),
      "description" => __("Select border for the sidebar.", "meganews")
    )
  )
) );

/* Button
---------------------------------------------------------- */
$icons_arr = array(
    __("None", "meganews") => "none",
    __("Address book icon", "meganews") => "wpb_address_book",
    __("Alarm clock icon", "meganews") => "wpb_alarm_clock",
    __("Anchor icon", "meganews") => "wpb_anchor",
    __("Application Image icon", "meganews") => "wpb_application_image",
    __("Arrow icon", "meganews") => "wpb_arrow",
    __("Asterisk icon", "meganews") => "wpb_asterisk",
    __("Hammer icon", "meganews") => "wpb_hammer",
    __("Balloon icon", "meganews") => "wpb_balloon",
    __("Balloon Buzz icon", "meganews") => "wpb_balloon_buzz",
    __("Balloon Facebook icon", "meganews") => "wpb_balloon_facebook",
    __("Balloon Twitter icon", "meganews") => "wpb_balloon_twitter",
    __("Battery icon", "meganews") => "wpb_battery",
    __("Binocular icon", "meganews") => "wpb_binocular",
    __("Document Excel icon", "meganews") => "wpb_document_excel",
    __("Document Image icon", "meganews") => "wpb_document_image",
    __("Document Music icon", "meganews") => "wpb_document_music",
    __("Document Office icon", "meganews") => "wpb_document_office",
    __("Document PDF icon", "meganews") => "wpb_document_pdf",
    __("Document Powerpoint icon", "meganews") => "wpb_document_powerpoint",
    __("Document Word icon", "meganews") => "wpb_document_word",
    __("Bookmark icon", "meganews") => "wpb_bookmark",
    __("Camcorder icon", "meganews") => "wpb_camcorder",
    __("Camera icon", "meganews") => "wpb_camera",
    __("Chart icon", "meganews") => "wpb_chart",
    __("Chart pie icon", "meganews") => "wpb_chart_pie",
    __("Clock icon", "meganews") => "wpb_clock",
    __("Fire icon", "meganews") => "wpb_fire",
    __("Heart icon", "meganews") => "wpb_heart",
    __("Mail icon", "meganews") => "wpb_mail",
    __("Play icon", "meganews") => "wpb_play",
    __("Shield icon", "meganews") => "wpb_shield",
    __("Video icon", "meganews") => "wpb_video"
);

vc_map( array(
  "name" => __("Button", "meganews"),
  "base" => "vc_button",
  "icon" => "icon-wpb-ui-button",
  "category" => __('Content', 'meganews'),
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Text on the button", "meganews"),
      "holder" => "button",
      "class" => "wpb_button",
      "param_name" => "title",
      "value" => __("Text on the button", "meganews"),
      "description" => __("Text on the button.", "meganews")
    ),
    array(
      "type" => "textfield",
      "heading" => __("URL (Link)", "meganews"),
      "param_name" => "href",
      "description" => __("Button link.", "meganews")
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Target", "meganews"),
      "param_name" => "target",
      "value" => $target_arr,
      "dependency" => Array('element' => "href", 'not_empty' => true)
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Color", "meganews"),
      "param_name" => "color",
      "value" => $colors_arr,
      "description" => __("Button color.", "meganews")
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Size", "meganews"),
      "param_name" => "size",
      "value" => $size_arr,
      "description" => __("Button size.", "meganews")
    ),
    $add_css_animation,
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  ),
  "js_view" => 'VcButtonView'
) );

/* Call to Action Button
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Call to Action Button", "meganews"),
  "base" => "vc_cta_button",
  "icon" => "icon-wpb-call-to-action",
  "category" => __('Content', 'meganews'),
  "params" => array(
    array(
      "type" => "textarea",
      'admin_label' => true,
      "heading" => __("Text", "meganews"),
      "param_name" => "call_text",
      "value" => __("Click edit button to change this text.", "meganews"),
      "description" => __("Enter your content.", "meganews")
    ),
    array(
      "type" => "textfield",
      "heading" => __("Text on the button", "meganews"),
      "param_name" => "title",
      "value" => __("Text on the button", "meganews"),
      "description" => __("Text on the button.", "meganews")
    ),
    array(
      "type" => "textfield",
      "heading" => __("URL (Link)", "meganews"),
      "param_name" => "href",
      "description" => __("Button link.", "meganews")
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Target", "meganews"),
      "param_name" => "target",
      "value" => $target_arr,
      "dependency" => Array('element' => "href", 'not_empty' => true)
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Color", "meganews"),
      "param_name" => "color",
      "value" => $colors_arr,
      "description" => __("Button color.", "meganews")
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Size", "meganews"),
      "param_name" => "size",
      "value" => $size_arr,
      "description" => __("Button size.", "meganews")
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Button position", "meganews"),
      "param_name" => "position",
      "value" => array(__("Align right", "meganews") => "cta_align_right", __("Align left", "meganews") => "cta_align_left", __("Align bottom", "meganews") => "cta_align_bottom"),
      "description" => __("Select button alignment.", "meganews")
    ),
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  ),
  "js_view" => 'VcCallToActionView'
) );

/* Video element
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Video Player", "meganews"),
  "base" => "vc_video",
  "icon" => "icon-wpb-film-youtube",
  "category" => __('Content', 'meganews'),
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Video link", "meganews"),
      "param_name" => "link",
      "admin_label" => true,
      "description" => sprintf(__('Link to the video. More about supported formats at %s.', "meganews"), '<a href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank">WordPress codex page</a>')
    ),
    $add_css_animation,
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
) );

/* Google maps element
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Google Maps", "meganews"),
  "base" => "vc_gmaps",
  "icon" => "icon-wpb-map-pin",
  "category" => __('Content', 'meganews'),
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Google map link", "meganews"),
      "param_name" => "link",
      "admin_label" => true,
      "description" => sprintf(__('Link to your map. Visit %s find your address and then click "Link" button to obtain your map link.', "meganews"), '<a href="http://maps.google.com" target="_blank">Google maps</a>')
    ),
    array(
      "type" => "textfield",
      "heading" => __("Map height", "meganews"),
      "param_name" => "size",
      "description" => __('Enter map height in pixels. Example: 200.', "meganews")
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Map type", "meganews"),
      "param_name" => "type",
      "value" => array(__("Map", "meganews") => "m", __("Satellite", "meganews") => "k", __("Map + Terrain", "meganews") => "p"),
      "description" => __("Select map type.", "meganews")
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Map Zoom", "meganews"),
      "param_name" => "zoom",
      "value" => array(__("14 - Default", "meganews") => 14, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 15, 16, 17, 18, 19, 20)
    ),
    array(
      "type" => 'checkbox',
      "heading" => __("Remove info bubble", "meganews"),
      "param_name" => "bubble",
      "description" => __("If selected, information bubble will be hidden.", "meganews"),
      "value" => Array(__("Yes, please", "meganews") => true),
    ),
    $add_css_animation,
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
) );

/* Raw HTML
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Raw HTML", "meganews"),
	"base" => "vc_raw_html",
	"icon" => "icon-wpb-raw-html",
	"category" => __('Structure', 'meganews'),
	"wrapper_class" => "clearfix",
	"params" => array(
		array(
  		"type" => "textarea_raw_html",
			"holder" => "div",
			"heading" => __("Raw HTML", "meganews"),
			"param_name" => "content",
			"value" => base64_encode("<p>I am raw html block.<br/>Click edit button to change this html</p>"),
			"description" => __("Enter your HTML content.", "meganews")
		),
	)
) );

/* Raw JS
---------------------------------------------------------- */
vc_map( array(
	"name" => __("Raw JS", "meganews"),
	"base" => "vc_raw_js",
	"icon" => "icon-wpb-raw-javascript",
	"category" => __('Structure', 'meganews'),
	"wrapper_class" => "clearfix",
	"params" => array(
  	array(
  		"type" => "textarea_raw_html",
			"holder" => "div",
			"heading" => __("Raw js", "meganews"),
			"param_name" => "content",
			"value" => __(base64_encode("<script type='text/javascript'> alert('Enter your js here!'); </script>"), "meganews"),
			"description" => __("Enter your JS code.", "meganews")
		),
	)
) );

/* Flickr
---------------------------------------------------------- */
vc_map( array(
  "base" => "vc_flickr",
  "name" => __("Flickr Widget", "meganews"),
  "icon" => "icon-wpb-flickr",
  "category" => __('Content', 'meganews'),
  "params"	=> array(
    array(
      "type" => "textfield",
      "heading" => __("Flickr ID", "meganews"),
      "param_name" => "flickr_id",
      'admin_label' => true,
      "description" => sprintf(__('To find your flickID visit %s.', "meganews"), '<a href="http://idgettr.com/" target="_blank">idGettr</a>')
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Number of photos", "meganews"),
      "param_name" => "count",
      "value" => array(9, 8, 7, 6, 5, 4, 3, 2, 1),
      "description" => __("Number of photos.", "meganews")
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Type", "meganews"),
      "param_name" => "type",
      "value" => array(__("User", "meganews") => "user", __("Group", "meganews") => "group"),
      "description" => __("Photo stream type.", "meganews")
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Display", "meganews"),
      "param_name" => "display",
      "value" => array(__("Latest", "meganews") => "latest", __("Random", "meganews") => "random"),
      "description" => __("Photo order.", "meganews")
    ),
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
) );





/* Dropcaps
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Dropcap", "meganews"),
  "base" => "vc_dropcap",
  "icon" => "icon-wpb-film-youtube",
  "category" => __('Content', 'meganews'),
  "params" => array(
	array(
      "type" => "dropdown",
      "heading" => __("Dropcap type", "meganews"),
      "param_name" => "type",
      "value" => array(__('Type#1', "meganews") => "type1", __('Type#2', "meganews") => "type2"),
      "description" => __("Select dropcap type.", "meganews")
    ),
    array(
      "type" => "textfield",
      "heading" => __("First letter", "meganews"),
      "param_name" => "first_letter",
      "value" => __("A", "meganews"),
      "description" => __("First letter.", "meganews")
	  ),
    array(
      "type" => "textfield",
      "heading" => __("First letter size", "meganews"),
      "param_name" => "first_letter_size",
      "description" => __("First letter font size. Insert number only.", "meganews")
	  ),
	array(
      "type" => "colorpicker",
      "heading" => __("First letter color", "meganews"),
      "param_name" => "first_letter_color",
      "description" => __("Choose color for first letter.", "meganews"),
      "dependency" => Array('element' => "bgcolor", 'value' => array('custom'))
    ),
	array(
      "type" => "colorpicker",
      "heading" => __("First letter background", "meganews"),
      "param_name" => "first_letter_bg",
      "description" => __("Choose background color for first letter.", "meganews"),
      "dependency" => Array('element' => "type", 'value' => array('type2'))
    ),
  array(
      "type" => "textarea_html",
      "heading" => __("Text", "meganews"),
      "param_name" => "content",
      "value" => __("<p>Sample content</p>", "meganews")
    ),
    $add_css_animation,
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )	
  )
 // ,  "js_view" => 'VcColLeftIconView'
) );

/* Blockquote
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Blockquote", "meganews"),
  "base" => "vc_blockquote",
  "icon" => "icon-wpb-film-youtube",
  "category" => __('Content', 'meganews'),
  "params" => array(
  	array(
      "type" => "dropdown",
      "heading" => __("Blockquote type", "meganews"),
      "param_name" => "type",
      "value" => array(__('Type#1', "meganews") => "type1", __('Type#2', "meganews") => "type2", __('Type#3', "meganews") => "type3"),
      "description" => __("Select blockquote type.", "meganews")
    ),
	array(
      "type" => "colorpicker",
      "heading" => __("Border color", "meganews"),
      "param_name" => "border_color",
      "description" => __("Border color for blockquote.", "meganews"),
      "dependency" => Array('element' => "type", 'value' => array('type1'))
    ),
	  array(
      "type" => "textfield",
      "heading" => __("Border size", "meganews"),
      "param_name" => "border_size",
      "description" => __("Border size. Insert number only.", "meganews"),
	   "dependency" => Array('element' => "type", 'value' => array('type1'))
	  ),
    array(
      "type" => "attach_image",
      "heading" => __("Icon", "meganews"),
      "param_name" => "icon_image",
      "value" => "",
      "description" => __("Select icon image from media library.", "meganews"),
	   "dependency" => Array('element' => "type", 'value' => array('type2', 'type3'))
    ),
	array(
      "type" => "colorpicker",
      "heading" => __("Background color", "meganews"),
      "param_name" => "background_color",
      "description" => __("Background color for blockquote.", "meganews"),
      "dependency" => Array('element' => "type", 'value' => array('type2'))
    ),
	array(
      "type" => "colorpicker",
      "heading" => __("Icon background color", "meganews"),
      "param_name" => "icon_background_color",
      "description" => __("Background color for icon.", "meganews"),
      "dependency" => Array('element' => "type", 'value' => array('type3'))
    ),
	array(
      "type" => "textarea_html",
      "heading" => __("Text", "meganews"),
      "param_name" => "content",
      "value" => __("<p>Sample content</p>", "meganews")
    ),
    $add_css_animation,
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )	
  )
 // ,  "js_view" => 'VcColLeftIconView'
) );


 
 /* Counter
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Counter", "meganews"),
  "base" => "vc_counter",
  "icon" => "icon-wpb-images-stack",
  "category" => __('Content', 'meganews'),
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Title", "meganews"),
      "param_name" => "title",
      "holder" => "div",
      "value" => __("Title", "meganews"),
      "description" => __("Counter title.", "meganews")
	  ),
	array(
      "type" => "colorpicker",
      "heading" => __("Title color", "meganews"),
      "param_name" => "title_color",
      "description" => __("Choose title color.", "meganews")
    ),
    array(
      "type" => "textfield",
      "heading" => __("Counter value", "meganews"),
      "param_name" => "counter_value",
      "description" => __("Enter counter value.", "meganews")
    ),
	array(
      "type" => "colorpicker",
      "heading" => __("Value color", "meganews"),
      "param_name" => "value_color",
      "description" => __("Choose color for value.", "meganews")
    ),
    array(
      "type" => "my_param1",
      "heading" => __("Icon", "meganews"),
      "param_name" => "icon",
      "value" => $allIcons,
      "description" => __("Select icon.", "meganews")
    ),
    array(
      "type" => "colorpicker",
      "heading" => __("Icon color", "meganews"),
      "param_name" => "icon_color",
      "description" => __("Choose color icon.", "meganews")
    ),
    array(
      "type" => "colorpicker",
      "heading" => __("Background color", "meganews"),
      "param_name" => "bg_color",
      "description" => __("Choose background color.", "meganews")
    ),
    $add_css_animation,
     array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
) );


/* List
---------------------------------------------------------- */
vc_map( array(
  "name" => __("List", "meganews"),
  "base" => "vc_list",
  "icon" => "icon-wpb-film-youtube",
  "category" => __('Content', 'meganews'),
  "params" => array(  
    array(
      "type" => "my_param1",
      "heading" => __("List marker", "meganews"),
      "param_name" => "icon",
      "value" => $allIcons,
      "description" => __("Select list marker.", "meganews")
    ),
	array(
      "type" => "colorpicker",
      "heading" => __("Icon color", "meganews"),
      "param_name" => "icon_color",
      "description" => __("Choose color for icon.", "meganews")
    ),	
  array(
      "type" => "textarea_html",    
      "heading" => __("List", "meganews"),
      "param_name" => "content",
      "value" => __("<ul><li>List item..</li></ul>", "meganews")
    ),
    $add_css_animation,
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
) );


 /* Circle chart
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Circle chart", "meganews"),
  "base" => "vc_circle_chart",
  "icon" => "icon-wpb-film-youtube",
  "category" => __('Content', 'meganews'),
  "params" => array(  
    array(
      "type" => "colorpicker",
      "heading" => __("Bar color", "meganews"),
      "param_name" => "color",
	  "value" => "#e00008",
      "description" => __("Choose color for chart bar.", "meganews")
    ),
    array(
      "type" => "colorpicker",
      "heading" => __("Track color", "meganews"),
      "param_name" => "track_color",
	   "value" => "#f1f1f1",
      "description" => __("Choose color for chart track.", "meganews")
    ),
  array(
      "type" => "textfield",
      "heading" => __("Chart width", "meganews"),
      "param_name" => "chart_width",
	  "value" => "200",
      "description" => __("Enter value for data line width. Enter number only.", "meganews")
    ),
  array(
      "type" => "textfield",
      "heading" => __("Data line width", "meganews"),
      "param_name" => "line_width",
	  "value" => "7",
      "description" => __("Enter value for data line width. Enter number only.", "meganews")
    ),
  array(
      "type" => "textfield",
      "heading" => __("Value", "meganews"),
      "param_name" => "value",
      "description" => __("Enter value for bar chart. Enter number only [1-100].", "meganews")
    ),
	array(
      "type" => "dropdown",
      "heading" => __("Type", "meganews"),
      "param_name" => "type",
      "value" => array(__('Percent', "meganews") => "percent", __('Icon', "meganews") => "icon", __('Description', "meganews") => "description"),
      "description" => __("Select type that will appear inside the chart.", "meganews")
    ),
    array(
      "type" => "textfield",
      "heading" => __("Description", "meganews"),
      "param_name" => "description",
      "description" => __("Enter description text for inside the chart.", "meganews"),
	 "dependency" => Array('element' => "type", 'value' => array('description'))
    ),
	array(
      "type" => "textfield",
      "heading" => __("Description font size", "meganews"),
      "param_name" => "description_size",
      "description" => __("Enter size for chart description. Enter number only.", "meganews"),
	 "dependency" => Array('element' => "type", 'value' => array('description'))
    ),
	array(
      "type" => "colorpicker",
      "heading" => __("Description color", "meganews"),
      "param_name" => "description_color",
      "description" => __("Choose color for chart description.", "meganews"),
	  "dependency" => Array('element' => "type", 'value' => array('description'))
    ),
	array(
      "type" => "my_param1",
      "heading" => __("Icon", "meganews"),
      "param_name" => "icon",
	  "value" => $allIcons,
      "description" => __("Select icon text for inside the chart.", "meganews"),
	 "dependency" => Array('element' => "type", 'value' => array('icon'))
    ),
    array(
      "type" => "textfield",
      "heading" => __("Icon size", "meganews"),
      "param_name" => "icon_size",
      "description" => __("Enter size for chart icon.  Enter number only.", "meganews"),
	 "dependency" => Array('element' => "type", 'value' => array('icon'))
    ),
	array(
      "type" => "colorpicker",
      "heading" => __("Icon color", "meganews"),
      "param_name" => "icon_color",
      "description" => __("Choose color for chart icon.", "meganews"),
	   "dependency" => Array('element' => "type", 'value' => array('icon'))
    ),
   array(
      "type" => "textfield",
      "heading" => __("Percent font size", "meganews"),
      "param_name" => "percent_size",
      "description" => __("Enter size for chart percent. Enter number only.", "meganews"),
	 "dependency" => Array('element' => "type", 'value' => array('percent'))
    ),
	array(
      "type" => "colorpicker",
      "heading" => __("Percent color", "meganews"),
      "param_name" => "percent_color",
      "description" => __("Choose color for chart percent.", "meganews"),
	  "dependency" => Array('element' => "type", 'value' => array('percent'))
    ),
   array(
      "type" => "textarea_html",    
      "heading" => __("Content under chart", "meganews"),
      "param_name" => "content",
      "value" => __("<p></p>", "meganews")
    ),
    $add_css_animation,
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
) );
 
 

 
 
 /* Carousel
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Carousel", "meganews"),
  "base" => "vc_carousel",
  "icon" => "icon-wpb-images-stack",
  "category" => __('Content', 'meganews'),
  "params" => array(
    array(
      "type" => "attach_images",
      "heading" => __("Images", "meganews"),
      "param_name" => "images",
      "value" => "",
      "description" => __("Select images from media library.", "meganews")
    ),
    array(
      "type" => "textfield",
      "heading" => __("Item width", "meganews"),
      "param_name" => "img_size",
      "description" => __("Enter image width. Enter number only. If empty 220 will be set. ", "meganews")
    ),
    array(
      "type" => "exploded_textarea",
      "heading" => __("Custom links", "meganews"),
      "param_name" => "custom_links",
      "description" => __('Enter links for each slide here. Divide links with linebreaks (Enter).', 'meganews')
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Custom link target", "meganews"),
      "param_name" => "custom_links_target",
      "description" => __('Select where to open  custom links.', 'meganews'),
      'value' => $target_arr
    )
  )
) );




 vc_map( array(
  "name" => __("Testimonials", "meganews"),
  "base" => "vc_testimonials",
  "icon" => "icon-wpb-film-youtube",
  "category" => __('Content', 'meganews'),
  "params" => array(
    array(
      "type" => "exploded_textarea",
      "heading" => __("Testimonials", "meganews"),
      "param_name" => "values",
      "description" => __('Input testimonials here. Divide values with linebreaks (Enter). You may choose between: <strong>'.$list_testimonials.'</strong>.  ', 'meganews'),
      "value" => ""
    ),
    $add_css_animation,
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
 // ,  "js_view" => 'VcColLeftIconView'
) );
 
 
 vc_map( array(
  "name" => __("Icon", "meganews"),
  "base" => "vc_icon",
  "icon" => "icon-wpb-film-youtube",
  "category" => __('Content', 'meganews'),
  "params" => array(
   	array(
      "type" => "my_param1",
      "heading" => __("Icon", "meganews"),
      "param_name" => "icon",
      "value" => $allIcons,
      "description" => __("Select icon.", "meganews")
	  ),
	array(
      "type" => "textfield",
      "heading" => __("Icon size", "meganews"),
      "param_name" => "icon_size",
      "description" => __("Enter icon size. Enter number only.", "meganews")
    ),
	array(
      "type" => "colorpicker",
      "heading" => __("Icon color", "meganews"),
      "param_name" => "icon_color",
      "description" => __("Select color for icon.", "meganews")
    ),	
	array(
      "type" => "colorpicker",
      "heading" => __("Icon hover color", "meganews"),
      "param_name" => "icon_hovercolor",
      "description" => __("Select color for icon hover.", "meganews")
    ),
    array(
      "type" => "textfield",
      "heading" => __("Icon link", "meganews"),
      "param_name" => "icon_link",
      "description" => __("Enter url where icon click will link.", "meganews")
    ),
    $add_css_animation,
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
 // ,  "js_view" => 'VcColLeftIconView'
) );
 
 
 
 

/* Graph
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Progress Bar", "meganews"),
  "base" => "vc_progress_bar",
  "icon" => "icon-wpb-graph",
  "category" => __('Content', 'meganews'),
  "params" => array(
    array(
      "type" => "exploded_textarea",
      "heading" => __("Graphic values", "meganews"),
      "param_name" => "values",
      "description" => __('Input graph values here. Divide values with linebreaks (Enter). Example: 90|Development', 'meganews'),
      "value" => "90|Development,80|Design,70|Marketing"
    ),
    array(
      "type" => "textfield",
      "heading" => __("Units", "meganews"),
      "param_name" => "units",
      "description" => __("Enter measurement units (if needed) Eg. %, px, points, etc. Graph value and unit will be appended to the graph title.", "meganews")
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Bar color", "meganews"),
      "param_name" => "bgcolor",
      "value" => array(__("Grey", "meganews") => "bar_grey", __("Blue", "meganews") => "bar_blue", __("Turquoise", "meganews") => "bar_turquoise", __("Green", "meganews") => "bar_green", __("Orange", "meganews") => "bar_orange", __("Red", "meganews") => "bar_red", __("Black", "meganews") => "bar_black", __("Custom Color", "meganews") => "custom"),
      "description" => __("Select bar background color.", "meganews"),
      "admin_label" => true
    ),
    array(
      "type" => "colorpicker",
      "heading" => __("Bar custom color", "meganews"),
      "param_name" => "custombgcolor",
      "description" => __("Select custom background color for bars.", "meganews"),
      "dependency" => Array('element' => "bgcolor", 'value' => array('custom'))
    ),
    array(
      "type" => "checkbox",
      "heading" => __("Options", "meganews"),
      "param_name" => "options",
      "value" => array(__("Add Stripes?", "meganews") => "striped", __("Add animation? Will be visible with striped bars.", "meganews") => "animated")
    ),
    array(
      "type" => "checkbox",
      "heading" => __("Hide track?", "meganews"),
      "param_name" => "hide_track",
      "value" => array(__("Hide track for progress bar?", "meganews") => "hide")
    ),
	array(
      "type" => "textfield",
      "heading" => __("Height", "meganews"),
      "param_name" => "height",
      "description" => __("Enter height for progress bar. Enter number only.", "meganews")
    ),
	array(
      "type" => "textfield",
      "heading" => __("Font size", "meganews"),
      "param_name" => "font_size",
      "description" => __("Enter font size for progress bar caption. Enter number only.", "meganews")
    ),
	    array(
      "type" => "textfield",
      "heading" => __("Units", "meganews"),
      "param_name" => "units",
      "description" => __("Enter measurement units (if needed) Eg. %, px, points, etc. Graph value and unit will be appended to the graph title.", "meganews")
    ),
    $add_css_animation,
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
) );




if (is_plugin_active('LayerSlider/layerslider.php')) {
  global $wpdb;
  $ls = $wpdb->get_results( 
  	"
  	SELECT id, name, date_c
  	FROM ".$wpdb->prefix."layerslider
  	WHERE flag_hidden = '0' AND flag_deleted = '0'
  	ORDER BY date_c ASC LIMIT 100
  	"
  );
  $layer_sliders = array();
  if ($ls) {
    foreach ( $ls as $slider ) {
      $layer_sliders[$slider->name] = $slider->id;
    }
  } else {
    $layer_sliders["No sliders found"] = 0;
  }
  vc_map( array(
    "base" => "layerslider_vc",
    "name" => __("Layer Slider", "meganews"),
    "icon" => "icon-wpb-layerslider",
    "category" => __('Content', 'meganews'),
    "params" => array(
      array(
        "type" => "dropdown",
        "heading" => __("LayerSlider ID", "meganews"),
        "param_name" => "id",
        "admin_label" => true,
        "value" => $layer_sliders,
        "description" => __("Select your LayerSlider.", "meganews")
      ),
      array(
        "type" => "textfield",
        "heading" => __("Extra class name", "meganews"),
        "param_name" => "el_class",
        "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
      )
    )
  ) );
} // if layer slider plugin active

if (is_plugin_active('revslider/revslider.php')) {
  global $wpdb;
  $rs = $wpdb->get_results( 
  	"
  	SELECT id, title, alias
  	FROM ".$wpdb->prefix."revslider_sliders
  	ORDER BY id ASC LIMIT 100
  	"
  );
  $revsliders = array();
  if ($rs) {
    foreach ( $rs as $slider ) {
      $revsliders[$slider->title] = $slider->alias;
    }
  } else {
    $revsliders["No sliders found"] = 0;
  }
  vc_map( array(
    "base" => "rev_slider_vc",
    "name" => __("Revolution Slider", "meganews"),
    "icon" => "icon-wpb-revslider",
    "category" => __('Content', 'meganews'),
    "params"=> array(
      array(
        "type" => "textfield",
        "heading" => __("Widget title", "meganews"),
        "param_name" => "title",
        "description" => __("What text use as a widget title. Leave blank if no title is needed.", "meganews")
      ),
      array(
        "type" => "dropdown",
        "heading" => __("Revolution Slider", "meganews"),
        "param_name" => "alias",
        "admin_label" => true,
        "value" => $revsliders,
        "description" => __("Select your Revolution Slider.", "meganews")
      ),
      array(
        "type" => "textfield",
        "heading" => __("Extra class name", "meganews"),
        "param_name" => "el_class",
        "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
      )
    )
  ) );
} // if revslider plugin active

if (is_plugin_active('gravityforms/gravityforms.php')) {
  $gravity_forms_array[__("No Gravity forms found.", "meganews")] = '';
  if ( class_exists('RGFormsModel') ) {
    $gravity_forms = RGFormsModel::get_forms(1, "title");
    if ($gravity_forms) {
      $gravity_forms_array = array(__("Select a form to display.", "meganews") => '');
      foreach ( $gravity_forms as $gravity_form ) {
        $gravity_forms_array[$gravity_form->title] = $gravity_form->id;
      }
    }
  }
  vc_map( array(
    "name" => __("Gravity Form", "meganews"),
    "base" => "gravityform",
    "icon" => "icon-wpb-vc_gravityform",
    "category" => __("Content", "meganews"),
    "params" => array(
      array(
        "type" => "dropdown",
        "heading" => __("Form", "meganews"),
        "param_name" => "id",
        "value" => $gravity_forms_array,
        "description" => __("Select a form to add it to your post or page.", "meganews"),
        "admin_label" => true
      ),
      array(
        "type" => "dropdown",
        "heading" => __("Display Form Title", "meganews"),
        "param_name" => "title",
        "value" => array( __("No", "meganews") => 'false', __("Yes", "meganews") => 'true' ),
        "description" => __("Would you like to display the forms title?", "meganews"),
        "dependency" => Array('element' => "id", 'not_empty' => true)
      ),
      array(
        "type" => "dropdown",
        "heading" => __("Display Form Description", "meganews"),
        "param_name" => "description",
        "value" => array( __("No", "meganews") => 'false', __("Yes", "meganews") => 'true' ),
        "description" => __("Would you like to display the forms description?", "meganews"),
        "dependency" => Array('element' => "id", 'not_empty' => true)
      ),
      array(
        "type" => "dropdown",
        "heading" => __("Enable AJAX?", "meganews"),
        "param_name" => "ajax",
        "value" => array( __("No", "meganews") => 'false', __("Yes", "meganews") => 'true' ),
        "description" => __("Enable AJAX submission?", "meganews"),
        "dependency" => Array('element' => "id", 'not_empty' => true)
      ),
      array(
        "type" => "textfield",
        "heading" => __("Tab Index", "meganews"),
        "param_name" => "tabindex",
        "description" => __("(Optional) Specify the starting tab index for the fields of this form. Leave blank if you're not sure what this is.", "meganews"),
        "dependency" => Array('element' => "id", 'not_empty' => true)
      )
    )
  ) );
} // if gravityforms active



/* Facebook like button
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Facebook Like", "meganews"),
  "base" => "vc_facebook",
  "icon" => "icon-wpb-balloon-facebook-left",
  "category" => __('Social', 'meganews'),
  "params" => array(
    array(
      "type" => "dropdown",
      "heading" => __("Button type", "meganews"),
      "param_name" => "type",
      "admin_label" => true,
      "value" => array(__("Standard", "meganews") => "standard", __("Button count", "meganews") => "button_count", __("Box count", "meganews") => "box_count"),
      "description" => __("Select button type.", "meganews")
    )
  )
) );

/* Tweetmeme button
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Tweetmeme Button", "meganews"),
  "base" => "vc_tweetmeme",
  "icon" => "icon-wpb-tweetme",
  "category" => __('Social', 'meganews'),
  "params" => array(
    array(
      "type" => "dropdown",
      "heading" => __("Button type", "meganews"),
      "param_name" => "type",
      "admin_label" => true,
      "value" => array(__("Horizontal", "meganews") => "horizontal", __("Vertical", "meganews") => "vertical", __("None", "meganews") => "none"),
      "description" => __("Select button type.", "meganews")
    )
  )
) );

/* Google+ button
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Google+ Button", "meganews"),
  "base" => "vc_googleplus",
  "icon" => "icon-wpb-application-plus",
  "category" => __('Social', 'meganews'),
  "params" => array(
    array(
      "type" => "dropdown",
      "heading" => __("Button size", "meganews"),
      "param_name" => "type",
      "admin_label" => true,
      "value" => array(__("Standard", "meganews") => "", __("Small", "meganews") => "small", __("Medium", "meganews") => "medium", __("Tall", "meganews") => "tall"),
      "description" => __("Select button size.", "meganews")
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Annotation", "meganews"),
      "param_name" => "annotation",
      "admin_label" => true,
      "value" => array(__("Inline", "meganews") => "inline", __("Bubble", "meganews") => "", __("None", "meganews") => "none"),
      "description" => __("Select annotation type.", "meganews")
    )
  )
) );

/* Google+ button
---------------------------------------------------------- */
vc_map( array(
  "name" => __("Pinterest Button", "meganews"),
  "base" => "vc_pinterest",
  "icon" => "icon-wpb-pinterest",
  "category" => __('Social', 'meganews'),
  "params"	=> array(
    array(
      "type" => "dropdown",
      "heading" => __("Button layout", "meganews"),
      "param_name" => "type",
      "admin_label" => true,
      "value" => array(__("Horizontal", "meganews") => "", __("Vertical", "meganews") => "vertical", __("No count", "meganews") => "none"),
      "description" => __("Select button layout.", "meganews")
    )
  )
) );

/* WordPress default Widgets (Appearance->Widgets)
---------------------------------------------------------- */
vc_map( array(
  "name" => 'WP ' . __("Search", "meganews"),
  "base" => "vc_wp_search",
  "icon" => "icon-wpb-wp",
  "category" => __("WordPress Widgets", "meganews"),
  "class" => "wpb_vc_wp_widget",
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
) );

vc_map( array(
  "name" => 'WP ' . __("Meta", "meganews"),
  "base" => "vc_wp_meta",
  "icon" => "icon-wpb-wp",
  "category" => __("WordPress Widgets", "meganews"),
  "class" => "wpb_vc_wp_widget",
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
) );

vc_map( array(
  "name" => 'WP ' . __("Recent Comments", "meganews"),
  "base" => "vc_wp_recentcomments",
  "icon" => "icon-wpb-wp",
  "category" => __("WordPress Widgets", "meganews"),
  "class" => "wpb_vc_wp_widget",
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Number of comments to show", "meganews"),
      "param_name" => "number",
      "admin_label" => true
    ),
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
) );

vc_map( array(
  "name" => 'WP ' . __("Calendar", "meganews"),
  "base" => "vc_wp_calendar",
  "icon" => "icon-wpb-wp",
  "category" => __("WordPress Widgets", "meganews"),
  "class" => "wpb_vc_wp_widget",
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
) );

vc_map( array(
  "name" => 'WP ' . __("Pages", "meganews"),
  "base" => "vc_wp_pages",
  "icon" => "icon-wpb-wp",
  "category" => __("WordPress Widgets", "meganews"),
  "class" => "wpb_vc_wp_widget",
  "params" => array(
    array(
      "type" => "dropdown",
      "heading" => __("Sort by", "meganews"),
      "param_name" => "sortby",
      "value" => array(__("Page title", "meganews") => "post_title", __("Page order", "meganews") => "menu_order", __("Page ID", "meganews") => "ID"),
      "admin_label" => true
    ),
    array(
      "type" => "textfield",
      "heading" => __("Exclude", "meganews"),
      "param_name" => "exclude",
      "description" => __("Page IDs, separated by commas.", "meganews"),
      "admin_label" => true
    ),
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
) );

$tag_taxonomies = array();
foreach ( get_taxonomies() as $taxonomy ) :
  $tax = get_taxonomy($taxonomy);
	if ( !$tax->show_tagcloud || empty($tax->labels->name) )
  	continue;
	$tag_taxonomies[$tax->labels->name] = esc_attr($taxonomy);
endforeach;
vc_map( array(
  "name" => 'WP ' . __("Tag Cloud", "meganews"),
  "base" => "vc_wp_tagcloud",
  "icon" => "icon-wpb-wp",
  "category" => __("WordPress Widgets", "meganews"),
  "class" => "wpb_vc_wp_widget",
  "params" => array(
    array(
      "type" => "dropdown",
      "heading" => __("Taxonomy", "meganews"),
      "param_name" => "taxonomy",
      "value" => $tag_taxonomies,
      "admin_label" => true
    ),
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
) );

$custom_menus = array();
$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
if ( $menus ) {
  foreach ( $menus as $single_menu ) {
    $custom_menus[$single_menu->name] = $single_menu->term_id;
  }
  vc_map( array(
    "name" => 'WP ' . __("Custom Menu", "meganews"),
    "base" => "vc_wp_custommenu",
    "icon" => "icon-wpb-wp",
    "category" => __("WordPress Widgets", "meganews"),
    "class" => "wpb_vc_wp_widget",
    "params" => array(
      array(
        "type" => "dropdown",
        "heading" => __("Menu", "meganews"),
        "param_name" => "nav_menu",
        "value" => $custom_menus,
        "description" => __("Select menu", "meganews"),
        "admin_label" => true
      ),
      array(
        "type" => "textfield",
        "heading" => __("Extra class name", "meganews"),
        "param_name" => "el_class",
        "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
      )
    )
  ) );
}

vc_map( array(
  "name" => 'WP ' . __("Text", "meganews"),
  "base" => "vc_wp_text",
  "icon" => "icon-wpb-wp",
  "category" => __("WordPress Widgets", "meganews"),
  "class" => "wpb_vc_wp_widget",
  "params" => array(
    array(
      "type" => "textarea",
      "heading" => __("Text", "meganews"),
      "param_name" => "text",
      "admin_label" => true
    ),
    /*array(
      "type" => "checkbox",
      "heading" => __("Automatically add paragraphs", "meganews"),
      "param_name" => "filter"
    ),*/
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
) );


vc_map( array(
  "name" => 'WP ' . __("Recent Posts", "meganews"),
  "base" => "vc_wp_posts",
  "icon" => "icon-wpb-wp",
  "category" => __("WordPress Widgets", "meganews"),
  "class" => "wpb_vc_wp_widget",
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("Number of posts to show", "meganews"),
      "param_name" => "number",
      "admin_label" => true
    ),
    array(
      "type" => "checkbox",
      "heading" => __("Display post date?", "meganews"),
      "param_name" => "show_date",
      "value" => array(__("Display post date?", "meganews") => true )
    ),
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
) );


$link_category = array(__("All Links", "meganews") => "");
$link_cats = get_terms( 'link_category' );
foreach ( $link_cats as $link_cat ) {
  $link_category[$link_cat->name] = $link_cat->term_id;
}
vc_map( array(
  "name" => 'WP ' . __("Links", "meganews"),
  "base" => "vc_wp_links",
  "icon" => "icon-wpb-wp",
  "category" => __("WordPress Widgets", "meganews"),
  "class" => "wpb_vc_wp_widget",
  "params" => array(
    array(
      "type" => "dropdown",
      "heading" => __("Link Category", "meganews"),
      "param_name" => "category",
      "value" => $link_category,
      "admin_label" => true
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Sort by", "meganews"),
      "param_name" => "orderby",
      "value" => array(__("Link title", "meganews") => "name", __("Link rating", "meganews") => "rating", __("Link ID", "meganews") => "id", __("Random", "meganews") => "rand")
    ),
    array(
      "type" => "checkbox",
      "heading" => __("Options", "meganews"),
      "param_name" => "options",
      "value" => array(__("Show Link Image", "meganews") => "images", __("Show Link Name", "meganews") => "name", __("Show Link Description", "meganews") => "description", __("Show Link Rating", "meganews") => "rating")
    ),
    array(
      "type" => "textfield",
      "heading" => __("Number of links to show", "meganews"),
      "param_name" => "limit"
    ),
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
) );


vc_map( array(
  "name" => 'WP ' . __("Categories", "meganews"),
  "base" => "vc_wp_categories",
  "icon" => "icon-wpb-wp",
  "category" => __("WordPress Widgets", "meganews"),
  "class" => "wpb_vc_wp_widget",
  "params" => array(
    array(
      "type" => "checkbox",
      "heading" => __("Options", "meganews"),
      "param_name" => "options",
      "value" => array(__("Display as dropdown", "meganews") => "dropdown", __("Show post counts", "meganews") => "count", __("Show hierarchy", "meganews") => "hierarchical")
    ),
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
) );


vc_map( array(
  "name" => 'WP ' . __("Archives", "meganews"),
  "base" => "vc_wp_archives",
  "icon" => "icon-wpb-wp",
  "category" => __("WordPress Widgets", "meganews"),
  "class" => "wpb_vc_wp_widget",
  "params" => array(  
    array(
      "type" => "checkbox",
      "heading" => __("Options", "meganews"),
      "param_name" => "options",
      "value" => array(__("Display as dropdown", "meganews") => "dropdown", __("Show post counts", "meganews") => "count")
    ),
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
) );

vc_map( array(
  "name" => 'WP ' . __("RSS", "meganews"),
  "base" => "vc_wp_rss",
  "icon" => "icon-wpb-wp",
  "category" => __("WordPress Widgets", "meganews"),
  "class" => "wpb_vc_wp_widget",
  "params" => array(
    array(
      "type" => "textfield",
      "heading" => __("RSS feed URL", "meganews"),
      "param_name" => "url",
      "description" => __("Enter the RSS feed URL.", "meganews"),
      "admin_label" => true
    ),
    array(
      "type" => "dropdown",
      "heading" => __("Items", "meganews"),
      "param_name" => "items",
      "value" => array(__("10 - Default", "meganews") => '', 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20),
      "description" => __("How many items would you like to display?", "meganews"),
      "admin_label" => true
    ),
    array(
      "type" => "checkbox",
      "heading" => __("Options", "meganews"),
      "param_name" => "options",
      "value" => array(__("Display item content?", "meganews") => "show_summary", __("Display item author if available?", "meganews") => "show_author", __("Display item date?", "meganews") => "show_date")
    ),
    array(
      "type" => "textfield",
      "heading" => __("Extra class name", "meganews"),
      "param_name" => "el_class",
      "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "meganews")
    )
  )
) );