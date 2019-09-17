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
    <div class="sidebar-template header-above container">
        <div class="flex sidebar-left">

            <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">

					<?php
					/* Start the Loop */
					while ( have_posts() ) : the_post();
						/**
						 * Here we look for the right content. We look for a folder with the name of the post type
						 * if it doesn't exist we will use the post folder
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

			<?php get_sidebar(); ?>
        </div>
    </div>


<?php get_footer();
