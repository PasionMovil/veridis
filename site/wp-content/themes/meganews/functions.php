<?php
	
/***************************************************************  
Javascript files include
***************************************************************/
	function pego_javascripts() {
		wp_enqueue_script('modernizr', get_template_directory_uri() . '/js/modernizr.custom.js','','',true);
		wp_enqueue_script('superfish', get_template_directory_uri() . '/js/superfish.js','','',true);
		wp_enqueue_script('scroller', get_template_directory_uri() . '/js/jquery.li-scroller.1.0.js','','',true);
		wp_enqueue_script('piechart', get_template_directory_uri() . '/js/jquery.easy-pie-chart.js','','',true);
		wp_enqueue_script( 'prettyphoto', get_template_directory_uri() . '/wpbakery/js_composer/assets/lib/prettyphoto/js/jquery.prettyPhoto.js','','',true);
		wp_enqueue_script('counter', get_template_directory_uri() . '/js/jquery.countTo.js','','',false);					
		wp_enqueue_script('flexslider', get_template_directory_uri() . '/js/jquery.flexslider-min.js','','',true);
		wp_enqueue_script('qrotator', get_template_directory_uri() . '/js/jquery.cbpQTRotator.min.js','','',true);
		wp_enqueue_script('mediaplayer', get_template_directory_uri() . '/js/mediaelement-and-player.min.js','','',true);
		wp_enqueue_script('mobilemenu', get_template_directory_uri() . '/js/jquery.mobilemenu.js','','',true);
		wp_enqueue_script('custom-javascript', get_template_directory_uri() . '/js/custom.js','','',true);		
	}
	add_action('wp_enqueue_scripts', 'pego_javascripts');	
	
/***************************************************************  
Style files include
***************************************************************/
	function pego_theme_styles() { 
		global $pego_prefix;	
		wp_enqueue_style( 'icons-style', get_template_directory_uri() . '/css/icons-css.css', array(), '1.0', 'all' );	
		wp_enqueue_style( 'flexslider-style', get_template_directory_uri() . '/css/flexslider.css', array(), '1.0', 'all' );	
		wp_enqueue_style( 'prettyphoto-style', get_template_directory_uri() . '/wpbakery/js_composer/assets/lib/prettyphoto/css/prettyPhoto.css', array(), '1.0', 'all' );	
		wp_enqueue_style( 'mediaplayer-style', get_template_directory_uri() . '/css/mediaelementplayer.css', array(), '1.0', 'all' );
		wp_enqueue_style( 'font-awesome-style', get_template_directory_uri() . '/css/font-awesome.min.css', array(), '1.0', 'all' );	
		wp_enqueue_style( 'default-style', get_template_directory_uri() . '/style.css', array(), '1.0', 'all' );	
		wp_enqueue_style( 'media-style', get_template_directory_uri() . '/css/media.css', array(), '1.0', 'all' );	
	}	
	add_action('wp_enqueue_scripts', 'pego_theme_styles');


/***************************************************************  
Javascript files include for backend
***************************************************************/	
	
 	function pego_admin_scripts() {
      	wp_enqueue_media(); 
      	wp_register_script('my-admin-js', get_template_directory_uri() .'/js/admin-javascript.js', array('jquery'));
       	wp_enqueue_script('my-admin-js');
	}
	add_action('admin_enqueue_scripts', 'pego_admin_scripts');

	
	
/***************************************************************  
Add shorcodes to widgets
***************************************************************/
	add_filter('widget_text', 'do_shortcode');
	
/***************************************************************  
Menu declaration
***************************************************************/
	register_nav_menu( 'primary', __( 'Navigation Menu', 'meganews' ) );

	
/***************************************************************  
 Enqueue the Google fonts
***************************************************************/
	function pego_theme_fonts() {
  	  	$protocol = is_ssl() ? 'https' : 'http';
  	  	wp_enqueue_style( 'mytheme-patuaone', "$protocol://fonts.googleapis.com/css?family=Patua+One" );
  	  	wp_enqueue_style( 'mytheme-opensans', "$protocol://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700italic,700,800,800italic" );
	}
	add_action( 'wp_enqueue_scripts', 'pego_theme_fonts' );
	

/***************************************************************  
Added support for post thumbnails
***************************************************************/
	if ( function_exists( 'add_image_size' ) ) add_theme_support( 'post-thumbnails' );

	if ( function_exists( 'add_image_size' ) ) { 
		add_image_size('PostSectionLarge', 770, 350, true);
		add_image_size('Post1', 920, 510, true);
				
		add_image_size('PostSection41', 776, 390, true);
		add_image_size('PostSection31', 579, 390, true);
		add_image_size('PostSection21', 382, 390, true);
		add_image_size('PostSection22', 382, 189, true);
		add_image_size('PostSection11', 185, 390, true);
		add_image_size('PostSection12', 185, 189, true);
	}
	
/***************************************************************  
Get post from post title
***************************************************************/
	function pego_get_post_by_title($page_title, $output1 = OBJECT) {
		global $wpdb;
			$post = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='post'", $page_title ));
			if ( $post )
				return get_post($post, $output1);

		return null;
	}
	
/***************************************************************  
Menu customization
***************************************************************/	
class pego_description_walker extends Walker_Nav_Menu
	{
      function start_el(&$output, $item, $depth = 0, $args = Array(), $id = 0)
      {
	   global $wp_query;
	   
	   $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

	   $class_names = $value = '';

	   $classes = empty( $item->classes ) ? array() : (array) $item->classes;

	   $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
	   $class_names = ' class="'. esc_attr( $class_names );
	  
	    
	 	if ($item->subtitle == 'Megamenu Columns') {
		
			global $wpdb;
			$has_children = $wpdb -> get_var( "SELECT COUNT(meta_id) FROM {$wpdb->prefix}postmeta WHERE meta_key='_menu_item_menu_item_parent' AND meta_value='" . $item->ID . "'" );
 			$class_names .= ' mega-menu-columns mega-menu-columns-count-'.$has_children;
		
		}
		
		$isCategory1 = strpos($class_names,'menu-item-object-category');	
		
		if ($item->subtitle  == 'Latest 4 post from Category') {
			$class_names .= ' mega-menu-posts mega-menu-posts-count-4';
		}
	  
	    $class_names .= '"';
	    
	   $content_page_id = get_post_meta ( $item->ID, '_menu_item_object_id', true );
	  // $content_page = get_post ( $content_page_id );
	 
	  // $post_name = $content_page->post_name;
	  // $typee = get_post_type( $content_page_id);
	  // $template_set = '';
				   	
		$allClasses=$class_names;
		$newHref= home_url();
		$isCategory = strpos($allClasses,'menu-item-object-category');	

		$newHref = $item->url;
		if ($isCategory == true) {			
			
			$class_names = str_replace("menu-item-object-category", "menu-item-object-category menu-cat-item-".$content_page_id, $allClasses);
		}
		
		
	   
	   $output .= $indent . '<li ' . $value . $class_names .'>';

	   $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
	   $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
	   $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
	   $attributes .= ! empty( $item->url )        ? ' href="'.$newHref.'"' : '';

	   $prepend = '';
	   $append = '';
   
   
	   if($depth != 0)
	   {
				 $description = $append = $prepend = "";
	   }	
		$item_output = '<a'. $attributes .'>';
		$item_output .= $prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append;
		$item_output .= '</a>';
			
			
		if (($isCategory1 == true)&&($item->subtitle  == 'Latest 4 post from Category'))  {
			$args = array('posts_per_page' => 4,	 'order'=> 'DESC', 'orderby' => 'post_date', 'cat' => $item->object_id );
			$port_query = new WP_Query( $args );
	      
	   	 $item_output .= '<ul class="submenu_1 sub-menu" style="display: none; visibility: hidden;">';
  	 		if( $port_query->have_posts() ) : while( $port_query->have_posts() ) : $port_query->the_post(); 
  	  			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'PostSection31' );
				$item_output .= '<li class="menu-item menu-item-type-post_type menu-item-object-page">
  	   						 <a title="'.get_the_title($post->ID).'" href="'.get_permalink().'" class="sf-with-ul">	<img src="'.$image[0].'" alt="'.get_the_title($post->ID).'" />
							<span class="post-link-in-menu" href="'.get_permalink().'" class="sf-with-ul">'.get_the_title().'</span></a>';
  	 
  		 	endwhile; endif; wp_reset_postdata();
			 $item_output .= '</ul>';
		}
			
			

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args, $id );
		}
			
		function start_lvl(&$output, $depth = 0, $args = Array()) {
			$indent = str_repeat("\t", $depth);
			$output .= "\n$indent<ul class=\"submenu_1\">\n";
		}	
			  
	}	
	
	
