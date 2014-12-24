<?php
/*
Plugin Name: Feedweb
Plugin URI: http://wordpress.org/extend/plugins/feedweb/
Description: Expose your blog to the Feedweb reader's community. Promote your views. Get a comprehensive and detailed feedback from your readers.
Author: Feedweb
Version: 2.4.12
Author URI: http://www.feedweb.net
*/

require_once('feedweb_util.php');
require_once('feedweb_options.php');
require_once(ABSPATH.'wp-admin/includes/plugin.php');

$feedweb_blog_caps = null;
$feedweb_rw_swf = "FL/RatingWidget.swf";

function ContentFilter($content)
{
	$code = GetFeedwebContent();
	if ($code != null)	
		return $content.$code;
	return $content;
}


function GetFeedwebContent()
{
	global $post_ID;
	$pid = get_the_ID($post_ID);
	
	$data = GetFeedwebOptions();
	if ($data["mp_widgets"] == "0")	// Doesn't display on the Home / Front Page
		if (is_front_page() || is_home() || is_feed() || is_category() || is_tag())
			return null;
	
	$pac = GetPac($pid);
	if ($pac == null)
		return null;
				
	if (CheckServiceAvailability() != null)
		return null; 
	
	$width = intval($data["widget_width"]);
	$height = 120;	// Wide layout default
	if (strtolower($data["widget_layout"]) == "mobile")
	{
		$width = 300;
		$height = 150;
	}
		
	$frame_width = $width + 5;
	$frame_height = $height + 5;
	$src = GetFeedwebUrl()."BRW/BlogRatingWidget.aspx?cs=".$data["widget_cs"]."&amp;width=$width&amp;height=$height".
		"&amp;lang=".$data["language"]."&amp;pac=$pac&amp;layout=".$data["widget_layout"];
			
	if ($data["results_before_voting"] == "1")	// Display results before voting
		$src .= "&amp;rbv=true";
			
	if ($data["custom_css"] == "0")
		$src .= "&amp;ext_bg=".$data["widget_ext_bg"];
	else
		$src .= "&amp;custom_css=".$data["custom_css"];
		
	$code = "<iframe class='FeedwebRatingWidget' id='FeedwebRatingWidget_$pid' scrolling='no' src='$src' ".
		"style='width: ".$frame_width."px; height: ".$frame_height."px; border-style: none;'></iframe>";
		
	$url = GetFeedwebUrl()."?pac=$pac";
	$code .= "<a id='FeedwebVerificationLink_$pid' style='display: none;' href='$url'>.</a>";
						
	if($data["copyright_notice_ex"] == "1")
		$code .= GetCopyrightNotice();
	$code .= GetLicenseInfo(null);

	if ($data["add_paragraphs"] == "1")
		return "<p>$code</p>";
 	return $code;
}

function GetCopyrightNotice()
{
	$data = get_plugin_data( __FILE__ );
	$version = $data['Version'];
	
	$text = 
		"<div style='direction:ltr; font-size:7pt; font-family:Verdana; height:30px; display:block; overflow:hidden;".
		"width:380px;position:relative;margin:2px 0 0 0;padding:0;'><span style='display:block; positiion: absolute;".
		"top: 0px; left: 0px; margin: 0; padding-top: 1px;'><a href= 'http://wordpress.org/plugins/feedweb'>Feedweb ".
		"for WordPress</a>. v$version</span>".
		"<iframe src = 'http://www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Ffeedwebresearch".
		"&amp;send=false&amp;layout=button_count&amp;width=25&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;".
		"action=like&amp;height=21&amp;appId=240492672692711' scrolling='no' frameborder='0' allowTransparency='true' ".
		"style='border: none; overflow: hidden; width: 88px; height: 21px; margin: 0; padding: 0; position: absolute; ".
		"left:170px; top:0px;'></iframe>".
		"<div style='position:absolute;left:259px;top:0; display: block;'><a href='https://twitter.com/Feedwebresearch' ".
		"class='twitter-follow-button' data-show-count='false' data-show-screen-name='false'>Follow @Feedwebresearch</a>".
        "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if".
        "(!d.getElementById ( id ) ) { js = d.createElement(s); js.id=id; js.src=p+'://platform.twitter.com/widgets.js';".
        "fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></div></div>";

	return $text;
}

