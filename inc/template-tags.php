<?php
/**
 * Custom template tags for ignition
 * Most expect you to be in the loop
 * ign_get_terms does not need to be in loop
 *
 * @package Ignition
 * @since 1.0
 */


/*
 * Prints the author image inside a link and  name inside another link.
*/
if ( ! function_exists( 'ign_posted_by' ) ) {
	function ign_posted_by() {
		$author_id          = get_the_author_meta( 'ID' );
		$author_link        = esc_url( get_author_posts_url( $author_id ) );
		$author_image       = '<a href="' . $author_link . '" class="author-avatar">' . get_avatar( $author_id, 50 ) . '</a>';
		$author_name        = sprintf( __( '%s by %s', 'ignition' ), '<a href="' . $author_link . '" class="author-name"><span class="byline">', '</span>' . get_the_author() . '</a>' );
		$author_description = '<div class="author-description">' . get_the_author_meta( 'description' ) . '</div>';

		echo '<div class="posted-by">' . $author_image . '<div class="author-info">' . $author_name . $author_description . '</div></div>';

	}
}


/**
 * Post the time
 */

/**
 * Prints HTML with meta information for the current post-date/time.
 */
if ( ! function_exists( 'ign_posted_on' ) ) {
	function ign_posted_on( $date_format = '' ) {
		// post published and modified dates
		echo '<div class="posted-on">' . ign_time_link( $date_format ) . '</div>';
	}
}


/**
 * Gets link with date
 */
if ( ! function_exists( 'ign_time_link' ) ) {

	function ign_time_link( $date_format ) {
		$time_string = '<time class="published" datetime="%1$s">%2$s</time>';

		//if when post was made does not equal the modified date
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="published" datetime="%1$s">%2$s</time><time class="updated screen-reader-text" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			get_the_date( DATE_W3C ),
			get_the_date( $date_format ),
			get_the_modified_date( DATE_W3C ),
			get_the_modified_date( $date_format )
		);

		// Wrap the time string in a link, and preface it with 'Posted on'.
		return sprintf(
		/* translators: %s: post date */
			__( '<span class="screen-reader-text">Posted on</span> %s', 'ganton' ),
			'<a class="entry-date" href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);
	}
}


/**
 * @param string $taxonomy
 * @param bool $get_all
 *
 * @return string|void
 * Get all or one term for a taxonomy for a post
 */
if ( ! function_exists( 'ign_get_term_links' ) ) {

	function ign_get_term_links( $taxonomy = 'category', $get_all = false, $id = 0 ) {

		if ( ! $id ) {
			$id = get_the_ID();
		}

		$terms = get_the_terms( $id, $taxonomy );

		if ( $terms && ! is_wp_error( $terms ) ) :
			//get first term found
			if ( ! $get_all ) {
				$term              = $terms[0];
				$term_links_output = '<a class="term-link ' . $taxonomy . '" href="' . get_term_link( $term->term_id, $taxonomy ) . '">' . $term->name . '</a>';
			} //else get all terms with a comma
			else {
				$term_links = array();
				foreach ( $terms as $term ) {
					$term_links[] = '<a class="term-link ' . $taxonomy . '" href="' . get_term_link( $term->term_id, $taxonomy ) . '">' . $term->name . '</a>';
				}
				//add comma after each one
				$term_links_output = join( '<span class="delim">' . __( ', ', 'ignition' ) . '</span>', $term_links );
			}

			return $term_links_output;
		endif;

		return '';
	}
}


/**
 * @param string $taxonomy
 *
 * @return string|void
 * Get all term objects for a post
 * fast way to grab and run a foreach loop
 */
if ( ! function_exists( 'ign_get_terms' ) ) {

	function ign_get_terms( $taxonomy = 'category', $id = 0 ) {
		if ( ! $id ) {
			$id = get_the_ID();
		}

		$terms = get_the_terms( $id, $taxonomy );

		if ( $terms && ! is_wp_error( $terms ) ) :
			return $terms;
		endif;

		return array();
	}
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
				$write_comments = '<a class="comment-link" href="' . get_comments_link() . '"><span class="screen-reader-text">Comments</span>' . ign_get_svg( array(
						'icon' => 'comments'
					) ) . ' ' . $comments . '</a>';
			} else {
				$write_comments = '<a class="comment-link" href="' . get_comments_link() . '"><span class="screen-reader-text">Comments</span>' . ign_get_svg( array(
						'icon' => 'comments'
					) ) . ' ' . $num_comments . '</a>';
			}

			return $write_comments;
		}

		return;
	}
