<form id="mks_shortcode_columns">
	<table class="form-table">
		<tbody>
		 <tr>
		 	<th><h3><?php _e('Standard','mks-shortcodes'); ?></h3></th><td></td>
		 </tr>
	   <tr>
				<th><?php _e('Two columns','mks-shortcodes'); ?>:</th> 
				<td><input type="radio" name="columns" value="<p>[mks_one_half]Your awesome content goes here[/mks_one_half]</p><p>[mks_one_half]Your awesome content goes here[/mks_one_half]</p>" checked /> (1/2) + (1/2)</td>
		</tr>
		<tr>
				<th><?php _e('Three columns','mks-shortcodes'); ?>:</th> 
				<td><input type="radio" name="columns" value="<p>[mks_one_third]Your awesome content goes here[/mks_one_third]</p><p>[mks_one_third]Your awesome content goes here[/mks_one_third]</p><p>[mks_one_third]Your awesome content goes here[/mks_one_third]</p>"/> (1/3) + (1/3) + (1/3)</td>
		</tr>
		<tr>
				<th><?php _e('Four columns','mks-shortcodes'); ?>:</th> 
				<td><input type="radio" name="columns" value="<p>[mks_one_quarter]Your awesome content goes here[/mks_one_quarter]</p><p>[mks_one_quarter]Your awesome content goes here[/mks_one_quarter]</p><p>[mks_one_quarter]Your awesome content goes here[/mks_one_quarter]</p><p>[mks_one_quarter]Your awesome content goes here[/mks_one_quarter]</p>"/> (1/4) + (1/4) + (1/4) + (1/4)</td>
		</tr>
		 <tr>
		 	<th><h3><?php _e('Complex','mks-shortcodes'); ?></h3></th><td></td>
		 </tr>
	   <tr>
				<th><?php _e('Two thirds + one third','mks-shortcodes'); ?>:</th> 
				<td><input type="radio" name="columns" value="<p>[mks_two_thirds]Your awesome content goes here[/mks_two_thirds]</p><p>[mks_one_third]Your awesome content goes here[/mks_one_third]</p>"/> (2/3) + (1/3)</td>
		</tr>
		<tr>
				<th><?php _e('One third + two thirds','mks-shortcodes'); ?>:</th>
				<td><input type="radio" name="columns" value="<p>[mks_one_third]Your awesome content goes here[/mks_one_third]</p><p>[mks_two_thirds]Your awesome content goes here[/mks_two_thirds]</p>"/> (1/3) + (2/3)</td>
		</tr>
		<tr>
				<th><?php _e('One half + two quarters','mks-shortcodes'); ?>:</th> 
				<td><input type="radio" name="columns" value="<p>[mks_one_half]Your awesome content goes here[/mks_one_half]</p><p>[mks_one_quarter]Your awesome content goes here[/mks_one_quarter]</p><p>[mks_one_quarter]Your awesome content goes here[/mks_one_quarter]</p>"/> (1/2) + (1/4) + (1/4) </td>
		</tr>
		
		<tr>
				<th><?php _e('Two quarters + one half','mks-shortcodes'); ?>:</th> 
				<td><input type="radio" name="columns" value="<p>[mks_one_quarter]Your awesome content goes here[/mks_one_quarter]</p><p>[mks_one_quarter]Your awesome content goes here[/mks_one_quarter]</p><p>[mks_one_half]Your awesome content goes here[/mks_one_half]</p>"/> (1/4) + (1/4) + (1/2) </td>
		</tr>
		<tr>
				<th><?php _e('One quarter + one half + one quarter','mks-shortcodes'); ?>:</th> 
				<td><input type="radio" name="columns" value="<p>[mks_one_quarter]Your awesome content goes here[/mks_one_quarter]</p><p>[mks_one_half]Your awesome content goes here[/mks_one_half]</p><p>[mks_one_quarter]Your awesome content goes here[/mks_one_quarter]</p>"/> (1/4) + (1/2) + (1/4) </td>
		</tr>
		<tr>
				<th><input type="submit" class="button-primary" value="<?php _e('Insert Columns','mks-shortcodes'); ?>"/></th> 
				<td>&nbsp;</td>
		</tr>
	
	</tbody>
	</table>
</form>

<script type="text/javascript">
	/* <![CDATA[ */
  (function($) {
    	$('#mks_shortcode_columns').submit(function(e) {
    			e.preventDefault();
    			tb_remove();
    			var content = '[mks_col]'+$(this).find('input[name="columns"]:checked').val()+'[/mks_col]';
    			content = content.replace('{content}',mks_shortcode_content);
    			mks_shortcode.setContent(content);
			});
	})(jQuery);
	/* ]]> */
</script>