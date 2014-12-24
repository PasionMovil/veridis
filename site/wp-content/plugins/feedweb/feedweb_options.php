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
			<link href='<?php echo plugin_dir_url(__FILE__)?>Feedweb.css?v=2.4.3' rel='stylesheet' type='text/css' />
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
						settings[0].style.height = "450px";
						div.style.display = "none";
						row.style.height = "35px";
					}
					else
					{
						title.innerHTML = "<?php _e("<<< Hide Widget Preview", "FWTD") ?>";
						settings[0].style.height = "580px";
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
				
				/*
				function OnWidgetPlacement(placement)
				{
					document.getElementById('RatingWidgetPlacement').value = placement;
				}
				*/
				
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
				
				function ValidateRatingWidgetWidth()
				{
					var input = document.getElementById("WidgetWidthEdit");
					var width = parseInt(input.value);
					if (isNaN(width))
					{
						window.alert ('<?php _e("Please enter a valid width", "FWTD")?>');
						return 0;
					}

					if (width < 350 || width > 500)
					{
						window.alert ('<?php _e("Width is out of range", "FWTD")?>');
						return 0;
					}
					input.value = width.toString();
					return width;
				}

				function OnSubmitFeedwebSettingsForm()
				{
					var layout = document.getElementById("RatingWidgetLayout").value;
					if (layout == "wide")
						if (ValidateRatingWidgetWidth() == 0)
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
			<!--
			<input type='hidden' id='AsyncLoadMode' name='AsyncLoadMode' value='<?php echo $feedweb_data["async_load_mode"];?>'/>
			<input type='hidden' id='RatingWidgetPlacement' name='RatingWidgetPlacement' value='<?php echo $feedweb_data["widget_place"];?>'/>
			<input type='hidden' id='FrontWidgetHideScroll' name='FrontWidgetHideScroll' value='<?php echo $feedweb_data["front_widget_hide_scroll"];?>'/>
			<input type='hidden' id='FrontWidgetColorScheme' name='FrontWidgetColorScheme' value='<?php echo $feedweb_data["front_widget_color_scheme"];?>'/>
			<input type='hidden' id='FrontWidgetItemCount' name='FrontWidgetItemCount' value='<?php echo $feedweb_data["front_widget_items"];?>'/>
			-->
			<br/>
			<div id="CSSEditorDiv" ><?php BuildCSSEditor();?></div> 
			<table id="SettingsTable" cellpadding="0" cellspacing="0">
				<tr class="FeedwebSettingsTabs">
					<td>
						<a href="#" class="FeedwebSettingsTab" onclick="OnClickFeedwebSettingsTab(0)" 
							style="text-decoration: underline; background-color: #e0e0ff;"><?php _e("Rating Widget", "FWTD")?></a>
						<a href="#" class="FeedwebSettingsTab" onclick="OnClickFeedwebSettingsTab(1)"><?php _e("Advanced", "FWTD")?></a>
					</td>
				</tr>
				<tr class="FeedwebSettingsContent" style="overflow: hidden;">
					<td>
						<div class="FeedwebSettingsDiv" style="display: block; height: 450px;">
							<table class="FeedwebSettingsTable">
								<tbody>
									<tr id="RatingWidgetColorSchemeRow" style="height: 100px; vertical-align: top;">
										<td>
											<span style="position: relative; top: 5px;"><b><?php _e("Widget Color Scheme:", "FWTD")?></b></span><br/>
											<span style="position: relative; top: 20px;"><b><?php _e("Widget External Background:", "FWTD")?></b></span><br/>
											<span style="position: relative; top: 35px;"><b><?php _e("Widget Stylesheet (CSS):", "FWTD")?></b></span>
										</td>
										<td>
											<?php BuildColorSchemeBox($feedweb_data['widget_cs'], true) ?><br/>
											<?php BuildExternalBackgroundControl($feedweb_data['widget_ext_bg']) ?>
											<input type='button' class='button button-primary' id="EditWidgetCSSButton" onclick='OnEditCSS()' value='View / Edit CSS'/>
										</td>
										<td>
											<span id='ChooseColorSchemePrompt'><?php _e("Please choose the color scheme of your HTML rating widgets.", "FWTD")?></span><br />
											<span id='CustomCSSValidityPrompt'><?php _e("Your custom CSS might not be compatible with the Widget.", "FWTD")?>
											<br/><?php _e("Please revise and resubmit.", "FWTD")?></span>
										</td>
									</tr>	
																		
									<tr>
										<td>
											<span><b><?php _e("Widget Language:", "FWTD")?></b></span>
										</td>
										<td style='width: 200px;'>
											<?php BuildLanguageBox($feedweb_data['language'], $feedweb_data['language_set'], false) ?>
										</td>
										<td style='width: 600px;'>
											<span><i>Don't find your language? <a href="mailto://contact@feedweb.net">Help us translate the widget for you!</a></i></span>
										</td>
									</tr>
									<tr id="RatingWidgetLayoutRow" style="height: 56px; vertical-align: top;">
										<td>
											<span style="position: relative; top: 5px;"><b><?php _e("Widget Layout:", "FWTD")?></b></span><br/>
											<span style="position: relative; top: 20px;"><b><?php _e("Widget width (pixels):", "FWTD")?></b></span>
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
								</tbody>
							</table>
						</div>
						<div class="FeedwebSettingsDiv" style="display: none; height: 450px;">
							<table class="FeedwebSettingsTable">
								<tbody>
									<!--
									<tr>
										<td style='width: 320px;'>
											<span><b><?php _e("Async load mode:", "FWTD")?></b></span> 				
										</td>
										<td style='width: 200px;'>
											<input id="AsyncLoadModeBox" name="AsyncLoadModeBox" onchange="OnCheckAsyncLoadMode()"
											<?php if($feedweb_data['async_load_mode'] == "1") echo 'checked="checked"'?> type="checkbox"> 
											<?php _e("Enable", "FWTD")?></input>				
										</td>
										<td style='width: 600px;'>
											<span><i><?php _e("Check to enable asynchronous (nonblocking) loading of Rating Widgets.", "FWTD")?></i></span>
										</td>
									</tr>
									-->
									<tr>
										<td style='width: 320px;'>
											<span><b><?php _e("Automatically add paragraphs:", "FWTD")?></b></span> 				
										</td>
										<td style='width: 200px;'>
											<input <?php if($feedweb_data['add_paragraphs'] == "1") echo 'checked="checked"' ?>
											id="AddParagraphsBox" name="AddParagraphsBox" type="checkbox" onchange='OnCheckAddParagraphs()'> <?php _e("Add")?></input>				
										</td>
										<td style='width: 600px;'>
											<span><i><?php _e("Surround widgets with paragraph tags:", "FWTD")?></i><b> &lt;P&gt;...&lt;/P&gt;</b></span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</td>
				</tr>
				<tr class="FeedwebSettingsCommitButton">
					<td>
						<?php echo get_submit_button(__('Save Changes'), 'primary', 'submit', false, "style='width: 200px;'");?>
						<?php GetPurgeInactiveWidgets(); ?>
					</td>
				</tr>
			</table>
		</form>
	</div>
	<script>
		OnWidgetType('<?php echo $feedweb_data['widget_type']?>');
		OnChangeLayout();
	</script>
	<?php 
}
?>