/***************************************************************  
Widget areas
***************************************************************/
	function pego_new_widgets_init() {	
	
		register_sidebar(array(
			'name' => 'Blog sidebar',
			'id' => 'blog-sidebar',
			'description'   => 'These are widgets for the sidebar.',
			'before_widget' => '<div id="%1$s" class="widget animationClass %2$s" style="margin-bottom:30px;">',
			'after_widget'  => '<div class="clear"></div></div><div class="clear"></div>',
			'before_title'  => '<h3 class="sidebar-title">',
			'after_title'   => '</h3><div class="title_stripes_sidebar"></div><div class="clear"></div>'
		));	

		//custom sidebars from admin
		$argsSidebars = array('post_type'=> 'sidebars', 'posts_per_page' => -1, 'order'=> 'DESC', 'orderby' => 'post_date'  );
		$allSidebars = get_posts($argsSidebars);	
	
		if($allSidebars) {
			foreach($allSidebars as $singleSidebar)
			{ 	
				$sidebarTitle = $singleSidebar->post_title;	
				$sidebarName= $singleSidebar->post_name;				
				register_sidebar(array(
					'name' => $sidebarTitle,
					'id' => $sidebarName,
					'description'   => 'These are widgets for the sidebar.',
					'before_widget' => '<div id="%1$s" class="widget animationClass %2$s" style="margin-bottom:30px;">',
					'after_widget'  => '</div><div class="clear"></div>',
					'before_title'  => '<h3 class="sidebar-title">',
					'after_title'   => '</h3><div class="title_stripes_sidebar"></div><div class="clear"></div>'
				));				
			}
		}	
		
		register_sidebar(array(
			'name' => 'Footer First Column Sidebar',
			'id' => 'first-footer-column-sidebar',
			'before_widget' => '<div id="%1$s" class="widget animationClass %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h1 class="widget-title">',
			'after_title' => '</h1>',
		));
		register_sidebar(array(
			'name' => 'Footer Second Column Sidebar',
			'id' => 'second-footer-column-sidebar',
			'before_widget' => '<div id="%1$s" class="widget animationClass %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h1 class="widget-title">',
			'after_title' => '</h1>',
		));
		register_sidebar(array(
			'name' => 'Footer  Third Column Sidebar',
			'id' => 'third-footer-column-sidebar',
			'before_widget' => '<div id="%1$s" class="widget animationClass %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h1 class="widget-title">',
			'after_title' => '</h1>',
		));		
	}
	add_action( 'widgets_init', 'pego_new_widgets_init' );	
	
	
	
/***************************************************************  
Meta boxes for post categories
***************************************************************/	

	//include the main class file
	require_once("functions/Tax-meta-class/Tax-meta-class.php");
	if (is_admin()){
	  /*  prefix of meta keys, optional */
	  $prefix = 'pego_';
	  /* meta box configuration */
	  $config = array(
		'id' => 'demo_meta_box',          // meta box id, unique per meta box
		'title' => 'Demo Meta Box',          // meta box title
		'pages' => array('category'),        // taxonomy name, accept categories, post_tag and custom taxonomies
		'context' => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
		'fields' => array(),            // list of meta fields (can be added by field arrays)
		'local_images' => false,          // Use local or hosted images (meta box images for add/remove)
		'use_with_theme' => true          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
	  );
	  
	  /* Initiate meta box */
	  $my_meta =  new Tax_Meta_Class($config);  
	  /* Adding fields to meta box  */
	  //Category Color
	  $my_meta->addColor($prefix.'category_color',array('name'=> __('Category color ','tax-meta')));
	   //Category show 
	  $my_meta->addSelect($prefix.'category_show_type',array('type1'=>'Type#1','type2'=>'Type#2'),array('name'=> __('Category show type ','tax-meta'), 'std'=> array('type1')));
	   //latest post show
	  $my_meta->addCheckbox($prefix.'category_show_latest',array('name'=> __('Show latest news ticker?','tax-meta')));
	 //Featured post section 
	  $my_meta->addSelect($prefix.'category_featured_post_section',array('none'=>'None', 'type1'=>'Type#1','type2'=>'Type#2', 'type3'=>'Type#3', 'type4'=>'Type#4', 'type5'=>'Type#5'),array('name'=> __('Category Featured Post Section ','tax-meta'), 'std'=> array('none')));
	  //Post category pagination
	  //$my_meta->addText($prefix.'category_pagination',array('name'=> 'Set number of posts where you want to start the pagination. If empty, default WordPress setting will be taken.'));
	  
	   //Sidebar select
	  $allSidebarss = pego_get_all_sidebars();
	  $my_meta->addSelect($prefix.'category_sidebar',$allSidebarss,array('name'=> __('Category sidebar ','tax-meta')));
	  
	  

	 //Finish Meta Box Decleration
	  $my_meta->Finish();
	}

	
/***************************************************************  
Include php files
***************************************************************/	
	define('PEGO_FILEPATH', get_template_directory());
	define('PEGO_DIRECTORY', get_template_directory_uri());
	define( 'OPTIONS', 'meganews_options' );
	
	include("functions/custom-sidebar.php");
	include("functions/custom-post.php");
	include("functions/custom-page.php");
	include("functions/custom-testimonials.php");
	include("functions/widget-latest-posts.php");		
	include("functions/widget-flickr.php");	
	include("functions/widget-posts-in-tab.php");
	include("functions/twitteroauth.php");	
	include("functions/widget-twitter.php");
	
/***************************************************************  
Added support for post formats
***************************************************************/	
add_theme_support( 'post-formats', array( 'image', 'video', 'gallery', 'audio' ) );	
	
add_theme_support( 'automatic-feed-links' );	
	
	
	
/***************************************************************  
Get all categories
***************************************************************/	
function pego_get_all_categories() {	
	$allcategories = get_categories();
	$allCategoriesArray = array();
	if($allcategories) {
		foreach($allcategories as $singleCategory)
		{ 	
			$allCategoriesArray[$singleCategory->cat_ID] = $singleCategory->name;						
		}
	return $allCategoriesArray;
	}
}
/***************************************************************  
Get all sidebars
***************************************************************/	
function pego_get_all_sidebars() {	
	$argsSidebars= array('post_type'=> 'sidebars', 'posts_per_page' => -1, 'order'=> 'DESC', 'orderby' => 'post_date'  );
	$allSidebars = get_posts($argsSidebars);
	$allSidebarsArray = array();
	$allSidebarsArray[0] = 'Blog sidebar';
	if($allSidebars) {
		foreach($allSidebars as $singleSidebar)
		{ 	
			$allSidebarsArray[$singleSidebar->ID] = $singleSidebar->post_title;						
		}
	return $allSidebarsArray;
	}
}
	
	

