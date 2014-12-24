<?php
$title = $grid_columns_count = $grid_layout = $grid_link = $grid_link_target = '';
$grid_template = $grid_thumb_size = $grid_layout_mode = '';
$grid_content = $el_class = $width = $orderby = $order = $el_position = $isotope_item = $teasers = $taxonomies = '';
$output = $loop = '' ;
$template_path = '';
$teaser_categories = Array();

extract(shortcode_atts(array(
    'title' => '',
    'grid_columns_count' => 4,
    'grid_teasers_count' => 8,
    'grid_layout' => 'title_thumbnail_text', // title_thumbnail_text, thumbnail_title_text, thumbnail_text, thumbnail_title, thumbnail, title_text
    'grid_link' => 'link_post', // link_post, link_image, link_image_post, link_no
    'grid_link_target' => '_self',
    'grid_template' => 'grid', //grid, carousel
    'grid_thumb_size' => 'full',
    'grid_layout_mode' => 'fitRows',
    'grid_content' => 'teaser', // teaser, content
    'el_class' => '',
    'width' => '1/1',
    'orderby' => NULL,
    'order' => 'DESC',
	'loop' => ''
), $atts));


list($args, $my_query) = vc_build_loop_query($loop); //
/**
 * Enqueue isotope js/css if required
 * {{
 */
if ( $grid_template == 'grid' || $grid_template == 'filtered_grid') {
    wp_enqueue_style('isotope-css');
    wp_enqueue_script( 'isotope' );
    $isotope_item = 'isotope-item ';
} else if ( $grid_template == 'carousel' ) {
    wp_enqueue_script( 'jcarousellite' );
    $isotope_item = '';
}
// }}
/**
 * Enqueue prettyphoto js/css if required.
 * {{
 */
if ( $grid_link == 'link_image' || $grid_link == 'link_image_post' ) {
    wp_enqueue_script( 'prettyphoto' );
    wp_enqueue_style( 'prettyphoto' );
}
// }}
$el_class = $this->getExtraClass( $el_class );
$li_span_class = wpb_translateColumnsCountToSpanClass( $grid_columns_count );

/**
 * Find posts types taxonomies
 * {{
 */
if($grid_template == 'filtered_grid' && empty($grid_taxomonies)) {
    $taxonomies = get_object_taxonomies(!empty($args['post_type']) ? $args['post_type'] : get_post_types(array('public' => false, 'name' => 'attachment'), 'names', 'NOT'));
}
// }}
$link_target = $grid_link_target=='_blank' ? ' target="_blank"' : '';

/**
 * Get teaser block template path
 */
$template_r = VcTeaserTemplates::getInstance();
$template_path =  $template_r->getTemplatePath($grid_layout);

if($template_path!==false)
    while ( $my_query->have_posts() ) {
        $link_title_start = $link_image_start = $p_link = $link_image_end = $p_img_large = '';
        $my_query->the_post();
        /**
         * Find taxonomies
         */
        $categories_css = '';

        if( $grid_template == 'filtered_grid' ) {
            /** @var $post_categories get list of categories */
            $post_categories = wp_get_object_terms($my_query->post->ID, $taxonomies);
            foreach($post_categories as $cat) {
                if(!in_array($cat->term_id, $teaser_categories)) {
                    $teaser_categories[] = $cat->term_id;
                }
                $categories_css .= ' grid-cat-'.$cat->term_id;
            }
        }
        // }}

        $post_title = the_title("", "", false);
        $post_id = $my_query->post->ID;


        $teaser_post_type = 'posts_grid_teaser_'.$my_query->post->post_type . ' ';
        if($grid_content == 'teaser') {
            $content = apply_filters('the_excerpt', get_the_excerpt());
        } else {
            $content = get_the_content();
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);
        }

        // $content = ( $grid_content == 'teaser' ) ? apply_filters('the_excerpt', get_the_excerpt()) : get_the_content(); //TODO: get_the_content() rewrite more WP native way.
        $content = wpautop($content);
        $link = '';
        $thumbnail = '';

        // Read more link
        if ( $grid_link != 'link_no' ) {
            $link = '<a class="more-link" href="'. get_permalink($post_id) .'"'.$link_target.' title="'. sprintf( esc_attr__( 'Permalink to %s', 'meganews' ), the_title_attribute( 'echo=0' ) ).'">'. __("Read more", "meganews") .'</a>';
        }

        // Thumbnail logic
        if ( in_array($grid_layout, array('title_thumbnail_text', 'thumbnail_title_text', 'thumbnail_text', 'thumbnail_title', 'thumbnail', 'title_text', 'thumbnail_title_text_details', 'title_thumbnail_text_details', 'title_thumbnail_details_text') ) ) {
            $post_thumbnail = $p_img_large = '';
            //$attach_id = get_post_thumbnail_id($post_id);

            $post_thumbnail = wpb_getImageBySize(array( 'post_id' => $post_id, 'thumb_size' => $grid_thumb_size ));
            $thumbnail = $post_thumbnail['thumbnail'];
            $p_img_large = $post_thumbnail['p_img_large'];
        }

        // Link logic
        if ( $grid_link != 'link_no' ) {
            $p_video = '';
            if ( $grid_link == 'link_image' || $grid_link == 'link_image_post' ) {
                $p_video = get_post_meta($post_id, "_p_video", true);
            }

            if ( $grid_link == 'link_post' ) {
                $link_image_start = '<a class="link_image" href="'.get_permalink($post_id).'"'.$link_target.' title="'.sprintf( esc_attr__( 'Permalink to %s', 'meganews' ), the_title_attribute( 'echo=0' ) ).'">';
                $link_title_start = '<a class="link_title" href="'.get_permalink($post_id).'"'.$link_target.' title="'.sprintf( esc_attr__( 'Permalink to %s', 'meganews' ), the_title_attribute( 'echo=0' ) ).'">';
            }
            else if ( $grid_link == 'link_image' ) {
                if ( $p_video != "" ) {
                    $p_link = $p_video;
                } else {
                    $p_link = $p_img_large[0];
                }
                $link_image_start = '<a class="link_image prettyphoto" href="'.$p_link.'"'.$link_target.' title="'.the_title_attribute('echo=0').'">';
                $link_title_start = '<a class="link_title prettyphoto" href="'.$p_link.'"'.$link_target.' title="'.the_title_attribute('echo=0').'">';
            }
            else if ( $grid_link == 'link_image_post' ) {
                if ( $p_video != "" ) {
                    $p_link = $p_video;
                } else {
                    $p_link = $p_img_large[0];
                }
                $link_image_start = '<a class="link_image prettyphoto" href="'.$p_link.'"'.$link_target.' title="'.the_title_attribute('echo=0').'">';
                $link_title_start = '<a class="link_title" href="'.get_permalink($post_id).'"'.$link_target.' title="'.sprintf( esc_attr__( 'Permalink to %s', 'js_composer' ), the_title_attribute( 'echo=0' ) ).'">';
            }
            $link_title_end = $link_image_end = '</a>';
        } else {
            $link_image_start = '';
            $link_title_start = '';
            $link_title_end = $link_image_end = '';
        }
        $teasers .= '<li class="'.$isotope_item.apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $li_span_class, 'vc_teaser_grid_li').$categories_css.'">';
        include $template_path;
        $teasers .= '</li> ' . $this->endBlockComment('single teaser');

    }
