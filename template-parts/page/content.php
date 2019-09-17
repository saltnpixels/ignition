<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Ignition
 * @since 1.0
 * @version 1.0
 *
 * We have two types of views here.
 * full page views for pages.
 *
 */

?>


    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<?php
		//special header layouts can be used with acf, otherwise a fallback header is used.
		//Soon custom header classic-blocks for Gutenberg will be made
		//if this is a sidebar template, js is used to pop out this header and put above the article at full width.
		//this way its semantically placed inside properly, but moved out and looks nicely placed above the sidebar
		locate_template( 'template-parts/classic-blocks/header_sections.php', true );

		?>

        <div class="entry-content container-content">

			<?php

			//shows blocks or classic blocks
			if( has_blocks() ) {
				the_content();
			}else{
				locate_template( 'template-parts/classic-blocks/sections.php', true );
			}

			wp_link_pages( array(
				'before'      => '<div class="page-links">' . __( 'Pages:', 'ignition' ),
				'after'       => '</div>',
				'link_before' => '<span class="page-number">',
				'link_after'  => '</span>',
			) );
			?>
        </div><!-- .entry-content -->
    </article><!-- #page-## -->
<?php if ( comments_open() || get_comments_number() ) :?>
    <section class="after-article container-content">
		<?php
		// If comments are open or we have at least one comment, load up the comment template.
		comments_template();
		?>
    </section>

<?php endif;  ?>