/***************************************************************  
Get all testimonials
***************************************************************/	
function pego_get_all_test() {	
	//custom sidebars from admin
	$argsTest= array('post_type'=> 'testimonials', 'posts_per_page' => -1, 'order'=> 'DESC', 'orderby' => 'post_date'  );
	$allTest = get_posts($argsTest);	
	$allTestimonials = array();
	if($allTest) {
		foreach($allTest as $singleTest)
		{ 	
			$allTestimonials[$singleTest->ID] = $singleTest->post_title;						
		}
	return $allTestimonials;
	}
}
	

/***************************************************************  
Breadcrumbs
***************************************************************/
	function pego_breadcrumbs() {
	  global $pego_prefix;
	  $delimiter = '&#92;';
	  $name = 'Home'; //text for the 'Home' link
	  $currentBefore = '<span class="current">';
	  $currentAfter = '</span>';
	  if ( function_exists( 'ot_get_option' ) ) {
	  	if (ot_get_option('meganews_breadcrumbs_pretext') != '') {
			echo ot_get_option('meganews_breadcrumbs_pretext').' ';
		}
	   }	
	 
	  if ( !is_home() && !is_front_page() || is_paged() ) { 

		global $post;
		$home = home_url();
		echo '<a href="' . $home . '">' . $name . '</a> ' . $delimiter . ' ';
	 
		if ( is_category() ) {
		  global $wp_query;
		  $cat_obj = $wp_query->get_queried_object();
		  $thisCat = $cat_obj->term_id;
		  $thisCat = get_category($thisCat);
		  $parentCat = get_category($thisCat->parent);
		  if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
		 // echo $currentBefore . 'Archive by category &#39;';
		  echo $currentBefore;
		  single_cat_title();
		 // echo '&#39;' . $currentAfter;
		  echo $currentAfter;
	 
		} 
		if (is_tax('portfolio_categories') ) {

		  echo $currentBefore;
		  single_cat_title();
		  echo $currentAfter;
	 
		} elseif
		( is_day() ) {
		  echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
		  echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
		  echo $currentBefore . get_the_time('d') . $currentAfter;
	 
		} elseif ( is_month() ) {
		  echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
		  echo $currentBefore . get_the_time('F') . $currentAfter;
	 
		} elseif ( is_year() ) {
		  echo $currentBefore . get_the_time('Y') . $currentAfter;
	 
		} elseif ( is_single() ) {
			if (get_post_type() == 'post'){
				$cat = get_the_category();
				foreach($cat as $category) {
					echo  '<a href="'.get_category_link($category->term_id).'" >'.$category->name.'</a> ' . $delimiter . ' ';
				}
			}
			else {
				if (get_post_type() == 'portfolio'){
					$cat = get_the_terms( $post->ID, 'portfolio_categories' );
					foreach($cat as $category) {
						echo  '<a href="'.get_term_link($category->slug, 'portfolio_categories').'" >'.$category->name.'</a> ' . $delimiter . ' ';
					}
				}
			}
		  echo $currentBefore;
		  the_title();
		  echo $currentAfter;
	 
		} elseif ( is_page() && !$post->post_parent ) {
		  echo $currentBefore;
		  the_title();
		  echo $currentAfter;
	 
		} elseif ( is_page() && $post->post_parent ) {
		  $parent_id  = $post->post_parent;
		  $breadcrumbs = array();
		  while ($parent_id) {
			$page = get_page($parent_id);
			$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . ecs_html(get_the_title($page->ID)) . '</a>';
			$parent_id  = $page->post_parent;
		  }
		  $breadcrumbs = array_reverse($breadcrumbs);
		  foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
		  echo $currentBefore;
		  the_title();
		  echo $currentAfter;
	 
		} elseif ( is_search() ) {
		  echo $currentBefore . 'Search results for &#39;' . get_search_query() . '&#39;' . $currentAfter;
	 
		} elseif ( is_tag() ) {
		  echo $currentBefore . 'Posts tagged &#39;';
		  single_tag_title();
		  echo '&#39;' . $currentAfter;
	 
		} elseif ( is_author() ) {
		   global $author;
		  $userdata = get_userdata($author);
		  echo $currentBefore . 'Articles posted by ' . $userdata->display_name . $currentAfter;
	 
		} elseif ( is_404() ) {
		  echo $currentBefore . 'Error 404' . $currentAfter;
		}
	 
		if ( get_query_var('paged') ) {
		  if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
		  echo __('Page', 'meganews') . ' ' . get_query_var('paged');
		  if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
		}
	 
	  }
	}
	function pego_display_breadcrumbs() {
	
		if ( !is_home() && !is_front_page() || is_paged() ) { 
		 if ( function_exists( 'ot_get_option' ) ) {
		 	if (ot_get_option('meganews_show_breadcrumbs')) {
			 if (in_array("yes", ot_get_option('meganews_show_breadcrumbs'))) { 
			?>
			
				<div class="page-breadcrumbs">
					<div class="breadcrumbs-content">
						<?php pego_breadcrumbs(); ?>
					</div>
				</div>
			<?php
			}
		  }
		}
	  }
	}	
	

/***************************************************************  
Custom admin logo
***************************************************************/
	function pego_custom_login_logo() {
		$pego_logo_admin = get_template_directory_uri()."/images/logo.png";
		if ( function_exists( 'ot_get_option' ) ) {
			if (ot_get_option('meganews_admin_logo') != '') {
				$pego_logo_admin = ot_get_option('meganews_admin_logo');
			}
		}		
		echo '<style type="text/css">
			h1 a { background-size: auto !important; width: auto !important; background-image:url('.$pego_logo_admin.') !important;  }
		</style>';
		
    }
    add_action('login_head', 'pego_custom_login_logo');	
	
/***************************************************************  
Move admin bar to footer
***************************************************************/	
	function pego_move_admin_bar() {
    	echo '
    	<style type="text/css">
    body.admin-bar {
        margin-top: -32px;
        padding-bottom: 32px;
    }
    #wpadminbar {
        top: auto !important;
        bottom: 0;
    }
    #wpadminbar .quicklinks>ul>li {
        position:relative;
    }
    #wpadminbar .ab-top-menu>.menupop>.ab-sub-wrapper {
        bottom:28px;
    }
    
    #wpadminbar {
    	position: fixed;
    }
    		</style>';
	}
	if ( is_admin_bar_showing() ) {
		add_action( 'wp_head', 'pego_move_admin_bar' );
	}
	
