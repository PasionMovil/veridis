<?php 

if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(dirname(dirname(dirname(__FILE__)))) . '/');
require_once( ABSPATH.'wp-load.php');

function PrepareParam($param)
{
	if (strlen ($param) == 0)
		return "";

	$param = base64_encode(stripcslashes($param));
	$param = str_replace("+", "-", $param);
	$param = str_replace("=", "~", $param);
	$param = str_replace("/", "_", $param);
	return $param;
}

function ConvertHtml($str)
{
	return htmlspecialchars($str, ENT_QUOTES|ENT_HTML5|ENT_IGNORE);
}

function GetPostId()
{
	return $_GET["wp_post_id"];
}

function GetPostCode($id)
{
	$post = get_post($id);
	$url = PrepareParam(get_permalink($id));
	$name = PrepareParam($post->post_title);
	$user = PrepareParam(get_userdata($post->post_author)->display_name);
	
	if ($url != "" && $name != "" && $user != "")
		return $url."|".$name."|".$user;
	return null;
}

function GetPostAuthorId($id)
{
	$post = get_post($id);
	return $post->post_author;
}


function InsertPac($pac, $id)
{
	global $wpdb;
	$query = "SELECT COUNT(*) FROM $wpdb->postmeta WHERE post_id=$id AND meta_key='feedweb_pac'";
	$count = $wpdb->get_var($query);
	if ($count > 0)
		return false;
	
	$query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) VALUES ($id, 'feedweb_pac', '$pac')";
	$wpdb->query($query);
	return true;
}

function RemovePac($id)
{
	global $wpdb;
	$query = "DELETE FROM $wpdb->postmeta WHERE post_id=$id AND meta_key='feedweb_pac'";
	$result = $wpdb->query($query);
}


function GetPac($id)
{
	global $wpdb;
	$query = "SELECT meta_value FROM $wpdb->postmeta WHERE post_id=$id AND meta_key='feedweb_pac'";
	return $wpdb->get_var($query);
}

function GetBac($must_exist)
{
	// First, try to get option...
	$bac = get_option("_feedweb_plugin_bac");
	if ($bac != null && $bac != false)
		return $bac;
	
	// Get it old way (from postmeta)	
	global $wpdb;
	$query = "SELECT meta_value FROM $wpdb->postmeta WHERE post_id=0 AND meta_key='feedweb_bac'";
	$bac = $wpdb->get_var($query);
	if ($bac != null)
	{
		// Save it as an option
		add_option("_feedweb_plugin_bac", $bac);	
		return $bac;
	}
	
    if ($must_exist)
		return null;
	
    // Register Site Domain command
	$root = PrepareParam(get_option('siteurl'));
	$query = GetFeedwebUrl()."FBanner.aspx?action=rsd&root=$root";
	
	$user_id = wp_get_current_user()->ID;
	$user_data = get_userdata($user_id);
	if ($user_data != null)
	{
		$name = null;
		$mail = PrepareParam($user_data->user_email);
		if ($user_data->display_name != "")
			$name = PrepareParam($user_data->display_name);
		else
			if ($user_data->nickname != "")
				$name = PrepareParam($user_data->nickname);
				
		if ($name != null)
			$query .= "&name=$name&mail=$mail"; 
	}
	
	$response = wp_remote_get ($query, array('timeout' => 30));
	if (is_wp_error ($response))
		return null;
	
	$dom = new DOMDocument;
	if ($dom->loadXML($response['body']) == true)
		if ($dom->documentElement->tagName == "BANNER")
			$code = $dom->documentElement->getAttribute("bac");
	
	if ($code == null || $code == "")	
		return null;
	
	add_option("_feedweb_plugin_bac", $code);	
	return $code;
}

function SetSingleFeedwebOption($key, $value)
{
	update_option("_feedweb_plugin_$key", $value);
}

function GetSingleFeedwebOption($key)
{
	$value = get_option("_feedweb_plugin_$key");
	if ($value == null || $value == false)
	{
		global $wpdb;
		
		$query = "SELECT meta_value FROM $wpdb->usermeta WHERE meta_key='feedweb_$key'";
		$value = $wpdb->get_var($query);
		if ($value != null)
			add_option("_feedweb_plugin_$key", $value);
	}
	return $value;
}

