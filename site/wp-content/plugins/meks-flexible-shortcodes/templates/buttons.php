<form id="mks_shortcode_buttons">
	
	<table class="form-table">
		<tbody>
			<tr>
		 		<th><h3><?php _e('Style Options','mks-shortcodes'); ?></h3></th><td>&nbsp;</td>
			</tr>
	   <tr>
				<th>
					<?php _e('Size','mks-shortcodes'); ?>:
				</th>
				<td><input type="radio" name="size" value="small"/> <?php _e('Small','mks-shortcodes'); ?>&nbsp;&nbsp; 
					<input type="radio" name="size" value="medium"/> <?php _e('Medium','mks-shortcodes'); ?>&nbsp;&nbsp;
					<input type="radio" name="size" value="large" checked /> <?php _e('Large','mks-shortcodes'); ?> 
				</td>
		</tr>
		
	  <tr>
				<th>
					<?php _e('Style','mks-shortcodes'); ?>:
				</th>
				<td>
					<input type="radio" name="style" value="squared" checked /> <?php _e('Squared','mks-shortcodes'); ?>&nbsp;&nbsp;
					<input type="radio" name="style" value="rounded"/> <?php _e('Rounded','mks-shortcodes'); ?>&nbsp;&nbsp;
				</td>
		</tr>
		<tr>
				<th><?php _e('Background Color','mks-shortcodes'); ?>:</th>
				<td><input id="mks_button_bg_color" type="text" name="bg_color" value="#000000"/></td>
		</tr>
		<tr>
				<th><?php _e('Text Color','mks-shortcodes'); ?>:</th>
				<td><input id="mks_button_txt_color" type="text" name="txt_color" value="#FFFFFF"/></td>
		</tr>
		<tr>
				<th>
					<?php _e('Icon','mks-shortcodes'); ?>:
				</th>
				<td>
					<?php mks_generate_fontawesome_icons_picker(); ?>
				</td>
		</tr>
		<tr>
		 	<th><h3><?php _e('Link Options','mks-shortcodes'); ?></h3></th><td>&nbsp;</td>
		</tr>
		<tr>
				<th><?php _e('Title','mks-shortcodes'); ?>:</th>
				<td><input type="text" name="title" class="widefat" value="<?php _e('Button','mks-shortcodes'); ?>"/></td>
		</tr>
		<tr>
				<th><?php _e('URL','mks-shortcodes'); ?>:</th>
				<td><input type="text" name="url" class="widefat" value="http://"/></td>
		</tr>
		<tr>
				<th>
					<?php _e('Link Target','mks-shortcodes'); ?>:
				</th>
				<td>
					<input type="radio" name="target" value="_self" checked/> <?php _e('Open in same window','mks-shortcodes'); ?><br />
					<input type="radio" name="target" value="_blank"/> <?php _e('Open in new window/tab','mks-shortcodes'); ?>&nbsp;&nbsp;
				</td>
		</tr>
		<tr>
				<th><input type="submit" class="button-primary" value="<?php _e('Insert Button','mks-shortcodes'); ?>"></th> 
				<td>&nbsp;</td>
		</tr>
	
	</tbody>
	</table>
</form>

<script type="text/javascript">
	/* <![CDATA[ */
  (function($) {
    	$('#mks_shortcode_buttons').submit(function(e) {
    			e.preventDefault();
    			tb_remove();
    			var size = $(this).find('input[name="size"]:checked').val();
    			var style = $(this).find('input[name="style"]:checked').val();
    			var title = $(this).find('input[name="title"]').val();
    			var url = $(this).find('input[name="url"]').val();
    			var target = $(this).find('input[name="target"]:checked').val();
    			var icon = $(this).find('input[name="icon"]').val();
    			var icon_type = $(this).find('input[name="icon_type"]').val();
    			var bg_color = $(this).find('input[name="bg_color"]').val();
    			var txt_color = $(this).find('input[name="txt_color"]').val();
    			var content = '[mks_button size="'+size+'" title="'+title+'" style="'+style+'" url="'+url+'" target="'+target+'" bg_color="'+bg_color+'" txt_color="'+txt_color+'" icon="'+icon+'" icon_type="'+icon_type+'"]';
    			mks_shortcode.setContent(content);
			});
			
			if($('#mks_button_bg_color').length && jQuery.isFunction(jQuery.fn.wpColorPicker)){
    		$('#mks_button_bg_color').wpColorPicker(); 		
    	}
    	
    	if($('#mks_button_txt_color').length && jQuery.isFunction(jQuery.fn.wpColorPicker)){
    		$('#mks_button_txt_color').wpColorPicker();
    	}
    	
			$('#mks_shortcode_buttons .mks_icon_pick_button').click(function(e) {
    			e.preventDefault();
    			var holder = $(this).closest('.mks_icon_pick_hold');
    			
    			holder.find('.mks_icon_list').toggle();
			});
			
			$('#mks_shortcode_buttons ul.mks_icon_list li a').click(function(e) {
    			e.preventDefault();
    			var holder = $(this).closest('.mks_icon_pick_hold');
    			holder.find('.mks_icon_data_preview').html($(this).html());
    			holder.find('.mks_icon_data').val($(this).attr("mks-data-icon"));
    			holder.find('.mks_icon_type').val($(this).attr("mks-icon-type"));
    			holder.find('.mks_icon_list').toggle();
			});
			
	})(jQuery);
	/* ]]> */
</script>