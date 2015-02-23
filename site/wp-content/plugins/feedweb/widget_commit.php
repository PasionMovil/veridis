<?php

if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(dirname(dirname(dirname(__FILE__)))) . '/');

require_once( ABSPATH.'wp-load.php');
require_once( 'feedweb_util.php');

if (!current_user_can('manage_options'))
	wp_die(__("You are not allowed to be here"));

$alert = "";
$cmd = $_GET["feedweb_cmd"];
$post_id = $_GET["wp_post_id"];

switch ($cmd)
{
	case "DEL":
		RemoveWidget(intval($post_id));
		break;
		
	case "UPD":
		UpdateWidget(intval($post_id));
		break;
		
	case "NPW":
		UpdateWidgetTitleAndUrl(intval($post_id));
		break;
		
	case "REM":
		RemovePac(intval($post_id));
		$alert = __("The widget has been removed", "FWTD");
		break;
		
	case "RMW":
		RemoveMultipleWidgets($_GET["wp_post_ids"]);
		break;
		
	default:
		$cmd="INS";
		InsertWidget(intval($post_id));
		break;
}

function GetQuestionParams($params)
{
	$questions = explode ( "|", $_POST["WidgetQuestionsData"]);
	$count = count($questions);
	$params['qn'] = strval($count);

	for ($index = 0; $index < $count; $index++)
	{
		$question = $questions[$index];
		if ($question == "")
			break;
		
		if (substr($question, 0, 1) == "@")
		{
			$question = substr($question, 1);
			$question = PrepareParam($question);
		}
		else
			$question = "{".$question."}";
		$name = 'q'.$index;
		$params[$name] = $question;
	}
	return $params;
}

function FormatQuestionQuery()
{
	$questions = explode ( "|", $_POST["WidgetQuestionsData"]);
	$count = count($questions);
	$query = "&qn=".$count;

	for ($index = 0; $index < $count; $index++)
	{
		$question = $questions[$index];
		if ($question == "")
			break;
		
		if (substr($question, 0, 1) == "@")
		{
			$question = substr($question, 1);
			$question = PrepareParam($question);
		}
		else
			$question = "{".$question."}";
		$query .= "&q".$index."=".$question;
	}
	return $query;
}

function GetPostQueryParams($id, $params)
{
	global $alert;

	$url = PrepareParam($_POST["UrlText"]);
	if ($url == "")
	{
		$alert = __("Error in the Post data", "FWTD");
		return null;
	}
	$params['page'] = $url;
		
	$title = PrepareParam($_POST["TitleText"]);
	if ($title == "")
	{
		$alert = __("Error in the Post data", "FWTD");
		return null;
	}
	$params['title'] = $title;
	
	$lang = $_POST["LanguageText"];
	if ($lang == "")
	{
		$alert = __("Error in the Post data", "FWTD");
		return null;
	}
	$params['lang'] = $lang;
	
	$author = PrepareParam($_POST["AuthorText"]);
	if ($author == "")
	{
		$alert = __("Error in the Post data", "FWTD");
		return null;
	}
	$params['author'] = $author;
	
	$author_id = GetPostAuthorId($id);
	$author_code = GetUserCode($author_id, false);
	if ($author_code == null)
	{
		$alert = __("Error in the User data", "FWTD");
		return null;
	}
	$params['guid'] = $author_code;
	
	$summary = PrepareParam($_POST["SummaryText"]);
	if ($summary != "")
		$params['brief'] = $summary;
	
	$channel_id = $_POST["SelectedChannelId"];
	if ($channel_id != null && $channel_id != "" && $channel_id != "0")
		$params['cnl_id'] = $channel_id;
		
	$categories = PrepareParam($_POST["CategoryText"]);
	if ($categories != "")
		$params['cat'] = $categories;
	
	$tags = PrepareParam($_POST["TagText"]);
	if ($tags != "")
		$params['tag'] = $tags;
		
	$img = PrepareParam($_POST["WidgetImageUrl"]);
	if ($img != "")
		$params['img'] = $img;
		
	$v_data = $_POST["WidgetVisibilityData"];
	if ($v_data == null || $v_data == "")
	{
		$alert = __("Error in the Widget data", "FWTD");
		return null;
	}
	$params['vd'] = $v_data;
	
	$user_data = get_userdata($author_id);
	if ($user_data != null)
	{
		$mail = PrepareParam($user_data->user_email);
		if ($mail != "")
			$params['mail'] = $mail;
	}
	return $params;
}

