<?php
/**
 * Sections that can be used on the site using ACF
 */


$id = get_the_ID();
$section_hash = 0;

if( have_rows('sections', $id) ):
    while( have_rows('sections', $id) ): the_row();
    $section = get_row_layout();
	$section_hash++;

		if( file_exists( locate_template( 'template-parts/blocks/' . $section . '.php') )) {
			include( locate_template( 'template-parts/blocks/' . $section . '.php' ) );
		}

    endwhile;
endif;