<?php
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(dirname(dirname(dirname(__FILE__)))) . '/');
require_once( ABSPATH.'wp-load.php');
include_once( ABSPATH.'wp-admin/includes/plugin.php' );


function BuildLayoutBox($layout)
{
	$layouts = array("mobile" => __("Mobile Screen", "FWTD"), "wide" => __("Wide Screen", "FWTD"));		
		
	echo "<select id='WidgetLayoutBox' name='WidgetLayoutBox' onchange='OnChangeLayout()'>";
	foreach ($layouts as $key => $value)
	{
		echo "<option";
		if ($key == $layout)
			echo " selected='selected'";
		echo " value='".$key."'>".$value."</option>";
	}
	echo "</select>";
}


function BuildLanguageBox($language, $language_set, $all)
{
	echo "<select id='WidgetLanguageBox' name='WidgetLanguageBox' onchange='OnChangeLanguage()'>";
    
	$languages = GetLanguageList($all);
    if ($language_set != true || $language == null) // Language was not set yet by the admin. Try to set by default locale
	{
		$locale = get_locale();
		$pos = strpos($locale, "_");
		if ($pos > 0)
		    $locale = substr($locale, 0, $pos);
			
		if (array_key_exists($locale, $languages) == true)
		    $language = $locale;
	}
	
	if ($languages != null)
		foreach ($languages as $key => $value)
		{
			echo "<option";
			if ($key == $language)
				echo " selected='selected'";
			echo " value='".$key."'>".$value."</option>";
		}
	
	echo "</select>";
	return $language;
}

function BuildItemCountBox($number)
{
	echo "<select id='ItemCountBox' name='ItemCountBox' onchange='OnChangeItemCount()'>";
	for ($value = 3; $value <= 10; $value++)
	{
		echo "<option";
		if ($value == $number)
			echo " selected='selected'";
		echo " value='$value'>$value</option>";
	}
	echo "</select>";
}

function BuildColorSchemeBox($scheme, $is_rating_widget)
{
	if ($is_rating_widget)
	{
		echo "<select id='RatingWidgetColorSchemeBox' name='RatingWidgetColorSchemeBox' onchange='OnChangeRatingWidgetColorScheme()'>";
		$values = array("blue" => __("Blue", "FWTD"), "gray" => __("Gray", "FWTD"), "modern" => __("Modern", "FWTD"));
	}
	else
	{
		echo "<select id='FrontWidgetColorSchemeBox' name='FrontWidgetColorSchemeBox' style='width: 99%;' onchange='OnChangeFrontWidgetColorScheme()'>";
		$values = array("classic" => __("Classic", "FWTD"), "monochrome" => __("Monochrome", "FWTD"), "light_blue" => __("Light Blue", "FWTD"), 
			"dark_blue" => __("Dark Blue", "FWTD"), "dark_green" => __("Dark Green", "FWTD"));
	}
			
	foreach ($values as $key => $value)
	{
		echo "<option";
		if ($key == $scheme)
			echo " selected='selected'";
		echo " value='".$key."'>".$value."</option>";
	}
	echo "</select>";
}

function BuildRunTimeoutBox($timeout)
{
	echo "<select id='FeederRunTimeoutBox' name='FeederRunTimeoutBox' style='width: 145px;' onchange='OnChangeFeederRunTimeout()'>";
	$values = array("1000" => __("1 sec.", "FWTD"), "1500" => __("1½ sec.", "FWTD"), "2000" => __("2 sec.", "FWTD"), 
		"2500" => __("2½ sec.", "FWTD"), "3000" => __("3 sec.", "FWTD"), "5000" => __("5 sec.", "FWTD"), 
		"7000" => __("7 sec.", "FWTD"), "10000" => __("10 sec.", "FWTD"));
			
	foreach ($values as $key => $value)
	{
		echo "<option";
		if ($key == $timeout)
			echo " selected='selected'";
		echo " value='".$key."'>".$value."</option>";
	}
	echo "</select>";
}

function BuildDateFormatBox($timeout)
{
	echo "<select id='FeederDateFormatBox' name='FeederDateFormatBox' style='width: 145px;' onchange='OnChangeFeederDateFormat()'>";
	$values = array("0" => "Full date", "1" => "Short (Relative)");
			
	foreach ($values as $key => $value)
	{
		echo "<option";
		if ($key == $timeout)
			echo " selected='selected'";
		echo " value='".$key."'>".$value."</option>";
	}
	echo "</select>";
}

function BuildExternalBackgroundControl($color)
{
	echo "<input id='ExternalBackgroundBox' name='ExternalBackgroundBox' class='color' value='$color'>";
	BuildResetPreviewButton('ExternalBackgroundResetButton');
}

function BuildResetPreviewButton($id)
{
	$title = __("Reset Preview", "FWTD");
	$button_url = plugin_dir_url(__FILE__)."images/refresh.png";
	echo "<img id='$id' src='$button_url' title='$title' onclick='ResetWidgetPreview()'/>";
}

function BuildDelayBox($delay)
{
	$values = array("0"=>__("No Delay", "FWTD"), "1"=>__("1 Hour", "FWTD"), "2"=>__("2 Hours", "FWTD"), "5"=>__("5 Hours", "FWTD"));

	echo "<select id='DelayResultsBox' name='DelayResultsBox' onchange='OnChangeDelay()'>";
	foreach ($values as $key => $value)
	{
		echo "<option";
		if ($key == $delay)
			echo " selected='selected'";
		echo " value='".$key."'>".$value."</option>";
	}
	echo "</select>";
}

function GetPurgeInactiveWidgets()
{
	$ids = GetOrfanedIDs();
	if ($ids == null)
		return;
	if (count($ids) == 0)
		return;
			
	echo "<input id='InactiveWidgetIds' type='hidden' value='";
	$first = true;
	foreach ($ids as $id)
	{
		if ($first == true)
			$first = false;
		else
			echo ";";
		echo $id;
	}
	echo "'/><input id='PurgeInactiveWidgetsButton' type='button' onclick='OnPurgeInactiveWidgets()' value='".__("Remove Inactive Widgets", "FWTD")."' ".
		"title='".__("Click to remove widgets from the deleted posts", "FWTD")."' />";
}