endif;


/**
 * ign edit link
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

	edit_post_link(
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
}

/**
 * Checks to see if we're on the static front homepage or not.
 */
function is_static_frontpage() {
	return ( is_front_page() && ! is_home() );
}


/**
 * Returns the html image  for either the post thumbnail or a acf image field.
 * This goes through filters of WP and returns an image with possible mutiple srcsets
 * acf image field must be set to array
 *
 * @param int $post_id
 * @param string $size
 * @param string $attr
 * @param  mixed $acf_image
 *
 * @return string
 */
function ign_get_image( $acf_image = '', $post_id = 0, $size = 'post-thumbnail', $attr = '', $use_thumbnail_as_fallback = false ) {

	if ( ! $post_id && ! is_tax() ) {
		global $post;
		$post_id = $post->ID;
	}

	if(is_tax() && ! $post_id){
		$post_id = 'term_' . get_queried_object()->term_id;
	}

	//if were passed a string we first need to get the image from the field
	if(is_string($acf_image)){
		if(get_row_index()){
			$acf_image = get_sub_field($acf_image, $post_id);
		}else{
			$acf_image = get_field($acf_image, $post_id);
		}
	}


	$image = '';
	if ( $acf_image && is_array($acf_image) ) {
		$image_id = $acf_image['id'];
		$image    = wp_get_attachment_image( $image_id, $size, '', $attr );
	} else {
		if ( has_post_thumbnail( $post_id ) && $use_thumbnail_as_fallback ) {
			$image = get_the_post_thumbnail( $post_id, $size, $attr );
		}
	}

	return $image;
}

/**
 * Returns the image url for either the post thumbnail or a acf image field.
 * acf image field must be set to array
 *
 * @param int $post_id
 * @param string $size
 * @param  mixed $acf_image
 *
 * @return string
 */
function ign_get_image_url( $acf_image = '', $post_id = 0, $size = '', $use_thumbnail_as_fallback = false ) {

	if ( ! $post_id && ! is_tax() ) {
		global $post;
		$post_id = $post->ID;
	}

	if(is_tax() && ! $post_id){
		$post_id = 'term_' . get_queried_object()->term_id;
	}

	//if were passed a string we first need to get the image from the field
	if(is_string($acf_image)){
		if(get_row_index()){
			$acf_image = get_sub_field($acf_image, $post_id);
		}else{
			$acf_image = get_field($acf_image, $post_id);
		}
	}

	$image = '';

	if ( $acf_image && is_array($acf_image) ) {
		$image_id = $acf_image['id'];
		$image    = wp_get_attachment_image_url( $image_id, $size, '' );
	} else {
		if ( has_post_thumbnail( $post_id ) && $use_thumbnail_as_fallback ) {
			$image = get_the_post_thumbnail_url( $post_id, $size );
		}
	}

	return $image;
}


/**
 * @param int $post_id
 * @param string $kind
 * @param string $attr
 *
 * @return string
 *
 * Returns the header image field for a post as a url, unless $return_type is set to anything else. then it returns the actual image element
 * if no image is found it will return the featured image
 * if no image is set, then nothing is returned.
 *
 */
function ign_get_header_image( $post_id = 0, $return_type = 'url', $attr = '' ) {


	if ( ! $post_id ) {
		global $post;
		$post_id = $post->ID;
	}


	if ( function_exists( 'get_field' ) ) {

		if ( get_field( 'no_image', $post_id ) ) {
			return '';
		}

		$image = get_field( 'header_image', $post_id );
	} else {
		$image = '';
	}


	if ( $return_type == 'url' ) {
		return ign_get_image_url( $image, $post_id, 'header_image', true );
	} else {
		return ign_get_image( $image, $post_id, 'header_image', $attr, true );
	}
}