function SetFeedwebOptions($data)
{
 	update_option("_feedweb_plugin_options", $data);
	return true;
}

function GetFeedwebOptions()
{
	// Set default options
	$data = array(
		"language" => "en", 
		"mp_widgets" => "0", 
		"widget_width" => "400",
		"widget_layout" => "mobile",
		"results_before_voting" => "0", 
		"delay" => "0", 
		"copyright_notice_ex" => "0", 
		"widget_prompt" => "1", 
		"add_paragraphs" => "0",
		"front_widget_color_scheme" => "classic", 
		"front_widget_height" => "400", 
		"front_widget_hide_scroll" => "0", 
		"widget_type" => "H", 
		"widget_cs" => "modern",
		"custom_css" => "0",
		"widget_ext_bg" => "FFFFFF" );
		
	$values = get_option("_feedweb_plugin_options");
	if ($values != null && $values != false)
	{
		// override default options with retrieved ones
		foreach ($values as $key => $value) 
		{
			$data[$key] = $value;
			if ($key == "language")
				$data["language_set"] = true;
		}
		return $data;		
	}
	
	global $wpdb;
	$query = "SELECT meta_key, meta_value FROM $wpdb->usermeta WHERE meta_key LIKE 'feedweb%%'";
	$rows = $wpdb->get_results($query);
	foreach ( $rows as $row )
	{
		$key = substr($row->meta_key, 8);
		if ($key != false && $key != "")
		{
			$data[$key] = $row->meta_value;
			if ($key == "language")
				$data["language_set"] = true;
		}
	}
	add_option("_feedweb_plugin_options", $data);
	return $data;
}

function GetFeedwebOptionsCount()
{
 	$data = get_option("_feedweb_plugin_options");
 	if ($data == false || $data == null)
	{
		global $wpdb;
		$query = "SELECT COUNT(meta_value) FROM $wpdb->usermeta WHERE meta_key LIKE 'feedweb%%'";
		return $wpdb->get_var($query);
	}
	return count($data);
}


function GetUserCode($id, $must_exist)
{
	global $wpdb;
	$query = "SELECT meta_value FROM $wpdb->usermeta WHERE user_id=$id AND meta_key='user_code_feedweb'";
	$code = $wpdb->get_var($query);
	if ($code != null)
		return $code;
		
	if ($must_exist)
		return null;
	
	$query = GetFeedwebUrl()."FBanner.aspx?action=get&mode=temp";
	$response = wp_remote_get ($query, array('timeout' => 30));
	if (is_wp_error ($response))
		return null;
	
	$dom = new DOMDocument;
	if ($dom->loadXML($response['body']) == true)
		if ($dom->documentElement->tagName == "BANNER")
			$code = $dom->documentElement->getAttribute("newgid");
	
	if ($code == null || $code == "")	
		return null;
	
	$query = "INSERT INTO $wpdb->usermeta (user_id, meta_key, meta_value) VALUES ($id, 'user_code_feedweb', '$code')";
	$result = $wpdb->query($query);
	if ($result == false)
		return null;
	
	return $code;
}



function GetPostAge($id)
{
	if (phpversion() < "5.3")
		return 0;
	
	try
	{
		$post = get_post($id);
		$now = new DateTime("now");
		$date = new DateTime($post->post_date);
		$interval = $date->diff($now);
		if ($interval->invert == 1)
			return -$interval->days;
		return $interval->days;
	}
	catch(Exception $e)
	{
	}
	return 0;
}

function GetMaxPostAge()
{
	return 30;
}

function IsRTL($language)
{
	if ($language == 'he' || $language == 'ar')
		return true;
	return false;
}

function GetFeedwebUrl()
{
	return "http://www.feedweb.net/";
}

function GetFileUrl($file)
{
	// Return if already absolute URL
    if(parse_url($file, PHP_URL_SCHEME) != '') 
		return $file;		
	return GetFeedwebUrl().$file;
}

function ReadAnswerList($root)
{
	$list = $root->getElementsByTagName("ANSWERS");
	if ($list->length == 0)
		return null;
		
	$answers = array();
	$list = $list->item(0)->getElementsByTagName("Q");
	for ($item = 0; $item < $list->length; $item++)
	{
		$answer = $list->item($item);
		$title = $answer->getAttribute("title");
		$yes = $answer->getAttribute("yes");
		$no = $answer->getAttribute("no");
		
		$answers[$item] = $title."|".$yes."|".$no;
	}
	return $answers;
}

