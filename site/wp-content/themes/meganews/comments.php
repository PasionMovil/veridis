<div class="post-desc">
	<h1>
		<?php
			
				printf( _n( 'There is %1$s comment', 'There are %1$s comments', get_comments_number(), '' ), number_format_i18n( get_comments_number() ), '' . get_the_title() . '' );
		
			
		?>
	</h1>
	<?php wp_list_comments('avatar_size=55'); ?>
	<?php paginate_comments_links(array('prev_text' => '&lsaquo; Previous', 'next_text' => 'Next &rsaquo;')); ?>
	<h1 class="leave-reply"><?php _e('Leave a comment','meganews'); ?></h1>
	<p class="leave-opinion"><?php _e('Want to express your opinion?','meganews'); ?> <br /><?php _e('Leave a reply!','meganews'); ?></p>
	<?php comment_form(); ?>
	<div class="clear"></div>
</div>
