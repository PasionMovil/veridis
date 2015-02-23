<form id="mks_shortcode_pullquotes">
	<table class="form-table">
		<tbody>
			<tr>
		 		<th><h3><?php _e('Style Options','mks-shortcodes'); ?></h3></th><td>&nbsp;</td>
			</tr>
	   <tr>
				<th>
					<?php _e('Align','mks-shortcodes'); ?>:
				</th>
				<td><input type="radio" name="align" value="left" checked /> <?php _e('Left','mks-shortcodes'); ?>&nbsp;&nbsp; 
					<input type="radio" name="align" value="right"/> <?php _e('Right','mks-shortcodes'); ?>&nbsp;&nbsp;
				</td>
		</tr>
		<tr>
				<th><?php _e('Width','mks-shortcodes'); ?>:</th>
				<td><input type="text" name="width" class="small-text" value="300"/> px</td>
		</tr>
	  <tr>
				<th><?php _e('Font size','mks-shortcodes'); ?>:</th>
				<td><input type="text" name="size" value="24" class="small-text"/> px </td>
		</tr>
		<tr>
				<th><?php _e('Background Color','mks-shortcodes'); ?>:</th>
				<td><input id="mks_pullquote_bg_color" type="text" name="bg_color" value="#000000"/></td>
		</tr>
		<tr>
				<th><?php _e('Text Color','mks-shortcodes'); ?>:</th>
				<td><input id="mks_pullquote_txt_color" type="text" name="txt_color" value="#ffffff"/></td>
		</tr>
		<tr>
				<th><input type="submit" class="button-primary" value="<?php _e('Insert Boxed Quote','mks-shortcodes'); ?>"></th> 
				<td>&nbsp;</td>
		</tr>
	
	</tbody>
	</table>
</form>

<script type="text/javascript">
	/* <![CDATA[ */
  (function($) {
    	$('#mks_shortcode_pullquotes').submit(function(e) {
    			e.preventDefault();
    			tb_remove();
    			var align = $(this).find('input[name="align"]:checked').val();
    			var width = $(this).find('input[name="width"]').val();
    			var size = $(this).find('input[name="size"]').val();
    			var bg_color = $(this).find('input[name="bg_color"]').val();
    			var txt_color = $(this).find('input[name="txt_color"]').val();
    			var content = '[mks_pullquote align="'+align+'" width="'+width+'" size="'+size+'" bg_color="'+bg_color+'" txt_color="'+txt_color+'"]Pullquote sample text[/mks_pullquote]';
    			mks_shortcode.setContent(content);
			});
			
			if($('#mks_pullquote_bg_color').length && jQuery.isFunction(jQuery.fn.wpColorPicker)){
    		$('#mks_pullquote_bg_color').wpColorPicker(); 		
    	}
    	
    	if($('#mks_pullquote_txt_color').length && jQuery.isFunction(jQuery.fn.wpColorPicker)){
    		$('#mks_pullquote_txt_color').wpColorPicker();
    	}
	})(jQuery);
	/* ]]> */
</script>