function ReadChannelList($root)
{
	$list = $root->getElementsByTagName("CHANNELS");
	if ($list->length == 0)
		return null;
	
	$channels = array();
	$channel_root = $list->item(0);
	$list = $channel_root->getElementsByTagName("C");
	for ($item = 0; $item < $list->length; $item++)
	{
		$channel = $list->item($item);
		$id = $channel->getAttribute("id");
		$code = $channel->getAttribute("code");
		$desc = $channel->getAttribute("desc");
		$title = $channel->getAttribute("title");
		
		$channels[$id] = array('code' => $code, 'title' => $title, 'desc' => $desc);
	}
	
	$data = array();
	$data['list'] = $channels;
	$data['default'] = $channel_root->getAttribute("default");
	return $data;
}

function ReadQuestionList($root)
{
	$list = $root->getElementsByTagName("QUESTIONS");
	if ($list->length == 0)
		return null;
		
	$questions = array();
	$list = $list->item(0)->getElementsByTagName("Q");
	for ($item = 0; $item < $list->length; $item++)
	{
		$question = $list->item($item);
		$id = $question->getAttribute("id");
		$text = $question->getAttribute("text");
		$index = $question->getAttribute("index");
		
		if ($index != null && $index != "")
			$questions[$index] = array($id, $text);
		else
			$questions[$id] = $text;
	}
	return $questions;
}

function Str2Bool($str)
{
	return $str == "True";
}

function GetPageData($pac, $info_mode)
{
	$query = GetFeedwebUrl()."FBanner.aspx?action=gpd&icon=edit&pac=".$pac;
	if ($info_mode == true)
		$query .= "&mode=info";
		
    $bac = GetBac(true);
    if ($bac != null)
		$query = $query."&bac=".$bac;
		
	$response = wp_remote_get ($query, array('timeout' => 30));
	if (is_wp_error ($response))
		return null;
	
	$dom = new DOMDocument;
	if ($dom->loadXML($response['body']) == true)
		if ($dom->documentElement->tagName == "BANNER")
		{
			$data['id'] = $dom->documentElement->getAttribute("id");
			$data['error'] = $dom->documentElement->getAttribute("error");
			if ($info_mode == true)
			{
				$data['url'] = $dom->documentElement->getAttribute("url");
				$data['lang'] = $dom->documentElement->getAttribute("lang");
				$data['title'] = $dom->documentElement->getAttribute("title");
				$data['author'] = $dom->documentElement->getAttribute("author");
				$data['author_id'] = $dom->documentElement->getAttribute("aid");
				
				$data['visible'] = Str2Bool($dom->documentElement->getAttribute("visible"));
				if ($data['visible'] == "true")
				{
					$data['img'] = $dom->documentElement->getAttribute("img");
					$data['tags'] = $dom->documentElement->getAttribute("tags");
					$data['brief'] = $dom->documentElement->getAttribute("brief");
					$data['cnl_id'] = $dom->documentElement->getAttribute("cnl_id");
					$data['categories'] = $dom->documentElement->getAttribute("categories");
					$data['censorship'] = $dom->documentElement->getAttribute("censorship");
					$data['ad_content'] = Str2Bool($dom->documentElement->getAttribute("ad_content"));
				}
				$data['questions'] = ReadQuestionList($dom->documentElement);
			}
			else	// Votes / Score / Image / Answers
			{
				$data['image'] = $dom->documentElement->getAttribute("image");
				$data['votes'] = $dom->documentElement->getAttribute("votes");
				$data['score'] = $dom->documentElement->getAttribute("score");
				$data['answers'] = ReadAnswerList($dom->documentElement);
			}
			return $data;
		}
	return null;
}