/***************************************************************  
Get all icons
***************************************************************/	
	function pego_get_all_icons() {
		$allIcons= array('no-icon', 'plus', 'minus', 'info', 'left-thin', 'up-thin', 'right-thin', 'down-thin', 'level-up', 'level-down', 'switch', 'infinity', 'plus-squared', 'minus-squared', 'home', 'keyboard', 'erase', 'pause', 'fast-forward', 'fast-backward', 'to-end', 'to-start', 'hourglass', 'stop', 'up-dir', 'play', 'right-dir','down-dir', 'left-dir', 'adjust', 'cloud', 'star', 'star-empty', 'cup', 'menu', 'moon', 'heart-empty', 'heart', 'note', 'note-beamed', 'layout', 'flag', 'tools', 'cog', 'attention', 'flash', 'record', 'cloud-thunder', 'tape', 'flight', 'mail', 'pencil', 'feather', 'check', 'cancel', 'cancel-circled', 'cancel-squared', 'help', 'quote', 'plus-circled', 'minus-circled', 'right', 'direction', 'forward', 'ccw', 'cw', 'left', 'up', 'down', 'list-add', 'list', 'left-bold', 'right-bold', 'up-bold', 'down-bold', 'user-add', 'help-circled', 'info-circled', 'eye', 'tag', 'upload-cloud', 'reply', 'reply-all', 'code', 'export', 'print', 'retweet', 'comment', 'chat', 'vcard', 'address', 'location', 'map', 'compass', 'trash', 'doc', 'docs', 'doc-landscape', 'archive', 'rss', 'share', 'basket', 'shareable', 'login', 'logout', 'volume', 'resize-full', 'resize-small', 'popup', 'publish', 'window', 'arrow-combo', 'chart-pie', 'language', 'air', 'database', 'drive', 'bucket', 'thermometer', 'down-circled', 'left-circled', 'right-circled', 'up-circled', 'down-open', 'left-open', 'right-open', 'up-open', 'down-open-mini', 'left-open-mini', 'right-open-mini', 'up-open-mini', 'down-open-big', 'left-open-big', 'right-open-big', 'up-open-big', 'progress-0', 'progress-1', 'progress-2', 'progress-3', 'back-in-time', 'network', 'inbox', 'install', 'lifebuoy', 'mouse', 'dot', 'dot-2', 'dot-3', 'suitcase', 'flow-cascade', 'flow-branch', 'flow-tree', 'flow-line', 'flow-parallel', 'brush', 'paper-plane', 'magnet', 'gauge', 'traffic-cone', 'cc', 'cc-by', 'cc-nc', 'cc-nc-eu', 'cc-nc-jp', 'cc-sa', 'cc-nd', 'cc-pd', 'cc-zero', 'cc-share', 'cc-remix', 'github', 'github-circled', 'flickr', 'flickr-circled', 'vimeo', 'vimeo-circled', 'twitter', 'twitter-circled', 'facebook', 'facebook-circled', 'facebook-squared', 'gplus', 'gplus-circled', 'pinterest', 'pinterest-circled', 'tumblr', 'tumblr-circled', 'linkedin', 'linkedin-circled', 'dribbble', 'dribbble-circled', 'stumbleupon', 'stumbleupon-circled', 'lastfm', 'lastfm-circled', 'rdio', 'rdio-circled', 'spotify', 'spotify-circled', 'qq', 'instagram', 'dropbox', 'evernote', 'flattr', 'skype', 'skype-circled', 'renren', 'sina-weibo', 'paypal', 'picasa', 'soundcloud', 'mixi', 'behance', 'google-circles', 'vkontakte', 'smashing', 'db-shape', 'sweden', 'logo-db', 'picture', 'globe', 'leaf', 'graduation-cap', 'mic', 'palette', 'ticket', 'video', 'target', 'music', 'trophy', 'thumbs-up', 'thumbs-down', 'bag', 'user', 'users', 'lamp', 'alert', 'water', 'droplet', 'credit-card', 'monitor', 'briefcase', 'floppy', 'folder', 'doc-text', 'calendar', 'chart-line', 'chart-bar', 'clipboard', 'attach', 'bookmarks', 'book', 'book-open', 'phone', 'megaphone', 'upload', 'download', 'box', 'newspaper', 'mobile','signal', 'camera', 'shuffle', 'loop', 'arrows-ccw', 'light-down', 'light-up', 'mute', 'sound', 'battery', 'search', 'key', 'lock', 'lock-open', 'bell', 'bookmark', 'link', 'back', 'flashlight', 'chart-area', 'clock', 'rocket', 'block');	
		return $allIcons;
	}

	function pego_get_all_icons_social() {
		$allIcons= array('no-icon', 'mail', 'github', 'github-circled', 'flickr', 'flickr-circled', 'vimeo', 'vimeo-circled', 'twitter', 'twitter-circled', 'facebook', 'facebook-circled', 'facebook-squared', 'gplus', 'gplus-circled', 'pinterest', 'pinterest-circled', 'tumblr', 'tumblr-circled', 'linkedin', 'linkedin-circled', 'dribbble', 'dribbble-circled', 'stumbleupon', 'stumbleupon-circled', 'lastfm', 'lastfm-circled', 'rdio', 'rdio-circled', 'spotify', 'spotify-circled', 'qq', 'instagram', 'dropbox', 'evernote', 'flattr', 'skype', 'skype-circled', 'renren', 'sina-weibo', 'paypal', 'picasa', 'soundcloud', 'mixi', 'behance', 'google-circles');	
		return $allIcons;
	}

	function pego_get_all_icons_codes() {
	$allIconsCodes= array('', '\e816', '\e823', '\e819', '\e880', '\e87e', '\e87f','\e884','\e8d4','\e893','\e89a','\e8c8','\e81e','\e81f','\e830','\e83b','\e8c7', '\e8d5','\e8a0','\e8a1', '\e89e','\e89f', '\e86b', '\e89c', '\e883', '\e89b', '\e889', '\e88b', '\e88a', '\e867', '\e8ae', '\e808', '\e809', '\e848', '\e811', '\e8b1', '\e807', '\e805', '\e800', '\e801', '\e810', '\e827', '\e855', '\e854', '\e83e', '\e8b0', '\e89d', '\e8af', '\e8c3', '\e8b2', '\e804', '\e838', '\e839', '\e813', '\e812', '\e814', '\e815', '\e821', '\e835', '\e817', '\e820', '\e88d', '\e84a', '\e834', '\e87d', '\e88f', '\e88e', '\e88c', '\e87c', '\e8ab', '\e898', '\e887', '\e881', '\e886', '\e882', '\e80c',
 	'\e822', '\e818', '\e82b', '\e82a', '\e81b', '\e832', '\e833', '\e836', '\e837', '\e83a', '\e81a', '\e81c', '\e83c', '\e84e', '\e84d', '\e84c', '\e84b', '\e849', '\e847', '\e846', '\e845', '\e844', '\e850', '\e852', '\e856', '\e858', '\e857', '\e85b',
 	'\e85c', '\e888', '\e865', '\e864', '\e863', '\e862', '\e861', '\e860', '\e8d3', '\e8c1', '\e8bd', '\e8ce', '\e8cf', '\e8d0', '\e8d2', '\e85f', '\e86e', '\e86f', '\e870', '\e871', '\e872', '\e873', '\e874', '\e875', '\e876', '\e877', '\e878', '\e879',
 	'\e885', '\e87a', '\e87b', '\e8a2', '\e890', '\e891', '\e894', '\e8a7', '\e8a4', '\e8a3', '\e8ac', '\e8b5', '\e8b6', '\e8b9', '\e8ba', '\e8bb', '\e8b8', '\e8dd', '\e8dc', '\e8db', '\e8da', '\e8d9', '\e8ca', '\e8b3', '\e8c9', '\e8d7', '\e8d6', '\e8f4',
 	'\e8f5', '\e8fd', '\e8fc', '\e8fb', '\e8f6', '\e8fa', '\e8f7', '\e8de',  '\e8df', '\e8e0', '\e8e1', '\e8e2', '\e8e3', '\e8f8', '\e8e4', '\e8f3', '\e8f2', '\e8f1', '\e8f0', '\e8ef', '\e8ee', '\e8ed', '\e8ec', '\e8eb', '\e8ea', '\e8e9', '\e8e8', '\e8e7', '\e8e6', '\e8f9', '\e8e5', '\e8fe', '\e8ff', '\e90d', '\e900', '\e901', '\e902', '\e903', '\e904', '\e905', '\e906', '\e907', '\e908', '\e909', '\e90a', '\e90b', '\e90c', '\e90e', '\e90f', '\e910', '\e911', '\e912', '\e913', '\e914', '\e915', '\e916', '\e918', '\e917', '\e919', '\e80e', '\e8ad', '\e8b4', '\e8c2', '\e85d', '\e897', '\e8c0', '\e80d', '\e895', '\e802', '\e8a9', '\e81d', '\e826', '\e859', '\e80a', '\e80b', '\e86a', '\e83f', '\e8bf', '\e8be', '\e8bc', '\e8a6', '\e8b7', '\e8cb', '\e84f', '\e843', '\e85a', '\e8c6', '\e8c5', '\e8cc', '\e82e', '\e828', '\e840', '\e841', '\e853', '\e8cd', '\e824', '\e825', '\e851', '\e842', '\e8a5', '\e8aa', '\e80f', '\e896', '\e899', '\e892', '\e869', '\e868', '\e85e', '\e86d', '\e8a8', '\e803', '\e8d1', '\e82d', '\e82c', '\e83d', '\e829', '\e82f', '\e831', '\e806', '\e8c4', '\e86c', '\e8d8', '\e866');	
		
	return $allIconsCodes;
	}


	function pego_get_all_icons_codes_social() {
		$allIconsCodes= array('', '\e804', '\e8e1', '\e8e2', '\e8e3', '\e8f8', '\e8e4', '\e8f3', '\e8f2', '\e8f1', '\e8f0', '\e8ef', '\e8ee', '\e8ed', '\e8ec', '\e8eb', '\e8ea', '\e8e9', '\e8e8', '\e8e7', '\e8e6', '\e8f9', '\e8e5', '\e8fe', '\e8ff', '\e90d', '\e900', '\e901', '\e902', '\e903', '\e904', '\e905', '\e906', '\e907', '\e908', '\e909', '\e90a', '\e90b', '\e90c', '\e90e', '\e90f', '\e910', '\e911', '\e912', '\e913', '\e914');	
		return $allIconsCodes;
	}
	

