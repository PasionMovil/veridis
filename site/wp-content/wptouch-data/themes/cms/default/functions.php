<?php

add_action( 'wp_enqueue_scripts', 'cms_enqueue_scripts' );

function cms_enqueue_scripts() {
	wp_enqueue_script( 'cms-js', CMS_URL . '/default/cms.js', array( 'jquery' ), CMS_THEME_VERSION, true );
}

function cms_get_background_image() {
	$cms_settings = cms_get_settings();

	if ( $cms_settings->background_image ) {
		echo WPTOUCH_BASE_CONTENT_URL . $cms_settings->background_image;
		return true;
	} else {
		return false;
	}
}

function has_cms_homepage_message() {
	$cms_settings = cms_get_settings();

	if ( $cms_settings->frontpage_message ) {
		return true;
	} else {
		return false;
	}
}

function cms_homepage_message() {
	$cms_settings = cms_get_settings();
	echo $cms_settings->frontpage_message;
}

function cms_css_noise() {
	$cms_settings = cms_get_settings();

	if ( $cms_settings->css_noise ) {
		echo 'noise';
		return true;
	} else {
		return false;
	}
}

function cms_secondary_menu_title() {
	$cms_settings = cms_get_settings();
	$title = $cms_settings->second_menu_title;

	if ( $title ) {
		echo $title;
	} else {
		return false;
	}
}

function cms_show_category_slider() {
	$cms_settings = cms_get_settings();

	if ( $cms_settings->category_slider ) {
		return true;
	} else {
		return false;
	}
}

function cms_show_page_titles() {
	$cms_settings = cms_get_settings();

	if ( $cms_settings->show_titles ) {
		return true;
	} else {
		return false;
	}
}

function cms_show_post_featured_images() {
	$cms_settings = cms_get_settings();

	if ( $cms_settings->show_featured_images_in_posts ) {
		return true;
	} else {
		return false;
	}
}

function cms_show_search() {
	$cms_settings = cms_get_settings();

	if ( $cms_settings->show_search ) {
		return true;
	} else {
		return false;
	}
}