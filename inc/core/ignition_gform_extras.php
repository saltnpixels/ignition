<?php


//turn submit button into a button so more than just text can be inside button
//https://gist.github.com/mannieschumpert/8334811#file-gistfile3-php
// filter the Gravity Forms button type to be a button not an input
add_filter( 'gform_submit_button', 'form_submit_button', 10, 2 );
function form_submit_button( $button, $form ) {
	$button = str_replace( "input", "button", $button );
	$button = str_replace( "/", "", $button );
	$button .= " <span>{$form['button']['text']}</span></button>"; //change the text or icon here

	return $button;
}
