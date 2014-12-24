<?php
if ( $thumbnail ) {
    $to_filter = '<div class="post-thumb">' . $link_image_start . $thumbnail . $link_image_end .'</div>';
$teasers .= apply_filters('vc_teaser_grid_thumbnail', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "thumbnail" => $thumbnail, "media_link" => $p_link) );
}
if ( $content ) {
    $to_filter = '<div class="entry-content">' . $content . '</div>';
    $teasers .= apply_filters('vc_teaser_grid_content', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "content" => $content, "media_link" => $p_link) );
}

 $to_filter = '<div class="post-details">
						<div class="left">
							<img src="images/icon-type-image.png" alt="" /><i>in</i> <a href="" title="">Images</a>, <a href="" title="">Design</a> <i>by</i> <a href="">Pego</a> 
						</div>
					</div>';
    $teasers .= apply_filters('vc_teaser_grid_content', $to_filter, array("grid_layout" => $grid_layout, "ID" => $post_id, "content" => $content, "media_link" => $p_link) );