<?php

add_action( 'wptouch_admin_ajax_intercept', 'wptouch_pro_admin_ajax_intercept' );
add_filter( 'wptouch_theme_directories', 'wptouch_pro_theme_directories' );
add_filter( 'wptouch_addon_directories', 'wptouch_pro_addon_directories' );
add_filter( 'wptouch_settings_compat', 'wptouch_pro_add_compat_settings' );

function wptouch_pro_add_compat_settings( $page_options ) {
	if ( function_exists( 'icl_get_languages' ) ) { 
		wptouch_add_page_section(
			WPTOUCH_ADMIN_SETUP_COMPAT,
			'WPML',
			'compat-wpml',
			array(
				wptouch_add_setting( 
					'checkbox', 
					'show_wpml_lang_switcher', 
					__( 'Show WPML language switcher in theme footer', 'wptouch-pro' ), 
					'', 
					WPTOUCH_SETTING_BASIC, 
					'3.2.1' 
				)
			),
			$page_options
		);	
	}

	return $page_options;
}

function wptouch_pro_addon_directories( $addon_directories ) {
	if ( wptouch_is_multisite_enabled() ) {
		$addon_directories[] = array( WPTOUCH_BASE_CONTENT_MS_DIR . '/extensions', WPTOUCH_BASE_CONTENT_MS_URL . '/extensions' );
	}	

	$addon_directories[] = array( WPTOUCH_BASE_CONTENT_DIR . '/extensions', WPTOUCH_BASE_CONTENT_URL . '/extensions' );		

	return $addon_directories;	
}

function wptouch_pro_theme_directories( $theme_directories ) {
	if ( wptouch_is_multisite_enabled() ) {
		$theme_directories[] = array( WPTOUCH_BASE_CONTENT_MS_DIR . '/themes', WPTOUCH_BASE_CONTENT_MS_URL . '/themes' );
	}

	$theme_directories[] = array( WPTOUCH_BASE_CONTENT_DIR . '/themes', WPTOUCH_BASE_CONTENT_URL . '/themes' );		

	return $theme_directories;
}

// Functions only available in the pro version
function wptouch_can_show_license_menu() {
	$should_show_license_menu = true;
	
	if ( !wptouch_is_multisite_enabled( ) ) {
		$settings = wptouch_get_settings( 'bncid' );
		$should_show_license_menu = ( !$settings->license_accepted );
	} else {
		if ( is_plugin_active_for_network( WPTOUCH_PLUGIN_SLUG ) ) {
			// Plugin is network activated
			$settings = wptouch_get_settings( 'bncid' );
			$should_show_license_menu = ( !$settings->license_accepted ) && is_network_admin();
		} else {
			$settings = wptouch_get_settings( 'bncid' );
			$should_show_license_menu = ( !$settings->license_accepted );	
		}
	}
	
	return $should_show_license_menu;
}	

function wptouch_should_show_license_nag() {
	if ( wptouch_is_multisite_enabled() ) {
		$settings = wptouch_get_settings( 'bncid' );
		if ( is_plugin_active_for_network( WPTOUCH_PLUGIN_SLUG ) ) {
			return ( !$settings->license_accepted ) && current_user_can( 'manage_network_options' );
		} else {
			return ( !$settings->license_accepted );	
		}
	} else {
		return wptouch_can_show_license_menu();
	}
}

function wptouch_get_license_activation_url() {
	if ( is_plugin_active_for_network( WPTOUCH_PLUGIN_SLUG ) ) {
		return network_admin_url( 'admin.php?page=wptouch-admin-license' ); 
	} else {
		return admin_url( 'admin.php?page=wptouch-admin-license' ); 
	}	
}

