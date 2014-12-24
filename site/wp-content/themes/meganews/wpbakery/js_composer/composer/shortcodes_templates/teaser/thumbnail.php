<?php
if ( $thumbnail ) {
    $to_filter = '<div class="post-thumb">' . $link_image_start . $thumbnail . $link_image_end .'</div>';
    $teasers .= apply_filters('vc_teaser_grid_thumbnail', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "thumbnail" => $thumbnail, "media_link" => $p_link) );
}