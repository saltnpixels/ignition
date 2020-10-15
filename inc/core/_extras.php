<?php

/**
 * This file holds actions and filters for advanced functions, you probably dont need to change or touch.
 */



/*--------------------------------------------------------------
# Google Fonts
--------------------------------------------------------------*/
/**
 * Register custom google fonts. These are loaded along with scripts and styles.
 * Change/Remove the fonts in the $font_families array below to load different ones
 * Open the core/variables.scss to set the font, font-alt, and font-pre variables to these fonts.
 */
if ( ! function_exists( 'ign_google_fonts_url' ) ) {
	function ign_google_fonts_url() {

		if(! ign_get_config('google_fonts')){
			return false;
		}

		//ADD YOUR FONTS HERE
		//when adding from google remove the + between words Ex: 'Source+Code' becomes 'Source Code'
		//dont forget to add your fonts in sass under variables.scss
		$query_args = array(
			'family' => urlencode( implode( '|', ign_get_config('google_fonts', array("Roboto:400,400i,700,700i", "Roboto Slab:400,700" )) ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

		return esc_url_raw( $fonts_url );
	}
}


/*--------------------------------------------------------------
# Menu Work
--------------------------------------------------------------*/

/**
 * @param $classes
 * @param $item
 * @param $depth
 *https://developer.wordpress.org/reference/classes/walker_nav_menu/
 * Add top-level-item to top level menu items for easier styling.
 *
 * @return array
 */
function ign_nav_menu_css_class( $classes, $item, $args, $depth ) {

	if ( $item->menu_item_parent == 0 ) { //Count top level menu items
		$classes[] = 'top-level-item';
	}

	if ( $depth >= 2 ) { //Count top level menu items
		$classes[] = 'nested-menu-item';
	}

	return $classes;
}

add_filter( 'nav_menu_css_class', 'ign_nav_menu_css_class', 10, 4 );


/**
 * @param $item
 * @param $args
 *add buttons for dropdowns when there is a sub-menu.
 * surround anchor ( and buttons if there is one)
 *
 * @return string
 */
function ign_menu( $item, $args ) {

	$classes = $args->classes;
	$arrow   = '<div class="icon iconify icon-angle-right">' . ign_get_config('submenu_arrow_icon', '<svg xmlns=\'http://www.w3.org/2000/svg\' xmlns:xlink=\'http://www.w3.org/1999/xlink\' aria-hidden=\'true\' focusable=\'false\' width=\'1em\' height=\'1em\' style=\'-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);\' preserveAspectRatio=\'xMidYMid meet\' viewBox=\'0 0 32 32\'><path d=\'M22 16L12 26l-1.4-1.4l8.6-8.6l-8.6-8.6L12 6z\' fill=\'#626262\'/><rect x=\'0\' y=\'0\' width=\'32\' height=\'32\' fill=\'rgba(0, 0, 0, 0)\' /></svg>') . '</div>';

	if ( in_array( 'menu-item-has-children', (array) $classes ) ) {
		$item .= '<button tabindex="-1" class="submenu-dropdown-toggle">' . $arrow . '
                    <span class="screen-reader-text">' . __( 'Expand child menu', 'ignition' ) . '</span></button>';
	}

	return '<div class="menu-item-link">' . $item . '</div>';
}

add_filter( 'walker_nav_menu_start_el', 'ign_menu', 10, 99 );


//add search bar to a menu
function add_search_form( $items, $args ) {
	if ( $args->theme_location == 'top-menu' ) {
		$items .= '<li class="menu-item top-level-item"><div class="menu-item-link">' . get_search_form( false ) . '</div></li>';
	}

	return $items;
}

if ( ign_get_config('search_menu_item', false) ) {
	add_filter( 'wp_nav_menu_items', 'add_search_form', 10, 2 );
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
		//add an aria-label if none found

		if( ! preg_match('/aria-label=\"([^"]*)\"/', $html)){
			$html = str_replace('<svg ', '<svg aria-label="logo" ', $html);
		}
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


/**
 * @param $block_content
 * @param $block
 *
 * @return string
 * Some blocks are too naked to work nicely like ul which needs its own margins and when inside container-content they dont play nice.
 */
function surround_block( $block_content, $block ) {
	if ( empty( trim( $block_content ) ) ) {
		return $block_content;
	}

	if ( $block['blockName'] == 'core/list' ) {

		return sprintf(
			'<div class="block-%1$s">%2$s</div>',
			sanitize_title( $block['blockName'] ),
			$block_content
		);
	}

	return $block_content;

}

add_filter( 'render_block', 'surround_block', 10, 2 );
