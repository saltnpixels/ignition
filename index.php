<?php
/**
 * The main template file
 *
 *
 * It is used to display a page when nothing more specific matches a query.
 * It's also used for archive post type pages, unless a set page is specified in the WP Customizer.
 *
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ignition
 * @since 1.0
 * @version 1.0
 *
 *
 * If using an actual page to represent this archive, an archive page will be found and its ACF sections will be output instead of
 * the loop.
 */


get_header();
?>

<?php
/*
 * With ignition You can use the WP Customizer to choose a page to show its sections for an archive page
 * This way clients can control the archive page instead of needing you to make changes via php.
 * If an archive page is set, then a page will be queried here instead of the archive loop being used. (you can still output the loop as one of the sections using ACF Blocks)
 *
 * For a regular index without a page used the loop will simply be output down below after "else:"
*/

//checking to see if a page has been set to be used with this post type archive
global $post;
$page_archive_id = ign_get_archive_page();

//set if there is a sidebar template on the page used, add the proper divs. divs are taken straight from those templates
$has_sidebar = false;
if ( $page_archive_id ) {
	if ( strpos( get_page_template_slug( $page_archive_id ), 'sidebar-left' ) !== false ) {
		echo '<div class="sidebar-template header-above container-fluid">';
		echo '<div class="flex sidebar-left">';
		$has_sidebar = true;
	}
	if ( strpos( get_page_template_slug( $page_archive_id ), 'sidebar-right' ) !== false ) {
		echo '<div class="sidebar-template header-above container-fluid">';
		echo '<div class="flex sidebar-right">';
		$has_sidebar = true;
	}
}

?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

			<?php
			//if an archive page has been set show it now
			if ( $page_archive_id ) :

				//set the post to the archive page
				$post = get_post( $page_archive_id );
				setup_postdata( $post );

				include( locate_template( 'template-parts/classic-blocks/header_sections.php' ) );
				?>

                <div class="entry-content container-content">
					<?php

					if( has_blocks() ) {
						the_content();
					}else{
						locate_template( 'template-parts/classic-blocks/sections.php', true );
					}

					?>
                </div>
				<?php wp_reset_postdata();
				//END PAGE ARCHIVE
				?>


			<?php
			//BASIC INDEX WHEN NO PAGE USED (DEFAULT)
			else:
				?>

                <header class="page-header layout-center-content text-center">


                    <div class="header-content container-fluid">
                        <h1>
							<?php
							if ( is_home() ) {
								echo __( 'Blog', 'ignition' );
							} else {
								echo get_the_archive_title();
							} ?>
                        </h1>

                    </div>
                </header>

                <div class="container">
                    <section class="archive-cards card-grid">
						<?php
						//default to one section fo cards
						while ( have_posts() ) : the_post();

						ign_loop();

						endwhile; // End of the loop.
						?>
                    </section><!-- .entry-content -->


                    <div class="container card-pagination text-center">
						<?php
						the_posts_pagination( array(
							'prev_text'          => ign_get_svg( array( 'icon' => 'angle-left' ) ) . '<span class="screen-reader-text">' . __( 'Previous page', 'cmlaw' ) . '</span>',
							'next_text'          => '<span class="screen-reader-text">' . __( 'Next page', 'cmlaw' ) . '</span>' . ign_get_svg( array( 'icon' => 'angle-right' ) ),
							'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'cmlaw' ) . ' </span>',
						) );
						?>
                    </div>


                </div>
			<?php endif; ?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
if ( $has_sidebar ) {
	get_sidebar();
	echo '</div></div><!-- #sidebar-template -->';
}
?>
<?php
get_footer();
