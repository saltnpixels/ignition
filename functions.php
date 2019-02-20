<?php

/**
 * Ignition functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * @package Ignition
 * @since 1.0
 *
 * This is the first file you should edit when starting a new theme.
 * Here you can edit the google fonts, the images sizes and other setup options for your theme.
 */

/**
 * Ignition only works in WordPress 4.7 or later. Here we check before allowing the theme to be used.
 * There is nothing here for you to do.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.7-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';

	return;
}


/*--------------------------------------------------------------
# Setup
--------------------------------------------------------------*/
/**
 * This sets up theme defaults and registers support for various WordPress features.
 * Runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 * Here is where you can start changing settings.
 */
function ignition_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/ignition
	 * If you're building a theme based on Ignition, and you downloaded this from github, use a find and replace
	 * to change 'ignition' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'ignition' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
    */
	add_theme_support( 'post-thumbnails' );


	/*
	 * default image size for cards and thumbnails and header images
	 */
	set_post_thumbnail_size( 300, 300, true );
	add_image_size( 'header_image', '2000', '600' );


	/*
	 * Add menus here
	 */
	register_nav_menus( array(
		'top' => __( 'Top Menu', 'ignition' ),
	) );

	/*
    * Enable support for Post Formats.
    * Uncomment if you want to use this feauture
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
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );


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


/**
 * Register custom google fonts. These are loaded along with scripts and styles.
 * Change/Remove the fonts in the $font_families array below to load different ones
 * Open the base/variables.scss to set the font, font-alt, and font-pre variables to these fonts.
 */
if ( ! function_exists( 'ign_google_fonts_url' ) ) {
	function ign_google_fonts_url() {
		$fonts_url     = '';
		$font_families = array();

		//add your fonts here into the array below
		//when adding from google remove the + between words Ex: 'Source+Code' becomes 'Source Code'
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
 * Register widget areas.
 * Change/Remove widget areas here. By default the widget areas are the sidebar and the footer which has 4 widget areas being output in columns.
 * See template-parts/footer/footer-widgets.php
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */

if ( ! function_exists( 'ign_widgets_init' ) ) {
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


/*--------------------------------------------------------------
# ADMIN ACCESS AND ADMIN BAR VISIBILITY
--------------------------------------------------------------*/

/**
 * Disable admin bar for everyone but administrators
 * You can change this based on capabilities. By default manage_options is used to check for Administrators
 */
define('IGN_WP_ADMIN_ACCESS_CAPABILITY', 'manage_options');

if ( ! function_exists( 'disable_admin_bar' ) ) {

	function disable_admin_bar() {
		if ( ! current_user_can( IGN_WP_ADMIN_ACCESS_CAPABILITY ) ) {
			add_filter( 'show_admin_bar', '__return_false' );
		}
	}
}
add_action( 'after_setup_theme', 'disable_admin_bar' );


/**
 * Redirect back to homepage and not allow access to WP Admin. Except admins and ajax
 */
if ( ! function_exists( 'redirect_admin' ) ) {

	function redirect_admin() {
		if ( ! current_user_can( IGN_WP_ADMIN_ACCESS_CAPABILITY ) && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
			wp_redirect( home_url() );
			exit;
		}
	}
}

add_action( 'admin_init', 'redirect_admin' );


/*--------------------------------------------------------------
# Scripts and Styles
--------------------------------------------------------------*/
/**
 * Enqueue all scripts and styles.
 * Add your own scripts and styles below.
 */
function ignition_scripts() {
	// Add google fonts
	wp_enqueue_style( 'ignition-fonts', ign_google_fonts_url(), array(), wp_get_theme()->get( 'Version' ) );

	// Theme stylesheet. Will get this stylesheet or a child themes stylesheet.
	wp_enqueue_style( 'ignition-style', get_stylesheet_uri(), '', wp_get_theme()->get( 'Version' ) );

	//Sass compiles styles. Will get child's theme version if found instead. Child theme should import with sass.
	wp_enqueue_style( 'ignition-sass-styles', get_theme_file_uri( '/main.css' ), '', wp_get_theme()->get( 'Version' ) );

	//jQuery 3.0 replaces WP jquery
	wp_deregister_script( 'jquery-core' );
	wp_register_script( 'jquery-core', "https://code.jquery.com/jquery-3.3.1.min.js", array(), '3.3.1' );
	wp_deregister_script( 'jquery-migrate' );
	wp_register_script( 'jquery-migrate', "https://code.jquery.com/jquery-migrate-3.0.0.min.js", array(), '3.0.0' );


	//any javascript file in assets/js that ends with custom.js will be lumped into this file.
	wp_enqueue_script( 'ignition-custom-js', get_template_directory_uri() . '/assets/js/custom.js', array( 'jquery' ),
		'1.0', true );

	//AJAX ready for .custom.js files
	wp_localize_script( 'ignition-custom-js', 'frontEndAjax', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'ajax_nonce' ),
		'url' => home_url(),
	) );

	wp_localize_script( 'ignition-custom-js', 'screenReaderText', array(
		'quote'    => ign_get_svg( array( 'icon' => 'quote-right' ) ),
		'expand'   => __( 'Expand child menu', 'ignition' ),
		'collapse' => __( 'Collapse child menu', 'ignition' )
	) );

	//Icons: add icons for use in custom js here
	wp_localize_script( 'ignition-custom-js', 'icons', array(
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


/*
 * Add Stylesheet for Gutenberg
 */
function ign_gutenberg_styles() {
	// Load the theme styles within Gutenberg.
	wp_enqueue_style( 'ign-gutenberg-style', get_theme_file_uri( '/gutenberg-editor-style.min.css' ), false, '', 'all' );
}

add_action( 'enqueue_block_editor_assets', 'ign_gutenberg_styles' );


/**
 * Add login stylehseet
 */
function my_login_styles() {
	wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/login-style.css' );
}

add_action( 'login_enqueue_scripts', 'my_login_styles' );



/*--------------------------------------------------------------
# Pre Post Queries
--------------------------------------------------------------*/
/**
 * @param $query
 * Here you can change the default query for any WordPress page/post or archive
 */
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





/*--------------------------------------------------------------
# Adding More PHP Files
--------------------------------------------------------------*/
/**
 * ignition abilities and extra filter and functions
 * you can edit the comment walker at the bottom of this file.
 */
require get_parent_theme_file_path( '/inc/extras.php' );


/**
 * Custom template tags and functions for this theme.
 * Useful in the content for showing post author and time and edit links.
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
 * Add ACF Field Extras
 */
require get_parent_theme_file_path( '/inc/acf_extras/acf_extras.php' );

