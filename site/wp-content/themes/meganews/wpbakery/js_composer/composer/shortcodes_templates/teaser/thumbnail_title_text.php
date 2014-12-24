<?php
if ( $thumbnail ) {
    $to_filter = '<div class="post-thumb">' . $link_image_start . $thumbnail . $link_image_end .'</div>';
    $teasers .= apply_filters('vc_teaser_grid_thumbnail', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "thumbnail" => $thumbnail, "media_link" => $p_link) );
}
if ( $post_title ) 	{
    $to_filter = '<h2 class="post-title">' . $link_title_start . $post_title . $link_title_end . '</h2>';
    $teasers .= apply_filters('vc_teaser_grid_title', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "title" => $post_title, "media_link" => $p_link) );
}
if ( $content ) {
    $to_filter = '<div class="entry-content">' . $content . '</div>';
    $teasers .= apply_filters('vc_teaser_grid_content', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "content" => $content, "media_link" => $p_link) );
}