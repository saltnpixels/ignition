<?php
/**
 * Template part for displaying page content in page.php
 *
 * For cards see card-content.php as that has been removed from this file.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Ignition
 * @since 1.0
 * @version 1.0
 *
 *
 */

?>


<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php ign_the_header(); ?>
	<div class="entry-content container-content">
		<?php

		the_content();

		//old blocks
		//include( locate_template( 'template-parts/classic-blocks/sections.php' ) );

		//not sure gutenberg eve has this anymore
		wp_link_pages( array(
			'before'      => '<div class="page-links">' . __( 'Pages:', 'ignition' ),
			'after'       => '</div>',
			'link_before' => '<span class="page-number">',
			'link_after'  => '</span>',
		) );

		?>

	</div><!-- .entry-content -->
</article><!-- #page-## -->

<?php if ( comments_open() || get_comments_number() ) : ?>
	<section class="after-article container-content">
		<?php
		// If comments are open or we have at least one comment, load up the comment template.
		comments_template();
		?>
	</section>

<?php endif; ?>
