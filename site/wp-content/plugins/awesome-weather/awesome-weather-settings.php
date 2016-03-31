<?php

// CREATE THE SETTINGS PAGE
function awesome_weather_setting_page_menu()
{
	add_options_page( 'Awesome Weather ', 'Awesome Weather', 'manage_options', 'awesome-weather', 'awesome_weather_page' );
}

function awesome_weather_page()
{
?>
<div class="wrap">
    <h2><?php _e('Awesome Weather Widget', 'awesome-weather'); ?></h2>
    
    <?php if( isset($_GET['awesome-weather-cached-cleared']) ) { ?>
    <div id="setting-error-settings_updated" class="updated settings-error"> 
		<p><strong><?php _e('Weather Widget Cache Cleared', 'awesome-weather'); ?></strong></p>
	</div>
	<?php } ?>
    
    <form action="options.php" method="POST">
        <?php settings_fields( 'awe-basic-settings-group' ); ?>
        <?php do_settings_sections( 'awesome-weather' ); ?>
        <?php submit_button(); ?>
    </form>
	<hr>
	<p>
		<a href="options-general.php?page=awesome-weather&action=awesome-weather-clear-transients" class="button"><?php _e('Clear all Awesome Weather Widget Cache', 'awesome-weather'); ?></a>
	</p> 
</div>
<?php
}


// SET SETTINGS LINK ON PLUGIN PAGE
function awesome_weather_plugin_action_links( $links, $file ) 
{
	$appid = apply_filters( 'awesome_weather_appid', awe_get_appid() );
	
	if( $appid )
	{
		$settings_link = '<a href="' . admin_url( 'options-general.php?page=awesome-weather' ) . '">' . esc_html__( 'Settings', 'awesome-weather' ) . '</a>';	
	}
	else
	{
		$settings_link = '<a href="' . admin_url( 'options-general.php?page=awesome-weather' ) . '">' . esc_html__( 'API Key Required', 'awesome-weather' ) . '</a>';
	}
	
	if( $file == 'awesome-weather/awesome-weather.php' ) array_unshift( $links, $settings_link );

	$donate_link = '<a href="https://halgatewood.com/donate" target="_blank">' . esc_html__( 'Donate', 'awesome-weather' ) . '</a>';
	if( $file == 'awesome-weather/awesome-weather.php' ) array_unshift( $links, $donate_link );
	
	$upgrade_link = '<a href="https://halgatewood.com/downloads/awesome-weather-widget-pro" target="_blank">' . esc_html__( 'Upgrade', 'awesome-weather' ) . '</a>';
	if( $file == 'awesome-weather/awesome-weather.php' ) array_unshift( $links, $upgrade_link );
	
	return $links;
}
add_filter( 'plugin_action_links', 'awesome_weather_plugin_action_links', 10, 2 );


add_action( 'admin_init', 'awesome_weather_setting_init' );
function awesome_weather_setting_init()
{
    register_setting( 'awe-basic-settings-group', 'open-weather-key' );
    register_setting( 'awe-basic-settings-group', 'aw-error-handling' );

    add_settings_section( 'awe-basic-settings', '', 'awesome_weather_api_keys_description', 'awesome-weather' );
	add_settings_field( 'open-weather-key', __('OpenWeatherMaps APPID', 'awesome-weather'), 'awesome_weather_openweather_key', 'awesome-weather', 'awe-basic-settings' );
	add_settings_field( 'aw-error-handling', __('Error Handling', 'awesome-weather'), 'awesome_weather_error_handling_setting', 'awesome-weather', 'awe-basic-settings' );

	if( isset($_GET['action']) AND $_GET['action'] == "awesome-weather-clear-transients")
	{
		awesome_weather_delete_all_transients();
		wp_redirect( "options-general.php?page=awesome-weather&awesome-weather-cached-cleared=true" );
		die;
	}
}




// DELETE ALL AWESOME WEATHER WIDGET TRANSIENTS
function awesome_weather_delete_all_transients_save( $value )
{
	awesome_weather_delete_all_transients();
	return $value;
}

function awesome_weather_delete_all_transients()
{
	global $wpdb;
	
	// DELETE TRANSIENTS
	$sql = "DELETE FROM $wpdb->options WHERE option_name LIKE '%_transient_awe_%'";
	$clean = $wpdb->query( $sql );
	return true;
}

function awesome_weather_api_keys_description() { }

function awesome_weather_openweather_key()
{
	if( defined('AWESOME_WEATHER_APPID') )
	{
		echo "<em>" . __('Defined in wp-config', 'awesome-weather-pro') . ": " . AWESOME_WEATHER_APPID . "</em>";
	}
	else 
	{
		$setting = esc_attr( apply_filters('awesome_weather_appid', get_option( 'open-weather-key' )) );
		echo "<input type='text' name='open-weather-key' value='$setting' style='width:70%;' />";
		echo "<p>";
		echo __("As of October 2015, OpenWeatherMap requires an APP ID key to access their weather data.", 'awesome-weather');
		echo " <a href='http://openweathermap.org/appid' target='_blank'>";
		echo __('Get your APPID', 'awesome-weather');
		echo "</a>";
		echo "</p>";
	}
}

function awesome_weather_error_handling_setting()
{
	$setting = esc_attr( get_option( 'aw-error-handling' ) );
	if(!$setting) $setting = "source";
	
	echo "<input type='radio' name='aw-error-handling' value='source' " . checked( $setting, 'source', false ) . " /> " . __('Hidden in Source', 'awesome-weather') . " &nbsp; &nbsp; ";
	echo "<input type='radio' name='aw-error-handling' value='display-admin' " . checked( $setting, 'display-admin', false ) . " /> " . __('Display if Admin', 'awesome-weather') . " &nbsp; &nbsp; ";
	echo "<input type='radio' name='aw-error-handling' value='display-all' " . checked( $setting, 'display-all', false ) . " /> " . __('Display for Anyone', 'awesome-weather') . " &nbsp; &nbsp; ";
	
	echo "<p>";
	echo __("What should the plugin do when there is an error?", 'awesome-weather');
	echo "</p>";
}