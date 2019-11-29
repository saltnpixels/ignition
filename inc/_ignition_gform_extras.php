<?php


//add script to make credit card field nicer with 01 / 15 type expiration field
add_action( 'gform_enqueue_scripts', 'add_gform_script', 10, 2 );
function add_gform_script( $form, $is_ajax ) {
	wp_enqueue_script( 'gform_extras', get_theme_file_uri( '/assets/js/min/gform_extras.min.js' ), array( 'jquery'
	), '1.0' );
}

//turn submit button into a button so more than just text can be inside button
//https://gist.github.com/mannieschumpert/8334811#file-gistfile3-php
// filter the Gravity Forms button type to be a button not an input
add_filter( 'gform_submit_button', 'form_submit_button', 10, 2 );
function form_submit_button( $button, $form ) {
	$button = str_replace( "input", "button", $button );
	$button = str_replace( "/", "", $button );
	$button .= " <span>{$form['button']['text']}" . ign_get_svg( array( 'icon' => 'arrow-circle-o-right') ) . "</span></button>"; //change the text or icon here

	return $button;
}