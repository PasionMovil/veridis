=== Awesome Weather Widget ===
Contributors: halgatewood
Donate link: https://halgatewood.com/donate/
Tags: widgets, sidebar, shortcode, openweathermap, weather, weather widget, forecast, global, temp, local weather,local forecast
Requires at least: 3.5
Tested up to: 4.5
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Finally beautiful weather widgets for your beautiful site.

== Description ==

>[Pro Version](https://halgatewood.com/downloads/awesome-weather-widget-pro/) available. Weather icons, 5 New Layouts and Custom Templates, Weather by Yahoo!, User Location Detection and more!

This plugin allows you to easily add super clean (and awesome) weather widgets to your site. 

The weather data is provided for free by http://openweathermap.org and as of October 2015, they require an [API Key](http://openweathermap.org/appid#get) to access their weather. 
Once you have the API Key you can simply add it in 'Settings' -> 'Awesome Weather' and you're ready to go. 

[View the API Key Help Document](https://halgatewood.com/docs/plugins/awesome-weather-widget/register-for-an-openweathermap-api-key-appid)


Use the built in widget with all of its marvelous settings or add it to a page or theme with the shortcode:

Easiest: 
`[awesome-weather location="Oklahoma City"]`

All Available Parameters: 
`[awesome-weather location="Montreal" units="F" owm_city_id="6077243" size="tall" override_title="MTL" forecast_days="3" hide_stats="1" background="http://urltoanimage.jpg" custom_bg_color="#cccccc" inline_style="width: 200px; margin: 20px; float: left;" background_by_weather="1" text_color="#000" locale="fr"]`

= Settings =

*   Location: Enter a string like "Montreal, CA" or just "Montreal". You can even get the City ID from OpenWeatherMap and use that.
*   Units: F (default) or C
*   Size: wide (default) or tall
*   Override Title: Change the title in the header bar to whatever, sometimes it pulls weather from a close city
*   Forecast Days: How many days to show in the forecast bar
*   Hide stats: Hide the text stats like humidity, wind, high and lows, etc
*   Background: URL to an image that will be used as the background of the entire widget
*   Custom Background Color: Add a hex color to override the default colors
*   Inline Styles: Add inline CSS styles to your widget to float around text and whatever else
*   Background Image Based on Weather (v1.5+): Set this to 1 if you want to use different images for different weather types, How to: https://halgatewood.com/awesome-weather-bgs
*	Text Color: Easily change the text color of the widget without CSS
*	Locale: New locale attribute for shortcodes allows you to change the weather description text without having to change your whole WordPress installation.

= Translations =
* Portuguese - alvarogois
* German - Anne Bremer
* Polish - Femcio
* Dutch - Marco Rooze
* Swedish - AJ
* French - Gilles D.
* Italian - Cesare C.
* Russian - Alex
* Spanish - JoaquÃ­n B.
* Greek - John T.
* Finnish - Mikko A.
* Hungarian - Istvan H.
* Arabic - Fouad Y.
* Croatian - Vinko P.
* Nederlands - Schoene H.
* Turkish - Saban K.

== Installation ==

1. Add plugin to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Register for an OpenWeatherMap [API Key](http://openweathermap.org/appid#get)
1. Add your API Key to the settings field in 'Settings' -> 'Awesome Weather' (added in version 1.5.3)
1. Use shortcode or widget to display awesome weather on your awesome site

The easiest shortcode setting is just: `[awesome-weather location="Oklahoma City"]`


== Screenshots ==

1. Basic wide layout
2. Basic tall layout
3. Micro, using the checkbox 'Hide Stats'
4. Widget Settings
5. Background Image Option (1.2)
6. Add inline styles to your widget and set custom background colors (1.3.1)
7. Use different background images based on weather (1.5)
8. Search for the City ID directly in the widget settings (1.5)

== Upgrade Notice ==

= 1.5.9 = 
Checkbox to hide the attribution in the widget settings or use shortcode attribute hide_attribution="1"

= 1.5.7 =
Updated the language .pot file. A few new text items have been added and some modified. Fixed location searches that contain commas

= 1.5.6 = 
Two new filters available to remove to the Google font Open Sans. Added new missing background preset 'atmosphere'

= 1.5.5 = 
Some new shortcode attributes, and a constant can be set for the OpenWeatherMap AppID. This helps multisite installations. 

= 1.5.4 =
* Preset background images added for the 'Use Different Background Images Based on Weather' option
* Color picker to choose a font color for the whole widget
* CSS whitespace: no-wrap on the little degree symbols
* Fixed wind speed to mph for 'Imperial' and m/s for Celsius as per the OpenWeatherMap weather data section
* Updated .pot file

= 1.5.3.1 =
* Added an error message in the admin widget page to let users know they need an APPID

= 1.5.3 =
* Added spot for API key in 'Settings' -> 'Awesome Weather'
* Added new setting to decide how to show error messages.

= 1.5.1 =
* Changed .custom class to .awe_custom

= 1.5 =
* You can now speed up your weather and provide better accuracy by using the new Search box in the widget to find the OpenWeatherMap City ID. 
* We also added the ability to use different background images by weather. Also new CSS properties using the weather condition code and text are added so you can target based on weather type.
* Added several new filters to modify aspects of the weather widget like changing the C and F to a Degree symbol.
* Fixed an issue where changing the forecast days would not clear the cache.
* Default cache is now 30 minutes

= 1.4.2 =
Slightly modified the CSS to include box-sizing. This may slightly modify the look of your widget, hopefully in a cleaner way.

= 1.4.1 =
* New translations added.
* Caching fixes
* Fixed checkboxes in widget settings
* Checked to be working with WordPress 3.9

= 1.4 =
* Extended forecast now uses WP current_time to determine what days to show, uses Timezone in 'Settings' -> 'General'
* Added a Widget Title field that uses the standard widget code from the sidebar (optional)
* Support for OpenWeatherMaps City ID, just insert in the Location field.
* Support for rgba() in the Custom Background Color

= 1.3.4 =
Fixed issue with Location stripping spaces from text cause weather to not get accessed. Thanks @storkontheroof!

= 1.3.3 =
Moved Google Font out of CSS into enqueue

= 1.3.2 =
Improved support for poorly coded themes that load the before_title and after_title with extra divs and don't take into account that widgets may not use a title. 

= 1.3.1 = 
Added setting for color override
Added URL param to clear transient cache '?clear_awesome_widget'
Added new translations for the days of the week in the extended forecast
Ability to add inline styles to the widget shortcode

= 1.3 =
Upgraded to most recent OpenWeatherMap APIS and included translation files.

= 1.2.6 =
Improved error handling with API calls

= 1.2.5 =
Widget with custom background CSS issue.

= 1.2.4 =
Forecast now stops showing today.

= 1.2.3 =
Better API Support

= 1.2 =
Background Image Option

= 1.1.1 =
Google Font URL changed or I messed it up, probably me.

= 1.1 =
Errors are now commented out. Look in the source to see what the problem is.

= 1.0.2 =
Removed debugging code, sorry!

= 1.0.1 =
Changed API endpoints. Might not find weather without update.




== Changelog ==

= 1.5.9 =
* Updated language to allow for custom translations located at: wp-content/languages/awesome-weather/awesome-weather-{locale}.mo 
* Checkbox to hide the attribution in the widget settings or use shortcode attribute hide_attribution="1"

= 1.5.8 =
* Language fixes and improvements
* OpenWeatherMap attribution added as per licensing requirements
* High and Low temperature fix

= 1.5.7 =
* Wording changes to help improve user experience, thus new updated .pot file
* Bug fix for AWESOME_WEATHER_APPID constant, wasn't always being used.
* Changed awesome-weather-widget.js to awesome-weather-widget-admin.js
* Moved Widget functions into widget.php file
* Fixed locations searches that contain commas
* Check for variables exist before trying to display them (minimizes PHP Notices)
* Rounding wind speed to nearest integer
* Added filters used in the PRO version to keep consistancy

= 1.5.6.2 =
* Added SK to locales and a filter to modify list of available locales

= 1.5.6.1 =
* Fix trailing slash issue with one of the preset background image checks

= 1.5.6 =
* Two new filters available to remove to the Google font Open Sans. (`awesome_weather_use_google_font` AND `awesome_weather_google_font_queue_name`)
* Added new missing background preset `atmosphere.jpg`

= 1.5.5 =
* New constant for the AppID: AWESOME_WEATHER_APPID. Set in `wp-config.php` for multisite installations
* Shortcode attribute for locale: `locale="fr"`
* Shortcode attribute for the units display symbol: `units_display_symbol="&deg;F"`

= 1.5.4 =
* Preset background images added for the 'Use Different Background Images Based on Weather' option
* Color picker to choose a font color for the whole widget
* CSS whitespace: no-wrap on the little degree symbols
* Fixed wind speed to mph for 'Imperial' and m/s for Celsius as per the OpenWeatherMap weather data section
* Updated .pot file

= 1.5.3.1 =
* Added an error message in the admin widget page to let users know they need an APPID

= 1.5.3 =
* Added spot for API key in 'Settings' -> 'Awesome Weather'
* Added new setting to decide how to show error messages.

= 1.5.2 =
* Updated WP_Widget construct to prevent future breaking.

= 1.5.1 =
* Changed .custom class to .awe_custom

= 1.5 =
* You can now speed up your weather and provide better accuracy by using the new Search box in the widget to find the OpenWeatherMap City ID. 
* We also added the ability to use different background images by weather. Also new CSS properties using the weather condition code and text are added so you can target based on weather type.
* Added several new filters to modify aspects of the weather widget like changing the C and F to a Degree symbol.
* Fixed an issue where changing the forecast days would not clear the cache.
* Default cache is now 30 minutes

= 1.4.3.3 =
* Hungarian language added (thanks Istvan Hidegkuti

= 1.4.3.2 =
* Finnish language added (thanks Mikko Anttila)

= 1.4.3.1 =
More languages correctly supported from the OpenWeatherMap API

= 1.4.3 =
* Fixed spanish translations for weather description
* Transient bug for languages also

= 1.4.2 = 
Added box-sizing: border-box to hopefully clean up the widget in many themes.

= 1.4.1 =
* New translations added.
* Caching fixes
* Fixed checkboxes in widget settings
* Checked to be working with WordPress 3.9

= 1.4 =
* Extended forecast now uses WP current_time to determine what days to show, setting in 'Settings' -> 'General'
* Added a Widget Title field that uses the standard widget code from the sidebar (optional)
* Fixed bottom margin issue for Firefox
* Minor CSS tweaks
* Support for OpenWeatherMaps City ID, just insert in the Location field.
* Support for rgba() in the Custom Background Color
* Changed default cached time from 1 hour to 3 hours
* Cleaned up two PHP notices in admin

= 1.3.4 =
Fixed issue with Location stripping spaces from text cause weather to not get accessed. Thanks @storkontheroof!

= 1.3.3 =
Moved Google Font out of CSS into enqueue

= 1.3.2 =
* Improved support for poorly coded themes that load the before_title and after_title with extra divs and don't take into account that widgets may not use a title. 
* Portuguese translation created by user: alvarogois

= 1.3.1 = 
* Added setting for color override
* Added URL param to clear transient cache '?clear_awesome_widget'
* Added new translations for the days of the week in the extended forecast
* Improved caching support
* Ability to add inline styles to the widget shortcode

= 1.3 =
* Upgraded to most recent OpenWeatherMap APIS
* Ready for translations
* Current Locales available from OpenWeatherMap: 
* English - en, Russian - ru, Italian - it, Spanish - sp, Ukrainian - ua, German - de, Portuguese - pt, Romanian - ro, Polish - pl, Finnish - fi, Dutch - nl, French - fr, Bulgarian - bg, Swedish - se, Chinese Traditional - zh_tw, Chinese Simplified - zh_cn, Turkish - tr 

= 1.2.6 =
* Improved error handling with API calls

= 1.2.5 =
* Widget with custom background CSS issue.

= 1.2.4 =
* Forecast now stops showing today.

= 1.2.3 =
* remove file_get_contents and used wp_remote_get

= 1.2.1 =
* Background Image Option in widget

= 1.2 =
* Background Image Option

= 1.1 =
* Errors are now commented out. Look in the source to see what the problem is.
* Ability to add link to openweathermap for extended forecast

= 1.0.2 =
* Removed debugging code, sorry!

= 1.0.1 =
* Changed API endpoints

= 1.0 =
* Initial load of the plugin.