<?php

/*--------------------------------------------------------------
# Development Work and Helpers
--------------------------------------------------------------*/
/**
 * Add to the wp log for development and debugging. You can also send the log to the web console.
 * To use, you must have the following defined in your wp_config file.
 * define('WP_DEBUG', true);
 * define('WP_DEBUG_LOG', true);
 */
if ( ! function_exists( 'write_log' ) ) {
	function write_log( $log, $send_to_console = false ) {
		if ( true === WP_DEBUG ) {
			if ( is_array( $log ) || is_object( $log ) ) {
				error_log( print_r( $log, true ) );
			} else {
				error_log( $log );
			}

			if ( $send_to_console ) {
				debug_to_console( $log );
			}
		}
	}
}

/**
 * Send debug code to the Javascript console.
 * Will only work if the page loads fully without an error that stops wp_footer from outputting.
 */
$console_log = '';
if ( ! function_exists( 'debug_to_console' ) ) {
	function debug_to_console( $data ) {
		if ( is_array( $data ) || is_object( $data ) ) {
			$log = "<script>console.table(" . json_encode( $data ) . ");</script>";
		} else {
			$log = "<script>console.log('PHP: $data');</script>";
		}
		global $console_log;
		$console_log .= $log;
	}
}

/*
* Output the error in the footer and send to console
*/
function output_log_to_footer() {
	global $console_log;
	echo $console_log;
}

add_action( 'wp_footer', 'output_log_to_footer' );
add_action( 'admin_footer', 'output_log_to_footer' );


/*--------------------------------------------------------------
# Adding more php files
--------------------------------------------------------------*/

/**
 * @param $dir
 * Gets all php files from the directory and sub directory that start with an underscore
 * @param int $depth
 */
function ign_require_all( $dir, $depth = 2 ) {
	if ( file_exists( $dir ) ) {
		foreach ( array_diff( scandir( $dir ), array( '.', '..' ) ) as $filename ) {
			//check if its a file
			if ( is_file( $dir . '/' . $filename ) ) {
				//only include automatically if it starts with an underscore
				if ( substr( $filename, 0, 1 ) === '_' && strpos( $filename, '.php' ) !== false ) {
					require_once( $dir . '/' . $filename );
				}

			} else {
				//if its not a file its a directory. Look through it for more underscore php partial files
				if ( $depth > 0 ) {
					ign_require_all( $dir . '/' . $filename, $depth - 1 );
				}
			}
		}
	}
}

//always include the parent themes inc files
ign_require_all( get_parent_theme_file_path( '/inc' ) );

//get theme template part partials from current theme only (if child being used get childs)
//child theme will have to include parents if they want them. Will be included in child theme by default.
ign_require_all( get_stylesheet_directory() . '/template-parts' );


/**
 * Handles JavaScript detection.
 * Adds a script that adds `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Ignition 1.0
 */
function ignition_javascript_detection() {
	echo "<script type='text/javascript'>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
	echo "<script type='text/javascript'>var  isIE11 = !!window.MSInputMethodContext && !!document.documentMode;</script>";
}

add_action( 'wp_head', 'ignition_javascript_detection', 0 );
add_action( 'admin_head', 'ignition_javascript_detection', 0 );


/**
 * admin_bar_color_dev_site function.
 * So you can tell if your working on dev site or staging site by checking if admin bar is blue or not. Blue means staging
 * @access public
 * @return void
 */

function admin_bar_color_dev_site() {
	if ( in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', "::1" ) ) ) {
		echo '<style>
	body #wpadminbar{ background: #156288; }
</style>';
	}
}

add_action( 'wp_head', 'admin_bar_color_dev_site' );
add_action( 'admin_head', 'admin_bar_color_dev_site' );


