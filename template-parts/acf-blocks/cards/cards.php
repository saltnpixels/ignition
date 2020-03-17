<?php
/**
 * Show the loop for this page and output the content or cards
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

/**
 * Reset post data just in case it was touched, or is pointing to something else and then put it back.
 * This can be used to get cards for an archive page.
 */

$block_id       = isset( $block['anchor'] ) ? $block['anchor'] : 'section-' . $block['id'];
$container      = get_field( 'container_class' );
$card_type      = get_field( 'card_type' ) ?: 'default';
$old_post       = $post_id; //saving the current page id
$posts_per_page = get_field( 'posts_per_page' ) ? get_field( 'posts_per_page' ) : 3;
$post_type      = ign_is_page_archive( $post_id );

?>

<section id="<?php echo $block_id; ?>" <?php ign_block_class( $block ); ?>>
	<div class="card-listing <?php echo $container; ?>">
		<div class="<?php echo esc_attr( get_field( 'grid_class' ) ); ?> cards-holder">

			<?php if ( ! $is_preview && $post_type && $card_type == 'default' ) : ?>
				<?php
				//on the front end get the default query
				//if this is an archive page, show that archives listing. we need to change the global post for a few seconds
				wp_reset_postdata(); //set back to the archive listing

				//routes to the right template file
				if ( have_posts() ):
					while ( have_posts() ): the_post();
						ign_loop('card');
					endwhile;

				else :
					if ( file_exists( locate_template( 'template-parts/' . $post_type . '/content-none.php' ) ) ) {
						include( locate_template( 'template-parts/' . $post_type . '/content-none.php' ) );
					} else {
						echo '<p>' . __( 'Nothing has been found here' ) . '</p>';
					}
				endif;
				?>


			<?php elseif ( $is_preview && $post_type && $card_type == 'default' ): ?>
				<?php

				//on the gutenberg editor we need to make a query for the default
				$archive = new WP_Query( array(
					'post_type'      => $post_type,
					'posts_per_page' => (int) $posts_per_page
				) );

				//routes to the right template file
				if ( $archive->have_posts() ): while ( $archive->have_posts() ) :
					$archive->the_post();
					ign_loop('card');
				endwhile;

				else :
					if ( file_exists( locate_template( 'template-parts/' . $post_type . '/content-none.php' ) ) ) {
						include( locate_template( 'template-parts/' . $post_type . '/content-none.php' ) );
					} else {
						echo '<p>' . __( 'Nothing has been found here' ) . '</p>';
					}

				endif;
				?>


			<?php else: //show a simple custom set of cards for a custom query

				$post_type = get_field( 'post_type' ) ? get_field( 'post_type' ) : 'post';

				$cards = new WP_Query( array(
					'post_status'    => 'publish',
					'post_type'      => $post_type,
					'posts_per_page' => (int) $posts_per_page
				) );


				//loop
				if ( $cards->have_posts() ) : ?>
					<?php while ( $cards->have_posts() ) : $cards->the_post(); ?>
						<?php ign_loop('card'); ?>
					<?php endwhile; ?>
				<?php else :

					if ( file_exists( locate_template( 'template-parts/' . $post_type . '/content-none.php' ) ) ) {
						include( locate_template( 'template-parts/' . $post_type . '/content-none.php' ) );
					} else {
						echo '<p>' . __( 'Nothing has been found here' ) . '</p>';
					}
				endif; ?>


			<?php endif; ?>


			<?php

			global $post;
			$post = $old_post; //set back to archive page and continue outputting the right blocks from the right post
			setup_postdata( $post );

			?>

		</div>
	</div>
</section>
