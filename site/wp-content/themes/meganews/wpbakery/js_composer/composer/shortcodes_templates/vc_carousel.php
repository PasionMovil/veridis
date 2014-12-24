<?php
$output = $title = $type = $onclick = $custom_links = $img_size = $custom_links_target = $images = $el_class = $interval = '';
extract(shortcode_atts(array(
    'custom_links' => '',
    'custom_links_target' => '',
    'img_size' => '220',
    'images' => ''
), $atts));
$gal_images = '';
$link_start = '';
$link_end = '';
$el_start = '<li>';
$el_end = '</li>';

$id = rand(1, 10000);
$slides_wrap_start = '<div id="carousel'.$id.'" class="flexsliderCarousel"><ul class="slides">';
$slides_wrap_end = '</ul></div>';


if ( $images == '' ) $images = '-1,-2,-3';

//$pretty_rel_random = ' rel="prettyPhoto[rel-'.rand().']"'; //rel-'.rand();
$onclick = 'custom_link';

if ( $onclick == 'custom_link' ) { $custom_links = explode( ',', $custom_links); }
$images = explode( ',', $images);
$i = -1;

foreach ( $images as $attach_id ) {
    $i++;
    if ($attach_id > 0) {
        $post_thumbnail = wpb_getImageBySize(array( 'attach_id' => $attach_id, 'thumb_size' => 'full' ));
    }
    else {
        $different_kitten = 400 + $i;
        $post_thumbnail = array();
        $post_thumbnail['thumbnail'] = '<img src="http://placekitten.com/g/'.$different_kitten.'/300" />';
        $post_thumbnail['p_img_large'][0] = 'http://placekitten.com/g/1024/768';
    }

    $thumbnail = $post_thumbnail['thumbnail'];
    $p_img_large = $post_thumbnail['p_img_large'];
    $link_start = $link_end = '';

    if ( $onclick == 'link_image' ) {
        $link_start = '<a class="prettyphoto" href="'.$p_img_large[0].'"'.$pretty_rel_random.'>';
        $link_end = '</a>';
    }
    else if ( $onclick == 'custom_link' && isset( $custom_links[$i] ) && $custom_links[$i] != '' ) {
        $link_start = '<a href="'.$custom_links[$i].'"' . (!empty($custom_links_target) ? ' target="'.$custom_links_target.'"' : '') . '>';
        $link_end = '</a>';
    }
    $gal_images .= $el_start . $link_start . $thumbnail . $link_end . $el_end;
}

$output .= '<script>
		jQuery(document).ready(function() {
		jQuery("#carousel'.$id.'").flexslider({
			animation: "slide",
			animationLoop: false,
			itemWidth: '.$img_size.',
			itemMargin: 20,
			slideshow: false,
			move: 1,
			minItems: 1
		  });
		});
		</script>';

$output .= "\n\t\t".'<div class="wpb_wrapper">';
$output .= '<div class="wpb_carousell_slides">'.$slides_wrap_start.$gal_images.$slides_wrap_end.'</div>';
$output .= "\n\t\t".'</div> '.$this->endBlockComment('.wpb_wrapper');


echo $output;