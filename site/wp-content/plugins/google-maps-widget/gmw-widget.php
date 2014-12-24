<?php
/*
 * Google Maps Widget
 * Widget definition, admin GUI and front-end functions
 * (c) Web factory Ltd, 2012 - 2014
 */


// include only file
if (!defined('ABSPATH')) {
  die();
}


// main widget class, extends WP widget interface/class
class GoogleMapsWidget extends WP_Widget {
  static $widgets = array();

  function GoogleMapsWidget() {
    $widget_ops = array('classname' => 'google-maps-widget', 'description' => __('Displays a map image thumbnail with a larger map available in a lightbox.', 'google-maps-widget'));
    $control_ops = array('width' => 450, 'height' => 350);
    $this->WP_Widget('GoogleMapsWidget', __('Google Maps Widget', 'google-maps-widget'), $widget_ops, $control_ops);
  } // GoogleMapsWidget

  function form($instance) {
    $instance = wp_parse_args((array) $instance,
                              array('title' => __('Map', 'google-maps-widget'),
                                    'address' => __('New York, USA', 'google-maps-widget'),
                                    'thumb_pin_color' => 'red',
                                    'thumb_pin_size' => 'default',
                                    'thumb_width' => '250',
                                    'thumb_height' => '250',
                                    'thumb_type' => 'roadmap',
                                    'thumb_zoom' => '13',
                                    'thumb_header' => '',
                                    'thumb_footer' => '',
                                    'thumb_new_colors' => '1',
                                    'thumb_link_type' => 'lightbox',
                                    'thumb_link' => '',
                                    'lightbox_width' => '550',
                                    'lightbox_height' => '550',
                                    'lightbox_type' => 'roadmap',
                                    'lightbox_zoom' => '14',
                                    'lightbox_bubble' => '1',
                                    'lightbox_skin' => 'light',
                                    'lightbox_title' => '1',
                                    'lightbox_header' => '',
                                    'lightbox_footer' => ''));

    extract($instance, EXTR_SKIP);

    // legacy fixes for older versions; it's auto-fixed on first widget save but has to be here
    if(!$thumb_link_type) {
      $thumb_link_type = 'lightbox';
    }
    if(!$lightbox_skin) {
      $lightbox_skin = 'light';
    }

    $map_types_thumb = array(array('val' => 'roadmap', 'label' => __('Road (default)', 'google-maps-widget')),
                             array('val' => 'satellite', 'label' => __('Satellite', 'google-maps-widget')),
                             array('val' => 'terrain', 'label' => __('Terrain', 'google-maps-widget')),
                             array('val' => 'hybrid', 'label' => __('Hybrid', 'google-maps-widget')));

    $map_types_lightbox = array(array('val' => 'm', 'label' => __('Road (default)', 'google-maps-widget')),
                                array('val' => 'k', 'label' => __('Satellite', 'google-maps-widget')),
                                array('val' => 'p', 'label' => __('Terrain', 'google-maps-widget')),
                                array('val' => 'h', 'label' => __('Hybrid', 'google-maps-widget')));

    $pin_colors = array(array('val' => 'black', 'label' => __('Black', 'google-maps-widget')),
                        array('val' => 'brown', 'label' => __('Brown', 'google-maps-widget')),
                        array('val' => 'green', 'label' => __('Green', 'google-maps-widget')),
                        array('val' => 'purple', 'label' => __('Purple', 'google-maps-widget')),
                        array('val' => 'yellow', 'label' => __('Yellow', 'google-maps-widget')),
                        array('val' => 'blue', 'label' => __('Blue', 'google-maps-widget')),
                        array('val' => 'gray', 'label' => __('Gray', 'google-maps-widget')),
                        array('val' => 'orange', 'label' => __('Orange', 'google-maps-widget')),
                        array('val' => 'red', 'label' => __('Red (default)', 'google-maps-widget')),
                        array('val' => 'white', 'label' => __('White', 'google-maps-widget')));

    $pin_sizes = array(array('val' => 'tiny', 'label' => __('Tiny', 'google-maps-widget')),
                       array('val' => 'small', 'label' => __('Small', 'google-maps-widget')),
                       array('val' => 'mid', 'label' => __('Medium', 'google-maps-widget')),
                       array('val' => 'default', 'label' => __('Large (default)', 'google-maps-widget')));

    $zoom_levels = array(array('val' => '0', 'label' => __('0 - entire world', 'google-maps-widget')));
    for ($tmp = 1; $tmp <= 21; $tmp++) {
      $zoom_levels[] = array('val' => $tmp, 'label' => $tmp);
    }

    $lightbox_skins[] = array('val' => 'light', 'label' => __('Light (default)', 'google-maps-widget'));
    $lightbox_skins[] = array('val' => 'dark', 'label' => __('Dark', 'google-maps-widget'));

    $thumb_link_types[] = array('val' => 'lightbox', 'label' => __('Lightbox (default)', 'google-maps-widget'));
    $thumb_link_types[] = array('val' => 'custom', 'label' => __('Custom link', 'google-maps-widget'));
    $thumb_link_types[] = array('val' => 'nolink', 'label' => __('Disable link', 'google-maps-widget'));


    echo '<p><label for="' . $this->get_field_id('title') . '">' . __('Title', 'google-maps-widget') . ':</label><input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . esc_attr($title) . '" /></p>';
    echo '<p><label for="' . $this->get_field_id('address') . '">' . __('Address', 'google-maps-widget') . ':</label><input class="widefat" id="' . $this->get_field_id('address') . '" name="' . $this->get_field_name('address') . '" type="text" value="' . esc_attr($address) . '" /></p>';

    echo '<div class="gmw-tabs" id="tab-' . $this->id . '"><ul><li><a href="#gmw-thumb">' . __('Thumbnail map', 'google-maps-widget') . '</a></li><li><a href="#gmw-lightbox">' . __('Lightbox map', 'google-maps-widget') . '</a></li></ul>';
    echo '<div id="gmw-thumb">';

    echo '<p><label class="gmw-label" for="' . $this->get_field_id('thumb_width') . '">' . __('Map Size', 'google-maps-widget') . ':</label>';
    echo '<input class="small-text" id="' . $this->get_field_id('thumb_width') . '" name="' . $this->get_field_name('thumb_width') . '" type="text" value="' . esc_attr($thumb_width) . '" /> x ';
    echo '<input class="small-text" id="' . $this->get_field_id('thumb_height') . '" name="' . $this->get_field_name('thumb_height') . '" type="text" value="' . esc_attr($thumb_height) . '" />';
    echo ' px</p>';

    echo '<p><label class="gmw-label" for="' . $this->get_field_id('thumb_type') . '">' . __('Map Type', 'google-maps-widget') . ':</label>';
    echo '<select id="' . $this->get_field_id('thumb_type') . '" name="' . $this->get_field_name('thumb_type') . '">';
    GMW::create_select_options($map_types_thumb, $thumb_type);
    echo '</select></p>';

    echo '<p><label class="gmw-label" for="' . $this->get_field_id('thumb_pin_color') . '">' . __('Pin Color', 'google-maps-widget') . ':</label>';
    echo '<select id="' . $this->get_field_id('thumb_pin_color') . '" name="' . $this->get_field_name('thumb_pin_color') . '">';
    GMW::create_select_options($pin_colors, $thumb_pin_color);
    echo '</select></p>';

    echo '<p><label class="gmw-label" for="' . $this->get_field_id('thumb_pin_size') . '">' . __('Pin Size', 'google-maps-widget') . ':</label>';
    echo '<select id="' . $this->get_field_id('thumb_pin_size') . '" name="' . $this->get_field_name('thumb_pin_size') . '">';
    GMW::create_select_options($pin_sizes, $thumb_pin_size);
    echo '</select></p>';

    echo '<p><label class="gmw-label" for="' . $this->get_field_id('thumb_zoom') . '">' . __('Zoom Level', 'google-maps-widget') . ':</label>';
    echo '<select id="' . $this->get_field_id('thumb_zoom') . '" name="' . $this->get_field_name('thumb_zoom') . '">';
    GMW::create_select_options($zoom_levels, $thumb_zoom);
    echo '</select></p>';

    echo '<p><label class="gmw-label" for="' . $this->get_field_id('thumb_link_type') . '">' . __('Link To', 'google-maps-widget') . ':</label>';
    echo '<select class="gmw_thumb_link_type" id="' . $this->get_field_id('thumb_link_type') . '" name="' . $this->get_field_name('thumb_link_type') . '">';
    GMW::create_select_options($thumb_link_types, $thumb_link_type);
    echo '</select></p>';

    echo '<p class="gmw_thumb_link_section"><label class="gmw-label" for="' . $this->get_field_id('thumb_link') . '">' . __('Custom Link', 'google-maps-widget') . ':</label>';
    echo '<input class="regular-text" id="' . $this->get_field_id('thumb_link') . '" name="' . $this->get_field_name('thumb_link') . '" type="text" value="' . esc_attr($thumb_link) . '" /></p>';

    echo '<p><label for="' . $this->get_field_id('thumb_new_colors') . '">' . __('Use New Color Scheme', 'google-maps-widget') . ':&nbsp;</label>';
    echo '<input ' . checked('1', $thumb_new_colors, false) . ' value="1" type="checkbox" id="' . $this->get_field_id('thumb_new_colors') . '" name="' . $this->get_field_name('thumb_new_colors') . '">';
    echo '</p>';

    echo '<p><label for="' . $this->get_field_id('thumb_header') . '">' . __('Text Above Map', 'google-maps-widget') . ':</label>';
    echo '<textarea class="widefat" rows="3" cols="20" id="' . $this->get_field_id('thumb_header') . '" name="' . $this->get_field_name('thumb_header') . '">'. $thumb_header . '</textarea></p>';

    echo '<p><label for="' . $this->get_field_id('thumb_footer') . '">' . __('Text Below Map', 'google-maps-widget') . ':</label>';
    echo '<textarea class="widefat" rows="3" cols="20" id="' . $this->get_field_id('thumb_footer') . '" name="' . $this->get_field_name('thumb_footer') . '">'. $thumb_footer . '</textarea></p>';

    echo '</div>'; // thumbnail tab
    echo '<div id="gmw-lightbox">';

    echo '<p><label class="gmw-label" for="' . $this->get_field_id('lightbox_width') . '">' . __('Map Size', 'google-maps-widget') . ':</label>';
    echo '<input class="small-text" id="' . $this->get_field_id('lightbox_width') . '" name="' . $this->get_field_name('lightbox_width') . '" type="text" value="' . esc_attr($lightbox_width) . '" /> x ';
    echo '<input class="small-text" id="' . $this->get_field_id('lightbox_height') . '" name="' . $this->get_field_name('lightbox_height') . '" type="text" value="' . esc_attr($lightbox_height) . '" />';
    echo ' px</p>';

    echo '<p><label class="gmw-label" for="' . $this->get_field_id('lightbox_type') . '">' . __('Map Type', 'google-maps-widget') . ':</label>';
    echo '<select id="' . $this->get_field_id('lightbox_type') . '" name="' . $this->get_field_name('lightbox_type') . '">';
    GMW::create_select_options($map_types_lightbox, $lightbox_type);
    echo '</select></p>';

    echo '<p><label class="gmw-label" for="' . $this->get_field_id('lightbox_zoom') . '">' . __('Zoom Level', 'google-maps-widget') . ':</label>';
    echo '<select id="' . $this->get_field_id('lightbox_zoom') . '" name="' . $this->get_field_name('lightbox_zoom') . '">';
    GMW::create_select_options($zoom_levels, $lightbox_zoom);
    echo '</select></p>';

    echo '<p><label class="gmw-label" for="' . $this->get_field_id('lightbox_skin') . '">' . __('Skin', 'google-maps-widget') . ':</label>';
    echo '<select id="' . $this->get_field_id('lightbox_skin') . '" name="' . $this->get_field_name('lightbox_skin') . '">';
    GMW::create_select_options($lightbox_skins, $lightbox_skin);
    echo '</select></p>';

    echo '<p><label for="' . $this->get_field_id('lightbox_bubble') . '">' . __('Show Address Bubble', 'google-maps-widget') . ':&nbsp;</label>';
    echo '<input ' . checked('1', $lightbox_bubble, false) . ' value="1" type="checkbox" id="' . $this->get_field_id('lightbox_bubble') . '" name="' . $this->get_field_name('lightbox_bubble') . '">';
    echo '</p>';

    echo '<p><label for="' . $this->get_field_id('lightbox_title') . '">' . __('Show Map Title Above Lightbox', 'google-maps-widget') . ':&nbsp;</label>';
    echo '<input ' . checked('1', $lightbox_title, false) . ' value="1" type="checkbox" id="' . $this->get_field_id('lightbox_title') . '" name="' . $this->get_field_name('lightbox_title') . '">';
    echo '</p>';

    echo '<p><label for="' . $this->get_field_id('lightbox_header') . '">' . __('Header Text', 'google-maps-widget') . ':</label>';
    echo '<textarea class="widefat" rows="3" cols="20" id="' . $this->get_field_id('lightbox_header') . '" name="' . $this->get_field_name('lightbox_header') . '">'. $lightbox_header . '</textarea></p>';

    echo '<p><label for="' . $this->get_field_id('lightbox_footer') . '">' . __('Footer Text', 'google-maps-widget') . ':</label>';
    echo '<textarea class="widefat" rows="3" cols="20" id="' . $this->get_field_id('lightbox_footer') . '" name="' . $this->get_field_name('lightbox_footer') . '">'. $lightbox_footer . '</textarea></p>';

    echo '</div>'; // lightbox tab
    echo '</div>'; // tabs
    echo '<p><i>' . __('If you like the plugin give us a shout. Thanks!', 'google-maps-widget') . ' <a title="WebFactory on Twitter" target="_blank" href="http://twitter.com/WebFactoryLtd">@WebFactoryLtd</a></i></p>';
  } // form

