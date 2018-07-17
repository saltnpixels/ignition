<?php

/**
 * Here you will find various hooks and filters and the ability to change how users can view stuff
 */
/*--------------------------------------------------------------
# Development Work
--------------------------------------------------------------*/
/**
 * Add to the wp log for development and debugging
 */
if ( ! function_exists( 'write_log' ) ) {
	function write_log( $log ) {
		if ( true === WP_DEBUG ) {
			if ( is_array( $log ) || is_object( $log ) ) {
				error_log( print_r( $log, true ) );
			} else {
				error_log( $log );
			}
		}
	}
}


/**
 * admin_bar_color_dev_site function.
 * So you can tell if your wokring on dev site or staging site by checking if admin bar is blue or not. Blue means staging
 * @access public
 * @return void
 */
function admin_bar_color_dev_site() {
	if ( strpos( home_url(), '.dev' ) !== false || strpos( home_url(), 'staging' ) !== false || strpos( home_url(), '.local' ) || strpos( home_url(), 'dev.' ) ) {
		echo '<style>
						body #wpadminbar{ background: #156288; }
						</style>';
	}
}

add_action( 'wp_head', 'admin_bar_color_dev_site' );
add_action( 'admin_head', 'admin_bar_color_dev_site' );




/*--------------------------------------------------------------
# Menu Work
--------------------------------------------------------------*/
//hooks found here:
//https://developer.wordpress.org/reference/classes/walker_nav_menu/

function ign_nav_menu_css_class( $classes, $item, $args ) {

		if($item->menu_item_parent==0){ //Count top level menu items
			$classes[] = 'top-level-item';
		}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'ign_nav_menu_css_class', 10, 3 );

//add buttons for dropdowns when there is a sub-menu. sorruound anchor and button
function ign_menu($item, $args){
    $classes = $args->classes;
  
    if(in_array('menu-item-has-children', (array) $classes)){
        $item .= '<button aria-expanded="false" class="submenu-dropdown-toggle">' . ign_get_svg(array("icon"=>"angle-right")).'
                    <span class="screen-reader-text">' . __( 'Expand child menu', 'ignition' ) . '</span></button>';
    }
    return '<div class="menu-item-link">' . $item . '</div>';
}
add_filter('walker_nav_menu_start_el', 'ign_menu', 10, 99);

/*--------------------------------------------------------------
# Logo stuff
--------------------------------------------------------------*/
/**
 * Allow upload of svg for logos in media library
 */
function cc_mime_types( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';

	//add more mimes here...
	return $mimes;
}

add_filter( 'upload_mimes', 'cc_mime_types' );


/**
 * @param $data
 * @param $file
 * @param $filename
 * @param $mimes
 *
 * @return array
 */
function svgs_disable_real_mime_check( $data, $file, $filename, $mimes ) {
	$wp_filetype = wp_check_filetype( $filename, $mimes );

	$ext             = $wp_filetype['ext'];
	$type            = $wp_filetype['type'];
	$proper_filename = $data['proper_filename'];

	return compact( 'ext', 'type', 'proper_filename' );
}

add_filter( 'wp_check_filetype_and_ext', 'svgs_disable_real_mime_check', 10, 4 );


/**
 * output_inline_svg logo function by filtering the logo returned
 * Outputs inline svg logo when using customizer or outputting theme logo using get_custom_logo function
 * @access public
 *
 * @param mixed $html
 *
 * @return void
 */
function ignition_output_inline_svg( $html ) {
	$logo_id = get_theme_mod( 'custom_logo' ); //made by wp with add_theme_support

	if ( get_post_mime_type( $logo_id ) == 'image/svg+xml' ) {
		$image = get_attached_file( $logo_id );
		$html  = preg_replace( "/<img[^>]+\>/i", file_get_contents( $image ), $html );
	}

	return $html;
}

add_filter( 'get_custom_logo', 'ignition_output_inline_svg' );


/**
 * ignition_logo function.
 *
 * @access public
 *
 * @param bool if $return_image_url instead if this is true
 *
 * @return String for logo with h1 or p based on page.
 */
function ign_logo() {

	if ( has_custom_logo() ) {
		$logo = get_custom_logo();
	} else { //no theme mod found. Get site title instead.
		$no_image = true;
		$logo     = '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . get_bloginfo( 'name' ) . '
		</a>';

	}//theme mod

	//now if we have a custom logo, we output logo_output or both on customizer page or url for login page
	//if we are in the customizer preview get both and hide/show based on js
	if ( is_customize_preview() ) {
		return '<div class="site-logo"><h1 class="site-title">' . $logo . '<a class="site-name" href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . get_bloginfo( 'name' ) . '
				</a>' . '</h1></div>';
	}


	if ( is_front_page() ) {
		//$logo is the custom logo wrapped in a link
		return '<div class="site-logo"><h1 class="site-title">' . $logo . '</h1></div>';
	} else {
		return '<div class="site-logo"><p class="site-title">' . $logo . '</p></div>';
	}

}


