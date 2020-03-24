<?php
/**
 * Template Name: Sidebar Left
 * Template Post Type: post, page
 *
 * Used to show a sidebar on the right of the content
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Ignition
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>
	<div class="sidebar-template header-above sidebar-left">
		<div class="container">
			<div class="flex sidebar-left">

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