  function update($new_instance, $old_instance) {
    $instance = $old_instance;

    $instance['title'] = $new_instance['title'];
    $instance['address'] = $new_instance['address'];
    $instance['thumb_pin_color'] = $new_instance['thumb_pin_color'];
    $instance['thumb_pin_size'] = $new_instance['thumb_pin_size'];
    $instance['thumb_width'] = (int) $new_instance['thumb_width'];
    $instance['thumb_height'] = (int) $new_instance['thumb_height'];
    $instance['thumb_zoom'] = $new_instance['thumb_zoom'];
    $instance['thumb_type'] = $new_instance['thumb_type'];
    $instance['thumb_link_type'] = $new_instance['thumb_link_type'];
    $instance['thumb_link'] = trim($new_instance['thumb_link']);
    $instance['thumb_header'] = trim($new_instance['thumb_header']);
    $instance['thumb_footer'] = trim($new_instance['thumb_footer']);
    $instance['thumb_new_colors'] = isset($new_instance['thumb_new_colors']);
    $instance['lightbox_width'] = (int) $new_instance['lightbox_width'];
    $instance['lightbox_height'] = (int) $new_instance['lightbox_height'];
    $instance['lightbox_type'] = $new_instance['lightbox_type'];
    $instance['lightbox_zoom'] = $new_instance['lightbox_zoom'];
    $instance['lightbox_bubble'] = isset($new_instance['lightbox_bubble']);
    $instance['lightbox_title'] = isset($new_instance['lightbox_title']);
    $instance['lightbox_header'] = trim($new_instance['lightbox_header']);
    $instance['lightbox_footer'] = trim($new_instance['lightbox_footer']);
    $instance['lightbox_skin'] = $new_instance['lightbox_skin'];

    return $instance;
  } // update