/**
 * Add logo to login page inline. Not with CSS. By using the message area Above
 */
function the_login_logo( $message ) {
	if ( empty( $message ) ) {
		return ign_logo();
	} else {
		return ign_logo() . $message;
	}
}

add_filter( 'login_message', 'the_login_logo' );


/**
 * Hide wordpress logo and make current logo centered.
 */
function my_login_styles() {
	wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/login-style.css' );
}

add_action( 'login_enqueue_scripts', 'my_login_styles' );


/**
 * Login page logo points to home page
 */
function the_url() {
	return get_bloginfo( 'url' );
}

add_filter( 'login_headerurl', 'the_url' );


/*--------------------------------------------------------------
# ADMIN ACCESS AND ADMIN BAR VISIBILITY
--------------------------------------------------------------*/

/**
 * Disable admin bar for everyone but admins
 *
 */
function disable_admin_bar() {
	if ( ! current_user_can( 'manage_options' ) ) {
		add_filter( 'show_admin_bar', '__return_false' );
	}
}

add_action( 'after_setup_theme', 'disable_admin_bar' );


/**
 * Redirect back to homepage and not allow access to WP Admin. Except admins and ajax
 */
function redirect_admin() {
	if ( ! current_user_can( 'manage_options' ) && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
		wp_redirect( home_url() );
		exit;
	}
}

add_action( 'admin_init', 'redirect_admin' );


/*--------------------------------------------------------------
# Comment override walker
--------------------------------------------------------------*/

/**
 * Ovveride comment section. Now you can change html and css how you want!
 * Taken from class-walker-comment.php html5 comment walker
 */

//html5 comment
function ignition_comments_callback( $comment, $args, $depth ) {

	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
	?>
	<<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>"
	<?php comment_class( $args['has_children'] ? 'parent' : '', $comment ); ?>>
	<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
		<footer class="comment-meta">
			<div class="comment-author vcard">
				<?php if ( 0 != $args['avatar_size'] ) {
					echo get_avatar( $comment, $args['avatar_size'] );
				} ?>
			</div>
			<!-- .comment-author -->

			<div class="comment-name-date">
				<?php printf( __( '%s <span class="says">says:</span>' ), sprintf( '<b class="fn">%s</b>', get_comment_author_link( $comment ) ) ); ?>

				<div class="comment-metadata">
					<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php
							/* translators: 1: comment date, 2: comment time */
							printf( __( '%1$s at %2$s' ), get_comment_date( '', $comment ), get_comment_time() );
							?>
						</time>
					</a>
					<?php edit_comment_link( __( 'Edit' ), '<span class="edit-link">', '</span>' ); ?>
				</div>
				<!-- .comment-metadata -->
			</div>

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation">
					<?php _e( 'Your comment is awaiting moderation.' ); ?>
				</p>
			<?php endif; ?>
		</footer>
		<!-- .comment-meta -->

		<div class="comment-content">
			<?php comment_text(); ?>
		</div>
		<!-- .comment-content -->

		<?php
		comment_reply_link( array_merge( $args, array(
			'add_below' => 'div-comment',
			'depth'     => $depth,
			'max_depth' => $args['max_depth'],
			'before'    => '<div class="reply">',
			'after'     => '</div>'
		) ) );
		?>


	</article>
	<!-- .comment-body -->
	<?php
}


/*--------------------------------------------------------------
# Page as Archive Feature
--------------------------------------------------------------*/
/**
 * Check if this page is being used as an archive header.
 * return true
 * if $return_state is true return the actual state to be used in admin on list of pages
 */

/**
 * ignition_is_page_archive_header function.
 *
 * @access public
 *
 * @param int $post_id
 * @param bool $return_state (default: false)
 *
 * @return string state label OR true
 */
function ign_is_page_archive_header( $post_id, $return_type = '' ) {

	if( 'page' == get_post_type( $post_id ) ) {
		//get all post types that have an archive page and get the main post type post.

		$ign_post_types   = get_post_types( array( '_builtin' => false, 'has_archive' => true ), 'objects' );
		$ign_post_types[] = get_post_type_object( 'post' ); //add this built in object only.


		if ( ! empty( $ign_post_types ) ) {

			//foreach post type, if that post type has a theme mod associated with it and that id is equal to this page, return true
			foreach ( $ign_post_types as $key => $post_type ) {

				if ( (int) get_theme_mod( 'ign_archive_' . $post_type->name ) == $post_id ) {
					if($return_type == 'label'){
						return __( 'Archive ', 'cmlaw' ) . $post_type->labels->singular_name;
					}

					if($return_type == 'post_type'){
						return $post_type->name;
					}

					else{
						return true;
					}
					break;
				} //if theme mod with this post type has this page
			} //foreach
		} //if page
	}

	return false;
}




