<!-- Back Button for Web-App Mode -->
<div class="wptouch-icon-reply back-button tappable"><!-- css-button --></div>

<div class="page-wrapper <?php cms_css_noise(); ?>" style="background-image: url(<?php cms_get_background_image(); ?>); background-size: 25%;">
	<div id="header" class="<?php cms_css_noise(); ?>">
			<div id="header-title-logo">
				<a href="<?php wptouch_bloginfo( 'url' ); ?>">
					<?php if ( foundation_has_logo_image() ) { ?>
						<img id="header-logo" src="<?php foundation_the_logo_image(); ?>" alt="logo image" />
					<?php } else { ?>
						<h1 class="heading-font"><?php wptouch_bloginfo( 'site_title' ); ?></h1>
					<?php } ?>
				</a>
			</div>
			<div id="header-buttons">
				<?php if ( wptouch_has_menu( 'main_menu' ) ) { ?>
					<a href="#" id="page-menu-button" class="button-dark show-hide-toggle no-ajax" data-effect-target="main-menu" data-effect-close="alt-menu"><?php _e( 'menu', 'wptouch-pro' ); ?> <i class="wptouch-icon-caret-down"></i></a>
				<?php } ?>
				<?php  if ( cms_show_search() ) { ?>
					<a href="#" id="search-menu-button" class="button-dark show-hide-toggle no-ajax needsclick" data-effect-target="search-dropper"><i class="wptouch-icon-search"></i></a>
				<?php } ?>
				<?php if ( function_exists( 'wptouch_fdn_show_login' ) ) { ?>
					<?php if ( wptouch_fdn_show_login() ) { ?>
						<?php if ( !is_user_logged_in() ) { ?>
							<a href="#" id="login-button" class="button-dark login-toggle no-ajax">
								<i class="wptouch-icon-signin"></i> <?php _e( 'login', 'wptouch-pro' ); ?>
							</a>
						<?php } else { ?>
							<a href="<?php echo wp_logout_url( esc_url_raw( $_SERVER['REQUEST_URI'] ) ); ?>" id="login-button" class="button-dark">
								<i class="wptouch-icon-signout"></i> <?php _e( 'logout', 'wptouch-pro' ); ?>
							</a>
						<?php } ?>
					<?php } ?>
				<?php } ?>
				<?php if ( wptouch_has_menu( 'alternate_menu' ) ) { ?>
					<a href="#" id="alt-menu-button" class="button-dark show-hide-toggle no-ajax" data-effect-target="alt-menu" data-effect-close="main-menu"><?php cms_secondary_menu_title(); ?> <i class="wptouch-icon-caret-down"></i></a>
				<?php } ?>
			</div>

		<?php  if ( cms_show_search() ) { ?>
			<div id="search-dropper">
				<div id="wptouch-search-inner">
					<form method="get" id="searchform" action="<?php wptouch_bloginfo( 'search_url' ); ?>/">
						<input type="text" name="s" id="search-text" placeholder="<?php _e( 'search this website', 'wptouch-pro' ); ?>&hellip;" />
						<input name="submit" type="submit" id="search-submit" value="<?php _e( 'search', 'wptouch-pro' ); ?>" class="button-dark" />
					</form>
				</div>
			</div>
		<?php } ?>

	<?php if ( ( is_home() || is_front_page() || is_archive() ) && cms_show_category_slider() ) { ?>
		<div id="section-slider" class="generic-slider">
			<?php if ( function_exists( 'wptouch_fdn_ordered_cat_list' ) ) wptouch_fdn_ordered_cat_list( 15, false ); ?>
		</div>
	<?php } ?>


	<?php if ( is_search() || is_archive() ) { // On single post pages load the nav bar // ?>
		<div id="single-nav-bar" class="<?php cms_css_noise(); ?>">
			<?php if ( is_single() ) { ?>
				<div id="nav-controls">
					<?php if ( wptouch_fdn_if_previous_post_link() ) { ?>
						<a href="<?php wptouch_fdn_get_previous_post_link(); ?>" class="button-dark older"><i class=" wptouch-icon-caret-<?php if ( wptouch_should_load_rtl() ) echo 'right'; else echo 'left'; ?> prev-button"></i> <?php _e( 'older posts', 'wptouch-pro' ); ?></a>
					<?php } ?>

					<?php if ( wptouch_fdn_if_next_post_link() ) { ?>
						<a href="<?php wptouch_fdn_get_next_post_link(); ?>" class="button-dark newer"><?php _e( 'newer posts', 'wptouch-pro' ); ?> <i class=" wptouch-icon-caret-<?php if ( wptouch_should_load_rtl() ) echo 'left'; else echo 'right'; ?> next-button"></i></a>
					<?php } ?>
				</div>
			<?php } else { ?>
				<?php wptouch_fdn_archive_title_text(); ?>
			<?php } ?>
		</div>
	<?php } ?>

	<?php if ( wptouch_has_menu( 'main_menu' ) ) { ?>
		<div id="main-menu" class="wptouch-menu show-hide-menu <?php cms_css_noise(); ?>">
			<?php wptouch_show_menu( 'main_menu' ); ?>
		</div>
	<?php } ?>

	<?php if ( wptouch_has_menu( 'alternate_menu' ) ) { ?>
		<div id="alt-menu" class="wptouch-menu show-hide-menu <?php cms_css_noise(); ?>">
			<?php wptouch_show_menu( 'alternate_menu' ); ?>
		</div>
	<?php } ?>

	</div><!-- #header -->

	<?php do_action( 'wptouch_advertising_top' ); ?>

	<?php if ( is_home() || is_front_page() ) { ?>
		<?php if ( function_exists( 'foundation_featured_slider' ) ) { ?>
			<?php foundation_featured_slider(); ?>
		<?php } ?>
	<?php } ?>