/***************************************************************  
Pagination
***************************************************************/
	function pego_kriesi_pagination($pages = '', $range = 2)
	{  
		 $showitems = ($range * 2)+1;  

		 global $paged;
		
		 if(empty($paged)) $paged = 1;
		 //if ($catpaged != '') $paged = $catpaged;
		 
		 if($pages == '')
		 {
			 global $wp_query;
			 $pages = $wp_query->max_num_pages;
			 if(!$pages)
			 {
				 $pages = 1;
			 }
		 }   
		 if(1 != $pages)
		 {
			 echo "<div class='pagination'>";
			 if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>First &laquo;</a>";
			 if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>Previous &lsaquo;</a>";

					
				
			 for ($i=1; $i <= $pages; $i++)
			 {
				 if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
				 {
					 echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
				 }
			 }
			 

			 if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."'>Next &rsaquo;</a>";  
			 if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>Last &raquo;</a>";
			 echo "</div>\n";
		 }
	}
	
	
	
function pego_param_settings_field($settings, $value) {
    $output = '<div class="my_param_block">';
    $output .= '<input type="hidden" class="wpb_vc_param_value" name="'.$settings['param_name'].'" value="'.htmlspecialchars($value).'">';
    foreach ($settings['value'] as $key => $singleValue) {
        $output .= '<label class="radioImgClassComposer '.$settings['param_name'].'" for="post-section-'.$singleValue.'">
 <input style="width: 180px;" class="wpb-textinput '.$settings['param_name'].' '.$settings['type'].'_field" id="post-section-'.$singleValue.'"
  type="radio" name="'.$settings['param_name'].'" value="'.$singleValue.'"'.($singleValue===$value ? ' checked="checked"' : '').' />
 <div class="post-section-admin-picker post-section-'.$singleValue.'"></div>
 </label>';
    }
    $output .= '</div>';

    return $output;
}
add_action('init', 'pego_add_my_param');
function pego_add_my_param() {
    add_shortcode_param('my_param', 'pego_param_settings_field', get_stylesheet_directory_uri().'/js/my_param.js');
}


function pego_param1_settings_field($settings, $value) {
    $output = '<div class="my_param1_block">';
    $output .= '<input type="hidden" class="wpb_vc_param_value" name="'.$settings['param_name'].'" value="'.htmlspecialchars($value).'">';
    foreach ($settings['value'] as $key => $singleValue) {
        $output .= '<label class="radioImgClassComposer '.$settings['param_name'].'" for="icon-'.$singleValue.'">
 <input style="width: 10px;" class="wpb-textinput '.$settings['param_name'].' '.$settings['type'].'_field" id="icon-'.$singleValue.'"
  type="radio" name="'.$settings['param_name'].'" value="'.$singleValue.'"'.($singleValue===$value ? ' checked="checked"' : '').' />
 <div class="iconTest icon-'.$singleValue.'"></div>
 </label>';
    }
    $output .= '</div>';

    return $output;
}
add_action('init', 'pego_add_my_param1');
function pego_add_my_param1() {
    add_shortcode_param('my_param1', 'pego_param1_settings_field', get_stylesheet_directory_uri().'/js/my_param1.js');
}

function pego_param2_settings_field($settings, $value) {
    $output = '<div class="my_param2_block">';
    $output .= '<input type="hidden" class="wpb_vc_param_value" name="'.$settings['param_name'].'" value="'.htmlspecialchars($value).'">';
    foreach ($settings['value'] as $key => $singleValue) {
        $output .= '<label class="radioImgClassComposer '.$settings['param_name'].'" for="post-grid-'.$singleValue.'">
 <input style="width: 180px;" class="wpb-textinput '.$settings['param_name'].' '.$settings['type'].'_field" id="post-grid-'.$singleValue.'"
  type="radio" name="'.$settings['param_name'].'" value="'.$singleValue.'"'.($singleValue===$value ? ' checked="checked"' : '').' />
 <div class="post-grid-admin-picker post-grid-'.$singleValue.'"></div>
 </label>';
    }
    $output .= '</div>';

    return $output;
}
add_action('init', 'pego_add_my_param2');
function pego_add_my_param2() {
    add_shortcode_param('my_param2', 'pego_param2_settings_field', get_stylesheet_directory_uri().'/js/my_param2.js');
}
	
/***************************************************************  
Include Visual Composer
***************************************************************/
	
	
	if (!class_exists('WPBakeryVisualComposerAbstract')) {
	  $dir = dirname(__FILE__) . '/wpbakery/';
	  $composer_settings = Array(
		  'APP_ROOT'      => $dir . '/js_composer',
		  'WP_ROOT'       => dirname( dirname( dirname( dirname($dir ) ) ) ). '/',
		  'APP_DIR'       => basename( $dir ) . '/js_composer/',
		  'CONFIG'        => $dir . '/js_composer/config/',
		  'ASSETS_DIR'    => 'assets/',
		  'COMPOSER'      => $dir . '/js_composer/composer/',
		  'COMPOSER_LIB'  => $dir . '/js_composer/composer/lib/',
		  'SHORTCODES_LIB'  => $dir . '/js_composer/composer/lib/shortcodes/',
		  'USER_DIR_NAME'  => 'vc_templates',
		  'default_post_types' => Array('page', 'post')
	  );
	  require_once locate_template('/wpbakery/js_composer/js_composer.php');
	  $wpVC_setup->init($composer_settings);
	}	
	
	
