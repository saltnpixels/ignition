<?php

/**
 * @package Ignition
 * @since   4.0
 *
 * Shows a single post or page
 * ign_header_block only outputs a header block if the_content does not have a header block.
 * a block is considered a header block if its name starts with header-
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-content container-content grow-font">
	    <?php ign_header_block(); ?>
		<?php the_content(); ?>
    </div>
</article>


<?php if (  ! is_page() ): ?>
    <section class="after-article container-content">
		<?php
		the_post_navigation( array(
			'prev_text' => '<span class="screen-reader-text">' . __( 'Previous Post', 'ignition' ) . '</span><div class="nav-title"><span class="nav-title-icon-wrapper"><span class="iconify" data-icon="carbon:chevron-left"></span></span> <span>%title</span></div>',
			'next_text' => '<span class="screen-reader-text">' . __( 'Next Post', 'ignition' ) . '</span><div class="nav-title"><span>%title</span> <span class="nav-title-icon-wrapper"><span class="iconify" data-icon="carbon:chevron-right"></span></span></div>',
		) );
		?>

		<?php // If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;
		?>
    </section>
<?php endif; ?>