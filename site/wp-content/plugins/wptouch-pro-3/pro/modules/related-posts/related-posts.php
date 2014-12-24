<?php

add_filter( 'wptouch_setting_defaults_foundation', 'wptouch_related_posts_default_settings' );
add_action( 'wptouch_admin_page_render_wptouch-admin-theme-settings', 'foundation_related_posts_settings' );

function wptouch_related_posts_default_settings( $defaults ) {
	$defaults->enable_related_posts = false;

	return $defaults;
}

function wptouch_has_related_posts() {
	$settings = wptouch_get_settings( 'foundation' );
	if ( !$settings->related_posts_enabled ) {
		return false;
	}

	$related = wptouch_related_posts();
	return ( is_array( $related ) && count( $related ) );
}

function wptouch_related_posts() {
	global $post;
	$old_post = $post;
	$settings = wptouch_get_settings( 'foundation' );

	if ( !$settings->related_posts_enabled ) {
		return false;
	}

	global $wpdb;
	if ( is_single() ) {
		$ids = array();

		$taxonomies = get_taxonomies( array( 'public' => true ) );
		$use_taxonomies = array();

		foreach( $taxonomies as $tax ) {
			$these_tax = wp_get_post_terms( $post->ID, $tax );
			if ( $these_tax ) {
				$use_taxonomies = array_merge( $use_taxonomies, $these_tax );
			}
		}

		foreach( $use_taxonomies as $cat ) {
			$value = 1;

			$results = $wpdb->get_results( $wpdb->prepare( "SELECT object_id FROM " . $wpdb->prefix . "term_relationships WHERE term_taxonomy_id = %d", $cat->term_taxonomy_id ) );
			if ( $results ) {
				$new_ids = array();
				foreach( $results as $result ) {
					$new_ids[] = $result->object_id;
				}

				rsort( $new_ids );
				$new_ids = array_slice( $new_ids, 0, 25 );

				foreach( $new_ids as $id ) {

					$post_info = get_post( $id );
					if ( isset( $post_info->post_status ) && $post_info->post_status == 'publish' ) {
						if ( !isset( $ids[ $id ] ) ) {
							$ids[ $id ] = $value;
						} else {
							$ids[ $id] += $value;
						}
					}
				}
			}
		}

		if ( $ids ) {
			$related_posts = array();

			unset( $ids[ $post->ID ] );

			arsort( $ids );

			$ids = array_slice( $ids, 0, $settings->related_posts_max, true );

			foreach( $ids as $post_id => $count ) {
				if ( $count < 2 ) {
					continue;
				}

				$post = get_post( $post_id );

				$this_post = new stdClass;
				$this_post->id = $post_id;
				$this_post->link = get_permalink( $post_id );
				$this_post->title = get_the_title();
				$this_post->excerpt = wp_trim_words( apply_filters( 'the_content', $post->post_content ), 20 );

				if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( $post_id ) ) {
					$this_post->thumbnail = get_the_post_thumbnail( $post_id, 'wptouch-new-thumbnail' );
				} else {
					$this_post->thumbnail = '';
				}

				$related_posts[] = $this_post;
			}

			$post = $old_post;

			return $related_posts;
		}
	}
}

function foundation_related_posts_settings( $page_options ){
	wptouch_add_page_section(
		FOUNDATION_PAGE_GENERAL,
		__( 'Related Posts', 'wptouch-pro' ),
		'foundation-related-posts-settings',
		array(
			wptouch_add_setting(
				'checkbox',
				'related_posts_enabled',
				__( 'Enable display of related content on single posts', 'wptouch-pro' ),
				'',
				WPTOUCH_SETTING_BASIC,
				'1.0.7'
			),
			wptouch_add_setting(
				'radiolist',
				'related_posts_max',
				__( 'Maximum number of related posts to show', 'wptouch-pro' ),
				'',
				WPTOUCH_SETTING_BASIC,
				'1.0.7',
				array(
					'2' => '2',
					'3' => '3',
					'4' => '4'
				)
			)
		),
		$page_options,
		FOUNDATION_SETTING_DOMAIN
	);

	return $page_options;

}