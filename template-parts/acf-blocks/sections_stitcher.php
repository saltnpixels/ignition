<section id="section-<?php echo $section_hash; ?>"
         class="alignfull <?php echo esc_attr( get_sub_field( 'class' ) ); ?> <?php echo get_row_layout(); ?>">
	<?php
	//connect sections together from different posts and pages!
	$ids = get_sub_field( 'post_items' );

	foreach ( $ids as $connector_id ) {
		//cannot output same page! will result in endless loop
		if ( $connector_id != $id && $connector_id != 0 ) {

			echo '<div class="connected-page" id="stitched-' . $connector_id . '">';
			ign_edit_link( $connector_id ); //easy way to get to that pages edit screen

			if ( have_rows( 'sections', $connector_id ) ):

				while ( have_rows( 'sections', $connector_id ) ): the_row();
					$section = get_row_layout();
					$section_hash ++;
					$id = $connector_id; //need this id otherwise id will get the page id not the connector id
					if ( file_exists( locate_template( 'template-parts/acf-blocks/' . $section . '.php' ) ) ) {
						include( locate_template( 'template-parts/acf-blocks/' . $section . '.php' ) );
					}

				endwhile;
			endif;
			echo '</div>';
		}
	}

	?>
</section>

