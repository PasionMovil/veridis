<?php
/**
 * Loads attributes hooks.
 */
$dir = dirname(__FILE__);

require_once $dir.'/textarea_html/textarea_html.php';
require_once $dir.'/colorpicker/colorpicker.php';
require_once $dir.'/loop/loop.php';
require_once $dir.'/vc_link/vc_link.php';
require_once $dir.'/teaser_template/teaser_template.php';




global $vc_params_list;
$vc_params_list = array('textarea_html', 'colorpicker', 'loop', 'vc_link', 'teaser_template');
