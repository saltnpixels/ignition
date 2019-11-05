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

$block_id  = isset( $block['anchor'] ) ? $block['anchor'] : 'section-' . $block['id'];
$container = get_field( 'container_class' );
$card_type = get_field( 'card_type' );
$old_post  = $post_id; //saving the current page id

?>

<section id="<?php echo $block_id; ?>" <?php ign_block_class( $block ); ?>>
	<div class="card-listing <?php echo $container; ?>">
		<div class="<?php echo esc_attr( get_field( 'grid_class' ) ); ?> cards-holder">

			<?php if ( ign_is_page_archive( $post_id ) && $card_type == 'default' ) : ?>
				//if this is an archive page, show that archives listing. we need to change the global post for a few seconds
				<?php

				wp_reset_postdata(); //set back to the archive listing

				//routes to the right template file
				while ( have_posts() ) :
					the_post();
					ign_loop();
				endwhile;


				?>

			<?php else: //show a simple custom set of cards

				$post_type      = get_field( 'post_type' ) ? get_field( 'post_type' ) : 'post';
				$posts_per_page = get_field( 'posts_per_page' ) ? get_field( 'posts_per_page' ) : 3;


				$cards = new WP_Query( array(
					'post_status'    => 'publish',
					'post_type'      => $post_type,
					'posts_per_page' => (int) $posts_per_page
				) );


				//loop
				if ( $cards->have_posts() ) : ?>
					<?php while ( $cards->have_posts() ) : $cards->the_post(); ?>
						<?php ign_loop(); ?>
					<?php endwhile; ?>
				<?php else : ?>
					<?php get_template_part( 'content', 'none' ); ?>
				<?php endif; ?>


			<?php endif; ?>


			<?php

			global $post;
			$post = $old_post; //set back to archive page and continue outputting the right blocks from the right post
			setup_postdata( $post );

			?>

		</div>
	</div>
</section>