/*
function GetQueryParams($id)
{
	global $alert;

	$params = "";
	$url = PrepareParam($_POST["UrlText"]);
	if ($url == "")
	{
		$alert = __("Error in the Post data", "FWTD");
		return null;
	}
	$params = "&page=".$url;
		
	$title = PrepareParam($_POST["TitleText"]);
	if ($title == "")
	{
		$alert = __("Error in the Post data", "FWTD");
		return null;
	}
	$params .= "&title=".$title;
	
	$lang = $_POST["LanguageText"];
	if ($lang == "")
	{
		$alert = __("Error in the Post data", "FWTD");
		return null;
	}
	$params .= "&lang=*".$lang;
	
	$author = PrepareParam($_POST["AuthorText"]);
	if ($author == "")
	{
		$alert = __("Error in the Post data", "FWTD");
		return null;
	}
	$params .= "&author=".$author;
	
	$author_id = GetPostAuthorId($id);
	$author_code = GetUserCode($author_id, false);
	if ($author_code == null)
	{
		$alert = __("Error in the User data", "FWTD");
		return null;
	}
	$params .= "&guid=".$author_code;
	
	$summary = PrepareParam($_POST["SummaryText"]);
	if ($summary != "")
		$params .= "&brief=".$summary;
		
	$categories = PrepareParam($_POST["CategoryText"]);
	if ($categories != "")
		$params .= "&cat=".$categories;
	
	$tags = PrepareParam($_POST["TagText"]);
	if ($tags != "")
		$params .= "&tag=".$tags;
		
	$img = PrepareParam($_POST["WidgetImageUrl"]);
	if ($img != "")
		$params .= "&img=".$img;
	
	return $params;
}
*/

function UpdateWidgetTitleAndUrl($id)
{
	global $alert;
	
	$pac = GetPac($id);
	$bac = GetBac(true);
	if ($pac == null || $bac == null)
	{
		$alert = __("Wordpress cannot modify your widget", "FWTD");
		return;
	}
	
	$url = PrepareParam(get_permalink($id));
	if ($url == "")
	{
		$alert = __("Error in the Post data", "FWTD");
		return;
	}
	
	$post = get_post($id);
	$title = PrepareParam(ConvertHtml($post->post_title));
	if ($title == "")
	{
		$alert = __("Error in the Post data", "FWTD");
		return;
	}

	try
	{
		$params = array();
		$params['page'] = $url;
		$params['title'] = $title;
		$query = GetFeedwebUrl()."FBanner.aspx?action=npw&pac=$pac&bac=$bac";
		
		$response = wp_remote_post ($query, array('method' => 'POST', 'timeout' => 300, 'body' => $params));		
		if (is_wp_error ($response))
		{
			$alert = __("Cannot connect Feedweb server", "FWTD");
			return;
		}
		
		$dom = new DOMDocument;
		if ($dom->loadXML($response['body']) == true)
		{
			$el = $dom->documentElement;
			if ($el->tagName == "BANNER")
			{
				$alert = $el->getAttribute("error");
				if ($alert == "")
					$alert = __("The widget Title/Url have been updated", "FWTD");
				return;
			}
		}
		$alert = __("Feedweb service cannot update the widget", "FWTD");
	}
	catch (Exception $e)
	{
		$alert = $e->getMessage();
	}
}

function UpdateWidget($id)
{
	global $alert;
	
	$pac = GetPac($id);
	$bac = GetBac(true);
	if ($pac == null || $bac == null)
	{
		$alert = __("Wordpress cannot modify your widget", "FWTD");
		return;
	}

	$plugin_name = dirname(__FILE__)."/feedweb.php";
	$plugin_data = get_plugin_data($plugin_name);
	$version = $plugin_data['Version'];
		
	try
	{
		$query = GetFeedwebUrl()."FBanner.aspx?action=mpw&client=WP:$version&pac=$pac&bac=$bac";
		
		$params = GetPostQueryParams($id, array());
		if ($params == null)
			return;
		
		if ($_POST["WidgetQuestionsData"] != "")
			$params = GetQuestionParams($params);
		
		//$response = wp_remote_get ($query, array('timeout' => 60));
		$response = wp_remote_post ($query, array('method' => 'POST', 'timeout' => 300, 'body' => $params));		
		if (is_wp_error ($response))
		{
			$alert = __("Cannot connect Feedweb server", "FWTD");
			return;
		}
		
		$dom = new DOMDocument;
		if ($dom->loadXML($response['body']) == true)
		{
			$el = $dom->documentElement;
			if ($el->tagName == "BANNER")
			{
				$alert = $el->getAttribute("error");
				if ($alert == "")
					$alert = __("The widget has been updated", "FWTD");
				return;
			}
		}
		$alert = __("Feedweb service cannot update the widget", "FWTD");
	}
	catch (Exception $e)
	{
		$alert = $e->getMessage();
	}
}

