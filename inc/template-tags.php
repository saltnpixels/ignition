<?php
/**
 * Custom template tags for ignition
 *
 * @package Ignition
 * @since 1.0
 */


/*
 * Prints the author image inside a link and  name inside another link.
*/
function ign_posted_by() {
	$author_id          = get_the_author_meta( 'ID' );
	$author_link        = esc_url( get_author_posts_url( $author_id ) );
	$author_image       = '<a href="' . $author_link . '" class="author-avatar">' . get_avatar( $author_id, 50 ) . '</a>';
	$author_name        = sprintf( __( '%s by %s', 'ignition' ), '<a href="' . $author_link . '" class="author-name"><span class="byline">', '</span>' . get_the_author() . '</a>' );
	$author_description = '<div class="author-description">' . get_the_author_meta( 'description' ) . '</div>';

	return '<div class="posted-by">' . $author_image . '<div class="author-info">' . $author_name . $author_description . '</div></div>';

}


/**
 * Post the time
 */

/**
 * Prints HTML with meta information for the current post-date/time.
 */
function ign_posted_on() {
	// post published and modified dates
	echo '<div class="posted-on">' . ign_time_link() . '</div>';
}


/**
 * Gets link with date
 */
function ign_time_link() {
	$time_string = '<time class="published" datetime="%1$s">%2$s</time>';

	//if when post was made does not equal the modified date
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		get_the_date( DATE_W3C ),
		get_the_date(),
		get_the_modified_date( DATE_W3C ),
		get_the_modified_date()
	);

	// Wrap the time string in a link, and preface it with 'Posted on'.
	return sprintf(
	/* translators: %s: post date */
		__( '<span class="screen-reader-text">Posted on</span> %s', 'ignition' ),
		'<a class="entry-date" href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);
}


/**
 * @param string $taxonomy
 * @param bool $get_all
 *
 * @return string|void
 * Get all or one term for a taxonomy for a post
 */
function ign_get_terms( $taxonomy = 'category', $get_all = false ) {

	$terms = get_the_terms( get_the_ID(), $taxonomy );

	if ( $terms && ! is_wp_error( $terms ) ) :
		//get first term found
		if ( ! $get_all ) {
			$term = array_pop( $terms );

			$term_links_output = '<a class="term-link ' . $taxonomy . '" href="' . get_term_link( $term->term_id, $taxonomy ) . '">' . $term->name . '</a>';
		} //else get all terms with a comma
		else {
			$term_links = array();
			foreach ( $terms as $term ) {
				$term_links[] = '<a class="term-link ' . $taxonomy . '" href="' . get_term_link( $term->term_id, $taxonomy ) . '">' . $term->name . '</a>';
			}
			$term_links_output = join( '<span class="delim">' . __( ', ', 'ignition' ) . '</span>', $term_links );
		}

		return $term_links_output;
	endif;

	return;
}


if ( ! function_exists( 'ign_comment_link' ) ) :
	/**
	 * Returns the comment link with icon for comments as well as comment or comments if wanted.
	 */
	function ign_comment_link( $comment_string = false ) {
		$num_comments = get_comments_number(); // get_comments_number returns only a numeric value

		if ( comments_open() ) {
			if ( $num_comments == 0 ) {
				$num_comments = '';
				$comments     = __( 'No Comments' );
			} elseif ( $num_comments > 1 ) {
				$comments = $num_comments . __( ' Comments' );
			} else {
				$comments = __( '1 Comment' );
			}

			if ( $comment_string ) {
				$write_comments = '<a class="comment-link" href="' . get_comments_link() . '">' . ign_get_svg( array(
						'icon' =>
							'comments'
					) ) . ' ' . $comments . '</a>';
			} else {
				$write_comments = '<a class="comment-link" href="' . get_comments_link() . '">' . ign_get_svg( array(
						'icon' =>
							'comments'
					) ) . ' ' . $num_comments . '</a>';
			}

			return $write_comments;
		}

		return;
	}
endif;


/**
 *
 */
function ign_edit_link( $id = null, $class = '', $text = 'Edit' ) {
	global $saving_sections;
	if ( $saving_sections ) {
		return;
	}

	if ( ! $id ) {
		global $post;
		$id = $post->ID;
	}
	$link = edit_post_link(
		sprintf(
		/* translators: %s: Name of current post */
			__( '%s<span class="screen-reader-text"> "%s"</span>', 'ignition' ),
			$text, get_the_title()
		),
		'<span class="edit-link">',
		'</span>',
		$id,
		$class
	);

	return $link;
}

/**
 * Checks to see if we're on the static homepage or not.
 */
function is_static_frontpage() {
	return ( is_front_page() && ! is_home() );
}


/**
 * Returns the html image  for either the post thumbnail or a acf image field.
 * This goes through filters of WP and returns an image with possible mutiple srcsets
 * acf image field must be set to array
 *
 * @param string $id
 * @param string $size
 * @param string $attr
 * @param        $acf_image
 *
 * @return string
 */
function ign_get_the_image( $id = '', $size = '', $acf_image = '', $attr = '', $use_thumbnail_as_fallback = true ) {

	//if were trying to get the header image, and the user specified not to, then return none.
	if( $size == 'header_image' && get_field('no_image', $id)){
		return '';
	}

	$image = '';
	if ( $acf_image ) {
		$image_id = $acf_image['id'];
		$image    = wp_get_attachment_image( $image_id, $size, '', $attr );
	} else {
		if ( has_post_thumbnail( $id ) && $use_thumbnail_as_fallback ) {
			$image = get_the_post_thumbnail( $id, $size, $attr );
		}
	}

	return $image;
}

/**
 * Returns the image url for either the post thumbnail or a acf image field.
 * acf image field must be set to array
 *
 * @param string $id
 * @param string $size
 * @param        $acf_image
 *
 * @return string
 */
function ign_get_the_image_url( $id = '',  $size = '', $acf_image = '',  $use_thumbnail_as_fallback = true ) {

	$image = '';

	if ( $acf_image ) {
		$image_id = $acf_image['id'];
		$image    = wp_get_attachment_image_url( $image_id, $size, '' );
	} else {
		if ( has_post_thumbnail( $id ) && $use_thumbnail_as_fallback ) {
			$image = get_the_post_thumbnail_url( $id, $size );
		}
	}

	return $image;
}



function ign_get_the_header_image($id, $kind = 'url', $attr = ''){
	if( get_field('no_image', $id) ){
		return '';
	}

	$image = get_field('header_image');
	if($kind == 'url'){
		return ign_get_the_image_url($id, 'header_image', $image);
	}
	else{
		return ign_get_the_image($id, 'header_image', $image, $attr);
	}
}

