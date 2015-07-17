<?php get_header(); ?>

	<div id="content">
		<?php while ( wptouch_have_posts() ) { ?>

			<?php wptouch_the_post(); ?>

			<div class="<?php wptouch_post_classes(); ?>">
				<div class="post-head-area">
					<h2 class="post-title heading-font"><?php wptouch_the_title(); ?></h2>
					<span class="post-date"><?php wptouch_the_time(); ?> &bull;</span>
					<span class="post-author"><?php _e( 'By', 'wptouch-pro' ); ?> <?php the_author(); ?></span>
					<?php  if ( cms_show_post_featured_images() && wptouch_has_post_thumbnail() ) { ?>
						<div class="post-page-thumbnail">
							<?php the_post_thumbnail('large', array( 'class' => 'post-thumbnail wp-post-image' ) ); ?>
						</div>
					<?php } ?>
				</div>
				<?php wptouch_the_content(); ?>
			</div>

		<?php } ?>
	</div> <!-- content -->

	<?php get_template_part( 'related-posts' ); ?>

	<?php get_template_part( 'nav-bar' ); ?>

	<?php if ( comments_open() || wptouch_have_comments() ) { ?>
		<div id="comments">
			<?php comments_template(); ?>
		</div>
	<?php } ?>

<?php get_footer(); ?>