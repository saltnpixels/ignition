<?php
/**
 * SVG icons related functions and filters
 *
 * @package Ignition
 * @since 1.0
 * Based on Twenty Seventeen
 */


/**
 * View your custom icons easily
 * You now also have access to iconify
 */
function ignition_icon_template() {

	$slug = $_SERVER["REQUEST_URI"];
	if ( $slug == '/icons' ) {
		wp_redirect( get_template_directory_uri() . '/src/icons/demo.html' );
		exit;
	}
}


/**
 * Add SVG definitions to the footer.
 */
function ignition_include_svg_icons() {
	// Define SVG sprite file.
	$svg_icons = get_parent_theme_file_path( '/src/icons/symbol-defs.svg' );

	// If it exists, include it.
	if ( file_exists( $svg_icons ) ) {
		require_once( $svg_icons );
	}
}

//must be turned on via the theme config file
if ( ign_get_config( 'load_custom_icons' ) ) {
	add_action( "template_redirect", 'ignition_icon_template' );
	add_action( 'wp_footer', 'ignition_include_svg_icons', 9999 );
	add_action( 'admin_footer', 'ignition_include_svg_icons', 9999 );
}

/**
 * Return SVG markup.
 *
 * @param array $args {
 *     Parameters needed to display an SVG.
 *
 * @type string $icon Required SVG icon filename.
 * @type string $title Optional SVG title.
 * @type string $desc Optional SVG description.
 * }
 * @return string SVG markup.
 */
function ign_get_svg( $args = array() ) {
	// Make sure $args are an array.
	if ( empty( $args ) ) {
		return __( 'Please define default parameters in the form of an array.', 'ignition' );
	}

	// Define an icon.
	if ( false === array_key_exists( 'icon', $args ) ) {
		return __( 'Please define an SVG icon filename.', 'ignition' );
	}

	// Set defaults.
	$defaults = array(
		'icon'     => '',
		'title'    => '',
		'desc'     => '',
		'fallback' => false,
	);

	// Parse args.
	$args = wp_parse_args( $args, $defaults );

	// Set aria hidden.
	$aria_hidden = ' aria-hidden="true"';

	// Set ARIA.
	$aria_labelledby = '';

	/**
	 * Ignition doesn't use the SVG title or description attributes; non-decorative icons are described with .screen-reader-text.
	 *
	 * However, child themes can use the title and description to add information to non-decorative SVG icons to improve accessibility.
	 * Example 1 with title:  echo ignition_get_svg( array( 'icon' => 'arrow-right', 'title' => __( 'This is the title', 'textdomain' ) ) );
	 * Example 2 with title and description: <?php echo ignition_get_svg( array( 'icon' => 'arrow-right', 'title' => __( 'This is the title', 'textdomain' ), 'desc' => __( 'This is the description', 'textdomain' ) ) ); ?>
	 *
	 * See https://www.paciellogroup.com/blog/2013/12/using-aria-enhance-svg-accessibility/.
	 */
	if ( $args['title'] ) {
		$aria_hidden     = '';
		$unique_id       = uniqid();
		$aria_labelledby = ' aria-labelledby="title-' . $unique_id . '"';

		if ( $args['desc'] ) {
			$aria_labelledby = ' aria-labelledby="title-' . $unique_id . ' desc-' . $unique_id . '"';
		}
	}

	// Begin SVG markup.
	$svg = '<svg class="icon icon-' . esc_attr( $args['icon'] ) . '"' . $aria_hidden . $aria_labelledby . ' role="img">';

	// Display the title.
	if ( $args['title'] ) {
		$svg .= '<title id="title-' . $unique_id . '">' . esc_html( $args['title'] ) . '</title>';

		// Display the desc only if the title is already set.
		if ( $args['desc'] ) {
			$svg .= '<desc id="desc-' . $unique_id . '">' . esc_html( $args['desc'] ) . '</desc>';
		}
	}

	/*
	 * Display the icon.
	 *
	 * The whitespace around `<use>` is intentional - it is a work around to a keyboard navigation bug in Safari 10.
	 *
	 * See https://core.trac.wordpress.org/ticket/38387.
	 */
	$svg .= ' <use href="#icon-' . esc_html( $args['icon'] ) . '" xlink:href="#icon-' . esc_html( $args['icon'] ) . '"></use> ';

	// Add some markup to use as a fallback for browsers that do not support SVGs.
	if ( $args['fallback'] ) {
		$svg .= '<span class="svg-fallback icon-' . esc_attr( $args['icon'] ) . '"></span>';
	}

	$svg .= '</svg>';

	return $svg;
}