function AddFeedwebColumn($columns) 
{
	// Check if user is admin
	if (current_user_can('manage_options'))
	{
		if (UpdateBlogCapabilities() != null)
		{
			$columns['feedweb'] = "Feedweb";
		
			$feedweb_data = GetFeedwebOptions();
			if ($feedweb_data["widget_type"] == "F")
			{
				$msg = __("Please upgrade your Feedweb widget type to HTML5 (in the Feedweb Plugin settings). The Flash widget support will be discontinued after December 31, 2013", "FWTD");
				ShowFeedwebMessage($msg, false);
			}
		}	
	}
	
	return $columns;
}

function AddFeedwebColumnSort($columns) 
{
	if (current_user_can('manage_options'))
		$columns['feedweb'] = "feedweb";
	
	return $columns;
}

function FeedwebColumnOrderby($vars) 
{
    if ( isset( $vars['orderby'] ) && 'feedweb' == $vars['orderby'] ) 
	{
        $vars = array_merge ( $vars, array('meta_key' => 'feedweb_post_sort_value', 'orderby' => 'meta_value_num') );
    }
    return $vars;
}

function SetSortValue($id, $sort_value)
{
	global $wpdb;
	
	// Get Previous Value
	$query = "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key='feedweb_post_sort_value' AND post_id=$id";
	$old_value = $wpdb->get_var($query);

	if ($old_value == null) // No Previous Value
		$query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) VALUES ($id, 'feedweb_post_sort_value', '$sort_value')";
	else					// Update existing Value
		$query = "UPDATE $wpdb->postmeta SET meta_value='$sort_value' WHERE post_id=$id AND meta_key='feedweb_post_sort_value'";
	
	$wpdb->query($query);
}

