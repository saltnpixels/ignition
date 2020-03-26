<?php
/**
 * The main template file
 *
 *
 * It is used to display a page when nothing more specific matches a query.
 *
 * The index page. Default for any archive not created. Duplicate this for new archive pages for post types and rename to archive-{post-type}.php.
 * For the 'post' post type you can duplicate this and rename it home.php
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Ignition
 * @since   1.0
 * @version 1.0
 *
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php ign_the_header(); ?>

			<div class="container">
				<section class="archive-cards card-grid">
					<?php
					if ( have_posts() ):
						while ( have_posts() ) : the_post();
							ign_loop( 'card' );
						endwhile;
					else:
						//show no content file, or just a message
						$post_type = get_query_var( 'post_type' ) ?: 'post';
						if ( file_exists( locate_template( 'template-parts/' . $post_type . '/content-none.php' ) ) ) {
							include( locate_template( 'template-parts/' . $post_type . '/content-none.php' ) );
						} else {
							//message
							echo '<p>' . __( 'Nothing has been found here' ) . '</p>';
						}
					endif;

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

		</main><!-- #main -->
	</div><!-- #primary -->


<?php
get_footer();
