<?php
/*
 * Different layouts for the header area can be used
 *
 */


$id = get_the_ID();

if ( function_exists('have_rows') && have_rows( 'header_layout', $id ) && get_field( 'override_header' ) ):
	while ( have_rows( 'header_layout', $id ) ): the_row();
		$header = get_row_layout();
		if ( file_exists( locate_template( 'template-parts/classic-blocks/' . $header . '.php' ) ) ) {
			include( locate_template( 'template-parts/classic-blocks/' . $header . '.php' ) );
		}
	endwhile;
else:


//DEFAULT HEADER IF NO CUSTOM HEADER CHOSEN

	$bg_image = ign_get_header_image( get_the_ID() );
	?>
    <header class="entry-header layout-center-content overlay"
	        <?php if ( $bg_image ) { ?>style="background-image: url('<?php echo $bg_image; ?>');" <?php } ?>>

        <div class="header-content container-fluid text-center">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
        </div>

    </header>
<?php
endif;

