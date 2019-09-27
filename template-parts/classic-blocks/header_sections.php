<?php
/*
 * Different layouts for the header area can be used
 *
 */


$post_id = get_the_ID();

if ( function_exists('have_rows') && have_rows( 'header_layout', $post_id ) && get_field( 'override_header' ) ):
	while ( have_rows( 'header_layout', $post_id ) ): the_row();
		$header = get_row_layout();
		if ( file_exists( locate_template( 'template-parts/classic-blocks/' . $header . '.php' ) ) ) {
			include( locate_template( 'template-parts/classic-blocks/' . $header . '.php' ) );
		}
	endwhile;
else:

	locate_template( 'template-parts/site-top/default-header.php', true );

endif;

