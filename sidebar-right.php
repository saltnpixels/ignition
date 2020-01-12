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
	<div class="sidebar-template header-above">
		<div class="container">
			<div class="flex sidebar-right">
				<div id="primary" class="content-area">
					<main id="main" class="site-main" role="main">

						<?php
						/* Start the Loop */
						while ( have_posts() ) : the_post();

							ign_loop();

						endwhile; // End of the loop.
						?>

					</main><!-- #main -->
				</div><!-- #primary -->

				<?php get_sidebar(); ?>

			</div>
		</div>
	</div>

<?php get_footer();
