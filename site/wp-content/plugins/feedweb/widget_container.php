<?php

	if ( !defined('ABSPATH') )
		define('ABSPATH', dirname(dirname(dirname(dirname(__FILE__)))) . '/');
	
	require_once('feedweb_util.php');
	require_once('feedweb_options.php');
	
	require_once( ABSPATH.'wp-load.php');
	require_once(ABSPATH.'wp-admin/includes/plugin.php');

	function DisplayFeedwebWidget($pid, $is_hp)
	{
		$data = GetFeedwebOptions();
		if ($data["mp_widgets"] == "0")	// Doesn't display on the Home / Front Page
			if ($is_hp == "true")
				return "No widgets on the Homepage";
					
		$pac = GetPac($pid);
		if ($pac == null)
			return "No PAC / Invalid page";
				
		if (CheckServiceAvailability() != null)
			return "Service is not available"; 
			
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
		
		$code = "<iframe class='FeedwebRatingWidget' style='width: ".$frame_width."px; height: ".$frame_height."px; ".
			"border-style: none; position: absolute; top: 0; left: 0;' scrolling='no' src='$src'></iframe>";
						
		$url = GetFeedwebUrl()."RCP?pac=$pac";
		$code .= "<a id='FeedwebVerificationLink_$pid' style='display: none;' href='$url'>.</a>";
			
		$code = $code.GetLicenseInfo(null);
		
		return array('width' => $frame_width, 'height' => $frame_height, 'code' => $code, 'notice' => $data["copyright_notice_ex"]);
	}

	$pid = $_GET['pid'];
	$is_hp = $_GET['is_hp'];
	$result = DisplayFeedwebWidget($pid, $is_hp);	

	if ($result != null)
	{
		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
		<html>
			<head>
				<style>
					html, body
					{
						padding: 0;
						margin: 0;
					}
				</style>
				
				<?php
				if (gettype($result) == "array")
				{
				?>
				<script type="text/javascript">
					function ActivateWidget()
					{
						var frame = window.parent.document.getElementById("FeedwebRatingWidgetContainer_<?php echo $pid;?>");
						var frame = window.frameElement;
					    if (frame == null)
					    	return;
					    
						frame.width = "<?php echo $result['width']?>";
						frame.height = "<?php echo $result['height']?>";
						
						<?php
						if ($result['notice'] == "1")
						{
							?>
							var placeholder = parent.document.getElementById("FeedwebNoticePlaceHolder_<?php echo $pid;?>");
					    	if (placeholder != null && placeholder != undefined)
					    		placeholder.style.display = 'block';
							<?php
						}
						?>
					}
				</script>
				<?php
				}
				?>
			</head>
			<body onload="setTimeout(function(){ActivateWidget()}, 10)">
			<?php
				if (gettype($result) == "string")
					echo "<input type='hidden' id='FeedwebErrorMessage' value='$result'/>";
				else 
				{
					echo $result['code'];
					if ($result['notice'] != null)
						echo $result['notice'];
				}
			?>
			<!--
			<script type="text/javascript">
				ActivateWidget();
			</script>
			-->
			</body>			
		</html>
		<?php			
	}
?>