<?php
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(dirname(dirname(dirname(__FILE__)))) . '/');

require_once( ABSPATH.'wp-load.php');
require_once( 'feedweb_util.php');

function GetMonitorPath()
{
	return GetFeedwebUrl()."MR/Monitor.aspx?lang=".GetDefaultLanguage()."&bac=".GetBac(true)."&client=WP:".GetPluginVersion();
}
?>

<div class="wrap">
	<div id="icon-plugins" class="icon32"><br /></div>
	<h2><?php _e("Feedweb Monitor", "FWTD");?>*</h2><br/>
	<span style='color: #A04000'><b><i>*A limited beta preview. Please report any errors / problems to </i><a href="mailto://contact@feedweb.net">contact@feedweb.net</a>. Time is according to GMT.</b>
	<br>Page Views are properly recorded after December 15, 2013</span><br/><br/>
	<iframe id='FeedwebMonitor' style='width: 1100px; height: 600px;' scrolling='no' src='<?php echo GetMonitorPath();?>'></iframe>
</div>