function GetCSSText()
{
	$feedweb_data = GetFeedwebOptions();
	$cs = $feedweb_data["widget_cs"];
	$bac = GetBac(true);
			
	$query = GetFeedwebUrl().'FBanner.aspx?action=get-css&cs='.$cs.'&bac='.$bac;
	$response = wp_remote_get ($query, array('timeout' => 30));
	if (is_wp_error ($response))
		return null;
	
	$dom = new DOMDocument;
	if ($dom->loadXML($response['body']) == true)
		if ($dom->documentElement->tagName == "BANNER")
		{
			$error = $dom->documentElement->getAttribute("error");
			if ($error != null && $error != "")
				return null;
			
			$css = array();
			$css['valid'] = $dom->documentElement->getAttribute("valid");
			$css['type'] = $dom->documentElement->getAttribute("type");
			$css['text'] = $dom->documentElement->textContent;
			return $css;
		}
	
	return null;
}

function BuildCSSEditor()
{
	$title = __("Close");
	$button_url = plugin_dir_url(__FILE__)."images/Cancel.png";
	echo "<img id='CloseCSSEditorButton' src='$button_url' title='$title' onclick='CloseCSSEditor()'/>";
	
	$title = __("Edit Rating Widget CSS", "FWTD");
	echo "<span id='CSSEditorTitle'>".$title."</span>";

	$css = GetCSSText();
	if ($css == null)
		echo "<span id='CSSEditorError'>".__("Error loading Widget CSS", "FWTD")."</span>";
	else
	{
		$title = __("Restore Default", "FWTD");
		echo "<input type='submit' id='RestoreCSSButton' class='button button-primary' onclick='OnRestoreCSS()' value='$title'/>";
		
		$title = __("Save"); 	
		echo "<input type='submit' id='SaveCSSButton' class='button button-primary' onclick='OnSaveCSS()' value='$title'/>";
			
		echo "<textarea id='CSSTextEditor' name='CSSTextEditor'>".$css['text']."</textarea>";
		echo "<textarea id='OriginalCSSText'>".$css['text']."</textarea>";
		echo "<input type='hidden' id='CSSCommandValue' name='CSSCommandValue' value=''/>";
		echo "<input type='hidden' id='CSSContentType' name='CSSContentType' value='".$css['type']."'/>";
		
		if ($css['valid'] != "true")
			echo "<script>setTimeout(function () { ShowCSSValidityPrompt() }, 1000);</script>";
	}
}

