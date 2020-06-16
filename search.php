<?php
/**
 * The template for displaying search results pages
 * If you want to have this display with a sidebar, uncomment the sidebar out at the bottom.
 * Add the class .page-template-sidebar-right or left to the main element
 * Or just add the pull in sidebar
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Ignition
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <header class="page-header layout-center-content text-center">

                <div class="header-content container-fluid">
                    <?php if ( have_posts() ) : ?>
                        <h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'ignition' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
                    <?php else : ?>
                        <h1 class="page-title"><?php _e( 'Nothing Found', 'ignition' ); ?></h1>
                    <?php endif; ?>
                    <div class="container-content">
                        <?php get_search_form(); ?>
                    </div>
                </div>
            </header>


	        <div class="container search-items">
		        <section class="card-grid">
			        <?php
			        while ( have_posts() ) : the_post();
				       ign_template('card');
			        endwhile; // End of the loop.
			        ?>
		        </section><!-- .entry-content -->

		        <div class="container">
			        <?php

			        the_posts_pagination( array(
				        'prev_text'          => '<span class="iconify" data-icon="carbon:chevron-left"></span><span class="screen-reader-text">' . __( 'Previous page', 'ignition' ) . '</span>',
				        'next_text'          => '<span class="screen-reader-text">' . __( 'Next page', 'ignition' ) . '</span><span class="iconify" data-icon="carbon:chevron-right"></span>',
				        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'ignition' ) . ' </span>',
			        ) );

			        ?>

		        </div>
	        </div>

        </main><!-- #main -->
    </div><!-- #primary -->



<?php get_footer();
