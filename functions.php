<?php

/**
 * Ignition functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * @package Ignition
 * @since 1.0
 */

/**
 * Ignition only works in WordPress 4.7 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.7-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';

	return;
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function ignition_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/ignition
	 * If you're building a theme based on Ignition, use a find and replace
	 * to change 'ignition' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'ignition' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	//Adds Gutenberg Support
	add_theme_support( 'align-wide' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );


	//default image size for cards
	set_post_thumbnail_size( 300, 300, true );
	add_image_size( 'header_image', '2000', '600' );

	//menus
	register_nav_menus( array(
		'top' => __( 'Top Menu', 'ignition' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	/*
			add_theme_support( 'post-formats', array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			) );
	*/

	// Add theme support for Custom Logo.
	add_theme_support( 'custom-logo', array(
		'width'       => 400,
		'height'      => 250,
		'flex-width'  => true,
		'flex-height' => true
	) );

	// Add theme support for selective refresh for widgets in customizer.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, and column width.
	  */
	add_editor_style( array( 'editor-style.min.css', ign_google_fonts_url() ) );

	$GLOBALS['content_width'] = 730;
}

add_action( 'after_setup_theme', 'ignition_setup' );

function ign_gutenberg_styles() {
	// Load the theme styles within Gutenberg.
	wp_enqueue_style( 'ign-gutenberg-style', get_theme_file_uri( '/gutenberg-editor-style.min.css' ), false, '', 'all' );
}

add_action( 'enqueue_block_editor_assets', 'ign_gutenberg_styles' );


/**
 * Register custom google fonts.
 */
if ( ! function_exists( 'ign_google_fonts_url' ) ) {
	function ign_google_fonts_url() {
		$fonts_url     = '';
		$font_families = array();

		//add your fonts here into array
		//when adding from google remove the + between words
		//dont forget to add your fonts in sass under variables.scss
		$font_families[] = 'Roboto:400,400i,700,700i';
		$font_families[] = 'Roboto Slab:400,700';
		$font_families[] = 'Source Code Pro';

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

		return esc_url_raw( $fonts_url );
	}
}


/**
 * Add pre-connect for Google Fonts. This makes them load faster
 *
 * @since Ignition 1.0
 *
 * @param array $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed.
 *
 * @return array $urls           URLs to print for resource hints.
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
 * Register widget areas.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */

