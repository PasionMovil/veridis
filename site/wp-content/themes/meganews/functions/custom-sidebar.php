<?php
// creating Sidebars 
add_action('init', 'Sidebars_register');
 
function Sidebars_register() { 
	$labels = array(
		'name' => __('Sidebars', "meganews"),
		'singular_name' => __('Sidebars Item', "meganews"),
		'add_new' => __('Add New', "meganews"),
		'add_new_item' => __('Add New Sidebars Item', "meganews"),
		'edit_item' => __('Edit Sidebars Item', "meganews"),
		'new_item' => __('New Sidebars Item', "meganews"),
		'view_item' => __('View Sidebars Item', "meganews"),
		'search_items' => __('Search Sidebars', "meganews"),
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
 
	register_post_type( 'sidebars' , $args );
}
?>