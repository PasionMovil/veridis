<?php

add_action( 'foundation_module_init_mobile', 'foundation_advertising_init' );
add_action( 'wptouch_admin_page_render_wptouch-admin-theme-settings', 'foundation_ad_settings' );

add_filter( 'wptouch_body_classes', 'foundation_advertising_body_classes' );

function foundation_advertising_init() {
	$settings = foundation_get_settings();
	
	// Can't use WP is_single(), etc. functions here
	if ( 	$settings->advertising_blog_listings ||
			$settings->advertising_single ||
			$settings->advertising_pages ||
			$settings->advertising_taxonomy ||
			$settings->advertising_search
	) {
		switch ( $settings->advertising_location ) {
			case 'footer':
				add_action( 'wptouch_advertising_bottom', 'foundation_handle_advertising' );
				break;
			case 'header':
				add_action( 'wptouch_advertising_top', 'foundation_handle_advertising' );
				break;
			case 'top-content':
				add_filter( 'the_content', 'foundation_handle_advertising_content_top' );
				break;
			case 'bottom-content':
				add_filter( 'the_content', 'foundation_handle_advertising_content_bottom' );
				break;
			default:
				WPTOUCH_DEBUG( WPTOUCH_WARNING, 'Unknown advertising location: ' . $settings->advertising_location );
				break;
		}	
	}
}

function foundation_advertising_body_classes( $classes ) {
	$settings = foundation_get_settings();

	if ( $settings->advertising_type != 'none' ) {
		$classes[] = $settings->advertising_location . '-ad';	
	}

	if ( $settings->advertising_type == 'custom' ) {
		$classes[] = 'custom-ad';	
	}	
	
	return $classes;
}


function foundation_get_admob_ad() {
	global $wptouch_pro;

	ob_start();
	if ( $wptouch_pro->get_active_device_class() == WPTOUCH_DEFAULT_DEVICE_CLASS ) {
		include( dirname( __FILE__ ) . '/admob.php' );
	} 
			
	$advertising = ob_get_contents();
	ob_end_clean();	
	
	return $advertising;
}

function foundation_get_google_ad() {
	global $wptouch_pro;
	$settings = foundation_get_settings();

	ob_start();
	if ( $wptouch_pro->get_active_device_class() == WPTOUCH_DEFAULT_DEVICE_CLASS ) {
		switch( $settings->google_code_type ) {
			case 'sync':
				include( dirname( __FILE__ ) . '/adsense-iphone-sync.php' );
				break;
			case 'async':
				include( dirname( __FILE__ ) . '/adsense-iphone-async.php' );
				break;
		}
	} 

	$advertising = ob_get_contents();
	ob_end_clean();	
	
	return $advertising;
}

function foundation_handle_advertising_content( $content, $top_content = true ) {
	ob_start();
	foundation_handle_advertising();
	$advertising = ob_get_contents();
	ob_end_clean();	

	if ( $top_content ) {
		return $advertising . $content;
	} else {
		return $content . $advertising;	
	}
}
		
function foundation_handle_advertising_content_top( $content ) {
	return foundation_handle_advertising_content( $content, true );
}	

function foundation_handle_advertising_content_bottom( $content ) {
	return foundation_handle_advertising_content( $content, false );
}	

function foundation_advertising_can_show_ads() {
	$settings = foundation_get_settings();
	
	$can_show_ads = false;	

	if ( $settings->advertising_blog_listings  ) {
		$can_show_ads = ( is_home() || is_author() || is_date() );
	} 

	if ( $settings->advertising_single ) {
		$can_show_ads = $can_show_ads || is_single();
	} 

	if ( $settings->advertising_pages ) {
		$can_show_ads = $can_show_ads || is_page();
	} 

	if ( $settings->advertising_taxonomy ) {
		$can_show_ads = $can_show_ads || ( is_category() || is_tag() || is_tax() );
	} 

	if ( $settings->advertising_search ) {
		$can_show_ads = $can_show_ads || is_search();
	} 
	
	
	
	return $can_show_ads;
}
		
function foundation_handle_advertising() {
	$settings = foundation_get_settings();
	
	if ( foundation_advertising_can_show_ads() ) { 
		switch( $settings->advertising_type ) {
			case 'admob':
				echo '<div class="wptouch-ad">' . foundation_get_admob_ad() . '</div>';
				break;
			case 'google':
				echo '<div class="wptouch-ad">' . foundation_get_google_ad() . '</div>';
				break;
			case 'custom':
				echo '<div class="wptouch-custom-ad">' . $settings->custom_advertising_mobile . '</div>';
				break;
			case 'default':
				// Try to get this advertising type from a plugin
				do_action( 'wptouch_advertising_' . $settings->advertising_type );
				break;
		}
	}
}


