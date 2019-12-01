<?php

/**
 * This file holds actions and filters for advanced functions, you probably dont need to change or touch.
 */



/*--------------------------------------------------------------
# Menu Work
--------------------------------------------------------------*/
//hooks found here:
//https://developer.wordpress.org/reference/classes/walker_nav_menu/
//Add top-level-item to top level menu items for easier styling.
function ign_nav_menu_css_class( $classes, $item, $depth ) {

	if ( $item->menu_item_parent == 0 ) { //Count top level menu items
		$classes[] = 'top-level-item';
	}

	return $classes;
}

add_filter( 'nav_menu_css_class', 'ign_nav_menu_css_class', 10, 3 );


//add buttons for dropdowns when there is a sub-menu.
// surround anchor ( and buttons if there is one)
function ign_menu( $item, $args ) {
	$classes = $args->classes;


	if ( in_array( 'menu-item-has-children', (array) $classes ) ) {
		$item .= '<button tabindex="-1" aria-haspopup="true" data-toggle aria-expanded="false" class="submenu-dropdown-toggle">' . ign_get_svg( array( "icon" => "angle-right" ) ) . '
                    <span class="screen-reader-text">' . __( 'Expand child menu', 'ignition' ) . '</span></button>';
	}

	return '<div class="menu-item-link">' . $item . '</div>';
}

add_filter( 'walker_nav_menu_start_el', 'ign_menu', 10, 99 );

//add search bar to a menu
//add_filter('wp_nav_menu_items', 'add_search_form', 10, 2);
function add_search_form($items, $args) {
	if( $args->theme_location == 'top-menu' )
		$items .= '<li class="menu-item top-level-item"><div class="menu-item-link">' . get_search_form(false) . '</div></li>';
	return $items;
}


/*------- Menu Fallback --------*/
/**
 * Menu fallback. Link to the menu editor if that is useful.
 *
 * @param array $args from wp_nav_menu
 *
 * @return string
 *
 */
function link_to_menu_editor( $args ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return '';
	}

	// see wp-includes/nav-menu-template.php for available arguments
	$link = $args['link_before'] . '<a href="' . admin_url( 'nav-menus.php' ) . '">' . $args['before'] . 'Add a menu' . $args['after'] . '</a>'
	        . $args['link_after'];

	// We have a list, then output the link in an li
	if ( false !== stripos( $args['items_wrap'], '<ul' )
	     or false !== stripos( $args['items_wrap'], '<ol' )
	) {
		$link = "<li class='menu-item top-level-item'><div class='menu-item-link'>$link</div></li>";
	}

	// expects  'items_wrap'  => '<ul id="%1$s" class="%2$s">%3$s</ul>'
	$output = sprintf( $args['items_wrap'], $args['menu_id'], $args['menu_class'], $link );
	//add container
	if ( ! empty ( $args['container'] ) ) {
		$output = "<" . $args['container'] . "class='" . $args['container_class'] . "' id='" . $args['container_id'] . "'>" . $output . "</" . $args['container'] . ">";
	}

	if ( $args['echo'] ) {
		echo $output;
	}

	return $output;
}

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
 * @return string
 *
 */
function ign_logo() {

	if ( has_custom_logo() ) {
		$logo = get_custom_logo();
	} else { //no theme mod found. Get site title instead.
		$logo = '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . get_bloginfo( 'name' ) . '
		</a>';

	}//theme mod

	//if we are in the customizer preview get both image and site title and hide/show based on js
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
 * @param $message
 *
 * @return string
 *
 * Add logo to login page inline by using the message area Above. That way it is inline svg
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
 * Login page logo points to home page instead of WordPress
 */
function ign_login_url() {
	return get_bloginfo( 'url' );
}

add_filter( 'login_headerurl', 'ign_login_url' );


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
function ign_is_page_archive( $post_id, $return_type = '' ) {

	if ( 'page' == get_post_type( $post_id ) ) {
		//get all post types that have an archive page and get the main post type post.

		$ign_post_types   = get_post_types( array( '_builtin' => false, 'has_archive' => true ), 'objects' );
		$ign_post_types[] = get_post_type_object( 'post' ); //add this built in object only.


		if ( ! empty( $ign_post_types ) ) {

			//foreach post type, if that post type has a theme mod associated with it and that id is equal to this page, return true
			foreach ( $ign_post_types as $key => $post_type ) {

				if ( (int) get_theme_mod( 'ign_archive_' . $post_type->name ) == $post_id ) {
					if ( $return_type == 'label' ) {
						return __( 'Archive ', 'cmlaw' ) . $post_type->labels->singular_name;
					}

					if ( $return_type == 'post_type' ) {
						return $post_type->name;
					} else {
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

	$state = ign_is_page_archive( $post->ID, 'label' );

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

add_action( 'admin_bar_menu', 'add_archive_edit_link', 100 );
function add_archive_edit_link( $admin_bar ) {
	$archive_id = ign_get_archive_page();
	if ( ! is_admin() && is_post_type_archive() && $archive_id ) {
		$admin_bar->add_menu( array(
			'id'    => 'archive-link',
			'title' => sprintf( __( 'Edit %s Page', 'ignition' ), get_post_type_object( get_post_type() )->labels->name ),
			'href'  => get_edit_post_link( $archive_id ),
		) );
	}
}






/*--------------------------------------------------------------
# Google Font Loading
--------------------------------------------------------------*/
/**
 * Add pre-connect for Google Fonts. This makes them load faster
 *
 * @param array $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed.
 *
 * @return array $urls           URLs to print for resource hints.
 * @since Ignition 1.0
 *
 */
function ignition_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'ignition-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}

add_filter( 'wp_resource_hints', 'ignition_resource_hints', 10, 2 );

