<?php
/**
 * Custom template tags for ignition
 * Most expect you to be in the loop
 * ign_get_terms  and ign_get_term_links does not need to be in loop
 *
 * @package Ignition
 * @since   1.0
 */


/**
 * Checks to see if we're on a static front homepage or not, as opposed to the blog frontpage.
 */
if ( ! function_exists( 'is_static_frontpage' ) ) :
	function is_static_frontpage() {
		return ( is_front_page() && ! is_home() );
	}
endif;


/*
 * Prints the author image inside a link and  name inside another link.
*/
if ( ! function_exists( 'ign_author_meta' ) ) {
	function ign_author_meta( $post_id = 0, $avatar_size = 50 ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$author_id = get_post_field( 'post_author', $post_id );

		return array(
			'author_id'          => $author_id,
			'author_page'        => esc_url( get_author_posts_url( $author_id ) ),
			'author_image'       => get_avatar( $author_id, $avatar_size ),
			'author_name'        => get_the_author_meta( 'display_name', $author_id ),
			'author_description' => get_the_author_meta( 'description', $author_id ),
		);
	}
}


/*
 * Prints the author image inside a link and  name inside another link.
*/
if ( ! function_exists( 'ign_posted_by' ) ) {
	function ign_posted_by( $post_id, $avatar_size = 50 ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		$author_meta        = ign_author_meta( $post_id, $avatar_size );
		$author_link        = esc_url( get_author_posts_url( $author_meta['author_id'] ) );
		$author_image       = '<a href="' . $author_link . '" class="author-avatar">' . $author_meta( 'author_image' ) . '</a>';
		$author_name        = sprintf( __( '%s by %s', 'ignition' ), '<a href="' . $author_link . '" class="author-name"><span class="byline">', '</span>' . $author_meta( 'author_name' ) . '</a>' );
		$author_description = '<div class="author-description">' . $author_meta( 'author_description' ) . '</div>';

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
	function ign_posted_on( $post_id = '', $date_format = '' ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		// post published and modified dates
		echo '<div class="posted-on">' . ign_time_link( $post_id, $date_format ) . '</div>';
	}
}


/**
 * Gets link with date
 */
if ( ! function_exists( 'ign_time_link' ) ) {

	function ign_time_link( $post_id = '', $date_format ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		$time_string = '<time class="published" datetime="%1$s">%2$s</time>';

		//if when post was made does not equal the modified date
		if ( get_the_time( 'U', $post_id ) !== get_the_modified_time( 'U', $post_id ) ) {
			$time_string = '<time class="published" datetime="%1$s">%2$s</time><time class="updated screen-reader-text" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			get_the_date( DATE_W3C, $post_id ),
			get_the_date( $date_format, $post_id ),
			get_the_modified_date( DATE_W3C, $post_id ),
			get_the_modified_date( $date_format, $post_id )
		);

		// Wrap the time string in a link, and preface it with 'Posted on'.
		return sprintf(
		/* translators: %s: post date */
			__( '<span class="screen-reader-text">Posted on</span> %s', 'ignition' ),
			'<a class="entry-date" href="' . esc_url( get_permalink( $post_id ) ) . '" rel="bookmark">' . $time_string . '</a>'
		);
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
		if ( $id != 0 ) {
			$terms = get_the_terms( $id, $taxonomy );

			if ( $terms && ! is_wp_error( $terms ) ) :
				return $terms;
			endif;
		}

		return array();
	}
}


if ( ! function_exists( 'ign_comment_link' ) ) :
	/**
	 * Returns the comment link with icon for comments as well as comment or comments if wanted.
	 */
	function ign_comment_link( $post_id = '', $icon = '', $comment_string = false ) {
		$num_comments = get_comments_number( $post_id ); // get_comments_number returns only a numeric value
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		if ( ! $icon ) {
			$icon = ign_get_config( 'comment_icon', "<svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' aria-hidden='true' focusable='false' width='0.71em' height='1em' style='vertical-align: -0.2em;-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);' preserveAspectRatio='xMidYMid meet' viewBox='0 -205 768 1086'><path d='M227 507L97 594s27-72 29-124C49 429 0 362 0 287C0 162 139 60 310 60c142 0 262 71 299 166c-14-2-28-3-42-3h-14c-70 3-135 26-185 65c-55 44-85 106-83 168c1 19 5 40 12 58c-24 0-47-3-70-7zm481 127l-85-46c-17 4-37 7-55 8h-12c-109 0-197-63-200-143c-4-83 86-154 200-158h11c109 0 198 61 201 142c2 49-28 93-77 123c2 35 17 74 17 74z' fill='currentColor'/><rect x='0' y='-205' width='768' height='1086' fill='rgba(0, 0, 0, 0)' /></svg>" );
		}
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
				$write_comments = '<a class="comment-link" href="' . get_comments_link( $post_id ) . '"><span class="screen-reader-text">Comments</span>' . $icon . ' ' . $comments . '</a>';
			} else {
				$write_comments = '<a class="comment-link" href="' . get_comments_link( $post_id ) . '"><span class="screen-reader-text">Comments</span>' . $icon . ' ' . $num_comments . '</a>';
			}

			return $write_comments;
		}

		return;
	}
