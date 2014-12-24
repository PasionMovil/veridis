<?php  
	//custom CSS 
	include("functions/custom-css.php"); 
?>
</div> <!-- end container-wrapper -->
<div id="footer">
		<div id="footer-inside">
			<div class="one_third">
					<?php 
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer First Column Sidebar') ) :  

					endif; 
					?>
			</div>
			<div class="one_third">		
					<?php 
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Second Column Sidebar') ) :  

					endif; 
					?>	
			</div>
			<div class="one_third">	
					<?php 
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Third Column Sidebar') ) :  

					endif; 
					?>	
			</div>
			<div class="clear"></div>
		</div>
</div>
<?php
if ( function_exists( 'ot_get_option' ) ) {
		if ((ot_get_option('meganews_under_footer_left_content') != '') || (ot_get_option('meganews_under_footer_right_content') != '')) {
		?>

<div id="under-footer">
	<div id="under-footer-inside">
			<div class="fl">
				<?php echo ot_get_option('meganews_under_footer_left_content');  ?>
			</div>
			<div class="fr">
				<?php echo ot_get_option('meganews_under_footer_right_content'); ?>
			</div>
		</div>
		<div class="clear"></div>
	</div>
<?php
		}
	}
?>
<?php  wp_footer(); ?>
</body>
</html>