/***************************************************************  
Blog socials
***************************************************************/
function pego_get_blog_socials($permalink, $title, $shareArray) {
	global $pego_prefix;
	$title = str_replace(" ","%20",$title);			
	?>
	<div class="blog-share-socials-wrapper">
	<?php
	 if ( function_exists( 'ot_get_option' ) ) {
		if (ot_get_option('meganews_share_caption') != '') {
			echo '<div class="blog-share-socials-title">'.ot_get_option('meganews_share_caption').'</div>';
		}
	 }
	?>	
		<ul class="blog-share-socials">
		<?php if (in_array("facebook", $shareArray)) {  ?>
				<li class="facebook">
					<a title="Facebook"  href="http://www.facebook.com/share.php?u=<?php echo $permalink;?>" target="_blank"><img src="<?php  echo get_template_directory_uri(); ?>/images/socials/facebook.jpg" alt="facebook"></a>
				</li>
		<?php } ?>
		<?php if (in_array("twitter", $shareArray)) {  ?>
				<li class="twitter">
					<a title="Twitter"  href="http://twitter.com/home?status=<?php echo $title; ?>%20-%20<?php echo $permalink;?>" target="_blank"><img src="<?php  echo get_template_directory_uri(); ?>/images/socials/twitter.jpg" alt="twitter"></a>
				</li>	
		<?php } ?>	
		<?php if (in_array("googleplus", $shareArray)) {  ?>
				<li class="googleplus">
					<a title="Google Plus"  href="https://plus.google.com/share?url=<?php echo $permalink;?>" target="_blank"><img src="<?php  echo get_template_directory_uri(); ?>/images/socials/google-plus.jpg" alt="googleplus"></a>
				</li>
		<?php } ?>
		<?php if (in_array("linkedin", $shareArray)) {  ?>
				<li class="linkedin">
					<a title="LinkedIn"  href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo $permalink;?>&title=<?php echo $title;?>" target="_blank"><img src="<?php  echo get_template_directory_uri(); ?>/images/socials/linkedin.jpg" alt="linkedin"></a>
				</li>
		<?php } ?>
		<?php if (in_array("tumblr", $shareArray)) {  ?>
				<li class="tumblr">
					<a  title="Tumblr" href="http://www.tumblr.com/share/link?url=<?php echo $permalink;?>&name=<?php echo $title;?>" target="_blank"><img src="<?php  echo get_template_directory_uri(); ?>/images/socials/tumblr.jpg" alt="tumblr"></a>
				</li>
		<?php } ?>
		<?php if (in_array("rss", $shareArray)) {  ?>
				<li class="rss">
					<a title="RSS" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo $permalink;?>&title=<?php echo $title;?>" target="_blank"><img src="<?php  echo get_template_directory_uri(); ?>/images/socials/rss.jpg" alt="rss"></a>
				</li>
		<?php } ?>
		<?php if (in_array("pinterest", $shareArray)) {  ?>
				<li class="pinterest">
					<a title="Pinterest" href="http://pinterest.com/pin/create/button/?url=<?php echo $permalink;?>" target="_blank"><img src="<?php  echo get_template_directory_uri(); ?>/images/socials/pinterest.jpg" alt="pinterest"></a>
				</li>
				
		<?php } ?>
		<?php if (in_array("mail", $shareArray)) { 
			$mailShare = ''; 
			 if ( function_exists( 'ot_get_option' ) ) {
				if (ot_get_option('meganews_single_post_share_mail') != '') {
					$mailShare = ot_get_option('meganews_single_post_share_mail');
				}
			 }
			if ($mailShare != '') {
			?>
				<li class="mail">
					<a title="Mail" href="mailto:<?php echo $mailShare;?>?subject=<?php echo $title;?>&body=<?php  echo $permalink; ?>" target="_blank"><img src="<?php  echo get_template_directory_uri(); ?>/images/socials/mail.jpg" alt="mail"></a>
				</li>
		<?php 
			} 
		}
		?>
		</ul>
	</div>
	<?php
				
}

/***************************************************************  
Single post hooks
***************************************************************/	
function pego_get_author_data($post) {
?>
	<!--BEGIN .author-bio-->
	<div class="clear"></div>
	<div class="author-bio">
				<?php echo get_avatar( get_the_author_meta('email'), '90' ); ?>
	<div class="author-info">
	<ul class="author-socials">
		<?php 
		if (get_the_author_meta( 'user_url', $post->post_author ) != '') { 
			echo '<li><a title="WebSite" href="'.get_the_author_meta( 'user_url', $post->post_author ).'" class="icons-author-socials icon-home"></a></li>';
		}
		if (get_the_author_meta( 'facebook', $post->post_author ) != '') { 
			echo '<li><a title="Facebook" href="'.get_the_author_meta( 'facebook', $post->post_author ).'" class="icons-author-socials icon-facebook"></a></li>';
		}
		if (get_the_author_meta( 'twitter', $post->post_author ) != '') { 
			echo '<li><a title="Twitter"  href="'.get_the_author_meta( 'twitter', $post->post_author ).'" class="icons-author-socials icon-twitter"></a></li>';
		}
		if (get_the_author_meta( 'googleplus', $post->post_author ) != '') { 
			echo '<li><a title="Google Plus" href="'.get_the_author_meta( 'googleplus', $post->post_author ).'" class="icons-author-socials icon-gplus"></a></li>';
		}
		if (get_the_author_meta( 'linkedin', $post->post_author ) != '') { 
			echo '<li><a title="LinkedIn" href="'.get_the_author_meta( 'linkedin', $post->post_author ).'" class="icons-author-socials icon-linkedin"></a></li>';
		}		
		if (get_the_author_meta( 'mail', $post->post_author ) != '') { 
			echo '<li><a title="Mail" href="'.get_the_author_meta( 'mail', $post->post_author ).'" class="icons-author-socials icon-mail"></a></li>';
		}
		?>
	</ul>
	
	<h3 class="author-title">
	<?php
	 if ( function_exists( 'ot_get_option' ) ) {
		if (ot_get_option('meganews_written_by_caption') != '') {
			echo ot_get_option('meganews_written_by_caption').' ';
		}
	 }
	the_author_link(); ?></h3>
	<p class="author-description"><?php the_author_meta('description'); ?>
	</div>
	<!--END .author-bio-->
	<div class="clear"></div>
	</div>
	<div class="clear"></div>
	<?php
}

/* BEGIN Custom User Contact Info */
function pego_extra_contact_info($contactmethods) {
$contactmethods['mail'] = 'Mail';
$contactmethods['facebook'] = 'Facebook';
$contactmethods['twitter'] = 'Twitter';
$contactmethods['linkedin'] = 'LinkedIn';
$contactmethods['googleplus'] = 'GooglePlus';
return $contactmethods;
}

add_filter('user_contactmethods', 'pego_extra_contact_info');
 /* END Custom User Contact Info */
 
 
 add_filter( 'inline_get_avatar', 'pego_tgm_custom_avatar_size' );
function pego_tgm_custom_avatar_size( $avatar ) {
    global $comment;
    $avatar = get_avatar( $comment, $size = '64' );
    return $avatar;
}

/***************************************************************  
Search form shortcode
***************************************************************/	
function pego_wpbsearchform( $form ) {

    $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
    <div><label class="screen-reader-text" for="s">' . __('SEARCH', 'meganews') . '</label>
    <input type="text" value="' . get_search_query() . '" name="s" id="s" />
    </div>
    </form>';

    return $form;
}

add_shortcode('wpbsearch', 'pego_wpbsearchform');

