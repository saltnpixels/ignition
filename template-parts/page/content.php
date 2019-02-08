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
 * full page views and archive views for when the page is being queried.
 * also testing is_single and is_post_type_archive cause some plugins apparently have post types that use page.php
 */

$post_type = get_post_type();
$id        = get_the_ID();

?>


<?php if ( is_page() || is_single() || is_post_type_archive() ) : ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<?php
		//special header layouts can be used with acf, otherwise a fallback header is used.
		//Soon custom header acf-blocks for Gutenberg will be made
		//if this is a sidebar template, js is used to pop out this header and put above the article at full width.
		//this way its semantically placed inside properly, but moved out and looks nicely placed above the sidebar
		locate_template( 'template-parts/acf-blocks/header_sections.php', true );

		?>

        <div class="entry-content container-content">

			<?php

			//include sections made with acf or falls back on the_content.
			locate_template( 'template-parts/acf-blocks/sections.php', true );


			wp_link_pages( array(
				'before'      => '<div class="page-links">' . __( 'Pages:', 'ignition' ),
				'after'       => '</div>',
				'link_before' => '<span class="page-number">',
				'link_after'  => '</span>',
			) );
			?>
        </div><!-- .entry-content -->
    </article><!-- #page-## -->

    <section class="after-article container-content">
		<?php
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;
		?>
    </section>


	<?php ////////////////////////////////////////////// below is used if this is NOT a single page, but an archive or the blog ?>


<?php else: ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>
        <div class="header-image cover-image">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'post-thumbnail', array( 'class' => 'header-image' ) );
			}
			?>
        </div>

        <header class="card-header">
            <div class="header-content">
				<?php echo ign_get_terms(); ?>
				<?php the_title( '<h2 class="card-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
            </div>
        </header>

        <div class="card-content">
			<?php
			the_excerpt();
			?>
        </div><!-- .entry-content -->

        <div class="card-meta">
			<?php echo ign_posted_on(); ?>
			<?php echo ign_comment_link(); ?>
        </div>
    </article><!-- #post-## -->
<?php endif; //end if not single ?>
