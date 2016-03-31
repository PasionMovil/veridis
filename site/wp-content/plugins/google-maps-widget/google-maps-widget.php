<?php
/*
Plugin Name: Google Maps Widget
Plugin URI: http://www.gmapswidget.com/
Description: Display a single-image super-fast loading Google map in a widget. A larger, full featured map is available as an image replacement or in a lightbox. Includes shortcode support and numerous options.
Author: Web factory Ltd
Version: 3.25
Author URI: http://www.webfactoryltd.com/
Text Domain: google-maps-widget
Domain Path: lang

  Copyright 2012 - 2016  Web factory Ltd  (email : gmw@webfactoryltd.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// this is an include only WP file
if (!defined('ABSPATH')) {
  die;
}


if (!class_exists('GMW')) :

define('GMW_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('GMW_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once GMW_PLUGIN_DIR . 'gmw-tracking.php';
require_once GMW_PLUGIN_DIR . 'gmw-widget.php';
require_once GMW_PLUGIN_DIR . 'gmw-map-styles.php';
require_once GMW_PLUGIN_DIR . 'gmw-export-import.php';
if (file_exists(GMW_PLUGIN_DIR . 'gmw-pro-license.php')) {
  require_once GMW_PLUGIN_DIR . 'gmw-pro-license.php';  
}


class GMW {
  static $version = '3.25';
  static $options = 'gmw_options';
  static $licensing_servers = array('http://license.gmapswidget.com/', 'http://license2.gmapswidget.com/');


  // hook everything up
  static function init() {
    if (is_admin()) {
      // check if minimal required WP version is present
      if (false === GMW::check_wp_version(3.8)) {
        return false;
      }

      // check a few variables
      GMW::maybe_upgrade();

      // aditional links in plugin description
      add_filter('plugin_action_links_' . basename(dirname(__FILE__)) . '/' . basename(__FILE__),
                 array('GMW', 'plugin_action_links'));
      add_filter('plugin_row_meta', array('GMW', 'plugin_meta_links'), 10, 2);

      // enqueue admin scripts
      add_action('admin_enqueue_scripts', array('GMW', 'admin_enqueue_scripts'));
      add_action('customize_controls_enqueue_scripts', array('GMW', 'admin_enqueue_scripts'));

      // JS dialog markup
      add_action('admin_footer', array('GMW', 'admin_dialogs_markup'));

      // register AJAX endpoints
      add_action('wp_ajax_gmw_activate', array('GMW', 'activate_license_key_ajax'));
      add_action('wp_ajax_gmw_test_api_key', array('GMW', 'test_api_key_ajax'));
      add_action('wp_ajax_gmw_get_trial', array('GMW', 'get_trial_ajax'));

      // custom admin actions
      add_action('admin_action_gmw_dismiss_notice', array('GMW', 'dismiss_notice'));
      add_action('admin_action_gmw_export_widgets', array('GMW_export_import', 'send_export_file'));

      // add options menu
      add_action('admin_menu', array('GMW', 'add_menus'));

      // settings registration
      add_action('admin_init', array('GMW', 'register_settings'));

      // display various notices
      add_action('current_screen', array('GMW', 'add_notices'));
      
    } else {
      // enqueue frontend scripts
      add_action('wp_enqueue_scripts', array('GMW', 'register_scripts'));
      add_action('wp_footer', array('GMW', 'dialogs_markup'));
    }

    // track plugin usage
    GMW_tracking::init();

    // add shortcode support
    GMW::add_shortcodes();
  } // init


  // some things have to be loaded earlier
  static function plugins_loaded() {
    load_plugin_textdomain('google-maps-widget', false, basename(dirname(__FILE__)) . '/lang');
    add_filter('cron_schedules', array('GMW_tracking', 'register_cron_intervals'));
  } // plugins_loaded


  // initialize widgets
  static function widgets_init() {
    $options = GMW::get_options();
    
    register_widget('GoogleMapsWidget');

    if (GMW::is_activated() && !$options['disable_sidebar']) {
      register_sidebar( array(
        'name' => __('Google Maps Widget PRO hidden sidebar', 'google-maps-widget'),
        'id' => 'google-maps-widget-hidden',
        'description' => __('Widgets in this area will never be shown anywhere in the theme. Area only helps you to build maps that are displayed with shortcodes.', 'google-maps-widget'),
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>',
      ));
    } // if activated
  } // widgets_init


  // all settings are saved in one option
  static function register_settings() {
    register_setting(GMW::$options, GMW::$options, array('GMW', 'sanitize_settings'));
  } // register_settings


  // sanitize settings on save
  static function sanitize_settings($values) {
    $new_values = array();
    $old_options = GMW::get_options();
                  
    // license_key_changed
    if (isset($_POST['submit-license'])) {
      if (empty($values['activation_code'])) {
        $new_values['license_type'] = '';
        $new_values['license_expires'] = '';
        $new_values['license_active'] = false;
        $new_values['activation_code'] = '';
      } else {
        $tmp = GMW::validate_activation_code($values['activation_code']);
        $new_values['activation_code'] = $values['activation_code'];
        if ($tmp['success']) {
          $new_values['license_type'] = $tmp['license_type'];
          $new_values['license_expires'] = $tmp['license_expires'];
          $new_values['license_active'] = $tmp['license_active'];
          if ($tmp['license_active']) {
            add_settings_error(GMW::$options, 'license_key', __('License key saved and activated!', 'google-maps-widget'), 'updated');
          } else {
            add_settings_error(GMW::$options, 'license_key', 'License not active. ' . $tmp['error'], 'error');
          }
        } else {
          add_settings_error(GMW::$options, 'license_key', 'Unable to contact licensing server. Please try again in a few moments.', 'error');
        }
      }
      $values = $new_values;
    } elseif (isset($_POST['submit'])) { // save settings
      foreach ($values as $key => $value) {
        switch ($key) {
          case 'api_key':
            $values[$key] = str_replace(' ', '', $value);
          break;
          case 'sc_map':
            $values[$key] = sanitize_title_with_dashes($value);
          break;
          case 'activation_code':
            $values[$key] = substr(trim($value), 0, 50);
          break;
          case 'track_ga':
          case 'include_jquery':
          case 'include_lightbox_css':
          case 'include_lightbox_js':
          case 'disable_tooltips':
          case 'disable_sidebar':
            $values[$key] = (int) $value;
          break;
        } // switch
      } // foreach

      if (GMW::is_activated()) {
        $values = GMW::check_var_isset($values, array('track_ga' => 0, 'include_jquery' => 0, 'include_lightbox_js' => '0', 'include_lightbox_css' => '0', 'disable_tooltips' => '0', 'disable_sidebar' => '0'));  
      }

      if (strlen($values['api_key']) < 30) {
        add_settings_error(GMW::$options, 'api_key', __('Google Maps API key is not valid. Access <a href="https://console.developers.google.com/project">Google Developers Console</a> to generate a key for free.', 'google-maps-widget'), 'error');
      }

      if (GMW::is_activated() && empty($values['sc_map'])) {
        $values['sc_map'] = 'gmw';
        add_settings_error(GMW::$options, 'api_key', __('Map Shortcode is not valid. Please enter a valid shortcode name, eg: <i>gmw</i>.', 'google-maps-widget'), 'error');
      }  
    } elseif (isset($_POST['submit-import'])) { // import widgets
      $import_data = GMW_export_import::validate_import_file();
      if (is_wp_error($import_data)) {
        add_settings_error(GMW::$options, 'import_widgets', $import_data->get_error_message(), 'error');
      } else {
        $results = GMW_export_import::process_import_file($import_data);
        add_settings_error(GMW::$options, 'import_widgets', __($results['total'] . ' widgets imported.', 'google-maps-widget'), 'updated');
      }
    }

    return array_merge($old_options, $values);
  } // sanitize_settings

  
  // return default options
  static function default_options() {
    $defaults = array('sc_map'               => 'gmw',
                      'api_key'              => '',
                      'track_ga'             => '0',
                      'include_jquery'       => '1',
                      'include_lightbox_js'  => '1',
                      'include_lightbox_css' => '1',
                      'disable_tooltips'     => '0',
                      'disable_sidebar'      => '0',
                      'activation_code'      => '',
                      'license_active'       => '',
                      'license_expires'      => '',
                      'license_type'         => ''
                     );

    return $defaults;
  } // default_settings


  // get plugin's options
  static function get_options() {
    $options = get_option(GMW::$options, array());

    if (!is_array($options)) {
      $options = array();
    }
    $options = array_merge(GMW::default_options(), $options);

    return $options;
  } // get_options


  // update and set one or more options
  static function set_options($new_options) {
    if (!is_array($new_options)) {
      return false;
    }

    $options = GMW::get_options();
    $options = array_merge($options, $new_options);

    update_option(GMW::$options, $options);

    return $options;
  } // set_options


  // add widgets link to plugins page
  static function plugin_action_links($links) {
    $settings_link = '<a href="' . admin_url('options-general.php?page=gmw_options') . '" title="' . __('Settings for Google Maps Widget', 'google-maps-widget') . '">' . __('Settings', 'google-maps-widget') . '</a>';
    $widgets_link = '<a href="' . admin_url('widgets.php') . '" title="' . __('Configure Google Maps Widget for your theme', 'google-maps-widget') . '">' . __('Widgets', 'google-maps-widget') . '</a>';

    array_unshift($links, $settings_link);
    array_unshift($links, $widgets_link);

    return $links;
  } // plugin_action_links


  // add links to plugin's description in plugins table
  static function plugin_meta_links($links, $file) {
    $documentation_link = '<a target="_blank" href="http://www.gmapswidget.com/documentation/" title="' . __('View Google Maps Widget documentation', 'google-maps-widget') . '">'. __('Documentation', 'google-maps-widget') . '</a>';
    if (GMW::is_activated()) {
      $support_link = '<a href="mailto:gmw@webfactoryltd.com?subject=GMW%20support" title="' . __('Problems? We are here to help!', 'google-maps-widget') . '">' . __('Support', 'google-maps-widget') . '</a>';
    } else {
      $support_link = '<a target="_blank" href="http://wordpress.org/support/plugin/google-maps-widget" title="' . __('Problems? We are here to help!', 'google-maps-widget') . '">' . __('Support', 'google-maps-widget') . '</a>';
    }
    $review_link = '<a target="_blank" href="http://wordpress.org/support/view/plugin-reviews/google-maps-widget" title="' . __('If you like it, please review the plugin', 'google-maps-widget') . '">' . __('Review the plugin', 'google-maps-widget') . '</a>';
    $activate_link = '<a href="' . esc_url(admin_url('options-general.php?page=gmw_options&gmw_open_promo_dialog')) . '">' . __('Activate PRO features', 'google-maps-widget') . '</a>';

    if ($file == plugin_basename(__FILE__)) {
      $links[] = $documentation_link;
      $links[] = $support_link;
      $links[] = $review_link;
      if (!GMW::is_activated()) {
        $links[] = $activate_link;
      }
    }

    return $links;
  } // plugin_meta_links


  // check if user has the minimal WP version required by GMW
  static function check_wp_version($min_version) {
    if (!version_compare(get_bloginfo('version'), $min_version,  '>=')) {
        add_action('admin_notices', array('GMW', 'notice_min_version_error'));
        return false;
    }

    return true;
  } // check_wp_version


  // display error message if WP version is too low
  static function notice_min_version_error() {
    echo '<div class="error"><p>' . sprintf(__('Google Maps Widget <b>requires WordPress version 3.8</b> or higher to function properly. You are using WordPress version %s. Please <a href="%s">update it</a>.', 'google-maps-widget'), get_bloginfo('version'), admin_url('update-core.php')) . '</p></div>';
  } // notice_min_version_error


  // get users maps api key or one of temporary plugin ones
  static function get_api_key($type = 'static') {
    $options = GMW::get_options();
    $default_api_keys = array('AIzaSyB35Ukt8bKRSY-mII-1Q7DmVx8LnlpmND0',
                              'AIzaSyCsY7jqJLTFBtRsGLcb-JQOhW6910qh1ts',
                              'AIzaSyDawXzxQurkSkkTxzzW-WRYKmia_Y28S1Q',
                              'AIzaSyArcXkQ15FoOTS2Z7El2SJHDIlTMW7Rxxg',
                              'AIzaSyBVJ4JR63d1JIL8L6b_emat-_jXMcHveR0',
                              'AIzaSyDOobziwX_9-4JuAgqIlTUZgXAss7zIIEM');

    if ($type == 'static') {
      return $options['api_key'];
    } elseif ($type == 'embed') {
      if (!empty($options['api_key'])) {
        return $options['api_key'];
      } else {
        shuffle($default_api_keys);
        return $default_api_keys[0];
      }
    }

    return false;
  } // get_api_key
  

  // checkes if API key is active for all needed API services  
  static function test_api_key_ajax() {
    check_ajax_referer('gmw_test_api_key');
    
    $msg = '';
    $error = false;
    $api_key = trim(@$_GET['api_key']);

    $test = wp_remote_get(esc_url_raw('https://maps.googleapis.com/maps/api/staticmap?center=new+york+usa&size=100x100&key=' . $api_key));
    if (wp_remote_retrieve_response_message($test) == 'OK') {
      $msg .= 'Google Static Maps API test - OK' . "\n";
    } else {
      $msg .= 'Google Static Maps API test - FAILED' . "\n";
      $error = true;
    }
    
    $test = wp_remote_get(esc_url_raw('https://www.google.com/maps/embed/v1/place?q=new+york+usa&key=' . $api_key));
    if (wp_remote_retrieve_response_message($test) == 'OK') {
      $msg .= 'Google Embed Maps API test - OK' . "\n\n";
    } else {
      $msg .= 'Google Embed Maps API test - FAILED' . "\n\n";
      $error = true;
    }
    
    if ($error) {
      $msg .= 'Something is not right. Please read the instruction below on how to generate the API key and double-check everything.';
    } else {
      $msg = 'The API key is OK! Don\'t forget to save it ;)';
    }

    wp_send_json_success($msg);    
  } // test_api_key

  
  // build a complete URL for the iframe map
  static function build_lightbox_url($widget) {
    $map_params = array();

    if ($widget['lightbox_mode'] == 'place') {
      $map_params['q'] = $widget['address'];
      $map_params['attribution_source'] = get_bloginfo('name')? get_bloginfo('name'): 'Google Maps Widget';
      $map_params['attribution_web_url'] = get_home_url();
      $map_params['attribution_ios_deep_link_id'] = 'comgooglemaps://?daddr=' . $widget['address'];
      $map_params['maptype'] = $widget['lightbox_map_type'];
      $map_params['zoom'] = $widget['lightbox_zoom'];
    } elseif ($widget['lightbox_mode'] == 'directions') {
      $map_params['origin'] = $widget['lightbox_origin'];
      $map_params['destination'] = $widget['address'];
      $map_params['maptype'] = $widget['lightbox_map_type'];
      if (!empty($widget['lightbox_unit']) && $widget['lightbox_unit'] != 'auto') {
        $map_params['units'] = $widget['lightbox_unit'];
      }
      if ($widget['lightbox_zoom'] != 'auto') {
        $map_params['zoom'] = $widget['lightbox_zoom'];
      }
    } elseif ($widget['lightbox_mode'] == 'search') {
      if (($coordinates = GMW::get_coordinates($widget['address'])) !== false) {
        $map_params['center'] = $coordinates['lat'] . ',' . $coordinates['lng'];  
      }
      $map_params['q'] = $widget['lightbox_search'];
      $map_params['maptype'] = $widget['lightbox_map_type'];
      if ($widget['lightbox_zoom'] != 'auto') {
        $map_params['zoom'] = $widget['lightbox_zoom'];
      }
    } elseif ($widget['lightbox_mode'] == 'view') {
      if (($coordinates = GMW::get_coordinates($widget['address'])) !== false) {
        $map_params['center'] = $coordinates['lat'] . ',' . $coordinates['lng'];  
      }
      $map_params['maptype'] = $widget['lightbox_map_type'];
      if ($widget['lightbox_zoom'] != 'auto') {
        $map_params['zoom'] = $widget['lightbox_zoom'];
      }
    } elseif ($widget['lightbox_mode'] == 'streetview') {
      if (($coordinates = GMW::get_coordinates($widget['address'])) !== false) {
        $map_params['location'] = $coordinates['lat'] . ',' . $coordinates['lng'];  
      }
      $map_params['heading'] = $widget['lightbox_heading'];
      $map_params['pitch'] = $widget['lightbox_pitch'];
    }

    if ($widget['lightbox_lang'] != 'auto') {
      $map_params['language'] = $widget['lightbox_lang'];
    }
    $map_params['key'] = GMW::get_api_key('embed');

    $map_url = 'https://www.google.com/maps/embed/v1/' . $widget['lightbox_mode'] . '?';
    $map_url .= http_build_query($map_params, null, '&amp;');

    return $map_url;
  } // build_lightbox_url
  
  
  // fetch coordinates based on the address
  static function get_coordinates($address, $force_refresh = false) {
    $address_hash = md5('gmw_' . $address);

    if ($force_refresh || ($data = get_transient($address_hash)) === false) {
      $url = 'https://maps.googleapis.com/maps/api/geocode/xml?address=' . urlencode($address) . '&sensor=false';
      $result = wp_remote_get(esc_url_raw($url), array('sslverify' => false, 'timeout' => 5));

      if (!is_wp_error($result) && $result['response']['code'] == 200) {
        $data = new SimpleXMLElement($result['body']);

        if ($data->status == 'OK') {
          $cache_value['lat']     = (string) $data->result->geometry->location->lat;
          $cache_value['lng']     = (string) $data->result->geometry->location->lng;
          $cache_value['address'] = (string) $data->result->formatted_address;

          // cache coordinates for 2 months
          set_transient($address_hash, $cache_value, DAY_IN_SECONDS * 60);
          $data = $cache_value;
          $data['cached'] = false;
        } elseif (!$data->status) {
          return false;
        } else {
          return false;
        }
      } else {
         return false;
      }
    } else {
       // data is cached
       $data['cached'] = true;
    }

    return $data;
  } // get_coordinates
  

  // print dialogs markup in footer
  static function dialogs_markup() {
       $out = '';
       $js_vars = array();
       $options = GMW::get_options();

       if (empty(GoogleMapsWidget::$widgets)) {
         return;
       }

       // add CSS and JS in footer
       $js_vars['track_ga'] = $options['track_ga'];
       if ($options['include_lightbox_css']) {
         $js_vars['colorbox_css'] = plugins_url('/css/gmw.css', __FILE__) . '?ver=' . GMW::$version;
       } else {
         $js_vars['colorbox_css'] = false;
       }
       if ($options['include_lightbox_js']) {
         wp_enqueue_script('gmw-colorbox');
       }
       wp_enqueue_script('gmw');
       wp_localize_script('gmw', 'gmw_data', $js_vars);

       foreach (GoogleMapsWidget::$widgets as $widget) {
         $map_url = GMW::build_lightbox_url($widget);

         if ($widget['lightbox_fullscreen']) {
           $widget['lightbox_width'] = '100%';
           $widget['lightbox_height'] = '100%';
         }

         $out .= '<div class="gmw-dialog" style="display: none;" data-map-height="' . $widget['lightbox_height'] . '" 
                  data-map-width="' . $widget['lightbox_width'] . '" data-thumb-height="' . $widget['thumb_height'] . '" 
                  data-thumb-width="' . $widget['thumb_width'] . '" data-map-skin="' . $widget['lightbox_skin'] . '" 
                  data-map-iframe-url="' . $map_url . '" id="gmw-dialog-' . $widget['id'] . '" title="' . esc_attr($widget['title']) . '" 
                  data-close-button="' . (int) in_array('close_button', $widget['lightbox_feature']) . '" 
                  data-show-title="' . (int) in_array('title', $widget['lightbox_feature']) . '" 
                  data-close-overlay="' . (int) in_array('overlay_close', $widget['lightbox_feature']) . '" 
                  data-close-esc="' . (int) in_array('esc_close', $widget['lightbox_feature']) . '">';
         if ($widget['lightbox_header']) {
           $tmp = str_ireplace(array('{address}'), array($widget['address']), $widget['lightbox_header']);
           $out .= '<div class="gmw-header">' . wpautop(do_shortcode($tmp)) . '</div>';
         }
         $out .= '<div class="gmw-map"></div>';
         if ($widget['lightbox_footer']) {
           $tmp = str_ireplace(array('{address}'), array($widget['address']), $widget['lightbox_footer']);
           $out .= '<div class="gmw-footer">' . wpautop(do_shortcode($tmp)) . '</div>';
         }
         $out .= "</div>\n";
       } // foreach $widgets

       echo $out;
  } // dialogs_markup


  // add plugin menus
  static function add_menus() {
    $title = __('Google Maps Widget', 'google-maps-widget');
    if (GMW::is_activated()) {
       $title = '<span style="font-size: 11px;">' . $title . ' <span style="color: #d54e21;">PRO</span></span>';
    }

    add_options_page($title, $title, 'manage_options', GMW::$options, array('GMW', 'settings_screen'));
  } // add_menus


  // check availability and register shortcode
  static function add_shortcodes() {
    if (!GMW::is_activated()) {
      return;
    }
    
    global $shortcode_tags;
    $options = GMW::get_options();

    if (isset($shortcode_tags[$options['sc_map']])) {
      add_action('admin_notices', array('GMW', 'notice_sc_conflict_error'));
    } else {
      add_shortcode($options['sc_map'], array('GMW', 'do_shortcode'));
    }
  } // add_shortcodes


  // display notice if shortcode name is already taken
  static function notice_sc_conflict_error() {
    $options = GMW::get_options();

    echo '<div class="error"><p><strong>' . __('Google Maps Widget shortcode is not active!', 'google-maps-widget') . '</strong>' . sprintf(__(' Shortcode <i>[%s]</i> is already in use by another plugin or theme. Please deactivate that theme or plugin, or <a href="%s">change</a> the GMW shortcode.', 'google-maps-widget'), $options['sc_map'], admin_url('options-general.php?page=gmw_options')) . '</p></div>';
  } // notice_sc_conflict_error


  // handle dismiss button for notices
  static function dismiss_notice() {
    if (empty($_GET['notice'])) {
      wp_redirect(admin_url());
      exit;
    }
    
    if ($_GET['notice'] == 'upgrade') {
      GMW::set_options(array('dismiss_notice_upgrade2' => true));
    }
    if ($_GET['notice'] == 'rate') {
      GMW::set_options(array('dismiss_notice_rate' => true));
    }
    if ($_GET['notice'] == 'api_key') {
      GMW::set_options(array('dismiss_notice_api_key' => true));
    }
    if ($_GET['notice'] == 'license_expires') {
      GMW::set_options(array('dismiss_notice_license_expires' => true));
    }

    if (!empty($_GET['redirect'])) {
      wp_redirect($_GET['redirect']);
    } else {
      wp_redirect(admin_url());
    }

    exit;
  } // dismiss_notice


  // controls which notices are shown
  static function add_notices() {
    $options = GMW::get_options();
    $notice = false;

    // license expire notice is always shown
    if ((!$notice && GMW::is_activated() && empty($options['dismiss_notice_license_expires']) && 
        (strtotime($options['license_expires']) - time() < DAY_IN_SECONDS * 3)) ||
        (!$notice && empty($options['dismiss_notice_license_expires']) &&
        $options['license_expires'] < date('Y-m-d') && $options['license_active'] == true)) {
      add_action('admin_notices', array('GMW', 'notice_license_expires'));
      $notice = true;
    } elseif ((!$notice && GMW::is_activated() && GMW::is_plugin_admin_page('settings') && 
        (strtotime($options['license_expires']) - time() < DAY_IN_SECONDS * 3)) ||
        (!$notice && GMW::is_plugin_admin_page('settings') && 
        $options['license_expires'] < date('Y-m-d') && $options['license_active'] == true)) {
      add_action('admin_notices', array('GMW', 'notice_license_expires'));
    } // show license expire notice
    
    // API key notification is shown if there are active widgets and no key
    if (!$notice && empty($options['dismiss_notice_api_key']) && 
        !GMW::get_api_key('static') && GMW_tracking::count_active_widgets() > 0) {
      add_action('admin_notices', array('GMW', 'notice_api_key'));
      $notice = true;
    } // show api key notice
    
    // upgrade notice is shown after one day; temporarily disabled
    if (!$notice && empty($options['dismiss_notice_upgrade2']) && !GMW::is_activated() &&
       (current_time('timestamp') - $options['first_install']) > (DAY_IN_SECONDS * 0)) {
      add_action('admin_notices', array('GMW', 'notice_upgrade'));
      $notice = true;
    } // show upgrade notice

    // rating notification is shown after 5 days if you have active widgets
    if (!$notice && empty($options['dismiss_notice_rate']) &&
        GMW_tracking::count_active_widgets() > 0 &&
        (current_time('timestamp') - $options['first_install']) > (DAY_IN_SECONDS * 5)) {
      add_action('admin_notices', array('GMW', 'notice_rate_plugin'));
      $notice = true;
    } // show rate notice

    // tracking notification is shown after 15 days and only to non PRO users
    if (!$notice && !isset($options['allow_tracking']) && !GMW::is_activated() &&
        ((current_time('timestamp') - $options['first_install']) > (DAY_IN_SECONDS * 15))) {
      add_action('admin_notices', array('GMW_tracking', 'tracking_notice'));
      $notice = true;
    } // show tracking notice
  } // add_notices


  // display message if license will expire in 14 days or less
  static function notice_license_expires() {
    $options = GMW::get_options();
    
    $buy_url = admin_url('options-general.php?page=gmw_options&gmw_open_promo_dialog');
    $dismiss_url = add_query_arg(array('action' => 'gmw_dismiss_notice', 'notice' => 'license_expires', 'redirect' => urlencode($_SERVER['REQUEST_URI'])), admin_url('admin.php'));
    
    $days = strtotime($options['license_expires'] . date(' G:i:m')) - time();
    $days = round($days / DAY_IN_SECONDS);
    
    echo '<div id="gmw_license_expires_notice" class="error notice"><p>';
    echo 'Your <b>Google Maps Widget</b> <b style="color: #d54e21;">PRO</b> trial ';
    if ($options['license_expires'] == date('Y-m-d')) {
      echo '<b>expires today</b>!';
      echo ' A special <b>25% discount coupon</b> is valid only till trial lasts. Don\'t be late, no need to pay the full price.<br>';
      echo ' All <b style="color: #d54e21;">PRO</b> features will be disabled once the trial expires.';
      $button_text = 'Get PRO with a 25% discount - offer ends today';
    } elseif (date('Y-m-d', time() + DAY_IN_SECONDS) == $options['license_expires']) {
      echo '<b>expires tomorrow</b>!';
      echo ' A special <b>25% discount coupon</b> is valid only till trial lasts. Don\'t be late, no need to pay the full price.<br>';
      echo ' All <b style="color: #d54e21;">PRO</b> features will be disabled once the trial expires.';
      $button_text = 'Get PRO with a 25% discount - offer ends tomorrow';
    } elseif ($days > 1) {
      echo '<b>expires in ' . $days . ' days</b>!';
      echo ' A special <b>25% discount coupon</b> is valid only till trial lasts. Don\'t be late, no need to pay the full price.<br>';
      echo ' All <b style="color: #d54e21;">PRO</b> features will be disabled once the trial expires.';
      $button_text = 'Get PRO with a 25% discount - offer ends in ' . $days . ' days';
    } else {
      echo '<b>has expired</b>!';
      echo ' All <b style="color: #d54e21;">PRO</b> features have been disabled.';  
      $button_text = 'Get PRO now';
    }

    echo '<br><a href="' . esc_url($buy_url) . '" style="vertical-align: baseline; margin-top: 15px;" class="button-primary">' . $button_text . '</a>';
    if (!GMW::is_plugin_admin_page('settings')) {
      echo '&nbsp;&nbsp;<a href="' . esc_url($dismiss_url) . '" class="">' . __('I will pay the full price ($29) later', 'google-maps-widget') . '</a>';  
    }
    echo '</p></div>';
  } // notice_license_expires
  
  
  // display message to get pro features for GMW
  static function notice_upgrade() {
    $activate_url = admin_url('options-general.php?page=gmw_options&gmw_open_promo_dialog');
    $dismiss_url = add_query_arg(array('action' => 'gmw_dismiss_notice', 'notice' => 'upgrade', 'redirect' => urlencode($_SERVER['REQUEST_URI'])), admin_url('admin.php'));

    echo '<div id="gmw_activate_notice" class="updated notice"><p>' . __('<b>Google Maps Widget</b> has more than 50 <b style="color: #d54e21;">PRO</b> features. Our support is fast & friendly, and licenses valid for an unlimited number of sites.', 'google-maps-widget');

    echo '<br><a href="' . esc_url($activate_url) . '" style="vertical-align: baseline; margin-top: 15px;" class="button-primary">' . __('Activate PRO features', 'google-maps-widget') . '</a>';
    echo '&nbsp;&nbsp;<a href="' . esc_url($dismiss_url) . '" class="">' . __('I\'m not interested', 'google-maps-widget') . '</a>';
    echo '</p></div>';
  } // notice_activate_extra_features


  // display message to rate plugin
  static function notice_rate_plugin() {
    $rate_url = 'https://wordpress.org/support/view/plugin-reviews/google-maps-widget?rate=5#postform';
    $dismiss_url = add_query_arg(array('action' => 'gmw_dismiss_notice', 'notice' => 'rate', 'redirect' => urlencode($_SERVER['REQUEST_URI'])), admin_url('admin.php'));

    echo '<div id="gmw_rate_notice" class="updated notice"><p>' . __('Hi! We saw you\'ve been using <b>Google Maps Widget</b> for a few days and wanted to ask for your help to make the plugin even better.<br>We just need a minute of your time to rate the plugin. Thank you!', 'google-maps-widget');

    echo '<br><a target="_blank" href="' . esc_url($rate_url) . '" style="vertical-align: baseline; margin-top: 15px;" class="button-primary">' . __('Help us out &amp; rate the plugin', 'google-maps-widget') . '</a>';
    echo '&nbsp;&nbsp;<a href="' . esc_url($dismiss_url) . '">' . __('I already rated the plugin', 'google-maps-widget') . '</a>';
    echo '</p></div>';
  } // notice_rate_plugin
  
  
  // display message to enter API key
  static function notice_api_key() {
    if (GMW::is_plugin_admin_page('settings')) {
      echo '<div id="gmw_api_key_notice" class="error notice"><p>';
      echo '<b>Important!</b> New Google rules dictate that you have to register for a <b>free Google Maps API key</b>. ';
      echo 'Please follow the instructions below to obtain and enter the key. If you don\'t configure the API key the maps will stop working.';  
      echo '</p></div>';      
    } else {
      $dismiss_url = add_query_arg(array('action' => 'gmw_dismiss_notice', 'notice' => 'api_key', 'redirect' => urlencode($_SERVER['REQUEST_URI'])), admin_url('admin.php'));
      
      echo '<div id="gmw_api_key_notice" class="error notice"><p>';
      echo '<b>Important!</b> New Google rules dictate that you have to register for a <b>free Google Maps API key</b>. ';
      echo 'Please open Google Maps Widget <a href="' . admin_url('options-general.php?page=gmw_options') . '" title="Google Maps Widget settings">settings</a> and follow instructions on how to obtain it. If you don\'t configure the API key the maps will stop working.';
      echo '<br><a href="' . admin_url('options-general.php?page=gmw_options') . '" style="vertical-align: baseline; margin-top: 15px;" class="button-primary">' . __('Configure the API key', 'google-maps-widget') . '</a>';
      echo '&nbsp;&nbsp;<a href="' . esc_url($dismiss_url) . '">' . __('Dismiss notice', 'google-maps-widget') . '</a>';
      echo '</p></div>';  
    }
  } // notice_api_key


  // register frontend scripts and styles
  static function register_scripts() {
    $options = GMW::get_options();

    wp_register_style('gmw', plugins_url('/css/gmw.css', __FILE__), array(), GMW::$version);

    if ($options['include_jquery']) {
      wp_register_script('gmw-colorbox', plugins_url('/js/jquery.colorbox.min.js', __FILE__), array('jquery'), GMW::$version, true);
      wp_register_script('gmw', plugins_url('/js/gmw.js', __FILE__), array('jquery'), GMW::$version, true);
    } else {
      wp_register_script('gmw-colorbox', plugins_url('/js/jquery.colorbox.min.js', __FILE__), array(), GMW::$version, true);
      wp_register_script('gmw', plugins_url('/js/gmw.js', __FILE__), array(), GMW::$version, true);
    }
  } // register_scripts


  // enqueue CSS and JS scripts in admin
  static function admin_enqueue_scripts() {
    global $wp_customize;
    $options = GMW::get_options();

    if (GMW::is_plugin_admin_page('widgets') || GMW::is_plugin_admin_page('settings') || !empty($wp_customize)) {
      wp_enqueue_script('jquery-ui-tabs');
      wp_enqueue_script('jquery-ui-dialog');
      wp_enqueue_script('wp-color-picker');
      wp_enqueue_script('wp-pointer');
      wp_enqueue_script('gmw-cookie', plugins_url('js/jquery.cookie.js', __FILE__), array('jquery'), GMW::$version, true);
      if (GMW::is_activated()) {
        wp_enqueue_script('gmw-gmap', '//maps.google.com/maps/api/js', array(), GMW::$version, true);  
      }
      wp_enqueue_script('gmw-select2', plugins_url('js/select2.min.js', __FILE__), array('jquery'), GMW::$version, true);
      wp_enqueue_script('gmw-admin', plugins_url('js/gmw-admin.js', __FILE__), array('jquery'), GMW::$version, true);

      wp_enqueue_style('wp-jquery-ui-dialog');
      wp_enqueue_style('wp-color-picker');
      wp_enqueue_style('wp-pointer');
      wp_enqueue_style('gmw-select2', plugins_url('css/select2.min.css', __FILE__), array(), GMW::$version);
      wp_enqueue_style('gmw-admin', plugins_url('css/gmw-admin.css', __FILE__), array(), GMW::$version);

      $js_localize = array('activate_ok' => __('Superb! PRO features are active ;)', 'google-maps-widget'),
                           'dialog_map_title' => __('Pick an address by drag &amp; dropping the pin', 'google-maps-widget'),
                           'undocumented_error' => __('An undocumented error has occured. Please refresh the page and try again.', 'google-maps-widget'),
                           'bad_api_key' => __('The API key format does not look right. Please double-check it.', 'google-maps-widget'),
                           'dialog_promo_title' => '<img alt="' . __('Google Maps Widget PRO', 'google-maps-widget') . '" title="' . __('Google Maps Widget PRO', 'google-maps-widget') . '" src="' . plugins_url('/images/gmw-logo-pro-dialog.png', __FILE__) . '">',
                           'dialog_pins_title' => __('Pins Library', 'google-maps-widget'),
                           'plugin_name' => GMW::is_activated()? __('Google Maps Widget PRO', 'google-maps-widget'): __('Google Maps Widget', 'google-maps-widget'),
                           'id_base' => 'googlemapswidget',
                           'map_picker_not_active' => __('Drag&drop address picking interface is a PRO feature. Interested in switching to PRO?', 'google-maps-widget'),
                           'map' => false,
                           'marker' => false,
                           'trial_ok' => __('Your trial has been activated. Enjoy all PRO features for 7 days.' . "\n" . 'Check your email for a DISCOUNT coupon ;)', 'google-maps-widget'),
                           'settings_url' => admin_url('options-general.php?page=gmw_options'),
                           'pins_library' => plugins_url('/images/pins/', __FILE__),
                           'disable_tooltips' => $options['disable_tooltips'],
                           'is_activated' => GMW::is_activated(),
                           'nonce_test_api_key' => wp_create_nonce('gmw_test_api_key'),
                           'nonce_get_trial' => wp_create_nonce('gmw_get_trial'),
                           'nonce_activate_license_key' => wp_create_nonce('gmw_activate_license_key'));
      wp_localize_script('gmw-admin', 'gmw', $js_localize);
    } // if
  } // admin_enqueue_scripts


  // check if plugin's admin page is shown
  static function is_plugin_admin_page($page = 'widgets') {
    $current_screen = get_current_screen();

    if ($page == 'widgets' && $current_screen->id == 'widgets') {
      return true;
    }

    if ($page == 'settings' && $current_screen->id == 'settings_page_gmw_options') {
      return true;
    }

    if ($page == 'plugins' && $current_screen->id == 'plugins') {
      return true;
    }

    return false;
  } // is_plugin_admin_page


  // check if license key is valid and not expired
  static function is_activated($license_type = false) {
    $options = GMW::get_options();
    
    if (isset($options['license_active']) && $options['license_active'] === true && 
        isset($options['license_expires']) && $options['license_expires'] >= date('Y-m-d')) {
          
      if (mt_rand(0, 1000) > 998 && is_admin()) {
        $tmp = GMW::validate_activation_code($options['activation_code']);
        if ($tmp['success']) {
          $update['license_type'] = $tmp['license_type'];
          $update['license_expires'] = $tmp['license_expires'];
          $update['license_active'] = $tmp['license_active'];
          GMW::set_options($update);
        }  
      } // random license revalidation    
      
      // check for specific license type?
      if (!empty($license_type)) {
        if (strtolower(trim($license_type)) == strtolower($options['license_type'])) {
          return true;
        } else {
          return false;
        }
      } // check specific license type
      
      return true;
    } else {
      return false;
    }
  } // is_activated


  // echo markup for promo dialog; only on widgets page
  static function admin_dialogs_markup() {
    $out = '';
    $options = GMW::get_options();
    $promo_delta = 3*60*60;
    
    if ((GMW::is_plugin_admin_page('widgets') || GMW::is_plugin_admin_page('settings'))) {
      $current_user = wp_get_current_user();
      if (empty($current_user->user_firstname)) {
        $name = $current_user->display_name;
      } else {
        $name = $current_user->user_firstname;
      }
    
      $out .= '<div id="gmw_promo_dialog" style="display: none;">';
      
      $out .= '<div id="gmw_dialog_intro" class="gmw_promo_dialog_screen">
               <div class="content">
                  <div class="header"><p><a href="#" class="gmw_goto_pro">Learn more</a> about <span class="gmw-pro">PRO</span> features.</p>';
      if (current_time('timestamp') - $options['first_install'] < $promo_delta) {
        $time = date(get_option('time_format'), $options['first_install'] + $promo_delta);
        $out .= '<div class="gmw-discount">We\'ve prepared a special <b>25% welcoming discount</b> for you available <b>only until ' . $time . '</b>. Discount has been applied on the unlimited license. Be quick &amp; use the buy button below.</div>';
      } elseif ($options['license_expires'] >= date('Y-m-d') && $options['license_type'] == 'trial') {
        $out .= '<div class="gmw-discount">We\'ve prepared a special <b>25% trial discount</b> for you available <b>only while the trial is active</b>. Discount has been applied on the unlimited license. Be quick &amp; use the buy button below.</div>';
      }
      $out .= '</div>'; // header

      $out .= '<div class="gmw-promo-box gmw-promo-box-lifetime gmw_goto_activation gmw-promo-box-hover">
               <div class="gmw-promo-icon"><img src="' . GMW_PLUGIN_URL . '/images/icon-unlimited.png" alt="Unlimited Lifetime License" title="Unlimited Lifetime License"></div>
               <div class="gmw-promo-description"><h3>Unlimited Lifetime License</h3><br>
               <span>Unlimited sites + lifetime support &amp; upgrades</span></div>';
      if (current_time('timestamp') - $options['first_install'] < $promo_delta) {
        $out .= '<div class="gmw-promo-button gmw-promo-button-extra"><a href="http://www.gmapswidget.com/buy/?p=pro-welcome&r=welcome-GMW+v' . GMW::$version . '" data-noprevent="1" target="_blank">only $19</a><span>discount: 25%</span></div>';
      } elseif ($options['license_expires'] >= date('Y-m-d') && $options['license_type'] == 'trial') {
        $out .= '<div class="gmw-promo-button gmw-promo-button-extra"><a href="http://www.gmapswidget.com/buy/?p=pro-trial&r=welcome-GMW+v' . GMW::$version . '" data-noprevent="1" target="_blank">only $19</a><span>discount: 25%</span></div>';
      } else {
        $out .= '<div class="gmw-promo-button"><a href="http://www.gmapswidget.com/buy/?p=pro-unlimited&r=GMW+v' . GMW::$version . '" data-noprevent="1" target="_blank">BUY $25</a></div>';
      }
      $out .= '</div>';
      $out .= '<div class="gmw-promo-box gmw-promo-box-yearly gmw_goto_activation">
               <div class="gmw-promo-icon"><img src="' . GMW_PLUGIN_URL . '/images/icon-yearly.png" alt="Yearly License" title="Yearly License"></div>
               <div class="gmw-promo-description"><h3>1 Year License</h3><br>
               <span>Unlimited sites + 1 year of support &amp; upgrades</span></div>
               <div class="gmw-promo-button"><a href="http://www.gmapswidget.com/buy/?p=yearly&r=GMW+v' . GMW::$version . '" data-noprevent="1" target="_blank">$11 /year</a></div>
               </div>';
      $out .= '<div class="gmw-promo-box gmw-promo-box-trial gmw_goto_trial">
               <div class="gmw-promo-icon"><img src="' . GMW_PLUGIN_URL . '/images/icon-trial.png" alt="7 Days Free Trial License" title="7 Days Free Trial License"></div>
               <div class="gmw-promo-description"><h3>7 Days Free Trial</h3><br>
               <span>Still on the fence? Test PRO for free.</span></div>
               <div class="gmw-promo-button"><a href="#">Start</a></div>
               </div>';
      $out .= '<p class="gmw-footer-intro">Already have a license key? <a href="#" class="gmw_goto_activation">Enter it here</a></p>';
      $out .= '</div></div>'; // dialog intro
      
      $out .= '<div id="gmw_dialog_activate" style="display: none;" class="gmw_promo_dialog_screen">
                 <div class="content">';
      $out .= '<p class="input_row">
                 <input type="text" id="gmw_code" name="gmw_code" placeholder="Please enter the license key">
                 <span style="display: none;" class="error gmw_code">Unable to verify license key. Unknown error.</span></p>
                 <p class="center">
                   <a href="#" class="button button-primary" id="gmw_activate">Activate PRO features</a>
                 </p>
                 <p class="center">If you don\'t have a license key - <a href="#" class="gmw_goto_intro">Get it now</a></p>
               </div>';
      $out .= '<div class="footer">
                 <ul class="gmw-faq-ul">
                   <li>Having problems paying or you misplaced your key? <a href="mailto:gmw@webfactoryltd.com?subject=Activation%20key%20problem">Email us</a></li>
                   <li>Key not working? Our <a href="mailto:gmw@webfactoryltd.com?subject=Activation%20key%20problem">support</a> is here to help</li>
                 </ul>
               </div>';
      $out .= '</div>'; // activate screen
      
      $out .= '<div id="gmw_dialog_pro_features" style="display: none;" class="gmw_promo_dialog_screen">
                 <div class="content">';
      $out .= '<h4>See how <span class="gmw-pro-red">PRO</span> features can make your life easier!</h4>';
      $out .= '<ul class="list-left">';
      $out .= '<li>11 thumbnail map skins</li>
               <li>1000+ thumbnail map pins</li>
               <li>4 extra map image formats for even faster loading</li>
               <li>replace thumb with interactive map feature</li>
               <li>extra hidden sidebar for easier shortcode handling</li>
               <li>custom map language option</li>
               <li>4 map modes; directions, view, street & streetview</li>
               <li>fully customizable pin options for thumbnail map</li>
               <li>Advanced cache &amp; fastest loading times</li>
               <li>JS &amp; CSS optimization options</li>
               <li>Continuous updates &amp; new features</li>';
      $out .= '</ul>';
      $out .= '<ul class="list-right">';
      $out .= '<li>3 additional map link types</li>
               <li>fullscreen lightbox mode</li>
               <li>extra lightbox features</li>
               <li>19 lightbox skins</li>
               <li>full shortcode support</li>
               <li>Clone widget feature</li>
               <li>export & import tools</li>
               <li>Google Analytics integration</li>
               <li>no ads</li>
               <li>no promo emails</li>
               <li>premium email support</li>';
      $out .= '</ul>';
      $out .= '  </div>';
      $out .= '<div class="footer">';
      $out .= '<p class="center"><a href="#" class="button-secondary gmw_goto_intro">Go PRO now</a> <a href="#" class="button-secondary gmw_goto_trial">Start a free trial</a><br>
               Or <a href="#" class="gmw_goto_activation">enter the license key</a> if you already have it.</p>';
      $out .= '</div>';
      $out .= '</div>'; // pro features screen
      
      $out .= '<div id="gmw_dialog_trial" style="display: none;" class="gmw_promo_dialog_screen">
             <div class="content">
             <h3>Fill out the form and get your free trial started <b>INSTANTLY</b>!</h3>';
      $out .= '<p class="input_row">
                 <input value="' . $name . '" type="text" id="gmw_name" name="gmw_name" placeholder="Your name">
                 <span class="error name" style="display: none;">Please enter your name.</span>
               </p>';
      $out .= '<p class="input_row">
                 <input value="' . $current_user->user_email . '" type="text" name="gmw_email" id="gmw_email" placeholder="Your email address" required="required">
                 <span style="display: none;" class="error email">Please double check your email address.</span>
               </p>';
      $out .= '<p class="center">
                 <a id="gmw_start_trial" href="#" class="button button-primary">Start a 7 days free trial</a></p>
                 <p class="center">Already have a license key? <a href="#" class="gmw_goto_activation">Enter it here</a></p>
               </div>';
      $out .= '<div class="footer">
                    <ul class="gmw-faq-ul">
                      <li>Please check your email for a special <b>discount code</b></li>
                      <li>We\'ll never share your email address</li>
                      <li>We hate spam too, so we never send it</li>
                    </ul>
                  </div>';
      $out .= '</div>'; // trial screen
      
      $out .= '</div>'; // dialog
    } // promo dialog
    
    // address picker and pins dialog
    if (GMW::is_plugin_admin_page('widgets') && GMW::is_activated()) {
      $out .= '<div id="gmw_map_dialog" style="display: none;">';
      $out .= '<div id="gmw_map_canvas"></div><hr>';
      $out .= '<div id="gmw_map_dialog_footer">';
      
      // current coordinates
      $out .= '<div class="gmw_dialog_current_coordinates">';
        $out .= 'Current coordinates: <input type="text" id="gmw_map_pin_coordinates" class="regular-text"> <a href="#" class="button-secondary gmw-move-pin" data-location-holder="gmw_map_pin_coordinates">Go</a><br>';
        $out .= '<a href="#" class="button-secondary gmw_close_save_map_dialog" data-location-holder="gmw_map_pin_coordinates">Use selected coordinates</a>';
      $out .= '</div>';
      
      // closest matching address
      $out .= '<div class="gmw_closest_matching_address">';
        $out .= 'Closest matching address: <input type="text" id="gmw_map_pin_address" class="regular-text"> <a href="#" class="button-secondary gmw-move-pin" data-location-holder="gmw_map_pin_address">Go</a><br>';
        $out .= '<a href="#" class="button-primary gmw_close_save_map_dialog" data-location-holder="gmw_map_pin_address">Use selected address</a>';
      $out .= '</div>';
      
      $out .= '</div>'; // footer
      $out .= '</div>'; // dialog
      
      // pins
      $out .= '<div id="gmw_pins_dialog" style="display: none;">';
      $out .= '<div id="search_header"><input type="search" id="pins_search" name="pins_search" placeholder="Search pins by name, eg hotel"><select id="pins_set"><option value="">All icon sets</option><option value="big/">Big icon set</option><option value="default/">Default icon set</option></select></div>';
      $out .= '<div id="pins_container">';
      foreach (glob(GMW_PLUGIN_DIR . 'images/pins/*/*.png') as $filename) {
        $filename = str_replace('\\', '/', $filename);
        preg_match('/\/([^\/]+)\/[^\/]+\.png$/i', $filename, $matches);
        if (!empty($matches[1])) {
          $folder = $matches[1];
        } else {
          $folder = 'default';          
        }
        $filename = basename($filename);
        $name = str_replace(array('.png', '-', '_'), array('', ' ', ' '), $filename);
        $name = ucfirst($name);
        $filename = $folder . '/' . $filename;
        $out .= '<a href="#" data-filename="' . $filename . '"><img src="" alt="' . $name . '" title="' . $name . '"><span>' . $name . '</span></a>';
      }
      $out .= '<p><i>Default icon set is created by Nicolas Mollet under the Creative Commons Attribution-Share Alike 3.0 Unported license. You can find them on the <a class="skip-search" href="https://mapicons.mapsmarker.com/" target="_blank">Maps Icons Collection</a>.</i></p>';
      $out .= '</div>';
      $out .= '</div>'; // dialog  
    } // address picker and pins dialog if activated

    echo $out;
  } // admin_dialogs_markup


  // complete options screen markup
  static function settings_screen() {
    if (!current_user_can('manage_options')) {
      wp_die('Cheating? You don\'t have the right to access this page.', 'Google Maps Widget', array('back_link' => true));
    }
    
    $options = GMW::get_options();

    echo '<div class="wrap gmw-options">';
    if (GMW::is_activated()) {
      echo '<h1><img alt="' . __('Google Maps Widget PRO', 'google-maps-widget') . '" title="' . __('Google Maps Widget PRO', 'google-maps-widget') . '" height="55" src="' . plugins_url('/images/gmw-logo-pro.png', __FILE__) . '"></h1>';
    } else {
      echo '<h1><img alt="' . __('Google Maps Widget', 'google-maps-widget') . '" title="' . __('Google Maps Widget', 'google-maps-widget') . '" height="55" src="' . plugins_url('/images/gmw-logo.png', __FILE__) . '"></h1>';
    }
    
    echo '<form method="post" action="options.php" enctype="multipart/form-data">';
    settings_fields(GMW::$options);
    
    echo '<div id="gmw-settings-tabs"><ul>';
    echo '<li><a href="#gmw-settings">' . __('Settings', 'google-maps-widget') . '</a></li>';
    echo '<li><a href="#gmw-export">' . __('Export &amp; Import', 'google-maps-widget') . '</a></li>';
    echo '<li><a href="#gmw-license">' . __('License', 'google-maps-widget') . '</a></li>';
    echo '</ul>';
    
    echo '<div id="gmw-settings" style="display: none;">';
    echo '<table class="form-table">';
    echo '<tr>
          <th scope="row"><label for="api_key">' . __('Google Maps API Key', 'google-maps-widget') . '</label></th>
          <td><input name="' . GMW::$options . '[api_key]" type="text" id="api_key" value="' . esc_attr($options['api_key']) . '" class="regular-text" placeholder="Google Maps API key" oninput="setCustomValidity(\'\')" oninvalid="this.setCustomValidity(\'Please use Google Developers Console to generate an API key and enter it here. It is completely free.\')"> <a href="#" class="button button-secondary gmw-test-api-key">Test API key</a>
          <p class="description">New Google Maps usage policy dictates that everyone using the maps should register for a free API key.<br>
          Please create a key for "Google Static Maps API" and "Google Maps Embed API" using the <a href="https://console.developers.google.com/project" target="blank">Google Developers Console</a>.<br>
          Or use <a href="https://console.developers.google.com/flows/enableapi?apiid=maps_embed_backend&keyType=CLIENT_SIDE&reusekey=true" target="_blank">wizard step #1</a> - login, click next a few times &amp; copy the key. Then use <a href="https://console.developers.google.com/flows/enableapi?apiid=static_maps_backend&keyType=CLIENT_SIDE&reusekey=true" target="_blank">wizard step #2</a> and select the same "My Project".<br>
          If you want to protect your API key by using the "Accept requests from these HTTP referrers" option in Google Console add your domain<br>in these two formats: *.mydomain.com/* and mydomain.com/*</p></td>
          </tr>';
    if (GMW::is_activated()) {
      echo '<tr>
            <th scope="row"><label for="sc_map">' . __('Map Shortcode', 'google-maps-widget') . '</label></th>
            <td><input class="regular-text" name="' . GMW::$options . '[sc_map]" type="text" id="sc_map" value="' . esc_attr($options['sc_map']) . '" placeholder="Map shortcode" required="required" oninvalid="this.setCustomValidity(\'Please enter the shortcode you want to use for Google Maps Widget maps.\')" oninput="setCustomValidity(\'\')">
            <p class="description">If the default shortcode "gmw" is taken by another plugin change it to something else, eg: "gmaps".</p></td>
            </tr>';
    }
    echo '</table>';

    if (GMW::is_activated()) {
      echo '<h3 class="title">Advanced Settings</h3>';
      echo '<table class="form-table">';
      echo '<tr>
            <th scope="row"><label for="track_ga">' . __('Track with Google Analytics', 'google-maps-widget') . '</label></th>
            <td><input name="' . GMW::$options . '[track_ga]" type="checkbox" id="track_ga" value="1"' . checked('1', $options['track_ga'], false) . '>
            <span class="description">Each time the interactive map is opened either in lightbox or as a thumbnail replacement a Google Analytics Event will be tracked.<br>You need to have GA already configured on the site. It is fully compatibile with all GA plugins and all GA tracking code versions. Default: unchecked.</span></td></tr>';
      echo '<tr>
            <th scope="row"><label for="include_jquery">' . __('Include jQuery', 'google-maps-widget') . '</label></th>
            <td><input name="' . GMW::$options . '[include_jquery]" type="checkbox" id="include_jquery" value="1"' . checked('1', $options['include_jquery'], false) . '>
            <span class="description">If you\'re experiencing problems with double jQuery include disable this option. Default: checked.</span></td></tr>';
      echo '<tr>
            <th scope="row"><label for="include_lightbox_css">' . __('Include Colorbox &amp; Thumbnail CSS', 'google-maps-widget') . '</label></th>
            <td><input name="' . GMW::$options . '[include_lightbox_css]" type="checkbox" id="include_lightbox_css" value="1"' . checked('1', $options['include_lightbox_css'], false) . '>
            <span class="description">If your theme or other plugins already include Colorbox CSS disable this option.<br>Please note that widget (thumbnail map) related CSS will also be removed which will cause minor differences in the way it\'s displayed. Default: checked.</span></td></tr>';
      echo '<tr>
            <th scope="row"><label for="include_lightbox_js">' . __('Include Colorbox JS', 'google-maps-widget') . '</label></th>
            <td><input name="' . GMW::$options . '[include_lightbox_js]" type="checkbox" id="include_lightbox_js" value="1"' . checked('1', $options['include_lightbox_js'], false) . '>
            <span class="description">If your theme or other plugins already include Colorbox JS file disable this option. Default: checked.</span></td></tr>';
      echo '<tr>
            <th scope="row"><label for="disable_tooltips">' . __('Disable Admin Tooltips', 'google-maps-widget') . '</label></th>
            <td><input name="' . GMW::$options . '[disable_tooltips]" type="checkbox" id="disable_tooltips" value="1"' . checked('1', $options['disable_tooltips'], false) . '>
            <span class="description">All settings in widget edit GUI have tooltips. This setting completely disables them. Default: unchecked.</span></td></tr>';
      echo '<tr>
            <th scope="row"><label for="disable_sidebar">' . __('Disable Hidden Sidebar', 'google-maps-widget') . '</label></th>
            <td><input name="' . GMW::$options . '[disable_sidebar]" type="checkbox" id="disable_sidebar" value="1"' . checked('1', $options['disable_sidebar'], false) . '>
            <span class="description">Hidden sidebar helps you to build maps that are displayed with shortcodes. If it bothers you in the admin, disable it. Default: unchecked.</span></td></tr>';
      echo '</table>';
    } // advanced settings
    
    if (!GMW::is_activated()) {
      echo '<p>Not sure if you should upgrade to <span class="gmw-pro-red">PRO</span>? It offers more than 50 extra features like shortcodes and Google Analytics tracking; <a href="#" class="open_promo_dialog">compare features now</a>.</p>';      
    }

    echo get_submit_button(__('Save Settings', 'google-maps-widget'));
    echo '</div>'; // settings tab
    
    echo '<div id="gmw-export" style="display: none;">';
    if (GMW::is_activated()) {
      echo '<table class="form-table">';
      echo '<tr>
            <th scope="row"><label for="">' . __('Export widgets', 'google-maps-widget') . '</label></th>
            <td><a href="' . add_query_arg(array('action' => 'gmw_export_widgets'), admin_url('admin.php')) . '" class="button button-secondary">Download export file</a>
            <p class="description">The export file will only containt Google Maps Widget widgets. This includes active (in sidebars) widgets and inactive ones as well.</p></td>
            </tr>';
      echo '<tr>
            <th scope="row"><label for="">' . __('Import widgets', 'google-maps-widget') . '</label></th>
            <td><input type="file" name="gmw_widgets_import" id="gmw_widgets_import" accept=".txt"> 
            <input type="submit" name="submit-import" id="submit-import" class="button button-secondary button-large" value="Import widgets">';
      echo '<p class="description">Only use TXT export files generated by Google Maps Widget.<br>
            Existing GMW widgets will not be overwritten nor any other widgets touched. If you renamed a sidebar or old one no longer exists widgets will be placed in the inactive widgets area.</p></td>
            </tr>';
      echo '</table>';  
    } else {
      echo '<p>Export &amp; Import are one of many <span class="gmw-pro-red">PRO</span> features. <a href="#" class="open_promo_dialog">Upgrade now</a> to get access to more than 50 extra options.</p>';
    }
    echo '</div>'; // export/import tab
    
    echo '<div id="gmw-license" style="display: none;">';
    if (!GMW::is_activated()) {
      echo '<p>Not sure if you should upgrade to <span class="gmw-pro-red">PRO</span>? It offers more than 50 extra features; <a href="#" class="open_promo_dialog">compare features now</a>.</p>';      
    }
    echo '<table class="form-table">';
    echo '<tr>
          <th scope="row"><label for="activation_code">' . __('License Key', 'google-maps-widget') . '</label></th>
          <td><input class="regular-text" name="' . GMW::$options . '[activation_code]" type="text" id="activation_code" value="' . esc_attr($options['activation_code']) . '" placeholder="12345-12345-12345">
          <p class="description">License key can be found in the confirmation email you received after purchasing.</p></td>
          </tr>';
    if (GMW::is_activated()) {
      if ($options['license_expires'] == '2035-01-01') {
        $valid = 'indefinitely';
      } else {
        $valid = 'until ' . date('F jS, Y', strtotime($options['license_expires']));
        if (date('Y-m-d') == $options['license_expires']) {
          $valid .= '; expires today';
        } elseif (date('Y-m-d', time() + DAY_IN_SECONDS) == $options['license_expires']) {
          $valid .= '; expires tomorrow';
        } elseif (date('Y-m-d', time() + 30 * DAY_IN_SECONDS) > $options['license_expires']) {
          $tmp = (strtotime($options['license_expires'] . date(' G:i:s')) - time()) / DAY_IN_SECONDS;
          $valid .= '; expires in ' . round($tmp) . ' days';
        }
      }
      echo '<tr>
          <th scope="row"><label for="">' . __('License Key Status', 'google-maps-widget') . '</label></th>
          <td><b style="color: green">Active</b><br>
          Type: ' . str_replace('pro', 'PRO', $options['license_type']) . '<br>
          Valid ' . $valid . '</td>
          </tr>';  
    } else {
      echo '<tr>
          <th scope="row"><label for="">' . __('License Key Status', 'google-maps-widget') . '</label></th>
          <td><b style="color: red">Inactive</b></td>
          </tr>';
    }
    echo '</table>';
    echo get_submit_button(__('Save and Validate License Key', 'google-maps-widget'), 'primary large', 'submit-license', true, array());
    echo '</div>'; // license tab

    echo '</form>';
    echo '</div>'; // wrap
  } // settings_screen
  
  
  // send user's name & email and get trial license key
  static function get_trial_ajax() {
    check_ajax_referer('gmw_get_trial');
    
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    if (defined('WPLANG')) {
      $lang = strtolower(substr(WPLANG, 0, 2));
    } else {
      $lang = 'en';
    }
    
    $request_params = array('sslverify' => false, 'timeout' => 7, 'redirection' => 2);
    $request_args = array('action' => 'get_trial', 'name' => $name, 'email' => $email, 'lang' => $lang, 'ip' => $_SERVER['REMOTE_ADDR'], 'site' => get_home_url());

    $url = add_query_arg($request_args, GMW::$licensing_servers[0]);
    $response = wp_remote_get(esc_url_raw($url), $request_params);
    
    if (is_wp_error($response) || !wp_remote_retrieve_body($response)) {
      $url = add_query_arg($request_args, GMW::$licensing_servers[1]);
      $response = wp_remote_get(esc_url_raw($url), $request_params);
    }

    if (!is_wp_error($response) && wp_remote_retrieve_body($response)) {
      $result = wp_remote_retrieve_body($response);
      $result = json_decode($result, true, 3);
      if (!empty($result['success']) && $result['success'] === true && is_array($result['data']) && sizeof($result['data']) == 3) {
        $result['data']['license_active'] = true;
        GMW::set_options($result['data']);
        wp_send_json_success();
      } elseif (isset($result['success']) && $result['success'] === false && !empty($result['data'])) {
        wp_send_json_error($result['data']);
      } else {
        wp_send_json_error('Invalid response from licensing server. Please try again later.');
      }
    } else {
      wp_send_json_error('Unable to contact licensing server. Please try again in a few moments.');
    }
  } // get_trial_ajax


  // check activation code and save if valid
  static function activate_license_key_ajax() {
    check_ajax_referer('gmw_activate_license_key');
    
    $code = str_replace(' ', '', $_POST['code']);
    
    if (strlen($code) < 6 || strlen($code) > 50) {
      wp_send_json_error(__('Please double-check the license key. The format is not valid.', 'google-maps-widget'));  
    }

    $tmp = GMW::validate_activation_code($code);
    if ($tmp['success']) {
      GMW::set_options(array('activation_code' => $code, 'license_active' => $tmp['license_active'], 'license_type' => $tmp['license_type'], 'license_expires' => $tmp['license_expires']));  
    }
    if ($tmp['license_active'] && $tmp['success']) {
      wp_send_json_success();
    } else {
      wp_send_json_error($tmp['error']);
    }
  } // activate_license_key_ajax


  // check if activation code is valid
  static function validate_activation_code($code) {
    $request_params = array('sslverify' => false, 'timeout' => 7, 'redirection' => 2);
    $request_args = array('action' => 'validate_license', 'code' => $code, 'domain' => get_home_url());
    
    $out = array('success' => false, 'license_active' => false, 'activation_code' => $code, 'error' => '', 'license_type' => '', 'license_expires' => '1900-01-01');

    $url = add_query_arg($request_args, GMW::$licensing_servers[0]);
    $response = wp_remote_get(esc_url_raw($url), $request_params);
    
    if (is_wp_error($response) || !wp_remote_retrieve_body($response)) {
      $url = add_query_arg($request_args, GMW::$licensing_servers[1]);
      $response = wp_remote_get(esc_url_raw($url), $request_params);
    }
    
    if (!is_wp_error($response) && wp_remote_retrieve_body($response)) {
      $result = wp_remote_retrieve_body($response);
      $result = json_decode($result, true, 3);
      if (is_array($result['data']) && sizeof($result['data']) == 4) {
        $out['success'] = true;
        $out = array_merge($out, $result['data']);
      } else {
        $out['error'] = 'Invalid response from licensing server. Please try again later.';
      }
    } else {
      $out['error'] = 'Unable to contact licensing server. Please try again in a few moments.';
    }

    return $out;
  } // validate_activation_code


  // helper function for creating dropdowns
  static function create_select_options($options, $selected = null, $output = true) {
    $out = "\n";

    if(!is_array($selected)) {
      $selected = array($selected);
    }

    foreach ($options as $tmp) {
      $data = '';
      if (isset($tmp['data-imagesrc'])) {
        $data .= ' data-imagesrc="' . $tmp['data-imagesrc'] . '" ';
      }
      if (isset($tmp['disabled'])) {
        $data .= ' disabled="disabled" ';
      }
      if ($tmp['val'] == '-1') {
        $data .= ' class="gmw_promo" ';
      }
      if (in_array($tmp['val'], $selected)) {
        $out .= "<option selected=\"selected\" value=\"{$tmp['val']}\"{$data}>{$tmp['label']}&nbsp;</option>\n";
      } else {
        $out .= "<option value=\"{$tmp['val']}\"{$data}>{$tmp['label']}&nbsp;</option>\n";
      }
    } // foreach

    if ($output) {
      echo $out;
    } else {
      return $out;
    }
  } // create_select_options


  // sanitizes color code string, leaves # intact
  static function sanitize_hex_color( $color ) {
    if (empty($color)) {
      return '#ff0000';
    }

    // 3 or 6 hex digits
    if (preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color)) {
      return $color;
    }
  } // sanitize_hex_color


  // converts color from human readable to hex
  static function convert_color($color) {
    $color_codes = array('black'  => '#000000', 'white'  => '#ffffff',
                         'brown'  => '#a52a2a', 'green'  => '#00ff00', 
                         'purple' => '#800080', 'yellow' => '#ffff00', 
                         'blue'   => '#0000ff', 'gray'   => '#808080', 
                         'orange' => '#ffa500', 'red'    => '#ff0000');

    $color = strtolower(trim($color));

    if (empty($color) || !isset($color_codes[$color])) {
      return '#ff0000';
    } else {
      return $color_codes[$color];
    }
  } // convert_color


  // helper function for checkbox handling
  static function check_var_isset($values, $variables) {
    foreach ($variables as $key => $value) {
      if (!isset($values[$key])) {
        $values[$key] = $value;
      }
    }

    return $values;
  } // check_var_isset


  // shortcode support for any GMW instance
  static function do_shortcode($atts, $content = null) {
    if (!GMW::is_activated()) {
      return '';
    }

    global $wp_widget_factory;
    $out = '';
    $atts = shortcode_atts(array('id' => 0, 'thumb_width' => 0, 'thumb_height' => 0), $atts);
    $id = (int) $atts['id'];
    $widgets = get_option('widget_googlemapswidget');

    if (!$id || !isset($widgets[$id]) || empty($widgets[$id])) {
      $out .= '<span class="gmw-error">Google Maps Widget shortcode error - please double-check the widget ID.</span>';
    } else {
      $widget_args = $widgets[$id];
      $widget_instance['widget_id'] = 'googlemapswidget-' . $id;
      $widget_instance['widget_name'] = 'Google Maps Widget';
      
      if (!empty($atts['thumb_width']) && !empty($atts['thumb_height'])) {
        $widget_args['thumb_width'] = min(640, max(50, (int) $atts['thumb_width']));
        $widget_args['thumb_height'] = min(640, max(50, (int) $atts['thumb_height']));
      }

      $out .= '<div class="gmw-shortcode-widget">';
      ob_start();
      the_widget('GoogleMapsWidget', $widget_args, $widget_instance);
      $out .= ob_get_contents();
      ob_end_clean();
      $out .= '</div>';
    }

    return $out;
  } // do_shortcode


  // activate doesn't get fired on upgrades so we have to compensate
  public static function maybe_upgrade() {
    $options = GMW::get_options();
    
    // pro was active with old key, recheck
    if (!empty($options['activation_code']) && strlen($options['activation_code']) == 6 && $options['license_active'] === '') {
      $tmp = GMW::validate_activation_code($options['activation_code']);
      if ($tmp['success']) {
        $update['license_type'] = $tmp['license_type'];
        $update['license_expires'] = $tmp['license_expires'];
        $update['license_active'] = $tmp['license_active'];
        GMW::set_options($update);
      }
    } // old license upgrade

    if (!isset($options['first_version']) || !isset($options['first_install'])) {
      $update = array();
      $update['first_version'] = GMW::$version;
      $update['first_install'] = current_time('timestamp');
      GMW::set_options($update);
    }
  } // maybe_upgrade


  // write down a few things on plugin activation
  // NO DATA is sent anywhere unless user explicitly agrees to it!
  static function activate() {
    $options = GMW::get_options();

    if (!isset($options['first_version']) || !isset($options['first_install'])) {
      $options['first_version'] = GMW::$version;
      $options['first_install'] = current_time('timestamp');
      $options['last_tracking'] = false;
      GMW::set_options($options);
    }
  } // activate


  // clean up on deactivation
  static function deactivate() {
    $options = GMW::get_options();

    if (isset($options['allow_tracking']) && $options['allow_tracking'] === true) {
      GMW_tracking::clear_cron();
    }
  } // deactivate


  // clean up on uninstall / delete
  static function uninstall() {
    delete_option(GMW::$options);
  } // uninstall
} // class GMW

endif; // if GMW class exists


// hook everything up
register_activation_hook(__FILE__, array('GMW', 'activate'));
register_deactivation_hook(__FILE__, array('GMW', 'deactivate'));
register_uninstall_hook(__FILE__, array('GMW', 'uninstall'));
add_action('init', array('GMW', 'init'));
add_action('plugins_loaded', array('GMW', 'plugins_loaded'));
add_action('widgets_init', array('GMW', 'widgets_init'));