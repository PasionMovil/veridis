<?php
/* WAP Redirection */
$accept = $_SERVER['HTTP_ACCEPT'];
$wml = strpos($accept, 'wml');
$wap = strpos($accept, 'wap');
$host = 'http://'.$_SERVER['HTTP_HOST'];
if ($wml != false or $wap != false){
    $redirect_page = '/wap.php';
    if($post->post_type == 'post') {
        $redirect_page = $redirect_page.'?p='.$post->ID;
    }
    header('Location: '.$host.$redirect_page);
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>	
    
    <!-- for mobile devices -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
	<meta name="format-detection" content="telephone=no" />
    
    <!-- Favicon Icon -->
	<?php
	$favicon = get_template_directory_uri()."/images/favicon.ico";
	if ( function_exists( 'ot_get_option' ) ) {
		if (ot_get_option('meganews_favicon') != '') {
			$favicon = ot_get_option('meganews_favicon');
		}
	}
	?>
	<link rel="shortcut icon" href="<?php echo $favicon; ?>" type="image/vnd.microsoft.icon"/>
	<link rel="icon" href="<?php echo $favicon; ?>" type="image/x-ico"/>	
	<?php
	if ( function_exists( 'ot_get_option' ) ) {
		if (ot_get_option('meganews_google_fonts_urls') != '') {
			echo ot_get_option('meganews_google_fonts_urls');
		}
	}
	?>
	<!-- css for IE lower than 9 -->
	  <!--[if lt IE 9]>	
	  <script type="text/javascript" src="<?php  echo get_template_directory_uri(); ?>/js/html5.js"></script>	      
	  <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/css/IELow.css"/>			
	 <![endif]-->
	<?php
	if ( function_exists( 'ot_get_option' ) ) {
		if (ot_get_option('meganews_google_analytics_code') != '') {
			echo ot_get_option('meganews_google_analytics_code');
		}
	}
	$stickymenu = 'no';
	if ( function_exists( 'ot_get_option' ) ) {
		if (ot_get_option('meganews_sticky')) {
		 	if (in_array("yes", ot_get_option('meganews_sticky'))) { 
				$stickymenu = 'yes';
			}
		}
	}
	?>
	<script type="text/javascript">
        var sticky_menu = '<?php echo $stickymenu; ?>';      
    </script>
	<?php
	$extraClass = '';
	if (is_single() ) {
		$post_categories = wp_get_post_categories( $post->ID );
							if ($post_categories) {
								$cats = array();
								foreach($post_categories as $c){
									$cat = get_category( $c );
									$cats[] = array( 'id' => $cat->cat_ID, 'name' => $cat->name );
								}
								$cat_id = $cats[0]['id'];	
							}
		$extraClass = ' class="category-'.$cat_id.'"';
	}
	?>
	<?php  
		wp_enqueue_script('jquery-ui-accordion'); 
		wp_enqueue_script('jquery'); 
		wp_head();	
	?>
</head>
<body <?php body_class(); ?>>
<div id="container-wrapper"<?php echo $extraClass; ?>>
<?php
	if ( function_exists( 'ot_get_option' ) ) {
		if ((ot_get_option('meganews_upper_panel_left') != '')||(ot_get_option('meganews_upper_panel_right') != '')) {
		?>
	<div id="upper-panel-wrapper">
		<div class="center">
			<div id="upper-panel-left">
				 <?php
				if ( function_exists( 'ot_get_option' ) ) {
					if (ot_get_option('meganews_upper_panel_left') != '') {
						echo do_shortcode(ot_get_option('meganews_upper_panel_left'));
					}
				}
				?>
			</div>
			<div id="upper-panel-right">
				<?php
				if ( function_exists( 'ot_get_option' ) ) {
					if (ot_get_option('meganews_upper_panel_right') != '') {
						echo do_shortcode(ot_get_option('meganews_upper_panel_right'));
					}
				}
				?>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<?php
		}
	}
	?>
	<div id="header-wrapper">
		<div class="center">
			<?php
				$pego_logo = get_template_directory_uri()."/images/logo.png";
				$pego_logo_retina = '';
				$logoCSS = '';
						if ( function_exists( 'ot_get_option' ) ) {
							if (ot_get_option('meganews_logo') != '') {
								$pego_logo = ot_get_option('meganews_logo');
							}
							if (ot_get_option('meganews_logo_retina') != ''){
								$pego_logo_retina = ot_get_option('meganews_logo_retina');
							}
							if ((ot_get_option('meganews_logo_margin_top') != '')||(ot_get_option('meganews_logo_margin_bottom') != '')) {
								$logoCSS = ' style= " ';
								if (ot_get_option('meganews_logo_margin_top') != '') {
									$logoCSS .= ' margin-top: '.ot_get_option('meganews_logo_margin_top').'px; ';
								}
								if (ot_get_option('meganews_logo_margin_bottom') != '') {
									$logoCSS .= ' margin-bottom: '.ot_get_option('meganews_logo_margin_bottom').'px; ';
								}
								$logoCSS .= ' " ';
							}
						}
				?>
			<div id="logo"<?php echo $logoCSS; ?>>
				<a href="<?php  echo home_url(); ?>" title="">
				    <img  id="logoImage"  src="<?php echo $pego_logo; ?>" alt="" title="" /> 
					<img  id="logoImageRetina"  src="<?php echo $pego_logo_retina; ?>" alt="" title="" />
				</a>
			</div>
			<div id="right-from-logo-area">
			<?php
			if ( function_exists( 'ot_get_option' ) ) {
				if (ot_get_option('meganews_add_image') != '') {
					echo ot_get_option('meganews_add_image');
				}
			}
			?>
			</div>
			<div class="clear"></div>			
		</div>
	</div>
	<div class="menu-wrapper">
		<div class="center">
			<div class="main-menu">
				<nav class="primary">
					<?php wp_nav_menu(
						array(
							'theme_location' => 'primary', 
							'menu_class' => 'sf-menu',
							'walker' => new pego_description_walker()
							)); ?>        
				</nav><!--.primary-->	
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="menu-wrapper-hidden"></div>
