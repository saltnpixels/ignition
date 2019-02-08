<?php
/**
 * Template Name: Sidebar Right
 * Template Post Type: post, page
 *
 * Used to show a sidebar on the right of the content
 * The header will be popped out and put above the sidebar-layout, if js is present.
 *
 * @package Ignition
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>
<div class="sidebar-template header-above container-fluid">
	<div class="flex sidebar-right">
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">

				<?php
				/* Start the Loop */
				while ( have_posts() ) : the_post();

					//if the post type has its own folder it must also have its own set of post format files too.
					if ( ! file_exists( locate_template( 'template-parts/' . get_post_type() ) ) ) {
						if ( get_post_format() ) {
							include( locate_template( 'template-parts/post/content-' . get_post_format() . '.php' ) );
						} else {
							include( locate_template( 'template-parts/post/content.php' ) );
						}

					} else {
						if ( get_post_format() ) {
							include( locate_template( 'template-parts/' . get_post_type() . '/content-' . get_post_format() . '.php' ) );
						} else {
							include( locate_template( 'template-parts/' . get_post_type() . '/content.php' ) );
						}
					}

				endwhile; // End of the loop.
				?>

			</main><!-- #main -->
		</div><!-- #primary -->

		<?php get_sidebar(); ?>

	</div>
	</div>

<?php get_footer();
