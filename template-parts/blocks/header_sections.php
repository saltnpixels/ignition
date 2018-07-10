<?php
/*
 * Different layouts for the header area can be used
 * Add your layouts styles to grid.scss
 * Some image classes can be found in media.scss
 *
 */


$id = get_the_ID();
// check if the flexible content field has rows of data without acf functions
if ( have_rows('header_layout', $id) ):
    while( have_rows('header_layout', $id) ): the_row();
    $header = get_row_layout();
        if ( file_exists( locate_template( 'template-parts/blocks/' . $header . '.php' ) ) ) {
           include( locate_template( 'template-parts/blocks/' . $header . '.php' ));
        }
    endwhile;

else :
//default header if no acf found or anything.
    ?>
     <header class="entry-header layout-center-content"
            style="background-image: url('<?php echo ign_get_the_image_url( get_the_ID(), 'full', $image ); ?>');">

        <div class="header-content container-fluid text-center">
            <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
        </div>
    </header>

<?php

endif;

