<?php


$category_ids = get_all_category_ids();
if($category_ids){
	$cat_css = ' <style> ';
	$cat_js = ' <script> ';
	foreach($category_ids as $cat_id) {
		$cat_color = get_tax_meta($cat_id,'pego_category_color');
		if ($cat_color != '') {
			$cat_css .= ' .main-menu .sf-menu > li.menu-cat-item-'.$cat_id.' > a  { border-top: 5px solid '.$cat_color.'; } ';
			$cat_css .= ' .main-menu .sf-menu > li.menu-cat-item-'.$cat_id.' > a:hover, .main-menu  .sf-menu > li.menu-cat-item-'.$cat_id.' > a:focus, .main-menu .sf-menu > li.menu-cat-item-'.$cat_id.'.sfHover > a,
			
			.main-menu .sf-menu > li.menu-cat-item-'.$cat_id.'.sfHover .submenu_1 
			
			
			
			
			 { background-color: '.$cat_color.'; color: #fff; } ';
			$cat_css .= ' .main-menu ul.sf-menu  li.menu-cat-item-'.$cat_id.'.current-menu-item > a,
						  .main-menu ul.sf-menu > li.menu-cat-item-'.$cat_id.'.current-menu-parent > a, 
						  .main-menu ul.sf-menu.cat-color-'.$cat_id.' > li.menu-cat-item-'.$cat_id.'.current-menu-ancestor > a,
						  .main-menu .sf-menu > li.menu-cat-item-'.$cat_id.' > a:focus
						  						  { background-color: '.$cat_color.'; } ';
			//$cat_css .= ' .main-menu .sf-menu > li.menu-cat-item-'.$cat_id.'.sfHover > a { background-color: #fff; color: #000; } '; 
			
			$cat_css .= ' .category-'.$cat_id.' .main-menu .sf-menu  { border-color: '.$cat_color.'; } ';
			
			$cat_css .= ' .category-'.$cat_id.' .vc_progress_bar .vc_single_bar.bar_blue .vc_bar  { background-color: '.$cat_color.'; } ';
			
			$cat_css .= ' .category-'.$cat_id.' .review-average  { background-color: '.$cat_color.'; } ';
			
			
			
			$cat_css .= ' .main-menu ul.sf-menu.cat-color-'.$cat_id.' { border-color: '.$cat_color.'; } ';
			$cat_css .= ' .category-color-'.$cat_id.' { color: '.$cat_color.'; } ';
			$cat_css .= ' .category-bg-color-'.$cat_id.' { background-color: '.$cat_color.' !important; } ';
			$cat_css .= ' .category-hover-color-'.$cat_id.':hover { color: '.$cat_color.' !important; } ';
			$cat_js  .= ' 	jQuery(document).ready(function(){
							jQuery(".main-menu .sf-menu > li.menu-cat-item-'.$cat_id.' > a, .main-menu .sf-menu > li.menu-cat-item-'.$cat_id.' > .submenu_1").hover(function(){
									jQuery(".main-menu .sf-menu").addClass("cat-color-'.$cat_id.'");
								},function(){
									jQuery(".main-menu .sf-menu ").removeClass("cat-color-'.$cat_id.'");
								});
							});	';
			
		} 
	}
	$cat_css .= '</style>';
	$cat_js .= '</script>';
}
if ( function_exists( 'ot_get_option' ) ) {
	/* main template color start */	
	$main_template_color = '';

	
		
		if (ot_get_option('meganews_main_template_color') != '') {
		
			$main_template_color = '  a:hover, #upper-panel-wrapper a:hover, .author-bio .author-info .author-description span a, li.comment div.reply a,  .wpb_widgetised_column ul li a:hover, span.post-date, .wpb_widgetised_column #calendar_wrap #today,
			.wpb_widgetised_column #calendar_wrap td.pad, #footer #calendar_wrap td.pad, .wpb_widgetised_column .tagcloud a:hover, .counter_execute  { ';
				$main_template_color .=' color: '. ot_get_option('meganews_main_template_color').';  }';		
				
			$main_template_color .= ' .main-menu .sf-menu li.sfHover > a, .main-menu .sf-menu li > a:focus, .main-menu .sf-menu li > a:hover, .main-menu .sf-menu li > a:active, .main-menu .sf-menu li.current-menu-item > a, .main-menu .sf-menu > li.current-menu-parent > a, .main-menu .sf-menu > li.current-menu-ancestor > a,
			 p.form-submit input#submit, .wpb_widgetised_column .post_widget a.cat, .wpb_widgetised_column .top_comment_widget a.cat, #footer .post_widget a.cat, #footer .top_comment_widget a.cat,
			 .wpb_widgetised_column input#searchsubmit, .wpb_widgetised_column  input#searchsubmit:hover, #footer input#searchsubmit, #footer  input#searchsubmit:hover, .wpb_widgetised_column #calendar_wrap caption, #footer #calendar_wrap caption,
			 .vc_progress_bar .vc_single_bar.bar_blue .vc_bar, .submenu_1   { ';
			$main_template_color .=' background-color: '. ot_get_option('meganews_main_template_color').';  }';

			$main_template_color .= ' .main-menu .sf-menu {  border-bottom: 5px solid '. ot_get_option('meganews_main_template_color').';  }';	
			$main_template_color .= ' .main-menu .sf-menu li a  {  border-top: 5px solid '. ot_get_option('meganews_main_template_color').';  }';	
			$main_template_color .= ' .main-menu ul.sf-menu.default_color  {  border-color:  '. ot_get_option('meganews_main_template_color').';  }';	
			
			
		}

	/* main template color end */	
	
	
$bgCss = '';
if ( function_exists( 'ot_get_option' ) ) {
	$bgArray = ot_get_option('meganews_backgound');
	if ($bgArray) {
		$bgCss = ' #container-wrapper { ';
		if ($bgArray['background-color'] != '') { $bgCss .= ' background-color: '.$bgArray['background-color'].'; '; }
		if ($bgArray['background-repeat'] != '') { $bgCss .= ' background-repeat: '.$bgArray['background-repeat'].'; '; }
		if ($bgArray['background-attachment'] != '') { $bgCss .= ' background-attachment: '.$bgArray['background-attachment'].'; '; }
		if ($bgArray['background-position'] != '') { $bgCss .= ' background-position: '.$bgArray['background-position'].'; '; }
		if ($bgArray['background-size'] != '') { $bgCss .= ' background-size: '.$bgArray['background-size'].'; '; }
		if ($bgArray['background-image'] != '') { $bgCss .= ' background-image: url("'.$bgArray['background-image'].'"); '; }
		$bgCss .= ' } ';
 	}
}

	$logoCss = '';
	if ((ot_get_option('meganews_logo_width') != '')||(ot_get_option('meganews_logo_height') != '')) {
		$logoCss = ' #logoImageRetina { ';	
		if (ot_get_option('meganews_logo_width') != '') {
			$logoCss .= ' width: '.ot_get_option('meganews_logo_width').'px; ';
		}
		if (ot_get_option('meganews_logo_height') != '') {
			$logoCss .= ' height: '.ot_get_option('meganews_logo_height').'px; ';
		}
		$logoCss .= ' } ';						
	}	
	
	$rightLogoCSS = '';
	if (ot_get_option('meganews_logo_width') != '') {
		$rightWidth = 960 - ot_get_option('meganews_logo_width') - 30;
		$rightLogoCSS .= ' @media only screen and (max-width: 1170px) { #right-from-logo-area { width: '.$rightWidth.'px; } }';
		$rightWidth = 769 - ot_get_option('meganews_logo_width') - 30;
		$rightLogoCSS .= ' @media only screen and (max-width: 960px) { #right-from-logo-area {  width: '.$rightWidth.'px; } }';
	}			

	echo '<style type="text/css">';	
		echo $main_template_color;		
		echo $bgCss;
		echo $logoCss;
		echo $rightLogoCSS;
		
	if ( function_exists( 'ot_get_option' ) ) {
		if (ot_get_option('meganews_google_fonts_1') != '') {
			echo ' body,td, div, h1, h2, h3, h4, h5, h6, #container .breadcrumbs-content, .breadcrumbs-content a, .breadcrumbs-content span, a.post_cat_tag, .post_cat_tag, h1.post_cat_title_large, h1.post_cat_title_small, #container ul.newsticker,
ul.newsticker a, ul.newsticker span, #container div.blog-share-socials-title, h1.single-post-title, #container div.single-post-author, #container div.single-post-date, #container div.post-tagged, #comments .post-desc p.leave-opinion,
#comments label, p.form-submit input#submit, li.comment div.reply a, .wpb_widgetised_column input#searchsubmit, #footer input#searchsubmit, .wpb_widgetised_column #calendar_wrap, .wpb_widgetised_column #calendar_wrap th, #footer #calendar_wrap,
#footer #calendar_wrap th, .wpb_widgetised_column .wpb_content_element .wpb_tour_tabs_wrapper .wpb_tabs_nav a, .wpb_widgetised_column .wpb_content_element .wpb_tour_tabs_wrapper .wpb_tabs_nav a, .blockquote.type1 p, .blockquote.type3 p,
.vc_dropcap .dropcap.type2 span.first_letter, .vc_dropcap .dropcap.type2 p, .cbp-qtrotator blockquote p, .counter_execute, #container .post-grid-thumb-type4-time-inside, #container .wpb_teaser_grid .categories_filter li,
#container .wpb_categories_filter li { '.ot_get_option('meganews_google_fonts_1').'; }';
		}
	}
	
		if ( function_exists( 'ot_get_option' ) ) {
		if (ot_get_option('meganews_google_fonts_2') != '') {
			echo ' #container div, #container ul li, p, div.wpb_wrapper, .post-details .post-date, .post-details .post-comments-number a, .pagination, .post-details-type3 .post-date, .post-details-type3 .post-comments-number a, .post-grid-thumb-type3-wrapper .excerpt, .single-post-thumb-wrapper-type-two .post-details .post-date, .single-post-thumb-wrapper-type-two .post-details .post-comments-number a, .single-post-thumb-wrapper-type-two .excerpt, div.single-post-cat a, div.single-post-excerpt, div.single-post-excerpt p,
div.post-tagged a, p.author-description,  cite.fn, div.comment-meta.commentmetadata, p.logged-in-as, #comments input, #comments textarea, li.comment p, #footer h1.widget-title, .wpb_widgetised_column .post_widget a.cat, .wpb_widgetised_column .post_widget a, .wpb_widgetised_column .top_comment_widget a.cat, .wpb_widgetised_column .top_comment_widget a, #footer .post_widget a.cat, #footer .post_widget a, #footer .top_comment_widget a.cat, #footer .top_comment_widget a, span.post-date, .wpb_widgetised_column  input#s,
#footer  input#s, .textwidget, .textwidget p, .wpb_widgetised_column #calendar_wrap td,  .wpb_widgetised_column #calendar_wrap a, #footer #calendar_wrap td, #footer #calendar_wrap a, .wpb_widgetised_column span.tweet_time a, #footer  span.tweet_time a, .blockquote.type2 p, .wpb_toggle,
#content h4.wpb_toggle, .wpb_content_element .wpb_tabs_nav li a, .wpb_accordion .wpb_accordion_wrapper .wpb_accordion_header a, .list_sh ul li, .list_sh ol li, .columns3 .content h1, .columns3 .content h1 a, .columns2 .content h1, .columns2 .content h1 a, .columns2 .content p, .wpcf7 input.wpcf7-form-control, .wpcf7 textarea.wpcf7-form-control, .wpcf7 p, .wpb_widgetised_column ul.menu  li, .wpb_widgetised_column ul.menu  li ul.sub-menu li, .wpb_widgetised_column ul.menu  li li li,
.wpb_widgetised_column ul.menu  li a, .wpb_widgetised_column  ul.menu  li.current-menu-item , .wpb_widgetised_column ul.menu  li:hover, .wpb_widgetised_column > ul.menu > li.current-menu-item > a, .slide-text-black-big, .slide-text-black-big-noBg, .slide-text-mainColor, .slide-text-black-medium, .slide-text-mainColor-noBg, .vc_progress_bar .vc_single_bar .vc_label, .cbp-qtrotator .testimonailsauthor, .pricing_price, h1.counter-title, .post-grid-type1-details .post-date, .post-grid-type1-details .post-comments-number a, 
.post-grid-type1-details .excerpt, ul.post-grid-thumb-list  li .post-date, .post-details-type3 .post-date, .post-details-type3 .post-comments-number a, .post-grid-thumb-type3-wrapper .excerpt, .post-details-type4 .post-date, .post-details-type4 .post-comments-number a, .post-grid-thumb-type4-wrapper .excerpt { '.ot_get_option('meganews_google_fonts_2').'; }';
		}
	}
		
		
	echo '</style>';
	
	echo $cat_css;
	echo $cat_js;
	
	echo '<style type="text/css">';	
		//additionalCSS
		if ( function_exists( 'ot_get_option' ) ) {
			echo ot_get_option('meganews_additional_css');
		}
	
	echo '</style>';
	
	//additionalJS
	if ( function_exists( 'ot_get_option' ) ) {
		if (ot_get_option('meganews_additional_js') != ''){
			echo '<script>';	
			echo  ot_get_option('meganews_additional_js');
			echo '</script>';	
		}
	}
	
	
}

	
?>
	