function FillFeedwebCell($id)
{
	$width = 675;
	$height = 360;
	
	// First, find out if a wiget has been created already
	$pac = GetPac($id);
	if ($pac == null) // Not created yet - display 'Insert' button
	{
		$status = GetInsertWidgetStatus($id);
		if ($status != null)
		{
			SetSortValue($id, -2);
			$src = GetFeedwebUrl()."IMG/Warning.png";
			echo "<img src='$src' title='$status'/>";
		}
		else 
		{
			SetSortValue($id, -1);
			$src = GetFeedwebUrl()."IMG/Append.png";
			$title = __("Insert Rating Widget", "FWTD");
			$url = plugin_dir_url(__FILE__)."widget_dialog.php?wp_post_id=$id&mode=add&KeepThis=true&TB_iframe=true&height=$height&width=$width";
			echo "<input alt='$url' class='thickbox' title='$title' type='image' src='$src'/>";
		}
	}
	else	// Created - display 'Edit' button
	{
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
}

function GetStatusImageClass()
{
	return "FeedwebPostStatusImage";
}

function FillFeedwebColumn($column_name, $id) 
{
	switch ($column_name) 
	{
		case 'feedweb' :
			FillFeedwebCell($id);
			break;
	}
}

function InitPlugin() 
{
	add_thickbox();
	load_plugin_textdomain( 'FWTD', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

function FeedwebPluginMenu()
{
	add_options_page('Feedweb Plugin Options', 'Feedweb', 'manage_options', basename(__FILE__), 'FeedwebPluginOptions');
}

function FeedwebSettingsLink($links)
{
	$settings_link = "<a href='options-general.php?page=".basename(__FILE__)."'>".__("Settings")."</a>";
	array_unshift($links, $settings_link);
	return $links;
}

function FeederBarCallback($atts)
{
	if (CheckServiceAvailability() != null)
		return "";

	$bac = GetBac(true);
	if ($bac == null)
		return "";
		
	$data = GetFeedwebOptions();
	if ($data == null)
		return "";
		
	$lang = $data["language"];
	$url = GetFeedwebUrl()."FPW/Feeder.aspx?bac=$bac&lang=$lang&mode=H";
	
	$width = 280;
	$height = 550;
	$scrolling = "";
	$color = "#ffffff";
	return "<div style='width: 100%; height: 100%; background-color: $color; text-align: center;'>".
		"<iframe id='FeederFrame' src='$url' style='width: ".$width."px; height: ".$height."px; ".
		"border-style: none;' $scrolling></iframe></div>";
}

function FrontWidgetCallback($atts)
{
	if (CheckServiceAvailability() != null)
		return "";

	$bac = GetBac(true);
	if ($bac == null)
		return "";
		
	$data = GetFeedwebOptions();
	if ($data == null)
		return "";
		
	$color = "#ffffff";
	$lang = $data["language"];
	$url = GetFeedwebUrl()."FPW/FrontWidget.aspx?bac=$bac&amp;lang=$lang";
	switch ($data["front_widget_color_scheme"])
	{
		case 'classic':
			$color = "#405060";
			break;
		case 'monochrome':
			$url = $url."&amp;background_color=ffffff&amp;title_color=000000&amp;content_color=404040&amp;title_highlight=808080&amp;selected_item_color=e0e0e0&amp;border_color=000000";
			break;
		case 'light_blue':
			$url = $url."&amp;background_color=c0c0ff&amp;title_color=000080&amp;content_color=000040&amp;title_highlight=0000ff&amp;selected_item_color=8080ff&amp;border_color=404080";
			break;
		case 'dark_blue':
			$url = $url."&amp;background_color=000040&amp;title_color=ffffff&amp;content_color=c0c0ff&amp;title_highlight=c0ffff&amp;selected_item_color=000060&amp;border_color=000000";
			break;
		case 'dark_green':
			$url = $url."&amp;background_color=004000&amp;title_color=ffffff&amp;content_color=c0ffc0&amp;title_highlight=c0ffff&amp;selected_item_color=006000&amp;border_color=000000";
			break;
	}
	$url .= "&amp;items=".$data["front_widget_items"];
	
	$width = 250;
	
	$scrolling = "";
	if ($data["front_widget_hide_scroll"] == "1") // No scrolling - 
		$scrolling = "scrolling='no'";
	else
		$width = 270;
	return "<div style='width: 100%; height: 100%; background-color: $color; text-align: center;'>".
		"<iframe id='FrontWidgetFrame' src='$url' style='width: ".$width."px; height: ".$data["front_widget_height"]."px; ".
		"border-style: none;' $scrolling></iframe></div>";
}

function ShowFeedwebMessage($message, $errormsg = false)
{
	if ($errormsg) 
		echo '<div id="message" class="error">';
	else 
		echo '<div id="message" class="updated fade">';
	echo "<p><strong>$message</strong></p></div>";
}    

function ShowAdminMessages()
{
     // Only show to admins
    if (current_user_can('manage_options'))
	{
		if (!class_exists('DOMDocument'))
		{
			$msg = __("Your server configuration does not support DOM Document class. Please upgrade your PHP version or report to contact@feedweb.net", "FWTD");
			ShowFeedwebMessage($msg, true);
			return;
		}
		
		$error = CheckServiceAvailability();
		if ($error != null)
		{
			$msg = __("The Feedweb service is temporarily unavailable due to system maintenance", "FWTD");
			if ($error != "")
				$msg .= " (Error: $error)";
			ShowFeedwebMessage($msg, true);
			return;
		}
    }
}

function FeedwebSavePost($pid) 
{
	if (current_user_can('manage_options') == false)
		return;
	
	//verify post is not a revision
	if (wp_is_post_revision($pid))
		return;
		
	//verify post is not trashed
	$status = get_post_status($pid);
	if ($status == 'trash')
		return;
			
	//verify post has a widget
	$pac = GetPac($pid);
	if ($pac == null)
		return;
		
	SetPostStatus($pid, 2); // Set status 2 - check post Title/Url
}


function EnqueueAdminScript() 
{
	?>
	<script type="text/javascript">
		function GetFeedwebPopupInfoClass()
		{
			return "FeedwebPostTablePopupInfoDiv";
		}
		
		function HideFeedwebStats()
		{
			var popups = document.getElementsByClassName(GetFeedwebPopupInfoClass());
			for (var index = 0; index < popups.length; index++)
				popups[index].style.visibility = "hidden";
		}
		
		function ShowFeedwebStats(id)
		{
			var image_class = '<?php echo GetStatusImageClass();?>';
			var image = document.getElementById(image_class + "_" + id);
			var answers = image.parentElement.getElementsByClassName("FeedwebPostAnswerData");
			if (answers.length == 0)
				return;
			
			var container = document.getElementById("wpcontent");
			if (container == null || container == undefined)
				return;
			
			var popup_class = GetFeedwebPopupInfoClass();	
			var popup_id = popup_class + "_" + id;
			var popup = document.getElementById(popup_id);
			if (popup == null || popup == undefined)
			{
				popup = document.createElement("DIV");
				popup.setAttribute("style", "position: absolute; top: 0; left: 0; display: block; visibility: hidden; background-color: #969696; color: white; border: 2px solid #c0c0c0;");
				popup.setAttribute("class", popup_class);
				popup.setAttribute("id", popup_id);
				
				var base = '<?php echo GetFeedwebUrl();?>' + 'IMG/Plugin/';
				var html = "<style>"+
					" ." + popup_class + " table {margin: 4px 0px 4px 0px;}" +
					" ." + popup_class + " td {font-size: 8pt; text-align: center; padding: 0 8px 0 8px; min-width: 24px;}" +
					" ." + popup_class + " img {border: 1px solid white; margin-top: 4px;}" + 
					" </style>";
				
				html += "<table>";
				html += "<tr><td style='text-align: left;'><img src='" + base + "Question.png'/></td>" + 
					"<td><img src='" + base + "Yes.png'/></td>" +
					"<td><img src='" + base + "No.png'/></td></tr>";
					
				for (var index = 0; index < answers.length; index++)
				{
					html += "<tr>";
					var data = answers[index].value.split("|");
					for (var pos = 0; pos < data.length; pos++)
					{
						html += "<td";
						if(pos == 0)
							html += " style='text-align: left;'";
						html += ">" + data[pos] + "</td>";
					}
					html += "</tr>";
				}
				html += "</table>";
				
				popup.innerHTML = html;
				container.appendChild(popup);
			}
		    setTimeout(function () { DisplayFeedwebStats(id) }, 100);
		}
		
		function DisplayFeedwebStats(id)
		{
			var container = document.getElementById("wpcontent");
			var container_rect = container.getBoundingClientRect();
			
			var image_class = '<?php echo GetStatusImageClass();?>';
			var image = document.getElementById(image_class + "_" + id);
			var image_rect = image.getBoundingClientRect();
			
			var popup_class = GetFeedwebPopupInfoClass();	
			var popup = document.getElementById(popup_class + "_" + id);
			
			popup.style.visibility = "visible";
			popup.style.left = (image_rect.left - popup.clientWidth - 8).toString() + "px";
			popup.style.top = (image_rect.top - container_rect.top - popup.clientHeight / 2 + 16).toString() + "px";
		}
	</script>
	<?php
	
	$result = QueryPostStatus();
	if ($result == null)
		return;
		
	$id = $result->post_id;
	switch ($result->meta_value)
	{
		case '0':	// New Post
			$function = "DisplayInsertWidgetPrompt();";
			$title = __("Insert Rating Widget", "FWTD");
			$prompt = __("Do you wish to insert a Feedweb rating widget into your new post?", "FWTD");
			$url = plugin_dir_url(__FILE__)."widget_dialog.php?wp_post_id=$id&mode=add&KeepThis=true&TB_iframe=true";
			break;
			
		case '2':	// Edited Post
			$pac = GetPac($id);
			$post = get_post($id);
			$data = GetPageData($pac, true);
			
			$server_url = $data["url"];
			$server_title = $data["title"];
			$local_url = get_permalink($id);
			$local_title = ConvertHtml($post->post_title);
				
			if ($server_url == $local_url && $server_title == $local_title)
				return;
				
			$prompt = __("The Title or Permalink of the post has been changed. Do you with to update the Rating Widget with new data?", "FWTD");
			$action = "window.location.href='".plugin_dir_url(__FILE__)."widget_commit.php?wp_post_id=$id&feedweb_cmd=NPW';";
			$function = "DisplayUpdateWidgetPrompt();";
			break;
			
		default:
			return;
	}
	
	?>
	<script type="text/javascript">
		var readyStateCheckInterval = setInterval( function() 
		{
			if (document.readyState === "complete") 
			{
				<?php echo $function?>
				clearInterval(readyStateCheckInterval);
			}
		}, 1000);
		
		function DisplayUpdateWidgetPrompt()
		{
			if (window.confirm('<?php echo $prompt ?>') == true)
			{
				<?php echo $action?>
			}
		}
	
		function DisplayInsertWidgetPrompt()
		{
			if (window.confirm('<?php echo $prompt ?>') == true)
			{
				tb_show('<?php echo $title?>', '<?php echo $url?>');
				
				var tb = document.getElementById("TB_window");
				if (tb != null)
				{
					var frames = tb.getElementsByTagName("iframe");
					frames[0].style.height = "400px";
					frames[0].style.width = "700px";
					tb.style.height = "400px";
					tb.style.width = "700px";
				}
			}
		}
	</script>
	<?php
}   


function DeletePostHook($pid)
{
	// Delete Widget Entry
}

function TrashPostHook($pid)
{
	//Set widget 'Invisible' 
	SetPageVisibilityStatus($pid, 0);
}

function TrashedPostHook($pid)
{
}

function PublishPostHook($deprecated = '')
{
	if (current_user_can('manage_options') == false)
		return;
		
	$data = GetFeedwebOptions();
	if ($data == null)
		return;
	
	if ($data["widget_prompt"] != "1")
		return;
	
	// Get current post id (for newly published post)
	global $post_ID;
	$id = get_the_ID($post_ID);
	$pac = GetPac($id);
	if ($pac != null) // Already exists
		return;
		
	SetPostStatus($id, 0);
}

// Checks if the current post status differs from 1. If yes, return it and reset status to 1.
function QueryPostStatus()
{
	global $wpdb;
	$query = "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = 'feedweb_post_status' AND meta_value != '1'";
	$results = $wpdb->get_results($query);
	if ($results == null)
		return null;
		
	foreach($results as $result)
	{
		$id = $result->post_id;
		$query = "UPDATE $wpdb->postmeta SET meta_value='1' WHERE post_id=$id AND meta_key='feedweb_post_status'";
		$wpdb->query($query);
		return $result;
	}
}

function SetPostStatus($id, $new_status)
{
	global $wpdb;
	// Get Previous Status
	$query = "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key='feedweb_post_status' AND post_id=$id";
	$old_status = $wpdb->get_var($query);

	if ($old_status == null) // No previous status
		$query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) VALUES ($id, 'feedweb_post_status', '$new_status')";
	else	// Update existing status
	{
		if ($new_status == 0) // Initial status; Old status MUST NOT exist
			return;
		
		$query = "UPDATE $wpdb->postmeta SET meta_value='$new_status' WHERE post_id=$id AND meta_key='feedweb_post_status'";
	}
	$wpdb->query($query);
}

function AddFeedwebAdminMenu() 
{
	$url = plugin_dir_url(__FILE__)."images/Logo_16x16_Transparent.png";
	
	add_menu_page ( 'Feedweb', 'Feedweb', 'manage_options', 'feedweb/feedweb_menu.php', '', $url );
	add_submenu_page( 'feedweb/feedweb_menu.php', __('Settings'), __('Settings'), 'manage_options', 'feedweb/feedweb_menu.php');
	add_submenu_page( 'feedweb/feedweb_menu.php', __('Tutorial', 'FWTD'), __('Tutorial', 'FWTD'), 'manage_options', 'feedweb/feedweb_help.php');
    if (GetBac(true) != null)
    {
		add_submenu_page( 'feedweb/feedweb_menu.php', __('Monitor', 'FWTD'), __('Monitor', 'FWTD'), 'manage_options', 'feedweb/feedweb_monitor.php');
		add_submenu_page( 'feedweb/feedweb_menu.php', __('License', 'FWTD'), __('License', 'FWTD'), 'manage_options', 'feedweb/feedweb_license.php');
	}
	add_submenu_page( 'feedweb/feedweb_menu.php', __('Our Friends', 'FWTD'), __('Our Friends', 'FWTD'), 'manage_options', 'feedweb/feedweb_friends.php');
}

add_action('init', 'InitPlugin');
add_filter('the_content', 'ContentFilter');

add_filter( 'manage_posts_columns', 'AddFeedwebColumn');
add_action( 'manage_posts_custom_column', 'FillFeedwebColumn', 10, 2 );

add_filter( 'manage_edit-post_sortable_columns', 'AddFeedwebColumnSort');
add_filter( 'request', 'FeedwebColumnOrderby' );

$feedweb_plugin = plugin_basename(__FILE__);
add_action('admin_menu', 'FeedwebPluginMenu');
add_filter("plugin_action_links_$feedweb_plugin", 'FeedwebSettingsLink' );

add_shortcode( 'FeedwebFrontWidget', 'FrontWidgetCallback' );
add_shortcode( 'FeedwebFeederBar', 'FeederBarCallback' );
add_filter('widget_text', 'do_shortcode');

add_action('admin_notices', 'ShowAdminMessages');

add_action('publish_post', 'PublishPostHook');

add_action('delete_post', 'DeletePostHook');
add_action('trashed_post', 'TrashedPostHook');
add_action('trash_post', 'TrashPostHook');

add_action( 'admin_enqueue_scripts', 'EnqueueAdminScript' );
add_action( 'admin_menu', 'AddFeedwebAdminMenu' );
add_action( 'save_post', 'FeedwebSavePost' );


?>