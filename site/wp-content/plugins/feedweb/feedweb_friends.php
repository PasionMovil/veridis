<style>
.FriendPromotionDiv
{
	position: relative;
}

.FriendPromotionDiv div,
.FriendPromotionDiv a
{
	position: absolute;
}

.FriendPromotionDiv .LogoImage
{
	width: 500px;
}

.FriendPromotionDiv a span
{
	position: absolute;
	font-weight: bold;
	display: block;
	width: 450px;
	top: 180px;
}

.FriendPromotionDiv .InfoImage
{
	position: absolute;
	width: 50px;
	left: 450px;
	top: 162px;
}
</style>

<div class="wrap">
	<div id="icon-plugins" class="icon32"><br /></div>
	<h2><?php _e("Our Friends - The Plugins We Recommend", "FWTD");?></h2><br/>
	
	<div class="FriendPromotionDiv">
		<div style="top: 0px; left: 0px;">
			<a href="http://wordpress.org/plugins/the-casengo-chat-widget/" target="_blank" style="top: 0px; left: 0px;">
				<img class="LogoImage" src="<?php echo plugin_dir_url(__FILE__)?>/images/Livechat-Casengo.jpg"/>
			</a>
			<a href="http://wordpress.org/plugins/the-casengo-chat-widget/" target="_blank" style="top: 0px; left: 0px;">
				<span>Casengo is Customer Support Software (Live Chat, Email, Social Media) from the Cloud, First User is Always Free!</span>
			</a>
			<input alt='<?php echo admin_url()?>/plugin-install.php?tab=plugin-information&plugin=the-casengo-chat-widget&TB_iframe=true&width=640&height=500' 
				class='thickbox InfoImage' title='Casengo Plugin Info' type='image' src='<?php echo plugin_dir_url(__FILE__)?>/images/Info.png'/>";
		</div>
	</div>
</div>

