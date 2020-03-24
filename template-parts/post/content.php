<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Ignition
 * @since 1.0
 * @version 1.0
 */

?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php  ign_the_header(); ?>
		<div class="entry-content container-content">
			<?php

			the_content();

			//old acf blocks
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
	</article><!-- #post-## -->

	<section class="after-article container-content">
		<?php
		the_post_navigation( array(
			'prev_text' => '<span class="screen-reader-text">' . __( 'Previous Post', 'ignition' ) . '</span><div class="nav-title"><span class="nav-title-icon-wrapper">' . ign_get_svg( array( 'icon' => 'arrow-left' ) ) . '</span> <span>%title</span></div>',
			'next_text' => '<span class="screen-reader-text">' . __( 'Next Post', 'ignition' ) . '</span><div class="nav-title"><span>%title</span> <span class="nav-title-icon-wrapper">' . ign_get_svg( array( 'icon' => 'arrow-right' ) ) . '</span></div>',
		) );

		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;
		?>
	</section>






