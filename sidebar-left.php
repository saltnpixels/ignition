<?php
/**
 * Template Name: Sidebar Left
 * Template Post Type: post, page
 *
 * Used to show a sidebar on the right of the content
 * The header will be popped out and put above the sidebar-layout, if js is present.
 * classes you can use to make the sidebar look the way you want it:
 * you can add a container to .sidebar-template element to contain your layout.
 * you can also use .container-right and .container-left to get the layout somewhat aligned
 * you can also use .align-content-left to align the content to the left as opposed to being centered.
 * The class header-above pulls the header out of the content column and puts it above the sidebar layout making it full width
 *
 * @package Ignition
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>
    <div class="sidebar-template header-above sidebar-left container">

        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">

				<?php
				/* Start the Loop */
				while ( have_posts() ) : the_post();

					ign_template('content');

				endwhile; // End of the loop.
				?>

            </main><!-- #main -->
        </div><!-- #primary -->

		<?php get_sidebar(); ?>

    </div>

<?php get_footer();