function FeedwebPluginOptions()
{
	if (!current_user_can("manage_options"))
		wp_die( __("You do not have sufficient permissions to access this page.") );
	
	// Read options 
	$feedweb_data = GetFeedwebOptions();
	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br /></div>
		<h2><?php _e("Feedweb Plugin Settings", "FWTD");?></h2>

		<form name="FeedwebSettingsForm" id="FeedwebSettingsForm" onsubmit="return OnSubmitFeedwebSettingsForm();">
			<link href='<?php echo plugin_dir_url(__FILE__)?>Feedweb.css?v=3.0.3' rel='stylesheet' type='text/css' />
			<?php
				$script_url = GetFeedwebUrl()."Base/jscolor/jscolor.js";
				echo "<script type='text/javascript' src='$script_url'></script>";
			?>
			
			<script type="text/javascript">
				function OnEditCSS()
				{
					document.getElementById("CSSEditorDiv").style.visibility = "visible";
					document.getElementById("SettingsTable").style.visibility = "hidden";
				}
				
				function OnRestoreCSS()
				{
					document.getElementById("CSSCommandValue").value = "R";
				}
				
				function OnSaveCSS()
				{
					document.getElementById("CSSCommandValue").value = "S";
				}
				
				function CloseCSSEditor()
				{
					var original = document.getElementById("OriginalCSSText");
					var text = document.getElementById("CSSTextEditor");
					if (original.value != text.value)
						if (confirm('<?php _e("Discard changes?", "FWTD") ?>') == true)
							text.value = original.value;
						else
							return;
					
					document.getElementById("CSSEditorDiv").style.visibility = "hidden"; 
					document.getElementById("SettingsTable").style.visibility = "visible";
				}
				
				function ShowCSSValidityPrompt()
				{
					var prompt = document.getElementById("CustomCSSValidityPrompt");
					if (prompt == null || prompt == undefined)
						return;
					prompt.style.visibility = "visible";
				}
			
				function OnShowWidgetPreview()
				{
					var settings = document.getElementsByClassName("FeedwebSettingsDiv");
					var title = document.getElementById("WidgetPreviewTitle");
					var row = document.getElementById("WidgetPreviewRow");
					var div = document.getElementById("WidgetPreview");
					if (div.style.display == "block") // Hide
					{
						title.innerHTML = "<?php _e("Show Widget Preview >>>", "FWTD") ?>";
						settings[0].style.height = "550px";
						div.style.display = "none";
						row.style.height = "35px";
					}
					else
					{
						title.innerHTML = "<?php _e("<<< Hide Widget Preview", "FWTD") ?>";
						settings[0].style.height = "680px";
						div.style.display = "block";
						row.style.height = "160px";
					}
				}
				
				function ResetWidgetPreview()
				{
					var layout = document.getElementById("RatingWidgetLayout").value;
					var lang = document.getElementById('FeedwebLanguage').value;
					var div = document.getElementById("WidgetPreview");
					var pac = "e5615caa-cc14-4c9d-9a5b-069f41c2e802";
					var height = 150;
					var width = 300;
					if (layout == "wide")
					{
						width = ValidateRatingWidgetWidth();
						if (width == 0)
							return;
						height = 120;
					}
						
					var ext_bg = document.getElementById("ExternalBackgroundBox").value;
					var custom_css = document.getElementById("CustomCSSCode").value;
					var box = document.getElementById("RatingWidgetColorSchemeBox");
					var rbv = document.getElementById("ResultsBeforeVoting").value;
					var cs = box.options[box.selectedIndex].value;
					var url = '<?php echo GetFeedwebUrl()?>';
					
					if (document.getElementById('CSSContentType').value == "reset")
						custom_css = "0";
											
					var src = url + "BRW/BlogRatingWidget.aspx?cs=" + cs + "&amp;layout=" + layout + 
						"&amp;width=" + width + "&amp;height=" + height + "&amp;lang=" + lang + "&amp;pac=" + pac;
					
					if (custom_css == "0")
						src += "&amp;ext_bg=" + ext_bg;
					else
						src += "&amp;custom_css=" + custom_css;
						
					if (rbv == "1")
						src += "&amp;rbv=true";
						
					var style = "width: " + (width + 5).toString() + "px; height: " + 
						(height + 5).toString() + "px; border-style: none;";
					div.innerHTML = "<iframe style='" + style + "' scrolling='no' src='" + src + "'></iframe>";
				}
								
				function OnPurgeInactiveWidgets()
				{
					if (window.confirm('<?php _e("Remove Widgets?", "FWTD") ?>') == true)
					{
						var ids = document.getElementById('InactiveWidgetIds');
						window.location.href = "<?php echo plugin_dir_url(__FILE__)?>widget_commit.php?feedweb_cmd=RMW&wp_post_ids=" + ids.value;
					}
				}
				
				function OnChangeLayout()
				{
					var input = document.getElementById('RatingWidgetLayout');
					var list = document.getElementById('WidgetLayoutBox');
					var edit = document.getElementById('WidgetWidthEdit');
					
					input.value = list.options[list.selectedIndex].value;
					if (input.value == "wide")
					{
						if (edit.value == "")
							edit.value = "400";
						edit.disabled = "";
						
						document.getElementById('WideLayoutDisclaimer').style.color = "#000000"
						document.getElementById('WidgetWidthResetButton').style.visibility = "visible";
					}
					else
					{
						edit.disabled = "disabled";
						
						document.getElementById('WideLayoutDisclaimer').style.color = "#808080"
						document.getElementById('WidgetWidthResetButton').style.visibility = "hidden";
					}
					ResetWidgetPreview();
				}
			
				function OnChangeLanguage()
				{
					var list = document.getElementById('WidgetLanguageBox');
					var input = document.getElementById('FeedwebLanguage');
					input.value = list.options[list.selectedIndex].value;
					ResetWidgetPreview();
				}

				function OnChangeDelay()
				{
					var list = document.getElementById('DelayResultsBox');
					var input = document.getElementById('DelayResults');
					input.value = list.options[list.selectedIndex].value;
				}
				
				function OnChangeItemCount()
				{
					var input = document.getElementById('FrontWidgetItemCount');
					var list = document.getElementById('ItemCountBox');
					input.value = list.options[list.selectedIndex].value;
				}
				
				function OnChangeFrontWidgetColorScheme()
				{
					var list = document.getElementById('FrontWidgetColorSchemeBox');
					var input = document.getElementById('FrontWidgetColorScheme');
					input.value = list.options[list.selectedIndex].value;
				}
				
				function OnChangeRatingWidgetColorScheme()
				{
					var list = document.getElementById('RatingWidgetColorSchemeBox');
					var input = document.getElementById('RatingWidgetColorScheme');
					input.value = list.options[list.selectedIndex].value;
					
					var type = document.getElementById('CSSContentType').value;
					if (type == 'custom')
					{
						alert('<?php _e("Note: Changing widget color scheme will disable your custom CSS", "FWTD")?>');
						document.getElementById('CSSContentType').value = "reset";
					}
					ResetWidgetPreview();
				}
				
				function OnWidgetType(type)
				{
					if (type == "H")
					{
						document.getElementById('ExternalBackgroundBox').disabled = "";
						document.getElementById('RatingWidgetColorSchemeBox').disabled = "";
						document.getElementById('RatingWidgetColorSchemeRow').style.color = "#000000";
						document.getElementById('ExternalBackgroundResetButton').style.visibility = "visible";
					}
					else
					{
						document.getElementById('ExternalBackgroundBox').disabled = "disabled";
						document.getElementById('RatingWidgetColorSchemeBox').disabled = "disabled";
						document.getElementById('RatingWidgetColorSchemeRow').style.color = "#808080";
						document.getElementById('ExternalBackgroundResetButton').style.visibility = "hidden";
					}
					document.getElementById('RatingWidgetType').value = type;
					
					ResetWidgetPreview();
				}
				
				function OnCheckMPWidgets()
				{
					var box = document.getElementById('MPWidgetsBox');
					var input = document.getElementById('FeedwebMPWidgets');
					if (box.checked == true)
						input.value = "1";
					else
						input.value = "0";
				}
				
				function OnCheckResultsBeforeVoting()
				{
					var box = document.getElementById('ResultsBeforeVotingBox');
					var input = document.getElementById('ResultsBeforeVoting');
					if (box.checked == true)
						input.value = "1";
					else
						input.value = "0";
					ResetWidgetPreview();
				}
				
				function OnCheckCopyrightNotice()
				{
					var box = document.getElementById('CopyrightNoticeBox');
					var input = document.getElementById('FeedwebCopyrightNotice');
					if (box.checked == true)
						input.value = "1";
					else
						input.value = "0";
				}
				
				function OnCheckWidgetPrompt()
				{
					var box = document.getElementById('WidgetPromptBox');
					var input = document.getElementById('InsertWidgetPrompt');
					if (box.checked == true)
						input.value = "1";
					else
						input.value = "0";
				}
				
				function OnCheckAddParagraphs()
				{
					var box = document.getElementById('AddParagraphsBox');
					var input = document.getElementById('AutoAddParagraphs');
					if (box.checked == true)
						input.value = "1";
					else
						input.value = "0";
				}
				
				function OnCheckHideScroll()
				{
					var box = document.getElementById('FrontWidgetHideScrollBox');
					var input = document.getElementById('FrontWidgetHideScroll');
					if (box.checked == true)
						input.value = "1";
					else
						input.value = "0";
				}
				
				function ValidateNumberParam(input_id, min_value, max_value, validation_alert, range_alert)
				{
					var input = document.getElementById(input_id);
					var value = parseInt(input.value);
					if (isNaN(value))
					{
						window.alert (validation_alert);
						return 0;
					}

					if (value < min_value || value > max_value)
					{
						window.alert (range_alert);
						return 0;
					}
					
					input.value = value.toString();
					return value;
				}
				
				function ValidateRatingWidgetWidth()
				{
					return ValidateNumberParam("WidgetWidthEdit", 350, 500, 
						'<?php _e("Please enter a valid width", "FWTD")?>', 
						'<?php _e("Width is out of range", "FWTD")?>');
				}
				
				function ValidateFeederHeight()
				{
		            return ValidateNumberParam("FeederHeightEdit", 300, 2000, 
						'<?php _e("Please enter a valid feeder height", "FWTD")?>', 
						'<?php _e("Feeder height is out of range", "FWTD")?>');
				}				

				function ValidateFeederWidth()
				{
					return ValidateNumberParam("FeederWidthEdit", 200, 1000, 
						'<?php _e("Please enter a valid feeder width", "FWTD")?>', 
						'<?php _e("Feeder width is out of range", "FWTD")?>');
				}
		        
		        function ValidateFeederImageHeight()
		        {
		        	return ValidateNumberParam("FeederImageHeightEdit", 100, 1000, 
						'<?php _e("Please enter a valid maximum image height", "FWTD")?>', 
						'<?php _e("Max. image height is out of range", "FWTD")?>');
				}
				
				function OnChangeFeederRunTimeout()
				{
					var box = document.getElementById("FeederRunTimeoutBox");
					var input = document.getElementById("FeederRunTimeout");
					input.value = box.options[box.selectedIndex].value;
				}
				
				function OnChangeFeederDateFormat()
				{
					var box = document.getElementById("FeederDateFormatBox");
					var input = document.getElementById("FeederDateFormat");
					input.value = box.options[box.selectedIndex].value;
				}
				
				function OnFeederCheck(box_id, input_id)
				{
					var input = document.getElementById(input_id);
					var box = document.getElementById(box_id);
					if (box.checked == true)
						input.value = "1";
					else
						input.value = "0";
					return box.checked;
				}
				
				function OnFeederShowHeader()
				{
					if (OnFeederCheck('FeederShowHeaderBox', 'FeederShowHeader') == true)
					{
						document.getElementById("FeederShowNavigatorBox").disabled = "";
						document.getElementById("FeederOrderSelectorBox").disabled = "";
						document.getElementById("FeederAuthorSelectorBox").disabled = "";
						
						document.getElementById('FeederNavigatorTableRow').style.color = "#000000";
						document.getElementById('FeederOrderSelectorTableRow').style.color = "#000000";
						document.getElementById('FeederAuthorSelectorTableRow').style.color = "#000000";
					}
					else
					{
						document.getElementById("FeederShowNavigatorBox").checked = ""; 
						document.getElementById("FeederOrderSelectorBox").checked = "";
						document.getElementById("FeederAuthorSelectorBox").checked = "";
						
						document.getElementById("FeederShowNavigatorBox").disabled = "disabled";
						document.getElementById("FeederOrderSelectorBox").disabled = "disabled";
						document.getElementById("FeederAuthorSelectorBox").disabled = "disabled";
						
						document.getElementById('FeederNavigatorTableRow').style.color = "#808080"
						document.getElementById('FeederOrderSelectorTableRow').style.color = "#808080";
						document.getElementById('FeederAuthorSelectorTableRow').style.color = "#808080";
					}
					OnFeederShowNavigator();
				}
				
				function OnFeederAuthorSelector()
				{
					OnFeederCheck('FeederAuthorSelectorBox', 'FeederAuthorSelector');
				}
				
				function OnFeederOrderSelector()
				{
					OnFeederCheck('FeederOrderSelectorBox', 'FeederOrderSelector');
				}
				
				function OnFeederShowNavigator()
				{
					if (OnFeederCheck('FeederShowNavigatorBox', 'FeederShowNavigator') == true)
					{
						document.getElementById("FeederAutoRunBox").disabled = "";
						document.getElementById('FeederAutoRunTableRow').style.color = "#000000"
					}
					else
					{
						document.getElementById("FeederAutoRunBox").checked = ""; 
						document.getElementById("FeederAutoRunBox").disabled = "disabled";
						document.getElementById('FeederAutoRunTableRow').style.color = "#808080"
					}
					OnFeederAutoRun();
				}
				
				function OnFeederAutoRun()
				{
					OnFeederCheck('FeederAutoRunBox', 'FeederAutoRun');
				}
				
				function OnFeederAuthorInfo()
				{
					OnFeederCheck('FeederAuthorInfoBox', 'FeederAuthorInfo');
				}
				
				function OnFeederWidgetInfo()
				{
					OnFeederCheck('FeederWidgetInfoBox', 'FeederWidgetInfo');
				}
				
				function OnFeederLinksNewTab()
				{
					OnFeederCheck('FeederLinksNewTabBox', 'FeederLinksNewTab');
				}
				
				function OnFeederShowFooter()
				{
					OnFeederCheck('FeederShowFooterBox', 'FeederShowFooter');
				}

				function OnResetFeeder()
				{
					var mode = "";
					var box = document.getElementById("FeederShowHeaderBox");
					
					if (box.checked == true)
					{
						mode += "H";
						
						box = document.getElementById("FeederShowNavigatorBox");
						if (box.checked == true)
						{
		                    mode += "N";
		                    
							box = document.getElementById("FeederAutoRunBox");
		                    if (box.checked == true)
		                        mode += "S";
						}
						
						box = document.getElementById("FeederAuthorSelectorBox");
						if (box.checked == true)
			                mode += "U";
						
						box = document.getElementById("FeederOrderSelectorBox");
						if (box.checked == true)
			                mode += "O";
					} 
					
					box = document.getElementById("FeederShowFooterBox");
					if (box.checked == true)
		                mode += "F";
		            
					box = document.getElementById("FeederAuthorInfoBox");
					if (box.checked == true)
		                mode += "A";
		                
					box = document.getElementById("FeederWidgetInfoBox");
					if (box.checked == true)
					{
		                mode += "I";
						if(document.getElementById("FeederDateFormat").value == "1")
		                	mode += "R";
					}
					
					box = document.getElementById("FeederLinksNewTabBox");
					if (box.checked == false)
		                mode += "W";
		              
		            var height = ValidateFeederHeight();
					if (height == 0)
						return;
		            
		            var width = ValidateFeederWidth();
					if (width == 0)
						return;
		            
		            var mih = ValidateFeederImageHeight();
					if (mih == 0)
						return;
						
					var timeout = document.getElementById("FeederRunTimeout").value;
					var feeder = document.getElementById("FeederPreview");
					var url = "<?php echo GetFeedwebUrl();?>";
					var bac = "<?php echo GetBac(true);?>";
					
					feeder.src = url + "FPW/Feeder.aspx?bac=" + bac + "&mode=" + mode + 
						"&mih=" + mih + "&rt=" + timeout + "&bc=20&mfc=300";
					feeder.style.height = height.toString() + "px";
					feeder.style.width = width.toString() + "px";
				}

				function OnSubmitFeedwebSettingsForm()
				{
					var layout = document.getElementById("RatingWidgetLayout").value;
					if (layout == "wide")
						if (ValidateRatingWidgetWidth() == 0)
							return false;
							
					if (ValidateFeederHeight() == 0 || ValidateFeederWidth() == 0 || ValidateFeederImageHeight() == 0)
						return false;
		            
					var form = document.getElementById("FeedwebSettingsForm");
					form.action ="<?php echo plugin_dir_url(__FILE__)?>feedweb_settings.php";
					form.method = "post";
					return true;
				}
				
				function OnClickFeedwebSettingsTab(tab)
				{
					var divs = document.getElementsByClassName("FeedwebSettingsDiv");
					var tabs = document.getElementsByClassName("FeedwebSettingsTab");
					for (var index = 0; index < divs.length; index++)
						if (index.toString() == tab)
						{
							divs[index].style.display = "block";
							tabs[index].style.textDecoration = "underline";
							tabs[index].style.backgroundColor = "#e0e0ff";
						}
						else
						{
							divs[index].style.display = "none";
							tabs[index].style.textDecoration = "none";
							tabs[index].style.backgroundColor = "#ffffff";
						}
					
					var reset_button = document.getElementById("ResetFeederButton");
					reset_button.style.visibility = (tab == 1 ? "visible" : "hidden");
					
					var purge_button = document.getElementById("PurgeInactiveWidgetsButton");
					if (purge_button != null && purge_button != undefined)
						purge_button.style.visibility = (tab == 0 ? "visible" : "hidden");
				}
			</script>
			<?php wp_referer_field(true)?>
			<input type='hidden' id='DelayResults' name='DelayResults' value='<?php echo $feedweb_data["delay"];?>'/>
			<input type='hidden' id='FeedwebLanguage' name='FeedwebLanguage' value='<?php echo $feedweb_data["language"];?>'/>
			<input type='hidden' id='FeedwebMPWidgets' name='FeedwebMPWidgets' value='<?php echo $feedweb_data["mp_widgets"];?>'/>
			<input type='hidden' id='RatingWidgetType' name='RatingWidgetType' value='<?php echo $feedweb_data["widget_type"];?>'/>
			<input type='hidden' id='AutoAddParagraphs' name='AutoAddParagraphs' value='<?php echo $feedweb_data["add_paragraphs"];?>'/>
			<input type='hidden' id='InsertWidgetPrompt' name='InsertWidgetPrompt' value='<?php echo $feedweb_data["widget_prompt"];?>'/>
			<input type='hidden' id='RatingWidgetLayout' name='RatingWidgetLayout' value='<?php echo $feedweb_data["widget_layout"];?>'/>
			<input type='hidden' id='RatingWidgetColorScheme' name='RatingWidgetColorScheme' value='<?php echo $feedweb_data["widget_cs"];?>'/>
			<input type='hidden' id='ResultsBeforeVoting' name='ResultsBeforeVoting' value='<?php echo $feedweb_data["results_before_voting"];?>'/>
			<input type='hidden' id='FeedwebCopyrightNotice' name='FeedwebCopyrightNotice' value='<?php echo $feedweb_data["copyright_notice_ex"];?>'/>
			
			<input type='hidden' id='FeederAutoRun' name='FeederAutoRun' value='<?php echo $feedweb_data["feeder_auto_run"];?>'/>
			<input type='hidden' id='FeederRunTimeout' name='FeederRunTimeout' value='<?php echo $feedweb_data["feeder_run_timeout"];?>'/>
			<input type='hidden' id='FeederDateFormat' name='FeederDateFormat' value='<?php echo $feedweb_data["feeder_date_format"];?>'/>
			<input type='hidden' id='FeederShowHeader' name='FeederShowHeader' value='<?php echo $feedweb_data["feeder_show_header"];?>'/>
			<input type='hidden' id='FeederAuthorInfo' name='FeederAuthorInfo' value='<?php echo $feedweb_data["feeder_author_info"];?>'/>
			<input type='hidden' id='FeederWidgetInfo' name='FeederWidgetInfo' value='<?php echo $feedweb_data["feeder_widget_info"];?>'/>
			<input type='hidden' id='FeederShowFooter' name='FeederShowFooter' value='<?php echo $feedweb_data["feeder_show_footer"];?>'/>
			<input type='hidden' id='FeederShowNavigator' name='FeederShowNavigator' value='<?php echo $feedweb_data["feeder_show_nav"];?>'/>
			<input type='hidden' id='FeederLinksNewTab' name='FeederLinksNewTab' value='<?php echo $feedweb_data["feeder_links_new_tab"];?>'/>
			<input type='hidden' id='FeederOrderSelector' name='FeederOrderSelector' value='<?php echo $feedweb_data["feeder_order_selector"];?>'/>
			<input type='hidden' id='FeederAuthorSelector' name='FeederAuthorSelector' value='<?php echo $feedweb_data["feeder_author_selector"];?>'/>
			<br/>
			<div id="CSSEditorDiv" ><?php BuildCSSEditor();?></div> 
			<table id="SettingsTable" cellpadding="0" cellspacing="0">
				<tr class="FeedwebSettingsTabs">
					<td>
						<a href="#" class="FeedwebSettingsTab" onclick="OnClickFeedwebSettingsTab(0)" 
							style="text-decoration: underline; background-color: #e0e0ff;"><?php _e("Rating Widget", "FWTD")?></a>
						<a href="#" class="FeedwebSettingsTab" onclick="OnClickFeedwebSettingsTab(1)"><?php _e("Feeder", "FWTD")?></a>
					</td>
				</tr>
				<tr class="FeedwebSettingsContent" style="overflow: hidden;">
					<td>
						<div class="FeedwebSettingsDiv" style="display: block; height: 550px;">
							<table class="FeedwebSettingsTable" cellpadding="0" cellspacing="0">
								<tbody>
									<tr id="RatingWidgetColorSchemeRow" style="height: 100px; vertical-align: top;">
										<td style="width: 325px; min-width: 325px; max-width: 325px; position: relative;">
											<span style="position: absolute; top: 6px;"><b><?php _e("Widget Color Scheme:", "FWTD")?></b></span><br/>
											<span style="position: absolute; top: 38px;"><b><?php _e("Widget External Background:", "FWTD")?></b></span><br/>
											<span style="position: absolute; top: 70px;"><b><?php _e("Widget Stylesheet (CSS):", "FWTD")?></b></span>
										</td>
										<td style='width: 310px; min-width: 310px;'>
											<?php BuildColorSchemeBox($feedweb_data['widget_cs'], true) ?><br/>
											<?php BuildExternalBackgroundControl($feedweb_data['widget_ext_bg']) ?>
											<input type='button' class='button button-primary' id="EditWidgetCSSButton" onclick='OnEditCSS()' value='View / Edit CSS'/>
										</td>
										<td style='width: 500px; min-width: 500px;'>
											<span id='ChooseColorSchemePrompt'><?php _e("Please choose the color scheme of your HTML rating widgets.", "FWTD")?></span><br />
											<span id='CustomCSSValidityPrompt'><?php _e("Your custom CSS might not be compatible with the Widget.", "FWTD")?>
											<br/><?php _e("Please revise and resubmit.", "FWTD")?></span>
										</td>
									</tr>	
																		
									<tr>
										<td>
											<span><b><?php _e("Widget Language:", "FWTD")?></b></span>
										</td>
										<td>
											<?php BuildLanguageBox($feedweb_data['language'], $feedweb_data['language_set'], false) ?>
										</td>
										<td>
											<span><i>Don't find your language? <a href="mailto://contact@feedweb.net">Help us translate the widget for you!</a></i></span>
										</td>
									</tr>
									<tr id="RatingWidgetLayoutRow" style="height: 56px; vertical-align: top;">
										<td style='position: relative;'>
											<span style="position: absolute; top: 8px;"><b><?php _e("Widget Layout:", "FWTD")?></b></span><br/>
											<span style="position: absolute; top: 40px;"><b><?php _e("Widget width (pixels):", "FWTD")?></b></span>
										</td>
										<td>
											<?php BuildLayoutBox($feedweb_data['widget_layout']) ?><br/>
											<input id='WidgetWidthEdit' name='WidgetWidthEdit' type='text' value="<?php echo $feedweb_data['widget_width']?>"/>
											<?php BuildResetPreviewButton('WidgetWidthResetButton') ?>
										</td>
										<td>
											<span id="LayoutInfoText"><i><?php _e("Mobile screen layout (300x150) is recommended for all types of devices.", "FWTD")?></i></span><br/>
											<span id="WideLayoutDisclaimer"><i><?php _e("Allowed width: 350 to 500 pixels. Recommended width: 400 to 450 pixels.", "FWTD")?></i></span>
										</td>
									</tr>
									<tr>
										<td>
											<span><b><?php _e("Display results before voting:", "FWTD")?></b></span>
										</td>
										<td>
											<input <?php if($feedweb_data['results_before_voting'] == "1") echo 'checked="checked"' ?>
											id="ResultsBeforeVotingBox" name="ResultsBeforeVotingBox" type="checkbox" onchange='OnCheckResultsBeforeVoting()'> 
											<?php _e("Display Results", "FWTD")?></input>				
										</td>
										<td>
											<span><i><?php _e("Allow your visitors to see results before they vote.", "FWTD")?></i></span>
										</td>
									</tr>
									
									<tr id="WidgetPreviewRow">
										<td>
											<span id="WidgetPreviewTitle" onclick="OnShowWidgetPreview()" style="cursor: pointer;"><?php _e("Show Widget Preview >>>", "FWTD")?></span>
										</td>
										<td colspan="2">
											<input id="CustomCSSCode" type="hidden" value="<?php echo $feedweb_data['custom_css'];?>"/>
											<div id="WidgetPreview" style="display: none;"></div>
										</td>
									</tr>
									
									<tr>
										<td>
											<span><b><?php _e("Widgets at the Home/Front Page:", "FWTD")?></b></span> 				
										</td>
										<td>
											<input <?php if($feedweb_data['mp_widgets'] == "1") echo 'checked="checked"' ?>
											id="MPWidgetsBox" name="MPWidgetsBox" type="checkbox" onchange='OnCheckMPWidgets()'> <?php _e("Display Widgets", "FWTD")?></input>				
										</td>
										<td>
											<span><i><?php _e("Check to display the widgets both in the Front Page and the single post pages.", "FWTD")?></i></span>
										</td>
									</tr>
									<tr>
										<td>
											<span><b><?php _e("Delay displaying results:", "FWTD")?></b></span> 				
										</td>
										<td>
											<?php BuildDelayBox($feedweb_data['delay']) ?>				
										</td>
										<td>
											<span><i><?php _e("Set the period of time you want to hide voting results after the widget is created.", "FWTD")?></i></span>
										</td>
									</tr>
									
									<tr>
										<td>
											<span><b><?php _e("Feedweb Copyright Notice:", "FWTD")?></b></span> 				
										</td>
										<td>
											<input <?php if($feedweb_data['copyright_notice_ex'] == "1") echo 'checked="checked"' ?>
											id="CopyrightNoticeBox" name="CopyrightNoticeBox" type="checkbox" onchange='OnCheckCopyrightNotice()'> <?php _e("Allow")?></input>				
										</td>
										<td style="padding-bottom: 0px; padding-top: 6px;">
											<span style='display: block; margin-bottom: 4px;'><i>
											<?php _e("Please check to display the following text below the widgets: ", "FWTD")?></i></span>
											<?php echo GetCopyrightNotice()?>
										</td>
									</tr>
									
									<tr>
										<td>
											<span><b><?php _e("Prompt to insert widgets:", "FWTD")?></b></span> 				
										</td>
										<td>
											<input <?php if($feedweb_data['widget_prompt'] == "1") echo 'checked="checked"' ?>
											id="WidgetPromptBox" name="WidgetPromptBox" type="checkbox" onchange='OnCheckWidgetPrompt()'> <?php _e("Show")?></input>				
										</td>
										<td>
											<span><i><?php _e("Display a prompt to insert a rating widget when a post is published.", "FWTD")?></i></span>
										</td>
									</tr>
									<tr>
										<td>
											<span><b><?php _e("Automatically add paragraphs:", "FWTD")?></b></span> 				
										</td>
										<td>
											<input <?php if($feedweb_data['add_paragraphs'] == "1") echo 'checked="checked"' ?>
											id="AddParagraphsBox" name="AddParagraphsBox" type="checkbox" onchange='OnCheckAddParagraphs()'> <?php _e("Add", "FWTD")?></input>				
										</td>
										<td>
											<span><i><?php _e("Surround widgets with paragraph tags:", "FWTD")?></i><b> &lt;P&gt;...&lt;/P&gt;</b></span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="FeedwebSettingsDiv" style="display: none; height: 770px;">
							<table class="FeedwebSettingsTable" cellpadding="0" cellspacing="0">
								<tbody>
									<tr>
										<td style='width: 170px; min-width: 170px; max-width: 170px; height: 44px;'>
											<span><b><?php _e("Feeder Width:", "FWTD")?></b></span>
										</td>
										<td style='width: 155px; min-width: 155px; max-width: 155px; '>
											<input id='FeederWidthEdit' name='FeederWidthEdit' type='text' style="width: 145px;" value="<?php echo $feedweb_data['feeder_width']?>"/>
										</td>
										<td style='width: 320px; min-width: 320px;'>
											<span><i><?php _e("Width of the Feeder in pixels.<br/>Allowed: 200 to 1000. Recommended: 300.", "FWTD")?></i></span>
										</td>
										<td style='padding: 0; background-color: #e0e0e0; vertical-align: top; min-width: 350px;' rowspan='14'>
											<div style='position: relative; width: 100%; height: 100%; display: block; overflow-y: scroll; overflow-x: scroll;'>
												<div style='position: absolute; display: block; top: 15px; bottom: 15px; left: 15px; right: 15px; text-align: right;'>
													<iframe id="FeederPreview" style="position: relative;"></iframe>	
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td style='height: 44px;'>
											<span><b><?php _e("Feeder Height:", "FWTD")?></b></span>
										</td>
										<td>
											<input id='FeederHeightEdit' name='FeederHeightEdit' type='text' style="width: 145px;" value="<?php echo $feedweb_data['feeder_height']?>"/>
										</td>
										<td>
											<span><i><?php _e("Height of the Feeder in pixels.<br/>Allowed: 300 to 2000 pixels.", "FWTD")?></i></span>
										</td>
									</tr>
									<tr >
										<td style='height: 44px;'>
											<span><b><?php _e("Show Header:", "FWTD")?></b></span> 				
										</td>
										<td>
											<input <?php if($feedweb_data['feeder_show_header'] == "1") echo 'checked="checked"' ?>
											id="FeederShowHeaderBox" name="FeederShowHeaderBox" type="checkbox" onchange='OnFeederShowHeader()'> <?php _e("Show")?></input>				
										</td>
										<td>
											<span><i><?php _e("Display header bar of the Feeder.", "FWTD")?></i></span>
										</td>
									</tr>
									<tr id="FeederAuthorSelectorTableRow">
										<td style="height: 44px;">
											<span><b><?php _e("Select Authors:", "FWTD")?></b></span> 				
										</td>
										<td>
											<input <?php if($feedweb_data['feeder_author_selector'] == "1") echo 'checked="checked"' ?>
											id="FeederAuthorSelectorBox" name="FeederAuthorSelectorBox" type="checkbox" onchange='OnFeederAuthorSelector()'> <?php _e("Show")?></input>				
										</td>
										<td>
											<span><i><?php _e("Display Authors Menu<br/>(Appears when the site has 2 or more authors).", "FWTD")?></i></span>
										</td>
									</tr>
									<tr id="FeederOrderSelectorTableRow">
										<td style="height: 44px;">
											<span><b><?php _e("Select Order:", "FWTD")?></b></span> 				
										</td>
										<td>
											<input <?php if($feedweb_data['feeder_order_selector'] == "1") echo 'checked="checked"' ?>
											id="FeederOrderSelectorBox" name="FeederOrderSelectorBox" type="checkbox" onchange='OnFeederOrderSelector()'> <?php _e("Show")?></input>				
										</td>
										<td>
											<span><i><?php _e("Display Order Menu<br/>(Sort posts by time / votes).", "FWTD")?></i></span>
										</td>
									</tr>
									<tr id="FeederNavigatorTableRow">
										<td style="height: 44px;">
											<span><b><?php _e("Show Navigator:", "FWTD")?></b></span> 				
										</td>
										<td>
											<input <?php if($feedweb_data['feeder_show_nav'] == "1") echo 'checked="checked"' ?>
											id="FeederShowNavigatorBox" name="FeederShowNavigatorBox" type="checkbox" onchange='OnFeederShowNavigator()'> <?php _e("Show")?></input>				
										</td>
										<td>
											<span><i><?php _e("Display navigator icons on the Feeder<br/>(show vertical scrollbar when unchecked).", "FWTD")?></i></span>
										</td>
									</tr> 
									<tr id="FeederAutoRunTableRow">
										<td style="height: 44px;">
											<span><b><?php _e("Auto Run Feed:", "FWTD")?></b></span> 				
										</td>
										<td>
											<input <?php if($feedweb_data['feeder_auto_run'] == "1") echo 'checked="checked"' ?>
											id="FeederAutoRunBox" name="FeederAutoRunBox" type="checkbox" onchange='OnFeederAutoRun()'> <?php _e("Yes")?></input>				
										</td>
										<td>
											<span><i><?php _e("Auto-play the Feeder on start.<br/>(*10 posts and up)", "FWTD")?></i></span>
										</td>
									</tr> 
									<tr>
										<td style='height: 44px;'>
											<span><b><?php _e("Feeder run timeout:", "FWTD")?></b></span>
										</td>
										<td>
											<?php BuildRunTimeoutBox($feedweb_data['feeder_run_timeout']) ?>
										</td>
										<td>
											<span><i><?php _e("The Feeder item scrolling timeout.", "FWTD")?></i></span>
										</td>
									</tr>
									<tr>
										<td style="height: 44px;">
											<span><b><?php _e("Author Info:", "FWTD")?></b></span> 				
										</td>
										<td>
											<input <?php if($feedweb_data['feeder_author_info'] == "1") echo 'checked="checked"' ?>
											id="FeederAuthorInfoBox" name="FeederAuthorInfoBox" type="checkbox" onchange='OnFeederAuthorInfo()'> <?php _e("Show")?></input>				
										</td>
										<td>
											<span><i><?php _e("Show Author info in the Feeder Items<br/>(Gravatar, Author Name, Site Title).", "FWTD")?></i></span>
										</td>
									</tr> 
									<tr>
										<td style="height: 44px;">
											<span><b><?php _e("Post Info:", "FWTD")?></b></span> 				
										</td>
										<td>
											<input <?php if($feedweb_data['feeder_widget_info'] == "1") echo 'checked="checked"' ?>
											id="FeederWidgetInfoBox" name="FeederWidgetInfoBox" type="checkbox" onchange='OnFeederWidgetInfo()'> <?php _e("Show")?></input>				
										</td>
										<td>
											<span><i><?php _e("Show Post info in the Feeder Items<br/>(Date / Number of votes).", "FWTD")?></i></span>
										</td>
									</tr> 
									<tr>
										<td style="height: 44px;">
											<span><b><?php _e("Post Date Format:", "FWTD")?></b></span> 				
										</td>
										<td>
											<?php BuildDateFormatBox($feedweb_data['feeder_date_format']) ?>
										</td>
										<td>
											<span><i><?php _e("Choose how to display post date.", "FWTD")?></i></span>
										</td>
									</tr> 
									
									<tr>
										<td style="height: 44px;">
											<span><b><?php _e("Feeder Links:", "FWTD")?></b></span> 				
										</td>
										<td>
											<input <?php if($feedweb_data['feeder_links_new_tab'] == "1") echo 'checked="checked"' ?>
											id="FeederLinksNewTabBox" name="FeederLinksNewTabBox" type="checkbox" onchange='OnFeederLinksNewTab()'> <?php _e("Open New Tab", "FWTD")?></input>				
										</td>
										<td>
											<span><i><?php _e("Open the Feeder links in a new tab<br/>(otherwise redirect the current window).", "FWTD")?></i></span>
										</td>
									</tr> 
									<tr>
										<td style="height: 44px;">
											<span><b><?php _e("Show Footer:", "FWTD")?></b></span> 				
										</td>
										<td>
											<input <?php if($feedweb_data['feeder_show_footer'] == "1") echo 'checked="checked"' ?>
											id="FeederShowFooterBox" name="FeederShowFooterBox" type="checkbox" onchange='OnFeederShowFooter()'> 
											<?php _e("Show")?></input>				
										</td>
										<td>
											<span><i><?php _e("Display footer bar of the Feeder.", "FWTD")?></i></span>
										</td>
									</tr> 
									<tr>
										<td style='height: 44px;'>
											<span><b><?php _e("Max. Image Height:", "FWTD")?></b></span>
										</td>
										<td>
											<input id='FeederImageHeightEdit' name='FeederImageHeightEdit' type='text' style="width: 145px;" value="<?php echo $feedweb_data['feeder_img_height']?>"/>
										</td>
										<td>
											<span><i><?php _e("Maximum height of the images in the Feeder.<br/>Allowed: 100 to 1000 pixels.", "FWTD")?></i></span>
										</td>
									</tr>
									<tr>
										<td colspan="4" style="border-bottom: none; padding-top: 10px;">
											<span style="font-weight: bold; font-size: 12pt;">To <font style="color: red;">enable</font> the Feeder, go to <a href="./widgets.php">Appearance -> Widgets</a>, add a text widget to your sidebar, and place <font style='color: blue;'>[FeedwebFeederBar]</font> in the widget's text.<br/>Please <a href="mailto://contact@feedweb.net">contact us</a> if you have any questions.</span>	
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</td>
				</tr>
				<tr class="FeedwebSettingsCommitButton">
					<td>
						<div>
							<?php echo get_submit_button(__('Save Changes'), 'primary', 'submit', false, "");?>
							<?php GetPurgeInactiveWidgets(); ?>
							<?php echo "<input id='ResetFeederButton' type='button' onclick='OnResetFeeder()' 
								value='".__("Reset Feeder Preview", "FWTD")."' title='".__("Click to Reset Feeder Preview", "FWTD")."'/>";?>
						</div>
					</td>
				</tr>
			</table>
		</form>
	</div>
	<script>
		OnWidgetType('<?php echo $feedweb_data['widget_type']?>');
		OnChangeLayout();
		OnResetFeeder();
	</script>
	<?php 
}
?>