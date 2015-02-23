<?php
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(dirname(dirname(dirname(__FILE__)))) . '/');

require_once( ABSPATH.'wp-load.php');
require_once( 'feedweb_util.php');

if (!current_user_can('manage_options'))
	wp_die(__("You are not allowed to be here"));
else
	DisplayWidgetIcon();

function DisplayWidgetIcon()
{
	$id = $_GET["id"];
	$pac = $_GET["pac"];
	$data = GetPageData($pac, false);
	if ($data == null)
		return;
	
	if ($data['error'] != null && $data['error'] != "")
	{
		SetSortValue($id, -3);
		$src = GetFeedwebUrl()."IMG/Remove.png";
		if ($data['error'] == "Bad PAC")
		{
			$title = __("The widget data is invalid and cannot be used.", "FWTD");
			echo "<script>function OnInvalidPAC() { if (window.confirm ('".__("Remove Invalid Widget?", "FWTD")."') == true) ".
				"window.location.href='".plugin_dir_url(__FILE__)."/widget_commit.php?feedweb_cmd=REM&wp_post_id=".$id."'; } ".
				"</script><a href='#' onclick='OnInvalidPAC()'><img title='$title' src='$src' style='padding-left: 4px;'/></a>";
			return;
		}
		$title = __("Unknown error.", "FWTD").__("\nPlease contact Feedweb (contact@feedweb.net)", "FWTD");
		echo "<img title='$title' src='$src' style='padding-left: 4px;'/>";
		return;
	}
	
	$src = GetFeedwebUrl()."IMG/Edit.png";
	$votes = $data['votes'];
	$score = $data['score'];
	if ($score != "")
	{
		SetSortValue($id, intval($votes));
		$format = __("Edit / Remove Rating Widget\n(%s Votes. Average Score: %s)", "FWTD");
		$title = sprintf($format, $votes, $score);
		if ($data['image'] != "")
			$src = GetFileUrl($data['image']);
	}
	else
	{
		SetSortValue($id, 0);
		$title = __("Edit / Remove Rating Widget\n(No votes yet)", "FWTD");
	}
	
	$width = 675;
	$height = 360;
	$url = plugin_dir_url(__FILE__)."widget_dialog.php?wp_post_id=".$id."&mode=edit&KeepThis=true&TB_iframe=true&height=$height&width=$width";
	
	$div_class = GetStatusImageClass();
	$image_id = $div_class . "_" . $id;
	echo "<div class='$div_class' style='display: inline;' onmouseover='ShowFeedwebStats($id)' onmouseout='HideFeedwebStats()'>";
	
	$answers = $data['answers'];
	if ($answers != null)
		for ($index = 0; $index < count($answers); $index++)
		{
			$text = str_replace("'", "’", $answers[$index]);
			echo "<input type='hidden' class='FeedwebPostAnswerData' value='$text'/>";
		}
	echo "<input alt='$url' class='thickbox' id='$image_id' title='$title' type='image' src='$src'/></div>";
}
?>