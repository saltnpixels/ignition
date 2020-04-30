<?php

/**
 * Ignition functions and definitions
 *
 * @link    https://developer.wordpress.org/themes/basics/theme-functions/
 * @package Ignition
 * @since   1.0
 *
 * This is the first file you should edit when starting a new theme.
 * Then you should set your sass variables in variables.scss
 * Then you should edit some global sass files.  _page.scss, _typograhpy.scss
 *
 * Here you can edit the google fonts, the images sizes and other setup options for your theme.
 * All css and js files should be added here using WP Enqueue functions, not in the header.
 * You can also separate and include another php file by simply creating one in the inc folder of the theme.
 * all php files in the inc folder are automatically included unless they star with an underscore
 */

/**
 * Ignition only works in WordPress 4.7 or later. Here we check before allowing the theme to be used.
 * There is nothing here for you to do.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.7-alpha', '<' ) ) {
	require get_template_directory() . '/inc/core/back-compat.php';

	return;
}


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


	/**
	 * default image size for cards and thumbnails and header images
	 * Users should upload twice the size of an image size.
	 * So if the image size is 600 x 600, the user should upload a 1200 by 1200.
	 * Output the image size and WP will automatically include the full size for big display when needed.
	 *
	 * WP is also smart and if you set crop to true it will include the original only if it matches in ratio
	 * Header image size is included for large header images. Users dont have to upload twice that size unless your ok with large files.
	 *
	 * Recommend installing smush or imsanity so users can't upload extremely huge images without them being compressed and resized.
	 */
	set_post_thumbnail_size( 300, 300, true );
	add_image_size( 'header_image', 2000, 9999 );


	// Remove default image sizes here. medium-large is probably not needed.
	//in the admin you can set media sizes to 0 to remove them.
	function remove_default_images( $sizes ) {
		unset( $sizes['medium_large'] ); // 768px

		return $sizes;
	}

	add_filter( 'intermediate_image_sizes_advanced', 'remove_default_images' );


	/*
	 * Add menus here
	 */
	register_nav_menus( array(
		'top-menu' => __( 'Top Menu', 'ignition' ),
	) );

	/*
    * Enable support for Post Formats.
    * Uncomment if you want to use this feauture
    * See: https://codex.wordpress.org/Post_Formats
    */


//	add_theme_support( 'post-formats', array(
//		'aside',
//		'image',
//		'video',
//		'quote',
//		'link',
//		'gallery',
//		'audio',
//	) );
//	add_post_type_support( 'post', 'post-formats' );


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
	add_theme_support( 'custom-header' );

	/*
	 * tinymce styles
	  */
	add_editor_style( array(
		get_template_directory_uri() . '/dist/editor-style.min.css?' . wp_get_theme()->get( 'Version' ),
		ign_google_fonts_url()
	) );


	$GLOBALS['content_width'] = 730;
}

add_action( 'after_setup_theme', 'ignition_setup' );



/*--------------------------------------------------------------
# ADMIN ACCESS AND ADMIN BAR VISIBILITY
--------------------------------------------------------------*/

/**
 * Disable admin bar for everyone but administrators
 * You can change this based on capabilities. By default manage_options is used to check for Administrators
 */
define( 'IGN_WP_ADMIN_ACCESS_CAPABILITY', 'manage_options' );

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

	//load regular versions if script debug is set to true in wp-config file.
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	// Add google fonts
	wp_enqueue_style( 'ignition-fonts', ign_google_fonts_url(), array(), wp_get_theme()->get( 'Version' ) );

	// Theme stylesheet. Will get this stylesheet or a child themes stylesheet.
	wp_enqueue_style( 'ignition-style', get_stylesheet_uri(), '', wp_get_theme()->get( 'Version' ) );

	//Sass compiles styles. Will get child's theme version if found instead. Child theme should import with sass.
	wp_enqueue_style( 'ignition-sass-styles', get_theme_file_uri( '/dist/main' . $suffix . '.css' ), '', wp_get_theme()->get( 'Version' ) );

	//ie11 js polyfills
	wp_enqueue_script( 'polyfill', 'https://polyfill.io/v3/polyfill.min.js?flags=gated&features=AbortController%2Cdefault%2CNodeList.prototype.forEach%2CEvent%2Csmoothscroll' );

	//jQuery 3.0 replaces WP jquery
	wp_deregister_script( 'jquery-core' );
	wp_register_script( 'jquery-core', "https://code.jquery.com/jquery-3.3.1.min.js", array(), '3.3.1' );
	wp_deregister_script( 'jquery-migrate' );
	wp_register_script( 'jquery-migrate', "https://code.jquery.com/jquery-migrate-3.0.0.min.js", array(), '3.0.0' );


	//any javascript file in assets/js that ends with custom.js will be lumped into this file.
	wp_enqueue_script( 'ignition-custom-js', get_template_directory_uri() . '/dist/custom' . $suffix . '.js', array( 'jquery' ),
		wp_get_theme()->get( 'Version' ), true );

	//AJAX ready for .custom.js files
	wp_localize_script( 'ignition-custom-js', 'frontEndAjax', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'ajax_nonce' ),
		'url'     => home_url(),
	) );


	//Icons: add icons for use in custom js here
	wp_localize_script( 'ignition-custom-js', 'icons', array(
		'angleRight' => ign_get_svg( array( 'icon' => 'angle-right' ) ),
		'sidebar'    => ign_get_svg( array( 'icon' => 'sidebar' ) )
	) );


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
	//load regular versions if script debug is set to true in wp-config file.
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';


	// Load the theme styles within Gutenberg.
	wp_enqueue_style( 'ign-gutenberg-style', get_theme_file_uri( '/dist/gutenberg-editor-style' . $suffix . '.css' ), false, wp_get_theme()->get( 'Version' ), 'all' );

	wp_enqueue_script( 'ignition-custom-js', get_template_directory_uri() . '/dist/custom' . $suffix . '.js', array( 'jquery' ),
		wp_get_theme()->get( 'Version' ), true );
}

add_action( 'enqueue_block_editor_assets', 'ign_gutenberg_styles' );


/**
 * Add login stylehseet
 */
function my_login_styles() {
	//load regular versions if script debug is set to true in wp-config file.
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/dist/login-style' . $suffix . '.css' );
}

add_action( 'login_enqueue_scripts', 'my_login_styles' );


/**
 * Add admin stylesheet
 */
function footer_styles() {
	//load regular versions if script debug is set to true in wp-config file.
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_style( 'ignition-admin-styles', get_theme_file_uri( '/dist/admin' . $suffix . '.css' ), '', wp_get_theme()->get( 'Version' ) );
}

add_action( 'admin_footer', 'footer_styles', 99 );


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




/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function ignition_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url' ) );
	}
}

add_action( 'wp_head', 'ignition_pingback_header' );


/*--------------------------------------------------------------
# Adding More PHP Files Automatically
--------------------------------------------------------------*/

require_once get_parent_theme_file_path( '/inc/core/dev-helpers.php' );

//no need to include php files, just add them to the inc folder and start them with an underscore. Ignition takes care of the rest!
//Ignition will also search two directories deep for more underscored files within inc and template-parts folders.
// (ie: inc/acf-extras/_acf-extras.php )

