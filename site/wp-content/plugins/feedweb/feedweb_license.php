<?php
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(dirname(dirname(dirname(__FILE__)))) . '/');

require_once( ABSPATH.'wp-load.php');
require_once( 'feedweb_util.php');

function GetPaymentModulePath()
{
	echo GetFeedwebUrl()."PM/PM.aspx?lang=".GetDefaultLanguage()."&bac=".GetBac(true)."&client=WP:".GetPluginVersion();
}
?>

<div class="wrap">
	<div id="icon-plugins" class="icon32"><br /></div>
	<h2><?php _e("Feedweb License", "FWTD");?></h2><br/>
	<iframe id='FeedwebPaymentModule' style='width: 1100px; height: 600px;' scrolling='no' src='<?php GetPaymentModulePath()?>'></iframe>
</div>
