<?php
/**
 * Sections that can be used on the site using ACF
 */


$post_id           = get_the_ID();
$section_hash = 0; //so each section has a unique ID

if ( function_exists( 'have_rows' ) && have_rows( 'sections', $post_id ) ):
	while ( have_rows( 'sections', $post_id ) ): the_row();
		$section = get_row_layout();
		$section_hash ++;

		if ( file_exists( locate_template( 'template-parts/classic-blocks/' . $section . '.php' ) ) ) {
			include( locate_template( 'template-parts/classic-blocks/' . $section . '.php' ) );
		}

	endwhile;

else:
	the_content();
endif;
