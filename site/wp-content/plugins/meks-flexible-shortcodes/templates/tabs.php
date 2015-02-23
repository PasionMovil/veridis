<form id="mks_shortcode_tabs">
	
	<table class="form-table">
		<tbody>
			<tr>
		 		<th><h3><?php _e('Options','mks-shortcodes'); ?></h3></th><td>&nbsp;</td>
			</tr>
	   <tr>
		<tr>
				<th><?php _e('Number of items','mks-shortcodes'); ?>:</th>
				<td><input type="text" name="num_items" value="3" class="small-text" /></td>
		</tr>
		
		<tr>
				<th>
					<?php _e('Navigation type','mks-shortcodes'); ?>:
				</th>
				<td>
					<input type="radio" name="nav" value="horizontal" checked /> <?php _e('Horizontal','mks-shortcodes'); ?>&nbsp;&nbsp;
					<input type="radio" name="nav" value="vertical"/> <?php _e('Vertical','mks-shortcodes'); ?>&nbsp;&nbsp;
				</td>
		</tr>
	
		<tr>
				<th><input type="submit" class="button-primary" value="<?php _e('Insert Tabs','mks-shortcodes'); ?>"></th> 
				<td>&nbsp;</td>
		</tr>
	
	</tbody>
	</table>
</form>

<script type="text/javascript">
	/* <![CDATA[ */
  (function($) {
    	$('#mks_shortcode_tabs').submit(function(e) {
    			e.preventDefault();
    			tb_remove();
    			var num_items = parseInt($(this).find('input[name="num_items"]').val());
    			var nav = $(this).find('input[name="nav"]:checked').val();
    			var content = '[mks_tabs nav="'+nav+'"]';
    			for(i=0; i < num_items; i++){
    				content += '<br/>[mks_tab_item title="Title '+(i+1)+'"]<br/>Example content '+(i+1)+'<br/>[/mks_tab_item]';
    			}
    			content += '<br/>[/mks_tabs]';
    			
    			mks_shortcode.setContent(content);
			});
			
			
			
	})(jQuery);
	/* ]]> */
</script>