if( ! function_exists('ign_widgets_init') ) {
	function ign_widgets_init() {
		register_sidebar( array(
			'name'          => __( 'Sidebar', 'ignition' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Add widgets here to appear in your sidebar.', 'ignition' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );


//footer widgets and sections. up to 4
		register_sidebar( array(
			'name'          => esc_html__( 'Footer', 'ignition' ),
			'id'            => 'sidebar-2',
			'description'   => esc_html__( 'Add footer widgets here.', 'pwm' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );


		register_sidebar( array(
			'name'          => esc_html__( 'Footer 2', 'ignition' ),
			'id'            => 'sidebar-3',
			'description'   => esc_html__( 'Add footer widgets here.', 'pwm' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );


		register_sidebar( array(
			'name'          => esc_html__( 'Footer 3', 'ignition' ),
			'id'            => 'sidebar-4',
			'description'   => esc_html__( 'Add footer widgets here.', 'pwm' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Footer 4', 'ignition' ),
			'id'            => 'sidebar-5',
			'description'   => esc_html__( 'Add footer widgets here.', 'pwm' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );

	}
}
add_action( 'widgets_init', 'ign_widgets_init' );

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and
 * a 'Continue reading' link.
 *
 * @since Ignition 1.0
 *
 * @return string 'Continue reading' link prepended with an ellipsis.
 */
function ignition_excerpt_more( $link ) {
	if ( is_admin() ) {
		return $link;
	}

	$link = sprintf( '<p class="link-more"><a href="%1$s" class="more-link">%2$s</a></p>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: Name of current post */
		sprintf( __( 'Read More<span class="screen-reader-text"> "%s"</span>', 'ignition' ), get_the_title( get_the_ID()
		) )
	);

	return ' &hellip; ' . $link;
}

add_filter( 'excerpt_more', 'ignition_excerpt_more' );


/**
 * Handles JavaScript detection.
 * Adds a script that adds `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Ignition 1.0
 */
function ignition_javascript_detection() {
	echo "<script type='text/javascript'>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}

add_action( 'wp_head', 'ignition_javascript_detection', 0 );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function ignition_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url' ) );
	}
}

add_action( 'wp_head', 'ignition_pingback_header' );


/**
 * Enqueue scripts and styles.
 */
function ignition_scripts() {
	// Add google fonts
	wp_enqueue_style( 'ignition-fonts', ign_google_fonts_url(), array(), null );

	// Theme stylesheet.
	wp_enqueue_style( 'ignition-style', get_stylesheet_uri(), '', '1.0' );

	//Sass compiles styles
	wp_enqueue_style( 'sass-styles', get_theme_file_uri( '/main.css' ), '', '1.0' );

	//jQuery 3.0 replaces WP jquery
	wp_deregister_script( 'jquery-core' );
	wp_register_script( 'jquery-core', "https://code.jquery.com/jquery-3.3.1.min.js", array(), '3.3.1' );
	wp_deregister_script( 'jquery-migrate' );
	wp_register_script( 'jquery-migrate', "https://code.jquery.com/jquery-migrate-3.0.0.min.js", array(), '3.0.0' );


	//any javascript file in assets/js that ends with custom.js will be lumped into this file.
	wp_enqueue_script( 'ignition-custom', get_theme_file_uri( '/assets/js/custom.js' ), array( 'jquery' ),
		'1.0', true );

	//AJAX ready for .custom.js files
	wp_localize_script( 'ignition-custom', 'frontEndAjax', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'ajax_nonce' ),
	) );

	wp_localize_script( 'ignition-custom', 'screenReaderText', array(
		'quote'    => ign_get_svg( array( 'icon' => 'quote-right' ) ),
		'expand'   => __( 'Expand child menu', 'ignition' ),
		'collapse' => __( 'Collapse child menu', 'ignition' )
	) );

	//Icons: add icons for use in custom js here
	wp_localize_script( 'ignition-custom', 'icons', array(
		'angleRight' => ign_get_svg( array( 'icon' => 'angle-right' ) ),
		'sidebar'    => ign_get_svg( array( 'icon' => 'sidebar' ) )
	) );

	//scrolling animation
	wp_enqueue_script( 'gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/2.0.0/TweenMax.min.js' );
	wp_enqueue_script( 'scrollMagic', 'https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/ScrollMagic.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'scrollMagic-gsap', 'https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/plugins/animation.gsap.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'scrollmagic-indicators', 'https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/plugins/debug.addIndicators.min.js' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	//add your styles and scripts here

}

add_action( 'wp_enqueue_scripts', 'ignition_scripts' );


/**
 * Custom template tags and functions for this theme.
 */
require get_parent_theme_file_path( '/inc/template-tags.php' );

/**
 * Additional functions and features to allow styling of the templates by adding classes to body.
 */
require get_parent_theme_file_path( '/inc/template-classes.php' );

/**
 * WP Customizer additions.
 */
require get_parent_theme_file_path( '/inc/customizer.php' );

/**
 * SVG icons functions and filters.
 */
require get_parent_theme_file_path( '/inc/icon-functions.php' );

/**
 * ignition abilities and extras
 */
require get_parent_theme_file_path( '/inc/extras.php' );

/**
 * Add ACF Field Extras
 */
require get_parent_theme_file_path( '/inc/acf_extras.php' );

/*
 * Require any functions.php for each post type
 */
function ign_post_type_functions() {
	$ign_post_types   = get_post_types( array( '_builtin' => false ) );
	$ign_post_types[] = 'post';
	$ign_post_types[] = 'page';


	//var_dump( $ign_post_types );
	foreach ( $ign_post_types as $type ) {
		$type = sanitize_key( $type );
		if ( file_exists( locate_template( 'template-parts/' . $type . '/functions.php' ) ) ) {
			include( locate_template( 'template-parts/' . $type . '/functions.php' ) );
		}
	}
}

add_action( 'init', 'ign_post_type_functions', 15 );