<?php
/*
Plugin Name: Awesome Weather Widget
Plugin URI: https://halgatewood.com/awesome-weather
Description: A weather widget that actually looks cool
Author: Hal Gatewood
Author URI: https://www.halgatewood.com
Version: 1.5.9
Text Domain: awesome-weather
Domain Path: /languages


FILTERS AVAILABLE:
https://halgatewood.com/docs/plugins/awesome-weather-widget/available-filters


// CLEAR OUT THE TRANSIENT CACHE
add to your URL 'clear_awesome_widget' 
For example: http://url.com/?clear_awesome_widget

*/


// SETTINGS
$awesome_weather_sizes = apply_filters( 'awesome_weather_sizes' , array( 'tall', 'wide' ) );


// SETUP
function awesome_weather_setup()
{
	$locale = apply_filters('plugin_locale', get_locale(), 'awesome-weather');
	$mofile = WP_LANG_DIR . "/awesome-weather/awesome-weather-" . $locale . '.mo';
	
	if( file_exists( $mofile ) )
	{
		load_textdomain( 'awesome-weather', $mofile );
	}
	else
	{
		load_plugin_textdomain( 'awesome-weather', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	add_action(	'admin_menu', 'awesome_weather_setting_page_menu' );
}
add_action('plugins_loaded', 'awesome_weather_setup', 99999);



// ENQUEUE CSS
function awesome_weather_wp_head( $posts ) 
{
	wp_enqueue_style( 'awesome-weather', plugins_url( '/awesome-weather.css', __FILE__ ) );
	
	$use_google_font = apply_filters('awesome_weather_use_google_font', true);
	$google_font_queuename = apply_filters('awesome_weather_google_font_queue_name', 'opensans-googlefont');
	
	if( $use_google_font )
	{
		wp_enqueue_style( $google_font_queuename, 'https://fonts.googleapis.com/css?family=Open+Sans:400,300' );
		wp_add_inline_style( 'awesome-weather', ".awesome-weather-wrap { font-family: 'Open Sans', sans-serif;  font-weight: 400; font-size: 14px; line-height: 14px; } " );
	}
}
add_action('wp_enqueue_scripts', 'awesome_weather_wp_head');



//THE SHORTCODE
add_shortcode( 'awesome-weather', 'awesome_weather_shortcode' );
function awesome_weather_shortcode( $atts )
{
	return awesome_weather_logic( $atts );	
}


// THE LOGIC
function awesome_weather_logic( $atts )
{
	global $awesome_weather_sizes;
	
	$dt_today = date( 'Ymd', current_time( 'timestamp', 0 ) );
	
	$rtn 						= "";
	$weather_data				= array();
	$location 					= isset($atts['location']) ? $atts['location'] : false;
	$owm_city_id				= isset($atts['owm_city_id']) ? $atts['owm_city_id'] : false;
	$size 						= (isset($atts['size']) AND $atts['size'] == "tall") ? 'tall' : 'wide';
	$units 						= (isset($atts['units']) AND strtoupper($atts['units']) == "C") ? "metric" : "imperial";
	$units_display				= $units == "metric" ? __('C', 'awesome-weather') : __('F', 'awesome-weather');
	$override_title 			= isset($atts['override_title']) ? $atts['override_title'] : false;
	$days_to_show 				= isset($atts['forecast_days']) ? $atts['forecast_days'] : 5;
	$show_stats 				= (isset($atts['hide_stats']) AND $atts['hide_stats'] == 1) ? 0 : 1;
	$show_attribution 			= (isset($atts['hide_attribution']) AND $atts['hide_attribution'] == 1) ? 0 : 1;
	$background_by_weather 		= (isset($atts['background_by_weather']) AND $atts['background_by_weather'] == 1) ? 1 : 0;
	$show_link 					= (isset($atts['show_link']) AND $atts['show_link'] == 1) ? 1 : 0;
	$background					= isset($atts['background']) ? $atts['background'] : false;
	$custom_bg_color			= isset($atts['custom_bg_color']) ? $atts['custom_bg_color'] : false;
	$inline_style				= isset($atts['inline_style']) ? $atts['inline_style'] : '';
	$text_color					= isset($atts['text_color']) ? $atts['text_color'] : '#ffffff';
	$locale						= 'en';

	$sytem_locale = get_locale();
	$available_locales = apply_filters('awesome_weather_available_locales', array( 'en', 'es', 'sp', 'fr', 'it', 'de', 'pt', 'ro', 'pl', 'ru', 'uk', 'ua', 'fi', 'nl', 'bg', 'sv', 'se', 'sk', 'ca', 'tr', 'hr', 'zh', 'zh_tw', 'zh_cn', 'hu' ) ); 

	
    // CHECK FOR LOCALE
    if( in_array( $sytem_locale, $available_locales ) ) $locale = $sytem_locale;
    
    
    // CHECK FOR LOCALE BY FIRST TWO DIGITS
    if( in_array(substr($sytem_locale, 0, 2), $available_locales ) ) $locale = substr($sytem_locale, 0, 2);

    
    // OVERRIDE LOCALE PARAMETER
    if( isset($atts['locale']) ) $locale = $atts['locale'];
    
  
	// DISPLAY SYMBOL
	$units_display_symbol = apply_filters('awesome_weather_units_display', '&deg;' );
    if( isset($atts['units_display_symbol']) ) $units_display_symbol = $atts['units_display_symbol'];
    

	// NO LOCATION, ABORT ABORT!!!1!
	if( !$location ) return awesome_weather_error();
	
	
	//FIND AND CACHE CITY ID
	if( $owm_city_id )
	{
		$city_name_slug 			= sanitize_title( $location );
		$api_query					= "id=" . $owm_city_id;
	}
	else if( is_numeric($location) )
	{
		$city_name_slug 			= sanitize_title( $location );
		$api_query					= "id=" . urlencode($location);
	}
	else
	{
		$city_name_slug 			= sanitize_title( $location );
		$api_query					= "q=" . urlencode($location);
	}
	
	
	// TRANSIENT NAME
	$weather_transient_name 		= 'awe_' . $city_name_slug . "_" . $days_to_show . "_" . strtolower($units) . '_' . $locale;


	// TWO APIS USED (VERSION 2.5)
	//http://api.openweathermap.org/data/2.5/weather?q=London,uk&units=metric&cnt=7&lang=fr
	//http://api.openweathermap.org/data/2.5/forecast/daily?q=London&units=metric&cnt=7&lang=fr
    
    
    // CLEAR THE TRANSIENT
    if( isset($_GET['clear_awesome_widget']) ) delete_transient( $weather_transient_name );

    
	// APPID
	$appid_string = '';
	$appid = apply_filters( 'awesome_weather_appid', awe_get_appid() );
	if($appid) $appid_string = '&APPID=' . $appid;
    
	
	// GET WEATHER DATA
	if( get_transient( $weather_transient_name ) )
	{
		$weather_data = get_transient( $weather_transient_name );
	}
	else
	{
		$weather_data['now'] = array();
		$weather_data['forecast'] = array();
		
		// NOW
		$now_ping = "http://api.openweathermap.org/data/2.5/weather?" . $api_query . "&lang=" . $locale . "&units=" . $units . $appid_string;
		$now_ping_get = wp_remote_get( $now_ping );
		
	
		// PING URL ERROR
		if( is_wp_error( $now_ping_get ) )  return awesome_weather_error( $now_ping_get->get_error_message()  ); 


		// GET BODY OF REQUEST
		$city_data = json_decode( $now_ping_get['body'] );
		
		if( isset($city_data->cod) AND $city_data->cod == 404 )
		{
			return awesome_weather_error( $city_data->message ); 
		}
		else
		{
			$weather_data['now'] = $city_data;
		}
		
		
		// FORECAST
		$forecast_ping = "http://api.openweathermap.org/data/2.5/forecast/daily?" . $api_query . "&lang=" . $locale . "&units=" . $units ."&cnt=7" . $appid_string;
		$forecast_ping_get = wp_remote_get( $forecast_ping );
		
		if( is_wp_error( $forecast_ping_get ) ) 
		{
			return awesome_weather_error( $forecast_ping_get->get_error_message()  ); 
		}	
		
		$forecast_data = json_decode( $forecast_ping_get['body'] );
		
		if( isset($forecast_data->cod) AND $forecast_data->cod == 404 )
		{
			return awesome_weather_error( $forecast_data->message ); 
		}
		else
		{
			$weather_data['forecast'] = $forecast_data;
		}	
		
		if($weather_data['now'] OR $weather_data['forecast'])
		{
			set_transient( $weather_transient_name, $weather_data, apply_filters( 'awesome_weather_cache', 1800 ) ); 
		}
	}
	
	

	// NO WEATHER
	if( !$weather_data OR !isset($weather_data['now'])) return awesome_weather_error();
	

	
	// TODAYS TEMPS
	$today 			= $weather_data['now'];
	$today_temp 	= isset($today->main->temp) ? round($today->main->temp) : false;
	
	
	// GET TODAY FROM FORECAST IF AVAILABLE
	if( isset($weather_data['forecast']) AND isset($weather_data['forecast']->list) AND isset($weather_data['forecast']->list[0]) )
	{
		$forecast_today = $weather_data['forecast']->list[0];
		$today_high = round($forecast_today->temp->max);
		$today_low = round($forecast_today->temp->min);
	}
	else
	{
		$today_high = isset($today->main->temp_max) ? round($today->main->temp_max) : false;
		$today_low 	= isset($today->main->temp_min) ? round($today->main->temp_min) : false;
	}
	
	
	// TEXT COLOR
	if( substr(trim($text_color), 0, 1) != "#" ) $text_color = "#" . $text_color;
	$inline_style .= " color: {$text_color}; ";
	
	
	// BACKGROUND DATA, CLASSES AND OR IMAGES
	$background_classes = array();
	$background_classes[] = "awesome-weather-wrap";
	$background_classes[] = "awecf";
	$background_classes[] = "awe_" . $size;
	
	if( $custom_bg_color )
	{
		if( substr(trim($custom_bg_color), 0, 1) != "#" AND substr(trim(strtolower($custom_bg_color)), 0, 3) != "rgb" ) { $custom_bg_color = "#" . $custom_bg_color; }
		$inline_style .= " background-color: {$custom_bg_color}; ";
		$background_classes[] = "awe_custom";
	}
	else if( $today_temp )
	{
		// COLOR OF WIDGET
		if($units == "imperial")
		{
			if($today_temp > 31 AND $today_temp < 40) $background_classes[] = "temp2";
			else if($today_temp >= 40 AND $today_temp < 50) $background_classes[] = "temp3";
			else if($today_temp >= 50 AND $today_temp < 60) $background_classes[] = "temp4";
			else if($today_temp >= 60 AND $today_temp < 80) $background_classes[] = "temp5";
			else if($today_temp >= 80 AND $today_temp < 90) $background_classes[] = "temp6";
			else if($today_temp >= 90) $background_classes[] = "temp7";
			else $background_classes[] = "temp1";
		}
		else
		{
			if($today_temp > 1 AND $today_temp < 4) $background_classes[] = "temp2";
			else if($today_temp >= 4 AND $today_temp < 10) $background_classes[] = "temp3";
			else if($today_temp >= 10 AND $today_temp < 15) $background_classes[] = "temp4";
			else if($today_temp >= 15 AND $today_temp < 26) $background_classes[] = "temp5";
			else if($today_temp >= 26 AND $today_temp < 32) $background_classes[] = "temp6";
			else if($today_temp >= 32) $background_classes[] = "temp7";
			else $background_classes[] = "temp1";
		}
	}

	// DATA
	$header_title = $override_title ? $override_title : $today->name;
	
	
	// WIND
	$wind_label = array ( __('N', 'awesome-weather'), __('NNE', 'awesome-weather'), __('NE', 'awesome-weather'), __('ENE', 'awesome-weather'), __('E', 'awesome-weather'), __('ESE', 'awesome-weather'), __('SE', 'awesome-weather'), __('SSE', 'awesome-weather'), __('S', 'awesome-weather'), __('SSW', 'awesome-weather'), __('SW', 'awesome-weather'), __('WSW', 'awesome-weather'), __('W', 'awesome-weather'), __('WNW', 'awesome-weather'), __('NW', 'awesome-weather'), __('NNW', 'awesome-weather') );
						
	$wind_direction = false;
	if( isset($today->wind->deg) ) $wind_direction = apply_filters('awesome_weather_wind_direction', $wind_label[ fmod((($today->wind->deg + 11) / 22.5),16) ]);


	$background_classes[] = ($show_stats) ? "awe_with_stats" : "awe_without_stats";
	

	// ADD WEATHER CONDITIONS CLASSES TO WRAP
	if( isset($today->weather[0]) )
	{
		$weather_code = $today->weather[0]->id;
		$weather_description_slug = sanitize_title( $today->weather[0]->description );
		
		$background_classes[] = "awe-code-" . $weather_code;
		$background_classes[] = "awe-desc-" . $weather_description_slug;
	}
	
	// CHECK FOR BACKGROUND BY WEATHER
	if( $background_by_weather AND ( $weather_code OR $weather_description_slug ) )
	{
		if( file_exists( get_stylesheet_directory() . "/awe-backgrounds" ) )
		{
			$bg_ext = apply_filters('awesome_weather_bg_ext', 'jpg' );
			
			// CHECK FOR CODE
			if( $weather_code AND file_exists( get_stylesheet_directory() . "/awe-backgrounds/" . $weather_code . "." . $bg_ext))
			{
				$background = get_stylesheet_directory_uri() . "/awe-backgrounds/" . $weather_code . "." . $bg_ext;
			}
			else if( $weather_description_slug AND file_exists( get_stylesheet_directory() . "/awe-backgrounds/" . $weather_description_slug . "." . $bg_ext))
			{
				$background = get_stylesheet_directory_uri() . "/awe-backgrounds/" . $weather_description_slug . "." . $bg_ext;
			}
			else
			{
				// PRESET WEATHER NAMES
				$preset_background_img_name = awesome_weather_preset_condition_names_openweathermaps( $weather_code );
	
				if( $preset_background_img_name )
				{
					$background_classes[] = "awe-preset-" . $preset_background_img_name;
					if( file_exists( get_stylesheet_directory() . "/awe-backgrounds/" . $preset_background_img_name . "." . $bg_ext) ) $background = get_stylesheet_directory_uri() . "/awe-backgrounds/" . $preset_background_img_name . "." . $bg_ext;
				}
			}
		}
		else
		{
			// PRESET WEATHER NAMES
			$preset_background_img_name = awesome_weather_preset_condition_names_openweathermaps( $weather_code );
				
			if( $preset_background_img_name )
			{
				$background_classes[] = "awe-preset-" . $preset_background_img_name;
				if( file_exists( dirname(__FILE__) . "/img/awe-backgrounds/" . $preset_background_img_name . ".jpg") ) $background = plugin_dir_url( __FILE__ ) . "/img/awe-backgrounds/" . $preset_background_img_name . ".jpg";
			}
		}
	}

	
	// EXTRA STYLES
	if($background) $background_classes[] = "darken";
	if($inline_style != "") $inline_style = " style=\"{$inline_style}\"";


	$background_class_string = @implode( " ", apply_filters( 'awesome_weather_background_classes', $background_classes ));

	// DISPLAY WIDGET	
	$rtn .= "<div id=\"awesome-weather-{$city_name_slug}\" class=\"{$background_class_string}\"{$inline_style}>";

	if($background) 
	{ 
		$rtn .= "<div class=\"awesome-weather-cover\" style='background-image: url($background);'>";
		if( !$background_by_weather) $rtn .= "<div class=\"awesome-weather-darken\">";
	}

	$rtn .= "<div class=\"awesome-weather-header\">{$header_title}</div>";
	$rtn .= "<div class=\"awesome-weather-current-temp\"><strong>{$today_temp}<sup>{$units_display_symbol}</sup></strong></div><!-- /.awesome-weather-current-temp -->";	
	
	if($show_stats AND isset($today->main) )
	{
		$wind_speed = isset($today->wind->speed) ? $today->wind->speed : false;
		
		$wind_speed_text 	= ( $units == "imperial" ) ? __('mph', 'awesome-weather') : __('m/s', 'awesome-weather');
		$wind_speed_obj = apply_filters('awesome_weather_wind_speed', array( 
																				'text' => apply_filters('awesome_weather_wind_speed_text', $wind_speed_text), 
																				'speed' => round($wind_speed), 
																				'direction' => $wind_direction ), $wind_speed, $wind_direction );
	
		// CURRENT WEATHER STATS
		$rtn .= '<div class="awesome-weather-todays-stats">';
		if( isset($today->weather[0]->description) ) $rtn .= '<div class="awe_desc">' . $today->weather[0]->description . '</div>';
		if( isset($today->main->humidity) ) $rtn .= '<div class="awe_humidty">' . __('humidity:', 'awesome-weather') . " " . $today->main->humidity . '%</div>';
		if( $wind_speed AND $wind_direction) $rtn .= '<div class="awe_wind">' . __('wind:', 'awesome-weather') . ' ' .$wind_speed_obj['speed'] . $wind_speed_obj['text'] . ' ' .$wind_speed_obj['direction'] . '</div>';
		if( $today_high AND $today_low) $rtn .= '<div class="awe_highlow">' . __('H', 'awesome-weather') . ' ' . $today_high . ' &bull; ' . __('L', 'awesome-weather') . ' ' . $today_low . '</div>';	
		$rtn .= '</div><!-- /.awesome-weather-todays-stats -->';
	}

	if($days_to_show != "hide")
	{
		$rtn .= "<div class=\"awesome-weather-forecast awe_days_{$days_to_show} awecf\">";
		$c = 1;
		$forecast = $weather_data['forecast'];
		$days_to_show = (int) $days_to_show;
		$days_of_week = apply_filters( 'awesome_weather_days_of_week', array( __('Sun' ,'awesome-weather'), __('Mon' ,'awesome-weather'), __('Tue' ,'awesome-weather'), __('Wed' ,'awesome-weather'), __('Thu' ,'awesome-weather'), __('Fri' ,'awesome-weather'), __('Sat' ,'awesome-weather') ) );
		
		if(!isset($forecast->list)) $forecast->list = array();
		
		foreach( (array) $forecast->list as $forecast )
		{
			if( $dt_today >= date('Ymd', $forecast->dt)) continue;
			$forecast->temp = (int) $forecast->temp->day;
			$day_of_week = $days_of_week[ date('w', $forecast->dt) ];
			$rtn .= "
				<div class=\"awesome-weather-forecast-day\">
					<div class=\"awesome-weather-forecast-day-temp\">{$forecast->temp}<sup>{$units_display_symbol}</sup></div>
					<div class=\"awesome-weather-forecast-day-abbr\">{$day_of_week}</div>
				</div>";
			if($c == $days_to_show) break;
			$c++;
		}
		$rtn .= "</div><!-- /.awesome-weather-forecast -->";
	}
	
	if($show_link AND isset($today->id))
	{
		$show_link_text 		= apply_filters('awesome_weather_extended_forecast_text' , __('extended forecast', 'awesome-weather'));
		$extended_url_target 	= apply_filters('awesome_weather_extended_url_target', '_blank');
		$rtn .= "<div class=\"awesome-weather-more-weather-link\">";
		$rtn .= "<a href=\"http://openweathermap.org/city/{$today->id}\" target=\"{$extended_url_target}\">{$show_link_text}</a>";		
		$rtn .= "</div> <!-- /.awesome-weather-more-weather-link -->";
	}
	
	if( $show_attribution ) $rtn .= "<div class=\"awesome-weather-attribution\">" . __('Weather from', 'awesome-weather') . " OpenWeatherMap</div>";
	
	if($background) 
	{ 
		if( !$background_by_weather) $rtn .= "</div><!-- /.awesome-weather-darken -->";
		$rtn .= "</div><!-- /.awesome-weather-cover -->";
	}

	$rtn .= "</div> <!-- /.awesome-weather-wrap -->";
	return $rtn;
}


// RETURN ERROR
function awesome_weather_error( $msg = false )
{
	$error_handling = get_option( 'aw-error-handling' );
	if(!$error_handling) $error_handling = "source";
	if(!$msg) $msg = __('No weather information available', 'awesome-weather');
	
	if( $error_handling == "display-admin")
	{
		// DISPLAY ADMIN
		if ( current_user_can( 'manage_options' ) ) 
		{
			return "<div class='awesome-weather-error'>" . $msg . "</div>";
		}
	}
	else if( $error_handling == "display-all")
	{
		// DISPLAY ALL
		return "<div class='awesome-weather-error'>" . $msg . "</div>";
	}
	else
	{
		return apply_filters( 'awesome_weather_error', "<!-- AWESOME WEATHER ERROR: " . $msg . " -->" );
	}
}


// ENQUEUE ADMIN SCRIPTS
function awesome_weather_admin_scripts( $hook )
{
	if( 'widgets.php' != $hook ) return;
	
	wp_enqueue_style('jquery');
	wp_enqueue_style('underscore');
	wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker'); 
	
    wp_enqueue_script( 'awesome_weather_admin_script', plugin_dir_url( __FILE__ ) . '/awesome-weather-widget-admin.js', array('jquery','underscore') );
    
	wp_localize_script( 'awesome_weather_admin_script', 'awe_script', array(
			'no_owm_city'				=> esc_attr(__("No city found in OpenWeatherMap.", 'awesome-weather')),
			'one_city_found'			=> esc_attr(__('Only one location found. The ID has been set automatically above.', 'awesome-weather')),
			'confirm_city'				=> esc_attr(__('Please confirm your city: &nbsp;', 'awesome-weather')),
		)
	);
	

}
add_action( 'admin_enqueue_scripts', 'awesome_weather_admin_scripts' );


// GET APPID
function awe_get_appid()
{
	return defined('AWESOME_WEATHER_APPID') ? AWESOME_WEATHER_APPID : get_option( 'open-weather-key' );
}


// PING OPENWEATHER FOR OWMID
add_action( 'wp_ajax_awe_ping_owm_for_id', 'awe_ping_owm_for_id');
function awe_ping_owm_for_id( )
{
	$appid_string = '';
	$appid = apply_filters('awesome_weather_appid', awe_get_appid());
	if($appid) $appid_string = '&APPID=' . $appid;
	
	$location = urlencode($_GET['location']);
	$units = $_GET['units'] == "C" ? "metric" : "imperial";
	$owm_ping = "http://api.openweathermap.org/data/2.5/find?q=" . $location ."&units=" . $units . "&mode=json" . $appid_string;
	$owm_ping_get = wp_remote_get( $owm_ping );
	header("Content-Type: application/json");
	echo $owm_ping_get['body'];
	die;
}


// PRESET WEATHER BACKGROUND NAMES
function awesome_weather_preset_condition_names_openweathermaps( $weather_code )
{
	if( substr($weather_code,0,1) == "2" ) 										return "thunderstorm";
	else if( substr($weather_code,0,1) == "3" ) 								return "drizzle";
	else if( substr($weather_code,0,1) == "5" ) 								return "rain";
	else if( $weather_code == 611 ) 											return "sleet";
	else if( substr($weather_code,0,1) == "6" OR $weather_code == 903 ) 		return "snow";
	else if( $weather_code == 781 OR $weather_code == 900 ) 					return "tornado";
	else if( $weather_code == 800 OR $weather_code == 904 ) 					return "sunny";
	else if( substr($weather_code,0,1) == "7" ) 								return "atmosphere";
	else if( substr($weather_code,0,1) == "8" ) 								return "cloudy";
	else if( $weather_code == 901 ) 											return "tropical-storm";
	else if( $weather_code == 902 OR $weather_code == 962 ) 					return "hurricane";
	else if( $weather_code == 905 ) 											return "windy";
	else if( $weather_code == 906 ) 											return "hail";
	else if( $weather_code == 951 ) 											return "calm";
	else if( $weather_code > 951 AND $weather_code < 962 ) 						return "breeze";
}




// WIDGET
require_once( dirname(__FILE__) . "/widget.php"); 


// SETTINGS
require_once( dirname(__FILE__) . "/awesome-weather-settings.php");  