  function widget($args, $instance) {
    $out = $tmp = '';

    extract($args, EXTR_SKIP);

    $ll = '';
    if ($instance['lightbox_zoom'] > 14) {
      $coordinates = GMW::get_coordinates($instance['address']);
      if ($coordinates) {
        $ll = $coordinates['lat'] . ',' . $coordinates['lng'];
      }
    }

    $lang = substr(@$_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    if (!$lang) {
      $lang = 'en';
    }
    
    // legacy fix for older versions; it's auto-fixed on first widget save but has to be here
    if(!$instance['lightbox_skin']) {
      $instance['lightbox_skin'] = 'light';
    }

    self::$widgets[] = array('title' => ($instance['lightbox_title']? $instance['title']: ''),
                             'width' => $instance['lightbox_width'],
                             'height' => $instance['lightbox_height'],
                             'footer' => $instance['lightbox_footer'],
                             'header' => $instance['lightbox_header'],
                             'address' => $instance['address'],
                             'zoom' => $instance['lightbox_zoom'],
                             'type' => $instance['lightbox_type'],
                             'skin' => $instance['lightbox_skin'],
                             'bubble' => $instance['lightbox_bubble'],
                             'll' => $ll,
                             'id' => $widget_id);

    $out .= $before_widget;

    if (!isset($instance['thumb_link_type']) || empty($instance['thumb_link_type'])) {
      $instance['thumb_link_type'] = 'lightbox';
    }

    if (isset($instance['thumb_new_colors']) && $instance['thumb_new_colors']) {
      $instance['thumb_new_colors'] = 'true';
    } else {
      $instance['thumb_new_colors'] = 'false';
    }

    $title = empty($instance['title'])? ' ' : apply_filters('widget_title', $instance['title']);
    if (!empty($title)) {
      $out .= $before_title . $title . $after_title;
    }

    if (isset($instance['thumb_header']) && $instance['thumb_header']) {
      $tmp .= wpautop(do_shortcode($instance['thumb_header']));
    }
    $tmp .= '<p>';
    
    if ($instance['thumb_link_type'] == 'lightbox') {
      $alt = __('Click to open larger map', 'google-maps-widget');
    } else {
      $alt = esc_attr($instance['address']);
    }
    
    if ($instance['thumb_link_type'] == 'lightbox') {
      $tmp .= '<a class="gmw-thumbnail-map gmw-lightbox-enabled" href="#gmw-dialog-' . $widget_id . '" title="' . __('Click to open larger map', 'google-maps-widget') . '">';
    } elseif ($instance['thumb_link_type'] == 'custom') {
      $tmp .= '<a class="gmw-thumbnail-map" title="' . esc_attr($instance['address']) . '" href="' . $instance['thumb_link'] . '">';
    }
    $tmp .= '<img alt="' . $alt . '" title="' . $alt . '" src="//maps.googleapis.com/maps/api/staticmap?center=' .
         urlencode($instance['address']) . '&amp;zoom=' . $instance['thumb_zoom'] .
         '&amp;size=' . $instance['thumb_width'] . 'x' . $instance['thumb_height'] . '&amp;maptype=' . $instance['thumb_type'] .
         '&amp;sensor=false&amp;scale=1&amp;markers=size:' . $instance['thumb_pin_size'] . '%7Ccolor:' . $instance['thumb_pin_color'] . '%7Clabel:A%7C' .
         urlencode($instance['address']) . '&amp;language=' . $lang . '&amp;visual_refresh=' . $instance['thumb_new_colors'] .'">';
    if ($instance['thumb_link_type'] == 'lightbox' || $instance['thumb_link_type'] == 'custom') {
      $tmp .= '</a>';
    }
    $tmp .= '</p>';
    if (isset($instance['thumb_footer']) && $instance['thumb_footer']) {
      $tmp .= wpautop(do_shortcode($instance['thumb_footer']));
    }
    $out .= apply_filters('google_maps_widget_content', $tmp);

    $out .= $after_widget;

    echo $out;
  } // widget
} // class GoogleMapsWidget