wp_reset_query();
/**
 * Create filter
 * {{
 */

if( $grid_template == 'filtered_grid' && $teasers && !empty($teaser_categories)) {
    $categories_array = get_terms($taxonomies, array(
        'orderby' => 'name',
        'include' => implode(',', $teaser_categories)
    ));

    $categories_list_output = '<ul class="categories_filter clearfix">';
    $categories_list_output .= '<li class="active"><a href="#" data-filter="*">' . __('All', 'meganews') . '</a></li>';
    foreach($categories_array as $cat) {
        $categories_list_output .= '<li><a href="#" data-filter=".grid-cat-'.$cat->term_id.'">' . esc_attr($cat->name) . '</a></li>';
    }
    $categories_list_output.= '</ul><div class="clearfix"></div>';
} else {
    $categories_list_output = '';
}
// }}

if ( $teasers ) { $teasers = '<div class="teaser_grid_container">'.$categories_list_output.'<ul class="wpb_thumbnails wpb_thumbnails-fluid clearfix" data-layout-mode="'.$grid_layout_mode.'">'. $teasers .'</ul></div>'; }
else { $teasers = __("Nothing found." , "meganews"); }

$posttypes_teasers = '';



if ( !empty($args['post_type']) && is_array($args['post_type']) ) {
    foreach ( $args['post_type'] as $post_type ) {
        $posttypes_teasers .= 'wpb_teaser_grid_'.$post_type . ' ';
    }
}

$grid_class = 'wpb_'.$grid_template . ' columns_count_'.$grid_columns_count . ' grid_layout-'.$grid_layout . ' '  . $grid_layout.'_'.$li_span_class . ' ' . 'columns_count_'.$grid_columns_count.'_'.$grid_layout . ' ' . $posttypes_teasers;
$css_class =  apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_teaser_grid wpb_content_element '.$grid_class.$width.$el_class, $this->settings['base']);

$output .= "\n\t".'<div class="'.$css_class.'">';
$output .= "\n\t\t".'<div class="wpb_wrapper">';
//$output .= ($title != '' ) ? "\n\t\t\t".'<h2 class="wpb_heading wpb_teaser_grid_heading">'.$title.'</h2>' : '';
$output .= wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_teaser_grid_heading'));
if ( $grid_template == 'carousel' ) {
    $output .= apply_filters( 'vc_teaser_grid_carousel_arrows', '<a href="#" class="prev">&larr;</a> <a href="#" class="next">&rarr;</a>' );
}

$output .= $teasers;
$output .= "\n\t\t".'</div> '.$this->endBlockComment('.wpb_wrapper');
$output .= "\n\t".'</div> '.$this->endBlockComment('.wpb_teaser_grid');

echo $output;