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


/*--------------------------------------------------------------
# Adding more php files
--------------------------------------------------------------*/
/*
* Require any functions.php for each post type.
* You can have a functions.php in each post type folder in template-parts for easy plug and play.
*/
function ign_post_type_functions() {
	$ign_post_types   = get_post_types( array( '_builtin' => false ) );
	$ign_post_types[] = 'post';
	$ign_post_types[] = 'page';


	foreach ( $ign_post_types as $type ) {
		$type = sanitize_key( $type );
		if ( file_exists( locate_template( 'template-parts/' . $type . '/functions.php' ) ) ) {
			include( locate_template( 'template-parts/' . $type . '/functions.php' ) );
		}
	}
}

add_action( 'init', 'ign_post_type_functions', 15 );

/**
 * @param $dir
 * Gets all php files from the directory and sub directories and includes them
 * to leave a file alone have it start with an '_'
 */
function require_all_inc( $dir ){
	foreach (array_diff(scandir($dir), array('.', '..')) as $filename) {
		if (is_file($dir . '/' . $filename)) {
			if(strpos( $filename, '.php') !== false && $filename !== 'dev-helpers.php' && substr($filename, 0, 1) !== '_'){
				require_once ($dir . '/' . $filename);
			}

		}else{
			require_all_inc($dir . '/' . $filename);
		}

	}
}
require_all_inc(get_parent_theme_file_path('/inc'));


/**
 * admin_bar_color_dev_site function.
 * So you can tell if your working on dev site or staging site by checking if admin bar is blue or not. Blue means staging
 * @access public
 * @return void
 */

function admin_bar_color_dev_site() {
	if ( in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', "::1")) ) {
		echo '<style>
	body #wpadminbar{ background: #156288; }
</style>';
	}
}

add_action( 'wp_head', 'admin_bar_color_dev_site' );
add_action( 'admin_head', 'admin_bar_color_dev_site' );