function foundation_ad_settings( $page_options ) {

	wptouch_add_sub_page( FOUNDATION_PAGE_ADVERTISING, 'foundation-page-advertising', $page_options );

	wptouch_add_page_section(
		FOUNDATION_PAGE_ADVERTISING,
		__( 'Service', 'wptouch-pro' ),
		'service',
		array(
			wptouch_add_setting(
				'radiolist',
				'advertising_type',
				__( 'Choose a service', 'wptouch-pro' ),
				'',
				WPTOUCH_SETTING_BASIC,
				'1.0',
				array(
					'none' => __( 'None', 'wptouch-pro' ),
					'google' => __( 'Google Adsense', 'wptouch-pro' ),
					'custom' => _x( 'Custom', 'Refers to a custom advertising service', 'wptouch-pro' )
				)
			)
		),
		$page_options,
		FOUNDATION_SETTING_DOMAIN
	);

	wptouch_add_page_section(
		FOUNDATION_PAGE_ADVERTISING,
		__( 'Google AdSense', 'wptouch-pro' ),
		'google-adsense',
		array(
			wptouch_add_setting( 'text', 'google_adsense_id', __( 'Publisher ID', 'wptouch-pro' ), '', WPTOUCH_SETTING_BASIC, '1.0' ),
			wptouch_add_setting( 'text', 'google_slot_id', __( 'Slot ID', 'wptouch-pro' ), '', WPTOUCH_SETTING_BASIC, '1.0' ),
			wptouch_add_setting( 
				'list', 
				'google_code_type', 
				__( 'Code Type', 'wptouch-pro'), '', 
				WPTOUCH_SETTING_ADVANCED, 
				'1.0.6',
				array( 'sync' => 'Synchronous', 'async' => 'Asynchronous' )
			)
		),
		$page_options,
		FOUNDATION_SETTING_DOMAIN
	);

	wptouch_add_page_section(
		FOUNDATION_PAGE_ADVERTISING,
		__( 'Custom Ads', 'wptouch-pro' ),
		'custom-ads',
		array(
			wptouch_add_setting( 'textarea', 'custom_advertising_mobile', __( 'Mobile advertising script', 'wptouch-pro' ), '', WPTOUCH_SETTING_BASIC, '1.0' )
		),
		$page_options,
		FOUNDATION_SETTING_DOMAIN
	);


	wptouch_add_page_section(
		FOUNDATION_PAGE_ADVERTISING,
		__( 'Ad Presentation', 'wptouch-pro' ),
		'ad-presentation',
		array(
			wptouch_add_setting(
				'list',
				'advertising_location',
				__( 'Theme location', 'wptouch-pro' ),
				'',
				WPTOUCH_SETTING_BASIC,
				'1.0',
				array(
					'header' => __( 'In the header', 'wptouch-pro' ),
					'top-content' => __( 'Above the page content', 'wptouch-pro' ),
					'bottom-content' => __( 'Below the page content', 'wptouch-pro' )
				//	'footer' => __( 'In the footer', 'wptouch-pro' )
				)
			),
		),
		$page_options,
		FOUNDATION_SETTING_DOMAIN
	);
	wptouch_add_page_section(
		FOUNDATION_PAGE_ADVERTISING,
		__( 'Active Pages', 'wptouch-pro' ),
		'active-pages',
		array(
			wptouch_add_setting( 'checkbox', 'advertising_blog_listings', __( 'Blog listings', 'wptouch-pro' ), '', WPTOUCH_SETTING_BASIC, '1.0' ),
			wptouch_add_setting( 'checkbox', 'advertising_single', __( 'Single posts', 'wptouch-pro' ), '', WPTOUCH_SETTING_BASIC, '1.0' ),
			wptouch_add_setting( 'checkbox', 'advertising_pages', __( 'Static pages', 'wptouch-pro' ), '', WPTOUCH_SETTING_BASIC, '1.0' ),
			wptouch_add_setting( 'checkbox', 'advertising_taxonomy', __( 'Taxonomy', 'wptouch-pro' ), '', WPTOUCH_SETTING_BASIC, '1.0' ),
			wptouch_add_setting( 'checkbox', 'advertising_search', __( 'Search results', 'wptouch-pro' ), '', WPTOUCH_SETTING_BASIC, '1.0' )
		),
		$page_options,
		FOUNDATION_SETTING_DOMAIN
	);

	return $page_options;
	
}
