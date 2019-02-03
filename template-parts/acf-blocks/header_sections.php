<?php
/*
 * Different layouts for the header area can be used
 * Add your layouts styles to grid.scss
 * Some image classes can be found in media.scss
 *
 */


$id = get_the_ID();
// check if the flexible content field has rows of data without acf functions
if ( function_exists('have_rows') && have_rows( 'header_layout', $id ) && get_field( 'override_header' ) ):
	while ( have_rows( 'header_layout', $id ) ): the_row();
		$header = get_row_layout();
		if ( file_exists( locate_template( 'template-parts/acf-blocks/' . $header . '.php' ) ) ) {
			include( locate_template( 'template-parts/acf-blocks/' . $header . '.php' ) );
		}
	endwhile;
else:

	// default header as a background.
	// you can add a bg color on top by adding a gradient into background image in the style below
	$bg_image = ign_get_header_image( get_the_ID() );
	?>
    <header class="entry-header layout-center-content"
	        <?php if ( $bg_image ) { ?>style="background-image: url('<?php echo $bg_image; ?>');" <?php } ?>>

        <div class="header-content container-fluid text-center">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
        </div>

    </header>
<?php
endif;