endif;


/**
 * ign edit link with some extras and markup
 *
 */
if ( ! function_exists( 'ign_edit_link' ) ) :
	function ign_edit_link( $id = null, $class = '', $text = 'Edit' ) {

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
endif;


/**
 * @param string $file_prefix
 * @param array $vars
 *  * Gets the proper template file to show for the WP LOOP
 * Simply routes the page to the right folder and file, or falls back on the post folder.
 * post formats should be used at end of file like: card-link.php
 *
 */
function ign_template( $file_prefix = 'content', $vars = array() ) {

	$format = '';
	if ( get_post_format() ) {
		$format = get_post_format() . '-';
	}
	$post_type = get_post_type();

	//if no vars are passed, at least pass empty $block because there is a chance we are grabbing a template that expects $block to exist for block attributes.
	$vars = wp_parse_args($vars, array('block' => '', 'post_id'=> get_the_ID()));
	set_query_var( 'block', $vars['block'] ); //you can use $blocks without having to parse args for fast reference
	set_query_var('post_id', $vars['post_id']);

	//also allow for full paths. if / is found in prefix then we wont check src. we expect a full path no prefix
	if ( strpos( $file_prefix, '/' ) !== false ) {
		locate_template( $file_prefix, true, false );
	} else {

		locate_template( array(
			'src/parts/' . $post_type . '/' . $file_prefix . '-' . $format . $post_type . '.php',
			//searches for post-type/{prefix}-{post-type}.php inside post type folder
			'src/parts/' . $post_type . '/' . $file_prefix . '-' . $format . '.php',
			//if it cant find the above, searches for post-type/{prefix}.php in post-type folder
			'src/parts/post/' . $file_prefix . '-' . $format . 'post.php',
			//searches for post/{prefix}-post.php
			'src/parts/post/content-' . $format . 'post.php',
			//gets post/content.php
		),
			true,
			false,
		$vars
		);

	}
}

//deprecated
function ign_loop( $file_prefix = 'content', $vars = '' ) {
	ign_template( $file_prefix, $vars );
}


/**
 * @param string $file_prefix
 * @param $posts
 * @param bool $past_as_ids
 *
 * quick way to get templates for a list of ID's like from a relationship field.
 */
function ign_loop_ids( $file_prefix = 'content', $posts, $vars = '', $past_as_ids = false ) {
	if ( is_array( $posts ) ) {
		global $post;
		$old_post = $post;

		if ( $past_as_ids ) {
			$posts = get_posts( $posts );
		}

		foreach ( $posts as $post ) {
			setup_postdata( $post );
			ign_template( $file_prefix, $vars );
		}
		$post = $old_post; //setting it back to whatever it was.
	}
}


/**
 * Returns the html image for acf image field.
 * Can default to show thumbnail
 * show image with filters properly set
 * This goes through filters of WP and returns an image with possible multiple srcsets
 *
 * @param int $post_id
 * @param string $size
 * @param string $attr
 * @param mixed $acf_image
 *
 * @return string
 */
function ign_get_image( $acf_image = '', $post_id = false, $size = 'post-thumbnail', $attr = '', $use_thumbnail_as_fallback = false ) {

	$image = '';

	//if its a string turn it into an array
	if ( is_string( $acf_image ) && function_exists( 'acf_get_loop' ) ) {
		if ( acf_get_loop() ) {
			$acf_image = get_sub_field( $acf_image, $post_id );
		} else {
			$acf_image = get_field( $acf_image, $post_id );
		}
	}

	//get image through WP
	if ( $acf_image && is_array( $acf_image ) ) {
		$image_id = $acf_image['id'];
		$image    = wp_get_attachment_image( $image_id, $size, '', $attr );
	} else {
		//fallback on thumbnail if wanted
		if ( is_single() && has_post_thumbnail() && $use_thumbnail_as_fallback ) {
			$image = get_the_post_thumbnail( $post_id, $size, $attr );
		}
	}

	return $image;
}

/**
 * Returns the image url for acf image field.
 * acf image field must be set to array
 *
 * @param int $post_id
 * @param string $size
 * @param mixed $acf_image
 *
 * @return string
 */
function ign_get_image_url( $acf_image = '', $post_id = false, $size = '', $use_thumbnail_as_fallback = false ) {

	$image = '';

	//if were passed a string we first need to get the image from the field. make sure acf is also installed
	if ( is_string( $acf_image ) && function_exists( 'acf_get_loop' ) ) {
		if ( acf_get_loop() ) {
			$acf_image = get_sub_field( $acf_image, $post_id );
		} else {
			$acf_image = get_field( $acf_image, $post_id );
		}
	}


	if ( $acf_image && is_array( $acf_image ) ) {
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
 * @param string $size
 * @param string $attr
 *
 * @return string
 *
 * Returns the header image field for a post as a url, unless $return_type is set to anything else. then it returns the actual image element
 * It also checks if the no_image field is checked and if so returns nothing.
 * if no image is found it will return the featured image
 * if no image is set, then nothing is returned.
 *
 */
function ign_get_header_image( $post_id = false, $size = 'header_image', $attr = '' ) {

	if ( function_exists( 'get_field' ) ) {

		if ( get_field( 'no_image', $post_id ) ) {
			return '';
		}

		$image = get_field( 'header_image', $post_id );
	} else {
		$image = '';
	}

	return ign_get_image( $image, $post_id, $size, $attr, true );
}


/**
 * @param bool $post_id
 * @param string $size
 * @param string $attr
 *
 * @return string
 */
function ign_get_header_image_url( $post_id = false, $size = 'header_image', $attr = '' ) {


	if ( function_exists( 'get_field' ) ) {

		if ( get_field( 'no_image', $post_id ) ) {
			return '';
		}

		$image = get_field( 'header_image', $post_id );
	} else {
		$image = '';
	}


	return ign_get_image_url( $image, $post_id, $size, true );
}

/**
 * @param $link_field
 * @param mixed $post_id
 *
 * @return array $link_field
 * If the link field is empty we still return an array so it is set and works
 */
function ign_get_link_field( $link_field, $post_id = false ) {


	if ( is_string( $link_field ) && function_exists( 'acf_get_loop' ) ) {
		if ( acf_get_loop() ) {
			$link_field = get_sub_field( $link_field, $post_id );
		} else {
			$link_field = get_field( $link_field, $post_id );
		}
	}

	//return array even if not link field found so no error occurs
	if ( ! $link_field ) {
		$link_field = array(
			'title'  => '',
			'url'    => 'javascript:;',
			'target' => '_self'
		);
	}

	return $link_field;

}


/**
 * @param $block
 * @param string $custom_classes
 * @param bool $include_align
 * @param bool $echo
 *
 * @return string|void
 */
function ign_block_class( $block, $custom_classes = '', $include_align = true, $echo = true ) {

	if ( $block ) {
		$classnames = isset( $block['className'] ) ? $block['className'] : '';
		$align      = isset( $block['align'] ) && $block['align'] && $include_align ? 'align' . $block['align'] . ' ' : '';
		$classes    = 'acf-' . sanitize_title( strtolower( $block['title'] ) ) . ' ' . $align . $classnames;
		if ( $echo ) {
			echo 'class="' . ( $custom_classes ? $custom_classes . ' ' . $classes : $classes ) . '"';
		} else {
			return ( $custom_classes ? $custom_classes . ' ' . $classes : $classes );
		}
	}

	return;
}


/**
 * @param $block
 * returns the block anchor or the block id to be used as the anchor
 *
 * @return mixed|string
 */
function ign_get_block_anchor( $block ) {
	if ( $block ) {
		return ( isset( $block['anchor'] ) && $block['anchor'] ) ? $block['anchor'] : $block['id'];
	}
}


/**
 * @param $block
 * @param string $custom_classes
 * @param bool $include_align
 */
function ign_block_attrs( $block = '', $custom_classes = '', $include_align = true ) {
	if ( $block ) {
		$block_id    = ign_get_block_anchor( $block );
		$block_class = ign_block_class( $block, $custom_classes, $include_align, false );

		echo "id='$block_id' class='$block_class'";
	} else {
		echo "class='$custom_classes'";
	}
}





/**
 * @param string $prefix
 * Shows a header block if none is in the content
 */
function ign_header_block($prefix = 'header') {
	if (! ign_has_header_block() ) {
		ign_template( $prefix, array( 'block' => '' ) );
	}
}


//fuzzy has_block search for any block with word header-
//has_block searches for exact block names, this searches for a block that starts with header-
/**
 * @param string $block_name
 * @param null $post
 *
 * @return bool
 * Hijacking the has_block function to search for fuzzy header- part
 */
function ign_has_header_block( $block_name = 'acf/header-', $post = null ) {
	if ( ! has_blocks( $post ) ) {
		return false;
	}

	if ( ! is_string( $post ) ) {
		$wp_post = get_post( $post );
		if ( $wp_post instanceof WP_Post ) {
			$post = $wp_post->post_content;
		}
	}

	/*
	 * Normalize block name to include namespace, if provided as non-namespaced.
	 * This matches behavior for WordPress 5.0.0 - 5.3.0 in matching blocks by
	 * their serialized names.
	 */
	if ( false === strpos( $block_name, '/' ) ) {
		$block_name = 'core/' . $block_name;
	}

	// Test for existence of block by its fuzzy qualified name. Searching for it to have the word header- in it
	$has_block = false !== strpos( $post, '<!-- wp:' . $block_name );

	if ( ! $has_block ) {
		/*
		 * If the given block name would serialize to a different name, test for
		 * existence by the serialized form.
		 */
		$serialized_block_name = strip_core_block_namespace( $block_name );
		if ( $serialized_block_name !== $block_name ) {
			$has_block = false !== strpos( $post, '<!-- wp:' . $serialized_block_name );
		}
	}

	return $has_block;
}









