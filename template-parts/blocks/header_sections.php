<?php
/*
 * Different layouts for the header area can be used
 * Add your layouts styles to grid.scss
 * Some image classes can be found in media.scss
 *
 */


$id = get_the_ID();
// check if the flexible content field has rows of data without acf functions
if ( have_rows( 'header_layout', $id ) ):
	while ( have_rows( 'header_layout', $id ) ): the_row();
		$header = get_row_layout();
		if ( file_exists( locate_template( 'template-parts/blocks/' . $header . '.php' ) ) ) {
			include( locate_template( 'template-parts/blocks/' . $header . '.php' ) );
		}
	endwhile;

else :
//default header if no acf found or anything.

//if(singular, check for image)
	if ( is_singular() ) {
		$image    = get_field( 'header_image' );
		$bg_image = ign_get_the_image_url( get_the_ID(), 'header_image', $image );
	} ?>


	<header class="entry-header layout-center-content"
	        <?php if ( $bg_image ) { ?>style="background-image: url('<?php echo $bg_image; ?>');" <?php } ?>>

		<div class="header-content container-fluid text-center">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</div>
	</header>

<?php

endif;