function GetLanguageList($all)
{
	$query = GetFeedwebUrl()."FBanner.aspx?action=gll";
	if ($all == true)
		$query .= "&all=true";
		
	$response = wp_remote_get ($query, array('timeout' => 30));
	if (is_wp_error ($response))
		return null;
	
	$dom = new DOMDocument;
    if ($dom->loadXML($response['body']) == true)
		if ($dom->documentElement->tagName == "BANNER")
		{
		    $root = $dom->documentElement->getElementsByTagName("LANGUAGES");
		    if ($root != null && $root->length > 0)
			{
				$list = $root->item(0);
				if ($list->childNodes != null && $list->childNodes->length > 0)
				{
				    $languages = array();
					foreach($list->childNodes as $item)
				    {
						$code = $item->getAttribute("code");
						$text = $item->getAttribute("text");
						$name = $item->getAttribute("name");
						if ($name != $text)
						    $name .= " - ".$text; 
						$languages[$code] = $name;
				    }
					return $languages;
				}
			}
		}
	return null;
}


function UpdateBlogCapabilities()
{
    if (current_user_can('manage_options') == false) // Must be admin
		return null;

    $bac = GetBac(false);
	if ($bac == null)
		return null;
	
	$params = array();
	$params['bac'] = $bac;
	$params['title'] = PrepareParam(get_bloginfo('name'));
	
	// Request blog caps by Blog Access Code
	$query = GetFeedwebUrl()."FBanner.aspx?action=cap";
	$response = wp_remote_post ($query, array('method' => 'POST', 'timeout' => 30, 'body' => $params));
	//$response = wp_remote_get ($query, array('timeout' => 30));
	if (is_wp_error ($response))
		return null;
	
	$dom = new DOMDocument;
	if ($dom->loadXML($response['body']) == true)
		if ($dom->documentElement->tagName == "BANNER")
		{
			$license = $dom->documentElement->getAttribute("license");
			if ($license != null && $license != "")
				SetSingleFeedwebOption("license", $license);
			
			global $feedweb_blog_caps;
		    $feedweb_blog_caps = array();
		    $caps = $dom->documentElement->getElementsByTagName("CAP");
		    
			foreach ($caps as $cap)
			{
				$name = $cap->getAttribute("name");
				$used = intval($cap->getAttribute("used"));
				$limit = intval($cap->getAttribute("limit"));
								
				$value = array();
				$value["used"] = $used;
				$value["limit"] = $limit;
				$feedweb_blog_caps[$name] = $value;
			}
			return $license;
		}
}

function GetInsertWidgetStatus($id)
{
	$status = get_post_status($id);
	if ($status == false)
		return __("Cannot insert widget into a post with undefined status", "FWTD");
	
	if ($status == 'private')
		return __("Cannot insert widget into a private post (must be public)", "FWTD");
		
	if ($status == 'trash')
		return __("Cannot insert widget into a trashed post", "FWTD");
		
	if (post_password_required($id) == true)
		return __("Cannot insert widget into a password-protected post", "FWTD");
	
	$days = GetPostAge($id);
	if ($days > GetMaxPostAge()) 
	{
		$format = __("Cannot insert widget into a post published %d days ago", "FWTD");
		return sprintf($format, $days);
	}
	
	if ($days < 0)
		return __("Cannot insert widget into a scheduled post", "FWTD");

	global $feedweb_blog_caps;
	$cap = $feedweb_blog_caps["DW"];
	if ($cap["limit"] >= 0)
		if ($cap["used"] >= $cap["limit"]) 
		{
			$format = __("You have created %d widgets in the last 24 hours. The daily limit for your subscription is %d new widgets.", "FWTD");
			return sprintf($format, $cap["used"], $cap["limit"]);
		}

	$cap = $feedweb_blog_caps["MW"];
	if ($cap["limit"] >= 0)
		if ($cap["used"] >= $cap["limit"]) 
		{
			$format = __("You have created %d widgets in the last 30 days. The monthly limit for your subscription is %d new widgets", "FWTD");
			return sprintf($format, $cap["used"], $cap["limit"]);
		}

	$cap = $feedweb_blog_caps["TW"];
	if ($cap["limit"] >= 0)
		if ($cap["used"] >= $cap["limit"]) 
		{
			$format = __("You have created %d widgets. The limit for your subscription is %d widgets", "FWTD");
			return sprintf($format, $cap["used"], $cap["limit"]);
		}

	return null;
}

function GetPluginVersion()
{
	$plugin_name = dirname(__FILE__)."/feedweb.php";
	$plugin_data = get_plugin_data($plugin_name);
	return $plugin_data['Version'];
}

