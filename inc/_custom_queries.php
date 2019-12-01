<?php

/*--------------------------------------------------------------
# Pre Post Queries
--------------------------------------------------------------*/
/**
 * @param $query
 * Here you can change the default query for any WordPress page/post or archive
 */
function set_posts_per_page_for_post_types( $query ) {
	if ( ! is_admin() && $query->is_main_query() ) {

//vanilla search with no post type uses search.php and only shows posts
		if ( $query->is_search() && ! $query->is_post_type_archive() ) {
			$query->set( 'post_type', 'post' );
		}

	}

}

//add_action( 'pre_get_posts', 'set_posts_per_page_for_post_types' );
//uncomment this if you want to use it
