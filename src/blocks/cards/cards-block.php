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

$block_id       = ign_get_block_anchor( $block );
$container      = get_field( 'container_class' );
$post_types     = get_field( 'post_types' );
$posts_per_page = get_field( 'posts_per_page' ) ? get_field( 'posts_per_page' ) : 3;
$skip_this_id   = get_field( 'skip_this_id' );

?>

<section <?php ign_block_attrs( $block ); ?>>
    <div class="card-listing <?php echo esc_attr( $container ); ?>">
        <div class="<?php echo esc_attr( get_field( 'grid_class' ) ); ?> cards-holder">
			<?php
			$cards = new WP_Query( array(
				'post_status'    => 'publish',
				'post_type'      => $post_types,
				'posts_per_page' => (int) $posts_per_page
			) );

			if ( $cards->have_posts() ):
				while ( ( $cards->have_posts() ) ): $cards->the_post();
					if ( get_the_ID() == $post_id && $skip_this_id ) {
						continue;
					}
					ign_template( 'card', array( 'block', $block ) );
				endwhile;
			endif;
			wp_reset_postdata();
			?>

        </div>
    </div>
</section>