function wptouch_add_pro_notifications() {
	global $wptouch_pro;

	// Check if licensed
	if ( ( WPTOUCH_SIMULATE_ALL || !wptouch_has_license() ) ) {
		if ( wptouch_should_show_license_nag() ) {
			$wptouch_pro->add_notification(
				__( 'License Missing', 'wptouch-pro' ),
				__( 'This installation of WPtouch Pro is currently unlicensed.', 'wptouch-pro' ),
				'error',
				wptouch_admin_url( 'admin.php?page=wptouch-admin-license' )
			);
		}
	}	

	// Plugin upgrade available
	$version = wptouch_is_upgrade_available();
	if ( ( WPTOUCH_SIMULATE_ALL || $version ) ) {
		$wptouch_pro->add_notification(
			sprintf( __( 'WPtouch Pro %s', 'wptouch-pro' ), $version ),
			__( 'A new version of WPtouch Pro is available.', 'wptouch-pro' ),
			'upgrade',
			is_multisite() ? network_admin_url( 'plugins.php?plugin_status=upgrade' ) : wptouch_admin_url( 'plugins.php?plugin_status=upgrade' )
		);
	}


	// Theme upgrade available
	$available_themes = $wptouch_pro->get_available_themes( true );
	foreach( $available_themes as $name => $theme ) {
		if ( isset( $theme->upgrade_available ) && $theme->upgrade_available ) {
			$wptouch_pro->add_notification(
				__( 'Theme Update Available', 'wptouch-pro' ),
				__( 'One or more updates are available for your installed themes.', 'wptouch-pro' ),
				'upgrade',
				admin_url( 'admin.php?page=wptouch-admin-themes-and-addons' )
			);

			break;
		} 
	}

	// Add-on upgrade available
	$available_addons = $wptouch_pro->get_available_addons( true );
	foreach( $available_addons as $name => $addons ) {
		if ( isset( $addons->upgrade_available ) && $addons->upgrade_available ) {
			$wptouch_pro->add_notification(
				__( 'Extension Update Available', 'wptouch-pro' ),
				__( 'One or more updates are available for your installed extensions.', 'wptouch-pro' ),
				'upgrade',
				admin_url( 'admin.php?page=wptouch-admin-themes-and-addons' )
			);

			break;
		} 
	}	

	// Error
	if ( WPTOUCH_SIMULATE_ALL || function_exists( 'wptouch_init' ) ) {
		$wptouch_pro->add_notification(
			'WPtouch 1.x',
			__( 'WPtouch Pro 3 cannot co-exist with WPtouch 1.x. Disable it first in the WordPress Plugins settings.', 'wptouch-pro' ),
			'error',
			'http://www.wptouch.com/support/knowledgebase/known-incompatibilities/#wptouchfree'
		);
	}

	// Error
	if ( WPTOUCH_SIMULATE_ALL || defined( 'WPTOUCH_PRO_MIN_BACKUP_FILES' ) ) {
		$wptouch_pro->add_notification(
			'WPtouch 2.x',
			__( 'WPtouch Pro 3 cannot co-exist with WPtouch Pro 2.x. Disable it first in the WordPress Plugins settings.', 'wptouch-pro' ),
			'error',
			'http://www.wptouch.com/support/knowledgebase/known-incompatibilities/#wptouch2'
		);
	}	
}

function wptouch_pro_admin_ajax_intercept( $ajax_action ) {
	global $wptouch_pro;

	switch( $ajax_action) {
		case 'reset-all-licenses':
			if ( $wptouch_pro->bnc_api->reset_all_licenses( 'wptouch-pro' ) ) {
				echo 'ok';
			} else {
				echo 'fail';
			}
			break;
		case 'activate-license-key':
			$email = $wptouch_pro->post['email'];
			$key = $wptouch_pro->post['key'];

			$settings = wptouch_get_settings( 'bncid' );

			$settings->bncid = $email;
			$settings->wptouch_license_key = $key;

			WPTOUCH_DEBUG( WPTOUCH_INFO, "Attempting site activation with email [" . $email . "] and key [" . $key . "]" );

			$settings->save();

			$wptouch_pro->bnc_api = false;
			$wptouch_pro->setup_bncapi();

			// let's try to activate the license
			$wptouch_pro->activate_license();

			// Check to see if the credentials were valid
			if ( $wptouch_pro->bnc_api->response_code >= 406 && $wptouch_pro->bnc_api->response_code <= 408 ) {
				WPTOUCH_DEBUG( WPTOUCH_WARNING, "Activation response code was [" . $wptouch_pro->bnc_api->response_code . "]" );
				echo '2';
			} else if ( $wptouch_pro->bnc_api->server_down ) {
				// Server is unreachable for some reason
				WPTOUCH_DEBUG( WPTOUCH_WARNING, "Activation response code was [SERVER UNREACHABLE]" );
				echo '4';
			} else if ( $wptouch_pro->bnc_api->verify_site_license( WPTOUCH_PRO_BNCAPI_PRODUCT_NAME ) ) {
				// Activation successful
				WPTOUCH_DEBUG( WPTOUCH_WARNING, "Activation successful, response code was [" . $wptouch_pro->bnc_api->response_code . "]" );

				$settings = wptouch_get_settings( 'bncid' );

				$settings->license_accepted = 1;
				$settings->license_accepted_time = time();

				$settings->save();

				echo '1';
			} else {
				// No more licenses - might be triggered another way
				WPTOUCH_DEBUG( WPTOUCH_WARNING, "Failure: activation response code was [" . $wptouch_pro->bnc_api->response_code . "]" );
				echo '3';
			}
			break;
		case 'download-addon':
			global $wptouch_pro;

			require_once( WPTOUCH_DIR . '/core/addon-theme-installer.php' );

			$addon_installer = new WPtouchAddonThemeInstaller;
			$addon_installer->install( $wptouch_pro->post[ 'base' ] , $wptouch_pro->post[ 'url' ], 'extensions' );

			$result = array();

			if ( file_exists( WPTOUCH_BASE_CONTENT_DIR . '/extensions/' . $wptouch_pro->post[ 'base' ] ) ) {
				$result['status'] = 1;
			} else {
				$result['status'] = 0;
				if ( $addon_installer->had_error() ) {
					$result['error'] = $addon_installer->error_text();
				} else {
					$result['error'] = __( 'Unknown error', 'wptouch-pro' );
				}
			}

			echo json_encode( $result );

			break;	
		case 'download-theme':
			global $wptouch_pro;

			require_once( WPTOUCH_DIR . '/core/addon-theme-installer.php' );

			$addon_installer = new WPtouchAddonThemeInstaller;
			$addon_installer->install( $wptouch_pro->post[ 'base' ] , $wptouch_pro->post[ 'url' ], 'themes' );

			$result = array();

			if ( file_exists( WPTOUCH_BASE_CONTENT_DIR . '/themes/' . $wptouch_pro->post[ 'base' ] ) ) {
				$result['status'] = 1;
			} else {
				$result['status'] = 0;
				if ( $addon_installer->had_error() ) {
					$result['error'] = $addon_installer->error_text();
				} else {
					$result['error'] = __( 'Unknown error', 'wptouch-pro' );
				}
			}

			echo json_encode( $result );

			break;									
	}
}