function GetLicenseInfo($remark)
{
	$license = GetSingleFeedwebOption("license");
	if ($license == null || $license == "")
		$license = "*";
		
	$val = $license.";".GetPluginVersion();
	if ($remark != null)
		$val .= ";".$remark;
	return "<input name='FeedwebLicenseInfo' id='FeedwebLicenseInfo' type='hidden' value='$val'/>";
}


// 1 	- Service was available within last 30 minutes - don't check again
// -1 	- Service was unavailable within last 15 minutes - don't check again
// 0	- Status is absent / outdated. Check service availability now
function GetServiceLastAccess()
{
	global $wpdb;
	$query = "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=0".
		" AND (meta_key='feedweb_last_access' OR meta_key='feedweb_last_fail')";
	
	$results = $wpdb->get_results($query);
	if ($results == null)
		return 0;
		
	$current = time();
	foreach($results as $result)
	{
		$previous = intval($result->meta_value);
		
		if ($result->meta_key == "feedweb_last_access")
			if (time() - $previous < 1800) 	// 30 min * 60 sec
				return 1;
		
		if ($result->meta_key == "feedweb_last_fail")
			if (time() - $previous < 900)	// 15 min * 60 sec
				return -1;
	}
	return 0;
}

function SetServiceLastAccess($status)
{
	global $wpdb;
	$query = "DELETE FROM $wpdb->postmeta WHERE post_id=0 AND (meta_key='feedweb_last_access' OR meta_key='feedweb_last_fail')";
	$wpdb->query($query);
	
	$key = ($status == true ? "feedweb_last_access" : "feedweb_last_fail");
	$query = sprintf("INSERT INTO %s(post_id, meta_key, meta_value) VALUES (0, '%s', '%d')", $wpdb->postmeta, $key, time());
	$wpdb->query($query);
}

function CheckServiceAvailability()
{
 	$status = GetServiceLastAccess();
	if ($status != 0) // Either true or false
		if ($status > 0)
			return null;
		else
			return "Not available. Check later";

	$query = GetFeedwebUrl()."FBanner.aspx?action=ping";
	$response = wp_remote_get ($query, array('timeout' => 20));
	if (is_wp_error ($response))
	{
		SetServiceLastAccess(false);
		return $response->get_error_message();
	}
	
	$dom = new DOMDocument;
	if ($dom->loadXML($response['body']) == true)
		if ($dom->documentElement->tagName == "BANNER")
		{
			$error = $dom->documentElement->getAttribute("error");
			if ($error != null && $error != "")
			{
				SetServiceLastAccess(false);
				return $error;
			}
		 	
		 	SetServiceLastAccess(true);
			return null;
		}
			
	SetServiceLastAccess(false);
	return "Unknown Error";
}

function SetPageVisibilityStatus($id, $visible)
{
	$pac = GetPac($id);
	if ($pac == null)
		return false;
		
	$bac = GetBac(true);
	if ($bac == null)
		return false;
		
	$query = GetFeedwebUrl()."FBanner.aspx?action=spv&pac=$pac&bac=$bac&vs=$visible";
	$response = wp_remote_get ($query, array('timeout' => 30));
	if (is_wp_error ($response))
		return false;
	
	return true;
}

function GetOrfanedIDs()
{
	global $wpdb;
	$query = "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='feedweb_pac' AND post_id NOT IN".
		" (SELECT DISTINCT ID FROM $wpdb->posts where post_type='post' and post_status='publish')";
	return $wpdb->get_col($query);
}

function WriteDebugLog($text)
{
    global $wpdb;
	$query = "INSERT INTO debug_log(log_time, log_text) VALUES(NOW(), '$text')";
	$wpdb->query($query);
}

function GetQuestionLengthLimit()
{
	return 64;
}

function GetQuestionCountLimit() 
{
	global $feedweb_blog_caps;
	
	if (UpdateBlogCapabilities() != null)
	{
		$cap = $feedweb_blog_caps["PQ"];
		return $cap["limit"];
	} 
	return -1;
}

function GetDefaultLanguage() 
{
	$lang = get_locale();
	$pos = strpos($lang, "_");
	if ($pos > 0)
		$lang = substr($lang, 0, $pos);
	return $lang;
}
?>