<form id="mks_shortcode_separators">
	<table class="form-table">
		<tbody>
			<tr>
		 		<th><h3><?php _e('Options','mks-shortcodes'); ?></h3></th><td>&nbsp;</td>
			</tr>
	   <tr>
				<th>
					<?php _e('Style','mks-shortcodes'); ?>:
				</th>
				<td><input type="radio" name="style" value="solid" checked /> <?php _e('Solid','mks-shortcodes'); ?><br/>
					<input type="radio" name="style" value="double" /> <?php _e('Double','mks-shortcodes'); ?><br/>
					<input type="radio" name="style" value="dotted" /> <?php _e('Dotted','mks-shortcodes'); ?><br/>
					<input type="radio" name="style" value="dashed" /> <?php _e('Dashed','mks-shortcodes'); ?><br/>
					<input type="radio" name="style" value="blank" /> <?php _e('Blank (empty space)','mks-shortcodes'); ?>
				</td>
		</tr>
		<tr>
				<th><?php _e('Height','mks-shortcodes'); ?>:</th>
				<td><input type="text" name="height" class="small-text" value="2"/> px</td>
		</tr>
		<tr>
				<th><input type="submit" class="button-primary" value="<?php _e('Insert Separator','mks-shortcodes'); ?>"></th> 
				<td>&nbsp;</td>
		</tr>
	
	</tbody>
	</table>
</form>

<script type="text/javascript">
	/* <![CDATA[ */
  (function($) {
    	$('#mks_shortcode_separators').submit(function(e) {
    			e.preventDefault();
    			tb_remove();
    			var style = $(this).find('input[name="style"]:checked').val();
    			var height = $(this).find('input[name="height"]').val();
    			var content = '[mks_separator style="'+style+'" height="'+height+'"]';
    			mks_shortcode.setContent(content);
			});
	})(jQuery);
	/* ]]> */
</script>