function wptouch_pro_handle_admin_command() {
	global $wptouch_pro;

	if ( isset( $wptouch_pro->get['admin_command'] ) ) {
		$admin_menu_nonce = $wptouch_pro->get['admin_menu_nonce'];
		if ( wptouch_admin_menu_nonce_is_valid( $admin_menu_nonce ) ) {
			// check user permissions
			if ( current_user_can( 'switch_themes' ) ) {
				switch( $wptouch_pro->get['admin_command'] ) {
					case 'activate_theme':
						WPTOUCH_DEBUG( WPTOUCH_INFO, 'Activating theme [' . $wptouch_pro->get['theme_name'] . ']' );
						$theme_to_activate = $wptouch_pro->get['theme_name'];
						if ( $theme_to_activate ) {
							$settings = $wptouch_pro->get_settings();

							$paths = explode( '/', ltrim( rtrim( $wptouch_pro->get['theme_location'], '/' ), '/' ) );

							$settings->current_theme_name = $paths[ count( $paths ) - 1 ];
							unset( $paths[ count( $paths ) - 1 ] );

							$settings->current_theme_location = '/' . implode( '/', $paths );
							$settings->current_theme_friendly_name = $wptouch_pro->get['theme_name'];

							$settings->save();
						}
						break;
					case 'activate_addon':
						WPTOUCH_DEBUG( WPTOUCH_INFO, 'Activating add-on [' . $wptouch_pro->get['addon_name'] . ']' );
						$addon_to_activate = $wptouch_pro->get['addon_name'];
						if ( $addon_to_activate ) {
							$settings = $wptouch_pro->get_settings();

							if ( !isset( $settings->active_addons[ $addon_to_activate ] ) ) {
								$paths = explode( '/', ltrim( rtrim( $wptouch_pro->get['addon_location'], DIRECTORY_SEPARATOR ), DIRECTORY_SEPARATOR ) );

								$addon_info = new stdClass;

								$addon_info->addon_name = $paths[ count( $paths ) - 1 ];
								unset( $paths[ count( $paths ) - 1 ] );
								$addon_info->location = '/' . implode( '/', $paths );

								$settings->active_addons[ $addon_to_activate ] = $addon_info;

								$settings->save();
							}
						}							
						break;
					case 'deactivate_addon':
						WPTOUCH_DEBUG( WPTOUCH_INFO, 'Deactivating add-on [' . $wptouch_pro->get['addon_name'] . ']' );
						$addon_to_deactivate = $wptouch_pro->get['addon_name'];	
						if ( $addon_to_deactivate ) {
							$settings = $wptouch_pro->get_settings();

							if ( isset( $settings->active_addons[ $addon_to_deactivate ] ) ) {
								unset( $settings->active_addons[ $addon_to_deactivate ] );
								$settings->save();	
							}
						}
						break;						
					case 'copy_theme':
						WPTOUCH_DEBUG( WPTOUCH_INFO, 'Copying theme [' . $wptouch_pro->get['theme_name'] . ']' );
						require_once( WPTOUCH_DIR . '/core/file-operations.php' );

						$copy_src = WP_CONTENT_DIR . $wptouch_pro->get['theme_location'];
						$theme_name = wptouch_convert_to_class_name( $wptouch_pro->get[ 'theme_name' ] );

						$num = $wptouch_pro->get_theme_copy_num( $theme_name );
						$copy_dest = WPTOUCH_CUSTOM_THEME_DIRECTORY . '/' . $theme_name . '-copy-' . $num;

						wptouch_create_directory_if_not_exist( $copy_dest );

						$wptouch_pro->recursive_copy( $copy_src, $copy_dest );

						$readme_file = $copy_dest . '/readme.txt';
						$readme_info = $wptouch_pro->load_file( $readme_file );
						if ( $readme_info ) {
							if ( preg_match( '#Theme Name: (.*)#', $readme_info, $matches ) ) {
								$readme_info = str_replace( $matches[0], 'Theme Name: ' . $matches[1] . ' Copy #' . $num, $readme_info );
								$f = fopen( $readme_file, "w+t" );
								if ( $f ) {
									fwrite( $f, $readme_info );
									fclose( $f );
								}
							}
						} else {
							WPTOUCH_DEBUG( WPTOUCH_ERROR, "Unable to modify readme.txt file after copy" );
						}
						break;
					case 'delete_theme':
						WPTOUCH_DEBUG( WPTOUCH_INFO, 'Deleting theme [' . $wptouch_pro->get['theme_location'] . ']' );
						require_once( WPTOUCH_DIR . '/core/file-operations.php' );

						$theme_location = WP_CONTENT_DIR . $wptouch_pro->get['theme_location'];

						$wptouch_pro->recursive_delete( $theme_location );
						break;
				}
			}
		}

		$used_query_args = array( 'admin_menu_nonce', 'admin_command', 'theme_name', 'theme_location' );

		header( 'Location: ' . remove_query_arg( $used_query_args ) );
		die;
	}
}

