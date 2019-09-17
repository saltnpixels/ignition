<?php
/**
 * The template for displaying all single posts
 * It does not include a sidebar
 *
 * This is the template that displays all posts by default.
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
            while ( have_posts() ) : the_post();

	            /**
	             * Here we look for the right content. We look for a folder with the name of the post type
                 * if it doesn't exist we will use the post folder
	             * This could be replaced with ign_loop_template();
	             */
                if ( ! file_exists( locate_template( 'template-parts/' . get_post_type() ) ) ) {
                    if( get_post_format() && file_exists( locate_template( 'template-parts/post/content-' . get_post_format() . '.php' ) ) ){
	                    include(locate_template( 'template-parts/post/content-' . get_post_format() . '.php' ));
                    }else{
	                    include(locate_template( 'template-parts/post/content.php' ));
                    }

                } else {
                    if ( get_post_format() && file_exists( locate_template( 'template-parts/' . get_post_type() . '/content-' . get_post_format() . '.php' ) ) ) {
                        include(locate_template( 'template-parts/' . get_post_type() . '/content-' . get_post_format() . '.php' ));
                    } else {
                        include(locate_template( 'template-parts/' . get_post_type() . '/content.php' ));
                    }
                }

            endwhile; // End of the loop.
            ?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php get_footer();
