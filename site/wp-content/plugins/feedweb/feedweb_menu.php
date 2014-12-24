<?php
	if (!current_user_can("manage_options"))
		wp_die( __("You do not have sufficient permissions to access this page.") );
		
	FeedwebPluginOptions();
?>