function wptouch_pro_check_for_update() {
	global $wptouch_pro;
	
	$upgrade_available = false;

	$wptouch_pro->setup_bncapi();

	$bnc_api = $wptouch_pro->get_bnc_api();

	$plugin_name = WPTOUCH_ROOT_NAME . '/wptouch-pro-3.php';

	WPTOUCH_DEBUG( WPTOUCH_INFO, 'Checking BNC server for a new product update' );

    // Check for WordPress 3.0 function
	if ( function_exists( 'is_super_admin' ) ) {
		$option = get_site_transient( 'update_plugins' );
	} else {
		$option = function_exists( 'get_transient' ) ? get_transient( 'update_plugins' ) : get_option( 'update_plugins' );
	}

	$version_available = false;

	if ( false === ( $latest_info = get_transient( '_wptouch_bncid_latest_version' ) ) ) {
		$latest_info = $bnc_api->get_product_version( 'wptouch-pro-3' );
		set_transient( '_wptouch_bncid_latest_version', $latest_info, WPTOUCH_API_GENERAL_CACHE_TIME );
	}

	if ( $latest_info && $latest_info[ 'version' ] != WPTOUCH_VERSION && isset( $latest_info[ 'upgrade_url' ] ) ) {
		if ( !isset( $option->response[ $plugin_name ] ) ) {
			$option->response[ $plugin_name ] = new stdClass();
		}

		WPTOUCH_DEBUG( WPTOUCH_INFO, 'A new product update is available [' . $latest_info['version'] . ']' );

		// Update upgrade options
		$option->response[ $plugin_name ]->url = 'http://www.wptouch.com/';
		$option->response[ $plugin_name ]->package = $latest_info[ 'upgrade_url' ];
		$option->response[ $plugin_name ]->new_version = $latest_info['version'];
		$option->response[ $plugin_name ]->id = '0';
		$option->response[ $plugin_name ]->slug = WPTOUCH_ROOT_NAME;

		$wptouch_pro->latest_version_info = $latest_info;

		$upgrade_available = $latest_info['version'];
	} else {
		if ( is_object( $option ) && isset( $option->response ) ) {
			unset( $option->response[ $plugin_name ] );	
		}
	}

	// WordPress 3.0 changed some stuff, so we check for a WP 3.0 function
	if ( function_exists( 'is_super_admin' ) ) {
		set_site_transient( 'update_plugins', $option );
	} else if ( function_exists( 'set_transient' ) ) {
		set_transient( 'update_plugins', $option );
	}

	return $upgrade_available;	
}