/***************************************************************  
Comment reply
***************************************************************/
function pego_js_comment_reply(){
	if ( (!is_admin()) && is_singular() && comments_open() && get_option('thread_comments') )
		wp_enqueue_script( 'comment-reply' );
}
	add_action('wp_print_scripts', 'pego_js_comment_reply');


/***************************************************************  
Support for bbpress
***************************************************************/
	add_theme_support('bbpress');


/***************************************************************  
Automatic plugin include
***************************************************************/
	
				/**
			 * This file represents an example of the code that themes would use to register
			 * the required plugins.
			 *
			 * It is expected that theme authors would copy and paste this code into their
			 * functions.php file, and amend to suit.
			 *
			 * @package	   TGM-Plugin-Activation
			 * @subpackage Example
			 * @version	   2.3.6
			 * @author	   Thomas Griffin <thomas@thomasgriffinmedia.com>
			 * @author	   Gary Jones <gamajo@gamajo.com>
			 * @copyright  Copyright (c) 2012, Thomas Griffin
			 * @license	   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
			 * @link       https://github.com/thomasgriffin/TGM-Plugin-Activation
			 */

			/**
			 * Include the TGM_Plugin_Activation class.
			 */
			require_once (PEGO_FILEPATH . '/functions/class-tgm-plugin-activation.php');

			add_action( 'tgmpa_register', 'pego_register_required_plugins' );
			/**
			 * Register the required plugins for this theme.
			 *
			 * In this example, we register two plugins - one included with the TGMPA library
			 * and one from the .org repo.
			 *
			 * The variable passed to tgmpa_register_plugins() should be an array of plugin
			 * arrays.
			 *
			 * This function is hooked into tgmpa_init, which is fired within the
			 * TGM_Plugin_Activation class constructor.
			 */
			function pego_register_required_plugins() {

				/**
				 * Array of plugin arrays. Required keys are name and slug.
				 * If the source is NOT from the .org repo, then source is also required.
				 */
				$plugins = array(

					// This is an example of how to include a plugin pre-packaged with a theme
				
				array(
						'name'     				=> 'Option Tree', // The plugin name
						'slug'     				=> 'option-tree', // The plugin slug (typically the folder name)
						'source'   				=> get_stylesheet_directory() . '/lib/plugins/option-tree.2.3.4.zip', // The plugin source
						'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
						'version' 				=> '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
						'force_activation' 		=> true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
						'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
						'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
					),
					array(
						'name'     				=> 'Contact Form 7', // The plugin name
						'slug'     				=> 'contact-form-7', // The plugin slug (typically the folder name)
						'source'   				=> get_stylesheet_directory() . '/lib/plugins/contact-form-7.3.7.2.zip', // The plugin source
						'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
						'version' 				=> '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
						'force_activation' 		=> true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
						'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
						'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
					)
				);

				// Change this to your theme text domain, used for internationalising strings
				$theme_text_domain = 'meganews';

				/**
				 * Array of configuration settings. Amend each line as needed.
				 * If you want the default strings to be available under your own theme domain,
				 * leave the strings uncommented.
				 * Some of the strings are added into a sprintf, so see the comments at the
				 * end of each line for what each argument will be.
				 */
				$config = array(
					'domain'       		=> $theme_text_domain,         	// Text domain - likely want to be the same as your theme.
					'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
					'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
					'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
					'menu'         		=> 'install-required-plugins', 	// Menu slug
					'has_notices'      	=> true,                       	// Show admin notices or not
					'is_automatic'    	=> false,					   	// Automatically activate plugins after installation or not
					'message' 			=> '',							// Message to output right before the plugins table
					'strings'      		=> array(
						'page_title'                       			=> __( 'Install Required Plugins', 'meganews' ),
						'menu_title'                       			=> __( 'Install Plugins', 'meganews' ),
						'installing'                       			=> __( 'Installing Plugin: %s', 'meganews' ), // %1$s = plugin name
						'oops'                             			=> __( 'Something went wrong with the plugin API.', 'meganews' ),
						'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
						'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
						'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
						'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
						'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
						'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
						'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
						'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
						'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
						'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
						'return'                           			=> __( 'Return to Required Plugins Installer', 'meganews' ),
						'plugin_activated'                 			=> __( 'Plugin activated successfully.', 'meganews' ),
						'complete' 									=> __( 'All plugins installed and activated successfully. %s', 'meganews' ), // %1$s = dashboard link
						'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
					)
				);

				tgmpa( $plugins, $config );

			}	
			
			
			
function get_post_average_review($post_id) {
	$title1 = get_post_meta($post_id, 'post_review_title1' , true);
	$review1 = get_post_meta($post_id, 'post_review_value1' , true);
	$title2 = get_post_meta($post_id, 'post_review_title2' , true);
	$review2 = get_post_meta($post_id, 'post_review_value2' , true);
	$title3 = get_post_meta($post_id, 'post_review_title3' , true);
	$review3 = get_post_meta($post_id, 'post_review_value3' , true);
	$title4 = get_post_meta($post_id, 'post_review_title4' , true);
	$review4 = get_post_meta($post_id, 'post_review_value4' , true);
	$title5 = get_post_meta($post_id, 'post_review_title5' , true);
	$review5 = get_post_meta($post_id, 'post_review_value5' , true);
	if ((($title1 != '')&&($review1 != ''))||(($title2 != '')&&($review2 != ''))||(($title3 != '')&&($review3 != ''))||(($title4 != '')&&($review4 != ''))||(($title5 != '')&&($review5 != ''))  ) {
		$value_sum = 0;
		$number_reviews = 0;	
		if (($title1 != '')&&($review1 != '')) { 							
			$number_reviews++;
			$value_sum += $review1;
		}
		if (($title2 != '')&&($review2 != '')) { 
			$number_reviews++;
			$value_sum += $review2;
		}
		if (($title3 != '')&&($review3 != '')) { 
			$number_reviews++;
			$value_sum += $review3;
		}
		if (($title4 != '')&&($review4 != '')) { 
			$number_reviews++;
			$value_sum += $review4;
		}
		if (($title5 != '')&&($review5 != '')) { 
			$number_reviews++;
			$value_sum += $review5;
		}					
		$average_review = round(($value_sum/$number_reviews), 0); 
		$average_review = $average_review / 10;
		return $average_review;
	
	}			
}
			
			
	
