<div class="<?php wptouch_post_classes(); ?>">
	<?php if ( has_cms_homepage_message() && is_front_page() ) { ?>	
		<div class="front-message">
			<?php cms_homepage_message(); ?>
		</div>	
	<?php } else { ?>
		<?php if ( cms_show_page_titles() ) { ?>
			<div class="post-head-area">
				<h2 class="post-title heading-font"><?php the_title(); ?></h2>
			</div>
		<?php } ?>
		<?php wptouch_the_content() ; ?>         
	<?php } ?>
</div><!-- post classes -->