<?php
/**
 * The template for displaying all single posts
 * It does not include a sidebar
 *
 * This is the template that displays all posts by default.
 * New post types can use this and the ign_loop will route it to the right folder in template-parts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ignition
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
	        <?php
	        if ( have_posts() ):
		        while ( have_posts() ) : the_post();
	                ign_template('content');
		        endwhile; // End of the loop.

	        endif;
	        ?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php get_footer();

