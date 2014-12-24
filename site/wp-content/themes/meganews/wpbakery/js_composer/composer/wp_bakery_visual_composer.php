<?php
/**
 * WPBakery Visual Composer Here includes useful files for plugin
 *
 * @package WPBakeryVisualComposer
 *
 */

$lib_dir = $composer_settings['COMPOSER_LIB'];
$shortcodes_dir = $composer_settings['SHORTCODES_LIB'];
$settings_dir = $composer_settings['COMPOSER'] . 'settings/';

require_once( $lib_dir . 'wp_autoupdate.php' );
require_once( $lib_dir . 'abstract.php' );
require_once( $lib_dir . 'helpers.php' );
require_once( $lib_dir . 'helpers_api.php' );
require_once( $lib_dir . 'filters.php' );
require_once( $lib_dir . 'params.php' );

require_once( $lib_dir . 'mapper.php' );
require_once( $lib_dir . 'shortcodes.php' );
require_once( $lib_dir . 'composer.php' );

require_once( $settings_dir . 'settings.php');

/* Add shortcodes classes */
require_once( $shortcodes_dir . 'row.php' );
require_once( $shortcodes_dir . 'column.php' );

require_once( $shortcodes_dir . 'tabs.php' );
require_once( $shortcodes_dir . 'accordion.php' );
require_once( $shortcodes_dir . 'button.php' );
require_once( $shortcodes_dir . 'cta_button.php' );
require_once( $shortcodes_dir . 'facebook.php' );
require_once( $shortcodes_dir . 'flickr.php' );
require_once( $shortcodes_dir . 'google_maps.php' );
require_once( $shortcodes_dir . 'google_plus.php' );
require_once( $shortcodes_dir . 'image_gallery.php' );
require_once( $shortcodes_dir . 'message_box.php' );
require_once( $shortcodes_dir . 'pinterest.php' );
require_once( $shortcodes_dir . 'posts_slider.php' );
require_once( $shortcodes_dir . 'posts_grid.php' );
require_once( $shortcodes_dir . 'progress_bar.php' );
require_once( $shortcodes_dir . 'raw_content.php' );
require_once( $shortcodes_dir . 'separator.php' );
require_once( $shortcodes_dir . 'single_image.php' );
require_once( $shortcodes_dir . 'teaser_grid.php' );
require_once( $shortcodes_dir . 'text.php' );
require_once( $shortcodes_dir . 'toggle_faq.php' );
require_once( $shortcodes_dir . 'tour.php' );
require_once( $shortcodes_dir . 'video_player.php' );
require_once( $shortcodes_dir . 'widgetised_sidebar.php' );
require_once( $shortcodes_dir . 'wordpress_widgets.php' );
require_once( $shortcodes_dir . 'dropcap.php' );
require_once( $shortcodes_dir . 'blockquote.php' );
require_once( $shortcodes_dir . 'list.php' );
require_once( $shortcodes_dir . 'circle_chart.php' );
require_once( $shortcodes_dir . 'contact_info.php' );
require_once( $shortcodes_dir . 'carousel.php' );
require_once( $shortcodes_dir . 'testimonials.php' );
require_once( $shortcodes_dir . 'single_item.php' );
require_once( $shortcodes_dir . 'icon.php' );
require_once( $shortcodes_dir . 'text_titles.php' );
require_once( $shortcodes_dir . 'some_text.php' );
require_once( $shortcodes_dir . 'counter.php' );
require_once( $shortcodes_dir . 'post_section.php' );
require_once( $shortcodes_dir . 'post_ticker.php' );


//require_once( $shortcodes_dir . 'example.php' );

require_once( $lib_dir . 'layouts.php' );

require_once( $lib_dir . 'params/load.php');
