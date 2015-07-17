<div class="entry">
	<?php if ( wptouch_has_post_thumbnail() ) { ?>
		<img src="<?php wptouch_the_post_thumbnail( 'thumbnail' ); ?>" alt="thumbnail" class="post-thumbnail wp-post-image" />
	<?php } else { ?>
		<span class="placeholder">
			<!-- styled in css -->
			<?php if ( foundation_is_theme_using_module( 'wptouch-icons' ) ) { ?>
				<i class="wptouch-icon-camera-retro"></i>
			<?php } ?>
		</span>
	<?php } ?>

	<a href="<?php wptouch_the_permalink(); ?>">
		<div class="entry-inner">
			<span class="post-date"><?php echo date_i18n( get_option( 'date_format' ), get_the_time( 'U' ) ); ?></span>
			<h2 class="post-title heading-font"><?php the_title(); ?></h2>
			<?php if ( foundation_is_theme_using_module( 'wptouch-icons' ) ) { ?>
				<a class="post-link-button">
					<?php if ( wptouch_should_load_rtl() ) { ?>
					<i class="wptouch-icon-angle-left"></i>
					<?php } else { ?>
					<i class="wptouch-icon-angle-right"></i>
					<?php } ?>
				</a>
			<?php } ?>
		</div><!-- entry-inner -->
	</a><!-- permalink -->
</div><!-- entry -->

<?php get_template_part( 'nav-bar' ); ?>