function InsertWidget($id)
{
	$pac = CreatePageWidget($id);
	if ($pac == null)
		return;
	
	global $alert;
	if (InsertPac($pac, $id) == true)
		$alert = __("The widget has been inserted", "FWTD");
	else
		$alert = __("Wordpress cannot insert your widget", "FWTD");
}

function RemoveMultipleWidgets($ids_str)
{
	$count = 0;
	$ids = explode (";", $ids_str);
	foreach ($ids as $id)
		if (RemoveWidget(intval($id)))
			$count++;
	
	if ($count > 0)
	{
		global $alert;	
		$format = __("%d out of %d widgets have been removed", "FWTD");
		$alert = sprintf($format, $count, count($ids));
	}
}

function RemoveWidget($id)
{
	global $alert;
	
	$pac = GetPac($id);
	if ($pac != null)
	{
		RemovePac($id);
		$author_id = GetPostAuthorId($id);
		$gid = GetUserCode($author_id, true);
		if ($gid != null)
		{
			$query = GetFeedwebUrl()."FBanner.aspx?action=rpw&pac=$pac&gid=$gid";
			$response = wp_remote_get ($query, array('timeout' => 30));
			if (is_wp_error ($response))
			{
				$alert = __("Cannot connect Feedweb server", "FWTD");
				return false;
			}
		
			$dom = new DOMDocument;
			if ($dom->loadXML($response['body']) == true)
			{
				$el = $dom->documentElement;
				if ($el->tagName == "BANNER")
					$alert = $el->getAttribute("error");
					
				if ($alert == "")
				{
					$alert = __("The widget has been removed", "FWTD");
					return true;
				}
				return false;
			}
		}
	}
	$alert = __("Wordpress cannot remove your widget", "FWTD");
	return false;
}


function CreatePageWidget($id)
{
	global $alert;

	$plugin_name = dirname(__FILE__)."/feedweb.php";
	$plugin_data = get_plugin_data($plugin_name);
	$version = $plugin_data['Version'];
	$data = GetFeedwebOptions();
		
	try
	{
		$query = GetFeedwebUrl()."FBanner.aspx?action=cpw&client=WP:$version";
		$bac = GetBac(true);
		if ($bac != null)
		    $query = $query."&bac=".$bac;
		
		if ($data["delay"] != "0")
			$query = $query."&delay=".$data["delay"];
		
		$params = GetPostQueryParams($id, array());
		if ($params == null)
			return;
		
		if ($_POST["WidgetQuestionsData"] != "")
			$params = GetQuestionParams($params);
		
		$response = wp_remote_post ($query, array('method' => 'POST', 'timeout' => 300, 'body' => $params));		
		if (is_wp_error ($response))
		{
			$alert = __("Cannot connect Feedweb server", "FWTD");
			return null;
		}
		
		$dom = new DOMDocument;
		if ($dom->loadXML($response['body']) == true)
		{
			$el = $dom->documentElement;
			if ($el->tagName == "BANNER")
			{
				$pac = $el->getAttribute("pac");
				if ($pac != "")
					return $pac;
				
				$alert = $el->getAttribute("error");
				if ($alert == "")
					$alert = __("Unknown server error", "FWTD");
				return null;
			}
		}
		$alert = __("Feedweb service cannot create a widget", "FWTD");
	}
	catch (Exception $e)
	{
		$alert = $e->getMessage();
	}
	return null;
}

function DisplayAlert($message)
{
	if ($message != null)
		echo "alert('$message');";
}
?>

<html>
	<head>
		<script language="javascript" type="text/javascript">
			function OnInit()
			{
				<?php
				DisplayAlert($alert);
				//echo "alert('$cmd');";
				switch ($cmd)
				{
					case "REM":
						echo "window.location.href='".get_admin_url()."edit.php'";
						break;
						
					case "RMW":
						echo "window.location.href='".get_admin_url()."options-general.php?page=feedweb.php'";
						break;
						
					case "NPW":
						echo "window.location.href='".get_admin_url()."post.php?post=$post_id&action=edit'";
						break;
						
					default:
						echo "window.parent.tb_remove();";
						echo "window.parent.location.href = window.parent.location.href;";
						break;
				}
				?>
			}
		</script>
	</head>
	<body onload="OnInit()">
	</body>
</html> 
