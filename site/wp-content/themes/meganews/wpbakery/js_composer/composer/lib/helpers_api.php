<?php
function vc_map($attributes) {
  if( !isset($attributes['base']) ) {
    trigger_error(__("Wrong wpb_map object. Base attribute is required", 'meganews'), E_USER_ERROR);
    die();
  }
  WPBMap::map($attributes['base'], $attributes);
}
/* Backwards compatibility  **/
function wpb_map($attributes) { vc_map($attributes); }


function vc_remove_element($shortcode) {
  WPBMap::dropShortcode($shortcode);
}
/* Backwards compatibility  **/
function wpb_remove($shortcode) { vc_remove_element($shortcode); }


function vc_add_param($shortcode, $attributes) {
  WPBMap::addParam($shortcode, $attributes);
}
/* Backwards compatibility  **/
function wpb_add_param($shortcode, $attributes) { vc_add_param($shortcode, $attributes); }

/**
 * Shorthand function for WPBMap::modify
 * @param $name
 * @param $setting
 * @param string $value
 * @return array|bool
 */
function vc_map_update($name = '', $setting = '', $value = '') {
    return WPBMap::modify($name, $setting);
}