//add state label to a page used as an archive header
function custom_post_states( $states, $post ) {

	$state = ign_is_page_archive_header( $post->ID, 'label' );

	if ( $state ) {
		$states[] = $state;
	}

	return $states;
}

add_filter( 'display_post_states', 'custom_post_states', 10, 2 );


//check if current archive has a page being used and return page id
/**
 * @return int ID|string
 */
function ign_get_archive_page() {
	if ( is_post_type_archive() || is_home() ) {
		global $wp_query;
		$post_type = $wp_query->get( 'post_type' ) == '' ? 'post' : $wp_query->get( 'post_type' );

		return get_theme_mod( 'ign_archive_' . $post_type );
	}

	return '';
}

add_action('admin_bar_menu', 'add_archive_edit_link', 100);
function add_archive_edit_link($admin_bar) {
    $archive_id = ign_get_archive_page();
    if(! is_admin() && is_post_type_archive() && $archive_id) {
	    $admin_bar->add_menu( array(
		    'id'    => 'archive-link',
		    'title' => __('Edit ' . get_post_type_object(get_post_type())->labels->name, 'ignition'),
		    'href'  => get_edit_post_link($archive_id),
	    ) );
    }
}

/*--------------------------------------------------------------
# ACF functions if not installed. Just some text
--------------------------------------------------------------*/
function plugin_init() {
	if ( ! function_exists( 'have_rows' ) ) {
		function have_rows( $field = '', $id = 0 ) {
			return __( 'Please install ACF Pro to use this function properly', 'ignition' );
		}
	}

	if ( ! function_exists( 'the_row' ) ) {
		function the_row() {
			return __( 'Please install ACF Pro to use this function properly', 'ignition' );
		}
	}

	if ( ! function_exists( 'get_field' ) ) {
		function get_field( $field = '', $id = 0 ) {
			return __( 'Please install ACF Pro to use this function properly', 'ignition' );
		}
	}

	if ( ! function_exists( 'the_field' ) ) {
		function the_field( $field = '', $id = 0 ) {
			return __( 'Please install ACF Pro to use this function properly', 'ignition' );
		}
	}

	if ( ! function_exists( 'the_sub_field' ) ) {
		function the_sub_field( $field = '', $id = 0 ) {
			return __( 'Please install ACF Pro to use this function properly', 'ignition' );
		}
	}

	if ( ! function_exists( 'get_sub_field' ) ) {
		function get_sub_field( $field = '', $id = 0 ) {
			return __( 'Please install ACF Pro to use this funciton properly', 'ignition' );
		}
	}
}

add_action( 'plugins_loaded', 'plugin_init' );

/**
 * @param $field
 *
 * @return mixed
 * Makes the loop field display archive or the content depending on what is being looked at.
 */
function acf_loop_field( $field ) {
	global $post;
	if($post){
	$post_type = ign_is_page_archive_header( $post->ID, 'post_type' );
	if ( is_admin() && get_post_type() == 'page' && $post_type ) {
		$field['layouts']['5afb034fab739']['label'] = ucfirst($post_type) . ' Card List';
	}
}

	return $field;
}

add_filter( 'acf/load_field/name=sections', 'acf_loop_field' );


/**
 * @param $post_id
 * Save the sections to the content just in case a new theme is used and the pages need to be exported. Also lets SEO work and searching works
 */
$saving_sections = false; //useful if making sections that should not be saved to the_content
// when making a sections that should be ignored.
function save_sections( $post_id ) {

	global $post;
	if ( $post ) {
		$id = $post->ID;
		if ( $post_id == $id ) {
			if ( function_exists( 'have_rows' ) && have_rows( 'sections', $id ) ) {
				ob_start();
				global $saving_sections;
				$saving_sections = true;
				locate_template( 'template-parts/blocks/sections.php', true );
				$sections        = ob_get_clean();
				$saving_sections = false;
				wp_update_post( array( 'ID' => $post_id, 'post_content' => $sections ) );
			}
		}
	}
}

add_action( 'acf/save_post', 'save_sections', '99' );




/*--------------------------------------------------------------
# pre get posts
--------------------------------------------------------------*/
function set_posts_per_page_for_post_types( $query ) {
	if ( ! is_admin() && $query->is_main_query() ) {

		//vanilla search with no post type uses search.php and only shows posts, and acts like index page
		if ( $query->is_search() && ! $query->is_post_type_archive() ) {
			$query->set( 'post_type', 'post' );
			$query->is_home = true;
		}

	}

}

add_action( 'pre_get_posts', 'set_posts_per_page_for_post_types' );