class rc_sweet_custom_menu {

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {

		// load the plugin translation files
		add_action( 'init', array( $this, 'textdomain' ) );
		
		// add custom menu fields to menu
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'rc_scm_add_custom_nav_fields' ) );

		// save menu custom fields
		add_action( 'wp_update_nav_menu_item', array( $this, 'rc_scm_update_custom_nav_fields'), 10, 3 );
		
		// edit menu walker
		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'rc_scm_edit_walker'), 10, 2 );

	} // end constructor
	
	
	/**
	 * Load the plugin's text domain
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function textdomain() {
		load_plugin_textdomain( 'rc_scm', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	
	/**
	 * Add custom fields to $item nav object
	 * in order to be used in custom Walker
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function rc_scm_add_custom_nav_fields( $menu_item ) {
	
	    $menu_item->subtitle = get_post_meta( $menu_item->ID, '_menu_item_subtitle', true );
	    return $menu_item;
	    
	}
	
	/**
	 * Save menu custom fields
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function rc_scm_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {
	
	    // Check if element is properly sent
	    if ( is_array( $_REQUEST['menu-item-subtitle']) ) {
	        $subtitle_value = $_REQUEST['menu-item-subtitle'][$menu_item_db_id];
	        update_post_meta( $menu_item_db_id, '_menu_item_subtitle', $subtitle_value );
	    }
	    
	}
	
	/**
	 * Define new Walker edit
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function rc_scm_edit_walker($walker,$menu_id) {
	
	    return 'Walker_Nav_Menu_Edit_Custom';
	    
	}

}

// instantiate plugin's class
$GLOBALS['sweet_custom_menu'] = new rc_sweet_custom_menu();





class Walker_Nav_Menu_Edit_Custom extends Walker_Nav_Menu  {
	/**
	 * @see Walker_Nav_Menu::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function start_lvl(&$output, $depth = 0, $args = Array()) {	
	}
	
	/**
	 * @see Walker_Nav_Menu::end_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function end_lvl(&$output, $depth = 0, $args = Array()) {
	}
	
	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param object $args
	 */
	function start_el(&$output, $item, $depth = 0, $args = Array(), $id = 0) {
	    global $_wp_nav_menu_max_depth;
	   
	    $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;
	
	    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
	
	    ob_start();
	    $item_id = esc_attr( $item->ID );
	    $removed_args = array(
	        'action',
	        'customlink-tab',
	        'edit-menu-item',
	        'menu-item',
	        'page-tab',
	        '_wpnonce',
	    );
	
	    $original_title = '';
	    if ( 'taxonomy' == $item->type ) {
	        $original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
	        if ( is_wp_error( $original_title ) )
	            $original_title = false;
	    } elseif ( 'post_type' == $item->type ) {
	        $original_object = get_post( $item->object_id );
	        $original_title = $original_object->post_title;
	    }
	
	    $classes = array(
	        'menu-item menu-item-depth-' . $depth,
	        'menu-item-' . esc_attr( $item->object ),
	        'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
	    );
	
	    $title = $item->title;
	
	    if ( ! empty( $item->_invalid ) ) {
	        $classes[] = 'menu-item-invalid';
	        /* translators: %s: title of menu item which is invalid */
	        $title = sprintf( __( '%s (Invalid)' ), $item->title );
	    } elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
	        $classes[] = 'pending';
	        /* translators: %s: title of menu item in draft status */
	        $title = sprintf( __('%s (Pending)'), $item->title );
	    }
	
	    $title = empty( $item->label ) ? $title : $item->label;
	
	    ?>
	    <li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes ); ?>">
	        <dl class="menu-item-bar">
	            <dt class="menu-item-handle">
	                <span class="item-title"><?php echo esc_html( $title ); ?></span>
	                <span class="item-controls">
	                    <span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
	                    <span class="item-order hide-if-js">
	                        <a href="<?php
	                            echo wp_nonce_url(
	                                add_query_arg(
	                                    array(
	                                        'action' => 'move-up-menu-item',
	                                        'menu-item' => $item_id,
	                                    ),
	                                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                                ),
	                                'move-menu_item'
	                            );
	                        ?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up'); ?>">&#8593;</abbr></a>
	                        |
	                        <a href="<?php
	                            echo wp_nonce_url(
	                                add_query_arg(
	                                    array(
	                                        'action' => 'move-down-menu-item',
	                                        'menu-item' => $item_id,
	                                    ),
	                                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                                ),
	                                'move-menu_item'
	                            );
	                        ?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down'); ?>">&#8595;</abbr></a>
	                    </span>
	                    <a class="item-edit" id="edit-<?php echo $item_id; ?>" title="<?php esc_attr_e('Edit Menu Item'); ?>" href="<?php
	                        echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
	                    ?>"><?php _e( 'Edit Menu Item' ); ?></a>
	                </span>
	            </dt>
	        </dl>
	
	        <div class="menu-item-settings" id="menu-item-settings-<?php echo $item_id; ?>">
	            <?php if( 'custom' == $item->type ) : ?>
	                <p class="field-url description description-wide">
	                    <label for="edit-menu-item-url-<?php echo $item_id; ?>">
	                        <?php _e( 'URL' ); ?><br />
	                        <input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
	                    </label>
	                </p>
	            <?php endif; ?>
	            <p class="description description-thin">
	                <label for="edit-menu-item-title-<?php echo $item_id; ?>">
	                    <?php _e( 'Navigation Label' ); ?><br />
	                    <input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
	                </label>
	            </p>
	            <p class="description description-thin">
	                <label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
	                    <?php _e( 'Title Attribute' ); ?><br />
	                    <input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
	                </label>
	            </p>
	            <p class="field-link-target description">
	                <label for="edit-menu-item-target-<?php echo $item_id; ?>">
	                    <input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked( $item->target, '_blank' ); ?> />
	                    <?php _e( 'Open link in a new window/tab' ); ?>
	                </label>
	            </p>
	            <p class="field-css-classes description description-thin">
	                <label for="edit-menu-item-classes-<?php echo $item_id; ?>">
	                    <?php _e( 'CSS Classes (optional)' ); ?><br />
	                    <input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
	                </label>
	            </p>
	            <p class="field-xfn description description-thin">
	                <label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
	                    <?php _e( 'Link Relationship (XFN)' ); ?><br />
	                    <input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
	                </label>
	            </p>
	            <p class="field-description description description-wide">
	                <label for="edit-menu-item-description-<?php echo $item_id; ?>">
	                    <?php _e( 'Description' ); ?><br />
	                    <textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
	                    <span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.'); ?></span>
	                </label>
	            </p>        
	            <?php
	            /* New fields insertion starts here */
	            ?>      
	            <p class="field-custom description description-wide">
	                <label for="edit-menu-item-subtitle-<?php echo $item_id; ?>">
	                    <?php _e( 'Submenu type:' ); ?><br />
	                   <?php $options = array();
	                   $options['standard'] = 'Standard';
	                   $options['mega-menu-columns'] = 'Megamenu Columns';
	                   $options['mega-menu-posts'] = 'Latest 4 post from Category';
	                   
	                   
	                   
	                   ?>
	      	<select style="width:100px;" id="edit-menu-item-subtitle-<?php echo $item_id; ?>" class="widefat code edit-menu-item-custom" name="menu-item-subtitle[<?php echo $item_id; ?>]">
			<?php foreach ( $options as $option ) :
				$current = $item->subtitle;
			 ?>
				<option <?php if ( htmlentities( $current, ENT_QUOTES ) == $option ) echo ' selected="selected"'; ?>>
					<?php echo $option; ?>
				</option>
			<?php endforeach; ?>
			</select>
			           
	                    
	                    
	                
	               
	                </label>
	            </p>
	            <?php
	            /* New fields insertion ends here */
	            ?>
	            <div class="menu-item-actions description-wide submitbox">
	                <?php if( 'custom' != $item->type && $original_title !== false ) : ?>
	                    <p class="link-to-original">
	                        <?php printf( __('Original: %s'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
	                    </p>
	                <?php endif; ?>
	                <a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
	                echo wp_nonce_url(
	                    add_query_arg(
	                        array(
	                            'action' => 'delete-menu-item',
	                            'menu-item' => $item_id,
	                        ),
	                        remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                    ),
	                    'delete-menu_item_' . $item_id
	                ); ?>"><?php _e('Remove'); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url( add_query_arg( array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) ) ) );
	                    ?>#menu-item-settings-<?php echo $item_id; ?>"><?php _e('Cancel'); ?></a>
	            </div>
	
	            <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
	            <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
	            <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
	            <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
	            <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
	            <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
	        </div><!-- .menu-item-settings-->
	        <ul class="menu-item-transport"></ul>
	    <?php
	    
	    $output .= ob_get_clean();

	    }
}

			
			
			
?>